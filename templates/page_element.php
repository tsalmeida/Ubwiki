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
		$template_conteudo_class = 'd-flex justify-content-center';
	}
	if (!isset($template_load_invisible)) {
		$template_load_invisible = false;
	}
	if (!isset($template_classes)) {
		$template_classes = false;
	}
	if (!isset($template_col_value)) {
		$template_col_value = 'col-12';
	}
	if (!isset($template_background)) {
		$template_background = 'bg-white';
	}
	
	if (!isset($template_no_spacer)) {
		$template_no_spacer = false;
	}

	$template_conteudo_no_col1 = false;
	$template_conteudo_no_col2 = false;

	if (!isset($template_conteudo_no_col)) {
		$template_conteudo_no_col = false;
	}

	if (!isset($template_p_limit)) {
		$template_p_limit = 'p-limit';
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

	if ($template_conteudo_no_col == false) {
		$template_conteudo_no_col = false;
		$template_conteudo_no_col1 = "<div class='col $template_p_limit $template_collapse collapse show'>";
		$template_conteudo_no_col2 = "</div>";
	}

	if (!isset($template_botoes_padrao)) {
		$template_botoes_padrao = false;
	}

	if ($template_botoes_padrao == true) {
		$template_botoes_padrao = "
					<span id='$template_esconder' class='$template_collapse collapse $show' data-toggle='collapse' data-target='.$template_collapse' title='esconder'><a href='javascript:void(0);'><i class='fad fa-chevron-square-up fa-fw'></i></a></span>
					<span id='$template_mostrar' class='$template_collapse collapse $hide' data-toggle='collapse' data-target='.$template_collapse' title='mostrar'><a href='javascript:void(0);' class='text-muted'><i class='fad fa-chevron-square-down fa-fw'></i></a></span>";
	}
	
	if (($template_botoes_padrao == false) && ($template_botoes == false)) {
		if ($template_no_spacer == false) {
			$template_botoes_padrao = "
				<span class='spacer text-white'><i class='fad fa-bookmark fa-fw'></i></span>
			";
		}
	}

	if (!isset($template_titulo_heading)) {
		$template_titulo_heading = 'h2';
	}

	echo "
<div id='$template_id' class='row show $template_classes $template_background p-2 pb-4 mb-2 rounded'>
	<div class='$template_col_value'>
    <div class='row d-flex justify-content-end page-element-botoes'>
      $template_botoes
      $template_botoes_padrao
    </div>";
	if ($template_titulo != false) {
		echo "
    <div class='row'>
    	<div class='col'>
        <$template_titulo_heading class='{$template_titulo_heading}-responsive'>$template_titulo</$template_titulo_heading>
    	</div>
    </div>
    ";
	}
	echo "
		<div class='row $template_collapse collapse $show $template_conteudo_class'>
    	$template_conteudo_no_col1
      	$template_conteudo
      $template_conteudo_no_col2
    </div>
  </div>
</div>";
	if ($template_botoes_padrao == true) {
		echo "
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
	}

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
	unset($template_conteudo_no_col);
	unset($template_conteudo_no_col1);
	unset($template_conteudo_no_col2);
	unset($template_col_value);
	unset($template_background);
	unset($template_p_limit);
	unset($template_no_spacer);

?>