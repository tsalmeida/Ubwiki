<?php
	if (!isset($sim_quill_id)) {
		return false;
	}
	if (!isset($template_modal_form_id)) {
		return false;
	}
	$quill_sim_result = false;
	$quill_sim_result = "
    <input name='quill_novo_{$sim_quill_id}_html' type='hidden'>
        <input name='quill_novo_{$sim_quill_id}_text' type='hidden'>
        <input name='quill_novo_{$sim_quill_id}_content' type='hidden'>
        <div class='row'>
            <div class='container col-12'>
                <div id='quill_container_novo_{$sim_quill_id}'>
                    <div id='quill_editor_novo_{$sim_quill_id}' class='quill_editor_height border border-light ql-editor-active'>
                    </div>
                </div>
            </div>
        </div>
        <script type='text/javascript'>
            var quill_{$sim_quill_id} = new Quill('#quill_editor_novo_{$sim_quill_id}', {
                theme: 'snow',
                formats: formatWhitelist_simulados,
                modules: {
                    toolbar: {
                        container: toolbarOptions_simulados
                    }
                }
            });
            $('body').on('submit', '#{$template_modal_form_id}', function() {
		            var form_{$sim_quill_id} = document.querySelector('#{$template_modal_form_id}');
                var quill_novo_{$sim_quill_id}_html = document.querySelector('input[name=quill_novo_{$sim_quill_id}_html]');
                quill_novo_{$sim_quill_id}_html.value = quill_{$sim_quill_id}.root.innerHTML;
                
                var quill_novo_{$sim_quill_id}_text = document.querySelector('input[name=quill_novo_{$sim_quill_id}_text]');
                quill_novo_{$sim_quill_id}_text.value = quill_{$sim_quill_id}.getText();
                
                var quill_novo_{$sim_quill_id}_content = document.querySelector('input[name=quill_novo_{$sim_quill_id}_content]');
                quill_novo_{$sim_quill_id}_content_var = quill_{$sim_quill_id}.getContents();
                quill_novo_{$sim_quill_id}_content_var = JSON.stringify(quill_novo_{$sim_quill_id}_content_var);
                quill_novo_{$sim_quill_id}_content.value = quill_novo_{$sim_quill_id}_content_var;
            });
        </script>
	";
	
	unset($sim_quill_id);
	
	return $quill_sim_result;
?>