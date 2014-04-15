<?php
/**
 * TGS Embed get podcast embed code
 * 
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('podcast_guid');

$podcast = new ElggPodcast($guid);

if (!elgg_instanceof($podcast, 'object', 'podcast')) {
	register_error(elgg_echo('podcasts:invaldepisode'));
} else {
	$encoded_content = rawurlencode(elgg_view('podcasts/player', array('entity_guid' => $guid)));
	echo "[generic embed={$encoded_content}]";
}
forward(REFERER);