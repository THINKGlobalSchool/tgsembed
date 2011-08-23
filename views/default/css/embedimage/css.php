<?php
/**
 * TGS Embed Image CSS
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>

.embedimage-module {
	width: 730px;
}

.embedimage-gallery-item {
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

.embedimage-gallery-item-title  {
	overflow: hidden;
	display: block;
	height:32px;
	width: 154px;
}
.embedimage-gallery-item-title span {
	font-weight:bold;
	line-height: 1.2em;
	height:32px;
	width: 154px;
	vertical-align: middle;
	text-align: center;
	display: table-cell;
}


.embedimage-gallery-item:hover {
	background-color: #CCCCCC;
}

.embedimage-gallery-item h3 {
	margin-bottom: 5px;
}

.drag-upload {
	display: none;
}

.embedimage-dropzone {
	margin-left: 15px;
	margin-right: 15px;
	width: 200px;
	height: 150px;
	background-image: url('<?php echo elgg_get_site_url() . 'mod/embedimage/_graphics/dropzone.png' ?>');
}

.embedimage-form-table h3 {
	margin-bottom: 15px;
}

.embedimage-form-table .embedimage-dropzone-container {
	width: 30%;
}

.embedimage-form-table .embedimage-or-container {
	vertical-align: middle;
	text-align: center;
	font-weight: bold;
	font-size: 20px;
	width: 10%;
}

.embedimage-form-table .embedimage-form-container {
	width: 60%
}