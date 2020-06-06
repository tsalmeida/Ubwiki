<?php
	$pagina_tipo = 'logout';
	include 'engine.php';
	session_unset();
	session_destroy();
	header('Location:index.php');
	exit();
?>
