<?php
/**
 * Generic ECML tag output
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// We're given the content encoded, so decode it and spit it out
if (elgg_get_context() != 'search') {
	echo urldecode($vars['embed']);
} 
