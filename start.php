<?php
/**
 * TGS Embed Image
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
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

	// Register jquery ui widget (for jquery file upload)
	$js = elgg_get_simplecache_url('js', 'jquery_ui_widget');
	elgg_register_simplecache_view('js/jquery_ui_widget');
	elgg_register_js('jquery.ui.widget', $js);

	// Register JS for jquery file upload
	$j_js = elgg_get_simplecache_url('js', 'jquery_file_upload');
	elgg_register_simplecache_view('js/jquery_file_upload');
	elgg_register_js('jquery-file-upload', $j_js);

	// Register JS for jquery.iframe-transport (for jquery file upload)
	$j_js = elgg_get_simplecache_url('js', 'jquery_iframe_transport');
	elgg_register_simplecache_view('js/jquery_iframe_transport');
	elgg_register_js('jquery.iframe-transport', $j_js);
	
	// Register colorbox JS
	$cb_js = elgg_get_simplecache_url('js', 'colorbox');
	elgg_register_simplecache_view('js/colorbox');
	elgg_register_js('colorbox', $cb_js);

	// Load Form JS
	elgg_load_js('jquery.form');

	// Load podcast JS/CSS if available
	if (elgg_is_active_plugin('podcasts')) {
		// Load JS
		elgg_load_js('elgg.podcasts');
		elgg_load_js('soundmanager2');

		// Load CSS
		elgg_load_css('elgg.podcasts');
	}

	// Register page handler
	elgg_register_page_handler('tgsembed','tgsembed_page_handler');

	// For legacy images
	elgg_register_page_handler('embedimage','tgsembed_page_handler');

	// ecml validator for embed
	elgg_register_page_handler('ecml_generate_generic', 'ecml_generic_page_handler');

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
	elgg_register_plugin_hook_handler('register', 'menu:simpleicon-entity', 'photos_setup_simpleicon_entity_menu');
	elgg_register_plugin_hook_handler('register', 'menu:simpleicon-entity', 'simplekaltura_setup_simpleicon_entity_menu');
	elgg_register_plugin_hook_handler('register', 'menu:simpleicon-entity', 'podcasts_setup_simpleicon_entity_menu');

	// Extend tidypics page handler
	elgg_register_plugin_hook_handler('route', 'photos', 'tgsembed_route_photos_handler');
	
	// Register for pagesetup event
	elgg_register_event_handler('pagesetup', 'system', 'tgsembed_pagesetup');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'tgsembed/actions/tgsembed';
	elgg_register_action('tgsembed/upload', "$action_base/upload.php");
	elgg_register_action('tgsembed/delete', "$action_base/delete.php");
	elgg_register_action('tgsembed/entityinfo', "$action_base/entityinfo.php");
	elgg_register_action('tgsembed/embedvideo', "$action_base/embedvideo.php");
	elgg_register_action('tgsembed/embedpodcast', "$action_base/embedpodcast.php");

	/** GENERIC EMBED **/
	// Hook to add new type
	elgg_register_plugin_hook_handler('render:generic', 'ecml', 'generic_embed_render');

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
 * Generate ECML given a URL or embed link and service.
 * Doesn't check if the resource actually exists.
 * Outputs JSON.
 *
 * @param unknown_type $page
 */
function ecml_generic_page_handler($page) {
	$service = trim(get_input('service'));
	$resource = trim(get_input('resource'));


	if (!$service || !$resource) {
		echo json_encode(array(
			'status' => 'error',
			'message' => elgg_echo('ecml:embed:invalid_web_service_keyword')
		));

		exit;
	}

	// @todo pull this out into a function.  allow optional arguments.
	$ecml = "[$service " . sprintf('embed="%s"', $resource) . ']';
	$result = array(
		'status' => 'success',
		'ecml' => $ecml
	);

	echo json_encode($result);
	exit;
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
	elgg_load_js('jquery.ui.widget');
	elgg_load_js('jquery-file-upload');
	elgg_load_js('jquery.iframe-transport');
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
 * Register 'insert link' item for the simpleicon entity menu
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

/**
 * Add 'embed photo' item for photo simpleicon entity menu
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function photos_setup_simpleicon_entity_menu($hook, $type, $return, $params) {
	if (get_input('embed_spot_content')) {
		$entity = $params['entity'];
		
		if (elgg_instanceof($entity, 'object', 'image')) {
			// Item to add object to portfolio
			$options = array(
				'name' => 'embed_photo',
				'text' => elgg_echo('tgsembed:label:embedphoto'),
				'title' => 'embed_photo',
				'href' => "#{$entity->guid}",
				'class' => 'tgsembed-embed-photo elgg-button elgg-button-action',
				'section' => 'info',
			);
			
			$return[] = ElggMenuItem::factory($options);
			return $return;
		}
	}
	return $return;
}

/**
 * Add 'embed video' item for video simpleicon entity menu
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function simplekaltura_setup_simpleicon_entity_menu($hook, $type, $return, $params) {
	if (get_input('embed_spot_content')) {
		$entity = $params['entity'];
		
		if (elgg_instanceof($entity, 'object', 'simplekaltura_video')) {
			// Item to add object to portfolio
			$options = array(
				'name' => 'embed_video',
				'text' => elgg_echo('tgsembed:label:embedvideo'),
				'title' => 'embed_video',
				'href' => "#{$entity->guid}",
				'class' => 'tgsembed-embed-video-initial elgg-button elgg-button-action',
				'section' => 'info',
				'priority' => 1,
			);
			
			$return[] = ElggMenuItem::factory($options);
			return $return;
		}
	}
	return $return;
}

/**
 * Add 'embed podcast' item for podcast simpleicon entity menu
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function podcasts_setup_simpleicon_entity_menu($hook, $type, $return, $params) {
	if (get_input('embed_spot_content')) {
		$entity = $params['entity'];
		
		if (elgg_instanceof($entity, 'object', 'podcast') || elgg_instanceof($entity, 'object', 'file')) {
			
			if (elgg_instanceof($entity, 'object', 'file')) {
				elgg_load_library('elgg:podcasts');
				$mimetype = podcasts_get_mime_type($entity->getFilenameOnFilestore());
				if (!ElggPodcast::checkValidMimeType($mimetype)) {
					return $return;
				}
			}

			// Item to add object to portfolio
			$options = array(
				'name' => 'embed_podcast',
				'text' => elgg_echo('tgsembed:label:embed' . $entity->getSubtype()),
				'title' => 'embed_podcast',
				'href' => "#{$entity->guid}",
				'class' => 'tgsembed-embed-podcast elgg-button elgg-button-action',
				'section' => 'info',
				'priority' => 1,
			);
			
			$return[] = ElggMenuItem::factory($options);
			return $return;
		}
	}
	return $return;
}


/**
 * Extend photos pagehandler to include tgsembed js
 *
 * @param string $hook
 * @param string $type
 * @param bool   $return
 * @param array  $params
 * @return mixed
 */
function tgsembed_route_photos_handler($hook, $type, $return, $params) {
	elgg_load_js('colorbox');
	elgg_load_js('jquery.ui.widget');
	elgg_load_js('jquery-file-upload');
	elgg_load_js('jquery.iframe-transport');
	elgg_load_js('elgg.tgsembed');
	return $return;
}

/** GENERIC EMBED **/
/**
 * Plugin hook to add render 'generic' ECML keyword
 *
 * @param $hook
 * @param $type
 * @param $value
 * @param $params
 * @return mixed $value
 */
function generic_embed_render($hook, $type, $value, $params) {
	return elgg_view('ecml/keywords/generic', $params['attributes']);
}

/**
 * Only run this once
 */
function tgsembed_run_once() {
	elgg_add_admin_notice('embed_rewrite_rule', "Warning: You need to add the following RewriteRule to Elgg's .htaccess, otherwise old embedded images will not work! <br /><br />RewriteRule ^mod/embedimage/(.*)   mod/tgsembed/$1");
}
