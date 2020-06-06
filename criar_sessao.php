<?php

	$sessionpath = getcwd();
	$sessionpath .= '/../sessions/';
	session_save_path($sessionpath);
	session_start();

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}
