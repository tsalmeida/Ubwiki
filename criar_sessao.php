<?php

	$session_check = session_status();
	error_log('session status:');
	error_log($session_check);

	//session_start();

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
	}

	error_log("user_info fora do session_status: {$_SESSION['user_info']}");

	if (!isset($_SESSION['user_info'])) {
		error_log('user info has not been set, is getting set now:');
		$_SESSION['user_info'] = 'visitante';
		error_log($_SESSION['user_info']);
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}

	/*error_log("E agora session_start declared again, followed by another session_status check:");
	session_start();
	error_log(session_status());*/

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}

