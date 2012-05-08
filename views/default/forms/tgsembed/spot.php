<?php
/**
 * TGS Embed spot content form
 *
 * @package TGSEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

echo elgg_view('input/dropdown', array(
	'id' => 'tgsembed-spotcontent-subtype-selector',
	'options_values' => tgsembed_get_embeddable_dropdown(),
	'value' => '',
));

// Create spot content module				
$module = elgg_view('modules/genericmodule', array(
	'view' => 'tgsembed/modules/spotcontent',
	'module_id' => 'tgsembed-spotcontent-module',
	'view_vars' => array(
		'user_guid' => elgg_get_logged_in_user_guid(),
		'selected_subtype' => ''
	),
));

// In line script to manually init module and resize colorbox on pagination
echo <<<JAVASCRIPT
	<script>
		$(document).ready(function() {
			var embedResizeColorbox = function() {
				$.colorbox.resize();
			};
			
			elgg.register_hook_handler('pagination_content_loaded', 'modules', embedResizeColorbox);
			elgg.register_hook_handler('generic_populated', 'modules', embedResizeColorbox);
			elgg.modules.genericmodule.init();

		});
	</script>
JAVASCRIPT;

echo $module;