<?php

	if (session_status() == PHP_SESSION_NONE) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
	}

	include 'templates/criar_conn.php';
	
	$pagina_tipo = 'index';
	$user_email = false;
	$new_user = false;
	$special = false;
	$user_id = false;
	
	if (isset($_GET['special'])) {
		$special = $_GET['special'];
		$usuarios = $conn->query("SELECT id, email, apelido FROM Usuarios WHERE special = '$special' AND special IS NOT NULL");
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$_SESSION['user_id'] = $usuario['id'];
				$_SESSION['user_email'] = $usuario['email'];
			}
		}
	}
	
	if (!isset($_SESSION['user_email'])) {
		if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
			$_SESSION['thinkific_email'] = $_POST['email'];
			$_SESSION['thinkific_bora'] = $_POST['bora'];
		}
	}
	
	header('Location:ubwiki.php');
	exit();
	
	?>
