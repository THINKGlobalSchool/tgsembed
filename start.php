<?php
/**
 * TGS Embed Image
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

elgg_register_event_handler('init', 'system', 'tgsembed_init');

function tgsembed_init() {

	// Register and load library
	elgg_register_library('tgsembed', elgg_get_plugins_path() . 'tgsembed/lib/tgsembed.php');
	elgg_load_library('tgsembed');

	// Register simplecache view for jQuery File Upload
	elgg_register_simplecache_view('js/jquery_file_upload');

	// Register CSS
	$e_css = elgg_get_simplecache_url('css', 'tgsembed/css');
	elgg_register_simplecache_view('css/tgsembed/css');
	elgg_register_css('elgg.tgsembed', $e_css);
	elgg_load_css('elgg.tgsembed');

	// Register JS libraries
	$e_js = elgg_get_simplecache_url('js', 'tgsembed/tgsembed');
	elgg_register_simplecache_view('js/tgsembed/tgsembed');
	elgg_register_js('elgg.tgsembed', $e_js);

	// Register JS for jquery file upload
	$j_js = elgg_get_simplecache_url('js', 'jquery_file_upload');
	elgg_register_simplecache_view('js/jquery_file_upload');
	elgg_register_js('jQuery-File-Upload', $j_js);
	
	// Register colorbox JS
	$cb_js = elgg_get_simplecache_url('js', 'colorbox');
	elgg_register_simplecache_view('js/colorbox');
	elgg_register_js('colorbox', $cb_js);

	// Register page handler
	elgg_register_page_handler('tgsembed','tgsembed_page_handler');

	// For legacy images
	elgg_register_page_handler('embedimage','tgsembed_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'embedimage', 'tgsembed_url');

	// Hook into longtext menu
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'tgsembed_longtext_menu');

	// Icon override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'tgsembed_icon_url_override');

	// Item entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'tgsembed_setup_entity_menu', 999);
	
	// Register simpleicon menu items
	elgg_register_plugin_hook_handler('register', 'menu:simpleicon-entity', 'tgsembed_setup_simpleicon_entity_menu');
	
	// Register for pagesetup event
	elgg_register_event_handler('pagesetup', 'system', 'tgsembed_pagesetup');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'tgsembed/actions/tgsembed';
	elgg_register_action('tgsembed/upload', "$action_base/upload.php");
	elgg_register_action('tgsembed/delete', "$action_base/delete.php");
	elgg_register_action('tgsembed/entityinfo', "$action_base/entityinfo.php");

	/** GENERIC EMBED **/
	// Hook to add new type
	elgg_register_plugin_hook_handler('get_keywords', 'ecml', 'generic_embed_get_keywords');
	
	// Register Ajax Views
	elgg_register_ajax_view('tgsembed/modules/spotcontent');
	
	// Run once
	run_function_once("tgsembed_run_once");

	return TRUE;
}

/**
 * Tgsembed page handler
 */
function tgsembed_page_handler($page) {
	elgg_load_css('elgg.tgsembed');

	// If ajax request
	if (elgg_is_xhr()) {
		switch($page[0]) {
			case 'tgsembed':
			default:
				echo elgg_view('tgsembed/embed');
				break;
		}
	} else {
		switch($page[0]) {
			case 'view':
			 	$params = tgsembed_get_page_content_view($page[1]);
				break;
			case 'thumbnail':
				include dirname(__FILE__) . '/thumbnail.php';
				break;
			case 'all':
			default:
				$params = tgsembed_get_page_content_list();
				break;
		}

		$body = elgg_view_layout('content', $params);

		echo elgg_view_page($params['title'], $body);
	}

	return TRUE;
}

/**
 * Populates the ->getUrl() method for embedimage entities
 *
 * @param ElggObject entity
 * @return string request url
 */
function tgsembed_url($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "tgsembed/view/" . $entity->getGUID() . "/" . $title;
}

/**
 * Add the embed image menu item to the long text menu
 *
 * @param string $hook
 * @param string $type
 * @param array $items
 * @param array $vars
 * @return array
 */
function tgsembed_longtext_menu($hook, $type, $items, $vars) {
	if (elgg_get_context() == 'embedimage') {
		return $items;
	}

	$items[] = ElggMenuItem::factory(array(
		'name' => 'tgsembed',
		'href' => "tgsembed",
		'text' => elgg_echo('tgsembed:label:insertcontent'),
		'link_class' => "elgg-longtext-control tgsembed-control tgsembed-control-{$vars['id']}",
		'priority' => 10,
		'title' => elgg_view_title(elgg_echo('tgsembed:label:insertcontent')),
	));

	elgg_load_js('colorbox');
	elgg_load_js('jQuery-File-Upload');
	elgg_load_js('elgg.tgsembed');

	return $items;
}

/**
 * Set up the menu for user settings
 *
 * @return void
 */
function tgsembed_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => 'embed_images',
			'text' => elgg_echo('tgsembed:title:embedimages'),
			'href' => "tgsembed/all",
		);
		elgg_register_menu_item('page', $params);
	}
}

/**
 * Override the default entity icon for files
 *
 * Plugins can override or extend the icons using the plugin hook: 'file:icon:url', 'override'
 *
 * @return string Relative URL
 */
function tgsembed_icon_url_override($hook, $type, $returnvalue, $params) {
	$file = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'embedimage')) {
		// thumbnails get first priority
		if ($file->thumbnail) {
			return elgg_get_site_url() . "tgsembed/thumbnail?file_guid=$file->guid&size=$size";
			//return "mod/tgsembed/thumbnail.php?file_guid=$file->guid&size=$size";
		}
	}
}

/**
 * Item entity plugin hook
 */
function tgsembed_setup_entity_menu($hook, $type, $return, $params) {
	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'object', 'embedimage')) {
		return $return;
	}

	// Nuke all items
	return array();
}

/**
 * Register items for the simpleicon entity menu
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function tgsembed_setup_simpleicon_entity_menu($hook, $type, $return, $params) {
	if (get_input('embed_spot_content')) {
		$entity = $params['entity'];
		
		// Item to add object to portfolio
		$options = array(
			'name' => 'link_content',
			'text' => elgg_echo('tgsembed:label:insertlink'),
			'title' => 'link_content',
			'href' => "#{$entity->guid}",
			'class' => 'tgsembed-add-spotcontent elgg-button elgg-button-action',
			'section' => 'info',
		);
		$return[] = ElggMenuItem::factory($options);
	}
	return $return;
}

/** GENERIC EMBED **/
/**
 * Plugin hook to add new 'generic' ECML keyword (won't work on its own..)
 *
 * @param $hook
 * @param $type
 * @param $value
 * @param $params
 * @return mixed $value
 */
function generic_embed_get_keywords($hook, $type, $value, $params) {
	$value['generic'] = array(
		'name' => 'Generic Embed',
		'view' => "ecml/keywords/generic",
		'description' => 'Embed Generic Code',
		'usage' => 'Only usable from the embed interface',
		'type' => 'generic',
		'params' => array('embed'), // a list of supported params
		'embed_format' => 'embed="%s"' // a sprintf string of the require param format. Added automatically to [keyword $here]
	);

	return $value;
}

/**
 * Only run this once
 */
function tgsembed_run_once() {
	elgg_add_admin_notice('embed_rewrite_rule', "Warning: You need to add the following RewriteRule to Elgg's .htaccess, otherwise old embedded images will not work! <br /><br />RewriteRule ^mod/embedimage/(.*)   mod/tgsembed/$1");
}
