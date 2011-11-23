<?php
/**
 * ECML Youtube support
 *
 * @package ECML
 */

$src = (isset($vars['src'])) ? $vars['src'] : FALSE;
$width = (isset($vars['width'])) ? $vars['width'] : 480;
$height = (isset($vars['height'])) ? $vars['height'] : 385;

// need to extract the video id.
// the src arg can take a full url or an id.
// assume if no youtube.com that it's an id.
if (strpos($src, 'youtube.com') === FALSE) {
	$vid = $src;
} else {
	// grab the v param
	if ($parts = parse_url($src)) {
		if (isset($parts['query'])) {
			parse_str($parts['query'], $query_arr);
			$vid = (isset($query_arr['v'])) ? $query_arr['v'] : FALSE;
		}
	}
}

if ($vid) {
	$movie_url = "http://www.youtube.com/embed/$vid";
	echo "<iframe width=\"$width\" height=\"$height\" src=\"$movie_url\" frameborder=\"0\" allowfullscreen></iframe>";
}