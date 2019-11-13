<?php
	if (!isset($template_titulo)) { return false; }
	if (!isset($template_titulo_context)) { $template_titulo_context = false; }
	if (!isset($template_titulo_no_nav)) { $template_titulo_no_nav = false; }
	
	$titulo_length = strlen($template_titulo);
	$display_level = false;
	if ($titulo_length < 15) {
		$display_level = 'display-1';
	} elseif ($titulo_length < 25) {
		$display_level = 'display-2';
	} elseif ($titulo_length < 45) {
		$display_level = 'display-3';
	} elseif ($tema_length < 60) {
		$display_level = 'display-4';
	} else {
		echo "<h1 class='h1-responsive'>$template_titulo</h1>";
		$display_level = false;
	}
	if ($template_titulo_no_nav == true) { $spacing = 'my-5'; }
	else { $spacing = 'mb-5'; }
	
	if ($template_titulo_context == true) {
		echo "
    	<div class='row d-flex justify-content-center'>
        <div class='col-lg-11 col-sm-12 text-center $spacing'>
		";
	}
	if ($display_level != false) {
		echo "<span class='$display_level playfair400 d-none d-md-inline m-0'>$template_titulo</span>
		<h1 class='h1-responsive d-sm-inline d-md-none m-0'>$template_titulo</h1>";
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