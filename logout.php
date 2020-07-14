<?php
	$pagina_tipo = 'logout';
	include 'engine.php';
	session_unset();
	session_destroy();
	if (isset($_GET['pagina_id'])) {
		$pagina_id = $_GET['pagina_id'];
		if ($pagina_id != false) {
			header("Location:pagina.php?pagina_id=$pagina_id");
			exit();
		}
	}
	header('Location:index.php');
	exit();
?>
