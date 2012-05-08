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

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form',
	'id' => 'tgsembed-image-form',
);


// Register the remove menu item
$params = array(
	'name' => 'remove-from-role',
	'text' => $delete_button,
	'href' => FALSE,
);

elgg_register_menu_item('tgsembed-popup-menu', array(
	'name' => 'tgsembed-image',
	'text' => elgg_echo('tgsembed:label:embedimage'),
	'href' => '#tgsembed-module-image',
	'priority' => 0,
	'item_class' => 'elgg-state-selected',
	'class' => 'tgsembed-menu-item',
));

elgg_register_menu_item('tgsembed-popup-menu', array(
	'name' => 'tgsembed-spot',
	'text' => elgg_echo('tgsembed:label:embedspotcontent'),
	'href' => '#tgsembed-module-spot',
	'priority' => 1,
	'class' => 'tgsembed-menu-item',
));

elgg_register_menu_item('tgsembed-popup-menu', array(
	'name' => 'tgsembed-generic',
	'text' => elgg_echo('tgsembed:label:embedcode'),
	'href' => '#tgsembed-module-generic',
	'priority' => 2,
	'class' => 'tgsembed-menu-item',
));

$menu = elgg_view_menu('tgsembed-popup-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

echo $menu;

$content = elgg_view_form('tgsembed/upload', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'tgsembed-module',
	'id' => 'tgsembed-module-image',
));

$content = elgg_view_form('tgsembed/spot', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'tgsembed-module',
	'id' => 'tgsembed-module-spot',
));


$content = elgg_view_form('tgsembed/generic', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'tgsembed-module',
	'id' => 'tgsembed-module-generic',
));

