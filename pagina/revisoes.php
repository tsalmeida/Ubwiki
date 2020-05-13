<?php
	$pagina_tipo = 'revisoes';
	$pagina_id = 1;
	include 'engine.php';
	if (($user_tipo != 'admin') && ($user_tipo != 'revisor')) {
		header('Location:escritorio.php');
		exit();
	}
	
