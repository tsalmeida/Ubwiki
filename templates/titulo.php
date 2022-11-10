<?php
	if (!isset($template_titulo)) {
		return false;
	}
	if (!isset($template_titulo_context)) {
		$template_titulo_context = true;
	}
	if (!isset($template_titulo_no_nav)) {
		$template_titulo_no_nav = false;
	}
	if (!isset($template_titulo_above)) {
		$template_titulo_above = false;
	}
	if (!isset($template_subtitulo)) {
		$template_subtitulo = false;
	}
	if (!isset($template_subtitulo_size)) {
		$template_subtitulo_size = 'h4';
	}
	if (!isset($titulo_color)) {
		$titulo_color = false;
	}

	$titulo_length = strlen($template_titulo);
	$display_level = false;
	if ($titulo_length < 20) {
		$display_level = 'display-2';
	} elseif ($titulo_length < 30) {
		$display_level = 'display-3';
	} elseif ($titulo_length < 65) {
		$display_level = 'display-4';
	}

	if ($titulo_length > 280) {
		$titulo_header = 'h4';
	} elseif ($titulo_length > 200) {
		$titulo_header = 'h3';
	} elseif ($titulo_length > 150) {
		$titulo_header = 'h2';
	}

	if (!isset($titulo_header)) {
		$titulo_header = 'h1';
	}

	if (isset($pagina_tipo)) {
		if ($pagina_tipo == 'texto') {
			$display_level = false;
		}
	}
	/*
	if ($template_titulo_no_nav == true) {
		$spacing = 'my-2';
	} else {
		$spacing = 'mb-2';
	}
	*/
	$spacing = false;

	if ($template_titulo_context == true) {
		echo "
    	<div class='row d-flex justify-content-center mb-2'>
        <div class='col-lg-11 col-sm-12 text-center $spacing'>
		";
	}
	if ($template_titulo_above != false) {
		echo "
			<span class='text-muted d-block fst-italic'><h4 class='mb-0'>$template_titulo_above</h4></span>
		";
	}
	if ($display_level != false) {
		echo "
			<{$titulo_header} id='titulo1' class='{$display_level} $titulo_color d-none d-md-inline m-0'>{$template_titulo}</{$titulo_header}>
			<{$titulo_header} id='titulo2' class='{$titulo_header}-responsive $titulo_color d-sm-inline d-md-none m-0'>{$template_titulo}</{$titulo_header}>
		";
	} else {
		echo "<{$titulo_header} class='{$titulo_header}-responsive $titulo_color'>$template_titulo</{$titulo_header}>";
	}
	if ($titulo_header != 'h1') {
		echo "<h1 class='d-none'>$template_titulo</h1>";
	}
	if ($template_subtitulo != false) {
		echo "
			<span id='subtitulo' class='text-muted d-block'><$template_subtitulo_size class='fine-subtitulo mb-0'>$template_subtitulo</$template_subtitulo_size></span>
		";
	}
	if ($template_titulo_context == true) {
		echo "
				</div>
			</div>
		";
	}

	unset($template_titulo);
	unset($template_titulo_context);
	unset($template_titulo_no_nav);
	unset($template_subtitulo);
	unset($template_titulo_above);
	unset($template_subtitulo_size);
	unset($titulo_color);