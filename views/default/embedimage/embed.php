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
$content = elgg_view_form('embedimage/upload', $form_vars);


echo elgg_view_module('info', elgg_echo('embedimage'), $content, array(
	'class' => 'embedimage-module',
	'id' => 'embedimage',
));