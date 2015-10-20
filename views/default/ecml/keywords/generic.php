<?php
/**
 * Generic ECML tag output
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// We're given the content encoded, so decode it and spit it out
if (elgg_get_context() != 'search') {
	header('X-XSS-Protection: 0');

	// Workaround for HTTP embeds on HTTPS enabled site
	$decoded = rawurldecode($vars['embed']);

	$search = array(
		"http://prezi.com",
		"http://padlet.com",
		"http://player.vimeo.com",
		"http://www.timetoast.com",
		"http://www.kaltura.com",
		"http://www.youtube.com",
		"http://www.slideshare.net",
		"http://www.mindmeister.com/",
		".html"
	);

	$replace = array(
		"//prezi.com",
		"//padlet.com",
		"//player.vimeo.com",
		"//www.timetoast.com",
		"//www.kaltura.com",
		"//www.youtube.com",
		"//www.slideshare.net",
		"//www.mindmeister.com/",
		""
	);

	echo str_replace($search, $replace, $decoded);
} 
