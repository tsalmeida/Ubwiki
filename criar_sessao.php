<?php

	$sessionpath = getcwd();
	$sessionpath .= '/../sessions/';
	session_save_path($sessionpath);

	session_start();

	if (session_status() == 1) {
		error_log('first php session none:');
		session_start();
		$_SESSION['user_info'] = 'visitante';
		error_log('session was started inside the PHP_SESSION_NONE. session status:');
		error_log(session_status());
	} elseif (session_status() == 2) {
		error_log('at this point, session_status equals 2');
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}
