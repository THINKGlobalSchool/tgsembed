<?php
/**
 * TGS Embed Image embedimage icon object view
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$title = $entity->title;
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if (isset($vars['img_class'])) {
	$class = $vars['img_class'];
}
if ($entity->thumbnail) {
	$class = "class=\"elgg-photo $class\"";
} else if ($class) {
	$class = "class=\"$class\"";
}

$img_src = $entity->getIconURL($vars['size']);
$img_src = elgg_format_url($img_src);

$master_src = $entity->getIconURL('master');

$img = "<img $class src=\"$img_src\" alt=\"$title\" />";

if ($master_src) {
	$params = array(
		'href' => $master_src,
		'text' => $img,
		'is_trusted' => true,
	);
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
	echo $img;
}