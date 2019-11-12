<?php
	
	// para criar uma nova instance do quill, as seguintes variáveis devem ser set:
	// $template_quill_unique_name
	// $template_quill_initial_state
	// $template_quill_conteudo
	
	if (!isset($template_quill_unique_name)) {
		$template_quill_unique_name = 'general';
	}
	if (!isset($template_quill_whitelist)) {
		$template_quill_whitelist = 'general';
	}
	if (!isset($template_quill_toolbar)) {
		$template_quill_toolbar = 'general';
	}
	if (!isset($template_quill_conteudo)) {
		$template_quill_conteudo = false;
	}
	if (!isset($template_quill_initial_state)) {
		$template_quill_initial_state = 'edicao';
	}
	
	if ($template_quill_initial_state == 'edicao') {
		$template_quill_editor_classes = 'quill_editor_height quill_editor_height_leitura';
		$template_quill_collapse = 'collapse show';
	} else {
		$template_quill_editor_classes = 'quill_editor_height';
		$template_quill_collapse = 'collapse';
	}
	
	if ($template_quill_whitelist == false) {
		$template_quill_whitelist = "formatWhitelist_general";
	} else {
		$template_quill_whitelist = "formatWhitelist_{$template_quill_whitelist}";
	}
	
	if ($template_quill_toolbar == false) {
		$template_quill_toolbar = "toolbarOptions_general";
	} else {
		$template_quill_toolbar = "toolbarOptions_{$template_quill_toolbar}";
	}
	
	$quill_result = false;

	$quill_result .= "
    <form id='quill_{$template_quill_unique_name}_form' method='post'>
        <input name='quill_novo_{$template_quill_unique_name}_html' type='hidden'>
        <input name='quill_novo_{$template_quill_unique_name}_text' type='hidden'>
        <input name='quill_novo_{$template_quill_unique_name}_content' type='hidden'>
        <div class='row'>
            <div class='container col-12'>
                <div id='quill_container_{$template_quill_unique_name}'>
                    <div id='quill_editor_{$template_quill_unique_name}' class='$template_quill_editor_classes'>
                    </div>
                </div>
            </div>
        </div>
        <div class='row justify-content-center {$template_quill_unique_name}_editor_collapse $template_quill_collapse mt-3'>
            <button type='button' class='btn btn-light btn-md'><i
                    class='fal fa-times-circle fa-fw'
                    ></i> Cancelar
            </button>
            <button type='submit' class='btn btn-primary btn-md'><i class='fal fa-check fa-fw'></i>
                Salvar
            </button>
        </div>
    </form>
    <script>
    var {$template_quill_unique_name}_editor = new Quill('#quill_editor_{$template_quill_unique_name}', {
        theme: 'snow',
        formats: $template_quill_whitelist,
        modules: {
            toolbar: {
                container: $template_quill_toolbar,
                handlers: {
                    image: imageHandler
                }
            }
        }
    });


    var form_{$template_quill_unique_name} = document.querySelector('#quill_{$template_quill_unique_name}_form');
    form_{$template_quill_unique_name}.onsubmit = function () {
        var quill_novo_{$template_quill_unique_name}_html = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_html]');
        quill_novo_{$template_quill_unique_name}_html.value = {$template_quill_unique_name}_editor.root.innerHTML;

        var quill_novo_{$template_quill_unique_name}_text = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_text]');
        quill_novo_{$template_quill_unique_name}_text.value = {$template_quill_unique_name}_editor.getText();

        var quill_novo_{$template_quill_unique_name}_content = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_content]');
        var quill_{$template_quill_unique_name}_content = {$template_quill_unique_name}_editor.getContents();
        quill_{$template_quill_unique_name}_content = JSON.stringify(quill_{$template_quill_unique_name}_content);
        quill_{$template_quill_unique_name}_content = encodeURI(quill_{$template_quill_unique_name}_content);
        quill_novo_{$template_quill_unique_name}_content.value = quill_{$template_quill_unique_name}_content;
    };
    
	  {$template_quill_unique_name}_editor.setContents($template_quill_conteudo);
	</script>
	";
	
	if ($template_quill_initial_state != 'edicao') {
		$quill_result .= "
			<script type='text/javascript'>
				{$template_quill_unique_name}_editor.disable();
			</script>";
	}
	
	unset($template_quill_unique_name);
	unset($template_quill_whitelist);
	unset($template_quill_toolbar);
	unset($template_quill_initial_state);
	unset($template_quill_conteudo);
	unset($template_quill_editor_classes);
	
	return $quill_result;