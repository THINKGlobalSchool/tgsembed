<?php
/**
 * TGS Embed Image save action
 * - Modified version of the file/upload action
 *
 * @package TGSEmbedImage
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = ACCESS_LOGGED_IN; // I think this is ok
$container_guid = (int) get_input('container_guid', 0);

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('embedimage-form');

// must have a file if a new file upload
if (empty($_FILES['upload']['name'])) {
	$error = elgg_echo('embedimage:error:nofile');
	echo json_encode(array('status' => -1, 'system_messages' => array('error' => $error)));
	return true;
}

// Grab type to make sure we have an image
$simpletype = file_get_simple_type($_FILES['upload']['type']);

// Check simpletype, need an image
if ($simpletype != 'image') {
	$error = elgg_echo('embedimage:error:invalidfile');
	echo json_encode(array('status' => -1, 'system_messages' => array('error' => $error)));
	return true;
}

// Hope this works..
$embedimage = new FilePluginFile();
$embedimage->subtype = "embedimage";

// if no title on new upload, grab filename
if (empty($title)) {
	$title = $_FILES['upload']['name'];
}

$embedimage->title = $title;
$embedimage->description = $desc;
$embedimage->access_id = $access_id;
$embedimage->container_guid = $container_guid;

// we have a file upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "file/";

	$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);
	
	$embedimage->setFilename($prefix.$filestorename);
	$embedimage->setMimeType($_FILES['upload']['type']);
	$embedimage->originalfilename = $_FILES['upload']['name'];
	$embedimage->simpletype = $simpletype;

	// Open the file to guarantee the directory exists
	$embedimage->open("write");
	$embedimage->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $embedimage->getFilenameOnFilestore());

	$guid = $embedimage->save();

	// if image, we need to create thumbnails (this should be moved into a function)
	if ($guid) {
		$thumbnail = get_resized_image_from_existing_file($embedimage->getFilenameOnFilestore(),60,60, true);
		if ($thumbnail) {
			$thumb = new ElggFile();
			$thumb->setMimeType($_FILES['upload']['type']);

			$thumb->setFilename($prefix."thumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$embedimage->thumbnail = $prefix."thumb".$filestorename;
			unset($thumbnail);
		}

		$thumbsmall = get_resized_image_from_existing_file($embedimage->getFilenameOnFilestore(),153,153, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$embedimage->smallthumb = $prefix."smallthumb".$filestorename;
			unset($thumbsmall);
		}

		$thumblarge = get_resized_image_from_existing_file($embedimage->getFilenameOnFilestore(),600,600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$embedimage->largethumb = $prefix."largethumb".$filestorename;
			unset($thumblarge);
		}
	}
}

// file saved so clear sticky form
elgg_clear_sticky_form('embedimage-form');

if ($guid) {
	$message = elgg_echo("embedimage:success:save");
	echo json_encode(array(
		'status' => 0, 
		'system_messages' => array('success' => $message),
		'title' => $embedimage->title,
		'entity_url' => $embedimage->getURL(),
		'icon_url' => $embedimage->getIconURL('large'),
	));
} else {
	// failed to save file object - nothing we can do about this
	$error = elgg_echo("embedimage:error:save");
	echo json_encode(array('status' => -1, 'system_messages' => array('error' => $error)));
}

return true;
