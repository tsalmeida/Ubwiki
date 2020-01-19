<?php
	$pagina_tipo = 'logout';
	error_log('engine ser aincluido no logout.php');
	include 'engine.php';
	error_log('engine foi incluido no logout.php');
	session_unset();
	session_destroy();
	error_log('redirected to login php from logout php');
	header('Location:login.php');
