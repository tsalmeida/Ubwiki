<?php
	
	if (isset($_POST['select_language'])) {
		$user_language = $_POST['select_language'];
		$_SESSION['lg'] = $user_language;
		unset($_SESSION['pagina_translated']);
		$user_language_titulo = convert_language($user_language);
		if ($user_id != false) {
			$query = "UPDATE Usuarios SET language = '$user_language' WHERE id = $user_id";
			$conn->query($query);
		}
	}
	
	if (isset($_SESSION['lg'])) {
		$user_language = $_SESSION['lg'];
		$user_language_titulo = convert_language($user_language);
	} else {
		$user_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$user_language_titulo = convert_language($user_language);
	}
	if ($user_language_titulo == false) {
		$user_language = 'en';
		$user_language_titulo = convert_language($user_language);
	}
	
	function return_chave_titulo($chave_id)
	{
		if ($chave_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT chave FROM Translation_chaves WHERE id = $chave_id");
		$chaves = $conn->query($query);
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
		$query = prepare_query("SELECT id, chave_id, chave_string, traducao FROM Chaves_traduzidas WHERE lingua = '$user_language'");
		$chaves_traduzidas = $conn->query($query);
		$resultados = array();
		if ($chaves_traduzidas->num_rows > 0) {
			while ($chave_traduzida = $chaves_traduzidas->fetch_assoc()) {
				$chave_traduzida_traducao_id = $chave_traduzida['id'];
				$chave_traduzida_id = $chave_traduzida['chave_id'];
				$chave_traduzida_string = $chave_traduzida['chave_string'];
				$chave_traduzida_traducao = $chave_traduzida['traducao'];
				if ($chave_traduzida_string == false) {
					$chave_traduzida_string = return_chave_titulo($chave_traduzida_id);
					$query = prepare_query("UPDATE Chaves_traduzidas SET chave_string = '$chave_traduzida_string' WHERE id = $chave_traduzida_traducao_id");
					$conn->query($query);
				}
				$resultados[$chave_traduzida_string] = $chave_traduzida_traducao;
			}
		}
		return $resultados;
	}

	function return_texto_pagina_login($user_language)
	{
		if ($user_language == 'pt') {
			return 548;
		} elseif ($user_language == 'en') {
			return 2635;
		} elseif ($user_language == 'es') {
			return 2636;
		} elseif ($user_language == 'fr') {
			return 548;
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
					case 'fr':
						return 2631;
				}
			case 'escritorio':
				switch ($user_language) {
					case 'en':
						return 2607;
					case 'pt':
						return 1220;
					case 'es':
						return 2621;
					case 'fr':
						return 2607;
				}
			case 'cursos':
				switch ($user_language) {
					case 'en':
						return 2609;
					case 'pt':
						return 1222;
					case 'es':
						return 2623;
					case 'fr':
						return 2609;
				}
			case 'paginaslivres':
				switch ($user_language) {
					case 'en':
						return 2611;
					case 'pt':
						return 1224;
					case 'es':
						return 2625;
					case 'fr':
						return 2611;
				}
			case 'biblioteca':
				switch ($user_language) {
					case 'en':
						return 2613;
					case 'pt':
						return 1226;
					case 'es':
						return 2629;
					case 'fr':
						return 2613;
				}
			case 'forum':
				switch ($user_language) {
					case 'en':
						return 2605;
					case 'pt':
						return 1228;
					case 'es':
						return 2627;
					case 'fr':
						return 2605;
				}
			case 'mercado':
				switch ($user_language) {
					case 'en':
						return 2615;
					case 'pt':
						return 1230;
					case 'es':
						return 2619;
					case 'fr':
						return 2615;
				}
		}
	}
