<?php
/**
 * TGS Embed Image CSS
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2014
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
/** <style> /**/
.tgsembed-module {
	width: 730px;
}

#tgsembed-module-image {}

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

/* Colorbox tweaks */
.tgsembed-colorbox #cboxClose {
	background-position: -50px -2px;
    border: 0 none;
}

.tgsembed-colorbox #cboxClose:hover {
	background-position: -50px -27px;
    border: 0 none;
}