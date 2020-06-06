<?php

	$sessionpath = getcwd();
	$sessionpath .= '/../sessions/';

	session_save_path($sessionpath);
	session_start();

	if (session_status() == PHP_SESSION_NONE) {
		session_save_path($sessionpath);
		session_start();
		$_SESSION['user_info'] = 'visitante';
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}