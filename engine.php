<?php

	if (!isset($pagina_tipo)) {
		$pagina_tipo = false;
	}

	include 'criar_sessao.php';
	include 'functions.php';

	if (!isset($user_email)) {
		$user_email = false;
	}

	$user_logged_out = false;

	if (!isset($_SESSION['user_email'])) {
		$user_email = false;
		if ((!isset($_POST['login_email'])) && (!isset($_POST['thinkific_login']))) {
			if (($user_email == false) && ($pagina_tipo != 'logout') && ($pagina_tipo != 'login') && ($pagina_tipo != 'index')) {
				$user_logged_out = true;
				$user_id = false;
				$user_tipo = 'visitante';
				$user_email = false;
//				header('Location:logout.php');
//				exit();
			}
		}
	} else {
		$user_email = $_SESSION['user_email'];
	}

	include 'templates/criar_conn.php';

	if (isset($_POST['thinkific_login'])) {
		$thinkific_login = $_POST['thinkific_login'];
		$thinkific_senha = $_POST['thinkific_senha'];
		$encrypted = password_hash($thinkific_senha, PASSWORD_DEFAULT);
		$query = prepare_query("UPDATE Usuarios SET senha = '$encrypted' WHERE email = '$thinkific_login'");
		$set_password = $conn->query($query);
		if ($set_password == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['login_email'])) {
		$login_email = $_POST['login_email'];
		$login_senha = $_POST['login_senha'];
		$login_senha2 = false;
		if (isset($_POST['login_senha2'])) {
			$login_senha2 = $_POST['login_senha2'];
		}
		if ($login_senha2 == false) {
			$login_origem = $_POST['login_origem'];
			$query = "SELECT senha, origem FROM Usuarios WHERE email = '$login_email'";
			$query = prepare_query($query);
			$hashes = $conn->query($query);
			if ($hashes->num_rows > 0) {
				while ($hash = $hashes->fetch_assoc()) {
					$hash_senha = $hash['senha'];
					$hash_origem = $hash['origem'];
					$check = password_verify($login_senha, $hash_senha);
					if ($check == true) {
						if (($hash_origem == false) || ($hash_origem == 'confirmado') || ($hash_origem == 'thinkific')) {
							$_SESSION['user_email'] = $login_email;
							$user_email = $login_email;
							$_SESSION['user_info'] = 'login';
							echo true;
						} else {
							echo 'confirmacao';
						}
					} elseif (($hash_origem == 'thinkific') && (is_null($hash_senha))) {
						echo 'thinkific';
					} else {
						echo false;
					}
				}
			} else {
				echo 'novo_usuario';
			}
		} else {
			$encrypted = password_hash($login_senha, PASSWORD_DEFAULT);
			$query = prepare_query("SELECT id FROM Usuarios WHERE email = '$login_email'");
			$check_preexistente = $conn->query($query);
			if ($check_preexistente->num_rows == 0) {
				$query = prepare_query("INSERT INTO Usuarios (tipo, email, senha) VALUES ('estudante', '$login_email', '$encrypted')");
				$check = $conn->query($query);
				if ($check == true) {
					$_SESSION['user_email'] = $login_email;
					$user_email = $login_email;
					$_SESSION['user_info'] = 'login';
					echo true;
				} else {
					echo false;
				}
			}
		}
	}

	$user_revisor = false;

	$do_login = false;
	if (!isset($_SESSION['user_info'])) {
		$do_login = true;
	}
	if ($_SESSION['user_info'] === 'login') {
		$do_login = true;
	}
	if ($do_login == true) {
		unset($_SESSION['user_opcoes']);
		$_SESSION['user_info'] = false;
		if ($user_email != false) {
			$query = "SELECT * FROM Usuarios WHERE email = '$user_email'";
			$query = prepare_query($query);
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$_SESSION['user_info'] = true;
					$_SESSION['user_id'] = $usuario['id'];
					$_SESSION['user_tipo'] = $usuario['tipo'];
					$_SESSION['user_revisor'] = check_revisor($_SESSION['user_tipo']);
					$_SESSION['user_criacao'] = $usuario['criacao'];
					$_SESSION['user_apelido'] = $usuario['apelido'];
					$_SESSION['user_nome'] = $usuario['nome'];
					$_SESSION['user_sobrenome'] = $usuario['sobrenome'];
					$_SESSION['raiz_ativa'] = $usuario['raiz_ativa'];
					if (isset($_SESSION['user_language'])) {
						if ($_SESSION['user_language'] != $usuario['language']) {
							unset($_SESSION['pagina_translated']);
						}
					}
					$_SESSION['user_language'] = $usuario['language'];
					$_SESSION['user_wallet'] = (int)return_wallet_value($_SESSION['user_id']);
					$_SESSION['user_escritorio'] = return_pagina_id($_SESSION['user_id'], 'escritorio');
					$_SESSION['user_lounge'] = return_lounge_id($_SESSION['user_id']);
					$_SESSION['user_editor_paginas_id'] = array();
				}
			}
		} else {
			$_SESSION['user_info'] = 'visitante';
			$user_id = false;
			$user_tipo = false;
			$user_criacao = false;
			$user_apelido = false;
			$user_sobrenome = false;
			$user_wallet = false;
			$user_escritorio = false;
			$user_lounge = false;
			$user_avatar_icone = 'fa-user';
			$user_avatar_cor = 'link-primary';
			$_SESSION['raiz_ativa'] = 1118;
			$user_opcoes = array();
			$_SESSION['user_editor_paginas_id'] = array();
		}
	}

	if ($_SESSION['user_info'] === true) {
		$user_id = $_SESSION['user_id'];
		$user_tipo = $_SESSION['user_tipo'];
		$user_revisor = $_SESSION['user_revisor'];
		$user_criacao = $_SESSION['user_criacao'];
		$user_apelido = $_SESSION['user_apelido'];
		$user_nome = $_SESSION['user_nome'];
		$user_sobrenome = $_SESSION['user_sobrenome'];
		$user_language = $_SESSION['user_language'];
		$user_wallet = $_SESSION['user_wallet'];
		$user_lounge = $_SESSION['user_lounge'];
		if (!isset($_SESSION['user_language'])) {
			$_SESSION['user_language'] = $user_language;
		}
		if (!isset($_SESSION['user_bookmarks'])) {
			$_SESSION['user_bookmarks'] = return_user_bookmarks($user_id);
		}
		if (!isset($_SESSION['user_completed'])) {
			$_SESSION['user_completed'] = return_user_completed($user_id);
		}
		$user_bookmarks = $_SESSION['user_bookmarks'];
		$user_completed = $_SESSION['user_completed'];
		$user_escritorio = $_SESSION['user_escritorio'];
		if (!isset($_SESSION['user_areas_interesse'])) {
			$_SESSION['user_areas_interesse'] = return_user_areas_interesse($user_escritorio);
		}
		$user_areas_interesse = $_SESSION['user_areas_interesse'];
		if (!isset($_SESSION['user_opcoes'])) {
			$_SESSION['user_opcoes'] = return_user_opcoes($_SESSION['user_id']);
			if (isset($_SESSION['user_opcoes']['avatar'])) {
				$_SESSION['user_avatar_icone'] = $_SESSION['user_opcoes']['avatar'][1];
			} else {
				$_SESSION['user_avatar_icone'] = 'fa-user-tie';
			}
			if (isset($_SESSION['user_opcoes']['avatar_cor'])) {
				$_SESSION['user_avatar_cor'] = $_SESSION['user_opcoes']['avatar_cor'][1];
			} else {
				$_SESSION['user_avatar_cor'] = 'link-primary';
			}
		}
		$user_opcoes = $_SESSION['user_opcoes'];
	} elseif ($_SESSION['user_info'] == 'visitante') {
		$user_id = false;
		$user_tipo = false;
		$user_criacao = false;
		$user_apelido = false;
		$user_sobrenome = false;
		$user_wallet = false;
		$user_escritorio = false;
		$user_lounge = false;
		$user_bookmarks = array();
		$user_completed = array();
		$user_areas_interesse = array();
		$user_opcoes = array();
		$_SESSION['user_editor_paginas_id'] = array();
		$_SESSION['user_avatar_icone'] = 'fa-user';
		$_SESSION['user_avatar_cor'] = 'link-primary';
	}
	$user_avatar_icone = $_SESSION['user_avatar_icone'];
	$user_avatar_cor = $_SESSION['user_avatar_cor'];

	if (!isset($user_opcoes['quill_colors'])) {
		$user_opcoes['quill_colors'] = 'default';
	}

	if (!isset($_SESSION['user_opcoes']['quill_colors'])) {
		$_SESSION['user_opcoes']['quill_colors'][1] = 'default';
		if ($user_id != false) {
			$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'quill_colors', 'default')");
		}
	}

	$user_quill_colors = $_SESSION['user_opcoes']['quill_colors'][1];

	if (!isset($_SESSION['user_editor_paginas_id'])) {
		$_SESSION['user_editor_paginas_id'] = array();
	}

	if (isset($_SESSION['adicionar_privilegio_edicao'])) {
		array_push($_SESSION['user_editor_paginas_id'], $_SESSION['adicionar_privilegio_edicao']);
		$_SESSION['user_editor_paginas_id'] = array_unique($_SESSION['user_editor_paginas_id']);
		unset($_SESSION['adicionar_privilegio_edicao']);
	}

	if (!isset($_SESSION['raiz_ativa'])) {
		$_SESSION['raiz_ativa'] = 1118;
	}
	$raiz_ativa = $_SESSION['raiz_ativa'];

	if (!isset($_SESSION['raiz_ativa_info'])) {
		$_SESSION['raiz_ativa_info'] = return_pagina_info($raiz_ativa, true);
	}

	$raiz_tipo = $_SESSION['raiz_ativa_info'][2];
	$raiz_item_id = $_SESSION['raiz_ativa_info'][1];
	$raiz_titulo = $_SESSION['raiz_ativa_info'][6];
	$raiz_sigla = false;
	if ($raiz_tipo == 'curso') {
		if (!isset($_SESSION['raiz_ativa_info']['sigla'])) {
			$raiz_sigla = return_curso_sigla($raiz_item_id);
			$_SESSION['raiz_ativa_info']['sigla'] = $raiz_sigla;
		} else {
			$raiz_sigla = $_SESSION['raiz_ativa_info']['sigla'];
		}
	}

	if (!isset($user_opcoes['hide_navbar'][0])) {
		$user_opcoes['hide_navbar'][0] = false;
	}
	if (!isset($user_opcoes['texto_justificado'][0])) {
		$user_opcoes['texto_justificado'][0] = false;
	}

	if ($user_opcoes != array()) {
		$opcao_texto_justificado_value = $user_opcoes['texto_justificado'][0];
		$opcao_hide_navbar = $user_opcoes['hide_navbar'][0];
	} else {
		$opcao_texto_justificado_value = false;
		$opcao_hide_navbar = false;
	}

	if (!isset($_SESSION['acesso_especial'])) {
		$_SESSION['acesso_especial'] = false;
	}

	include 'money_engine.php';

	if ($user_id != false) {
		if (!isset($_SESSION['carregar_carrinho'])) {
			$query = prepare_query("SELECT id FROM Carrinho WHERE user_id = $user_id AND estado = 1");
			$user_carrinho = $conn->query($query);
			if ($user_carrinho->num_rows > 0) {
				$carregar_carrinho = true;
			} else {
				$carregar_carrinho = false;
			}
			$_SESSION['carregar_carrinho'] = $carregar_carrinho;
		} else {
			$carregar_carrinho = $_SESSION['carregar_carrinho'];
		}
	}

	include 'templates/translation.php';

	if (!isset($_SESSION['pagina_translated'])) {
		$pagina_translated = translate_pagina($user_language);
		$_SESSION['pagina_translated'] = $pagina_translated;
	} else {
		$pagina_translated = $_SESSION['pagina_translated'];
	}

	$all_buttons_classes = "btn text-center mb-3";
	$button_classes = "$all_buttons_classes btn-primary";
	$button_small = 'brn rounded btn-sm text-center';
	$button_classes_light = "$all_buttons_classes btn-light";
	$button_classes_info = "$all_buttons_classes btn-info";
	$button_classes_red = "$all_buttons_classes btn-danger";
	$select_classes = "browser-default custom-select mt-2";
	$coluna_todas = false;
	$coluna_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$coluna_maior_classes = "col-lg-9 col-md-10 col-sm-11 $coluna_todas";
	$coluna_media_classes = "col-lg-7 col-md-10 col-sm-11 $coluna_todas";
	$coluna_pouco_maior_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$row_classes = "pt-3 pb-5";

	$tag_ativa_classes = 'text-dark m-1 p-2 rounded remover_tag w-auto';
	$tag_inativa_classes = 'text-dark m-1 p-2 rounded adicionar_tag w-auto';
	$tag_neutra_classes = 'text-dark m-1 p-2 rounded w-auto';
	$tag_inativa_classes2 = 'text-dark m-1 p-2 rounded adicionar_tag2 w-auto';

	if (isset($_POST['bookmark_change'])) {
		$bookmark_change = $_POST['bookmark_change'];
		$bookmark_pagina_id = $_POST['bookmark_pagina_id'];
		$query = prepare_query("SELECT id FROM Bookmarks WHERE user_id = $user_id AND pagina_id = $bookmark_pagina_id AND active = 1");
		$bookmarks = $conn->query($query);
		if ($bookmarks->num_rows > 0) {
			while ($bookmark = $bookmarks->fetch_assoc()) {
				$bookmark_id = $bookmark['id'];
				$query = prepare_query("UPDATE Bookmarks SET active = 0 WHERE id = $bookmark_id");
				$conn->query($query);
				break;
			}
		}
		$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $bookmark_pagina_id, 'bookmark', $bookmark_change)");
		$conn->query($query);
		$query = prepare_query("INSERT INTO Bookmarks (user_id, pagina_id, bookmark, active) VALUES ($user_id, $bookmark_pagina_id, $bookmark_change, 1)");
		$conn->query($query);
		$_SESSION['user_bookmarks'] = return_user_bookmarks($user_id);
		$user_bookmarks = $_SESSION['user_bookmarks'];
	}

	if (isset($_POST['completed_change'])) {
		$completed_change = $_POST['completed_change'];
		$completed_pagina_id = $_POST['completed_pagina_id'];
		$query = prepare_query("SELECT id FROM Completed WHERE user_id = $user_id AND pagina_id = $completed_pagina_id AND active = 1");
		$completos = $conn->query($query);
		if ($completos->num_rows > 0) {
			while ($completo = $completos->fetch_assoc()) {
				$completed_id = $completo['id'];
				$query = prepare_query("UPDATE Completed SET active = 0 WHERE id = $completed_id");
				$conn->query($query);
				break;
			}
		}
		$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $completed_pagina_id, 'completed', $completed_change)");
		$conn->query($query);
		$query = prepare_query("INSERT INTO Completed (user_id, pagina_id, estado, active) VALUES ($user_id, $completed_pagina_id, $completed_change, 1)");
		$conn->query($query);
		$_SESSION['user_completed'] = return_user_completed($user_id);
		$user_completed = $_SESSION['user_completed'];
//		print serialize($user_completed);
	}

	if (isset($_POST['sbcommand'])) {
		$busca_curso_id = base64_decode($_POST['sbcurso']);
		$command = base64_decode($_POST['sbcommand']);
		$command = utf8_encode($command);
		$found = false;
		$query = prepare_query("SELECT pagina_id FROM Searchbar WHERE curso_id = $busca_curso_id AND chave = '$command' ORDER BY ordem");
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$pagina_id = $row["pagina_id"];
				echo "foundfoundfoundfpagina.php?pagina_id=$pagina_id";
				return;
			}
		}
		$index = 500;
		$winner = 0;
		$query = prepare_query("SELECT chave FROM Searchbar WHERE curso_id = $busca_curso_id AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
		$result = $conn->query($query);
		$commandlow = mb_strtolower($command);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row["chave"];
				$chavelow = mb_strtolower($chave);
				$check = levenshtein($chavelow, $commandlow, 1, 1, 1);
				if (strpos($chavelow, $commandlow) !== false) {
					echo "notfoundnotfound$chave";
					return;
				} elseif ($check < $index) {
					$index = $check;
					$winner = $chave;
				}
			}
			$length = strlen($winner);
			$length = $length / 3;
			if ($index < $length) {
				echo "notfoundnotfound$winner";
				return;
			} else {
				echo "foundfoundfoundfbusca.php?busca=$command";
			}
		}
		return;
	}

	if ((isset($_POST['novo_texto_titulo'])) && (isset($_POST['novo_texto_titulo_id']))) {
		include 'templates/criar_conn.php';

		$novo_texto_titulo = $_POST['novo_texto_titulo'];
		$novo_texto_titulo = mysqli_real_escape_string($conn, $novo_texto_titulo);
		$novo_texto_titulo_id = $_POST['novo_texto_titulo_id'];
		$query = prepare_query("UPDATE Textos SET titulo = '$novo_texto_titulo' WHERE id = $novo_texto_titulo_id");
		$conn->query($query);
		echo true;
	}

	if (isset($_POST['nova_imagem'])) {
		error_log('does this ever happen?');
		$nova_imagem_link = $_POST['nova_imagem'];
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_subtipo = $_POST['nova_imagem_subtipo'];
		if ($nova_imagem_titulo == false) {
			$nova_imagem_titulo = 'Não há título registrado';
		}
		$user_id = $_POST['user_id'];
		$page_id = $_POST['page_id'];
		$pagina_tipo = $_POST['contexto'];
		$nossa_copia = adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $page_id, $user_id, $pagina_tipo, $curso_id, false);
		echo $nossa_copia;
	}

	if (isset($_POST['questao_id'])) {
		$questao_tipo = $_POST['questao_tipo'];
		$simulado_id = $_POST['simulado_id'];

		$user_id = $_POST['user_id'];
		$questao_curso_id = $_POST['curso_id'];
		$questao_id = $_POST['questao_id'];
		$questao_numero = $_POST['questao_numero'];

		if ($questao_tipo == 1) {
			$item1_resposta = $_POST['item1'];
			$item2_resposta = $_POST['item2'];
			$item3_resposta = $_POST['item3'];
			$item4_resposta = $_POST['item4'];
			$item5_resposta = $_POST['item5'];
			$query = prepare_query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, item1, item2, item3, item4, item5) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $item1_resposta, $item2_resposta, $item3_resposta, $item4_resposta, $item5_resposta)");
			$conn->query($query);
			echo true;
		} elseif ($questao_tipo == 2) {
			$resposta = $_POST['resposta'];
			$query = prepare_query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, multipla) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $resposta)");
			$conn->query($query);
			echo true;
		} elseif ($questao_tipo == 3) {
			$redacao_html = $_POST['redacao_html'];
			$redacao_text = $_POST['redacao_text'];
			$redacao_content = $_POST['redacao_content'];
			$redacao_html = mysqli_real_escape_string($conn, $redacao_html);
			$redacao_text = mysqli_real_escape_string($conn, $redacao_text);
			$redacao_content = mysqli_real_escape_string($conn, $redacao_content);
			$query = prepare_query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, redacao_html, redacao_text, redacao_content) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, '$redacao_html', '$redacao_text', '$redacao_content')");
			$conn->query($query);
			echo true;
		}
		echo false;
	}

	if (isset($_POST['busca_referencias'])) {
		$busca_referencias = $_POST['busca_referencias'];
		if (isset($_POST['busca_referencias_tipo'])) {
			$busca_referencias_tipo = $_POST['busca_referencias_tipo'];
		} else {
			$busca_referencias_tipo = false;
		}
		$busca_referencias = mysqli_real_escape_string($conn, $busca_referencias);
		$busca_resultados = false;
		if ($busca_referencias_tipo == false) {
			$query = prepare_query("SELECT titulo FROM Elementos WHERE titulo = '$busca_referencias' AND (tipo = 'referencia' OR tipo = 'video' OR tipo = 'album_musica')");
			$referencia_exata = $conn->query($query);
		} else {
			$query = prepare_query("SELECT titulo FROM Elementos WHERE titulo = '$busca_referencias' AND tipo = '$busca_referencias_tipo'");
			$referencia_exata = $conn->query($query);
		}
		if ($referencia_exata->num_rows == 0) {
			$busca_resultados .= "<div class='col-12 pl-0'><button type='button' id='criar_referencia' name='criar_referencia' class='btn text-center btn-info btn-sm mb-2' value='$busca_referencias'>{$pagina_translated['Referência não encontrada, criar nova?']}</button></div>";
		}
		if ($busca_referencias_tipo == false) {
			$query = prepare_query("SELECT id, etiqueta_id, compartilhamento, titulo, autor, tipo, user_id FROM Elementos WHERE titulo LIKE '%{$busca_referencias}%'");
			$elementos = $conn->query($query);
		} else {
			$query = prepare_query("SELECT id, etiqueta_id, compartilhamento, titulo, autor, tipo, user_id FROM Elementos WHERE titulo LIKE '%{$busca_referencias}%' AND tipo = '$busca_referencias_tipo'");
			$elementos = $conn->query($query);
		}
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				$elemento_etiqueta_id = $elemento['etiqueta_id'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				$elemento_tipo = $elemento['tipo'];
				$elemento_user_id = $elemento['user_id'];
				$elemento_compartilhamento = $elemento['compartilhamento'];
				if ($elemento_compartilhamento == 'privado') {
					if ($elemento_user_id != $user_id) {
						continue;
					}
				}
				$elemento_cor_icone = return_etiqueta_cor_icone($elemento_tipo);
				$elemento_cor = $elemento_cor_icone[0];
				$elemento_icone = $elemento_cor_icone[1];
				$busca_resultados .= "<a href='javascript:void(0)' class='$tag_inativa_classes $elemento_cor acrescentar_referencia_bibliografia' value='$elemento_etiqueta_id'><i class='far $elemento_icone fa-fw'></i> $elemento_titulo</a>";
			}
		}
		echo $busca_resultados;
	}

	if (isset($_POST['adicionar_referencia_titulo'])) {
		$adicionar_referencia_titulo = $_POST['adicionar_referencia_titulo'];
		$adicionar_referencia_titulo = mysqli_real_escape_string($conn, $adicionar_referencia_titulo);
		$adicionar_referencia_autor = $_POST['adicionar_referencia_autor'];
		$adicionar_referencia_autor = mysqli_real_escape_string($conn, $adicionar_referencia_autor);
		$adicionar_referencia_link = $_POST['adicionar_referencia_link'];
		$adicionar_referencia_link = mysqli_real_escape_string($conn, $adicionar_referencia_link);
		$adicionar_referencia_tipo = $_POST['adicionar_referencia_tipo'];
		$adicionar_referencia_subtipo = $_POST['adicionar_referencia_subtipo'];
		$adicionar_referencia_contexto = $_POST['adicionar_referencia_contexto'];
		$adicionar_referencia_pagina_id = $_POST['adicionar_referencia_pagina_id'];

		$nova_etiqueta = criar_etiqueta($adicionar_referencia_titulo, $adicionar_referencia_autor, $adicionar_referencia_tipo, $user_id, true, $adicionar_referencia_link, $adicionar_referencia_subtipo);
		$nova_etiqueta_id = $nova_etiqueta[0];
		$nova_etiqueta_autor_id = $nova_etiqueta[1];
		$nova_etiqueta_elemento_id = $nova_etiqueta[2];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($adicionar_referencia_pagina_id, '$adicionar_referencia_contexto', $nova_etiqueta_elemento_id, '$adicionar_referencia_tipo', '$adicionar_referencia_subtipo', $user_id)");
		$conn->query($query);
		echo true;
	}

	if (isset($_POST['pagina_nova_etiqueta_id'])) {
		$nova_etiqueta_id = $_POST['pagina_nova_etiqueta_id'];
		$nova_etiqueta_pagina_id = $_POST['nova_etiqueta_pagina_id'];
		$novo_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
		$novo_elemento_info = return_elemento_info($novo_elemento_id);
		$novo_elemento_tipo = $novo_elemento_info[3];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($nova_etiqueta_pagina_id, $novo_elemento_id, '$novo_elemento_tipo', $user_id)");
		$conn->query($query);
		echo true;
	}

	if (isset($_POST['busca_autores'])) {
		$busca_autores = $_POST['busca_autores'];
		$busca_resultados = false;
		$query = prepare_query("SELECT DISTINCT titulo FROM Etiquetas WHERE tipo = 'autor' AND titulo LIKE '%{$busca_autores}%'");
		$autores = $conn->query($query);
		if ($autores->num_rows > 0) {
			while ($autor = $autores->fetch_assoc()) {
				$busca_autor = $autor['titulo'];
				$busca_resultados .= "<a href='javascript:void(0);' class='$tag_inativa_classes2 blue-grey adicionar_autor' value='$busca_autor'>$busca_autor</a>";
			}
		} else {
			return false;
		}
		echo $busca_resultados;
	}

	if (isset($_POST['curso_novo_topico_id'])) {
		if (isset($_POST['curso_novo_topico_pagina_id'])) {
			$curso_novo_topico_id = $_POST['curso_novo_topico_id'];
			$curso_novo_topico_pagina_id = $_POST['curso_novo_topico_pagina_id'];
			$curso_novo_topico_user_id = $_POST['curso_novo_topico_user_id'];
			$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $curso_novo_topico_pagina_id, $curso_novo_topico_id, $curso_novo_topico_user_id)");
			$conn->query($query);
			$novo_topico_pagina_id = $conn->insert_id;
			$novo_topico_etiqueta_info = return_etiqueta_info($curso_novo_topico_id);
			$novo_topico_pagina_titulo = $novo_topico_etiqueta_info[2];
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_novo_topico_pagina_id, 'materia', $novo_topico_pagina_id, 'topico', $curso_novo_topico_user_id)");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_topico_pagina_id, 'pagina', 'titulo', '$novo_topico_pagina_titulo', $curso_novo_topico_user_id)");
			$conn->query($query);
			echo true;
		} else {
			echo false;
		}
	}
	if (isset($_POST['curso_novo_subtopico_id'])) {
		if (isset($_POST['curso_novo_subtopico_pagina_id'])) {
			$curso_novo_subtopico_id = $_POST['curso_novo_subtopico_id'];
			$curso_novo_subtopico_pagina_id = $_POST['curso_novo_subtopico_pagina_id'];
			$curso_novo_subtopico_user_id = $_POST['curso_novo_subtopico_user_id'];
			$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $curso_novo_subtopico_pagina_id, $curso_novo_subtopico_id, $curso_novo_subtopico_user_id)");
			$conn->query($query);
			$novo_subtopico_pagina_id = $conn->insert_id;
			$novo_subtopico_etiqueta_info = return_etiqueta_info($curso_novo_subtopico_id);
			$novo_subtopico_pagina_titulo = $novo_subtopico_etiqueta_info[2];
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_novo_subtopico_pagina_id, 'topico', $novo_subtopico_pagina_id, 'subtopico', $curso_novo_subtopico_user_id)");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_subtopico_pagina_id, 'pagina', 'titulo', '$novo_subtopico_pagina_titulo', $curso_novo_subtopico_user_id)");
			$conn->query($query);
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['curso_nova_materia_id'])) {
		if (isset($_POST['curso_nova_materia_pagina_id'])) {
			$curso_nova_materia_id = $_POST['curso_nova_materia_id'];
			$curso_nova_materia_pagina_id = $_POST['curso_nova_materia_pagina_id'];
			$curso_nova_materia_user_id = $_POST['curso_nova_materia_user_id'];
			$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('materia', $curso_nova_materia_pagina_id, $curso_nova_materia_id, $curso_nova_materia_user_id)");
			$check = $conn->query($query);
			$nova_materia_pagina_id = $conn->insert_id;
			$nova_materia_etiqueta_info = return_etiqueta_info($curso_nova_materia_id);
			$nova_materia_pagina_titulo = $nova_materia_etiqueta_info[2];
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_nova_materia_pagina_id, 'curso', $nova_materia_pagina_id, 'materia', $curso_nova_materia_user_id)");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_materia_pagina_id, 'pagina', 'titulo', '$nova_materia_pagina_titulo', $curso_nova_materia_user_id)");
			$conn->query($query);

			echo $check;
		}
	}

	if (isset($_POST['criar_topico_titulo'])) {
		$criar_topico_titulo = $_POST['criar_topico_titulo'];
		$criar_topico_page_id = $_POST['criar_topico_page_id'];
		$criar_topico_page_tipo = $_POST['criar_topico_page_tipo'];

		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];

		$query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_topico_titulo', $user_id)");
		$conn->query($query);
		$nova_etiqueta_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $criar_topico_page_id, $nova_etiqueta_id, $user_id)");
		$check = $conn->query($query);
		$novo_topico_pagina_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_topico_page_id, '$criar_topico_page_tipo', 'topico', $novo_topico_pagina_id, $user_id)");
		$conn->query($query);

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_topico_pagina_id, 'pagina', 'titulo', '$criar_topico_titulo', $user_id)");
		$conn->query($query);

		echo $check;

	}
	if (isset($_POST['criar_subtopico_titulo'])) {
		$criar_subtopico_titulo = $_POST['criar_subtopico_titulo'];
		$criar_subtopico_page_id = $_POST['criar_subtopico_page_id'];
		$criar_subtopico_page_tipo = $_POST['criar_subtopico_page_tipo'];

		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];

		$query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_subtopico_titulo', $user_id)");
		$conn->query($query);
		$nova_etiqueta_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $criar_subtopico_page_id, $nova_etiqueta_id, $user_id)");
		$check = $conn->query($query);
		$novo_subtopico_pagina_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_subtopico_page_id, '$criar_subtopico_page_tipo', 'subtopico', $novo_subtopico_pagina_id, $user_id)");
		$conn->query($query);

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_subtopico_pagina_id, 'pagina', 'titulo', '$criar_subtopico_titulo', $user_id)");
		$conn->query($query);

		echo $check;

	}

	if (isset($_POST['criar_materia_titulo'])) {
		$criar_materia_titulo = $_POST['criar_materia_titulo'];
		$criar_materia_page_id = $_POST['criar_materia_page_id'];
		$criar_materia_page_id = $_POST['criar_materia_page_id'];
		$criar_materia_page_tipo = $_POST['criar_materia_page_tipo'];

		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];

		$query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_materia_titulo', $user_id)");
		$conn->query($query);
		$nova_etiqueta_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('materia', $criar_materia_page_id, $nova_etiqueta_id, $user_id)");
		$check = $conn->query($query);
		$nova_materia_pagina_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_materia_page_id, '$criar_materia_page_tipo', 'materia', $nova_materia_pagina_id, $user_id)");
		$conn->query($query);

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_materia_pagina_id, 'pagina', 'titulo', '$criar_materia_titulo', $user_id)");
		$conn->query($query);

		echo $check;

	}

	if (isset($_POST['busca_etiquetas'])) {
		if (isset($_POST['busca_etiquetas_contexto'])) {
			$busca_etiquetas_contexto = $_POST['busca_etiquetas_contexto'];
		}
		if ($busca_etiquetas_contexto == 'curso') {
			$acao_etiqueta_criar = 'criar_materia';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_materia', $tag_inativa_classes);
		} elseif ($busca_etiquetas_contexto == 'materia') {
			$acao_etiqueta_criar = 'criar_topico';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_topico', $tag_inativa_classes);
		} elseif ($busca_etiquetas_contexto == 'topico') {
			$acao_etiqueta_criar = 'criar_subtopico';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_subtopico', $tag_inativa_classes);
		} else {
			$acao_etiqueta_criar = 'criar_etiqueta';
		}
		/*else {
			$tag_ativa_classes = str_replace('remover_tag', 'remover_materia', $tag_ativa_classes);
			$acao_etiqueta_criar = 'criar_etiqueta';
		}*/
		if (isset($_POST['busca_etiquetas_sem_link'])) {
			$busca_etiquetas_sem_link = $_POST['busca_etiquetas_sem_link'];
		} else {
			$busca_etiquetas_sem_link = false;
		}
		if (isset($_POST['busca_etiquetas_tipo'])) {
			$busca_etiquetas_tipo = $_POST['busca_etiquetas_tipo'];
		} else {
			$busca_etiquetas_tipo = 'all';
		}
		$busca_etiquetas = $_POST['busca_etiquetas'];
		$busca_etiquetas = mysqli_real_escape_string($conn, $busca_etiquetas);
		$busca_resultados = false;
		if ($busca_etiquetas_tipo == 'all') {
			$query = prepare_query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas'");
			$query = prepare_query($query);
			$etiqueta_exata = $conn->query($query);
		} else {
			$query = prepare_query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas' AND tipo = '$busca_etiquetas_tipo'");
			$query = prepare_query($query);
			$etiqueta_exata = $conn->query($query);
		}
		if ($etiqueta_exata->num_rows == 0) {
			$busca_resultados .= "
			<div class='col-12'>
				<button type='button' id='$acao_etiqueta_criar' name='$acao_etiqueta_criar' class='btn text-center btn-success btn-sm m-0 mb-2 align-self-center' value='$busca_etiquetas'>{$pagina_translated['Criar etiqueta']} \"$busca_etiquetas\"</button>
			</div>
			";
		}
		if ($busca_etiquetas_tipo == 'all') {
			$query = prepare_query("SELECT id, tipo, titulo FROM Etiquetas WHERE titulo LIKE '%{$busca_etiquetas}%'");
			$query = prepare_query($query);
			$etiquetas = $conn->query($query);
		} else {
			$query = prepare_query("SELECT id, tipo, titulo FROM Etiquetas WHERE titulo LIKE '%{$busca_etiquetas}%' AND tipo = '$busca_etiquetas_tipo'");
			$query = prepare_query($query);
			$etiquetas = $conn->query($query);
		}
		if ($etiquetas->num_rows > 0) {
			while ($etiqueta = $etiquetas->fetch_assoc()) {
				$etiqueta_id = $etiqueta['id'];
				$etiqueta_tipo = $etiqueta['tipo'];
				$etiqueta_titulo = $etiqueta['titulo'];

				$etiqueta_cor_icone = return_etiqueta_cor_icone($etiqueta_tipo);
				$etiqueta_cor = $etiqueta_cor_icone[0];
				$etiqueta_icone = $etiqueta_cor_icone[1];

				if ($etiqueta_cor != false) {
					if ($busca_etiquetas_sem_link == true) {
						$busca_resultados .= "<span href='javascript:void(0);' class='$tag_neutra_classes $etiqueta_cor'><i class='far $etiqueta_icone fa-fw'></i> $etiqueta_titulo</span>";
					} else {
						$busca_resultados .= "<a href='javascript:void(0);' class='$tag_inativa_classes $etiqueta_cor' value='$etiqueta_id'><i class='far $etiqueta_icone fa-fw'></i> $etiqueta_titulo</a>";
					}
				}
			}
		} else {
			$busca_resultados .= "<p><em>Nenhuma etiqueta encontrada.</em></p>";
		}
		echo $busca_resultados;
	}

	// Este mecanismo precisa ser dinâmico o suficiente para que funcione tanto para elementos quanto
	// para tópicos. Ele é compartilhado por ambos os sistemas, como se vê no condicional dos sitemas
	// no arquivo html_bottom
	if (isset($_POST['nova_etiqueta_id'])) {
		$nova_etiqueta_id = $_POST['nova_etiqueta_id'];
		$nova_etiqueta_page_id = $_POST['nova_etiqueta_page_id'];
		$nova_etiqueta_page_tipo = $_POST['nova_etiqueta_page_tipo'];
		$nova_etiqueta_info = return_etiqueta_info($nova_etiqueta_id);
		$nova_etiqueta_tipo = $nova_etiqueta_info[1];
		$nova_etiqueta_titulo = $nova_etiqueta_info[2];
		$nova_etiqueta_elemento_id = false;
		$halt = false;
		if ($nova_etiqueta_tipo == 'topico') {
			$nova_etiqueta_elemento_id = return_etiqueta_topico_id($nova_etiqueta_id);
		} else {
			$nova_etiqueta_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
			$nova_etiqueta_elemento_info = return_elemento_info($nova_etiqueta_elemento_id);
			$nova_etiqueta_elemento_user_id = $nova_etiqueta_elemento_info[16];
			$nova_etiqueta_elemento_compartilhamento = $nova_etiqueta_elemento_info[17];
			if ($nova_etiqueta_elemento_compartilhamento == 'privado') {
				if ($user_id != $nova_etiqueta_elemento_user_id) {
					$halt = true;
				} else {
					if ($nova_etiqueta_tipo == 'imagem_privada') {
						$nova_etiqueta_tipo = 'imagem';
					}
				}
			}
		}
		if ($nova_etiqueta_elemento_id == false) {
			$nova_etiqueta_elemento_id = "NULL";
		}
		if ($halt == false) {
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($nova_etiqueta_page_id, '$nova_etiqueta_page_tipo', $nova_etiqueta_elemento_id, '$nova_etiqueta_tipo', $nova_etiqueta_id, $user_id)");
			$query = prepare_query($query);
			$conn->query($query);
			$nova_etiqueta_cor_icone = return_etiqueta_cor_icone($nova_etiqueta_tipo);
			$nova_etiqueta_cor = $nova_etiqueta_cor_icone[0];
			$nova_etiqueta_icone = $nova_etiqueta_cor_icone[1];
			if ($nova_etiqueta_tipo == 'topico') {
				echo "<a href='javascript:void(0);' class='$tag_ativa_classes $nova_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $nova_etiqueta_icone fa-fw'></i> $nova_etiqueta_titulo</a>";
			} else {
				echo true;
			}
		} else {
			echo false;
		}
		if ($nova_etiqueta_page_id == $user_escritorio) {
			$_SESSION['user_areas_interesse'] = return_user_areas_interesse($user_escritorio);
		}
	}

	if (isset($_POST['criar_etiqueta_titulo'])) {
		$criar_etiqueta_titulo = $_POST['criar_etiqueta_titulo'];
		$criar_etiqueta_page_id = $_POST['criar_etiqueta_page_id'];
		$criar_etiqueta_page_tipo = $_POST['criar_etiqueta_page_tipo'];

		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];

		$query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_etiqueta_titulo', $user_id)");
		$conn->query($query);
		$nova_etiqueta_id = $conn->insert_id;

		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($criar_etiqueta_page_id, '$criar_etiqueta_page_tipo', NULL, 'topico', $nova_etiqueta_id, $user_id)");
		$conn->query($query);

		echo "<a href='javascript:void(0);' class='$tag_ativa_classes $criar_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</a>";
	}

	if (isset($_POST['remover_etiqueta_id'])) {
		$remover_etiqueta_id = $_POST['remover_etiqueta_id'];
		$remover_etiqueta_page_id = $_POST['remover_etiqueta_page_id'];
		$remover_etiqueta_page_tipo = $_POST['remover_etiqueta_page_tipo'];
		$query = prepare_query("UPDATE Paginas_elementos SET estado = FALSE WHERE extra IN ('$remover_etiqueta_id') AND pagina_id = $remover_etiqueta_page_id");
		$conn->query($query);
		if ($remover_etiqueta_page_id == $user_escritorio) {
			$_SESSION['user_areas_interesse'] = return_user_areas_interesse($user_escritorio);
		}
	}

	$fa_secondary_color_anotacao = '#2196f3';
	$fa_icone_anotacao = 'fa-file-alt';
	$fa_secondary_color_imagem = "#ff5722";
	$fa_primary_color_imagem = "#ffab91";
	$fa_icone_imagem = 'fa-file-image';

	if (isset($_POST['quill_novo_verbete_html'])) {
		$quill_novo_verbete_html = $_POST['quill_novo_verbete_html'];
		$quill_novo_verbete_html = mysqli_real_escape_string($conn, $quill_novo_verbete_html);
		$quill_novo_verbete_html = strip_tags($quill_novo_verbete_html, '<strong><p><li><ul><ol><h2><h3><blockquote><em><sup><img><u><b><a><s>');
		$quill_novo_verbete_text = $_POST['quill_novo_verbete_text'];
		$quill_novo_verbete_text = mysqli_real_escape_string($conn, $quill_novo_verbete_text);
		$quill_novo_verbete_content = $_POST['quill_novo_verbete_content'];
		$quill_novo_verbete_content = mysqli_real_escape_string($conn, $quill_novo_verbete_content);
		$quill_pagina_id = $_POST['quill_pagina_id'];
		$quill_texto_tipo = $_POST['quill_texto_tipo'];
		$quill_texto_id = $_POST['quill_texto_id'];
		$quill_texto_page_id = $_POST['quill_texto_page_id'];
		$quill_pagina_tipo = $_POST['quill_pagina_tipo'];
		$quill_pagina_subtipo = $_POST['quill_pagina_subtipo'];
		$quill_pagina_estado = $_POST['quill_pagina_estado'];
		$quill_curso_id = $_POST['quill_curso_id'];
		$quill_arquivo_id = (int)$_POST['quill_arquivo_id'];

		if ($quill_texto_tipo != 'anotacoes') {
			$check_privilegio_edicao = return_privilegio_edicao($quill_pagina_id, $user_id, $_SESSION['user_editor_paginas_id']);
			if ($check_privilegio_edicao == true) {
				$_SESSION['adicionar_privilegio_edicao'] = $quill_pagina_id;
			}
		} else {
			$check_privilegio_edicao = true;
		}

		if ($check_privilegio_edicao == true) {
			$query = prepare_query("UPDATE Textos SET verbete_html = '$quill_novo_verbete_html', verbete_text = '$quill_novo_verbete_text', verbete_content = '$quill_novo_verbete_content' WHERE id = $quill_texto_id");
			$check = $conn->query($query);

			switch ($quill_arquivo_id) {
				case 0:
				case false:
				case null:
					$query = prepare_query("INSERT INTO Textos_arquivo (texto_id, curso_id, tipo, page_id, pagina_id, pagina_tipo, pagina_subtipo, estado_texto, verbete_html, verbete_text, verbete_content, user_id) VALUES ($quill_texto_id, $quill_curso_id, '$quill_texto_tipo', $quill_texto_page_id, $quill_pagina_id, '$quill_pagina_tipo', '$quill_pagina_subtipo', 1, '$quill_novo_verbete_html', FALSE, '$quill_novo_verbete_content', $user_id)");
					$check2 = $conn->query($query);
					$novo_arquivo_id = $conn->insert_id;
					break;
				default:
					$query = prepare_query("UPDATE Textos_arquivo SET verbete_html = '$quill_novo_verbete_html', verbete_content = '$quill_novo_verbete_content' WHERE id = $quill_arquivo_id AND user_id = $user_id");
					$check2 = $conn->query($query);
					if ($check2 == true) {
						$novo_arquivo_id = $quill_arquivo_id;
					}
			}


			if (($quill_pagina_estado == false) && ($quill_novo_verbete_text != false)) {
				if ($quill_texto_tipo == 'verbete') {
					$query = prepare_query("UPDATE Paginas SET estado = 1 WHERE id = $quill_pagina_id");
					$conn->query($query);
				}
			}
			if (($check == false) || ($check2 == false)) {
				echo false;
			} else {
				echo $novo_arquivo_id;
			}
		} else {
			echo false;
		}
	}

	if (isset($_POST['busca_apelido'])) {
		$busca_apelido = $_POST['busca_apelido'];
		$busca_apelido_continuar = false;
		$busca_grupo_id = false;
		$busca_pagina_id = false;
		if (isset($_POST['busca_grupo_id'])) {
			$busca_apelido_continuar = true;
			$busca_grupo_id = $_POST['busca_grupo_id'];
			$convite_tipo = 'convite_usuario';
		} elseif (isset($_POST['busca_pagina_id'])) {
			$busca_apelido_continuar = true;
			$busca_pagina_id = $_POST['busca_pagina_id'];
			$convite_tipo = 'compartilhar_usuario';
		}
		if ($busca_apelido_continuar == true) {
			$query = prepare_query("SELECT apelido, id FROM Usuarios WHERE apelido LIKE '%$busca_apelido%' ORDER BY apelido");
			$query = prepare_query($query);
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_apelido = $usuario['apelido'];
					$usuario_id = $usuario['id'];
					$usuario_avatar = return_avatar($usuario_id);
					$usuario_avatar_icone = $usuario_avatar[0];
					$usuario_avatar_cor = $usuario_avatar[1];
					echo "<a value='$usuario_id' class='border p-1 me-2 mb-2 rounded $convite_tipo'><span class='$usuario_avatar_cor'><i class='fad $usuario_avatar_icone fa-fw fa-2x'></i></span> $usuario_apelido</a></a>";
				}
			} else {
				echo "<span class='text-muted'>{$pagina_translated['Nenhum usuário encontrado.']}</span>";
			}
		} else {
			echo false;
		}
	}

	if (isset($_POST['compartilhar_usuario_id'])) {
		$compartilhar_usuario_id = $_POST['compartilhar_usuario_id'];
		$compartilhar_pagina_id = $_POST['compartilhar_pagina_id'];
		$query = prepare_query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('acesso', $user_id, $compartilhar_pagina_id, 'pagina', 'usuario', $compartilhar_usuario_id)");
		$conn->query($query);
	}

	if (isset($_POST['convidar_usuario_id'])) {
		$convidar_usuario_id = $_POST['convidar_usuario_id'];
		$convidar_grupo_id = $_POST['convidar_grupo_id'];
		$query = prepare_query("INSERT INTO Membros (grupo_id, membro_user_id, user_id) VALUES ($convidar_grupo_id, $convidar_usuario_id, $user_id)");
		$check = $conn->query($query);
		if ($check == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['remover_carrinho_pagina_id'])) {
		$remover_carrinho_pagina_id = $_POST['remover_carrinho_pagina_id'];
		$query = prepare_query("UPDATE Carrinho SET estado = 0 WHERE produto_pagina_id = $remover_carrinho_pagina_id AND user_id = $user_id");
		$check = $conn->query($query);
		return $check;
	}

	if (isset($_POST['remover_compartilhamento_usuario'])) {
		$remover_compartilhamento_usuario = $_POST['remover_compartilhamento_usuario'];
		$remover_compartilhamento_usuario_pagina = $_POST['remover_compartilhamento_usuario_pagina'];
		$query = prepare_query("UPDATE Compartilhamento SET estado = 0 WHERE tipo = 'acesso' AND item_id = $remover_compartilhamento_usuario_pagina AND recipiente_id = $remover_compartilhamento_usuario AND compartilhamento = 'usuario'");
		$check_remocao_compartilhamento = $conn->query($query);
		if ($check_remocao_compartilhamento == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['remover_acesso_grupo'])) {
		$remover_acesso_grupo = $_POST['remover_acesso_grupo'];
		$remover_acesso_grupo_pagina_id = $_POST['remover_acesso_grupo_pagina_id'];
		$query = prepare_query("UPDATE Compartilhamento SET estado = 0 WHERE tipo = 'acesso' AND item_id = $remover_acesso_grupo_pagina_id AND recipiente_id = $remover_acesso_grupo AND compartilhamento = 'grupo'");
		$check_remocao_acesso_grupo = $conn->query($query);
		if ($check_remocao_compartilhamento == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['adicionar_item_acervo'])) {
		$adicionar_item_acervo = $_POST['adicionar_item_acervo'];
		$adicionar_item_pagina_id = return_pagina_id($adicionar_item_acervo, 'elemento');
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($user_escritorio, 'escritorio', $adicionar_item_acervo, 'referencia', $adicionar_item_pagina_id, $user_id)");
		$check_acervo = $conn->query($query);
		if ($check_acervo == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['remover_item_acervo'])) {
		$remover_item_acervo = $_POST['remover_item_acervo'];
		$query = prepare_query("UPDATE Paginas_elementos SET estado = 0 WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND elemento_id = $remover_item_acervo");
		$check_acervo = $conn->query($query);
		if ($check_acervo == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['adicionar_area_interesse'])) {
		$adicionar_area_interesse = $_POST['adicionar_area_interesse'];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($user_escritorio, 'escritorio', 'topico', $adicionar_area_interesse, $user_id)");
		$check_adicionar = $conn->query($query);
		if ($check_adicionar == true) {
			echo true;
		} else {
			echo false;
		}
		$_SESSION['user_areas_interesse'] = return_user_areas_interesse($user_escritorio);
		$user_areas_interesse = $_SESSION['user_areas_interesse'];
	}

	if (isset($_POST['remover_area_interesse'])) {
		$remover_area_interesse = $_POST['remover_area_interesse'];
		$query = prepare_query("UPDATE Paginas_elementos SET estado = 0 WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND tipo = 'topico' AND extra = $remover_area_interesse");
		$check_remover = $conn->query($query);
		if ($check_remover == true) {
			echo true;
		} else {
			echo false;
		}
		$_SESSION['user_areas_interesse'] = return_user_areas_interesse($user_escritorio);
		$user_areas_interesse = $_SESSION['user_areas_interesse'];
	}

	if (isset($_POST['remover_membro_grupo_id'])) {
		$remover_membro_grupo_id = $_POST['remover_membro_grupo_id'];
		$remover_membro_user_id = $_POST['remover_membro_user_id'];
		$query = prepare_query("UPDATE Membros SET estado = 0 WHERE grupo_id = $remover_membro_grupo_id AND membro_user_id = $remover_membro_user_id");
		$remover_membro_check = $conn->query($query);
		if ($remover_membro_check == true) {
			echo true;
		}
	}

	if (isset($_POST['carregar_edicao'])) {
		$carregar_edicao = $_POST['carregar_edicao'];
		$query = prepare_query("SELECT id, titulo FROM sim_etapas WHERE edicao_id = $carregar_edicao");
		$etapas = $conn->query($query);
		$etapas_resultado = false;
		if ($etapas->num_rows > 0) {
			$etapas_resultado .= "<span data-bs-toggle='modal' data-bs-target='#modal_vazio_edicoes'><span data-bs-toggle='modal' data-bs-target='#modal_vazio_provas'>";
			while ($etapa = $etapas->fetch_assoc()) {
				$etapa_id = $etapa['id'];
				$etapa_titulo = $etapa['titulo'];
				$etapas_resultado .= "<a href='javascript:void(0);' class='mt-1 carregar_etapa' value='$etapa_id'><li class='list-group-item list-group-item-action border-top'>$etapa_titulo</li></a>";
			}
			$etapas_resultado .= "</span></span>";
		}
		$etapas_resultado = list_wrap($etapas_resultado);
		echo $etapas_resultado;
	}

	if (isset($_POST['nova_etapa_edicao_id'])) {
		$nova_etapa_edicao_id = $_POST['nova_etapa_edicao_id'];
		$nova_etapa_curso_id = $_POST['nova_etapa_curso_id'];
		$nova_etapa_titulo = $_POST['nova_etapa_titulo'];
		$query = prepare_query("INSERT INTO sim_etapas (curso_id, edicao_id, titulo, user_id) VALUES ($nova_etapa_curso_id, $nova_etapa_edicao_id, '$nova_etapa_titulo', $user_id)");
		$conn->query($query);
	}

	if (isset($_POST['carregar_etapa'])) {
		$carregar_etapa = $_POST['carregar_etapa'];
		$query = prepare_query("SELECT id, titulo, tipo FROM sim_provas WHERE etapa_id = $carregar_etapa");
		$provas = $conn->query($query);
		$provas_resultado = false;
		if ($provas->num_rows > 0) {
			$provas_resultado .= "<span data-bs-toggle='modal' data-bs-target='#modal_vazio_provas'><span data-bs-toggle='modal' data-bs-target='#modal_vazio_questoes'>";
			while ($prova = $provas->fetch_assoc()) {
				$prova_id = $prova['id'];
				$prova_titulo = $prova['titulo'];
				$prova_tipo = $prova['tipo'];
				$prova_tipo_string = convert_prova_tipo($prova_tipo);
				$provas_resultado .= "<a href='javascript:void(0);' class='mt-1 carregar_prova' value='$prova_id'><li class='list-group-item list-group-item-action border-top'>$prova_titulo <strong>($prova_tipo_string)</strong></li></a>";
			}
			$provas_resultado .= "</span></span>";
		}
		$provas_resultado = list_wrap($provas_resultado);
		echo $provas_resultado;
	}

	if (isset($_POST['carregar_prova'])) {
		$carregar_prova = $_POST['carregar_prova'];
		$query = prepare_query("SELECT id, pagina_id, origem, numero, tipo FROM sim_questoes WHERE prova_id = $carregar_prova ORDER BY numero");
		$list_questoes = $conn->query($query);
		$list_questoes_resultado = false;
		if ($list_questoes->num_rows > 0) {
			while ($list_questao = $list_questoes->fetch_assoc()) {
				$list_questao_id = $list_questao['id'];
				$list_questao_origem = $list_questao['origem'];
				if ($list_questao_origem == 1) {
					$list_questao_origem_string = 'oficial';
				} elseif ($list_questao_origem == 0) {
					$list_questao_origem_string = 'não-oficial';
				}
				$list_questao_numero = $list_questao['numero'];
				$list_questao_tipo = $list_questao['tipo'];
				$list_questao_tipo_string = convert_questao_tipo($list_questao_tipo);
				$list_questoes_resultado .= "<a href='javascript:void(0);' class='mt-1 adicionar_questao' value='$list_questao_id'><li class='list-group-item list-group-item-action border-top'>Questão $list_questao_numero ($list_questao_tipo_string, $list_questao_origem_string)</li></a>";
			}
		}
		echo $list_questoes_resultado;
	}

	if (isset($_POST['nova_prova_etapa_id'])) {
		$nova_prova_etapa_id = $_POST['nova_prova_etapa_id'];
		$nova_prova_curso_id = $_POST['nova_prova_curso_id'];
		$nova_prova_tipo = $_POST['nova_prova_tipo'];
		$nova_prova_titulo = $_POST['nova_prova_titulo'];
		$query = prepare_query("INSERT INTO sim_provas (curso_id, etapa_id, titulo, tipo) VALUES ($nova_prova_curso_id, $nova_prova_etapa_id, '$nova_prova_titulo', $nova_prova_tipo)");
		$conn->query($query);
	}

	if (isset($_POST['trigger_nova_questao'])) {
		$nova_questao_curso_id = $_POST['nova_questao_curso_id'];
		$nova_questao_pagina_id = $_POST['nova_questao_pagina_id'];
		$nova_questao_etapa_id = $_POST['nova_questao_etapa_id'];
		$nova_questao_prova_id = $_POST['nova_questao_prova_id'];
		$nova_questao_edicao_id = $_POST['nova_questao_edicao_id'];
		$nova_questao_origem = $_POST['nova_questao_origem'];
		$nova_questao_texto_apoio = $_POST['nova_questao_texto_apoio'];
		$nova_questao_tipo = $_POST['nova_questao_tipo'];
		$nova_questao_numero = $_POST['nova_questao_numero'];

		$nova_questao_etapa_info = return_etapa_edicao_ano_e_titulo($nova_questao_etapa_id);
		$nova_questao_edicao_ano = $nova_questao_etapa_info[0];

		$nova_questao_pagina_familia = return_familia($nova_questao_pagina_id);
		$nova_questao_materia_id = $nova_questao_pagina_familia[2];

		$query = prepare_query("INSERT INTO sim_questoes (origem, curso_id, edicao_ano, etapa_id, texto_apoio, prova_id, numero, materia, tipo, enunciado_html, enunciado_text, enunciado_content, user_id) VALUES ($nova_questao_origem, $nova_questao_curso_id, $nova_questao_edicao_ano, $nova_questao_etapa_id, $nova_questao_texto_apoio, $nova_questao_prova_id, $nova_questao_numero, $nova_questao_materia_id, $nova_questao_tipo, false, false, false, $user_id)");
		$conn->query($query);
		$nova_questao_id = $conn->insert_id;
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($nova_questao_pagina_id, 'topico', $nova_questao_id, 'questao', $nova_questao_origem, $user_id)");
		$conn->query($query);
	}

	if (isset($_POST['adicionar_questao_id'])) {
		$adicionar_questao_id = $_POST['adicionar_questao_id'];
		$adicionar_questao_info = return_questao_info($adicionar_questao_id);
		$adicionar_questao_questao_pagina_id = $adicionar_questao_info[34];
		$adicionar_questao_origem = $adicionar_questao_info[0];
		$adicionar_questao_pagina_id = $_POST['adicionar_questao_pagina_id'];
		$adicionar_questao_pagina_info = return_pagina_info($adicionar_questao_pagina_id);
		$adicionar_questao_pagina_tipo = $adicionar_questao_pagina_info[2];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, extra2, user_id) VALUES ($adicionar_questao_pagina_id, '$adicionar_questao_pagina_tipo', $adicionar_questao_id, 'questao', $adicionar_questao_origem, $adicionar_questao_questao_pagina_id, $user_id)");
		$adicionar_questao_check = $conn->query($query);
		echo $adicionar_questao_check;
	}

	if (isset($_POST['notificacao_pagina_id'])) {
		$notificacao_pagina_id = $_POST['notificacao_pagina_id'];
		$notificacao_ativa = $_POST['notificacao_ativa'];
		$notificacao_email = $_POST['notificacao_email'];
		$notificacao_tipo = false;
		if ($notificacao_email == true) {
			$notificacao_tipo = 'email';
		} elseif ($notificacao_ativa == true) {
			$notificacao_tipo = 'normal';
		}
		$query = prepare_query("UPDATE Notificacoes SET estado = 0 WHERE user_id = $user_id AND pagina_id = $notificacao_pagina_id");
		$conn->query($query);
		if ($notificacao_tipo != false) {
			$query = prepare_query("INSERT INTO Notificacoes (user_id, pagina_id, tipo) VALUES ($user_id, $notificacao_pagina_id, '$notificacao_tipo')");
			$conn->query($query);
		}
	}

	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		if (in_array($novo_estado_pagina, array(1, 2, 3, 4)))
			$novo_estado_pagina_id = $_POST['novo_estado_pagina_id'];
		$check_compartilhamento = return_compartilhamento($novo_estado_pagina_id, $user_id);
		if ($check_compartilhamento == true) {
			$query = prepare_query("UPDATE Paginas SET estado = $novo_estado_pagina WHERE id = $novo_estado_pagina_id");
			$conn->query($query);
			$pagina_estado = $novo_estado_pagina;
		}
	}

	if (isset($_POST['delete_this_edit'])) {
		$delete_this_edit = (int)$_POST['delete_this_edit'];
		$query = prepare_query("DELETE FROM Textos_arquivo WHERE id = $delete_this_edit AND user_id = $user_id");
		$delete_edit_check = $conn->query($query);
		echo $delete_edit_check;
	}

	if (isset($_POST['popular_traducao_chave_pt'])) {
		$popular_traducao_chave_pt = $_POST['popular_traducao_chave_pt'];
		$query = prepare_query("SELECT DISTINCT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_pt AND lingua = 'pt'");
		$chaves_pt = $conn->query($query);
		if ($chaves_pt->num_rows > 0) {
			while ($chave_pt = $chaves_pt->fetch_assoc()) {
				$chave_pt_string = $chave_pt['traducao'];
				echo "<span><strong>Português</strong>: <em>$chave_pt_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	if (isset($_POST['popular_traducao_chave_en'])) {
		$popular_traducao_chave_en = $_POST['popular_traducao_chave_en'];
		$query = prepare_query("SELECT DISTINCT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_en AND lingua = 'en'");
		$chaves_en = $conn->query($query);
		if ($chaves_en->num_rows > 0) {
			while ($chave_en = $chaves_en->fetch_assoc()) {
				$chave_en_string = $chave_en['traducao'];
				echo "<span><strong>English</strong>: <em>$chave_en_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	if (isset($_POST['popular_traducao_chave_es'])) {
		$popular_traducao_chave_es = $_POST['popular_traducao_chave_es'];
		$query = prepare_query("SELECT DISTINCT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_es AND lingua = 'es'");
		$chaves_es = $conn->query($query);
		$result = false;
		if ($chaves_es->num_rows > 0) {
			while ($chave_es = $chaves_es->fetch_assoc()) {
				$chave_es_string = $chave_es['traducao'];
				$result = "<span><strong>Español</strong>: <em>$chave_es_string</em></span>";
			}
		}
		echo $result;
	}
	if (isset($_POST['popular_traducao_chave_fr'])) {
		$popular_traducao_chave_fr = $_POST['popular_traducao_chave_fr'];
		$query = prepare_query("SELECT DISTINCT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_fr AND lingua = 'fr'");
		$chaves_fr = $conn->query($query);
		$result = false;
		if ($chaves_fr->num_rows > 0) {
			while ($chave_fr = $chaves_fr->fetch_assoc()) {
				$chave_fr_string = $chave_fr['traducao'];
				$result = "<span><strong>Français</strong>: <em>$chave_fr_string</em></span>";
			}
		}
		echo $result;
	}

	if (isset($_POST['return_chave_codigo'])) {
		$return_chave_codigo = $_POST['return_chave_codigo'];
		$query = prepare_query("SELECT chave FROM Translation_chaves WHERE id = $return_chave_codigo");
		$codigos = $conn->query($query);
		$result = false;
		if ($codigos->num_rows > 0) {
			while ($codigo = $codigos->fetch_assoc()) {
				$codigo_chave = $codigo['chave'];
				$result = "<span>$codigo_chave</span>";
			}
		}
		echo $result;
	}

	if (isset($_GET['confirmacao'])) {
		$confirmacao = $_GET['confirmacao'];
		$query = prepare_query("UPDATE Usuarios SET origem = 'confirmado' WHERE origem = '$confirmacao'");
		$check = $conn->query($query);
	}

	if (isset($_POST['nova_senha'])) {
		$nova_senha = $_POST['nova_senha'];
		$nova_senha_email = $_POST['nova_senha_email'];
		$nova_senha_encrypted = password_hash($nova_senha, PASSWORD_DEFAULT);
		$confirmacao = generateRandomString(12);
		$check = send_nova_senha($nova_senha_email, $confirmacao, $user_language);
		if ($check == true) {
			//error_log('and then this happened');
			$query = prepare_query("SELECT id FROM Usuarios WHERE email = '$nova_senha_email'");
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_id = $usuario['id'];
					$query = prepare_query("UPDATE Usuarios SET senha = '$nova_senha_encrypted', origem = '$confirmacao' WHERE id = $usuario_id");
					$conn->query($query);
				}
			} else {
				$query = prepare_query("INSERT INTO Usuarios (email, origem, senha) VALUES ('$nova_senha_email', '$confirmacao', '$nova_senha_encrypted')");
				$conn->query($query);
			}
		}
	}

	if (isset($_POST['list_areas_interesse'])) {
		$areas_interesse_result = false;

		$areas_interesse_result .= "<span data-bs-toggle='modal' data-bs-target='#modal_areas_interesse'>";

		$areas_interesse_result .= put_together_list_item('link', 'paginas_livres.php', false, 'fad fa-tags', $pagina_translated['freepages encyclopedia'], false, 'fad fa-external-link', 'list-group-item-warning');

		$areas_interesse_result .= put_together_list_item('modal', '#modal_gerenciar_etiquetas', false, 'fad fa-plus-circle', $pagina_translated['Gerenciar etiquetas'], false, 'fad fa-tags', 'list-group-item-info');

		$areas_interesse_result .= "</span>";
		$user_areas_interesse = return_user_areas_interesse($user_escritorio);
		foreach ($user_areas_interesse as $user_area_interesse_pagina_id) {
			$areas_interesse_result .= return_list_item($user_area_interesse_pagina_id);
		}
		$areas_interesse_result = list_wrap($areas_interesse_result);
		echo $areas_interesse_result;
	}

	if (isset($_POST['list_biblioteca_particular'])) {
		$list_biblioteca_particular = false;
		$list_biblioteca_particular .= "<div data-bs-toggle='modal' data-bs-target='#modal_biblioteca_particular'>";

		$list_biblioteca_particular .= put_together_list_item('link', 'biblioteca.php', false, 'fad fa-books', $pagina_translated['library'], false, 'fad fa-external-link', 'list-group-item-success');

		$list_biblioteca_particular .= put_together_list_item('link', 'pagina.php?plano_id=bp', false, 'fad fa-calendar-check', $pagina_translated['Plano de estudos'], false, 'fad fa-external-link', 'list-group-item-warning');

		$list_biblioteca_particular .= put_together_list_item('modal', '#modal_add_elementos', false, 'fad fa-plus-circle', $pagina_translated['press add collection'], false, 'fad fa-cog', 'list-group-item-info');

		$list_biblioteca_particular .= "</div>";
		$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $user_escritorio AND estado = 1 AND elemento_id IS NOT NULL ORDER BY id DESC");
		$biblioteca_particular = $conn->query($query);
		if ($biblioteca_particular->num_rows > 0) {
			while ($item_biblioteca = $biblioteca_particular->fetch_assoc()) {
				$item_biblioteca_elemento_id = $item_biblioteca['elemento_id'];
				$item_biblioteca_pagina_id = return_pagina_id($item_biblioteca_elemento_id, 'elemento');
				$list_biblioteca_particular .= return_list_item($item_biblioteca_pagina_id, false, false, false, false, false, true);
			}
		}
		$list_biblioteca_particular = list_wrap($list_biblioteca_particular);
		echo $list_biblioteca_particular;
	}

	if (isset($_POST['list_historico'])) {
		$edicoes_do_usuario = $conn->query("SELECT * FROM Textos_arquivo WHERE user_id = $user_id ORDER BY id DESC");
		$result = false;
		if ($edicoes_do_usuario->num_rows > 0) {
			while ($edicao_do_usuario = $edicoes_do_usuario->fetch_assoc()) {
				$edicao_do_usuario_pagina_id = $edicao_do_usuario['pagina_id'];
				$edicao_do_usuario_pagina_titulo = return_pagina_titulo($edicao_do_usuario_pagina_id);
				$edicao_do_usuario_id = $edicao_do_usuario['id'];
				$edicao_do_usuario_criacao = $edicao_do_usuario['criacao'];
				$result .= put_together_list_item('link_button', $edicao_do_usuario_id, 'link-danger', 'fad fa-trash-alt', "<span class='fontstack-mono'>$edicao_do_usuario_criacao:</span> $edicao_do_usuario_pagina_titulo", false, false, false, 'delete_edit', false, false);
			}
			$result = list_wrap($result);
		}
		echo $result;
	}

	if (isset($_POST['list_planos'])) {
		$query = prepare_query("SELECT * FROM Planos WHERE user_id = $user_id");
		$planos_usuario = $conn->query($query);
		$list_planos = false;
		$list_planos .= put_together_list_item('link', 'pagina.php?plano_id=new', false, 'fad fa-calendar-plus', $pagina_translated['Create new empty plan'], false, 'fad fa-external-link', 'list-group-item-info');
		$list_planos .= put_together_list_item('link', 'pagina.php?plano_id=bp', false, 'fad fa-calendar-check', $pagina_translated['your collection'], false, 'fad fa-external-link', 'list-group-item-success my-1');

		if ($planos_usuario->num_rows > 0) {
			while ($plano_usuario = $planos_usuario->fetch_assoc()) {
				$plano_usuario_pagina_id = $plano_usuario['pagina_id'];
				if ($plano_usuario_pagina_id == $user_escritorio) {
					continue;
				}
				$list_planos .= return_list_item($plano_usuario_pagina_id);
			}
		}
		$list_planos = list_wrap($list_planos);
		echo $list_planos;
	}

	if (isset($_POST['list_cursos'])) {
		$list_cursos = false;
		$list_cursos .= put_together_list_item('link', 'cursos.php', false, 'fad fa-graduation-cap', $pagina_translated['available courses'], false, 'fad fa-external-link', 'list-group-item-success');
		$usuario_cursos = return_usuario_cursos_inscrito($user_id);
		foreach ($usuario_cursos as $usuario_curso) {
			$list_cursos .= return_list_item($usuario_curso);
		}
		$list_cursos = list_wrap($list_cursos);
		echo $list_cursos;
	}

	if (isset($_POST['list_simulados'])) {
		$list_simulados = false;
		$list_simulados .= put_together_list_item('link', 'pagina.php?simulado_id=new', false, 'fad fa-ballot-check', $pagina_translated['Novo simulado'], false, 'fad fa-external-link', 'list-group-item-info');
		$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $user_escritorio AND tipo = 'simulado'");
		$simulados = $conn->query($query);
		if ($simulados->num_rows > 0) {
			while ($simulado = $simulados->fetch_assoc()) {
				$simulado_pagina_id = $simulado['elemento_id'];
				$list_simulados .= return_list_item($simulado_pagina_id);
			}
		}
		$list_simulados = list_wrap($list_simulados);
		echo $list_simulados;
	}

	if (isset($_POST['list_referencias'])) {
		$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE user_id = $user_id ORDER BY id DESC");
		$referencias_usuario = $conn->query($query);
		$list_referencias = false;
		if ($referencias_usuario->num_rows > 0) {
			while ($referencia_usuario = $referencias_usuario->fetch_assoc()) {
				$referencia_usuario_elemento_id = $referencia_usuario['elemento_id'];
				$referencia_usuario_pagina_id = return_pagina_id($referencia_usuario_elemento_id, 'elemento');
				$list_referencias .= return_list_item($referencia_usuario_pagina_id);
			}
		}
		$list_referencias = list_wrap($list_referencias);
		echo $list_referencias;
	}

	if (isset($_POST['list_grupos_estudo'])) {
		$query = prepare_query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
		$grupos_estudo_usuario = $conn->query($query);
		$query = prepare_query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado IS NULL");
		$convites_ativos = $conn->query($query);
		$list_grupos_estudo = false;
		$list_grupos_estudo .= "<span data-bs-toggle='modal' data-bs-target='#modal_grupos_estudo'>";
		$grupos_algo = false;
		if ($convites_ativos->num_rows > 0) {
			$grupos_algo = true;
			$list_grupos_estudo .= put_together_list_item('modal', '#modal_reagir_convite', 'link-warning', 'fad fa-exclamation-triangle', $pagina_translated['Você recebeu convite para participar de grupos de estudos:'], 'text-muted', 'fad fa-users');
		}
		$list_grupos_estudo .= put_together_list_item('modal', '#modal_criar_grupo', false, 'fad fa-plus-circle', $pagina_translated['Criar grupo de estudos'], false, 'fad fa-users', 'list-group-item-info');
		$list_grupos_estudo .= "</span>";

		if ($grupos_estudo_usuario->num_rows > 0) {
			while ($grupo_estudo_usuario = $grupos_estudo_usuario->fetch_assoc()) {
				$grupos_algo = true;
				$grupo_estudo_id = $grupo_estudo_usuario['grupo_id'];
				$grupo_estudo_pagina_id = return_pagina_id($grupo_estudo_id, 'grupo');
				$list_grupos_estudo .= return_list_item($grupo_estudo_pagina_id);
			}
		}
		$list_grupos_estudo = list_wrap($list_grupos_estudo);
		echo $list_grupos_estudo;
		if ($grupos_algo == false) {
			if (isset($_SESSION['user_opcoes']['grupos_estudo'][0])) {
				$user_opcoes_grupos_estudo = $_SESSION['user_opcoes']['grupos_estudo'][0];
				if ($user_opcoes_grupos_estudo == true) {
					$query = prepare_query("UPDATE Opcoes SET opcao = 0 WHERE user_id = $user_id AND opcao_tipo = 'grupos_estudo'");
					$conn->query($query);
				}
			}
		} elseif ($grupos_algo == true) {
			if (!isset($_SESSION['user_opcoes']['grupos_estudo'][0])) {
				$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'grupos_estudo', true)");
				$conn->query($query);
			} else {
				$user_opcoes_grupos_estudo = $_SESSION['user_opcoes']['grupos_estudo'][0];
				if ($user_opcoes_grupos_estudo == false) {
					$query = prepare_query("UPDATE Opcoes SET opcao = 1 WHERE user_id = $user_id AND opcao_tipo = 'grupos_estudo'");
					$conn->query($query);
				}
			}
			unset($_SESSION['user_opcoes']);
		}
	}

	if (isset($_POST['list_docs_shared'])) {
		$result_docs_shared = false;
		$query = prepare_query("SELECT item_id FROM Compartilhamento WHERE compartilhamento = 'usuario' AND recipiente_id = $user_id AND estado = 1");
		$docs_shared = $conn->query($query);
		if ($docs_shared->num_rows > 0) {
			while ($doc_shared = $docs_shared->fetch_assoc()) {
				if (!isset($_SESSION['user_opcoes']['docs_shared'])) {
					$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'docs_shared', 1)");
					$conn->query($query);
					unset($_SESSION['user_opcoes']);
				}
				$result_docs_shared .= return_list_item($doc_shared['item_id']);
			}
		} else {
			if (isset($_SESSION['user_opcoes']['docs_shared'])) {
				$query = prepare_query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'docs_shared'");
				$conn->query($query);
			}
		}
		$result_docs_shared = list_wrap($result_docs_shared);
		echo $result_docs_shared;
	}

	if (isset($_POST['list_bookmarks'])) {
		$result_bookmarks = false;
		foreach ($user_bookmarks as $usuario_bookmark_pagina_id) {
			$result_bookmarks .= return_list_item($usuario_bookmark_pagina_id, false, false, false, false, false, true);
		}
		echo list_wrap($result_bookmarks);
	}

	if (isset($_POST['list_comments'])) {
		$query = prepare_query("SELECT DISTINCT pagina_id FROM Forum WHERE user_id = $user_id");
		$usuario_comments = $conn->query($query);
		$list_comments = false;
		if ($usuario_comments->num_rows > 0) {
			while ($usuario_comment = $usuario_comments->fetch_assoc()) {
				$usuario_comment_pagina_id = $usuario_comment['pagina_id'];
				$list_comments .= return_list_item($usuario_comment_pagina_id, 'forum');
			}
		}
		$list_comments = list_wrap($list_comments);
		echo $list_comments;
	}

	if (isset($_POST['list_contribuicoes'])) {
		$query = prepare_query("SELECT DISTINCT pagina_id FROM Textos_arquivo WHERE user_id = $user_id AND tipo = 'verbete' ORDER BY id DESC");
		$usuario_contribuicoes = $conn->query($query);
		$list_contribuicoes = false;
		if ($usuario_contribuicoes->num_rows > 0) {
			while ($usuario_contribuicao = $usuario_contribuicoes->fetch_assoc()) {
				$usuario_contribuicao_pagina_id = $usuario_contribuicao['pagina_id'];
				$list_contribuicoes .= return_list_item($usuario_contribuicao_pagina_id, false, false, false, false, false, true);
			}
		}
		$list_contribuicoes = list_wrap($list_contribuicoes);
		echo $list_contribuicoes;
	}

	if (isset($_POST['list_notificacoes'])) {
		$template_modal_body_conteudo = false;
		$query = prepare_query("SELECT DISTINCT pagina_id FROM Notificacoes WHERE user_id = $user_id AND estado = 1 ORDER BY id DESC");
		$notificacoes = $conn->query($query);
		if ($notificacoes->num_rows > 0) {
			while ($notificacao = $notificacoes->fetch_assoc()) {
				$notificacao_pagina_id = $notificacao['pagina_id'];
				$notificacao_pagina_titulo = return_pagina_titulo($notificacao_pagina_id);
				$alteracao_recente = return_alteracao_recente($notificacao_pagina_id);
				$alteracao_recente_data = $alteracao_recente[0];
				$alteracao_recente_data = format_data($alteracao_recente_data);
				$alteracao_recente_usuario = $alteracao_recente[1];
				$alteracao_recente_usuario_apelido = return_apelido_user_id($alteracao_recente_usuario);
				$alteracao_recente_tipo = $alteracao_recente[2];
				if ($alteracao_recente_tipo == 'verbete') {
					$alteracao_recente_tipo_icone = 'fa-edit';
				} elseif ($alteracao_recente_tipo == 'forum') {
					$alteracao_recente_tipo_icone = 'fa-comments-alt';
				} else {
					$alteracao_recente_tipo_icone = 'fa-pencil';
				}
				$template_modal_body_conteudo .= return_list_item($notificacao_pagina_id);
				if ($alteracao_recente_data != false) {
					$template_modal_body_conteudo .= put_together_list_item('inactive', false, 'link-teal', "fad $alteracao_recente_tipo_icone", "$alteracao_recente_usuario_apelido <small class='text-muted'>($alteracao_recente_data)</small>", false, false, 'spacing2');
				}
				$segunda_alteracao_recente_data = $alteracao_recente[3];
				if ($segunda_alteracao_recente_data != false) {
					$segunda_alteracao_recente_data = format_data($segunda_alteracao_recente_data);
					$segunda_alteracao_recente_tipo = $alteracao_recente[5];
					if ($segunda_alteracao_recente_tipo == 'verbete') {
						$segunda_alteracao_recente_tipo_icone = 'fa-edit';
					} elseif ($segunda_alteracao_recente_tipo == 'forum') {
						$segunda_alteracao_recente_tipo_icone = 'fa-comments-alt';
					}
					$segunda_alteracao_recente_usuario = $alteracao_recente[4];
					$segunda_alteracao_recente_usuario_apelido = return_apelido_user_id($segunda_alteracao_recente_usuario);
					$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$notificacao_pagina_id' class='mt-1'><li class='list-group-item list-group-item-action border-top d-flex justify-content-between'><span class='ms-3'><i class='fad $segunda_alteracao_recente_tipo_icone fa-fw'></i> $notificacao_pagina_titulo</span><span class='text-muted'><em>($segunda_alteracao_recente_usuario_apelido) $segunda_alteracao_recente_data</em></span></li></a>";
				}
			}
		}
		$template_modal_body_conteudo = list_wrap($template_modal_body_conteudo);
		echo $template_modal_body_conteudo;
	}

	if (isset($_POST['list_estudos_recentes'])) {
		$list_estudos_recentes = false;
		$query = prepare_query("SELECT page_id, tipo_pagina, extra, extra2 FROM Visualizacoes WHERE user_id = $user_id ORDER BY id DESC");
		$usuario_estudos_recentes = $conn->query($query);
		if ($usuario_estudos_recentes->num_rows > 0) {
			$count = 0;
			$listados = array();
			while ($usuario_estudo_recente = $usuario_estudos_recentes->fetch_assoc()) {
				if ($count > 20) {
					break;
				}
				$usuario_estudo_recente_page_id = $usuario_estudo_recente['page_id'];
				if ($usuario_estudo_recente_page_id < 12) {
					continue;
				}
				if (in_array($usuario_estudo_recente_page_id, $listados)) {
					continue;
				} else {
					$count++;
					array_push($listados, $usuario_estudo_recente_page_id);
				}
				$usuario_estudo_recente_tipo_pagina = $usuario_estudo_recente['tipo_pagina'];
				$usuario_estudo_recente_extra = $usuario_estudo_recente['extra'];
				$usuario_estudo_recente_extra2 = $usuario_estudo_recente['extra2'];
				$usuario_estudo_recente_pagina_titulo = return_pagina_titulo($usuario_estudo_recente_page_id);
				if ($usuario_estudo_recente_pagina_titulo == false) {
					continue;
				}
				switch ($usuario_estudo_recente_tipo_pagina) {
					case 'elemento':
						$list_item_color = 'list-group-item-success';
						$list_item_icon = 'fa-books';
						break;
					case 'topico':
						$list_item_color = 'list-group-item-info';
						$list_item_icon = 'fa-columns';
						break;
					case 'curso':
						$list_item_color = 'list-group-item-primary';
						$list_item_icon = 'fa-graduation-cap';
						break;
					case 'pagina':
						$pagina_subtipo_info = return_pagina_info($usuario_estudo_recente_page_id);
						$pagina_subtipo = $pagina_subtipo_info[8];
						switch ($pagina_subtipo) {
							case 'etiqueta':
								$list_item_color = 'list-group-item-warning';
								$list_item_icon = 'fa-tag fa-swap-opacity';
								break;
							case 'escritorio':
								$list_item_color = 'list-group-item-danger';
								$list_item_icon = 'fa-user';
								break;
							default:
								$list_item_color = 'list-group-item-secondary';
								$list_item_icon = 'fa-circle-notch';
						}
						break;
					default:
						$list_item_color = false;
						$list_item_icon = 'fa-external-link';
				}
				$list_estudos_recentes .= return_list_item($usuario_estudo_recente_page_id);
				//$list_estudos_recentes .= "<a href='pagina.php?pagina_id=$usuario_estudo_recente_page_id' class='mt-1'><li class='list-group-item list-group-item-action $list_item_color'><i class='fad $list_item_icon fa-fw'></i> $usuario_estudo_recente_pagina_titulo</li></a>";
			}
		}
		$list_estudos_recentes = list_wrap($list_estudos_recentes);
		echo $list_estudos_recentes;
	}

	if (isset($_POST['list_user_texts'])) {
		$list_user_texts = false;
		$query = prepare_query("SELECT id, pagina_id, texto_pagina_id FROM Textos WHERE tipo = 'anotacoes' AND user_id = $user_id AND verbete_text != '0' AND verbete_text != '' AND verbete_text != '\\n' ORDER BY id DESC");
		$user_textos = $conn->query($query);
		if ($user_textos->num_rows > 0) {
			while ($user_texto = $user_textos->fetch_assoc()) {
				$user_texto_id = $user_texto['id'];
				$user_texto_pagina_id = $user_texto['pagina_id'];
				$user_texto_texto_pagina_id = $user_texto['texto_pagina_id'];
				if ($user_texto_pagina_id == false) {
					if ($user_texto_texto_pagina_id == false) {
						$user_texto_pagina_id = return_pagina_id($user_texto_id, 'texto');
					} else {
						$user_texto_pagina_id = $user_texto_texto_pagina_id;
					}
				}
				$list_user_texts .= return_list_item($user_texto_pagina_id, 'texto');
			}
		}
		echo $list_user_texts;
	}

	if (isset($_POST['list_user_pages'])) {
		$query = prepare_query("SELECT id, subtipo FROM Paginas WHERE tipo = 'pagina' AND user_id = $user_id ORDER BY id DESC");
		$user_pages = $conn->query($query);
		$list_user_pages = false;
		if ($user_pages->num_rows > 0) {
			while ($user_page = $user_pages->fetch_assoc()) {
				$user_page_id = $user_page['id'];
				$user_page_subtipo = $user_page['subtipo'];
				if ($user_page_subtipo == 'modelo') {
					continue;
				}
				if ($user_page_subtipo == 'plano') {
					continue;
				}
				$list_user_pages .= return_list_item($user_page_id);

				//$list_user_pages .= "<a href='pagina.php?pagina_id=$user_page_id'><li class='list-group-item list-group-item-action'><span class='link-teal me-2 align-middle'><i class='fad fa-columns fa-2x fa-fw'></i></span> $user_page_titulo</li></a>";
			}
		}
		echo $list_user_pages;
	}

	if (isset($_POST['listar_usuarios_emails'])) {
		$usuarios_emails_results = false;
		if ($user_tipo == 'admin') {
			$query = prepare_query("SELECT email FROM Usuarios");
			$usuarios_emails = $conn->query($query);
			if ($usuarios_emails->num_rows > 0) {
				while ($usuario_email = $usuarios_emails->fetch_assoc()) {
					$usuario_email_one = $usuario_email['email'];
					$usuarios_emails_results .= "<li class='list-group-item'>$usuario_email_one</li>";
				}
			}
		}
		echo $usuarios_emails_results;
	}

	if (isset($_POST['finalizar_correcao'])) {
		$finalizar_correcao_pagina_id = $_POST['finalizar_correcao'];
		$query = prepare_query("UPDATE Orders SET estado = 0, corretor_user_id = $user_id, data_finalizado = NOW() WHERE tipo = 'review' AND pagina_id = $finalizar_correcao_pagina_id AND estado = 1");
		$conn->query($query);
	}

	if (isset($_POST['curso_aderir'])) {
		$curso_aderir_id = $_POST['curso_aderir'];
		$curso_aderir_pagina_id = return_pagina_id($curso_aderir_id, 'curso');
		$check_compartilhamento = return_compartilhamento($curso_aderir_pagina_id, $user_id);
		if ($check_compartilhamento == true) {
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao, opcao_string) VALUES ($user_id, 'curso', $curso_aderir_id, $curso_aderir_pagina_id)");
			$conn->query($query);
		}
		echo true;
	}

	if (isset($_POST['curso_sair'])) {
		$curso_sair_id = $_POST['curso_sair'];
		$curso_sair_pagina_id = return_pagina_id($curso_sair_id, 'curso');
		$query = prepare_query("UPDATE Opcoes SET opcao_tipo = 'curso_removido' WHERE user_id = $user_id AND opcao_tipo = 'curso' AND opcao = $curso_sair_id");
		$conn->query($query);
		echo true;
	}

	if (isset($_POST['publicar_modelo'])) {
		$check = false;
		$publicar_modelo_pagina_id = $_POST['publicar_modelo'];
		$publicar_modelo_pagina_info = return_pagina_info($publicar_modelo_pagina_id);
		$publicar_modelo_pagina_subtipo = $publicar_modelo_pagina_info[8];
		$publicar_modelo_user_id = $publicar_modelo_pagina_info[5];
		if ($publicar_modelo_user_id == $user_id) {
			if ($publicar_modelo_pagina_subtipo == 'modelo') {
				$query = prepare_query("UPDATE Paginas SET compartilhamento = NULL WHERE id = $publicar_modelo_pagina_id");
				$check = $conn->query($query);
			}
		}
		echo $check;
	}

	if (isset($_POST['change_into_model_pagina_id'])) {
		$new_model_pagina_id = $_POST['change_into_model_pagina_id'];
		$new_model_pagina_info = return_pagina_info($new_model_pagina_id);
		$new_model_pagina_user_id = $new_model_pagina_info[5];
		$check = false;
		if ($user_id == $new_model_pagina_user_id) {
			$query = prepare_query("UPDATE Paginas SET subtipo = 'modelo' WHERE id = $new_model_pagina_id");
			$check = $conn->query($query);
		}
		echo $check;
	}

	if (isset($_POST['criar_novo_modelo'])) {
		$query = prepare_query("INSERT INTO Paginas (tipo, subtipo, compartilhamento, user_id) VALUES ('pagina', 'modelo', 'privado', $user_id)");
		$conn->query($query);
		$novo_modelo_pagina_id = $conn->insert_id;
		echo $novo_modelo_pagina_id;
	}

	if (isset($_POST['escritorio_modelo_operation'])) {
		if (!isset($_SESSION['user_opcoes']['show_bfranklin'])) {
			$_SESSION['user_opcoes']['show_bfranklin'] = array(true, 'auto');
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao, opcao_tipo, opcao_string) VALUES ($user_id, 'show_bfranklin', true, 'auto')");
			$conn->query($query);
		}
		$escritorio_modelo_operation = $_POST['escritorio_modelo_operation'];
		$escritorio_modelo_pagina_id = $_POST['escritorio_modelo_pagina_id'];
		$escritorio_modelo_pagina_info = return_pagina_info($escritorio_modelo_pagina_id);
		$escritorio_modelo_pagina_subtipo = $escritorio_modelo_pagina_info[8];
		$escritorio_modelo_user_id = $escritorio_modelo_pagina_info[5];
		$escritorio_modelo_compartilhamento = $escritorio_modelo_pagina_info[4];
		$permitir = false;
		if ($escritorio_modelo_pagina_subtipo == 'modelo') {
			if ($escritorio_modelo_compartilhamento == 'privado') {
				if ($escritorio_modelo_user_id == $user_id) {
					$permitir = true;
				}
			} else {
				$permitir = true;
			}
		}
		if ($permitir == true) {
			if ($escritorio_modelo_operation == 'adicionar_modelo') {
				$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($user_escritorio, 'escritorio', $escritorio_modelo_pagina_id, 'modelo', 'added', $user_id)");
				$check = $conn->query($query);
			} elseif ($escritorio_modelo_operation == 'remover_modelo') {
				$query = prepare_query("UPDATE Paginas_elementos SET estado = 0 WHERE pagina_id = $user_escritorio AND tipo = 'modelo' AND elemento_id = $escritorio_modelo_pagina_id");
				$check = $conn->query($query);
			}
			echo $check;
		}
	}

	if (isset($_POST['modelo_esconder_paragrafo'])) {
		$modelo_esconder_paragrafo_pagina_id = $_POST['modelo_esconder_paragrafo'];
		$query = prepare_query("UPDATE Paginas_elementos SET extra = 'hidden' WHERE elemento_id = $modelo_esconder_paragrafo_pagina_id AND pagina_id = $user_escritorio AND estado = 1");
		$check = $conn->query($query);
		echo $check;
	}

	if (isset($_POST['modelo_mostrar_paragrafo'])) {
		$modelo_mostrar_paragrafo_pagina_id = $_POST['modelo_mostrar_paragrafo'];
		$query = prepare_query("UPDATE Paginas_elementos SET extra = 'added' WHERE elemento_id = $modelo_mostrar_paragrafo_pagina_id AND pagina_id = $user_escritorio AND estado = 1");
		$check = $conn->query($query);
		echo $check;
	}

	if (isset($_POST['apagar_etiqueta_pagina_id'])) {
		$apagar_etiqueta_pagina_id = $_POST['apagar_etiqueta_pagina_id'];
		$check = false;
		if ($user_tipo == 'admin') {
			$apagar_etiqueta_pagina_info = return_pagina_info($apagar_etiqueta_pagina_id);
			$apagar_etiqueta_pagina_subtipo = $apagar_etiqueta_pagina_info[8];
			if ($apagar_etiqueta_pagina_subtipo == 'etiqueta') {
				$query = prepare_query("DELETE FROM Paginas WHERE id = $apagar_etiqueta_pagina_id");
				$conn->query($query);
				$apagar_etiqueta_id = $apagar_etiqueta_pagina_info[1];
				$query = prepare_query("DELETE FROM Etiquetas WHERE id = $apagar_etiqueta_id");
				$check = $conn->query($query);
			}
		}
		echo $check;
	}

	if (isset($_POST['permitir_acesso_por_link'])) {
		$permitir_acesso_por_link_pagina_id = $_POST['permitir_acesso_por_link'];
		$permitir_acesso_por_link_pagina_info = return_pagina_info($permitir_acesso_por_link_pagina_id);
		$permitir_acesso_por_link_user_id = $permitir_acesso_por_link_pagina_info[5];
		if ($permitir_acesso_por_link_user_id == $user_id) {
			$random_link = generateRandomString(12, 'capsintegers');
			$query = prepare_query("UPDATE Paginas SET link = '$random_link' WHERE id = $permitir_acesso_por_link_pagina_id");
			$check = $conn->query($query);
			echo $check;
		}
	}

	if (isset($_POST['novo_estado_plano_id'])) {
		$novo_estado_entrada_id = $_POST['novo_estado_entrada_id'];
		$novo_estado_codigo = $_POST['novo_estado_codigo'];
		$novo_estado_plano_id = $_POST['novo_estado_plano_id'];
		$query = prepare_query("UPDATE Planejamento SET estado = $novo_estado_codigo WHERE plano_id = $novo_estado_plano_id AND id = $novo_estado_entrada_id AND user_id = $user_id");
		$check = $conn->query($query);
		echo $check;
		if (!isset($_SESSION['user_opcoes']['show_planos'])) {
			$_SESSION['user_opcoes']['show_planos'] = array(true, 'auto');
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao, opcao_string) VALUES ($user_id, 'show_planos', true, 'auto')");
			$conn->query($query);
		}
	}

	if (isset($_POST['set_comment'])) {
		$set_comment = $_POST['set_comment'];
		$set_comment = mysqli_real_escape_string($conn, $set_comment);
		$set_comment = strip_tags($set_comment, false);
		$set_comment_elemento_id = $_POST['set_comment_elemento_id'];
		$query = prepare_query("UPDATE Planejamento SET comments = '$set_comment' WHERE elemento_id = $set_comment_elemento_id AND user_id = $user_id");
		$conn->query($query);
	}

	if (isset($_POST['plan_set_tag'])) {
		$plan_set_tag = $_POST['plan_set_tag'];
		$plan_set_tag_elemento_id = $_POST['set_tag_elemento_id'];
		$plan_set_tag = mysqli_real_escape_string($conn, $plan_set_tag);
		$plan_set_tag = strip_tags($plan_set_tag, false);
		$nova_etiqueta_id = criar_etiqueta($plan_set_tag, false, 'topico', $user_id, false, false, false);
		$nova_etiqueta_id = $nova_etiqueta_id[0];
		$nova_etiqueta_pagina_id = return_pagina_id($nova_etiqueta_id, 'etiqueta');
		$query = prepare_query("UPDATE Planejamento SET classificacao = $nova_etiqueta_pagina_id WHERE elemento_id = $plan_set_tag_elemento_id AND user_id = $user_id");
		$conn->query($query);
	}

	if (isset($_POST['criar_simulado_pagina_id'])) {
		$criar_simulado_pagina_id = $_POST['criar_simulado_pagina_id'];
		$criar_simulado_pagina_info = return_pagina_info($criar_simulado_pagina_id);
		$check = false;
		if (($user_tipo == 'admin') && ($criar_simulado_pagina_info != false)) {
			$criar_simulado_pagina_tipo = $criar_simulado_pagina_info[2];
			if ($criar_simulado_pagina_tipo == 'curso') {
				$criar_simulado_curso_id = $criar_simulado_pagina_info[1];
			}
			$query = prepare_query("INSERT INTO Simulados (curso_id, contexto_pagina_id, user_id) VALUES ($criar_simulado_curso_id, $criar_simulado_pagina_id, $user_id)");
			$conn->query($query);
			$novo_simulado_id = $conn->insert_id;
			$query = prepare_query("INSERT INTO Paginas (item_id, tipo, subtipo, compartilhamento, user_id) VALUES ($novo_simulado_id, 'pagina', 'simulado', 'privado', $user_id)");
			$conn->query($query);
			$novo_simulado_pagina_id = $conn->insert_id;
			$query = prepare_query("UPDATE Simulados SET pagina_id = $novo_simulado_pagina_id WHERE id = $novo_simulado_id");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Paginas_elementos (estado, pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES (0, $criar_simulado_pagina_id, '$criar_simulado_pagina_tipo', $novo_simulado_id, 'simulado', $novo_simulado_pagina_id, $user_id)");
			$check = $conn->query($query);
		}
		echo $check;
	}

	if (isset($_POST['usuario_upvote_anotacao_id'])) {
		$usuario_upvote_anotacao_id = $_POST['usuario_upvote_anotacao_id'];
		$usuario_upvote_pagina_id = $_POST['usuario_upvote_pagina_id'];
		$query = prepare_query("SELECT id FROM Votos WHERE user_id = $user_id AND pagina_id = $usuario_upvote_pagina_id AND objeto = $usuario_upvote_anotacao_id AND tipo = 'anotacao_publicada' AND valor = 1");
		$check = $conn->query($query);
		if ($check->num_rows > 0) {
			echo false;
		} else {
			$query = prepare_query("INSERT INTO Votos (user_id, pagina_id, objeto, tipo, valor) VALUES ($user_id, $usuario_upvote_pagina_id, $usuario_upvote_anotacao_id, 'anotacao_publicada', 1)");
			$check = $conn->query($query);
			echo $check;
		}
	}

	if (isset($_POST['listar_elementos_pagina_id'])) {
		$final_result_elementos = false;
		$listar_elementos_pagina_id = $_POST['listar_elementos_pagina_id'];
		$query = prepare_query("SELECT * FROM Paginas_elementos WHERE pagina_id = $listar_elementos_pagina_id ORDER BY id DESC");
		$pagina_elementos = $conn->query($query);
		if ($pagina_elementos->num_rows > 0) {
			while ($pagina_elemento = $pagina_elementos->fetch_assoc()) {
				$pagina_elemento_estado = $pagina_elemento['estado'];
				if ($pagina_elemento_estado == 0) {
					$item_texto_class = 'text-muted fst-italic';
					$item_icone = 'fad fa-toggle-off fa-swap-opacity';
					$item_color = 'text-muted';
					$item_operacao = 'reativar_elemento_item';
				} else {
					$item_texto_class = false;
					$item_icone = 'fad fa-toggle-on';
					$item_color = 'link-teal';
					$item_operacao = 'remover_elemento_item';
				}
				$item_texto = false;
				switch ($pagina_elemento['tipo']) {
					case 'titulo':
						$pagina_elemento_titulo = $pagina_elemento['extra'];
						$item_texto = "<strong>Título:</strong> $pagina_elemento_titulo";
						break;
					case 'materia':
						$pagina_elemento_materia_pagina_id = $pagina_elemento['elemento_id'];
						$pagina_elemento_materia_pagina_titulo = return_pagina_titulo($pagina_elemento_materia_pagina_id);
						$item_texto = "<strong>Matéria:</strong> $pagina_elemento_materia_pagina_titulo";
						break;
					case 'simulado':
						$pagina_elemento_simulado_pagina_id = $pagina_elemento['extra'];
						$pagina_elemento_simulado_pagina_titulo = return_pagina_titulo($pagina_elemento_simulado_pagina_id);
						$item_texto = "<strong>Simulado:</strong> $pagina_elemento_simulado_pagina_titulo";
						break;
					case 'topico':
						break;
					case 'plano de estudos':
						$item_texto = "<strong>Plano de estudos</strong>";
						break;
					case 'referencia':
					case 'imagem':
					case 'video':
					case 'audio':
						$pagina_elemento_elemento_id = $pagina_elemento['elemento_id'];
						$pagina_elemento_pagina_id = return_pagina_id($pagina_elemento_elemento_id, 'elemento');
						$pagina_elemento_pagina_titulo = return_pagina_titulo($pagina_elemento_pagina_id);
						$item_texto = "<strong>Elemento:</strong> $pagina_elemento_pagina_titulo";
						break;
					case 'subtopico':
						$pagina_elemento_subtopico_id = $pagina_elemento['elemento_id'];
						$pagina_elemento_subtopico_titulo = return_pagina_titulo($pagina_elemento_subtopico_id);
						$item_texto = "<strong>Subtópico:</strong> $pagina_elemento_subtopico_titulo";
						break;
					case 'wikipedia':
						$pagina_elemento_extra = $pagina_elemento['extra'];
						$item_texto = "<strong>Wikipédia</strong>: $pagina_elemento_extra";
						break;
					default:
						$item_texto = serialize($pagina_elemento);
				}
				if ($item_texto != false) {
					$final_result_elementos .= put_together_list_item('link_button', $pagina_elemento['id'], $item_color, $item_icone, $item_texto, false, false, false, $item_operacao, "text-break $item_texto_class");
				}
			}
		}
		$final_result_elementos = list_wrap($final_result_elementos);
		echo $final_result_elementos;
	}

	if (isset($_POST['desabilitar_elemento_id'])) {
		$desabilitar_elemento_id = $_POST['desabilitar_elemento_id'];
		$query = prepare_query("UPDATE Paginas_elementos SET estado = 0 WHERE id = $desabilitar_elemento_id");
		$check = $conn->query($query);
		echo $check;
	}
	if (isset($_POST['reativar_elemento_id'])) {
		$reativar_elemento_id = $_POST['reativar_elemento_id'];
		$query = prepare_query("UPDATE Paginas_elementos SET estado = 1 WHERE id = $reativar_elemento_id");
		$check = $conn->query($query);
		echo $check;
	}
	if (isset($_POST['change_color'])) {
		$change_color = $_POST['change_color'];
		$_SESSION['user_opcoes']['quill_colors'][1] = $change_color;
		$query = prepare_query("UPDATE Opcoes SET opcao_string = '$change_color' WHERE user_id = $user_id AND opcao_tipo = 'quill_colors'");
		$conn->query($query);
	}

	if (isset($_POST['list_nexus_links'])) {
		echo "
			<form method='post'>
				<h3></h3>
				<p></p>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_link_url'>Paste your link url:</label>
					<input type='url' class='form-control' id='nexus_new_link_url' name='nexus_new_link_url'>
				</div>
				<button type='button' class='btn btn-info col-4 mx-auto' id='trigger_suggest_title'>Suggest title</button>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_link_title'>Link title:</label>
					<input type='text' class='form-control' id='nexus_new_link_title' name='nexus_new_link_title'>
				</div>
				<div class='mb-3'>
				<label class='form-label' for='nexus_new_link_location'>Where to place your new link?</label>
				<select id='nexus_new_link_location' name='nexus_new_link_localtion' class='form-select mb-3'>
					<option selected value='linkdump'>Link dump</option>";

					$query = prepare_query("SELECT id, title, icon, color FROM nexus_folders WHERE user_id = $user_id AND pagina_id = {$_SESSION['user_nexus_pagina_id']}");
					$user_nexus_folders_info = $conn->query($query);
					if ($user_nexus_folders_info->num_rows > 0) {
						while ($user_nexus_folder_info = $user_nexus_folders_info->fetch_assoc()) {
							$user_nexus_folder_id = $user_nexus_folder_info['id'];
							$user_nexus_folder_title = $user_nexus_folder_info['title'];
							$user_nexus_folder_icon = $user_nexus_folder_info['icon'];
							$user_nexus_folder_color = $user_nexus_folder_info['color'];
							echo "<option value='$user_nexus_folder_id'>$user_nexus_folder_title</option>";
						}
					} else {
						echo "<option disabled>You have not created any folders.</option>";
					}


			echo "
				</select>
				</div>
				<button type='submit' class='btn btn-primary col-6 mx-auto'>Add link</button>
			</form>
		";
	}

	if (isset($_POST['scan_new_link'])) {
		$new_link_title = get_title($_POST['scan_new_link']);
		unset($_POST['scan_new_link']);
		echo $new_link_title;
	}

	if (isset($_POST['list_nexus_folders'])) {
		echo "
			<form method='post'>
				<h3>Add folder</h3>
				<p>To add new folders, fill-in the form below:</p>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_folder_title'>Name of your new folder:</label>
					<input type='text' class='form-control' id='nexus_new_folder_title' name='nexus_new_folder_title'>
				</div>
				<select id='nexus_new_folder_icon' name='nexus_new_folder_icon' class='form-select mb-3'>
					<option selected disabled>Select an icon</option>";
		echo "
					<option value='fad fa-circle-quarters'>Circle-quarters</option>
					<option value='fad fa-triangle fa-swap-opacity'>Triangle</option>
					<option value='fad fa-square fa-swap-opacity'>Square</option>
					<option value='fad fa-hexagon fa-swap-opacity'>Hexagon</option>
					<option value='fad fa-circle-notch fa-swap-opacity'>Circle-notch</option>
					<option value='fad fa-circle-dashed'>Circle-dashed</option>
					<option value='fad fa-circle-dot'>Circle-dot</option>
					<option value='fad fa-shield-halved'>Shield-halved</option>
					<option value='fad fa-box-archive'>Box-archive</option>
					<option value='fad fa-cube'>Cube</option>
					<option value='fad fa-planet-ringed'>Planet-ringed</option>
					<option value='fad fa-solar-system'>Solar-system</option>
					<option value='fad fa-moon-over-sun'>Moon-over-sun</option>
					<option value='fad fa-meteor'>Meteor</option>
					<option value='fad fa-eclipse'>Eclipse</option>
					<option value='fad fa-comet'>Comet</option>";
		echo "</select>
				<select id='nexus_new_folder_color' name='nexus_new_folder_color' class='form-select mb-3'>
					<option selected disabled>Select a color</option>
					<option value='link-danger'>Red</option>
					<option value='link-warning'>Yellow</option>
					<option value='link-success'>Green</option>
					<option value='link-primary'>Blue</option>
					<option value='link-teal'>Teal</option>
					<option value='link-info'>Cyan</option>
					<option value='link-purple'>Purple</option>
					<option value='link-dark'>Black</option>
				</select>
				<button type='submit' class='btn btn-primary col-6 mx-auto'>Create folder</button>
			</form>
			<hr>
			<h3>Remove folder</h3>
			<form method='post'>
				<p>When you remove a folder, all links that had been added to it are also removed, though you can still find everything in the log.</p>
				<select id='nexus_del_folder_id' name='nexus_del_folder_id' class='form-select mb-3'>
					<option selected disabled>Select a folder to remove</option>
		";
		$query = prepare_query("SELECT id, title, icon, color FROM nexus_folders WHERE user_id = $user_id AND pagina_id = {$_SESSION['user_nexus_pagina_id']}");
		$user_nexus_folders_info = $conn->query($query);
		if ($user_nexus_folders_info->num_rows > 0) {
			while ($user_nexus_folder_info = $user_nexus_folders_info->fetch_assoc()) {
				$user_nexus_folder_id = $user_nexus_folder_info['id'];
				$user_nexus_folder_title = $user_nexus_folder_info['title'];
				$user_nexus_folder_icon = $user_nexus_folder_info['icon'];
				$user_nexus_folder_color = $user_nexus_folder_info['color'];
				echo "<option value='$user_nexus_folder_id'>$user_nexus_folder_title, $user_nexus_folder_icon, $user_nexus_folder_color</option>";
			}
			echo "</select>";
			echo "<button type='submit' class='btn btn-danger col-6 mx-auto'>Delete folder</button>";
			echo "</form>";
		} else {
			echo "<p>No folders found.</p>";
		}
	}

?>
