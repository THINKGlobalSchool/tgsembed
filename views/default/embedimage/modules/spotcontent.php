<?php
/**
 * TGS Embed spot content generic module
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * @uses $vars['user_guid']
 */

$options = array(
	'owner_guid' => $vars['user_guid'],
	'full_view' => FALSE,
	'limit' => 8,
);

set_input('ajaxmodule_listing_type', 'simpleicon');
set_input('embed_spot_content', TRUE);
$items = elgg_list_entities($options);

if (!$items) {
	$items = "<div style='width: 100%; text-align: center; margin: 10px;'><strong>No results</strong></div>";
}

echo $items;