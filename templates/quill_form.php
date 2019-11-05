<?php
	
	if (!isset($template_quill_form_id)) {
		$template_quill_form_id = false;
	}
	if (!isset($template_quill_conteudo_html)) {
		$template_quill_conteudo_html = false;
	}
	if (!isset($template_quill_conteudo_text)) {
		$template_quill_conteudo_text = false;
	}
	if (!isset($template_quill_conteudo_content)) {
		$template_quill_conteudo_content = false;
	}
	if (!isset($template_quill_container_id)) {
		$template_quill_container_id = false;
	}
	if (!isset($template_quill_editor_id)) {
		$template_quill_editor_id = false;
	}
	if (!isset($template_quill_editor_classes)) {
		$template_quill_editor_classes = false;
	}
	if (!isset($template_quill_conteudo_opcional)) {
		$template_quill_conteudo_opcional = false;
	}
	if (!isset($template_quill_botoes_collapse_stuff)) {
		$template_quill_botoes_collapse_stuff = false;
	}
	
	return "
    <form id='$template_quill_form_id' method='post'>
        <input name='$template_quill_conteudo_html' type='hidden'>
        <input name='$template_quill_conteudo_text' type='hidden'>
        <input name='$template_quill_conteudo_content' type='hidden'>
        <div class='row'>
            <div class='container col-12'>
                <div id='$template_quill_container_id'>
                    <div id='$template_quill_editor_id' class='$template_quill_editor_classes'>
                         $template_quill_conteudo_opcional
                    </div>
                </div>
            </div>
        </div>
        <div class='row justify-content-center $template_quill_botoes_collapse_stuff mt-3'>
            <button type='button' class='btn btn-light btn-md'><i
                    class='fal fa-times-circle fa-fw'
                    ></i> Cancelar
            </button>
            <button type='submit' class='btn btn-primary btn-md'><i class='fal fa-check fa-fw'></i>
                Salvar
            </button>
        </div>
    </form>
";

?>