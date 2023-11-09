<?php

	if (!isset($pagina_tipo)) {
		$pagina_tipo = false;
	}

	include 'criar_sessao.php';
	include 'functions.php';

	$user_logged_out = false;

	if (!isset($_SESSION['user_email'])) {
		$_SESSION['user_email'] = false;
		if ((!isset($_POST['login_email'])) && (!isset($_POST['thinkific_login']))) {
			if (($_SESSION['user_email'] == false) && ($pagina_tipo != 'logout') && ($pagina_tipo != 'login') && ($pagina_tipo != 'index')) {
				$user_logged_out = true;
				$user_id = false;
				$user_tipo = 'visitante';
				$_SESSION['user_email'] = false;
//				header('Location:logout.php');
//				exit();
			}
		}
	}

	include 'templates/criar_conn.php';

	if (isset($_POST['thinkific_login'])) {
		$thinkific_login = $_POST['thinkific_login'];
		$thinkific_senha = $_POST['thinkific_senha'];
		$encrypted = password_hash($thinkific_senha, PASSWORD_DEFAULT);
		$query = prepare_query("UPDATE usuarios SET senha = '$encrypted' WHERE email = '$thinkific_login'");
		$set_password = $conn->query($query);
		if ($set_password == true) {
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['login_email'])) {
		$login_email = $_POST['login_email'];
		if (!isset($_POST['login_senha2'])) {
			$_POST['login_senha2'] = false;
		}
		if ($_POST['login_senha2'] == false) {
			$login_origem = $_POST['login_origem'];
			$query = prepare_query("SELECT senha, origem FROM usuarios WHERE email = '{$_POST['login_email']}'");
			$hashes = $conn->query($query);
			if ($hashes->num_rows > 0) {
				while ($hash = $hashes->fetch_assoc()) {
					$hash_senha = $hash['senha'];
					$hash_origem = $hash['origem'];
					$check = password_verify($_POST['login_senha'], $hash_senha);
					if ($check == true) {
						if (($hash_origem == false) || ($hash_origem == 'confirmado') || ($hash_origem == 'thinkific')) {

							$_SESSION['user_email'] = $_POST['login_email'];
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
			$encrypted = password_hash($_POST['login_senha'], PASSWORD_DEFAULT);
			$query = prepare_query("SELECT id FROM usuarios WHERE email = '$login_email'");
			$check_preexistente = $conn->query($query);
			if ($check_preexistente->num_rows == 0) {
				$query = prepare_query("INSERT INTO usuarios (tipo, email, senha) VALUES ('estudante', '$login_email', '$encrypted')");
				$check = $conn->query($query);
				if ($check == true) {
					$_SESSION['user_email'] = $_POST['login_email'];
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
		if ($_SESSION['user_email'] != false) {
			$query = "SELECT * FROM usuarios WHERE email = '{$_SESSION['user_email']}'";
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
			$user_apelido = 'User';
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
		$user_apelido = 'User';
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

	if (!in_array($_SESSION['user_language'], array('en', 'pt', 'fr', 'es'))) {
		$_SESSION['user_language'] = 'en';
		$user_language = 'en';
	}
	if (!isset($_SESSION['pagina_translated'])) {
		$pagina_translated = translate_pagina($user_language);
		$_SESSION['pagina_translated'] = $pagina_translated;
	} else {
		$pagina_translated = $_SESSION['pagina_translated'];
	}

	if (empty($_SESSION['pagina_translated'])) {
		$_SESSION['pagina_translated'] = translate_pagina('en');
		$pagina_translated = $_SESSION['pagina_translated'];
	}

	$select_classes = "browser-default custom-select mt-2";
	$coluna_todas = "px-3";
	$coluna_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$coluna_maior_classes = "col-lg-9 col-md-10 col-sm-11 $coluna_todas";
	$coluna_media_classes = "col-lg-7 col-md-10 col-sm-11 $coluna_todas";
	$coluna_pouco_maior_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$row_classes = "pt-3 pb-5";

	$tag_ativa_classes = 'col-auto link-dark me-1 mb-1 p-2 rounded remover_tag border';
	$tag_inativa_classes = 'col-auto link-dark me-1 mb-1 p-2 rounded adicionar_tag border';
	$tag_neutra_classes = 'col-auto link-dark me-1 mb-1 p-2 rounded border';
	$tag_inativa_classes2 = 'col-auto link-dark me-1 mb-1 p-2 rounded adicionar_tag2 border';

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
		error_log('does this ever happen?'); // doesn't really happen
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
			$busca_resultados .= "<button type='button' id='criar_referencia' name='criar_referencia' class='btn btn-outline-primary mb-2 col-12' value='$busca_referencias'>{$pagina_translated['Referência não encontrada, criar nova?']}</button>";
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
			$busca_resultados .= "<button type='button' id='$acao_etiqueta_criar' name='$acao_etiqueta_criar' class='btn btn-outline-success mb-2' value='$busca_etiquetas'>{$pagina_translated['Criar etiqueta']} \"$busca_etiquetas\"</button>";
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
			$query = prepare_query("SELECT apelido, id FROM usuarios WHERE apelido LIKE '%$busca_apelido%' ORDER BY apelido");
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
		$query = prepare_query("UPDATE usuarios SET origem = 'confirmado' WHERE origem = '$confirmacao'");
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
			$query = prepare_query("SELECT id FROM usuarios WHERE email = '$nova_senha_email'");
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_id = $usuario['id'];
					$query = prepare_query("UPDATE usuarios SET senha = '$nova_senha_encrypted', origem = '$confirmacao' WHERE id = $usuario_id");
					$conn->query($query);
				}
			} else {
				$query = prepare_query("INSERT INTO usuarios (email, origem, senha) VALUES ('$nova_senha_email', '$confirmacao', '$nova_senha_encrypted')");
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
				$result .= put_together_list_item('link_button', $edicao_do_usuario_id, 'link-danger', 'fad fa-trash-alt', "<span class='font-monospace'>$edicao_do_usuario_criacao:</span> $edicao_do_usuario_pagina_titulo", false, false, false, 'delete_edit', false, false);
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
				if ($alteracao_recente != false) {
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
			$query = prepare_query("SELECT email FROM usuarios");
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
				<p>Fill-in the form below to add a link or <button id='show_modal_add_links_bulk' type='button' class='btn btn-outline-primary btn-sm' class='btn btn-outline-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modal_add_links_bulk'>click here</button> to add links in bulk to one destination.</p>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_link_url'>Paste your link url:</label>
					<input type='url' class='form-control' id='nexus_new_link_url' name='nexus_new_link_url'>
				</div>
				<button type='button' class='btn btn-sm btn-outline-secondary mb-3' id='trigger_suggest_title'>Suggest title</button>
				<button type='button' class='btn btn-sm btn-outline-danger mb-3 d-none' id='trigger_hide_suggestions'>Hide suggestions</button>
				<div class='mb-3 d-none border rounded bg-light p-1' id='populate_with_title_suggestions'>
				
				</div>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_link_title'>Link title:</label>
					<input type='text' class='form-control' id='nexus_new_link_title' name='nexus_new_link_title'>
				</div>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_link_location'>Where to place your new link?</label>
					<select id='nexus_new_link_location' name='nexus_new_link_location' class='form-select mb-3'>";
		echo return_folder_list($_SESSION['nexus_folders'], array('linkdump' => true));
		echo "
			</select>
				</div>
				<div class='mb-3'>
					<label class='form-label' for='#nexus_new_link_icon'>Choose an icon:</label>
					<select id='nexus_new_link_icon' name='nexus_new_link_icon' class='form-select'>
						<option value='random' selected>Random</option>";
		$nexus_icons = nexus_icons(array('mode' => 'list'));
		foreach (array_keys($nexus_icons) as $key) {
			$capitalize = ucfirst($key);
			echo "<option value='{$key}'>$capitalize</option>";
		}
		echo "
			</select>
				</div>
				<div class='mb-3'>
					<label class='form-label' for='#nexus_new_link_color'>Choose a color:</label>
					<select id='nexus_new_link_color' name='nexus_new_link_color' class='form-select'>
						<option value='random' selected>Random</option>
						<option value='blue'>Blue</option>
						<option value='indigo'>Indigo</option>
						<option value='purple'>Purple</option>
						<option value='pink'>Pink</option>
						<option value='red'>Red</option>
						<option value='orange'>Orange</option>
						<option value='yellow'>Yellow</option>
						<option value='green'>Green</option>
						<option value='teal'>Teal</option>
						<option value='cyan'>Cyan</option>
					</select>
				</div>
				<button type='submit' class='btn btn-primary mb-3' name='nexus_new_link_submit' id='nexus_new_link_submit'>Add link</button>
			</form>
		";
	}

	if (isset($_POST['remove_this_link'])) {
		if ($_POST['remove_this_link'] != false) {
			$query = prepare_query("UPDATE nexus_elements SET state = 0 WHERE user_id = {$_SESSION['user_id']} AND state = 1 AND param_int_2 = {$_POST['remove_this_link']}");
			$check = $conn->query($query);
			echo $check;
			unset($_SESSION['nexus_links']);
		}
	}

	if (isset($_POST['remove_this_folder'])) {
		if ($_POST['remove_this_folder'] != false) {
			$query = prepare_query("DELETE FROM nexus_folders WHERE id = {$_POST['remove_this_folder']} AND user_id = {$_SESSION['user_id']} AND pagina_id = {$_SESSION['user_nexus_pagina_id']}");
			$check = $conn->query($query);
			echo $check;
			unset($_SESSION['nexus_links']);
		}
	}

	if (isset($_POST['scan_new_link'])) {
		$suggestions = nexus_suggest_title($_POST['scan_new_link']);
		if ($suggestions == false) {
			echo "No suggestions could be created.";
		} else {
			$result = create_title_suggestion_buttons('new', $suggestions);
			echo $result;
		}
		unset($_POST['scan_new_link']);
		exit();
	}

	if (isset($_POST['scan_new_link_id'])) {
		$query = "SELECT param1 FROM nexus_elements WHERE param_int_2 = {$_POST['scan_new_link_id']}";
		$elements = $conn->query($query);
		$scan_new_link_id_url = false;
		if ($elements->num_rows > 0) {
			while ($element = $elements->fetch_assoc()) {
				$scan_new_link_id_url = $element['param1'];
				break;
			}
		}
		$suggestions = nexus_suggest_title($scan_new_link_id_url);
		if ($suggestions == false) {
			echo "No suggestions could be created.";
		} else {
			$result = create_title_suggestion_buttons('update', $suggestions);
			echo $result;
		}
		unset($_POST['scan_new_link_id']);
		exit();
	}


	if (isset($_POST['populate_themes_modal'])) {
		$themes_return = false;
		$themes_return .= "
			<div class='form-check form-check-inline mb-3'>
				<input class='form-check-input' type='radio' name='theme_form_options' id='radio_pick_theme' value='pick' checked>
				<label class='form-check-label' for='radio_pick_theme'>Pick theme</label>
			</div>
			<div class='form-check form-check-inline mb-3'>
				<input class='form-check-input' type='radio' name='theme_form_options' id='radio_add_theme' value='add'>
				<label class='form-check-label' for='radio_add_theme'>Add theme</label>
			</div>
			<div class='form-check form-check-inline mb-3'>
				<input class='form-check-input' type='radio' name='theme_form_options' id='radio_del_theme' value='del'>
				<label class='form-check-label' for='radio_del_theme'>Delete theme</label>
			</div>
			<div class='form-check form-check-inline mb-3'>
				<input class='form-check-input' type='radio' name='theme_form_options' id='radio_edit_theme' value='edit' disabled>
				<label class='form-check-label' for='radio_edit_theme'>Edit theme</label>
			</div>
		";

		$themes_return .= "
			<form method='post'>
				<h3 class='add_theme_details d-none'>Add theme</h3>
				<div class='mb-3 add_theme_details d-none'>
					<label class='form-label' for='new_theme_title'>Please give your theme a title</label>
					<input class='form-control' type='text' id='new_theme_title' name='new_theme_title' placeholder='Theme title'>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label class='form-label' for='wallpaper_file_url'>URL to the wallpaper file</label>
					<input class='form-control' type='url' id='wallpaper_file_url' name='wallpaper_file_url' placeholder='Wallpaper file url'>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label class='form-label' for='new_theme_bg_color_hex'>Background color hex code</label>
					<input class='form-control' type='text' id='new_theme_bg_color_hex' name='new_theme_bg_color_hex' placeholder='Color hex code'>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label for='new_theme_hometext_color' class='form-label'>Hometext color</label>
					<select id='new_theme_hometext_color' name='new_theme_hometext_color' class='form-select'>
						<option value='white'>White</option>
						<option value='black'>Black</option>
						<option value='blue'>Blue</option>
						<option value='indigo'>Indigo</option>
						<option value='purple'>Purple</option>
						<option value='pink'>Pink</option>
						<option value='red'>Red</option>
						<option value='orange'>Orange</option>
						<option value='yellow'>Yellow</option>
						<option value='green'>Green</option>
						<option value='teal'>Teal</option>
						<option value='cyan'>Cyan</option>
					</select>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label for='new_theme_wallpaper_display' class='form-label'>How to set the wallpaper</label>
					<select id='new_theme_wallpaper_display' name='new_theme_wallpaper_display' class='form-select'>
						<option value='cover'>Cover</option>
						<option value='center-contain'>Centralized and contained</option>
						<option value='center-contain-tile'>Centralized, contained and tiled</option>
						<option value='tile'>Tiled</option>
						<option value='stretch'>Stretched</option>
						<option value='center-no-strech'>Centralized and not stretched</option>
					</select>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label for='new_theme_hometext_font' class='form-label'>Hometext font</label>
					<select id='new_theme_hometext_font' name='new_theme_hometext_font' class='form-select'>
						<option value='lato'>Lato</option>
						<option value='osc'>Open Sans Condensed</option>
						<option value='vollkorn'>Vollkorn</option>
						<option value='playfair'>Playfair Display</option>
						<option value='comfortaa'>Comfortaa</option>
					</select>
				</div>
				<div class='mb-3 add_theme_details d-none'>
					<label for='new_theme_hometext_effect' class='form-label'>Hometext effect</label>
					<select id='new_theme_hometext_effect' name='new_theme_hometext_effect' class='form-select'>
						<option value='unset'>None</option>
						<option value='overlay'>Overlay</option>
						<option value='color-burn'>Color-burn</option>
						<option value='color-dodge'>Color-dodge</option>
						<option value='difference'>Difference</option>
					</select>
				</div>
				<button type='submit' class='btn btn-primary add_theme_details d-none'>Save new theme</button>
			</form>
		";

		$query = prepare_query("SELECT param_int_1 FROM nexus_elements WHERE pagina_id = {$_SESSION['user_nexus_pagina_id']} AND state = 1 AND type = 'theme'");
		$active_themes = $conn->query($query);
		$nexus_del_theme_options = false;
		if ($active_themes->num_rows > 0) {
			while ($active_theme = $active_themes->fetch_assoc()) {
				$active_theme_info = return_theme($active_theme['param_int_1']);
				if ($active_theme_info != false) {
					$selected = false;
					if ($active_theme['param_int_1'] == $_POST['populate_themes_modal']) {
						$selected = 'selected';
					}
					$nexus_del_theme_options .= "<option value='{$active_theme['param_int_1']}' $selected>{$active_theme_info['title']}</option>";
				}
			}
		}

		$themes_return .= "
				<form method='post' class='del_theme_details d-none'>
					<h3 class='del_theme_details d-none'>Delete theme</h3>
					<label for='del_theme_select' class='form-label del_theme_details d-none'>Which theme to delete?</label>
					<select id='del_theme_select' name='del_theme_select' class='form-select mb-3 del_theme_details d-none'>
						$nexus_del_theme_options
					</select>
					<button type='submit' class='btn btn-danger del_theme_details d-none'>Delete theme</button>
				</form>
			";

		$available_themes = array('landscape' => array('title' => 'Random landscapes'), 'random' => array('title' => 'Random background colors'), 'light' => array('title' => 'Random light tiles'), 'dark' => array('title' => 'Random dark tiles'), 'whimsical' => array('title' => 'Random silly, whimsical tiles',), 'AIlandscapes' => array('title' => 'Random AI landscapes'));
		$nexus_theme_options = false;
		$nexus_theme_options .= "<optgroup label='Default random themes'>";
		foreach ($available_themes as $key => $array) {
			$selected = false;
			if ($key == $_POST['populate_themes_modal']) {
				$selected = 'selected';
			}
			$nexus_theme_options .= "<option value='$key' $selected>{$available_themes[$key]['title']}</option>";
		}
		$nexus_theme_options .= "</optgroup>";

		$query = prepare_query("SELECT param_int_1 from nexus_elements WHERE (user_id = {$_SESSION['user_id']} AND pagina_id = {$_SESSION['user_nexus_pagina_id']} AND type = 'theme' AND state = 1)");
		$user_themes = $conn->query($query);
		$user_theme_results = array();
		$user_themes_result = false;
		if ($user_themes->num_rows > 0) {
			$user_themes_result = true;
			while ($user_theme = $user_themes->fetch_assoc()) {
				$user_theme_id = $user_theme['param_int_1'];
				array_push($user_theme_results, $user_theme_id);
			}
		}
		$lock_on_current_button = false;
		if (is_numeric($_POST['populate_themes_modal']) == false) {
			$lock_on_current_button = "<button type='button' class='btn btn-outline-secondary pick_theme_details' id='trigger_lock_theme'>Lock on current</button>";
		}
		if ($user_themes_result == true) {
			$nexus_theme_options .= "<optgroup label='Your themes'>";
		}
		foreach ($user_theme_results as $id) {
			$query = prepare_query("SELECT * FROM nexus_themes WHERE id = $id");
			$user_themes_found = $conn->query($query);
			if ($user_themes_found->num_rows > 0) {
				while ($user_theme_found = $user_themes_found->fetch_assoc()) {
					$selected = false;
					if ($id == $_POST['populate_themes_modal']) {
						$selected = 'selected';
					}
					$user_theme_found_id = $id;
					$user_theme_found_title = $user_theme_found['title'];
					$nexus_theme_options .= "<option value='$user_theme_found_id' $selected>$user_theme_found_title</option>";
				}
			}
		}
		if ($user_themes_result == true) {
			$nexus_theme_options .= "</optgroup>";
		}
		$themes_return .= "
				<form method='post'>
					<h3 class='pick_theme_details'>Pick theme</h3>
					<label for='nexus_theme_select' class='form-label pick_theme_details'>Take your pick of general appearance:</label>
					<select id='nexus_theme_select' name='nexus_theme_select' class='form-select mb-3 pick_theme_details'>
						$nexus_theme_options
					</select>
					<button type='submit' class='btn btn-primary pick_theme_details'>Pick theme</button>
						$lock_on_current_button
				</form>
			";
		echo $themes_return;
	}

	if (isset($_POST['new_theme_title'])) {
		if ($_POST['new_theme_title'] == false) {
			exit();
		}
		$_POST['new_theme_title'] = mysqli_real_escape_string($conn, $_POST['new_theme_title']);
		if (!isset($_POST['wallpaper_file_url'])) {
			$_POST['wallpaper_file_url'] = false;
		}
		$_POST['wallpaper_file_url'] = mysqli_real_escape_string($conn, $_POST['wallpaper_file_url']);
		if (!isset($_POST['new_theme_bg_color_hex'])) {
			$_POST['new_theme_bg_color_hex'] = false;
		}
		$new_theme_bg_color_hex = validate_hex($_POST['new_theme_bg_color_hex']);
		if (!isset($_POST['new_theme_hometext_color'])) {
			$_POST['new_theme_hometext_color'] = false;
		}
		if (!isset($_POST['new_theme_wallpaper_display'])) {
			$_POST['new_theme_wallpaper_display'] = false;
		}
		if (!isset($_POST['new_theme_hometext_font'])) {
			$_POST['new_theme_hometext_font'] = false;
		}
		if (!isset($_POST['new_theme_hometext_effect'])) {
			$_POST['new_theme_hometext_effect'] = false;
		}
		$query = prepare_query("INSERT INTO nexus_themes (user_id, title, url, bghex, homehex, wallpaper, homefont, homeeffect) VALUES ({$_SESSION['user_id']}, '{$_POST['new_theme_title']}', '{$_POST['wallpaper_file_url']}', '$new_theme_bg_color_hex', '{$_POST['new_theme_hometext_color']}', '{$_POST['new_theme_wallpaper_display']}', '{$_POST['new_theme_hometext_font']}', '{$_POST['new_theme_hometext_effect']}')");
		$conn->query($query);
		$new_theme_id = $conn->insert_id;
		$query = prepare_query("INSERT INTO nexus_elements (user_id, pagina_id, type, param_int_1) VALUES ({$_SESSION['user_id']}, {$_SESSION['user_nexus_pagina_id']}, 'theme', $new_theme_id)");
		$conn->query($query);
		$query = prepare_query("UPDATE nexus SET theme = $new_theme_id WHERE pagina_id = {$_SESSION['user_nexus_pagina_id']}");
		$conn->query($query);
	}

	if (isset($_POST['list_nexus_folders'])) {
		echo "
			<form method='post'>
				<p>You may add a folder by filling-in the form below or <button id='show_modal_add_folders_bulk' type='button' class='btn btn-outline-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modal_add_folders_bulk'>click here</button> to add folders in bulk.</p>
				<div class='mb-3'>
					<label class='form-label' for='nexus_new_folder_title'>Name of your new folder:</label>
					<input type='text' class='form-control' id='nexus_new_folder_title' name='nexus_new_folder_title'>
				</div>
				<div class='mb-3'>
				<label for='nexus_new_folder_icon' class='form-label'>Select an icon:</label>
				<select id='nexus_new_folder_icon' name='nexus_new_folder_icon' class='form-select mb-3'>
					<option selected value='0'>Random icon</option>";
		$nexus_icons = nexus_icons(array('mode' => 'list'));
		foreach (array_keys($nexus_icons) as $key) {
			$capitalize = ucfirst($key);
			echo "<option value='{$nexus_icons[$key]}'>$capitalize</option>";
		}

		echo "				</select>
				</div>
				<div class='mb-3'>
				<label for='nexus_new_folder_color' class='form-label'>Select a color:</label>
				<select id='nexus_new_folder_color' name='nexus_new_folder_color' class='form-select mb-3'>
					<option value='0' selected>Random color</option>
					<option value='blue'>Blue</option>
					<option value='indigo'>Indigo</option>
					<option value='purple'>Purple</option>
					<option value='pink'>Pink</option>
					<option value='red'>Red</option>
					<option value='orange'>Orange</option>
					<option value='yellow'>Yellow</option>
					<option value='green'>Green</option>
					<option value='teal'>Teal</option>
					<option value='cyan'>Cyan</option>
				</select>
				</div>
				<div class='form-check mb-3'>
					<input class='form-check-input' type='checkbox' value='' id='nexus_new_folder_type' name='nexus_new_folder_type' checked>
					<label class='form-check-label' for='nexus_new_folder_type'>This is a main folder: its icon will appear on the top bar. Otherwise, it will only be found alongside other \"archive\" folders.</label>
				</div>
				<button type='submit' class='btn btn-primary'>Add folder</button>
			</form>
		";
	}

	if (isset($_POST['populate_add_folders_bulk'])) {
		echo "
		<p>You will be able to change the details later. For now, all you need is a name for each.</p>
		<form method='post'>
			<div class='mb-3'>
				<label for='new_bulk_folders_type' class='form-label'>Type of folders ot create:</label>
				<select class='form-select' id='new_bulk_folders_type' name='new_bulk_folders_type'>
					<option value='main'>Main</option>
					<option value='archival'>Archival</option>
				</select>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_1' class='form-label'>Name for new folder #1</label>
				<input id='new_bulk_folder_1' type='text' class='form-control' name='new_bulk_folder_1'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_2' class='form-label'>Name for new folder #2</label>
				<input id='new_bulk_folder_2' type='text' class='form-control' name='new_bulk_folder_2'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_3' class='form-label'>Name for new folder #3</label>
				<input id='new_bulk_folder_3' type='text' class='form-control' name='new_bulk_folder_3'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_4' class='form-label'>Name for new folder #4</label>
				<input id='new_bulk_folder_4' type='text' class='form-control' name='new_bulk_folder_4'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_5' class='form-label'>Name for new folder #5</label>
				<input id='new_bulk_folder_5' type='text' class='form-control' name='new_bulk_folder_5'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_6' class='form-label'>Name for new folder #6</label>
				<input id='new_bulk_folder_6' type='text' class='form-control' name='new_bulk_folder_6'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_folder_7' class='form-label'>Name for new folder #7</label>
				<input id='new_bulk_folder_7' type='text' class='form-control' name='new_bulk_folder_7'>
			</div>
			<button type='submit' class='btn btn-primary'>Create new folders</button>
		</form>
		";
	}

	if (isset($_POST['populate_add_links_bulk'])) {
		echo "
		<p>Add the urls below and the Nexus will try to find a good name for each, along with random colors and icons. You may edit everything later.</p>
		<form method='post'>
		<div class='mb-3'>
			<label for='new_bulk_links_folder' class='form-label'>Folder in which to place the new links:</label>
			<select class='form-select' id='new_bulk_links_folder' name='new_bulk_links_folder'>
				<option selected value='0'>Link Dump</option>";

		$query = prepare_query("SELECT id, type, title, icon, color, type FROM nexus_folders WHERE user_id = $user_id AND pagina_id = {$_SESSION['user_nexus_pagina_id']}");
		$folders_info = $conn->query($query);
		if ($folders_info->num_rows > 0) {
			$main_folders = false;
			$archive_folders = false;
			while ($folder = $folders_info->fetch_assoc()) {
				$folder_id = $folder['id'];
				$folder_type = $folder['type'];
				$folder_title = $folder['title'];
				$folder_icon = $folder['icon'];
				$folder_color = $folder['color'];
				$folder_option = "<option value='$folder_id'>$folder_title ($folder_color $folder_icon)</option>";
				if ($folder_type == 'main') {
					$main_folders .= $folder_option;
				} elseif ($folder_type == 'archival') {
					$archive_folders .= $folder_option;
				}
			}
		}
		echo $main_folders;
		echo $archive_folders;

		echo "
			</select>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_1' class='form-label'>New link url #1</label>
				<input id='new_bulk_link_1' name='new_bulk_link_1' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_2' class='form-label'>New link url #2</label>
				<input id='new_bulk_link_2' name='new_bulk_link_2' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_3' class='form-label'>New link url #3</label>
				<input id='new_bulk_link_3' name='new_bulk_link_3' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_4' class='form-label'>New link url #4</label>
				<input id='new_bulk_link_4' name='new_bulk_link_4' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_5' class='form-label'>New link url #5</label>
				<input id='new_bulk_link_5' name='new_bulk_link_5' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_6' class='form-label'>New link url #6</label>
				<input id='new_bulk_link_6' name='new_bulk_link_6' type='url' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='new_bulk_link_7' class='form-label'>New link url #7</label>
				<input id='new_bulk_link_7' name='new_bulk_link_7' type='url' class='form-control'>
			</div>
			<button type='submit' class='btn btn-primary'>Add links to the selected destination</button>
		</form>
		";
	}

	if (isset($_POST['shred_nexus'])) {
		$query = prepare_query("TRUNCATE TABLE `ubwiki`.`nexus_elements`");
		$conn->query($query);
		$query = prepare_query("TRUNCATE `Ubwiki`.`nexus_folders`");
		$conn->query($query);
		$query = prepare_query("TRUNCATE TABLE `ubwiki`.`nexus_handles`");
		$conn->query($query);
		$query = prepare_query("TRUNCATE TABLE `ubwiki`.`nexus_links`");
		$conn->query($query);
		$query = prepare_query("TRUNCATE TABLE `ubwiki`.`nexus_log`");
		$conn->query($query);
		$query = prepare_query("TRUNCATE TABLE `ubwiki`.`nexus_options`");
		$conn->query($query);
		echo 'done';
	}

	if (isset($_POST['send_log'])) {
		if (isset($_SESSION['user_id'])) {
			if ($_SESSION['user_id'] != false) {
				$query = prepare_query("SELECT timestamp, type, message FROM nexus_log WHERE user_id = {$_SESSION['user_id']}");
				$logs = $conn->query($query);
				if ($logs->num_rows > 0) {
					$log_list = false;
					$log_list .= "
					<ul class='list-group'>";
					while ($log = $logs->fetch_assoc()) {
						$date = substr($log['timestamp'], 0, 10);
						$log_list .= "
						<li class='list-group-item d-flex justify-content-between align-items-center' title='{$log['timestamp']}: {$log['type']}'>
							{$log['message']}
							<span class='badge nexus-bg-blue text-light rounded-pill'><em>
								$date
							</em></span>
						</li>";
					}
					$log_list .= "
					</ul>";
				} else {
					$log_list = "<p>No logs were found.</p>";
				}
				echo $log_list;
			} else {
				echo false;
			}
		} else {
			echo false;
		}
	}

	if (isset($_POST['populate_links'])) {
		$result_folder = false;
		$result_large = false;
		$result_default = false;
		$result_compact = false;
		foreach ($_SESSION['nexus_folders'][$_POST['populate_links']] as $link_id => $link_info) {
			if (!isset($_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['url'])) {
				continue;
			}
			if (!isset($_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['diff'])) {
				$diff = 'link_normal';
			} else {
				$diff = $_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['diff'];
			}
			$icon = nexus_put_together(array('type' => $diff, 'id' => $link_id, 'href' => $_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['url'], 'color' => $_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['color'], 'icon' => $_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['icon'], 'title' => $_SESSION['nexus_folders'][$_POST['populate_links']][$link_id]['title'], 'class' => "link_of_folder_{$_POST['populate_links']}"));
			switch ($diff) {
				case 'folder_slim':
					$result_folder .= $icon;
					break;
				case 'link_large':
					$result_large .= $icon;
					break;
				case 'link_compact':
					$result_compact .= $icon;
					break;
				case 'link_default':
				default:
					$result_default .= $icon;
					break;
			}
		}
		echo "
			$result_folder <hr class='m-0 opacity-0'> $result_large <hr class='m-0 opacity-0'> $result_default <hr class='m-0 opacity-0'> $result_compact
		";
	}

	if (isset($_POST['populate_options_modal'])) {
		if ($_SESSION['nexus_options']['cmd_link_id'] == true) {
			$cmd_link_id_check = 'checked';
		} else {
			$cmd_link_id_check = false;
		}
		$options_modal = false;
		$options_modal .= "
			<div class='form-check form-switch mb-3'>
				<input class='form-check-input' type='checkbox' role='switch' id='nexus_cmd_link_id' $cmd_link_id_check>
				<label class='form-check-label' for='nexus_cmd_link_id'>Use link id numbers in the command bar. It's a faster way of opening your links, but that would require memorizing their ids. The command bar will still find typed titles. <span id='saved_cmd_link_id' class='text-success'><i class='fa-solid fa-cloud-check fa-fw'></i></span></label>
			</div>
			<script>
					$(document).on('click', '#nexus_cmd_link_id', function () {
					    $(document).find('#saved_cmd_link_id').removeClass('text-success');
					    $(document).find('#saved_cmd_link_id').addClass('text-muted');
					    let cmd_link_id = $('#nexus_cmd_link_id').is(':checked');
						$.post('engine.php', {
							'cmd_link_id': cmd_link_id
						}, function (data) {
							if (data == true) {
								$(document).find('#saved_cmd_link_id').removeClass('text-muted');
								$(document).find('#saved_cmd_link_id').addClass('text-success');
							} else if (data == false) {
								$(document).find('#saved_cmd_link_id').removeClass('text-muted');
								$(document).find('#saved_cmd_link_id').addClass('text-danger');   
							}
						})
					});
			</script>
			<hr>
			<div class='mb-3'>
				<label for='option_justify_links' class='form-label'>How to justify link icons on the screen?</label> <span id='saved_option_justify_links' class='text-success'><i class='fa-solid fa-cloud-check fa-fw'></i></span>
				<select id='option_justify_links' name='option_justify_links' class='form-select'>
					<option value='justify-content-start'>Justify left</option>
					<option value='justify-content-center'>Justify center</option>
					<option value='justify-content-between'>Justify between</option>
					<option value='justify-content-around'>Justify around</option>
					<option value='justify-content-evenly'>Justify evenly</option>
					<option value='justify-content-end'>Justify right</option>
				</select>
			</div>
			<script>
					$(document).on('click', '#option_justify_links', function () {
					    $(document).find('#saved_option_justify_links').removeClass('text-success');
					    $(document).find('#saved_option_justify_links').addClass('text-muted');
					    let option_justify_links = $('#option_justify_links').find(':selected').val();
						$.post('engine.php', {
							'option_justify_links': option_justify_links
						}, function (data) {
							if (data == true) {
								$(document).find('#saved_option_justify_links').removeClass('text-muted');
								$(document).find('#saved_option_justify_links').addClass('text-success');
                                $(document).find('.specific_folder_links').removeClass('justify-content-start');
                                $(document).find('.specific_folder_links').removeClass('justify-content-center');
                                $(document).find('.specific_folder_links').removeClass('justify-content-between');
                                $(document).find('.specific_folder_links').removeClass('justify-content-around');
                                $(document).find('.specific_folder_links').removeClass('justify-content-evenly');
                                $(document).find('.specific_folder_links').addClass(option_justify_links);
                                $(document).find('#under_cmdbar').removeClass('justify-content-start');
                                $(document).find('#under_cmdbar').removeClass('justify-content-center');
                                $(document).find('#under_cmdbar').removeClass('justify-content-between');
                                $(document).find('#under_cmdbar').removeClass('justify-content-around');
                                $(document).find('#under_cmdbar').removeClass('justify-content-evenly');
                                $(document).find('#under_cmdbar').addClass(option_justify_links);
							} else if (data == false) {
								$(document).find('#saved_option_justify_links').removeClass('text-muted');
								$(document).find('#saved_option_justify_links').addClass('text-danger');   
							}
						})
					});
			</script>
			<hr>
			<div class='mb-3'>
						<p><button id='trigger_redo_icons' type='button' class='btn btn-success btn-sm'>Click here</button> to redo all icons in your Nexus randomly, with minimal control so there's not too much repetition of colors and symbols. The page will reload when finished.</p>
			</div>
		";
		echo $options_modal;
	}

	if (isset($_POST['analyse_cmd_input'])) {
		if (!isset($_SESSION['nexus_options']['cmd_link_id'])) {
			$_SESSION['nexus_options']['cmd_link_id'] = false;
		} else {
			if ($_SESSION['nexus_options']['cmd_link_id'] == true) {
				//Analysis starts here. First step: is it a link code?
				if (isset($_SESSION['nexus_codes'][$_POST['analyse_cmd_input']])) {
					//If so, it's easy.
					echo $_SESSION['nexus_codes'][$_POST['analyse_cmd_input']]['url'];
					exit();
				}
			}
		}
		//Next step is to check if it's a command.
		$cmd_check = substr($_POST['analyse_cmd_input'], 0, 1);
		if ($cmd_check == '/') {
			$result = process_cmd(array('input' => $_POST['analyse_cmd_input'], 'pagina_id' => $_SESSION['user_nexus_pagina_id'], 'user_id' => $_SESSION['user_id']));
			if ($result == false) {
				echo "#MESSG#No match found";
				exit();
			} else {
				switch ($result['type']) {
					case 'url':
						echo $result['url'];
						exit();
					default:
						echo "#MESSG#No suitable command found";
						exit();
				}
			}
		}
		if (isset($_SESSION['nexus_alphabet'][$_POST['analyse_cmd_input']])) {
			$cmd_link_id = $_SESSION['nexus_alphabet'][$_POST['analyse_cmd_input']];
			echo $_SESSION['nexus_links'][$cmd_link_id]['url'];
			exit();
		}
		$match = array();
		$match_result = strlen($_POST['analyse_cmd_input']);
		$lower_cmd_input = strtolower($_POST['analyse_cmd_input']);
		$stripos_finds = array();
		$levenshtein_finds = array();
		$levenshtein_threshould = strlen($_POST['analyse_cmd_input']) / 3;
		foreach ($_SESSION['nexus_alphabet'] as $key => $array) {
			$lower_key = strtolower($key);
			$this_included = stripos($key, $_POST['analyse_cmd_input']);
			$this_match = levenshtein($lower_cmd_input, $lower_key);
			if ($this_match == 0) {
				echo $_SESSION['nexus_links'][$array]['url'];
				exit();
			}
			if ($this_included !== false) {
				$stripos_finds[$key] = $_SESSION['nexus_alphabet'][$key];
			} else {
				if ($this_match <= $levenshtein_threshould) {
					$levenshtein_finds[$key] = $_SESSION['nexus_alphabet'][$key];
				}
			}
			if ($this_match < $match_result) {
				$match_result = $this_match;
				$match['best_match'] = $_SESSION['nexus_alphabet'][$key];
			}
		}
		if (isset($match['best_match'])) {
			if ($match_result >= $levenshtein_threshould) {
				unset($match['best_match']);
			} else {
				if (isset($levenshtein_finds[$match['best_match']]))
					unset($levenshtein_finds[$match['best_match']]);
			}
		}
		$return = "<ul class='list-group'><li class='list-group-item'>No match found</li></ul>";
		if (($levenshtein_finds != false) || ($stripos_finds != false) || ($match != false)) {
			$return = false;
		}
		$count = 0;
		$one_title = false;
		$loaded = array();
		if (isset($match['best_match'])) {
			$count++;
			$one_title = $_SESSION['nexus_links'][$match['best_match']]['title'];
			$return .= nexus_put_together(array('type' => 'link_large', 'id' => $match['best_match'], 'href' => $_SESSION['nexus_links'][$match['best_match']]['url'], 'color' => $_SESSION['nexus_links'][$match['best_match']]['color'], 'icon' => $_SESSION['nexus_links'][$match['best_match']]['icon'], 'title' => $one_title, 'class' => 'link_from_cmdbar'));
		}
		if ($levenshtein_finds != false) {
			foreach ($levenshtein_finds as $title => $id) {
				if ($id == $match['best_match']) {
					continue;
				}
				$count++;
				$one_title = $title;
				$return .= nexus_put_together(array('type' => 'link_large', 'id' => $id, 'href' => $_SESSION['nexus_links'][$id]['url'], 'color' => $_SESSION['nexus_links'][$id]['color'], 'icon' => $_SESSION['nexus_links'][$id]['icon'], 'title' => $title, 'class' => 'link_from_cmdbar'));
			}
		}
		if ($stripos_finds != false) {
			if ($levenshtein_finds != false) {
				$return .= "<hr class='m-0 opacity-0'>";
			}
			foreach ($stripos_finds as $title => $id) {
				if (isset($match['best_match'])) {
					if ($id == $match['best_match']) {
						continue;
						$no_levenshtein = true;
					}
				}
				$count++;
				$one_title = $title;
				$return .= nexus_put_together(array('type' => 'link_large', 'id' => $id, 'href' => $_SESSION['nexus_links'][$id]['url'], 'color' => $_SESSION['nexus_links'][$id]['color'], 'icon' => $_SESSION['nexus_links'][$id]['icon'], 'title' => $title, 'class' => 'link_from_cmdbar'));
			}
		}
//		if ($match['result'] <= 8) {
//			echo $match['title'];
//			exit();
//		}
		switch ($count) {
			case 0:
				echo "#MESSG#No match found";
				break;
			case 1:
				echo "#MESSG#$title";
				break;
			default:
				echo "#HTMLS#$return";
				break;
		}
		exit();
	}

	if (isset($_POST['cmd_link_id'])) {
		$_POST['cmd_link_id'] = (int)filter_var($_POST['cmd_link_id'], FILTER_VALIDATE_BOOLEAN);
		nexus_options(array('mode' => 'set', 'pagina_id' => $_SESSION['user_nexus_pagina_id'], 'option' => 'cmd_link_id', 'choice' => boolval($_POST['cmd_link_id'])));
		$_SESSION['nexus_options'] = nexus_options(array('mode' => 'read', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
		if ($_SESSION['nexus_options']['cmd_link_id'] == boolval($_POST['cmd_link_id'])) {
			echo true;
		} else {
			echo false;
		}
		unset($_SESSION['nexus_options']);
		exit();
	}

	if (isset($_POST['option_justify_links'])) {
		$check = nexus_options(array('mode' => 'set', 'pagina_id' => $_SESSION['user_nexus_pagina_id'], 'option' => 'justify_links', 'choice' => $_POST['option_justify_links']));
		echo $check;
		unset($_SESSION['nexus_options']);
		exit();
	}

	if (isset($_POST['nexus_logout'])) {
		session_unset();
		session_destroy();
		$user_id = false;
		echo true;
	}

	if (isset($_POST['populate_icons_titles'])) {
		$populate_icons_titles = false;
		$populate_icons_titles .= "
			<div class='mb-3'>
			<p>Target a link or a folder?</p>
			<input type='hidden' id='details_loaded' value='false'>
			<form method='post'>
			<div class='form-check mb-3'>
				<input class='form-check-input' type='radio' name='manage_icon_title_choice' value='link' id='manage_icon_title_links' checked>
				<label class='form-check-label' for='manage_icon_title_links'><i class='fad fa-link fa-fw me-2 nexus-link-teal'></i>Links</label>
			</div>
			<div class='form-check mb-3'>
				<input class='form-check-input' type='radio' name='manage_icon_title_choice' value='folder' id='manage_icon_title_folders'>
				<label class='form-check-label' for='manage_icon_title_folders'><i class='fad fa-folders fa-fw me-2 nexus-link-orange'></i>Folders</label>
			</div>
			</div>
			<hr class='manage_link_hide'>
			<div class='mb-3 manage_link_hide'>
				<label for='manage_icon_title_link_title' class='form-label'>Select link to manage:</label>
				<select class='form-select change_trigger_show_details' id='manage_icon_title_link_id' name='manage_icon_title_link_id'>
				<option selected disabled>Select link</option>
				";
		foreach ($_SESSION['nexus_alphabet'] as $title => $id) {
			$populate_icons_titles .= "<option value='$id'>$title</option>";
		}
		$populate_icons_titles .= "
				</select>
			</div>	
			<hr class='manage_folder_hide d-none'>
			<div class='mb-3'>
			<label class='form-label manage_folder_hide d-none' for='manage_icon_title_folder_id'>Select folder to manage:</label>
			<select id='manage_icon_title_folder_id' name='manage_icon_title_folder_id' class='form-select manage_folder_hide change_trigger_show_details d-none'>
				<option selected disabled>Select folder</option>";
		$populate_icons_titles .= return_folder_list($_SESSION['nexus_folders'], array('linkdump' => false));
		$populate_icons_titles .= "
			</select>
			</div>
			<hr class='manage_details_hide d-none'>
			<div class='mb-3'>
			<label class='form-label manage_details_hide d-none' for='manage_icon_title_new_color'>Select the new color:</label>
			<select id='manage_icon_title_new_color' name='manage_icon_title_new_color' class='form-select manage_details_hide d-none'>
				<option selected disabled>Leave as is</option>";
		$colors = nexus_colors(array('mode' => 'list'));
		foreach ($colors as $color) {
			$color_capitalized = ucfirst($color);
			$populate_icons_titles .= "<option value='$color'>$color_capitalized</option>";
		}
		$populate_icons_titles .= "
			</select>
			</div>
			<div class='mb-3'>
			<label for='manage_icon_title_new_icon' class='form-label manage_details_hide d-none'>Select or type the new Fontawesome icon:</label>
			<input list='icons_list' name='manage_icon_title_new_icon' id='manage_icon_title_new_icon' class='form-select manage_details_hide d-none'>
			<div id='icon help' class='form-text manage_details_hide d-none'>Example of fontawesome icon code: \"fa-paintbrush\". To see all icon options, visite the <a href='https://fontawesome.com/icons' target='_blank'>Fontawesome Website</a>.</div>
			<datalist id='icons_list'>
				<option selected disabled>Leave as is</option>";
		$icons = nexus_icons(array('mode' => 'list'));
		foreach ($icons as $icon => $key) {
			$icon_capitalized = ucfirst($icon);
			$populate_icons_titles .= "<option value='$icon'>$icon_capitalized</option>";
		}
		$populate_icons_titles .= "
			</datalist>
			</div>
			<div class='mb-3 manage_details_hide d-none'>
				<label for='manage_icon_title_new_title' class='form-label manage_details_hide d-none'>New title:</label>
				<input class='form-control manage_details_hide d-none' type='text' id='manage_icon_title_new_title' name='manage_icon_title_new_title' placeholder='Leave as is'>
			</div>
			<button type='button' class='btn btn-outline-secondary btn-sm d-none manage_details_hide mb-2' id='trigger_suggest_new_title'>Suggest new titles</button>
			<div class='mb-3 manage_details_hide d-none border rounded bg-light p-1' id='manage_suggest_titles'></div>
		";

		$populate_icons_titles .= "
			<div class='mb-3 manage_details_hide manage_details_folders_only d-none'>
				<label for='change_folder_type' class='form-label manage_details_folders_only d-none'>Change folder type:</label>
				<select id='change_folder_type' name='change_folder_type' class='form-select manage_details_folders_only d-none'>
					<option disabled selected>Leave as is</option>
					<option value='main'>Main</option>
					<option value='archival'>Archival</option>
				</select>
			</div>
		";

		$populate_icons_titles .= "
			<div class='mb-3 manage_details_hide manage_details_links_only d-none'>
				<label for='move_to_this_folder_id' class='form-label manage_details_hide manage_details_links_only d-none'>Move to another folder:</label>
				<select id='move_to_this_folder_id' name='move_to_this_folder_id' class='form-select mb-3 manage_details_hide manage_details_links_only d-none'>
					<option disabled selected>Leave in current folder</option>";
		$populate_icons_titles .= return_folder_list($_SESSION['nexus_folders'], array('linkdump' => true));
		$populate_icons_titles .= "
				</select>
			</div>
		";

		$populate_icons_titles .= "
		<div class='mb-3 manage_details_hide manage_details_links_only d-none'>
			<label for='diff_this_link_type' class='form-label'>How to display this link:</label>
			<select id='diff_this_link_type' name='diff_this_link_type' class='form-select mb-3'>
				<option disabled selected>Leave as is</option>
				<!--<option value='folder_slim'>Folder: as used for folders</option>-->
				<option value='link_large'>Large: 3x-sized icon and centralized title</option>
				<option value='link_normal'>Default: text-sized icon</option> 
				<option value='link_compact'>Compact: default with less empty space</option>
				<!--<option value='medium'>Medium</option>-->
				<!--<option value='small'>Small</option>-->
				<!--<option value='navbar'>Navbar: as used for navbar icons, symbol only, no title</option>-->
			</select>
		</div>
			<button type='submit' class='btn btn-primary manage_details_hide d-none'>Update</button>
			<button id='trigger_delete_this_link' type='button' class='btn btn-outline-danger manage_details_hide manage_details_links_only d-none'>Delete this link</button>
			<button id='trigger_delete_this_folder' type='button' class='btn btn-outline-danger manage_details_hide manage_details_folders_only d-none'>Delete this folder</button>
			</form>
		";
		$populate_icons_titles .= "</form>";
		echo $populate_icons_titles;
	}

	if (isset($_POST['manage_icon_title_choice'])) {
		if (!isset($_POST['manage_icon_title_new_title'])) {
			$_POST['manage_icon_title_new_title'] = false;
		}
		if (!isset($_POST['manage_icon_title_link_id'])) {
			$_POST['manage_icon_title_link_id'] = false;
		}
		if (!isset($_POST['manage_icon_title_folder_id'])) {
			$_POST['manage_icon_title_folder_id'] = false;
		}
		if (!isset($_POST['manage_icon_title_new_icon'])) {
			$_POST['manage_icon_title_new_icon'] = false;
		}
		if (!isset($_POST['manage_icon_title_new_color'])) {
			$_POST['manage_icon_title_new_color'] = false;
		}
		if (!isset($_POST['manage_icon_title_new_title'])) {
			$_POST['manage_icon_title_new_title'] = false;
		}
		if (!isset($_POST['diff_this_link_type'])) {
			$_POST['diff_this_link_type'] = false;
		}
		if (!isset($_POST['move_to_this_folder_id'])) {
			$_POST['move_to_this_folder_id'] = false;
		}
		if (!isset($_POST['change_folder_type'])) {
			$_POST['change_folder_type'] = false;
		}

		if (($_POST['manage_icon_title_choice'] == 'folder') && ($_POST['manage_icon_title_folder_id'] != false)) {
			if ($_POST['manage_icon_title_new_icon'] != false) {
				$query = prepare_query("UPDATE nexus_folders SET icon = '{$_POST['manage_icon_title_new_icon']}' WHERE user_id = {$_SESSION['user_id']} AND id = {$_POST['manage_icon_title_folder_id']} ");
				$conn->query($query);
			}
			if ($_POST['manage_icon_title_new_color'] != false) {
				$query = prepare_query("UPDATE nexus_folders SET color = '{$_POST['manage_icon_title_new_color']}' WHERE user_id = {$_SESSION['user_id']} AND id = {$_POST['manage_icon_title_folder_id']} ");
				$conn->query($query);
			}
			if ($_POST['manage_icon_title_new_title'] != false) {
				$query = prepare_query("UPDATE nexus_folders SET title = '{$_POST['manage_icon_title_new_title']}' WHERE user_id = {$_SESSION['user_id']} AND id = {$_POST['manage_icon_title_folder_id']} ");
				$conn->query($query);
			}
			if ($_POST['change_folder_type'] != false) {
				$query = prepare_query("UPDATE nexus_folders SET type = '{$_POST['change_folder_type']}' WHERE user_id = {$_SESSION['user_id']} AND id = {$_POST['manage_icon_title_folder_id']}");
				$conn->query($query);
			}
		} elseif (($_POST['manage_icon_title_choice'] == 'link') && ($_POST['manage_icon_title_link_id']) != false) {
			if ($_POST['manage_icon_title_new_icon'] != false) {
				$query = prepare_query("UPDATE nexus_elements SET param3 = '{$_POST['manage_icon_title_new_icon']}' WHERE user_id = {$_SESSION['user_id']} AND param_int_2 = {$_POST['manage_icon_title_link_id']}");
				$conn->query($query);
			}
			if ($_POST['manage_icon_title_new_color'] != false) {
				$query = prepare_query("UPDATE nexus_elements SET param4 = '{$_POST['manage_icon_title_new_color']}' WHERE user_id = {$_SESSION['user_id']} AND param_int_2 = {$_POST['manage_icon_title_link_id']}");
				$conn->query($query);
			}
			if ($_POST['manage_icon_title_new_title'] != false) {
				nexus_handle(array('id' => $_POST['manage_icon_title_link_id'], 'title' => $_POST['manage_icon_title_new_title']));
				$query = prepare_query("UPDATE nexus_elements SET param2 = '{$_POST['manage_icon_title_new_title']}' WHERE user_id = {$_SESSION['user_id']} AND param_int_2 = {$_POST['manage_icon_title_link_id']}");
				$conn->query($query);
			}
			if ($_POST['diff_this_link_type'] != false) {
				$query = prepare_query("UPDATE nexus_elements SET param5 = '{$_POST['diff_this_link_type']}' WHERE user_id = {$_SESSION['user_id']} AND param_int_2 = {$_POST['manage_icon_title_link_id']} AND state = 1");
				$conn->query($query);
			}
			if ($_POST['move_to_this_folder_id'] != false) {
				$query = prepare_query("UPDATE nexus_elements SET param_int_1 = {$_POST['move_to_this_folder_id']} WHERE pagina_id = {$_SESSION['user_nexus_pagina_id']} AND param_int_2 = {$_POST['manage_icon_title_link_id']}");
				$conn->query($query);
			}
		}
		unset($_SESSION['nexus_links']);
	}

	if (isset($_POST['redo_all_icons'])) {
		include 'templates/criar_conn.php';
		$query = prepare_query("UPDATE nexus SET random_icons = false, random_colors = false WHERE user_id = {$_SESSION['user_id']}");
		foreach ($_SESSION['nexus_folders'] as $key => $array) {
			if ($_SESSION['nexus_folders'][$key]['info']['type'] != 'main') {
				continue;
			}
			$new_icon = nexus_icons(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
			$new_color = nexus_colors(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
			$query = prepare_query("UPDATE nexus_folders SET icon = '$new_icon', color = '$new_color' WHERE id = $key");
			$conn->query($query);
		}
		foreach ($_SESSION['nexus_folders'] as $key => $array) {
			if ($_SESSION['nexus_folders'][$key]['info']['type'] != 'archival') {
				continue;
			}
			$new_icon = nexus_icons(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
			$new_color = nexus_colors(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
			$query = prepare_query("UPDATE nexus_folders SET icon = '$new_icon', color = '$new_color' WHERE id = $key");
			$conn->query($query);
		}
		foreach ($_SESSION['nexus_folders'] as $key => $array) {
			$query = prepare_query("UPDATE nexus SET random_icons = false, random_colors = false WHERE user_id = {$_SESSION['user_id']}");
			if ($key == 'linkdump') {
				$key = '0';
			}
			$query = prepare_query("SELECT id FROM nexus_elements WHERE param_int_1 = $key AND state = 1");
			$links = $conn->query($query);
			if ($links->num_rows > 0) {
				while ($link = $links->fetch_assoc()) {
					$link_id = $link['id'];
					$new_icon = nexus_icons(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
					$new_color = nexus_colors(array('mode' => 'random', 'user_id' => $_SESSION['user_id']));
					$query = prepare_query("UPDATE nexus_elements SET param3 = '$new_icon', param4 = '$new_color' WHERE id = $link_id");
					$conn->query($query);
				}
			}
		}
		echo true;
		unset($_SESSION['nexus_links']);
	}

	if (isset($_POST['trigger_lock_theme'])) {
		if (isset($_SESSION['current_theme'])) {
			$t = time();
			$locked_time = date("Y-m-d H:i:s", $t);
			$locked_time = "Locked on $locked_time";
			$query = prepare_query("INSERT INTO nexus_themes (user_id, title, url, bghex, homehex, wallpaper, homefont, homeeffect) VALUES ({$_SESSION['user_id']}, '$locked_time', '{$_SESSION['current_theme']['url']}', '{$_SESSION['current_theme']['bghex']}', '{$_SESSION['current_theme']['homehex']}', '{$_SESSION['current_theme']['wallpaper']}', '{$_SESSION['current_theme']['homefont']}', '{$_SESSION['current_theme']['homeeffect']}')");
			$conn->query($query);
			$new_theme_id = $conn->insert_id;
			$query = prepare_query("INSERT INTO nexus_elements (user_id, pagina_id, type, state, param_int_1) VALUES ({$_SESSION['user_id']}, {$_SESSION['user_nexus_pagina_id']}, 'theme', 1, $new_theme_id)");
			$conn->query($query);
			$query = prepare_query("UPDATE nexus SET theme = $new_theme_id WHERE user_id = {$_SESSION['user_id']}");
			$check = $conn->query($query);
			echo $check;
			exit();
		} else {
			echo false;
			exit();
		}
	}

	//	if (isset($_POST['populate_move_links'])) {
	//		$populate_move_links = false;
	//		$populate_move_links .= "<form method='post'>
	//			<div class='mb-3'>
	//				<label for='move_this_link_id' class='form-label'>Select link to move:</label>
	//				<select id='move_this_link_id' name='move_this_link_id' class='form-select mb-3'>
	//					<option disabled selected>Link to move</option>";
	//		foreach ($_SESSION['nexus_alphabet'] as $title => $id) {
	//			$populate_move_links .= "<option value='$id'>$title</option>";
	//		}
	//		$populate_move_links .= "
	//				</select>
	//			</div>
	//			<div class='mb-3'>
	//				<label for='move_to_this_folder_id' class='form-label'>Select destination folder:</label>
	//				<select id='move_to_this_folder_id' name='move_to_this_folder_id' class='form-select mb-3'>
	//					<option disabled selected>Folder to move to</option>";
	//		$populate_move_links .= return_folder_list($_SESSION['nexus_folders'], array('linkdump' => true));
	//		$populate_move_links .= "
	//				</select>
	//			</div>
	//			<button class='btn btn-primary' type='submit'>Move link</button>
	//		</form>";
	//
	//		echo $populate_move_links;
	//	}

	if (isset($_POST['find_repeats_text'])) {
		if (!isset($_POST['repeats_word_repeats'])) {
			$_POST['repeats_word_repeats'] = 2;
		}
		if (!isset($_POST['repeats_word_length'])) {
			$_POST['repeats_word_length'] = 4;
		}
		$text = $_POST['find_repeats_text'];
		$text = strip_tags($text, '<p>');
		$text = str_replace('&nbsp;', '', $text);
		$words = array_count_values(str_word_count($text, 1, 'ãáèñíéõçóêîôâ'));
		arsort($words);
		$words_serialized = serialize($words);
		$repeats_result = false;
		$count = 0;
		foreach ($words as $key => $value) {
			if ($value < $_POST['repeats_word_repeats']) {
				continue;
			}
			if (strlen($key) <= $_POST['repeats_word_length']) {
				continue;
			}
			if ($count == 9) {
				$count = 0;
			}
			$color = number_to_color($count);
			$count++;
			$bg_color = nexus_colors(array('mode' => 'convert', 'color' => $color));
			$bg_color = $bg_color['highlight'];
			$text = str_replace($key, "<strong class='$bg_color px-1'>$key</strong>", $text);
			$repeats_result .= "
				<li class='list-group-item d-flex justify-content-between align-items-start'>
					<div class='form-check form-switch'>
						<a href='javascript:void(0);' class='link-primary'><input class='form-check-input' type='checkbox' role='switch' id='repeated_word_$count' value='$key' checked></a>
						<label class='form-check-label' for='repeated_word_$count'>$key</label>
					</div>
					<span class='badge bg-primary rounded-pill'>$value</span>
				</li>
			";
		}
		$repeats_result .= "
				<script type='text/javascript'>
					$('.repeated_word').on('click', function(){
						var repeated_word = $(this).attr('value');
					});
				</script>";
		$text = base64_encode($text);
		$repeats_result = base64_encode($repeats_result);
		$final_result = array($text, $repeats_result);
		echo json_encode($final_result);
	}

	if (isset($_POST['edit_this_log'])) {
		$query = prepare_query("SELECT * FROM travelogue WHERE id = {$_POST['edit_this_log']} AND user_id = {$_SESSION['user_id']}");
		$logs = $conn->query($query);
		$result = false;
		$types_options = false;

		if ($logs->num_rows > 0) {
			while ($log = $logs->fetch_assoc()) {
				foreach ($_SESSION['travelogue_types'] as $key => $array) {
					$selected = false;
					if ($key == $log['type']) {
						$selected = 'selected';
					}
					$types_options .= "<option value='{$key}' $selected>{$_SESSION['travelogue_types'][$key]['description']}</option>";
				}
				$log['codes'] = unserialize($log['codes']);
				$genre_list = false;
				if ($log['genre'] == false) {
					$query2 = prepare_query("SELECT DISTINCT genre FROM travelogue WHERE type = '{$log['type']}' AND user_id = {$_SESSION['user_id']} ORDER BY genre");
					$genres = $conn->query($query2);
					if ($genres->num_rows > 0) {
						while ($genre = $genres->fetch_assoc()) {
							if ($genre['genre'] == false) {
								continue;
							}
							$genre_list .= "<option>{$genre['genre']}</option>";
						}
					}
				}
				if ($genre_list == false) {
					$genre_module = "
						<div class='mb-3'>
							<label for='update_travel_new_genre' class='form-label'>Genre:</label>
							<input id='update_travel_new_genre' name='update_travel_new_genre' type='text' class='form-control' value='{$log['genre']}'>
						</div>
					";
				} else {
					$genre_module = "
						<div class='mb-3'>
							<label for='update_travel_new_genre' class='form-label'>Genre:</label>
							<input id='update_travel_new_genre' name='update_travel_new_genre' class='form-control' type='text' list='edit_item_genres'>
							<datalist id='edit_item_genres'>
								$genre_list
							</datalist>
						</div>
					";
				}

				$check = @unserialize($log['datexp']);
				$datexp_values = false;
				if ($check == false) {
					if ($log['datexp'] != false) {
						$datexp_values .= "<ul id='travel_update_datexp_list' class='list-group'>";
						$datexp_values .= "<li class='list-group-item'>{$log['datexp']}</li>";
						$datexp_values .= "</ul>";
					} else {
						$datexp_values .= "<ul id='travel_update_datexp_list' class='list-group'></ul>";
					}
				} else {
					$datexp_values .= "<ul id='travel_update_datexp_list' class='list-group'>
					<li class='list-group-item list-group-item-secondary'>Currently registered:</li>
					";
					foreach ($check as $key) {
						if ($key == false) {
							continue;
						}
						$datexp_values .= "<li class='list-group-item'>$key</li>";
					}
					$datexp_values .= "</ul>";
					$datexp_values .= "
					<div class='d-grid gap-2 d-flex justify-content-end'>
						<button type='button' class='btn btn-sm btn-outline-danger mt-1' id='delete_datexp' value='{$_POST['edit_this_log']}'>Delete all date experienced records</button>
					</div>
					";
				}

				$datexp_module = "
					<div class='mb-3 p-3 rounded border bg-light'>
						<label for=update_travel_new_datexp' class='form-label'>Date experienced: <em class='text-muted'>(recommended: YYYY, YYYYMM, or YYYYMMDD)</em></label>
						<input id='update_travel_new_datexp' name='update_travel_new_datexp' type='number' minlength='4' maxlength='8' class='form-control mb-2' value=''>
						<!--<button class='btn btn-sm btn-secondary my-1' type='button' id='update_travel_add_experienced_date'>Add experienced date</button>-->
						$datexp_values
					</div>
				";

				$result .= "
					<form method='post'>
						<div class='mb-3'>
							<label for='update_travel_new_type' class='form-label'>Type:</label>
							<select id='update_travel_new_type' name='update_travel_new_type' type='text' class='form-control'>
								$types_options
							</select>
						</div>
						<div class='mb-3'>
							<label for='update_travel_new_creator' class='form-label'>Author:</label>
							<input id='update_travel_new_creator' name='update_travel_new_creator' type='text' class='form-control' value=\"{$log['creator']}\">
						</div>
						<div class='mb-3'>
							<label for='update_travel_new_title' class='form-label'>Title:</label>
							<input id='update_travel_new_title' name='update_travel_new_title' type='text' class='form-control' value=\"{$log['title']}\">
						</div>
						$genre_module
						<div class='mb-3'>
							<label for='update_travel_new_release_date' class='form-label'>Release date: <em class='text-muted'>(recommended: YYYY, YYYYMM, or YYYYMMDD)</em></label>
							<input id='update_travel_new_release_date' name='update_travel_new_release_date' type='number' minlength='4' maxlength='8' class='form-control' value='{$log['releasedate']}'>
						</div>
						$datexp_module
						";
				if ($log['yourrating'] == '') {
					$result .= "<div class='mb-3 border p-3 rounded bg-light'>
							<label for='update_travel_new_rating' class='form-label'>Your rating (1 to 5):</label>
							<input type='range' class='form-range' id='update_travel_new_rating' name='update_travel_new_rating' min='1' max='5' disabled>
							<button id='update_trigger_enable_rating' class='btn btn-outline-secondary btn-sm' type='button'>Enable</button>
						</div>";
				} else {
					$result .= "
						<div class='mb-3 border p-3 rounded bg-light'>
							<label for='update_travel_new_rating' class='form-label'>Your rating (1 to 5):</label>
							<input type='range' class='form-range' id='update_travel_new_rating' name='update_travel_new_rating' min='1' max='5' value='{$log['yourrating']}'>
							<button id='update_trigger_disable_rating' class='btn btn-outline-secondary btn-sm' type='button'>Disable (erase rating)</button>
						</div>
					";
				}
				$result .= "
						<div class='mb-3'>
							<label for='update_travel_new_comments' class='form-label'>Your comments:</label>
							<textarea id='update_travel_new_comments' name='update_travel_new_comments' type='textarea' class='form-control' rows='3'>{$log['comments']}</textarea>
						</div>
						<div class='mb-3'>
							<label for='update_travel_new_information' class='form-label'>Relevant information:</label>
							<textarea id='update_travel_new_information' name='update_travel_new_information' type='text' class='form-control' rows='3'>{$log['otherrelevant']}</textarea>
						</div>
						<div class='mb-3'>
							<label for='update_travel_new_database' class='form-label'>Database link (Wikipedia, IMDb, AllMusic etc.):</label>
							<input id='update_travel_new_database' name='update_travel_new_database' type='url' class='form-control' value=\"{$log['dburl']}\">
						</div>";
				$result .= "<div class='mb-3'>
					<h3>Codes</h3>
				";
				foreach ($_SESSION['travelogue_codes'] as $key => $info) {
					$this_checked = false;
					if (isset($log['codes'][$key])) {
						$this_checked = 'checked';
					}
					$color = nexus_colors(array('mode' => 'convert', 'color' => $_SESSION['travelogue_codes'][$key]['color']));
					$result .= "
					<div class='form-check'>
						<input name='travel_update_code_$key' id='travel_update_code_$key' class='form-check-input' type='checkbox' value='$key' $this_checked>
						<label for='travel_update_code_$key' class='form-check-label'><i class='{$_SESSION['travelogue_codes'][$key]['icon']} me-2 {$color['link-color']} bg-dark p-1 rounded fa-fw'></i>{$_SESSION['travelogue_codes'][$key]['description']}</label>
					</div>
				";
				}
				$result .= "</div><hr>";

				$result .= "
						<button type='submit' class='btn btn-primary' name='update_this_entry' id='update_this_entry' value='{$log['id']}'>Submit</button>
						<button type='button' class='btn btn-danger' name='delete_this_entry' id='delete_this_entry' value='{$log['id']}'>Delete this entry</button>
					</form>
					";
				echo $result;
				exit();
			}
		}
	}

	if (isset($_POST['delete_this_entry'])) {
		$query = "UPDATE travelogue SET state = 0 WHERE id = {$_POST['delete_this_entry']} AND user_id = {$_SESSION['user_id']}";
		$check = $conn->query($query);
		echo $check;
	}

	if (isset($_POST['update_this_entry'])) {
		$_POST['update_travel_new_title'] = mysqli_real_escape_string($conn, $_POST['update_travel_new_title']);
		$_POST[$_POST['update_travel_new_genre']] = mysqli_real_escape_string($conn, $_POST['update_travel_new_genre']);
		$_POST['update_travel_new_comments'] = mysqli_real_escape_string($conn, $_POST['update_travel_new_comments']);
		$_POST['update_travel_new_information'] = mysqli_real_escape_string($conn, $_POST['update_travel_new_information']);

		$travel_update_codes = array();
		if (isset($_POST['travel_update_code_favorite'])) {
			$travel_update_codes['favorite'] = true;
		}
		if (isset($_POST['travel_update_code_lyrics'])) {
			$travel_update_codes['lyrics'] = true;
		}
		if (isset($_POST['travel_update_code_hifi'])) {
			$travel_update_codes['hifi'] = true;
		}
		if (isset($_POST['travel_update_code_relaxing'])) {
			$travel_update_codes['relaxing'] = true;
		}
		if (isset($_POST['travel_update_code_heavy'])) {
			$travel_update_codes['heavy'] = true;
		}
		if (isset($_POST['travel_update_code_vibe'])) {
			$travel_update_codes['vibe'] = true;
		}
		if (isset($_POST['travel_update_code_complex'])) {
			$travel_update_codes['complex'] = true;
		}
		if (isset($_POST['travel_update_code_instrumental'])) {
			$travel_update_codes['instrumental'] = true;
		}
		if (isset($_POST['travel_update_code_live'])) {
			$travel_update_codes['live'] = true;
		}
		if (isset($_POST['travel_update_code_lists'])) {
			$travel_update_codes['lists'] = true;
		}
		if (isset($_POST['travel_update_code_bookmark'])) {
			$travel_update_codes['bookmark'] = true;
		}
		if (isset($_POST['travel_update_code_thumbsup'])) {
			$travel_update_codes['thumbsup'] = true;
		}
		if (isset($_POST['travel_update_code_thumbsdown'])) {
			$travel_update_codes['thumbsdown'] = true;
		}
		if (isset($_POST['travel_update_code_thumbtack'])) {
			$travel_update_codes['thumbtack'] = true;
		}
		if (isset($_POST['travel_update_code_pointer'])) {
			$travel_update_codes['pointer'] = true;
		}
		$query = prepare_query("SELECT datexp, firstdatexp FROM travelogue WHERE id = {$_POST['update_this_entry']} AND user_id = {$_SESSION['user_id']}");
		$data = $conn->query($query);
		$result = false;
		if ($data->num_rows > 0) {
			while ($date = $data->fetch_assoc()) {
				if ($date['datexp'] == false) {
					$firstdatexp = $_POST['update_travel_new_datexp'];
					$result = array($_POST['update_travel_new_datexp']);
					$result = serialize($result);
					break;
				} else {
					$array_date = unserialize($date['datexp']);
					if ($array_date == false) {
						$firstdatexp = $_POST['update_travel_new_datexp'];
						$result = "{$date['datexp']}, {$_POST['update_travel_new_datexp']}";
						$result = array($result);
						$result = serialize($result);
					} else {
						$firstdatexp = $date['firstdatexp'];
						array_push($array_date, $_POST['update_travel_new_datexp']);
						$result = serialize($array_date);
					}
				}
			}
		}
		$_POST['update_travel_new_datexp'] = mysqli_real_escape_string($conn, $result);
		$travel_update_codes = serialize($travel_update_codes);
		$travel_update_codes = mysqli_real_escape_string($conn, $travel_update_codes);
		$query = prepare_query("UPDATE travelogue SET type = '{$_POST['update_travel_new_type']}', codes = '$travel_update_codes', releasedate = '{$_POST['update_travel_new_release_date']}', title = '{$_POST['update_travel_new_title']}', creator = '{$_POST['update_travel_new_creator']}', genre = '{$_POST['update_travel_new_genre']}', datexp = '{$_POST['update_travel_new_datexp']}', firstdatexp = '$firstdatexp', yourrating = '{$_POST['update_travel_new_rating']}', comments = '{$_POST['update_travel_new_comments']}', otherrelevant = '{$_POST['update_travel_new_information']}', dburl = '{$_POST['update_travel_new_database']}' WHERE id = {$_POST['update_this_entry']} AND user_id = {$_SESSION['user_id']}", 'log');
		$conn->query($query);
	}

	if (isset($_POST['load_filter_modal'])) {
		$query = prepare_query("SELECT DISTINCT type FROM travelogue WHERE user_id = {$_SESSION['user_id']}");
		$types = $conn->query($query);
		$_SESSION['travel_user_types'] = array();
		if ($types->num_rows > 0) {
			while ($type = $types->fetch_assoc()) {
				array_push($_SESSION['travel_user_types'], $type['type']);
			}
		} else {
			echo "No entries found.";
			exit();
		}

		$travel_options = false;
		foreach ($_SESSION['travel_user_types'] as $travel_user_key) {
			$travel_color = nexus_colors(array('mode' => 'convert', 'color' => $_SESSION['travelogue_types'][$travel_user_key]['color']));
			$travel_options .= "
				<div class='form-check'>
					<input name='travelogue_filter_$travel_user_key' id='travelogue_filter_$travel_user_key' class='form-check-input type_filter_option' type='checkbox' value='$travel_user_key' checked>
					<label for='travelogue_filter_$travel_user_key' class='form-check-label'><i class='fa-solid bg-dark p-2 rounded {$_SESSION['travelogue_types'][$travel_user_key]['icon']} fa-fw me-2 {$travel_color['text-color']}'></i>{$_SESSION['travelogue_types'][$travel_user_key]['description']}</label>
				</div>
			";
		}

		$codes_options = false;
		foreach ($_SESSION['travelogue_codes'] as $key => $array) {
			$color = nexus_colors(array('mode' => 'convert', 'color' => $_SESSION['travelogue_codes'][$key]['color']));
			$codes_options .= "
				<div class='form-check'>
					<input name='travelogue_filter_code_$key' id='travelogue_filter_code_$key' class='form-check-input code_filter_option' type='checkbox' value='code_$key' checked>
					<label for='travelogue_filter_code_$key' class='form-check-label'><i class='{$_SESSION['travelogue_codes'][$key]['icon']} fa-fw me-1 {$color['link-color']} bg-dark p-1 rounded'></i> {$_SESSION['travelogue_codes'][$key]['description']}</label>
				</div>
			";
		}

		$genre_options = false;
		$query = "SELECT DISTINCT genre FROM travelogue WHERE user_id = {$_SESSION['user_id']} ORDER BY genre";
		$user_genres = $conn->query($query);
		$genre_count = $user_genres->num_rows;
		if ($genre_count > 0) {
			while ($user_genre = $user_genres->fetch_assoc()) {
				if ($user_genre['genre'] == false) {
					continue;
				}
				$genre_options .= "<option value='{$user_genre['genre']}'>{$user_genre['genre']}</option>";
			}
		}

		$author_options = false;
		$query = "SELECT DISTINCT creator FROM travelogue WHERE user_id = {$_SESSION['user_id']} ORDER BY creator";
		$user_authors = $conn->query($query);
		$author_count = $user_authors->num_rows;
		if ($author_count > 0) {
			while ($user_author = $user_authors->fetch_assoc()) {
				if ($user_author['creator'] == false) {
					continue;
				}
				$author_options .= "<option value='{$user_author['creator']}'>{$user_author['creator']}</option>";
			}
		}

		$genre_size = $genre_count / 3;
		if ($genre_size < 3) {
			$genre_size = 3;
		}
		if ($genre_size > 20) {
			$genre_size = 20;
		}

		$author_size = $author_count / 3;
		if ($author_size < 3) {
			$author_size = 3;
		}
		if ($author_size > 20) {
			$author_size = 20;
		}

//		$genre_options .= "<input type='hidden' value='$count' name='filter_code_count'>";

		$result = "
			<h3>Order</h3>
	        <div class='mb-3'>
	            <label class='form-label' for='travelogue_sorting'>How to order entries:</label>
	            <select class='form-select' id='travelogue_sorting' name='travelogue_sorting'>
	                <option value='dateadded'>Chronological by date added</option>
	                <option value='chronological'>Chronological by release date</option>
	                <option value='chronological_recent_first'>Chronological by release date, most recent first</option>
	                <option value='biographical'>Chronological by date experienced</option>
	                <option value='alphabetical_creator'>Alphabetical by author name</option>
	                <option value='alphabetical_title'>Alphabetical by title</option>
	                <option value='alphabetical_genre'>Alphabetical by genre</option>
	                <option value='alphabetical_type'>Alphabetical by type</option>
	                <option value='rating'>By your rating</option>
                </select>
            </div>
            <div class='form-check'>
                <input name='travelogue_separate_types' id='travelogue_separate_types' class='form-check-input' type='checkbox'>
                <label for='travelogue_separate_types' class='form-check-label'>Separate entries by type</label>    
            </div>
            <hr>
            <h3>Filter types</h3>
            <p>Show only the following entry types:</p>
			$travel_options
			<button class='btn btn-secondary btn-sm mb-2' id='unselect_types' type='button'>Unselect all</button>
			<button class='btn btn-secondary btn-sm mb-2' id='select_types' type='button'>Select all all</button>
			<hr>
			<h3>Filter codes</h3>
			<p>Show only entries with the following codes:</p>
			<div class='form-check'>
				<input name='travelogue_show_no_code' id='travelogue_show_no_code' class='form-check-input code_filter_option' type='checkbox' value='show_no_code' checked>
				<label for='travelogue_show_no_code' class='form-check-label'><i class='fa-solid fa-empty-set fa-fw me-1 link-light bg-dark p-1 rounded'></i> Show items with no codes</label>
			</div>
			$codes_options
			<button class='btn btn-secondary btn-sm mb-2' id='unselect_codes' type='button'>Unselect all</button>
			<button class='btn btn-secondary btn-sm mb-2' id='select_codes' type='button'>Select all</button>
			<hr>
			<h3>Filter genres</h3>
			<p class='d-none' id='paragraph_explain_filter_genres'>The genres you select below will be shown once the filter is applied. All others will be hidden.</p>
			<select class='form-select mb-2 d-none' size='$genre_size' id='travelogue_filter_genres' name='travelogue_filter_genres[]' multiple disabled>
				$genre_options
			</select>
			<button class='btn btn-secondary btn-sm mb-2' id='enable_genre_filter' type='button'>Enable genre filter</button>
			<hr>
			<h3>Filter authors</h3>
			<p class='d-none' id='paragraph_explain_filter_authors'>The authors you select below will be shows once the filter is applied. All others will be hidden.</p>
			<select class='form-select mb-2 d-none' size='$author_size' id='travelogue_filter_authors' name='travelogue_filter_authors[]' multiple disabled>
				$author_options
			</select>
			<button class='btn btn-secondary btn-sm mb-2' id='enable_author_filter' type='button'>Enable author filter</button>
			<hr>
			<button class='btn btn-primary' name='trigger_modal_filter' id='trigger_modal_filter'>Submit</button>
			<button class='btn btn-secondary' name='trigger_reset_filter' id='trigger_reset_filter'>Reset all filters</button>
		";
		echo "<form method='post'>$result</form>";
		exit();
	}

	if (isset($_POST['populate_password_manager'])) {
		$result = false;
		$result = "
			<p>This is far from a super safe system, but it's a fact that your password is only stored encrypted, and your passcode, which is not stored in any way, is needed to decrypt it. At any rate, do not store passwords for anything too important, especially crypto wallets. Use a proper password manager instead, like LastPass or OnePassword.</p>
			<h3>Add password</h3>
			<form method='post'>
			<div class='mb-3'>
				<label for='manager_new_password' class='form-label'>New password:</label>
				<input type='password' id='manager_new_password' name='manager_new_password' class='form-control'>
			</div>
			<div class='mb-3'>
				<label for='manager_new_passcode' class='form-label'>Encryption passcode:</label>
				<input type='password' id='manager_new_passcode' name='manager_new_passcode' class='form-control'>
			</div>
			<button class='btn btn-primary'>Submit</button>
			</form>
			<hr>
			<div class='mb-3'>
				<h3>Decrypt password</h3>
				<label class='form-label' for='manager_decrypt_password'>Select password to decrypt:</label>
				<select class='form-select' id='manager_decrypt_password' name='manager_decrypt_password'>
					<option>cPanel</option>
				</select>
			</div>
			<button class='btn btn-primary'>Decrypt</button>
		";
		echo $result;
		exit();
	}

	if (isset($_POST['populate_manage_commands'])) {
		echo "
		    <ul class='list-group mb-3'>
		        <li class='list-group-item active'><h5>Commands:</h5></li>
		        <li class='list-group-item'>\"/r/\" will send you to a subreddit. For example, just type \"/r/prequelmemes\".</li>
		        <li class='list-group-item'>\"/ld url\" to add a link to the Link Dump with random icon and color.</li>
		        <li class='list-group-item'>\"/log message\" will add a message to your log.</li>
		        <li class='list-group-item'>Type an url starting with \"http\" or \"www\" to go directly to that address.</li>
		        <li class='list-group-item'>\"/del link title\" to delete a link.</li>
		        <li class='list-group-item'>\"/dl address\" to download an image.</li>
		        <li class='list-group-item'>\"/go search terms\" will perform a Google seach.</li>
		        <li class='list-group-item'>\"/gi search terms\" will perform a Google image seach.</li>
		        <li class='list-group-item'>\"/yt search terms\" will perform a YouTube seach.</li>
		        <li class='list-group-item'>\"/yd search terms\" will perform a Yandex seach.</li>
		        <li class='list-group-item'>\"/rd search terms\" will perform a Reddit seach.</li>
		        <li class='list-group-item'>\"/tw search terms\" will perform a Twitter seach.</li>
            </ul>
		    <ul class='list-group'>
		        <li class='list-group-item active'><h5>Hotkeys:</h5></li>
		        <li class='list-group-item'>Alt+c will return to the original screen, with focus on the command bar. Clicking the title text does the same.</li>
		        <li class='list-group-item'>Alt+1 to 9 will show the links from each of the first nine main folders, in order.</li>
		        <li class='list-group-item'>Alt+a will show the archived links.</li>
		        <li class='list-group-item'>Alt+s will show the settings.</li>
		        <li class='list-group-item'>Alt+t will show the tools.</li>
		        <li class='list-group-item'>Alt+r will show recent links.</li>
            </ul>
		";
	}

	if (isset($_POST['travelogue_populate_add_item'])) {

		$all_genres_list = false;
		$query = prepare_query("SELECT DISTINCT genre FROM travelogue WHERE user_id = {$_SESSION['user_id']} ORDER BY genre");
		$list_genres = $conn->query($query);
		if ($list_genres->num_rows > 0) {
			while ($list_genre = $list_genres->fetch_assoc()) {
				$all_genres_list .= "<option>{$list_genre['genre']}</option>";
			}
		}

		$all_authors_list = false;
		$query = prepare_query("SELECT DISTINCT creator FROM travelogue WHERE user_id = {$_SESSION['user_id']} ORDER BY creator");
		$list_authors = $conn->query($query);
		if ($list_authors->num_rows > 0) {
			while ($list_author = $list_authors->fetch_assoc()) {
				$all_authors_list .= "<option>{$list_author['creator']}</option>";
			}
		}

		$template_modal_body_conteudo = false;
		$options_types = false;
		foreach ($_SESSION['travelogue_types'] as $key => $array) {
			$options_types .= "<option value='$key'>{$_SESSION['travelogue_types'][$key]['description']}</option>";
		}
		$template_modal_body_conteudo .= "
			<form method='post'>
				<div class='mb-3'>
					<label for='travel_new_type' class='form-label'>Type:</label>
					<select id='travel_new_type' name='travel_new_type' type='text' class='form-control' required>
						<option value='' disabled selected>Select a type:</option>
						$options_types
					</select>
				</div>
				<div class='mb-3'>
					<label for='travel_new_creator' class='form-label'>Author:</label>
					<input id='travel_new_creator' name='travel_new_creator' type='text' class='form-control' list='new_item_authors_list'>
					<datalist id='new_item_authors_list'>$all_authors_list</datalist>
				</div>
				<div class='mb-3'>
					<label for='travel_new_title' class='form-label'>Title:</label>
					<input id='travel_new_title' name='travel_new_title' type='text' class='form-control' required>
				</div>
				<div class='mb-3'>
					<label for='travel_new_genre' class='form-label'>Genre:</label>
					<input id='travel_new_genre' name='travel_new_genre' type='text' class='form-control' list='new_item_genres_list'>
					<datalist id='new_item_genres_list'>$all_genres_list</datalist>
				</div>
				<div class='mb-3'>
					<label for='travel_new_release_date' class='form-label'>Release date: <em class='text-muted'>(recommended: YYYY, YYYYMM, or YYYYMMDD)</em></label>
					<input id='travel_new_release_date' name='travel_new_release_date' type='number' minlength='4' maxlength='8' class='form-control'>
				</div>
				<div class='mb-3'>
					<label for='travel_new_datexp' class='form-label'>Date experienced: <em class='text-muted'>(recommended: YYYY, YYYYMM, or YYYYMMDD)</em></label>
					<input id='travel_new_datexp' name='travel_new_datexp' type='number' minlength='4' maxlength='8' class='form-control'>
				</div>
				<div class='mb-3 p-1 rounded border'>
					<label for='travel_new_rating' class='form-label'>Your rating (1 to 5):</label>
					<input type='range' class='form-range' id='travel_new_rating' name='travel_new_rating' min='1' max='5' disabled>
					<button id='trigger_enable_rating' class='btn btn-outline-secondary btn-sm' type='button'>Enable</button>
				</div>
				<div class='mb-3'>
					<label for='travel_new_comments' class='form-label'>Your comments:</label>
					<textarea id='travel_new_comments' name='travel_new_comments' type='textarea' class='form-control' rows='3'></textarea>
				</div>
				<div class='mb-3'>
					<label for='travel_new_information' class='form-label'>Relevant information:</label>
					<textarea id='travel_new_information' name='travel_new_information' type='text' class='form-control' rows='3'></textarea>
				</div>
				<div class='mb-3'>
					<label for='travel_new_database' class='form-label'>Database link (Wikipedia, IMDb, AllMusic etc.):</label>
					<input id='travel_new_database' name='travel_new_database' type='url' class='form-control'>
				</div>";
		$template_modal_body_conteudo .= "<div class='mb-3'>
							<h3>Codes</h3>
						";
		foreach ($_SESSION['travelogue_codes'] as $key => $info) {
			$color = nexus_colors(array('mode' => 'convert', 'color' => $_SESSION['travelogue_codes'][$key]['color']));
			$template_modal_body_conteudo .= "
							<div class='form-check'>
								<input name='travel_new_code_$key' id='travel_new_code_$key' class='form-check-input' type='checkbox' value='$key'>
								<label for='travel_new_code_$key' class='form-check-label'><i class='{$_SESSION['travelogue_codes'][$key]['icon']} me-2 {$color['link-color']} bg-dark p-1 rounded fa-fw'></i>{$_SESSION['travelogue_codes'][$key]['description']}</label>
							</div>
						";
		}
		$template_modal_body_conteudo .= "</div><hr>";
		$template_modal_body_conteudo .= "
				<button type='submit' class='btn btn-primary'>Submit</button>
			</form>
		";
		echo $template_modal_body_conteudo;
		exit();
	}

	if (isset($_POST['edit_this_author'])) {
		$result = false;
		$result .= "<p><button id='exclusive_author_filter' class='btn btn-secondary btn-sm' value='{$_POST['edit_this_author']}'>Click here</button> to set the travelogue filter to only show works by this author.</p>";
		$_POST['edit_this_author'] = intval($_POST['edit_this_author']);
		$query = prepare_query("SELECT creator FROM travelogue WHERE id = {$_POST['edit_this_author']}");
		$creators = $conn->query($query);
		if ($creators->num_rows > 0) {
			while ($creator = $creators->fetch_assoc()) {
				$creator_string = $creator['creator'];
			}
		}
		$query = prepare_query("SELECT * FROM travelogue WHERE creator = '$creator_string' AND user_id = {$_SESSION['user_id']} ORDER BY releasedate");
		$works = $conn->query($query);
		if ($works->num_rows > 0) {
			$result .= "<p id='hide_when_show_list_author'><button id='show_list_author' class='btn btn-secondary btn-sm' type='button'>Click here</button> to show all registered works by this author:</p>";
			$result .= "<ul class='list-group mb-1 d-none author_works_list'><li class='list-group-item active'>Registered works by this author (chronological order):</li></ul>";
			$result .= "<ol class='list-group list-group-numbered d-none author_works_list'>";
			while ($work = $works->fetch_assoc()) {
				if (!isset($creator_id)) {
					$creator_id = $work['id'];
				}
				$comments_module = false;
				if ($work['comments'] != false) {
					$comments_module = "<br><small class='text-muted bg-light py-1 px-2 rounded border border-secondary'>{$work['comments']}</small>";
				}
				$result .= "<li class='list-group-item'><span class='bg-secondary text-light rounded small px-2 mx-2'>{$work['releasedate']}</span> {$work['title']} $comments_module</li>";
			}
			$result .= "</ol>";
		}
		$query = prepare_query("SELECT * FROM travelogue_authors WHERE creator_id = $creator_id");
		$creators = $conn->query($query);
		if ($creators->num_rows > 0) {
			while ($creator = $creators->fetch_assoc()) {
				$creator_user_comments = $creator['comments'];
				$creator_database = $creator['dburl'];
			}
		} else {
			$query = prepare_query("INSERT INTO travelogue_authors (creator_id, user_id, title) VALUES ($creator_id, {$_SESSION['user_id']}, '$creator_string')");
			$conn->query($query);
			$creator_user_comments = false;
			$creator_database = false;
		}
		$result .= "
			<hr>
			<h3>Editable author data</h3>
			<form method='post'>
			<div class='mb-3'>
				<label for='update_author_comments' class='form-label'>Your comments on this author:</label>
				<textarea id='update_author_comments' name='update_author_comments' type='textarea' class='form-control' rows='8'>$creator_user_comments</textarea>
			</div>
		";
		$result .= "
			<div class='mb-3'>
				<label for='update_author_database' class='form-label'>Database link (Wikipedia, IMDb, AllMusic etc.):</label>
				<input id='update_author_database' name='update_author_database' type='url' class='form-control' value=\"$creator_database\">
			</div>
			<hr>
			<button class='btn btn-primary' name='update_author_data' value='{$creator_id}'>Submit</button>
			</form>
		";
		echo $result;
	}

	if (isset($_POST['update_author_data'])) {
		$_POST['update_author_data'] = intval($_POST['update_author_data']);
		$_POST['update_author_database'] = mysqli_real_escape_string($conn, $_POST['update_author_database']);
		$_POST['update_author_comments'] = mysqli_real_escape_string($conn, $_POST['update_author_comments']);
		$query = prepare_query("UPDATE travelogue_authors SET comments = '{$_POST['update_author_comments']}', dburl = '{$_POST['update_author_database']}' WHERE creator_id = {$_POST['update_author_data']} AND user_id = {$_SESSION['user_id']}");
		$conn->query($query);
	}

	if (isset($_POST['exclusive_author_filter'])) {
		$_POST['exclusive_author_filter'] = intval($_POST['exclusive_author_filter']);
		$query = prepare_query("SELECT creator FROM travelogue WHERE id = {$_POST['exclusive_author_filter']}");
		$creators = $conn->query($query);
		if ($creators->num_rows > 0) {
			while ($creator = $creators->fetch_assoc()) {
				$author_title = $creator['creator'];
			}
		}
		if ($author_title !== false) {
			unset($_SESSION['travelogue_filter_options']);
			$_SESSION['travelogue_filter_options'] = array('sorting' => 'chronological', 'authors' => array($author_title));
			echo true;
		} else {
			echo false;
		}
	}

	if (isset($_POST['delete_datexp_from_this_entry'])) {
		$query = prepare_query("UPDATE travelogue SET datexp = NULL, firstdatexp = NULL where id = {$_POST['delete_datexp_from_this_entry']} AND user_id = {$_SESSION['user_id']}");
		$check = $conn->query($query);
		echo $check;
		exit();
	}

	if (isset($_POST['travel_load_storage'])) {
		$remaining = count($_SESSION['travelogue_storage']);
		if ($remaining == 0) {
			echo false;
			exit();
		}
		if (isset($_SESSION['travelogue_storage'])) {
			$result = false;
			if ($_POST['travel_load_storage'] == 'all') {
				foreach ($_SESSION['travelogue_storage'] as $key => $array) {
					$result .= $_SESSION['travelogue_storage'][$key];
					unset($_SESSION['travelogue_storage'][$key]);
				}
			} else {
				$travel_limit = $_POST['travel_load_storage'];
				$count = 0;
				foreach ($_SESSION['travelogue_storage'] as $key => $array) {
					if ($count >= $travel_limit) {
						break;
					} else {
						$result .= $_SESSION['travelogue_storage'][$key];
						unset($_SESSION['travelogue_storage'][$key]);
					}
					$count++;
				}
			}
			$remaining = count($_SESSION['travelogue_storage']);
			if ($remaining > 0) {
				$result .= generate_storage_module($remaining);
			}
			echo $result;
		} else {
			echo false;
		}
		exit();
	}

	//	$dsn = 'mysql:host=localhost;dbname=mydatabase';
	//	$username = 'myusername';
	//	$password = 'mypassword';
	//
	//	try {
	//		$pdo = new PDO($dsn, $username, $password);
	//		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//	} catch (PDOException $e) {
	//		echo 'Connection failed: ' . $e->getMessage();
	//		exit;
	//	}
	//
	//	$stmt = $pdo->prepare('SELECT * FROM users WHERE ethereum_address = :address');
	//	$stmt->execute(array('address' => $address));
	//	$user = $stmt->fetch();
	//
	//	if ($user) {
	//		// the user is authenticated
	//	} else {
	//		// the user is not authenticated
	//	}

?>
