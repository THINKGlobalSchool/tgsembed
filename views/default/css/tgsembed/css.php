<?php
/**
 * TGS Embed Image CSS
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>

.tgsembed-module {
	width: 730px;
}

#tgsembed-module-image {

}

#tgsembed-module-generic,
#tgsembed-module-spot {
	display: none;
}

.tgsembed-gallery-item {
	height: 225px;
    overflow: hidden;
	float: left;
	text-align: center;
	width: 165px;
	background-color:#EEEEEE;
	padding: 5px;
	margin: 2px;
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
	border-radius: 6px;
}

.tgsembed-gallery-item-title  {
	overflow: hidden;
	display: block;
	height:32px;
	width: 154px;
}
.tgsembed-gallery-item-title span {
	font-weight:bold;
	line-height: 1.2em;
	height:32px;
	width: 154px;
	vertical-align: middle;
	text-align: center;
	display: table-cell;
}


.tgsembed-gallery-item:hover {
	background-color: #CCCCCC;
}

.tgsembed-gallery-item h3 {
	margin-bottom: 5px;
}

.drag-upload {
	display: none;
}

.tgsembed-image-dropzone {
	-webkit-border-radius: 26px;
	-moz-border-radius: 26px;
	border-radius: 26px;
	margin-left: 15px;
	margin-right: 15px;
	margin-bottom: 10px;
	width: 200px;
	height: 150px;
}

.tgsembed-image-dropzone-background {
	background-image: url('<?php echo elgg_get_site_url() . 'mod/tgsembed/_graphics/dropzone.png' ?>');
}

.tgsembed-image-dropzone-drag {
	-moz-box-shadow: 0px 0px 15px Green;
	-webkit-box-shadow: 0px 0px 15px Green;
	box-shadow: 0px 0px 15px Green;
}

.tgsembed-image-form-table h3 {
	margin-bottom: 15px;
}

.tgsembed-image-form-table .tgsembed-image-dropzone-container {
	width: 30%;
}

.tgsembed-image-form-table .tgsembed-image-or-container {
	vertical-align: middle;
	text-align: center;
	font-weight: bold;
	font-size: 20px;
	width: 10%;
}

.tgsembed-image-form-table .tgsembed-image-form-container {
	width: 60%
}

.tgsembed-video-dimensions {
	display: inline-block;
}

.tgsembed-video-dimensions input {
	width: 40px;
	display: inline-block;
}

.tgsembed-video-dimensions span {
	display: inline-block;
	padding: 0 1px 0 3px;
}

#tgsembed-spotcontent-module ul.elgg-menu-simpleicon-entity li a {
	font-size: 11px;
}

#tgsembed-spotcontent-subtype-selector {
	float: right;
	margin-bottom: 3px;
}

/*
    ColorBox Core Style:
    The following CSS is consistent between example themes and should not be altered.
*/
#colorbox, #cboxOverlay, #cboxWrapper{
	position:absolute;
	top:0;
	left:0;
	z-index:9999;
	overflow:hidden;
	box-shadow: 0 0 40px black;
	-moz-box-shadow: 0 0 40px black;
	-webkit-box-shadow: 0 0 40px black;
}
#cboxOverlay{position:fixed; width:100%; height:100%;}
#cboxMiddleLeft, #cboxBottomLeft{clear:left;}
#cboxContent{position:relative;}
#cboxLoadedContent{overflow:auto;}
#cboxTitle{margin:0;}
#cboxLoadingOverlay, #cboxLoadingGraphic{position:absolute; top:0; left:0; width:100%; height:100%;}
#cboxPrevious, #cboxNext, #cboxClose, #cboxSlideshow{cursor:pointer;}
.cboxPhoto{float:left; margin:auto; border:0; display:block;}
.cboxIframe{width:100%; height:100%; display:block; border:0;}

/* 
    User Style:
    Change the following styles to modify the appearance of ColorBox.  They are
    ordered & tabbed in a way that represents the nesting of the generated HTML.
*/
#cboxOverlay{background:#777; opacity: 0.7 !important;}
#colorbox{background: #FFF;}
    #cboxContent{margin-top:32px; overflow:visible; background:#FFF;}
        .cboxIframe{background:#fff;}
        #cboxError{padding:50px; border:1px solid #ccc;}
        #cboxLoadedContent{background:#FFF; padding:10px;}
        #cboxLoadingGraphic{background:url(<?php echo elgg_get_site_url(); ?>_graphics/ajax_loader_bw.gif) no-repeat center center;}
        #cboxLoadingOverlay{background:#FFF;}
        #cboxTitle{position:absolute; top:-22px; left:0; padding-left: 10px;}
        #cboxCurrent{position:absolute; top:-22px; right:205px; text-indent:-9999px;}
        #cboxSlideshow, #cboxPrevious, #cboxNext, #cboxClose{text-indent:-9999px; width:20px; height:20px; position:absolute; top:-20px; background:url('<?php echo elgg_get_site_url() . 'mod/tgsembed/_graphics/controls.png' ?>') no-repeat 0 0;}
        #cboxPrevious{background-position:0px 0px; right:44px;}
        #cboxPrevious:hover{background-position:0px -25px;}
        #cboxNext{background-position:-25px 0px; right:22px;}
        #cboxNext:hover{background-position:-25px -25px;}
        #cboxClose{background-position:-50px 0px; right:0; top:-30px;}
        #cboxClose:hover{background-position:-50px -25px;}
        .cboxSlideshow_on #cboxPrevious, .cboxSlideshow_off #cboxPrevious{right:66px;}
        .cboxSlideshow_on #cboxSlideshow{background-position:-75px -25px; right:44px;}
        .cboxSlideshow_on #cboxSlideshow:hover{background-position:-100px -25px;}
        .cboxSlideshow_off #cboxSlideshow{background-position:-100px 0px; right:44px;}
        .cboxSlideshow_off #cboxSlideshow:hover{background-position:-75px -25px;}


/* Colorbox tweaks */
.tgsembed-colorbox #cboxClose {
	background-position: -50px -2px;
    border: 0 none;
}

.tgsembed-colorbox #cboxClose:hover {
	background-position: -50px -27px;
    border: 0 none;
}