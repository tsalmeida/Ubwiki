<?php
	
	if (!isset($template_id)) {
		$template_id = false;
	}
	if (!isset($template_collapse_stuff)) {
		$template_collapse_stuff = false;
	}
	if (!isset($template_titulo)) {
		$template_titulo = false;
	}
	if (!isset($template_botoes)) {
		$template_botoes = false;
	}
	if (!isset($template_conteudo)) {
		$template_conteudo = false;
	}
	
	echo "
<div id='$template_id' class='$template_collapse_stuff show mb-5 border-top border-light pt-4'>
    <div class='row'>
        <div class='col-12 d-flex justify-content-between'>
            <h1>$template_titulo</h1>
            <span class='h5'>
                $template_botoes
            </span>
        </div>
    </div>
    
    
    <div class='row py-3'>
        <div class='col-12'>
            $template_conteudo
        </div>
    </div>
</div>
";
	
	unset($template_id);
	unset($template_collapse_stuff);
	unset($template_titulo);
	unset($template_botoes);
	unset($template_conteudo);
	
	unset($template_quill_form_id);
	unset($template_quill_conteudo_html);
	unset($template_quill_conteudo_text);
	unset($template_quill_conteudo_content);
	unset($template_quill_container_id);
	unset($template_quill_editor_id);
	unset($template_quill_editor_classes);
	unset($template_quill_conteudo_opcional);
	unset($template_quill_botoes_collapse_stuff);


?>