<?php
	$pagina_tipo = 'logout';
	include 'engine.php';
	session_unset();
	session_destroy();
	unset($_SESSION);
	$sessionpath = getcwd();
	$sessionpath .= '/../sessions';
	session_save_path($sessionpath);
	session_start();
	$_SESSION['user_info'] = 'visitante';
	header('Location:login.php');
?>
