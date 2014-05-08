<?php
/**
 * TGS Embed Generic
 * - modified hacked up version of the embed-generic form
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$instructions = elgg_echo('tgsembed:label:instructions');

$generic_input = elgg_view('input/plaintext', array(
	'name' => 'generic_content', 
	'id' => 'embed-generic-input'
));

$submit_input = elgg_view('input/submit', array(
	'id' => 'embed-generic-submit',
	'value' => elgg_echo('submit'),
	'class' => 'elgg-button-submit elgg-state-disabled',
	'disabled' => 'DISABLED',
));

$content = <<<HTML
	<div>$instructions</div>
	<div>
		<label>Embed Code:<label><br />
		$generic_input
	</div><br />
	<div class='elgg-foot'>
		$submit_input
	</div>
HTML;

echo $content;

?>
<script type="text/javascript">
	$(function() {

		var selected_service = 'generic';
		var manual_selected_service = true;
		var embed_button = $('#embed-generic-submit');
		var embed_resource_input = $('#embed-generic-input');

		// counter for paused input to try to validate/generate a preview.
		var rest_timeout_id = null;
		var rest_min_time = 750;
		var embed_generate_ecml_url = '<?php echo $vars['url']; ?>ecml_generate_generic';
		var embed_ecml_keyword_help_url = '<?php echo $vars['url']; ?>ecml/';

		var web_services_ecml_update = function() {
			if (rest_timeout_id) {
				clearTimeout(rest_timeout_id);
			}

			if (manual_selected_service) {
				// fire off preview attempt
				rest_timeout_id = setTimeout(generate_ecml, rest_min_time);
				return true;
			}

			var value = $(this).val();
			var value_length = value.length;

			if (value_length > 0) {

			} else {
				embed_button.attr('disabled', 'disabled').addClass('disabled');
	
			}

			if (value_length < 5) {
				$('.ecml_web_service a').removeClass('selected');
				return true;
			}


			// fire off a preview attempt
			if (selected_service) {
				rest_timeout_id = setTimeout(generate_ecml, rest_min_time);
			}
		};
		
		// pings back core to generate the ecml.
		// includes a status, ecml code, and the generated html. 
		var generate_ecml = function() {
			if (!selected_service) {
				return false;
			}

			var resource = escape(embed_resource_input.val());
			var post_data = {'service': selected_service, 'resource': resource};

			$.post(embed_generate_ecml_url, post_data, function(data) {
				if (data.status == 'success') {
					// show previews and update embed code.
					$('#ecml_code').html(data.ecml);
					$('body').data('elgg_embed_ecml', data.ecml);
					$('.embed_content_section.preview').removeClass('hidden'); // reveal preview link/panel
					$('.ecml_generated_code').removeClass('hidden'); // reveal ecml generated code

					// set status for embed button
					embed_button.removeAttr('disabled').removeClass('elgg-state-disabled');
				} else {
					// data failure
					embed_button.attr('disabled', 'disabled').addClass('disabled');
				}
			}, 'json');
		}

		// auto guess the service.
		embed_resource_input.keyup(web_services_ecml_update);

		$('#embed-generic-submit').click(function(event) {
			if ($(this).is(':disabled') == false) {
				// insert the ECML
				// if the ECML input is empty, insert the resource.
				if (!(content = $('body').data('elgg_embed_ecml'))) {
					// @todo display an error?
					content = embed_resource_input.val();
				}

				elgg.tgsembed.insert(content);

				event.preventDefault();
			}
		});
	});
	</script>