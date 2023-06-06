<?php

	if (session_status() == 1) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions/';
		$sessionLifetime = 24 * 60 * 60; // 24 hours
		setcookie(session_name(),session_id(),time()+$sessionLifetime);
		session_save_path($sessionpath);
		session_start();
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}
