<?php

	if (!isset($template_id)) {
		$template_id = false;
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

	$template_collapse = $template_id . "_collapse";
	$template_esconder = "esconder_" . $template_id;
	$template_mostrar = "mostrar_" . $template_id;


echo "
<div id='$template_id' class='show mb-2 border-top border-light pt-4'>
    <div class='row'>
        <div class='col-12 d-flex justify-content-between'>
            <h1>$template_titulo</h1>
            <span class='h5'>
                $template_botoes
								<span id='$template_esconder' class='$template_collapse collapse show' data-toggle='collapse' data-target='.$template_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-square-up fa-fw'></i></a></span>
								<span id='$template_mostrar' class='$template_collapse collapse' data-toggle='collapse' data-target='.$template_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-square-down fa-fw'></i></a></span>
                <span 
            </span>
        </div>
    </div>
        
    <div class='row py-3 $template_collapse collapse show'>
        <div class='col-12'>
            $template_conteudo
        </div>
    </div>
</div>
<script type='text/javascript'>
		$('#$template_esconder').click(function(){
	    $('#$template_esconder').hide();
	    $('#$template_mostrar').show();
 		});
		$('#$template_mostrar').click(function(){
	    $('#$template_mostrar').hide();
	    $('#$template_esconder').show();
 		});
</script>
";

	unset($template_id);
	unset($template_titulo);
	unset($template_botoes);
	unset($template_conteudo);
	unset($template_collapse);
	unset($template_esconder);
	unset($template_mostrar);

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