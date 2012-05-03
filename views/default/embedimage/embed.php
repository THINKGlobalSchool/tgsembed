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

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form',
	'id' => 'embedimage-form',
);


// Register the remove menu item
$params = array(
	'name' => 'remove-from-role',
	'text' => $delete_button,
	'href' => FALSE,
);

elgg_register_menu_item('embedimage-popup-menu', array(
	'name' => 'embedimage-image',
	'text' => elgg_echo('embedimage:label:embedimage'),
	'href' => '#embedimage-module-image',
	'priority' => 0,
	'item_class' => 'elgg-state-selected',
	'class' => 'embedimage-menu-item',
));

elgg_register_menu_item('embedimage-popup-menu', array(
	'name' => 'embedimage-spot',
	'text' => elgg_echo('embedimage:label:embedspotcontent'),
	'href' => '#embedimage-module-spot',
	'priority' => 1,
	'class' => 'embedimage-menu-item',
));

elgg_register_menu_item('embedimage-popup-menu', array(
	'name' => 'embedimage-generic',
	'text' => elgg_echo('embedimage:label:embedcode'),
	'href' => '#embedimage-module-generic',
	'priority' => 2,
	'class' => 'embedimage-menu-item',
));

$menu = elgg_view_menu('embedimage-popup-menu', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz elgg-menu-filter elgg-menu-filter-default'
));

echo $menu;

$content = elgg_view_form('embedimage/upload', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'embedimage-module',
	'id' => 'embedimage-module-image',
));

$content = elgg_view_form('embedimage/spot', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'embedimage-module',
	'id' => 'embedimage-module-spot',
));


$content = elgg_view_form('embedimage/generic', $form_vars);
echo elgg_view_module('aside', '', $content, array(
	'class' => 'embedimage-module',
	'id' => 'embedimage-module-generic',
));

