<?php
	if (!isset($_SESSION['user_email'])) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
	}

	include 'templates/criar_conn.php';
	
	$user_email = false;
	$new_user = false;
	$special = false;
	
	if (isset($_GET['special'])) {
		$special = $_GET['special'];
		$usuarios = $conn->query("SELECT id, email, apelido FROM Usuarios WHERE special = '$special'");
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$_SESSION['user_id'] = $usuario['id'];
				$_SESSION['user_email'] = $usuario['email'];
				$check1 = $_SESSION['user_id'];
				header('Location:escritorio.php');
				exit();
			}
		}
	}
	
	if (!isset($_SESSION['user_email'])) {
		if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
			$usuario_email = $_POST['email'];
			$usuarios = $conn->query("SELECT id FROM Usuarios WHERE email = '$usuario_email'");
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$_SESSION['user_id'] = $usuario['id'];
					$_SESSION['user_email'] = $usuario_email;
					header('Location:escritorio.php');
					exit();
				}
			} else {
				$conn->query("INSERT INTO Usuarios (email) VALUES ('$usuario_email')");
				$_SESSION['user_id'] = $conn->insert_id;
				$_SESSION['user_email'] = $usuario_email;
 			}
		} else {
			header('Location:pagina.php?pagina_id=2'); // página que explica a necessidade de fazer login no site da Ubique.
			exit();
		}
	}
