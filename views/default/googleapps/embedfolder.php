<?php
/**
 * TGSEmbed generate google folder embed view
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org
 */

$style = elgg_extract('style', $vars, 'list');

$attrs = array(
	'src' => "https://drive.google.com/embeddedfolderview?id={$vars['id']}#{$style}",
	'height' => $vars['height'],
	'width' => $vars['width'],
	'frameborder' => "0"
);

$format_attrs = elgg_format_attributes($attrs);

$encoded_content = rawurlencode("<iframe {$format_attrs}></iframe>");
echo "[generic embed={$encoded_content}]";

