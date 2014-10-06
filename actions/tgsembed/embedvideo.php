<?php
/**
 * TGS Embed get video embed code
 * 
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('video_guid');
$height = get_input('video_height');
$width = get_input('video_width');

if (!is_int((int)$height)) {
	$height = 540;
}

if (!is_int((int)$width)) {
	$width = 725;
}

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	register_error(elgg_echo('simplekaltura:error:notfound'));
} else {
	$view_video_label = elgg_echo('simplekaltura:label:viewvideo');
	$encoded_content = rawurlencode(elgg_view('simplekaltura/widget', array(
		'custom_uiconfid' => '10201381', // Embed specific player
		'entity' => $video, 
		'width' => $width,
		'height' => $height,
	)) ."<br /><a href='{$video->getURL()}'>{$view_video_label}</a>");
	echo "[generic embed={$encoded_content}]";
}
forward(REFERER);