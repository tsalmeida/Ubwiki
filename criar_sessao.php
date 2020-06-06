<?php

	if (session_status() == PHP_SESSION_NONE) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
		$_SESSION['user_info'] = 'visitante';
	} /*else {
		session_start();
	}*/

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}