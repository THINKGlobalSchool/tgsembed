<?php
/**
 * TGS Embed Image thumbnail
 * - Modified version of the file plugin's thumbnail.php
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.com/
 * 
 */
// Get engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get file GUID
$file_guid = (int) get_input('file_guid', 0);

// Get file thumbnail size
$size = get_input('size', 'small');

$ia = elgg_get_ignore_access();
elgg_set_ignore_access();
$file = get_entity($file_guid);

if (!$file || $file->getSubtype() != "embedimage") {
	exit;
}

$filename = $file->title;

$simpletype = $file->simpletype;
if ($simpletype == "image") {

	// Get file thumbnail
	switch ($size) {
		case "small":
			$thumbfile = $file->thumbnail;
			break;
		case "medium":
			$thumbfile = $file->smallthumb;
			break;
		case "master":
			header("Pragma: public");
			header("Content-type: $mime");
			header("Content-Disposition: inline; filename=\"$filename\"");
			ob_clean();
			flush();
			readfile($file->getFilenameOnFilestore());
			exit;
			break;
		case "large":
		default:
			$thumbfile = $file->largethumb;
			break;
	}

	// Grab the file
	if ($thumbfile && !empty($thumbfile)) {
		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid;
		$readfile->setFilename($thumbfile);
		$mime = $file->getMimeType();
		$contents = $readfile->grabFile();

		// caching images for 10 days
		header("Content-type: $mime");
		header("Content-Disposition: inline; filename=\"$filename\"");
		header('Expires: ' . date('r',time() + 864000));
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("Content-Length: " . strlen($contents));

		echo $contents;
		exit;
	}
}
elgg_set_ignore_access($ia);