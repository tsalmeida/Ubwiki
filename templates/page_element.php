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
		$template_background = 'bg-white border';
	}
	if (!isset($template_return)) {
		$template_return = false;
	}

	$template_conteudo_no_col1 = false;
	$template_conteudo_no_col2 = false;

	if (!isset($template_conteudo_no_col)) {
		$template_conteudo_no_col = false;
	}

	if (!isset($template_col_classes)) {
		$template_col_classes = false;
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
		$template_conteudo_no_col1 = "<div class='col $template_p_limit $template_col_classes $template_collapse collapse $show'>";
		$template_conteudo_no_col2 = "</div>";
	}

	if (!isset($template_botoes_padrao)) {
		$template_botoes_padrao = false;
	}

	if ($template_botoes_padrao == true) {
		$template_botoes_padrao = false;
		if ($template_id == 'anotacoes') {
			$fechar_icone = 'fa-times-square';
			$fechar_cor = 'link-primary';
			$template_botoes_padrao .= "
					<span id='$template_esconder' class='$template_collapse collapse $show' data-bs-toggle='collapse' data-bs-target='.$template_collapse' title='{$pagina_translated['Esconder']}'><a href='javascript:void(0);' class='$fechar_cor'><i class='fad $fechar_icone fa-fw'></i></a></span>
		";
		} else {
			$fechar_icone = 'fa-chevron-square-up';
			$fechar_cor = 'link-primary';
		}
		$template_botoes_padrao .= "
					<span id='$template_mostrar' class='$template_collapse collapse $hide' data-bs-toggle='collapse' data-bs-target='.$template_collapse' title='{$pagina_translated['Mostrar']}'><a href='javascript:void(0);' class='link-primary'><i class='fad fa-chevron-square-down fa-fw'></i></a></span>
		";
	}

	if (!isset($template_titulo_heading)) {
		$template_titulo_heading = 'h2';
	}

	if (!isset($template_spacer)) {
		$template_spacer = "<span class='spacer text-white'><i class='fad fa-bookmark fa-fw'></i></span>";
	}
	if (!isset($template_spacing)) {
		$template_spacing = "p-2 pb-5 mb-2";
	}
	$final_result = false;
	$final_result .= "
	<div id='$template_id' class='row show $template_classes $template_background $template_spacing rounded justify-content-center'>
		<div class='$template_col_value ch-limit'>
		<div class='d-flex justify-content-end page-element-botoes'>
			$template_spacer
			$template_botoes
			$template_botoes_padrao
		</div>";
		if ($template_titulo != false) {
			$final_result .= "
			<div class='row mb-3'>
				<div class='col'>
				<$template_titulo_heading class='{$template_titulo_heading}-responsive m-0 text-center text-black-50 pb-2'>$template_titulo</$template_titulo_heading>
				</div>
			</div>
			";
		}
		$final_result .= "
		<div class='row $template_collapse collapse $show $template_conteudo_class'>
			$template_conteudo_no_col1
			$template_conteudo
			$template_conteudo_no_col2
		</div>
	  </div>
	</div>";
	if ($template_botoes_padrao == true) {
		$final_result .= "
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
	unset($template_col_classes);
	unset($template_spacer);
	unset($template_spacing);

	if ($template_return == true) {
		unset($template_return);
		return $final_result;
	} else {
		unset($template_return);
		echo $final_result;
	}

?>