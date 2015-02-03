<?php
/**
 * TGS Embed Image JS
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
//<script>
elgg.provide('elgg.tgsembed');

// Init function
elgg.tgsembed.init = function() {	
	elgg.tgsembed.initLightbox();

	// Make tgsembed popup tabs clickable
	$(document).delegate('.tgsembed-menu-item', 'click', elgg.tgsembed.menuclick);

	// Form submit handler
	$(document).delegate('#tgsembed-image-form', 'submit', elgg.tgsembed.submit);
	
	// Click handler for 'insert link' spot content click
	$(document).delegate('.tgsembed-add-spotcontent', 'click', elgg.tgsembed.spotContentClick);
	
	// Click handler for 'embed photo' click
	$(document).delegate('.tgsembed-embed-photo', 'click', elgg.tgsembed.embedPhotoClick);

	// Click handler for 'embed video' click
	$(document).delegate('.tgsembed-embed-video-initial', 'click', elgg.tgsembed.embedVideoInitialClick);

	// Click handler for 'embed' click (second stage of video embed)
	$(document).delegate('.tgsembed-embed-video-final', 'click', elgg.tgsembed.embedVideoFinalClick);

	// Click handler for 'embed video' click
	$(document).delegate('.tgsembed-embed-podcast', 'click', elgg.tgsembed.embedPodcastClick);

	// Change handler for content subtype change
	$(document).delegate('#tgsembed-spotcontent-subtype-selector', 'change', elgg.tgsembed.spotContentSubtypeChange);
}

/**
 * Init lightboxes (can be called manually)
 */
elgg.tgsembed.initLightbox = function() {
	$('.tgsembed-control').colorbox({
		'onComplete' : function() {
			elgg.tgsembed.initDragDropInput();
			$(this).colorbox.resize();
		},
		'onOpen' : function() {
			$(this).removeClass('cboxElement');
			var classes = $(this).attr('class');
			var embedClass = classes.split(/[, ]+/).pop();
			var textAreaId = embedClass.substr(embedClass.indexOf('tgsembed-control-') + "tgsembed-control-".length);
			elgg.tgsembed.textAreaId = textAreaId;
		},
		'onClosed' : function() {
			$(this).addClass('cboxElement');
		},
		'className': 'tgsembed-colorbox'
	});	
}

/**
 * Initialize the drag and drop input
 *
 * @return void
 */
elgg.tgsembed.initDragDropInput = function() {
	$('.drag-upload').fileupload({
        dataType: 'json',
		dropZone: $('.tgsembed-image-dropzone'),
        url: elgg.get_site_url() + 'action/tgsembed/upload?type=drop',
		drop: function (e, data) {
			$(e.originalEvent.target).removeClass('tgsembed-image-dropzone-drag');
			$(e.originalEvent.target).removeClass('tgsembed-image-dropzone-background');
			$(e.originalEvent.target).addClass('elgg-ajax-loader');
		},
		dragover: function (e, data) {
			$(e.originalEvent.target).addClass('tgsembed-image-dropzone-drag');
		},
        done: function (e, data) {
			if (data.result.output.system_messages) {
				elgg.register_error(data.result.output.system_messages.error);
				elgg.system_message(data.result.output.system_messages.success);
			}
			if (data.result.output.status >= 0) {
				// Insert the image
				elgg.tgsembed.insertImage(data.result.output.title, data.result.output.entity_url, data.result.output.icon_url);
			} else {
				$.colorbox.close()
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
elgg.tgsembed.insert = function(content) {
	var textAreaId = elgg.tgsembed.textAreaId;
	$('#' + textAreaId).val($('#' + textAreaId).val() + ' ' + content + ' ');

	<?php echo elgg_view('embed/custom_insert_js'); ?>

	$.colorbox.close()
}

/**
 * Wrapper function to build image content to insert
 *
 * @param string title
 * @param string entity_url
 * @param string icon_url
 */
elgg.tgsembed.insertImage = function(title, entity_url, icon_url) {
	var content = "<a href='" + entity_url + "' title='" + title + "'><img src='" + icon_url + "' /></a>";
	elgg.tgsembed.insert(content);
}


/**
 * Wrapper function to build spot entity content to insert
 *
 * @param string title
 * @param string entity_url
 */
elgg.tgsembed.insertLink = function(title, entity_url) {
	var content = "<a href='" + entity_url + "' title='" + title + "'>" + title + "</a>";
	elgg.tgsembed.insert(content);
}

/**
 * Submit the image upload form through Ajax
 *
 * @param {Object} event
 * @return bool
 */
elgg.tgsembed.submit = function(event) {
	// Show loader
	$('#tgsembed-foot').addClass('elgg-ajax-loader');

	$(this).ajaxSubmit({
		//type	: 'POST',
		dataType : 'json',
		data     : { 'X-Requested-With' : 'XMLHttpRequest'},
		success  : function(response) {
			if (response.output) {
				if (response.output.system_messages) {
					elgg.register_error(response.output.system_messages.error);
					elgg.system_message(response.output.system_messages.success);
				}
				if (response.output.status >= 0) {
					// Insert the image
					elgg.tgsembed.insertImage(response.output.title, response.output.entity_url, response.output.icon_url);
				} else {
					// Close the box
					$.colorbox.close();
				}
			}
		}
	});

	event.preventDefault();
	event.stopPropagation();
	return false;
}

// Click handler for menu items
elgg.tgsembed.menuclick = function(event) {
	$('.tgsembed-menu-item').parent().removeClass('elgg-state-selected');
	$('.tgsembed-module').hide();

	$(this).parent().addClass('elgg-state-selected');
	$($(this).attr('href')).show();
	
	$.colorbox.resize();

	event.preventDefault();
}

// Click handler for 'insert link' click
elgg.tgsembed.spotContentClick = function(event) {
	if (!$(this).hasClass('disabled')) {
		// href will be #{guid}
		var entity_guid = $(this).attr('href').substring(1);

		$(this).addClass('disabled');

		$_this = $(this);
		
		elgg.action('tgsembed/entityinfo', {
			data: {
				guid: entity_guid,
			},
			success: function(data) {
				if (data.status != -1) {
					// Insert link to content
					elgg.tgsembed.insertLink(data.output.entity_title, data.output.entity_url);
				} else {
					// Error
					$_this.removeClass('disabled');
				}
			}
		});
	}
	event.preventDefault();
}

// Click handler for 'embed photo' click
elgg.tgsembed.embedPhotoClick = function(event) {
	if (!$(this).hasClass('disabled')) {
		// href will be #{guid}
		var entity_guid = $(this).attr('href').substring(1);

		$(this).addClass('disabled');

		$_this = $(this);
		
		elgg.action('tgsembed/entityinfo', {
			data: {
				guid: entity_guid,
			},
			success: function(data) {
				if (data.status != -1) {
					// Insert link to content
					elgg.tgsembed.insertImage(data.output.entity_title, data.output.entity_url, data.output.icon_url);
				} else {
					// Error
					$_this.removeClass('disabled');
				}
			}
		});
	}
	event.preventDefault();
}

// Click handler for 'embed video' click
elgg.tgsembed.embedVideoInitialClick = function(event) {
	if (!$(this).hasClass('disabled')) {
		// href will be #{guid}
		var entity_guid = $(this).attr('href').substring(1);

		$(this).addClass('disabled');

		$_this = $(this).clone()
			.removeClass('disabled')
			.removeClass('tgsembed-embed-video-initial')
			.addClass('tgsembed-embed-video-final');

		$(this).replaceWith($_this);

		var $dim_container = $(document.createElement('div')).addClass('tgsembed-video-dimensions');

		var $h = $(document.createElement('span'))
			.html('height:');
		$h.appendTo($dim_container);

		var $h_input = $(document.createElement('input'))
			.attr('type', 'text')
			.val('540')
			.attr('maxlength', 4)
			.addClass('video-height')
		$h_input.appendTo($dim_container);

		var $w = $(document.createElement('span'))
			.html('width:');
		$w.appendTo($dim_container);

		var $w_input = $(document.createElement('input'))
			.attr('type', 'text')
			.val('725')
			.attr('maxlength', 4)
			.addClass('video-width')
		$w_input.appendTo($dim_container);

		$menu_item = $(document.createElement('li'))
			.addClass('elgg-menu-item-embed-video-dimensions');

		$dim_container.appendTo($menu_item);

		$_this.closest('ul.elgg-menu').prepend($menu_item);

	}
	event.preventDefault();
}

// Click handler for 'embed video' click
elgg.tgsembed.embedVideoFinalClick = function(event) {
	if (!$(this).hasClass('disabled')) {
		// href will be #{guid}
		var entity_guid = $(this).attr('href').substring(1);

		$(this).addClass('disabled');

		$_this = $(this);

		// Get height and width
		var height = $_this.closest('ul.elgg-menu').find('.elgg-menu-item-embed-video-dimensions input.video-height').val();
		var width = $_this.closest('ul.elgg-menu').find('.elgg-menu-item-embed-video-dimensions input.video-width').val();

		// Get embed
		elgg.action('tgsembed/embedvideo', {
			data: {
				video_guid: entity_guid,
				internal_embed: true,
				video_height: height,
				video_width: width
			}, 
			success: function(data) {	
				if (data.status != -1) {
					elgg.tgsembed.insert(data.output);
				} else {
					// Error
					$_this.removeClass('disabled');
				}
			},
		});
	}
	event.preventDefault();
}

// Click handler for 'embed podcast' click
elgg.tgsembed.embedPodcastClick = function(event) {
	if (!$(this).hasClass('disabled')) {
		// href will be #{guid}
		var entity_guid = $(this).attr('href').substring(1);

		$(this).addClass('disabled');

		$_this = $(this);

		// Get embed
		elgg.action('tgsembed/embedpodcast', {
			data: {
				podcast_guid: entity_guid,
				internal_embed: true,
			}, 
			success: function(data) {	
				if (data.status != -1) {
					elgg.tgsembed.insert(data.output);
				} else {
					// Error
					$_this.removeClass('disabled');
				}
			},
		});
	}
	event.preventDefault();
}

// Change handler for content subtype
elgg.tgsembed.spotContentSubtypeChange = function(event) {
	$module = $('#tgsembed-spotcontent-module');
	var subtype = $(this).val();

	$subtype_input = $module.find('div.options > input#selected_subtype');
	$subtype_input.val(subtype);

	elgg.modules.genericmodule.populateContainer($module);
	event.preventDefault();
}

// Require fileupload 
require(['jquery.iframe-transport', 'jquery.fileupload'], function() {
	// Register hooks
	elgg.register_hook_handler('init', 'system', elgg.tgsembed.init);
	elgg.register_hook_handler('photoLightboxAfterShow', 'tidypics', elgg.tgsembed.initLightbox);
});
