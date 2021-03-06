<?php
/**
 * TGS Embed Image embedimage object view
 * - Modified version of object/file
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$full = elgg_extract('full_view', $vars, FALSE);
$file = elgg_extract('entity', $vars, FALSE);

if (!$file) {
	return TRUE;
}

$owner = $file->getOwnerEntity();
$container = $file->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($file->description);
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$owner_link = elgg_view('output/url', array(
	'href' => "file/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$tags = elgg_view('output/tags', array('tags' => $file->tags));
$date = elgg_view_friendly_time($file->time_created);

$comments_count = $file->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $file->getURL() . '#file-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'embedimage',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {
	$header = elgg_view_title($file->title);

	$params = array(
		'entity' => $file,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$file_icon = elgg_view_entity_icon($file, 'large');

	$file_info = elgg_view_image_block('', $list_body);

	echo <<<HTML
$file_info
<div class="file elgg-content">
	<br />
	$file_icon
</div>
HTML;

} elseif (elgg_in_context('gallery')) {
	echo '<div class="tgsembed-gallery-item">';
	echo "<div class='tgsembed-gallery-item-title'><span><a href='{$file->getURL()}'>" . $file->title . "</a></span></div>";
	echo elgg_view_entity_icon($file, 'medium');
	echo "<p class='elgg-subtext'>$owner_link $date</p>";
	echo '</div>';
} else {
	// brief view

	$params = array(
		'entity' => $file,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$file_icon = elgg_view_entity_icon($file, 'small');

	echo elgg_view_image_block($file_icon, $list_body);
}
