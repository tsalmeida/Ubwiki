<?php
	if (!isset($template_titulo)) { return false; }
	if (!isset($template_titulo_context)) { $template_titulo_context = false; }
	if (!isset($template_titulo_no_nav)) { $template_titulo_no_nav = false; }
	if (!isset($template_titulo_escritorio)) { $template_titulo_escritorio = false; }
	if (!isset($template_subtitulo)) { $template_subtitulo = false; }
	
	$titulo_length = strlen($template_titulo);
	$display_level = false;
	if ($titulo_length < 20) {
		$display_level = 'display-1';
	} elseif ($titulo_length < 30) {
		$display_level = 'display-2';
	} elseif ($titulo_length < 50) {
		$display_level = 'display-3';
	} elseif ($titulo_length < 65) {
		$display_level = 'display-4';
	} else {
		$display_level = false;
	}
	if (isset($pagina_tipo)) {
		if ($pagina_tipo == 'texto') {
			$display_level = false;
		}
	}
	if ($template_titulo_no_nav == true) { $spacing = 'my-2'; }
	else { $spacing = 'mb-2'; }
	
	if ($template_titulo_context == true) {
		echo "
    	<div class='row d-flex justify-content-center'>
        <div class='col-lg-11 col-sm-12 text-center $spacing'>
		";
	}
	if ($template_titulo_escritorio == true) {
		echo "
			<span class='text-muted d-block mt-3'><em><h4 class='mb-0'>Escritório de</h4></em></span>
		";
	}
	if ($display_level != false) {
		echo "
			<h1><span class='$display_level d-none d-md-inline m-0 tighten'>$template_titulo</span></h1>
			<h1 class='h1-responsive d-sm-inline d-md-none m-0'>$template_titulo</h1>
		";
	}
	else {
		echo "<h1 class='h1-responsive'>$template_titulo</h1>";
	}
	if ($template_subtitulo != false) {
		echo "
			<span class='text-muted d-block mt-3'><h4>$template_subtitulo</h4></span>
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