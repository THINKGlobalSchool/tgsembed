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

	// add a site navigation item
	$item = new ElggMenuItem('embedimage', elgg_echo('embedimage:title:embedimages'), 'embedimage/all');
	elgg_register_menu_item('site', $item);

	// Register CSS
	$e_css = elgg_get_simplecache_url('css', 'embedimage/css');
	elgg_register_css('elgg.embedimage', $e_css);
	elgg_load_css('elgg.embedimage');

	// Register JS libraries
	$e_js = elgg_get_simplecache_url('js', 'embedimage/embedimage');
	elgg_register_js('elgg.embedimage', $e_js);

	// Register page handler
	elgg_register_page_handler('embedimage','embedimage_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'embedimage', 'embedimage_url');

	// Hook into longtext menu
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'embedimage_longtext_menu');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'embedimage/actions/embedimage';
	elgg_register_action('embedimage/upload', "$action_base/upload.php");
	elgg_register_action('embedimage/delete', "$action_base/delete.php");

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
			case 'all':
			default:
				$title = elgg_echo('blog:title:all_blogs');
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
	return "not implemented";
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
		'text' => elgg_echo('embedimage'),
		'rel' => 'lightbox',
		'link_class' => "elgg-longtext-control elgg-lightbox embedimage-control embedimage-control-{$vars['id']}",
		'priority' => 10,
	));

	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	elgg_load_js('elgg.embedimage');

	return $items;
}

