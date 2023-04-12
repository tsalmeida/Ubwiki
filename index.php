<?php
	$pagina_tipo = 'index';
	$pagina_id = 1;

	include 'criar_sessao.php';

	include 'templates/criar_conn.php';

	$user_email = false;
	$new_user = false;
	$special = false;
	$user_id = false;

	if (isset($_GET['special'])) {
		$special = $_GET['special'];
		$query = prepare_query("SELECT id, email, apelido FROM usuarios WHERE special = '$special' AND special IS NOT NULL");
		$usuarios = $conn->query($query);
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$_SESSION['user_info'] = 'login';
				$_SESSION['user_id'] = $usuario['id'];
				$_SESSION['user_email'] = $usuario['email'];
				header('Location:escritorio.php');
				exit();
			}
		}
	}

	if (isset($_GET['credito'])) {
		$_SESSION['credito'] = $_GET['credito'];
	}
	/*
	if (!isset($_SESSION['user_email'])) {
		if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
			$_SESSION['thinkific_email'] = $_POST['email'];
			$_SESSION['thinkific_bora'] = $_POST['bora'];
		}
	}*/
	if ($_SESSION['user_info'] === true) {
		header('Location:escritorio.php');
	} else {
		header('Location:ubwiki.php');
	}
	exit();

?>
