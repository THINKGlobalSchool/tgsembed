<?php
/**
 * TGS Embed spot content generic module
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['user_guid']
 */

// See if we have a selected subtype
$selected_subtype = elgg_extract('selected_subtype', $vars);

// If its empty or 'all' use all embeddable subtypes
if (empty($selected_subtype) || $selected_subtype == 'all') {
	$selected_subtype = FALSE;
}

// Options for elgg_list_entities
$options = array(
	'type' => 'object',
	'owner_guid' => $vars['user_guid'],
	'full_view' => FALSE,
	'limit' => 8,
);

// If we have a subtype, use it, otherwise show all
if (!$selected_subtype) {
	$options['subtypes'] = tgsembed_get_embeddable_subtypes();
} else {
	$options['subtype'] = $selected_subtype;
}

// Output entities
set_input('ajaxmodule_listing_type', 'simpleicon');
set_input('embed_spot_content', TRUE);
$items = elgg_list_entities($options);

if (!$items) {
	$items = "<div style='width: 100%; text-align: center; margin: 10px;'><strong>No results</strong></div>";
}

echo $items;