<?php
	if (!isset($template_titulo)) { return false; }
	
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
	if ($display_level != false) {
		echo "<span class='$display_level playfair400 d-none d-md-inline'>$template_titulo</span>";
		echo "<h1 class='h1-responsive d-sm-inline d-md-none'>$template_titulo</h1>";
	}
	
	$template_titulo = false;