<?php
/**
 * TGS Embed Image File Upload form
 * - Just a modified copy of the file/upload form
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$container_guid = elgg_extract('container_guid', $vars);

if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

$file_input = elgg_view('input/file', array(
	'name' => 'upload'
));

$drop_input = elgg_view('input/file', array(
	'name' => 'files',
	'class' => 'drag-upload',
));

$title_label = elgg_echo('title');

$title_input = elgg_view('input/text', array(
	'name' => 'title', 
	'value' => $title
));

$embed_image_size_label = elgg_echo('tgsembed:label:imagesize');
$embed_image_size_input = elgg_view('input/dropdown', array(
	'name' => 'embed_image_size', 
	'value' => 'large',
	'options_values' => array(
		'large' => elgg_echo('large'),
		'master' => elgg_echo('master'),
	)
));

$container_input = elgg_view('input/hidden', array(
	'name' => 'container_guid', 
	'value' => $container_guid
));

$submit_input = elgg_view('input/submit', array(
	'value' => $submit_label
));

$dropzone_desc = elgg_echo('tgsembed:label:dropzone_desc');
$upload_desc = elgg_echo('tgsembed:label:upload_desc');

$content = <<<HTML
	<table class='tgsembed-image-form-table'>
		<tbody>
			<tr>
				<td class='tgsembed-image-dropzone-container'>
					<h3>$dropzone_desc</h3>
					<div class='tgsembed-image-dropzone tgsembed-image-dropzone-background'>
					</div>
				</td>
				<td class='tgsembed-image-or-container'>
					OR
				</td>
				<td class='tgsembed-image-form-container'>
					<h3>$upload_desc</h3>
					<div>
						<label>$file_label</label>
						$file_input
						$drop_input
					</div>
					<br />
					<div>
						<label>$title_label</label>
						$title_input
					</div><br />
					<div>
						<label>$embed_image_size_label</label>
						$embed_image_size_input
					<div class='elgg-foot' id='tgsembed-foot'>
						$container_input
						<br />
						$submit_input
					</div>
				</td>
			</tr>
		</tbody>
	</table>
HTML;

echo $content;
