<?php

	$sessionpath = getcwd();
	$sessionpath .= '/../sessions/';

	if (session_status() == PHP_SESSION_NONE) {
		error_log('first php session none:');
		session_save_path($sessionpath);
		//session_start();
		$_SESSION['user_info'] = 'visitante';
		error_log('session was started inside the PHP_SESSION_NONE');
		error_log("user_info dentro do session_status: {$_SESSION['user_info']}");
	}

	if (!isset($_SESSION['user_info'])) {
		error_log('user info has not been set, is getting set now:');
		$_SESSION['user_info'] = 'visitante';
		error_log($_SESSION['user_info']);
	}

	if (session_status() == PHP_SESSION_NONE) {
		error_log('second php session none:');
		session_save_path($sessionpath);
		//session_start();
		$_SESSION['user_info'] = 'visitante';
		error_log('second session was started inside the PHP_SESSION_NONE');
		error_log("user_info dentro do segundo session_status: {$_SESSION['user_info']}");
	}

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}

	error_log("user_info fora do session_status: {$_SESSION['user_info']}");

	session_save_path($sessionpath);
	session_start();

	if (!isset($_SESSION['user_info'])) {
		$_SESSION['user_info'] = 'visitante';
	}

	error_log("user_info fora do session_status depois do failsafe: {$_SESSION['user_info']}");

