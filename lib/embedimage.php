<?php
/**
 * TGS Embed Image helper functions
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

/**
 * Get page components to list a user's embed images
 *
 * @return array
 */
function embedimage_get_page_content_list() {

	$return = array();

	$options = array(
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'type' => 'object',
		'subtype' => 'embedimage',
		'full_view' => FALSE,
	);

	$return['filter'] = FALSE;
	$return['title'] = elgg_echo('embedimage:title:embedimages');

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$return['content'] = elgg_echo('embedimage:label:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
}