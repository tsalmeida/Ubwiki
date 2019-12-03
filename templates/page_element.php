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
	if (!isset($template_conteudo_class)) {
		$template_conteudo_class = 'justify-content-center';
	}
	if (!isset($template_load_invisible)) {
		$template_load_invisible = false;
	}
	if (!isset($template_classes)) {
		$template_classes = false;
	}

	$template_collapse = $template_id . "_collapse";
	$template_esconder = "esconder_" . $template_id;
	$template_mostrar = "mostrar_" . $template_id;

	$show = false;
	$hide = false;
	if ($template_load_invisible == false) {
		$show = 'show';
	} else {
		$hide = 'show';
	}

	if ($template_conteudo != false) {

		$template_botoes_padrao = "
								<span id='$template_esconder' class='$template_collapse collapse $show' data-toggle='collapse' data-target='.$template_collapse' title='esconder'><a href='javascript:void(0);'><i class='fal fa-chevron-square-up fa-fw'></i></a></span>
								<span id='$template_mostrar' class='$template_collapse collapse $hide' data-toggle='collapse' data-target='.$template_collapse' title='mostrar'><a href='javascript:void(0);'><i class='fal fa-chevron-square-down fa-fw'></i></a></span>";
	} else {
		$template_botoes_padrao = false;
	}

	if (!isset($template_titulo_heading)) {
		$template_titulo_heading = 'h1';
	}

	echo "
<div id='$template_id' class='row show mb-2 border-top border-light pt-4 $template_classes'>
	<div class='col'>
    <div class='row d-flex justify-content-between'>
      <div class='col-lg-9 col-md-8'>
        <$template_titulo_heading class='{$template_titulo_heading}-responsive'>$template_titulo</$template_titulo_heading>
      </div>
      <div class='col-lg-3 d-flex justify-content-end'>
	        <span class='h5'>
	            $template_botoes
	            $template_botoes_padrao
	        </span>
      </div>
    </div>
        
    <div class='row $template_collapse collapse $show $template_conteudo_class'>
    	<div class='col'>
      	$template_conteudo
      </div>
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
	unset($template_conteudo_class);
	unset($template_load_invisible);
	unset($template_classes);
	unset($template_botoes_padrao);
	unset($template_titulo_heading);

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