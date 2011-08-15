<?php
/**
 * TGS Embed Image delete action
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get inputs
$guid = get_input('guid');

$embedimage = get_entity($guid);

if ($embedimage && $embedimage->getSubtype() == 'embedimage') {
	// Delete item and icon
	if ($embedimage->delete()) {
		// Success
		system_message(elgg_echo('embedimage:success:delete'));
	} else {
		// Error
		register_error(elgg_echo('embedimage:error:delete'));
	}
	forward(REFERER);
}
