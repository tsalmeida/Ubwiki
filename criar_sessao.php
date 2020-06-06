<?php

	$session_check = session_status();
	error_log('session status:');
	error_log($session_check);

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
		error_log('user info has not been set, is getting set now:');
		$_SESSION['user_info'] = 'visitante';
		error_log($_SESSION['user_info']);
	}
