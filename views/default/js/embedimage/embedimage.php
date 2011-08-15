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

	$('#embedimage-form').live('submit', elgg.embedimage.submit);
}

/**
 * Submit the image upload form through Ajax
 *
 * @param {Object} event
 * @return bool
 */
elgg.embedimage.submit = function(event) {
	// This is kind of gross, I should be setting the datatype and X-Requested-With
	// But this is the only way to get the upload working in all 3 browsers
	$(this).ajaxSubmit({
		type	: 'POST',
		//dataType : 'json',
		//data     : { 'X-Requested-With' : 'XMLHttpRequest'},
		success  : function(response) {
			json_response = eval( "(" + response + ")" );
			if (json_response) {
				if (json_response.system_messages) {
					elgg.register_error(json_response.system_messages.error);
					elgg.system_message(json_response.system_messages.success);
				}
				if (json_response.status >= 0) {
					console.log('done');
				}
			}
		}
	});

	event.preventDefault();
	event.stopPropagation();
	return;
}

elgg.register_hook_handler('init', 'system', elgg.embedimage.init);
//</script>