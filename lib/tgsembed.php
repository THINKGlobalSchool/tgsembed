<?php
/**
 * TGS Embed Image helper functions
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.com/
 * 
 */

/**
 * Get page components to list a user's embed images
 *
 * @return array
 */
function tgsembed_get_page_content_list() {

	$return = array();

	$options = array(
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'type' => 'object',
		'subtype' => 'embedimage',
		'list_type' => 'gallery',
		'full_view' => FALSE,
		'limit' => 12,
	);

	$return['filter'] = FALSE;
	$return['title'] = elgg_echo('tgsembed:title:embedimages');

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$return['content'] = elgg_echo('tgsembed:label:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
}

/**
 * Get page components to display a single embedded image
 * 
 * @param int $guid Guid of the image
 * @return array
 */
function tgsembed_get_page_content_view($guid) {
	if (!elgg_is_logged_in()) {
		forward(REFERER);
	}
	$embedimage = get_entity($guid);

	$owner = get_entity($embedimage->owner_guid);

	elgg_push_breadcrumb(elgg_echo('tgsembed:title:embedimages'), 'tgsembed/all');

	$title = $embedimage->title;

	elgg_push_breadcrumb($title);

	$content = elgg_view_entity($embedimage, array('full_view' => true));

	$return['filter'] = FALSE;
	$return['title'] = $title;
	$return['content'] = $content;
	return $return;
}

/**
 * Get 'embeddable' spot content subtypes
 * 
 * @return array
 */
function tgsembed_get_embeddable_subtypes() {
	$registered_entities = elgg_get_config('registered_entities');

	// Build array for embeddable subtypes
	$embeddable_subtypes = array();

	// Exceptions array
	$exceptions = array(
		'thewire',
		'site_activity',
		'connected_blog_activity',
	);

	// Loop over registered entities
	if (!empty($registered_entities)) {
		foreach ($registered_entities as $type => $subtypes) {
			// subtype will always be an array.
			if (count($subtypes)) {
				foreach ($subtypes as $subtype) {
					// If not in exceptions, add it to embeddable
					if (!in_array($subtype, $exceptions)) {
						$embeddable_subtypes[] = $subtype;
					}
				}
			}
		}
	}
	return $embeddable_subtypes;
}

/**
 * Get embeddable subtype dropdown options
 * 
 * @return array
 */
function tgsembed_get_embeddable_dropdown() {
	$options = array('all' => 'Show All');
	$embeddable_subtypes = tgsembed_get_embeddable_subtypes();
	foreach ($embeddable_subtypes as $subtype) {
		$label = elgg_echo('river:select', array(elgg_echo("item:object:$subtype")));
		$options[$subtype] = $label;
	}
	return $options;
}

/**
 * Helper function to remove all references of 'generic embed' from a string
 */
function tgsembed_filter_generic($string) {
	$text = preg_replace('/\[generic[^\]]*\]/', '', $string);
	$text = str_replace('[generic...', '', $text);
	return $text;
}