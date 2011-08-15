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

	// Register CSS
	$e_css = elgg_get_simplecache_url('css', 'embedimage/css');
	elgg_register_css('elgg.embedimage', $e_css);

	// Register JS libraries
	$e_js = elgg_get_simplecache_url('js', 'embedimage/embedimage');
	elgg_register_js('elgg.embedimage', $e_js);
	
	// Register page handler
	elgg_register_page_handler('embedimage','embedimage_page_handler');

	// Register URL handler
	elgg_register_entity_url_handler('object', 'embedimage', 'embedimage_url');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'embedimage/actions/embedimage';
	elgg_register_action('embedimage/save', "$action_base/save.php");
	elgg_register_action('embedimage/delete', "$action_base/delete.php");

	return TRUE;
}

/**
 * Embedimage page handler
 */
function embedimage_page_handler($page) {

	switch($page[0]) {
		default:
			break;
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

