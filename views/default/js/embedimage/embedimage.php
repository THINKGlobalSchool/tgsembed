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
	// Create lightbox manually
	$(".embedimage-control").fancybox({
		//'modal': true,
		'onComplete': function() {
			elgg.embedimage.initDragDropInput();
		}
	});

	// caches the current textarea id
	$(".embedimage-control").live('click', function() {
		var classes = $(this).attr('class');
		var embedClass = classes.split(/[, ]+/).pop();
		var textAreaId = embedClass.substr(embedClass.indexOf('embedimage-control-') + "embedimage-control-".length);
		elgg.embedimage.textAreaId = textAreaId;
	});

	$('#embedimage-form').live('submit', elgg.embedimage.submit);
}

/**
 * Initialize the drag and drop input
 *
 * @return void
 */
elgg.embedimage.initDragDropInput = function() {
	$('.drag-upload').fileupload({
        dataType: 'json',
		dropZone: $('.embedimage-dropzone'),
        url: elgg.get_site_url() + 'action/embedimage/upload?type=drop',
		drop: function (e, data) {
			$(e.originalEvent.target).removeClass('embedimage-dropzone-drag');
			$(e.originalEvent.target).removeClass('embedimage-dropzone-background');
			$(e.originalEvent.target).addClass('elgg-ajax-loader');
		},
		dragover: function (e, data) {
			$(e.originalEvent.target).addClass('embedimage-dropzone-drag');
		},
        done: function (e, data) {
			if (data.result.output.system_messages) {
				elgg.register_error(data.result.output.system_messages.error);
				elgg.system_message(data.result.output.system_messages.success);
			}
			if (data.result.output.status >= 0) {
				// Insert the image
				elgg.embedimage.insert(data.result.output.title, data.result.output.entity_url, data.result.output.icon_url);
			}
        }
    });
}


/**
 * Inserts data attached to an embed list item in textarea
 *
 * @param string title
 * @param string entity_url
 * @param string icon_url
 * @return void
 */
elgg.embedimage.insert = function(title, entity_url, icon_url) {
	var textAreaId = elgg.embedimage.textAreaId;

	var content = "<a href='" + entity_url + "' title='" + title + "'><img src='" + icon_url + "' /></a>";

	$('#' + textAreaId).val($('#' + textAreaId).val() + ' ' + content + ' ');

	<?php echo elgg_view('embed/custom_insert_js'); ?>

	$.fancybox.close();
}

/**
 * Submit the image upload form through Ajax
 *
 * @param {Object} event
 * @return bool
 */
elgg.embedimage.submit = function(event) {
	// Show loader
	$('#embedimage-foot').addClass('elgg-ajax-loader');

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
					// Insert the image
					elgg.embedimage.insert(json_response.title, json_response.entity_url, json_response.icon_url);
				} else {
					// Close the box
					$.fancybox.close();
				}
			}
		}
	});

	event.preventDefault();
	event.stopPropagation();
	return false;
}

elgg.register_hook_handler('init', 'system', elgg.embedimage.init);
//</script>