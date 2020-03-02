<?php
	
	if (isset($_GET['lg'])) {
		$user_language = $_GET['lg'];
		$user_language_titulo = convert_language($user_language);
	} else {
		$user_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$user_language_titulo = convert_language($user_language);
	}
	if ($user_language_titulo == false) {
		$user_language = 'pt';
		$user_language_titulo = convert_language($user_language);
	}
	
	if ($user_tipo != 'admin') {
		$user_language = 'pt';
		$user_language_titulo = convert_language($user_language);
	}
	
	function return_chave_titulo($chave_id) {
		if ($chave_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$chaves = $conn->query("SELECT chave FROM Translation_chaves WHERE id = $chave_id");
		if ($chaves->num_rows > 0) {
			while ($chave = $chaves->fetch_assoc()) {
				$chave_titulo = $chave['chave'];
				return $chave_titulo;
			}
		}
	}
	
	function translate_pagina($user_language)
	{
		if ($user_language == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$chaves_traduzidas = $conn->query("SELECT id, chave_id, chave_string, traducao FROM Chaves_traduzidas WHERE lingua = '$user_language'");
		$resultados = array();
		if ($chaves_traduzidas->num_rows > 0) {
			while ($chave_traduzida = $chaves_traduzidas->fetch_assoc()) {
				$chave_traduzida_traducao_id = $chave_traduzida['id'];
				$chave_traduzida_id = $chave_traduzida['chave_id'];
				$chave_traduzida_string = $chave_traduzida['chave_string'];
				$chave_traduzida_traducao = $chave_traduzida['traducao'];
				if ($chave_traduzida_string == false) {
					$chave_traduzida_string = return_chave_titulo($chave_traduzida_id);
					$conn->query("UPDATE Chaves_traduzidas SET chave_string = '$chave_traduzida_string' WHERE id = $chave_traduzida_traducao_id");
				}
				$resultados[$chave_traduzida_string]=$chave_traduzida_traducao;
			}
		}
		return $resultados;
	}
	
	function return_traducao($chave_id, $language) {
		if (($chave_id == false) || ($language == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$chaves = $conn->query("SELECT traducao FROM Chaves_traduzidas WHERE chave_id = $chave_id AND lingua = '$language'");
		if ($chaves->num_rows > 0) {
			while ($chave = $chaves->fetch_assoc()) {
				$chave_traducao = $chave['traducao'];
				return $chave_traducao;
			}
		}
		return false;
	}
	
	function return_texto_pagina_login($user_language)
	{
		if ($user_language == 'pt') {
			return 548;
		} elseif ($user_language == 'en') {
			return 2635;
		} elseif ($user_language == 'es') {
			return 2636;
		}
	}
	
	function return_texto_ambientes($ambiente, $user_language)
	{
		switch ($ambiente) {
			case 'ambientes':
				switch ($user_language) {
					case 'en':
						return 2631;
					case 'pt':
						return 1218;
					case 'es':
						return 2633;
				}
			case 'escritorio':
				switch ($user_language) {
					case 'en':
						return 2607;
					case 'pt':
						return 1220;
					case 'es':
						return 2621;
				}
			case 'cursos':
				switch ($user_language) {
					case 'en':
						return 2609;
					case 'pt':
						return 1222;
					case 'es':
						return 2623;
				}
			case 'paginaslivres':
				switch ($user_language) {
					case 'en':
						return 2611;
					case 'pt':
						return 1224;
					case 'es':
						return 2625;
				}
			case 'biblioteca':
				switch ($user_language) {
					case 'en':
						return 2613;
					case 'pt':
						return 1226;
					case 'es':
						return 2629;
				}
			case 'forum':
				switch ($user_language) {
					case 'en':
						return 2605;
					case 'pt':
						return 1228;
					case 'es':
						return 2627;
				}
			case 'mercado':
				switch ($user_language) {
					case 'en':
						return 2615;
					case 'pt':
						return 1230;
					case 'es':
						return 2619;
				}
			
		}
	}
