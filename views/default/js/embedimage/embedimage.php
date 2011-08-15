<?php
/**
 * TGS Embed Image JS
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
//<script>
elgg.provide('elgg.embedimage');

// Init function
elgg.embedimage.init = function() {	
	console.log('Embed Image JS Loaded');
}

elgg.register_hook_handler('init', 'system', elgg.embedimage.init);
//</script>