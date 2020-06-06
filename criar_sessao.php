<?php

	$sessionpath = getcwd();
	$sessionpath .= '/../sessions';
	session_save_path($sessionpath);
	session_start();
	error_log($pagina_tipo);
	if (session_status() == PHP_SESSION_NONE) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
		$_SESSION['user_info'] = 'visitante';
		error_log("SESSAO CRIADA EM $pagina_tipo");
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}