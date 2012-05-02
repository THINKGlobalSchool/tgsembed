<?php
/**
 * TGS Embed Image
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

elgg_register_event_handler('init', 'system', 'embedimage_init');

function embedimage_init() {

	// Register and load library
	elgg_register_library('embedimage', elgg_get_plugins_path() . 'embedimage/lib/embedimage.php');
	elgg_load_library('embedimage');

	// Register simplecache view for jQuery File Upload
	elgg_register_simplecache_view('js/jquery_file_upload');

	// Register CSS
	$e_css = elgg_get_simplecache_url('css', 'embedimage/css');
	elgg_register_simplecache_view('css/embedimage/css');
	elgg_register_css('elgg.embedimage', $e_css);
	elgg_load_css('elgg.embedimage');

	// Register JS libraries
	$e_js = elgg_get_simplecache_url('js', 'embedimage/embedimage');
	elgg_register_simplecache_view('js/embedimage/embedimage');
	elgg_register_js('elgg.embedimage', $e_js);

	// Register JS for jquery file upload
	$j_js = elgg_get_simplecache_url('js', 'jquery_file_upload');
	elgg_register_simplecache_view('js/jquery_file_upload');
	elgg_register_js('jQuery-File-Upload', $j_js);
	
	// Register colorbox JS
	$cb_js = elgg_get_simplecache_url('js', 'colorbox');
	elgg_register_simplecache_view('js/colorbox');
	elgg_register_js('colorbox', $cb_js);

	// Register page handler
	elgg_register_page_handler('embedimage','embedimage_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'embedimage', 'embedimage_url');

	// Hook into longtext menu
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embedimage_longtext_menu');

	// Icon override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'embedimage_icon_url_override');

	// Item entity menu hook
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'embedimage_setup_entity_menu', 999);
	
	// Register for pagesetup event
	elgg_register_event_handler('pagesetup', 'system', 'embedimage_pagesetup');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'embedimage/actions/embedimage';
	elgg_register_action('embedimage/upload', "$action_base/upload.php");
	elgg_register_action('embedimage/delete', "$action_base/delete.php");

	/** GENERIC EMBED **/
	// Hook to add new type
	elgg_register_plugin_hook_handler('get_keywords', 'ecml', 'generic_embed_get_keywords');

	return TRUE;
}

/**
 * Embedimage page handler
 */
function embedimage_page_handler($page) {
	elgg_load_css('elgg.embedimage');

	// If ajax request
	if (elgg_is_xhr()) {
		switch($page[0]) {
			case 'embedimage':
			default:
				echo elgg_view('embedimage/embed');
				break;
		}
	} else {
		switch($page[0]) {
			case 'view':
			 	$params = embedimage_get_page_content_view($page[1]);
				break;
			case 'all':
			default:
				$params = embedimage_get_page_content_list();
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
function embedimage_url($entity) {
	$title = $entity->title;
	$title = elgg_get_friendly_title($title);
	return "embedimage/view/" . $entity->getGUID() . "/" . $title;
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
function embedimage_longtext_menu($hook, $type, $items, $vars) {
	if (elgg_get_context() == 'embedimage') {
		return $items;
	}

	$items[] = ElggMenuItem::factory(array(
		'name' => 'embedimage',
		'href' => "embedimage",
		'text' => elgg_echo('embedimage:label:embedcontent'),
		'link_class' => "elgg-longtext-control embedimage-control embedimage-control-{$vars['id']}",
		'priority' => 10,
		'title' => elgg_view_title(elgg_echo('embedimage:label:embedcontent')),
	));

	elgg_load_js('colorbox');
	elgg_load_js('jQuery-File-Upload');
	elgg_load_js('elgg.embedimage');

	return $items;
}

/**
 * Set up the menu for user settings
 *
 * @return void
 */
function embedimage_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		$params = array(
			'name' => 'embed_images',
			'text' => elgg_echo('embedimage:title:embedimages'),
			'href' => "embedimage/all",
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
function embedimage_icon_url_override($hook, $type, $returnvalue, $params) {
	$file = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($file, 'object', 'embedimage')) {
		// thumbnails get first priority
		if ($file->thumbnail) {
			return "mod/embedimage/thumbnail.php?file_guid=$file->guid&size=$size";
		}
	}
}

/**
 * Item entity plugin hook
 */
function embedimage_setup_entity_menu($hook, $type, $return, $params) {
	$entity = $params['entity'];
	if (!elgg_instanceof($entity, 'object', 'embedimage')) {
		return $return;
	}

	// Nuke all items
	return array();
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
		'description' => 'Embed Generic Content',
		'usage' => 'Only usable from the embed interface',
		'type' => 'generic',
		'params' => array('embed'), // a list of supported params
		'embed_format' => 'embed="%s"' // a sprintf string of the require param format. Added automatically to [keyword $here]
	);

	return $value;
}
