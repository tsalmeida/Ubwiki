<?php

	//TODO: Podcast episodes should be shown in reverse order.
	//TODO: Permitir que a ordem das matérias seja diferente da ordem de criação.
	//TODO: Permitir que a ordem dos tópicos seja diferente da ordem de criação.
	//TODO: Permitir que a ordem de seções seja alterada.
	//TODO: Fontes diferentes e cores diferentes apenas para leitura.
	//TODO: Gerar PDF para impressão.
	//TODO: Permitir baixar um arquivo com todas as suas anotações.
	//TODO: Estado da página não está sendo atualizado imediatamente, apenas após recarregar.
	//TODO: Sistema de simulados automatizado, em que os alunos votam nas melhores respostas.
    //TODO: If the user has written anything, both columns should be visible on pageload.
    //TODO: Verbetes relacionados should only load when the user clicks on it (and only once).
    //TODO: Author page. Shouldn't be hard.

	$pagina_tipo = 'pagina_geral';

	include 'engine.php';

	$modal_novo_curso = false;
	$nao_contar = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_topico = false;
	$carregar_adicionar_subtopico = false;
	$carregar_questoes_topico = false;
	$carregar_modal_wikipedia = false;
	$carregar_quill_anotacoes = false;
	$carregar_modal_destruir_pagina = false;
	$carregar_toggle_acervo = false;
	$carregar_modal_correcao = false;
	$carregar_carrinho = false;
	$carregar_partes_elemento_modal = false;
	$carregar_convite = false;
	$item_no_acervo = false;
	if (!isset($_GET['pagina_id'])) {
		if (isset($_GET['topico_id'])) {
			$topico_id = (int)$_GET['topico_id'];
			$pagina_id = return_pagina_id($topico_id, 'topico');
		} elseif (isset($_GET['elemento_id'])) {
			$elemento_id = (int)$_GET['elemento_id'];
			$pagina_id = return_pagina_id($elemento_id, 'elemento');
		} elseif (isset($_GET['curso_id'])) {
			$pagina_curso_id = (int)$_GET['curso_id'];
			$pagina_id = return_pagina_id($pagina_curso_id, 'curso');
		} elseif (isset($_GET['materia_id'])) {
			$materia_id = (int)$_GET['materia_id'];
			$pagina_id = return_pagina_id($materia_id, 'materia');
		} elseif (isset($_GET['texto_id'])) {
			$texto_anotacao = false;
			$pagina_texto_id = (int)$_GET['texto_id'];
			if ($pagina_texto_id == 'new') {
				$query = prepare_query("INSERT INTO Textos (tipo, compartilhamento, page_id, user_id, verbete_html, verbete_text, verbete_content) VALUES ('anotacoes', 'privado', 0, $user_id, FALSE, FALSE, FALSE)");
				$conn->query($query);
				$pagina_texto_id = $conn->insert_id;
				header("Location:pagina.php?texto_id=$pagina_texto_id");
				exit();
			}
			$pagina_id = return_pagina_id($pagina_texto_id, 'texto');
			$pagina_tipo = 'texto';
		} elseif (isset($_GET['grupo_id'])) {
			$grupo_id = (int)$_GET['grupo_id'];
			$pagina_id = return_pagina_id($grupo_id, 'grupo');
		} elseif (isset($_GET['user_id'])) {
			$escritorio_user_id = (int)$_GET['user_id'];
			$escritorio_user_apelido = return_apelido_user_id($escritorio_user_id);
			if ($escritorio_user_apelido == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
			$escritorio_id = return_lounge_id($escritorio_user_id);
			$pagina_id = $escritorio_id;
			header("Location:pagina.php?pagina_id=$escritorio_id");
			exit();
		} elseif (isset($_GET['original_id'])) {
			$original_id = (int)$_GET['original_id'];
			if (isset($_GET['resposta_id'])) {
				if (isset($_GET['resposta_id'])) {
					$resposta_id = (int)$_GET['resposta_id'];
					if ($resposta_id == 'new') {
						$query = prepare_query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($original_id, 'resposta', 'igual à página original', $user_id)");
						$conn->query($query);
						$nova_resposta_id = $conn->insert_id;
						$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($original_id, 'texto', $nova_resposta_id, 'resposta', $user_id)");
						$conn->query($query);
						header("Location:pagina.php?pagina_id=$nova_resposta_id");
						exit();
					}
				}
			}
		} elseif (isset($_GET['etiqueta_id'])) {
			$pagina_etiqueta_id = $_GET['etiqueta_id'];
			$pagina_id = return_pagina_id($pagina_etiqueta_id, 'etiqueta');
		} elseif (isset($_GET['questao_id'])) {
			$pagina_questao_id = $_GET['questao_id'];
			$pagina_id = return_pagina_id($pagina_questao_id, 'questao');
		} elseif (isset($_GET['texto_apoio_id'])) {
			$pagina_texto_apoio_id = $_GET['texto_apoio_id'];
			$pagina_id = return_pagina_id($pagina_texto_apoio_id, 'texto_apoio');
		} elseif (isset($_GET['plano_id'])) {
			$pagina_plano_id = $_GET['plano_id'];
			if ($pagina_plano_id == 'new') {
				$query = prepare_query("INSERT INTO Planos (user_id) VALUES ($user_id)");
				$conn->query($query);
				$pagina_plano_id = $conn->insert_id;
				$query = prepare_query("INSERT INTO Paginas (item_id, tipo, subtipo, compartilhamento, user_id) VALUES ($pagina_plano_id, 'pagina', 'plano', 'privado', $user_id)");
				$conn->query($query);
				$pagina_id = $conn->insert_id;
				$query = prepare_query("UPDATE Planos SET pagina_id = $pagina_id WHERE id = $pagina_plano_id");
				$conn->query($query);
				header("Location:pagina.php?pagina_id=$pagina_id");
				exit();
			} elseif ($pagina_plano_id == 'bp') {
			    $pagina_com_verbete = false;
				$pagina_tipo_override = 'pagina';
				$pagina_subtipo_override = 'plano';
				$pagina_titulo_override = $pagina_translated['your collection'];
				if (!isset($_SESSION['user_plano_id'])) {
					$pagina_item_id_override = return_plano_id_pagina_id($user_escritorio);
					if ($pagina_item_id_override == false) {
						$query = prepare_query("INSERT INTO Planos (pagina_id, user_id) VALUES ($user_escritorio, $user_id)");
						$conn->query($query);
						$pagina_item_id_override = $conn->insert_id;
					}
					$_SESSION['user_plano_id'] = $pagina_item_id_override;
				} else {
					$pagina_item_id_override = $_SESSION['user_plano_id'];
				}
				$pagina_id = $user_escritorio;
			} else {
				$pagina_id = return_pagina_id($pagina_plano_id, 'plano');
			}
		} else {
			header('Location:pagina.php?pagina_id=3');
			exit();
		}
	} else {
		$pagina_id = $_GET['pagina_id'];
		if ($pagina_id == 'new') {
			$query = prepare_query("INSERT INTO Paginas (tipo, compartilhamento, user_id) VALUES ('pagina', 'privado', $user_id)");
			$conn->query($query);
			$pagina_id = $conn->insert_id;
			header("Location:pagina.php?pagina_id=$pagina_id");
			exit();
		}
	}
	if ($pagina_id == 1) {
		header('Location:ubwiki.php');
		exit();
	}

	$texto_revisao_ativa = check_review_state($pagina_id);

	$pagina_info = return_pagina_info($pagina_id);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = (int)$pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = (int)$pagina_info[5];
		$pagina_titulo = $pagina_info[6];
		$pagina_etiqueta_id = (int)$pagina_info[7];
		$pagina_subtipo = $pagina_info[8];
		$pagina_publicacao = $pagina_info[9];
		$pagina_colaboracao = $pagina_info[10];
		$pagina_link = $pagina_info[11];
	} else {
		header('Location:pagina.php?pagina_id=3');
		exit();
	}

	if (isset($pagina_tipo_override)) {
		$pagina_tipo = $pagina_tipo_override;
	}
	if (isset($pagina_subtipo_override)) {
		$pagina_subtipo = $pagina_subtipo_override;
	}
	if (isset($pagina_titulo_override)) {
		$pagina_titulo = $pagina_titulo_override;
	}
	if (isset($pagina_item_id_override)) {
		$pagina_item_id = $pagina_item_id_override;
	}
	if (isset($_POST['trigger_apagar_pagina'])) {
		$query = prepare_query("DELETE FROM Paginas WHERE id = $pagina_id");
		$conn->query($query);
		header('Location:pagina.php?pagina_id=3');
		exit();
	}

	if (isset($_GET['wiki_id'])) {
		$wiki_id = (int)$_GET['wiki_id'];
	} else {
		$wiki_id = false;
	}

	$privilegio_edicao = return_privilegio_edicao($pagina_id, $user_id);
	if (($pagina_subtipo == 'modelo') && ($pagina_compartilhamento == false)) {
		$privilegio_edicao = false;
	}

	$pagina_curso_user_id = false;
	if ($pagina_subtipo == 'Plano de estudos') {
		$pagina_materia_familia = return_familia($pagina_item_id);
		$pagina_curso_pagina_id = $pagina_materia_familia[1];
		$pagina_curso_pagina_info = return_pagina_info($pagina_curso_pagina_id);
		$pagina_compartilhamento = $pagina_curso_pagina_info[4];
		$pagina_curso_compartilhamento = $pagina_curso_pagina_info[4];
		$pagina_curso_user_id = (int)$pagina_curso_pagina_info[5];
	}

	if ($pagina_tipo == 'topico') {
		$familia_info = return_familia($pagina_id);
		$topico_nivel = $familia_info[0];
		$topico_curso_pagina_id = (int)$familia_info[1];
		$topico_curso_pagina_info = return_pagina_info($topico_curso_pagina_id);
		$topico_curso_titulo = $topico_curso_pagina_info[6];
		$pagina_curso_id = $topico_curso_pagina_info[1];
		$pagina_curso_pagina_id = (int)$topico_curso_pagina_id;
		$pagina_curso_user_id = (int)$topico_curso_pagina_info[5];
		$pagina_curso_compartilhamento = $topico_curso_pagina_info[4];
		$topico_materia_pagina_id = (int)$familia_info[2];
		$topico_materia_titulo = return_pagina_titulo($topico_materia_pagina_id);
	} elseif ($pagina_tipo == 'questao') {
		$pagina_questao_info = return_questao_info($pagina_item_id);
		$pagina_questao_origem = $pagina_questao_info[0];
		$pagina_questao_curso_id = $pagina_questao_info[1];
		$pagina_questao_edicao_ano = $pagina_questao_info[2];
		$pagina_questao_etapa_id = $pagina_questao_info[3];
		$pagina_questao_etapa_info = return_etapa_edicao_ano_e_titulo($pagina_questao_etapa_id);
		$pagina_questao_etapa_ano = $pagina_questao_etapa_info[0];
		$pagina_questao_etapa_titulo = $pagina_questao_etapa_info[1];
		$pagina_questao_texto_apoio = $pagina_questao_info[4];
		$pagina_questao_texto_apoio_id = $pagina_questao_info[5];
		if ($pagina_questao_texto_apoio_id != false) {
			$pagina_questao_texto_apoio_info = return_texto_apoio_info($pagina_questao_texto_apoio_id);
			$pagina_questao_texto_apoio_pagina_id = $pagina_questao_texto_apoio_info[1];
			$pagina_questao_texto_apoio_enunciado_html = $pagina_questao_texto_apoio_info[6];
			$pagina_questao_texto_apoio_html = $pagina_questao_texto_apoio_info[9];
			$pagina_questao_texto_apoio_titulo = $pagina_questao_texto_apoio_info[5];
		} else {
			$pagina_questao_texto_apoio_pagina_id = false;
			$pagina_questao_texto_apoio_html = false;
			$pagina_questao_texto_apoio_titulo = false;
		}
		$pagina_questao_prova_id = $pagina_questao_info[6];
		$pagina_questao_prova_info = return_info_prova_id($pagina_questao_prova_id);
		$pagina_questao_prova_titulo = $pagina_questao_prova_info[0];
		$pagina_questao_numero = $pagina_questao_info[7];
		$pagina_questao_materia = $pagina_questao_info[8];
		$pagina_questao_tipo = $pagina_questao_info[9];
		$pagina_questao_enunciado_html = $pagina_questao_info[10];
		$pagina_questao_enunciado_content = $pagina_questao_info[12];
		$pagina_questao_item1_html = $pagina_questao_info[13];
		$pagina_questao_item2_html = $pagina_questao_info[14];
		$pagina_questao_item3_html = $pagina_questao_info[15];
		$pagina_questao_item4_html = $pagina_questao_info[16];
		$pagina_questao_item5_html = $pagina_questao_info[17];
		$pagina_questao_item1_content = $pagina_questao_info[23];
		$pagina_questao_item2_content = $pagina_questao_info[24];
		$pagina_questao_item3_content = $pagina_questao_info[25];
		$pagina_questao_item4_content = $pagina_questao_info[26];
		$pagina_questao_item5_content = $pagina_questao_info[27];
		$pagina_questao_item1_gabarito = $pagina_questao_info[28];
		$pagina_questao_item2_gabarito = $pagina_questao_info[29];
		$pagina_questao_item3_gabarito = $pagina_questao_info[30];
		$pagina_questao_item4_gabarito = $pagina_questao_info[31];
		$pagina_questao_item5_gabarito = $pagina_questao_info[32];
	} elseif ($pagina_tipo == 'texto_apoio') {
		$pagina_texto_apoio_info = return_texto_apoio_info($pagina_item_id);
		$pagina_texto_apoio_origem = $pagina_texto_apoio_info[2];
		$pagina_texto_apoio_curso_id = $pagina_texto_apoio_info[3];
		$pagina_texto_apoio_curso_titulo = return_curso_titulo_id($pagina_texto_apoio_curso_id);
		$pagina_texto_apoio_prova_id = $pagina_texto_apoio_info[4];
		$pagina_texto_apoio_prova_info = return_info_prova_id($pagina_texto_apoio_prova_id);
		$pagina_texto_apoio_edicao_ano = $pagina_texto_apoio_prova_info[2];
		$pagina_texto_apoio_etapa_titulo = $pagina_texto_apoio_prova_info[6];
		$pagina_texto_apoio_prova_titulo = $pagina_texto_apoio_prova_info[0];
		$pagina_texto_apoio_titulo = $pagina_texto_apoio_info[5];
		$pagina_texto_apoio_enunciado_html = $pagina_texto_apoio_info[6];
		$pagina_texto_apoio_enunciado_content = $pagina_texto_apoio_info[8];
		$pagina_texto_apoio_html = $pagina_texto_apoio_info[9];
		$pagina_texto_apoio_content = $pagina_texto_apoio_info[11];
	} elseif (($pagina_tipo == 'materia') || ($pagina_tipo == 'curso')) {
		$familia_info = return_familia($pagina_id);
		$pagina_curso_pagina_id = (int)$familia_info[1];
		$pagina_curso_info = return_pagina_info($pagina_curso_pagina_id);
		$pagina_curso_id = (int)$pagina_curso_info[1];
		$pagina_curso_user_id = (int)$pagina_curso_info[5];
		$pagina_curso_compartilhamento = $pagina_curso_info[4];
		$pagina_curso_titulo = $pagina_curso_info[6];
	} elseif ($pagina_subtipo == 'produto') {
		$produto_preco = false;
		$produto_autor = false;
		$produto_info = return_produto_info($pagina_id);
		$produto_preco = $produto_info[2];
		if ($produto_preco == false) {
			$produto_preco = $pagina_translated['Indeterminado'];
		}
		$produto_autor = $produto_info[3];
		if ($produto_autor == false) {
			$produto_autor = $pagina_translated['Indeterminado'];
		}
	} elseif ($pagina_subtipo == 'etiqueta') {
		$pagina_etiqueta_id = $pagina_item_id;
	} elseif ($pagina_subtipo == 'modelo') {
		$modelo_do_usuario = return_modelo_estado($pagina_id, $user_id);
	} elseif ($pagina_subtipo == 'plano') {
		$plan_show_low = false;
		$plan_show_completed = false;
		$change_show_low = true;
		$change_show_completed = true;
		$color_show_completed = 'text-primary';
		$color_show_low = 'text-primary';
		if (isset($_GET['hl'])) {
			$plan_show_low = $_GET['hl'];
			if ($plan_show_low == true) {
				$color_show_low = 'text-danger';
			}
			$change_show_low = !$plan_show_low;
		}
		if (isset($_GET['sc'])) {
			$plan_show_completed = $_GET['sc'];
			if ($plan_show_completed == true) {
				$color_show_completed = 'text-success';
			}
			$change_show_completed = !$plan_show_completed;
		}
	} elseif ($pagina_subtipo == 'simulado') {
		$pagina_simulado_info = return_simulado_info($pagina_item_id);
		$pagina_simulado_contexto_pagina_id = $pagina_simulado_info[2];
		$pagina_simulado_curso_id = $pagina_simulado_info[1];
		$pagina_curso_id = $pagina_simulado_curso_id;
	}

	if ($pagina_tipo == 'curso') {
		$pagina_curso_user_id = $pagina_user_id;
		if ($_SESSION['raiz_ativa'] != $pagina_id) {
			$_SESSION['raiz_ativa'] = $pagina_id;
			if ($user_id != false) {
				$query = prepare_query("UPDATE Usuarios SET raiz_ativa = $pagina_id WHERE id = $user_id");
				$conn->query($query);
			}
		}
	}

	if (isset($_POST['novo_curso'])) {
		$novo_curso_sigla = $_POST['novo_curso_sigla'];
		$query = prepare_query("INSERT INTO Cursos (pagina_id, titulo, sigla, user_id) VALUES ($pagina_id, '$pagina_titulo', '$novo_curso_sigla', $user_id)");
		$conn->query($query);
		$novo_curso_id = $conn->insert_id;
		$query = prepare_query("UPDATE Paginas SET item_id = $novo_curso_id, tipo = 'curso' WHERE id = $pagina_id");
		$conn->query($query);
		header("Location:pagina.php?curso_id=$novo_curso_id");
		exit();
	}

	if ((($pagina_tipo == 'topico') || $pagina_tipo == 'materia') || ($pagina_subtipo == 'Plano de estudos')) {
		$pagina_compartilhamento = $pagina_curso_compartilhamento;
		$pagina_user_id = $pagina_curso_user_id;
	}

	$add_compartilhamento = false;
	if (isset($_GET['acs'])) {
		$link_de_acesso = $_GET['acs'];
		if ($pagina_link == $link_de_acesso) {
			$_SESSION['acesso_especial'] = $pagina_id;
			if ($user_id != false) {
				$add_compartilhamento = true;
			}
		}
	}


	if ($pagina_compartilhamento == 'privado') {
		$check_compartilhamento = false;
		$pagina_checar_compartilhamento = $pagina_id;
		if ($pagina_user_id == $user_id) {
			$check_compartilhamento = true;
		} else {
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia') || ($pagina_subtipo == 'Plano de estudos')) {
				$pagina_checar_compartilhamento = $pagina_curso_pagina_id;
			}
			if ($_SESSION['acesso_especial'] != false) {
				if ($user_id != false) {
					$check_compartilhamento = return_compartilhamento($pagina_checar_compartilhamento, $user_id);
					if (($check_compartilhamento == false) && ($add_compartilhamento == true)) {
						$query = prepare_query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('acesso', $pagina_user_id, $pagina_checar_compartilhamento, '$pagina_tipo', 'usuario', $user_id)");
						$check = $conn->query($query);
						if ($check == true) {
							if ($pagina_tipo == 'curso') {
								$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao, opcao_string) VALUES ($user_id, 'curso', $pagina_item_id, $pagina_id)");
								$conn->query($query);
							}
							$check_compartilhamento = return_compartilhamento($pagina_checar_compartilhamento, $user_id);
						}
					}
				}
			} else {
				$check_compartilhamento = return_compartilhamento($pagina_checar_compartilhamento, $user_id);
			}
		}
		if ($_SESSION['acesso_especial'] == $pagina_checar_compartilhamento) {
			$check_compartilhamento = true;
		}
		if ($pagina_id == $user_escritorio) {
		    $check_compartilhamento = true;
        }
		if ($check_compartilhamento == false) {
			header('Location:pagina.php?pagina_id=3');
			exit();
		}
	}

    $texto_page_id = false;
	if ($pagina_tipo == 'curso') {
		$pagina_curso_info = return_curso_info($pagina_curso_id);
		$pagina_curso_sigla = $pagina_curso_info[2];
		$pagina_curso_titulo = $pagina_curso_info[3];
	} elseif ($pagina_tipo == 'materia') {
		$materia_id = $pagina_item_id;
		$materia_curso_id = false;
		$materia_titulo = $pagina_titulo;
	} elseif ($pagina_tipo == 'topico') {
		include 'templates/verbetes_relacionados.php';
	} elseif ($pagina_tipo == 'elemento') {
		$elemento_id = $pagina_item_id;
	} elseif ($pagina_tipo == 'grupo') {
		$grupo_id = $pagina_item_id;
		$link_compartilhamento_info = return_link_compartilhamento($pagina_id);
		$link_compartilhamento_tipo = $link_compartilhamento_info[0];
		$link_compartilhamento_codigo = $link_compartilhamento_info[1];
		$check_membro = check_membro_grupo($user_id, $grupo_id);
		if ($check_membro == false) {
			if (isset($_GET['cd'])) {
				$check_link_compartilhamento_codigo = (int)$_GET['cd'];
				if ($check_link_compartilhamento_codigo == $link_compartilhamento_codigo) {
					switch ($link_compartilhamento_tipo) {
						case 'open':
							$query = prepare_query("INSERT INTO Membros (grupo_id, membro_user_id, estado, user_id) VALUES ($pagina_item_id, $user_id, 1, 0)");
							$conn->query($query);
							break;
						case 'onetimer':
							$query = prepare_query("UPDATE Paginas_elementos SET estado = 0 WHERE tipo = 'linkshare' AND pagina_id = $pagina_id");
							$conn->query($query);
							$query = prepare_query("INSERT INTO Membros (grupo_id, membro_user_id, estado, user_id) VALUES ($pagina_item_id, $user_id, 1, 0)");
							$conn->query($query);
							$link_compartilhamento_tipo = 'disabled';
							$link_compartilhamento_codigo = false;
							break;
					}
				} else {
					header('Location:pagina.php?pagina_id=3');
					exit();
				}
			} else {
				header('Location:pagina.php?pagina_id=3');
				exit();
			}
		}
	} elseif ($pagina_tipo == 'texto') {
		$pagina_texto_id = $pagina_item_id;
		$texto_info = return_texto_info($pagina_texto_id);
		$texto_curso_id = $texto_info[0];
		$texto_tipo = $texto_info[1];
		$texto_titulo = $texto_info[2];
		$texto_page_id = $texto_info[3];
		$texto_criacao = $texto_info[4];
		$texto_verbete_html = $texto_info[5];
		$texto_verbete_text = $texto_info[6];
		$texto_user_id = $texto_info[8];
		$texto_pagina_id = $texto_info[9];
		$texto_compartilhamento = $texto_info[11];
		$texto_texto_pagina_id = $texto_info[12];
		if (($texto_revisao_ativa == 1) && ($user_revisor == false)) {
			$privilegio_edicao = false;
		}
		$pagina_id = $texto_texto_pagina_id;
		if (isset($_POST['destruir_anotacao'])) {
			$query = prepare_query("DELETE FROM Textos WHERE id = $pagina_texto_id");
			$conn->query($query);
			$query = prepare_query("DELETE FROM Paginas WHERE id = $pagina_id");
			$conn->query($query);
			header('Location:pagina.php?pagina_id=5');
			exit();
		}
		if ($texto_page_id === 0) {
			$texto_editar_titulo = true;
		}
		if (isset($_GET['corr'])) {
			$open_review_modal = true;
		}
	} elseif ($pagina_tipo == 'secao') {
		$pagina_original_info = return_pagina_info($pagina_item_id);
		$pagina_original_compartilhamento = $pagina_original_info[4];
		$pagina_compartilhamento = $pagina_original_compartilhamento;
		$pagina_original_user_id = $pagina_original_info[5];
		if (($pagina_original_user_id != $user_id) && ($pagina_original_compartilhamento == 'privado')) {
			$check_compartilhamento = return_compartilhamento($pagina_item_id, $user_id);
			if ($check_compartilhamento == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
		}
		$pre_fab_modal_secoes = false;
		$pre_fab_modal_secoes .= return_list_item($pagina_item_id, 'link', 'list-group-item-success mb-1');
		$query = prepare_query("SELECT secao_pagina_id FROM Secoes WHERE pagina_id = $pagina_item_id ORDER BY ordem, id");
		$parentes = $conn->query($query);
		$found = false;
		$mais_recente = $pagina_item_id;
		if ($parentes->num_rows > 0) {
			while ($parente = $parentes->fetch_assoc()) {
				$parente_id = $parente['secao_pagina_id'];
				if ($found == true) {
					$proxima_pagina = $parente_id;
					$found = false;
				}
				if ($parente_id == $pagina_id) {
					$found = true;
					$pagina_anterior = $mais_recente;
					$lista_tipo = 'inactive';
					$item_classes = 'list-group-item-secondary';
				} else {
					$lista_tipo = false;
					$item_classes = false;
				}
				$mais_recente = $parente_id;
				$pre_fab_modal_secoes .= return_list_item($parente_id, $lista_tipo, $item_classes);
			}
		}
		$pre_fab_modal_secoes = list_wrap($pre_fab_modal_secoes);
	} elseif ($pagina_tipo == 'resposta') {
		$resposta_id = $pagina_id;
		$resposta_info = return_pagina_info($pagina_id);
		$original_id = $resposta_info[1];
		$original_info = return_pagina_info($original_id);
		$original_user_id = $original_info[5];
		if ($original_user_id != $user_id) {
			$check_compartilhamento = return_compartilhamento($original_id, $user_id);
			if ($check_compartilhamento == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
		}
		$original_titulo = $original_info[6];
		$original_texto_id = return_texto_id('texto', 'anotacoes', $original_id, $user_id);
		$original_texto_info = return_texto_info($original_texto_id);
		$original_texto_html = $original_texto_info[5];
	}

	if ($pagina_tipo == 'escritorio') {
	    header('Location:escritorio.php');
    }

	if ($pagina_tipo == 'elemento') {
		include 'pagina/isset_elemento.php';
		include 'pagina/queries_elemento.php';
	}

	include 'pagina/shared_issets.php';

	if (($pagina_tipo == 'elemento') || ($pagina_tipo == 'pagina') || ($pagina_tipo == 'grupo')) {
		if (isset($_POST['trigger_nova_secao'])) {
			$nova_secao_titulo = $_POST['elemento_nova_secao'];
			$nova_secao_titulo = mysqli_real_escape_string($conn, $nova_secao_titulo);
			$nova_secao_ordem = (int)$_POST['elemento_nova_secao_ordem'];
			$query = prepare_query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($pagina_id, 'secao', 'igual à página original', $user_id)");
			$conn->query($query);
			$nova_pagina_id = $conn->insert_id;
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_pagina_id, 'secao', 'titulo', '$nova_secao_titulo', $user_id)");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Secoes (ordem, user_id, pagina_id, secao_pagina_id) VALUES ($nova_secao_ordem, $user_id, $pagina_id, $nova_pagina_id)");
			$conn->query($query);
			if ($pagina_tipo == 'elemento') {
				$nova_etiqueta_titulo = "$elemento_titulo // $nova_secao_titulo";
				$nova_etiqueta_titulo = mysqli_real_escape_string($conn, $nova_etiqueta_titulo);
				$query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$nova_etiqueta_titulo', $user_id)");
				$conn->query($query);
				$nova_etiqueta_id = $conn->insert_id;
				$query = prepare_query("UPDATE Paginas SET etiqueta_id = $nova_etiqueta_id WHERE id = $nova_pagina_id");
				$conn->query($query);
			}
			$nao_contar = true;
		}
	}

	$pagina_bookmark = false;
	$pagina_completed = false;
	if (in_array($pagina_id, $user_bookmarks)) {
		$pagina_bookmark = true;
	}
	if (in_array($pagina_id, $user_completed)) {
		$pagina_completed = true;
	}

	if ($user_id != false) {
		if ($nao_contar == false) {
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia')) {
				$visualizacao_extra = $pagina_item_id;
			} elseif ($pagina_tipo == 'elemento') {
				$visualizacao_extra = $elemento_tipo;
			} elseif ($pagina_tipo == 'texto') {
				$visualizacao_extra = $pagina_texto_id;
			} else {
				$visualizacao_extra = "NULL";
			}
			$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra, extra2) VALUES ($user_id, $pagina_id, '$pagina_tipo', '$visualizacao_extra', 'pagina')");
			$conn->query($query);
		}
	}

	if (isset($_POST['compartilhar_grupo_id'])) {
		$compartilhar_grupo_id = $_POST['compartilhar_grupo_id'];
		$query = prepare_query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('acesso', $user_id, $pagina_id, '$pagina_tipo', 'grupo', $compartilhar_grupo_id)");
		$conn->query($query);
	}

	if (isset($_POST['produto_nova_imagem'])) {
		$produto_nova_imagem_elemento_id = $_POST['produto_nova_imagem'];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($pagina_id, 'produto', $produto_nova_imagem_elemento_id, 'imagem', $user_id)");
		$conn->query($query);
	}

	if (isset($_POST['novo_produto_preco'])) {
		$novo_produto_preco = $_POST['novo_produto_preco'];
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($pagina_id, 'produto', 'preco', $novo_produto_preco, $user_id)");
		$conn->query($query);
		$produto_preco = $novo_produto_preco;
	}

	if ($pagina_subtipo == 'produto') {
		$query = prepare_query("SELECT id FROM Carrinho WHERE user_id = $user_id AND produto_pagina_id = $pagina_id AND estado = 1");
		$carrinho = $conn->query($query);
		if ($carrinho->num_rows > 0) {
			$produto_no_carrinho = true;
		} else {
			$produto_no_carrinho = false;
		}
	}

	if (isset($_POST['adicionar_produto_pagina_id'])) {
		$adicionar_produto_pagina_id = $_POST['adicionar_produto_pagina_id'];
		$query = prepare_query("INSERT INTO Carrinho (user_id, produto_pagina_id, estado) VALUES ($user_id, $pagina_id, 1)");
		$conn->query($query);
		$produto_no_carrinho = true;
	}

	if (($pagina_tipo == 'elemento') || ($pagina_tipo == 'grupo') || (($pagina_tipo == 'pagina') && (($pagina_compartilhamento != 'escritorio') && ($pagina_subtipo != 'produto') && ($pagina_subtipo != 'simulado') && ($pagina_subtipo != 'plano')))) {
		$carregar_secoes = true;
	} else {
		$carregar_secoes = false;
	}

	if ($carregar_secoes == true) {
		$query = prepare_query("SELECT secao_pagina_id, ordem FROM Secoes WHERE pagina_id = $pagina_id ORDER BY ordem, id");
		$secoes = $conn->query($query);
	}
	$query = prepare_query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1 AND extra IS NOT NULL");
	$etiquetados = $conn->query($query);
	if ($pagina_tipo == 'texto') {
		$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'resposta'");
		$respostas = $conn->query($query);
	}
	if ($pagina_tipo == 'grupo') {
		$query = prepare_query("SELECT DISTINCT membro_user_id, estado FROM Membros WHERE grupo_id = $grupo_id AND (estado = 1 OR estado IS NULL)");
		$membros = $conn->query($query);
	}

	include 'pagina/queries_notificacoes.php';

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	$paginas_sem_verbete = array('texto', 'materia', 'questao', 'texto_apoio', 'grupo');
	$paginas_subtipos_sem_verbete = array('modelo');
	if (!isset($pagina_com_verbete)) {
		$pagina_com_verbete = false;
		if ((!in_array($pagina_tipo, $paginas_sem_verbete)) && (!in_array($pagina_subtipo, $paginas_subtipos_sem_verbete)) && ($pagina_id != $user_escritorio)) {
			$pagina_com_verbete = true;
		}
	}

	$html_head_template_quill = true;
	if (($pagina_tipo == 'questao') || ($pagina_tipo == 'texto_apoio')) {
		$html_head_template_quill_sim = true;
	}
	include 'templates/html_head.php';

?>
<body class="grey lighten-5">
<?php
	$pagina_padrao = true;
	include 'templates/navbar.php';
?>
<div class="container-fluid" id="buttonsbar">
    <div class="row d-flex justify-content-between">
        <div class='py-2 text-left col-md-4 col-sm-12'>
			<?php
				if (($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio') && ($pagina_tipo != 'materia') && ($pagina_subtipo != 'modelo')) {
					if ($privilegio_edicao == true) {
						echo "<a href='javascript:void(0)' class='text-success mr-1' id='add_elements' title='{$pagina_translated['Adicionar elementos']}' data-toggle='modal' data-target='#modal_add_elementos'><i class='fad fa-lg fa-plus-circle fa-fw'></i></a>";
					}
				}
				if ($pagina_tipo == 'elemento') {
					echo "
                        <a href='javascript:void(0);' data-toggle='modal' data-target='#modal_dados_elemento' class='text-info mr-1' id='elemento_dados' title='{$pagina_translated['Editar dados']}'><i class='fad fa-info-circle fa-fw fa-lg'></i></a>
                        <a href='javascript:void(0);' data-toggle='modal' data-target='#modal_elemento_subtipo' class='text-info mr-1' id='elemento_subtipo' title='{$pagina_translated['Determinar subcategoria']}'><i class='fad fa-sort-circle fa-fw fa-lg'></i></a>
                    ";
				}
				if (!isset($pagina_original_compartilhamento)) {
				    $pagina_original_compartilhamento = $pagina_compartilhamento;
                }
				$modal_pagina_dados = return_admin_status($pagina_id, $pagina_tipo, $pagina_subtipo, $user_id, $user_tipo, $pagina_user_id, $pagina_compartilhamento, $pagina_curso_user_id, $texto_page_id, $pagina_original_compartilhamento);

				if (($pagina_tipo == 'materia') && ($pagina_user_id == $user_id)) {
					$carregar_adicionar_topico = true;
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_topico' class='text-info mr-1' id='add_topico' title='{$pagina_translated['Adicionar tópico']}'><i class='fad fa-plus-square fa-swap-opacity fa-lg fa-fw'></i></a>";
				}
				if ($modal_pagina_dados == true) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_remover_elementos' class='text-info mr-1' id='pagina_elementos_remover' title='{$pagina_translated['Remover elementos']}'><i class='fad fa-sliders-h-square fa-swap-opacity fa-fw fa-lg'></i></a>";
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_pagina_dados' class='text-info mr-1' id='pagina_dados' title='{$pagina_translated['Editar dados']}'><i class='fad fa-info-square fa-swap-opacity fa-fw fa-lg'></i></a>";
					$carregar_produto_setup = false;
					if ($pagina_subtipo == 'produto') {
						$carregar_produto_setup = true;
						if (isset($imagem_opcoes)) {
							echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_produto_nova_imagem' class='text-danger mr-1' id='produto_imagem' title='{$pagina_translated['Imagem do produto']}'><i class='fad fa-image-polaroid fa-fw fa-lg'></i></a>";
						}
						echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_produto_preco' class='text-warning mr-1' id='produto_preco' title='{$pagina_translated['Preço do produto']}'><i class='fad fa-usd-circle fa-fw fa-lg'></i></a>";
					}
				}
				if (($pagina_tipo == 'curso') && ($pagina_curso_user_id == $user_id)) {
					$carregar_adicionar_materia = true;
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_materia' class='text-info mr-1' id='add_materia' title='{$pagina_translated['Adicionar matéria']}'><i class='fad fa-plus-square fa-swap-opacity fa-lg fa-fw'></i></a>";
				}
				if (($pagina_tipo == 'topico') && ($pagina_user_id == $user_id) && ($topico_nivel < 5)) {
					$carregar_adicionar_subtopico = true;
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_subtopico' class='text-info mr-1' id='add_subtopico' title='{$pagina_translated['Adicionar subtópico']}'><i class='fad fa-plus-square fa-swap-opacity fa-lg fa-fw'></i></a>";
				}
				if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id) && ($pagina_subtipo != 'Plano de estudos') && ($pagina_subtipo != 'modelo')) {
					if (($pagina_tipo != 'materia') && ($pagina_tipo != 'topico')) {
						echo "
                          <a href='javascript:void(0);' class='text-default mr-1 align-top' id='compartilhar_anotacao' title='{$pagina_translated['Colaboração e publicação']}' data-toggle='modal' data-target='#modal_compartilhar_pagina'>
                              <i class='fad fa-user-friends fa-fw fa-lg'></i>
                          </a>
                        ";
					}
					$carregar_modal_destruir_pagina = true;
					echo "
                      <a href='javascript:void(0);' class='text-danger mr-1 align-top' id='destruir_pagina' title='{$pagina_translated['Destruir esta página']}' data-toggle='modal' data-target='#modal_destruir_pagina'>
                          <i class='fad fa-trash-alt fa-fw fa-lg'></i>
                      </a>
                    ";
				}
				if ($pagina_tipo == 'questao') {
					echo "<a href='javascript:void(0);' class='mr-1 text-secondary' title='{$pagina_translated['Dados da questão']}' data-toggle='modal' data-target='#modal_questao_dados'><i class='fad fa-info-circle fa-fw fa-lg'></i></a>";
				} elseif ($pagina_tipo == 'texto_apoio') {
					echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_texto_apoio_dados' class='text-secondary mr-1' title='{$pagina_translated['Dados do texto de apoio']}'><i class='fad fa-info-circle fa-fw fa-lg'></i></a>";
				}
				if ($pagina_subtipo == 'modelo') {
					echo "
				        <a href='javascript:void(0);' data-toggle='modal' data-target='#modal_modelo_config' class='text-secondary mr-1' title='{$pagina_translated['Configurar modelo']}'><i class='fad fa-info-circle fa-fw fa-lg'></i></a>
				    ";
				}
				if ((($pagina_tipo == 'texto') && ($pagina_user_id == $user_id)) || (($texto_revisao_ativa == true) && ($user_revisor == true)) || ($pagina_tipo == 'topico')) {
					$carregar_modal_correcao = true;
					if ($texto_revisao_ativa == true) {
						if ($user_revisor == true) {
							$pencil_color1 = 'text-info';
						} else {
							$pencil_color1 = 'text-muted';
						}
					} else {
						$pencil_color1 = 'text-warning';
					}
					echo "<a id='carregar_modal_correcao' href='javascript:void(0);' class='$pencil_color1' data-toggle='modal' data-target='#modal_correcao' title='{$pagina_translated['Solicitar correção']}'><i class='fad fa-highlighter fa-fw fa-lg'></i></a>";
				}
			?>
        </div>
        <div class="py-2 text-center col-md-4 col-sm-12">
			<?php
				if (isset($pagina_anterior)) {
					echo "<a href='pagina.php?pagina_id=$pagina_anterior' class='mr-2 text-default'><i class='fad fa-arrow-left fa-fw fa-lg'></i></a>";
				}
				if ($pagina_tipo == 'curso') {
					echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_busca' class='text-primary' title='{$pagina_translated['Busca']}'><i class='fad fa-search fa-fw fa-lg'></i></a>";
				}
				echo "<a href='javascript:void(0);' class='hidden text-dark mx-2' id='show_bars'><i class='fad fa-eye fa-fw fa-lg'></i></a>";
				if ($pagina_tipo == 'topico') {
					echo "<a href='javascript:void(0);' id='verbetes_relacionados' class='text-primary mx-1' title='{$pagina_translated['Navegação']}' data-toggle='modal' data-target='#modal_verbetes_relacionados'><i class='fad fa-location-circle fa-fw fa-lg'></i></a>";
				} elseif ($pagina_tipo == 'secao') {
					echo "<a href='javascript:void(0);' id='secoes' class='mx-1 text-default' title='{$pagina_translated['Página e seções']}' data-toggle='modal' data-target='#modal_paginas_relacionadas'><i class='fad fa-sitemap fa-fw fa-lg'></i></a>";
				}
				if ($carregar_secoes == true) {
					if ($secoes->num_rows > 0) {
						$carregar_partes_elemento_modal = true;
						if ($pagina_tipo == 'elemento') {
							if ($elemento_subtipo == 'podcast') {
								$partes_titulo = $pagina_translated['Episódios'];
							} elseif ($elemento_subtipo == 'livro') {
								$partes_titulo = $pagina_translated['Capítulos'];
							}
						}
						if (!isset($template_titulo)) {
							$partes_titulo = $pagina_translated['Seções'];
						}
						echo "<a href='javascript:void(0);' id='partes' class='mx-1 text-primary' title='$partes_titulo' data-toggle='modal' data-target='#modal_partes_elemento'><i class='fad fa-sitemap fa-fw fa-lg'></i></a>";
					}
				}
				if ($pagina_subtipo == 'produto') {
					if ($produto_no_carrinho == false) {
						echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_adicionar_carrinho' id='adicionar_carrinho' class='text-success mx-1' title='{$pagina_translated['Adicionar este produto a seu carrinho']}'><i class='fad fa-cart-plus fa-fw fa-lg'></i></a>";
					}
				}
				if ($pagina_subtipo == 'plano') {
					if ($pagina_id == $user_escritorio) {
						$pagina_plan = 'plano_id=bp';
					} else {
						$pagina_plan = "pagina_id=$pagina_id";
					}
					echo "<a href='pagina.php?$pagina_plan&sc=$change_show_completed&hl=$plan_show_low' class='$color_show_completed ml-1'><i class='fad fa-badge-check fa-lg'></i></a>";
					echo "<a href='pagina.php?$pagina_plan&sc=$plan_show_completed&hl=$change_show_low' class='$color_show_low ml-1'><i class='fad fa-times-octagon fa-lg'></i></a>";
				}
				if (isset($proxima_pagina)) {
					echo "<a href='pagina.php?pagina_id=$proxima_pagina' class='ml-2 text-default'><i class='fad fa-arrow-right fa-fw fa-lg'></i></a>";
				}
			?>
        </div>
        <div class='py-2 text-right col-md-4 col-sm-12'>
			<?php
                echo "<a id='swatch_choice' data-target='#modal_escolher_cores' data-toggle='modal' class='ml-1 brown-text rounded swatch_button' value='default'><i class='fad fa-palette fa-fw fa-lg'></i></a>";
				if ($pagina_tipo == 'curso') {
					if ($user_id != false) {
						$carregar_toggle_curso = true;
						$curso_aderir_hidden = false;
						$curso_sair_hidden = false;
						$return_usuario_cursos_inscrito = return_usuario_cursos_inscrito($user_id);
						if (in_array($pagina_id, $return_usuario_cursos_inscrito)) {
							$curso_aderir_hidden = 'hidden';
						} else {
							$curso_sair_hidden = 'hidden';
						}
						echo "<a href='javascript:void(0);' class='ml-1 text-primary $curso_aderir_hidden' title='{$pagina_translated['Aderir a este curso']}' id='curso_aderir'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
						echo "<a href='javascript:void(0);' class='ml-1 text-success $curso_sair_hidden' title='{$pagina_translated['Sair deste curso']}' id='curso_sair'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
					}
				}
				/*
				if ($user_tipo == 'admin') {
					if ((($pagina_subtipo != 'produto') && ($pagina_tipo != 'escritorio')) && ($pagina_subtipo != 'modelo') || ($pagina_tipo == 'escritorio') && ($pagina_user_id = $user_id)) {
						$existe_produto = false;
						if ($existe_produto == true) {
							$produto_color = 'text-danger';
						} else {
							$produto_color = 'text-primary';
						}
						echo "<a href='mercado.php?pagina_id=$pagina_id' class='$produto_color ml-1 align-top' title='{$pagina_translated['visit_market']}'><i class='fad fa-bags-shopping fa-fw fa-lg'></i></a>";
					}
				}*/
				if ($carregar_partes_elemento_modal == true) {
					echo "
					        <a href='print_elemento.php?pagina_id=$pagina_id' target='_blank' id='print_elemento_partes' class='ml-1 text-primary' title='{$pagina_translated['print elemento partes']}'><i class='fad fa-print fa-fw fa-lg'></i></a>
					    ";
				}
				if (($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio') && ($pagina_subtipo != 'modelo') && ($pagina_id != $user_escritorio)) {
					$query = prepare_query("SELECT timestamp, comentario_text, user_id FROM Forum WHERE pagina_id = $pagina_id");
					$comments = $conn->query($query);
					if ($comments->num_rows == 0) {
						$forum_color = 'text-primary';
					} else {
						$forum_color = 'text-secondary';
					}
					if ($user_id != false) {
						echo "
                                <a href='forum.php?pagina_id=$pagina_id' title='{$pagina_translated['forum']}' class='$forum_color ml-1 align-top'>
                                    <i class='fad fa-comments-alt fa-fw fa-lg'></i>
                                </a>
                            ";
					} else {
						echo "
									<a href='javascript:void(0);' title='Fórum' class='text-secondary ml-1 align-top' data-toggle='modal' data-target='#modal_login'><i class='fad fa-comments-alt fa-fw fa-lg' title='{$pagina_translated['Login']}'></i></a>
								";
					}
				}
				if ($pagina_tipo == 'elemento') {
					if ($user_id != false) {
						$carregar_toggle_acervo = true;
						$query = prepare_query("SELECT id FROM Paginas_elementos WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND elemento_id = $pagina_item_id AND estado = 1");
						$elemento_no_acervo = $conn->query($query);
						if ($elemento_no_acervo->num_rows > 0) {
							$item_no_acervo = true;
						}
						echo "
								  <a id='remover_acervo' href='javascript:void(0);' class='ml-1 text-success' title='{$pagina_translated['Remover do seu acervo']}'>
									  <i class='fad fa-lamp-desk fa-fw fa-lg'></i>
								  </a>
								  <a id='adicionar_acervo' href='javascript:void(0);' class='ml-1 text-primary' title='{$pagina_translated['Adicionar a seu acervo']}'>
									  <i class='fad fa-lamp-desk fa-fw fa-lg'></i>
								  </a>
						        ";
					} else {
						echo "<a href='javascript:void(0);' class='ml-1 text-success' title='{$pagina_translated['Adicionar a seu acervo']}' data-toggle='modal' data-target='#modal_login'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
					}
				}
				if ($pagina_subtipo == 'etiqueta') {
					if ($user_tipo == 'admin') {
						echo "<a id='apagar_etiqueta' class='text-danger ml-1' title='Apagar esta etiqueta'><i class='fad fa-trash fa-fw fa-lg'></i></a>";
					}
					if ($user_id != false) {
						$carregar_toggle_paginas_livres = true;
						if (in_array($pagina_id, $user_areas_interesse)) {
							$area_interesse_ativa = true;
						}
						echo "
						      <a id='remover_area_interesse' href='javascript:void(0);' class='ml-1 text-warning' title='{$pagina_translated['Remover como área de interesse']}'>
						      	<i class='fad fa-lamp-desk fa-fw fa-lg'></i>
							  </a>
						      <a id='adicionar_area_interesse' href='javascript:void(0);' class='ml-1 text-primary' title='{$pagina_translated['Adicionar como área de interesse']}'>
						      	<i class='fad fa-lamp-desk fa-fw fa-lg'></i>
							  </a>
						    ";
					} else {
						echo "<a title='{$pagina_translated['Adicionar como área de interesse']}' href='javascript:void(0);' class='ml-1 text-warning' data-toggle='modal' data-target='#modal_login'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
					}
				}
				if ($pagina_subtipo != 'modelo') {
					if ($pagina_subtipo != 'plano') {
						$query = prepare_query("SELECT elemento_id, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'wikipedia'");
						$vinculos_wikipedia = $conn->query($query);
						if ($vinculos_wikipedia->num_rows > 0) {
							$carregar_modal_wikipedia = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_vinculos_wikipedia' class='text-dark ml-1' title='{$pagina_translated['Ver artigos da Wikipédia vinculados']}'><i class='fab fa-wikipedia-w fa-fw fa-lg'></i></a>";
						}
						if ($user_id != false) {
							$notificacao_modal = '#modal_notificacoes';
							$notificacao_cor = 'text-primary';
						} else {
							$notificacao_modal = '#modal_login';
							$notificacao_cor = 'text-default';
							$notificacao_icone = 'fa-bell fa-swap-opacity';
						}
						echo "<a href='javascript:void(0);' class='$notificacao_cor ml-1' data-toggle='modal' data-target='$notificacao_modal'><i class='fad $notificacao_icone fa-fw fa-lg'></i></a>";
					}
				} else {
					$adicionar_modelo_hidden = false;
					$remover_modelo_hidden = false;
					if ($modelo_do_usuario == false) {
						$remover_modelo_hidden = 'hidden';
					} else {
						$adicionar_modelo_hidden = 'hidden';
					}
					echo "<a class='text-primary escritorio_modelo $adicionar_modelo_hidden' id='adicionar_modelo' value='adicionar_modelo' title='{$pagina_translated['Adicionar seus modelos']}'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
					echo "<a class='text-secondary escritorio_modelo $remover_modelo_hidden' id='remover_modelo' value='remover_modelo' title='{$pagina_translated['Remover seus modelos']}'><i class='fad fa-lamp-desk fa-fw fa-lg'></i></a>";
				}
				if (($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio') && ($pagina_id != $user_escritorio)) {
					if ($etiquetados->num_rows > 0) {
						$etiquetas_color = 'text-warning';
					} else {
						$etiquetas_color = 'text-primary';
					}
					if ($user_id != false) {
						$etiquetas_modal = '#modal_secao_etiquetas';
					} else {
						$etiquetas_modal = '#modal_login';
						$etiquetas_color = 'text-warning';
					}
					echo "
                      <a id='adicionar_etiqueta' class='ml-1 $etiquetas_color' title='{$pagina_translated['Adicionar etiqueta']}' data-dismiss='modal' data-toggle='modal' href='$etiquetas_modal'>
                              <i class='fad fa-tags fa-fw fa-lg'></i>
                      </a>
                    ";
					if ($pagina_tipo == 'topico') {
						if ($user_id != false) {
							if ($pagina_completed == true) {
								$marcar_completo = 'collapse';
								$marcar_incompleto = false;
							} else {
								$marcar_completo = false;
								$marcar_incompleto = 'collapse';
							}
							echo "
                                  <a id='add_completed' href='javascript:void(0);' class='text-primary ml-1 $marcar_completo' title='{$pagina_translated['Marcar estudo completo']}' value='$pagina_id'><i class='fad fa-check-circle fa-fw fa-lg'></i></a>
                                  <a id='remove_completed' href='javascript:void(0);' class='ml-1 $marcar_incompleto text-success' title='{$pagina_translated['Desmarcar como completo']}' value='$pagina_id'><i class='fad fa-check-circle fa-fw fa-lg'></i></a>
                                ";
						} else {
							echo "
								        <a href='javascript:void(0);' class='text-success ml-1' title='{$pagina_translated['Marcar estudo completo']}' data-toggle='modal' data-target='#modal_login'><i class='fad fa-check-circle fa-fw fa-lg'></i></a>
								    ";
						}
					}
					if ($pagina_bookmark == true) {
						$marcar_bookmark = 'collapse';
						$desmarcar_bookmark = false;
					} else {
						$marcar_bookmark = false;
						$desmarcar_bookmark = 'collapse';
					}
					if (($pagina_subtipo != 'modelo') && ($pagina_id != $user_escritorio)) {
						if ($user_id != false) {
							echo "
                                  <a href='javascript:void(0);' id='add_bookmark' class='text-primary ml-1 $marcar_bookmark' title='{$pagina_translated['Marcar para leitura']}' value='$pagina_id'><i class='fad fa-bookmark fa-fw fa-lg'></i></a>
                                  <a href='javascript:void(0);' id='remove_bookmark' class='text-danger ml-1 $desmarcar_bookmark' title='{$pagina_translated['Remover da lista de leitura']}' value='$pagina_id'><i class='fad fa-bookmark fa-fw fa-lg'></i></a>
                                ";
						} else {
							echo "
                                    <a href='javascript:void(0);' id='login_bookmark' class='text-danger ml-1' title='{$pagina_translated['Marcar para leitura']}' data-toggle='modal' data-target='#modal_login'><i class='fad fa-bookmark fa-fw fa-lg'></i></a>
                                ";
						}
					}
					$pagina_estado_icone = return_estado_icone($pagina_estado);
					$estado_cor = $pagina_estado_icone[1];
					$estado_icone = $pagina_estado_icone[0];

					if ($user_id == false) {
						$estado_modal = '#modal_login';
					} else {
						$estado_modal = '#modal_estado';
					}
					echo "
                            <a href='javascript:void(0);' id='change_estado_pagina' class='ml-1 $estado_cor' title='{$pagina_translated['Estado da página']}' data-toggle='modal' data-target='$estado_modal'><i class='$estado_icone fa-fw fa-lg'></i></a>
                        ";
				}
			?>
        </div>
    </div>
</div>
<div class="container" id="titlebar">
	<?php
		if ($pagina_tipo == 'topico') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = "<a href='pagina.php?pagina_id=$topico_materia_pagina_id' title='{$pagina_translated['Matéria']}'>$topico_materia_titulo</a> / <a href='pagina.php?pagina_id=$topico_curso_pagina_id' title='Curso'>$topico_curso_titulo</a>";
		} elseif ($pagina_tipo == 'elemento') {
			$template_titulo = $elemento_titulo;
			$template_subtitulo = $elemento_autor;
		} elseif ($pagina_tipo == 'curso') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = "{$pagina_translated['Curso']}</br><a class='text-light' id='reveal_introduction'><i class='fad fa-info-circle fa-fw'></i></a>";
			//$template_subtitulo = "<a href='javascript:void(0);' id='reveal_introduction'>{$pagina_translated['Curso']}</a>";
		} elseif ($pagina_tipo == 'materia') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = "{$pagina_translated['Matéria']} / <a href='pagina.php?curso_id=$pagina_curso_id'>$pagina_curso_titulo</a>";
		} elseif ($pagina_tipo == 'texto') {
			if ($texto_page_id != false) {
				$template_titulo = return_pagina_titulo($texto_pagina_id);
			}
			$template_subtitulo = false;
			if ($texto_page_id == 0) {
				if ($texto_titulo != false) {
					$template_titulo = $texto_titulo;
				} else {
					$template_titulo = $pagina_translated['Texto sem título'];
				}
				$template_subtitulo = $pagina_translated['Texto privado'];
			} else {
				$template_subtitulo = "<a href='pagina.php?pagina_id=$texto_pagina_id'>{$pagina_translated['Página']}</a>";
			}
			$template_titulo_no_nav = false;
		} elseif ($pagina_tipo == 'sistema') {
			$template_titulo = $pagina_titulo;
		} elseif ($pagina_tipo == 'pagina') {
			if ($pagina_titulo == false) {
				$template_titulo = $pagina_translated['Página sem título'];
			} else {
				$template_titulo = $pagina_titulo;
			}
			if ($pagina_compartilhamento == 'privado') {
				$template_subtitulo = $pagina_translated['private page'];
			} elseif ($pagina_compartilhamento == 'publico') {
				$template_subtitulo = $pagina_translated['public page'];
			} elseif ($pagina_compartilhamento == 'escritorio') {
				$pagina_user_apelido = return_apelido_user_id($pagina_user_id);
				$pagina_user_avatar = return_avatar($pagina_user_id);
				$pagina_user_avatar_icone = $pagina_user_avatar[0];
				$pagina_user_avatar_cor = $pagina_user_avatar[1];
				$template_subtitulo = "{$pagina_translated['Escritório de']} <span class='$pagina_user_avatar_cor'><i class='fad $pagina_user_avatar_icone fa-fw'></i></span> $pagina_user_apelido";
			} else {
				$template_subtitulo = $pagina_compartilhamento;
			}
			if ($pagina_subtipo == 'Plano de estudos') {
				$template_titulo = return_pagina_titulo($pagina_id);
				$pagina_original_info = return_pagina_info($pagina_item_id);
				$pagina_original_titulo = $pagina_original_info[6];
				$pagina_original_concurso_pagina_id = $pagina_original_info[1];
				$pagina_original_concurso_titulo = return_pagina_titulo($pagina_original_concurso_pagina_id);
				$template_titulo = "{$pagina_translated['Plano de estudos']}: $pagina_original_titulo";
				$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_item_id'>$pagina_original_titulo</a> / <a href='pagina.php?pagina_id=$pagina_original_concurso_pagina_id'>$pagina_original_concurso_titulo</a>";
			} elseif ($pagina_subtipo == 'etiqueta') {
				$template_subtitulo = $pagina_translated['free page'];
			} elseif ($pagina_subtipo == 'modelo') {
				$template_subtitulo = $pagina_translated['BFranklin model'];
			} elseif ($pagina_subtipo == 'plano') {
				$template_subtitulo = "{$pagina_translated['Plano de estudos']}</br><a class='text-light' id='reveal_introduction'><i class='fad fa-info-circle fa-fw'></i></a>";
			} elseif ($pagina_subtipo == 'simulado') {
				$template_subtitulo = $pagina_translated['Simulado'];
			}
		} elseif ($pagina_tipo == 'secao') {
			$template_titulo = $pagina_titulo;
			$paginal_original_info = return_pagina_info($pagina_item_id);
			$pagina_original_titulo = $pagina_original_info[6];
			$pagina_original_compartilhamento = $pagina_original_info[4];
			$template_subtitulo = "{$pagina_translated['Seção de']} \"$pagina_original_titulo\"";
			if ($pagina_original_compartilhamento == 'privado') {
				$template_subtitulo = "$template_subtitulo ({$pagina_translated['Página e seções privadas']})";
			}
		} elseif ($pagina_tipo == 'grupo') {
			$template_titulo = return_grupo_titulo_id($grupo_id);
			$template_subtitulo = $pagina_translated['study group'];
		} elseif ($pagina_tipo == 'resposta') {
			if ($pagina_titulo == false) {
				$template_titulo = $pagina_translated['Resposta sem título'];
			} else {
				$template_titulo = $pagina_titulo;
			}
			if ($original_titulo != false) {
				$template_subtitulo = "{$pagina_translated['Referente ao texto']} \"$original_titulo\"";
			} else {
				$template_subtitulo = $pagina_translated['Referente a texto sem título'];
			}
		} elseif ($pagina_tipo == 'questao') {
			$template_titulo = $pagina_titulo;
			$pagina_questao_curso_titulo = return_curso_titulo_id($pagina_questao_curso_id);
			$pagina_questao_materia_titulo = return_pagina_titulo($pagina_questao_materia);
			$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_questao_materia'>$pagina_questao_materia_titulo</a> / <a href='pagina.php?curso_id=$pagina_questao_curso_id'>$pagina_questao_curso_titulo</a>";
		} elseif ($pagina_tipo == 'texto_apoio') {
			$template_titulo = $pagina_texto_apoio_titulo;
			$template_subtitulo = "{$pagina_translated['Texto de apoio']} / <a href='pagina.php?curso_id=$pagina_texto_apoio_curso_id'>$pagina_texto_apoio_curso_titulo</a>";
		}
		if ($wiki_id != false) {
			$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_id'>{$pagina_translated['Retornar ao verbete da página']}</a>";
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row d-flex justify-content-around">
        <div id="coluna_unica" class="col-lg-10 col-md-12 pagina_coluna">
			<?php
				if ($pagina_tipo == 'grupo') {
					include 'pagina/grupo.php';
				} elseif ($pagina_tipo == 'materia') {
					include 'pagina/materia.php';
				}
			?>
        </div>
		<?php

			if ($pagina_tipo == 'resposta') {
				echo "<div id='coluna_original' class='$coluna_classes pagina_coluna'>";
				$template_id = 'texto_original';
				if ($original_titulo != false) {
					$template_titulo = $original_titulo;
				} else {
					$template_titulo = $pagina_translated['Texto original'];
				}
				$template_conteudo = false;
				$template_conteudo = '<p class="mt-4"></p>';
				$template_conteudo .= $original_texto_html;
				include 'templates/page_element.php';
				echo "</div>";
			}

			echo "<div id='coluna_esquerda' class='$coluna_classes pagina_coluna'>";
			if ($pagina_tipo == 'elemento') {
				if ($elemento_tipo == 'imagem') {
					$template_id = 'imagem_div';
					$template_titulo = false;
					$template_col_classes = 'd-flex justify-content-center';
					$template_botoes = false;
					$template_conteudo = "<a href='../imagens/verbetes/$elemento_arquivo' ><img class='imagem_pagina border' src='../imagens/verbetes/$elemento_arquivo'></a>";
					include 'templates/page_element.php';
				} elseif (($elemento_tipo == 'video') && ($elemento_iframe != false)) {
					$template_id = 'video_div';
					$template_titulo = false;
					$template_col_classes = 'd-flex justify-content-center';
					$template_botoes = false;
					$template_conteudo = $elemento_iframe;
					include 'templates/page_element.php';
				} elseif (($elemento_tipo == 'referencia') && ($elemento_link != false)) {
					$template_id = 'referencia_link';
					$template_titulo = false;
					$template_conteudo = false;
					$template_conteudo_no_col = true;
					$elemento_link_host = parse_url($elemento_link, PHP_URL_HOST);
					$template_conteudo .= "
                              <a href='$elemento_link' target='_blank' class='fontstack-mono' title='$elemento_link'><i class='fad fa-external-link fa-fw fa-lg'></i> {$elemento_link_host}/...</a>
                            ";
					include 'templates/page_element.php';
				}
			} elseif ($pagina_tipo == 'texto') {
				if ($privilegio_edicao == false) {
					$template_id = 'texto_privado';
					$template_titulo = $pagina_titulo;
					$template_conteudo = return_verbete_html($pagina_item_id);
					include 'templates/page_element.php';
				} else {
					$template_id = $texto_tipo;
					$template_titulo = false;
					$template_quill_initial_state = 'edicao';
					$template_quill_page_id = $texto_page_id;
					$template_quill_pagina_id = $pagina_id;
					$template_quill_botoes = false;
					$template_conteudo = include 'templates/template_quill.php';
					include 'templates/page_element.php';
				}
			}

			if ($pagina_com_verbete) {
				$template_id = 'verbete';
				if ($wiki_id == false) {
					if (($pagina_tipo == 'curso') || ($pagina_subtipo == 'plano')) {
						$template_classes = 'hidden';
						$template_titulo = $pagina_translated['Apresentação'];
						$template_botoes_padrao = true;
						$template_quill_initial_state = 'leitura';
					} elseif ($pagina_tipo == 'sistema') {
						$template_titulo = $pagina_translated['Aviso'];
					} else {
						$template_titulo = $pagina_translated['Verbete'];
					}
					if (($pagina_tipo == 'sistema') && ($user_tipo != 'admin')) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if (($pagina_compartilhamento == 'escritorio') && ($user_id != $pagina_user_id)) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if ((($pagina_compartilhamento == 'privado') && ($user_id != $pagina_user_id)) && ($check_compartilhamento == false)) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if ($pagina_tipo == 'resposta') {
						$template_titulo = $pagina_translated['Resposta'];
						$template_classes = 'sticky-top';
						$template_quill_vazio = $pagina_translated['Escreva aqui sua resposta.'];
					}
					$template_conteudo = include 'templates/template_quill.php';
					include 'templates/page_element.php';
				} else {
					$template_id = 'verbete_wiki';
					$template_titulo = $pagina_translated['Artigo da Wikipédia'];
					$wiki_info = return_elemento_info($wiki_id);
					$wiki_url = $wiki_info[9];
					$wiki_conteudo = extract_wikipedia($wiki_url);
					$template_conteudo = false;
					$template_conteudo .= "<span class='strip_wikipedia'>$wiki_conteudo</span>";
					include 'templates/page_element.php';
				}

				if ($pagina_tipo == 'curso') {
					$template_id = 'modulos';
					$template_titulo = $pagina_translated['Módulos'];
					$template_botoes = false;
					$template_conteudo = false;

					$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE tipo = 'materia' AND pagina_id = $pagina_id");
					$materias = $conn->query($query);

					if ($materias->num_rows > 0) {
						$template_conteudo .= "<ul class='list-group list-group-flush'>";
						while ($materia = $materias->fetch_assoc()) {
							$materia_pagina_id = $materia['elemento_id'];
							$template_conteudo .= return_list_item($materia_pagina_id, false, 'fontstack-subtitle text-center force-size', true, true);
							//$template_conteudo .= return_list_item($materia_pagina_id, false, 'fontstack-subtitle text-center', true, true, false, false, false, false, 'force-size');
						}
						$template_conteudo .= "</ul>";
						unset($materia_id);
					}

					include 'templates/page_element.php';
				}

				if ($pagina_tipo == 'curso') {
					if ($user_tipo == 'admin') {
						$curso_simulados = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'simulado'");
					} else {
						$curso_simulados = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'simulado' AND estado = 1");
					}
					if ($curso_simulados->num_rows > 0) {
						$template_id = 'lista_simulados';
						$template_titulo = $pagina_translated['Simulados'];
						$template_botoes = false;
						$template_conteudo = false;
						while ($curso_simulado = $curso_simulados->fetch_assoc()) {
							$curso_simulado_pagina_id = $curso_simulado['extra'];
							$template_conteudo .= return_list_item($curso_simulado_pagina_id);
						}
						if ($template_conteudo != false) {
							$template_conteudo = list_wrap($template_conteudo);
							include 'templates/page_element.php';
						}
					}
				}

				if ($pagina_subtipo != 'plano') {
					include 'pagina/leiamais.php';
					include 'pagina/videos.php';
					include 'pagina/imagens.php';
					include 'pagina/audio.php';
				}

				if ($pagina_subtipo == 'etiqueta') {
					include 'pagina/paginas_etiqueta.php';
				}

				// include 'pagina/usos_etiqueta.php';
				// not clear in which cases this should be used.
				// the idea is showing pages connected to the present page
				// does it need to exist though?
			}
			if ($pagina_subtipo == 'modelo') {
				$template_id = 'modelo';
				$template_titulo = $pagina_translated['Modelo'];
				$template_quill_initial_state = 'leitura';
				$template_quill_vazio = $pagina_translated['Model explanation'];
				$template_botoes_padrao = false;
				$template_classes = false;
				if ($modelo_do_usuario != false) {
					if ($modelo_do_usuario == 'hidden') {
						$esconder_paragrafo_hidden_botao = 'hidden';
					} else {
						$esconder_paragrafo_hidden_botao = false;
					}
					$template_botoes = "
				    <a class='text-secondary modelo_esconder_paragrafo' href='javascript:void(0);' class='$esconder_paragrafo_hidden_botao'><i class='fad fa-times-square fa-fw'></i></a>
				";
				}
				if ($modelo_do_usuario == 'hidden') {
					$template_classes .= 'hidden';
				}
				$template_conteudo = include 'templates/template_quill.php';
				include 'templates/page_element.php';

				$template_id = 'modelo_directions';
				$template_titulo = $pagina_translated['Model directions'];
				$template_quill_initial_state = 'leitura';
				$template_quill_vazio = $pagina_translated['Model directions explanation'];
				$template_botoes_padrao = false;
				$template_conteudo = include 'templates/template_quill.php';
				include 'templates/page_element.php';
			}

			if (($pagina_tipo == 'topico') || ($pagina_subtipo == 'simulado')) {
				$query = prepare_query("SELECT elemento_id, extra2 FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'questao'");
				$list_pagina_questoes = $conn->query($query);
				if ($list_pagina_questoes->num_rows > 0) {
					$template_id = 'pagina_questoes';
					if ($pagina_subtipo == 'simulado') {
						$template_titulo = $pagina_translated['Questões incluídas neste simulado'];
					} else {
						$template_titulo = $pagina_translated['Questões sobre este tópico'];
					}
					$template_botoes = "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_adicionar_simulado' class='text-secondary'><i class='fad fa-plus-square fa-fw'></i></a>";
					$template_conteudo = false;
					while ($list_pagina_questao = $list_pagina_questoes->fetch_assoc()) {
						$list_pagina_questao_pagina_id = $list_pagina_questao['extra2'];
						if ($list_pagina_questao_pagina_id == false) {
							$list_pagina_questao_id = $list_pagina_questao['elemento_id'];
							$list_pagina_questao_pagina_id = return_pagina_id($list_pagina_questao_id, 'questao');
						}
						$template_conteudo .= return_list_item($list_pagina_questao_pagina_id);
					}
					if ($template_conteudo != false) {
						$template_conteudo = list_wrap($template_conteudo);
						include 'templates/page_element.php';
					}
				}
			}

			if ($pagina_tipo == 'texto_apoio') {
				$template_id = 'conteudo_texto_apoio';
				$template_titulo = false;
				$template_conteudo = false;
				if ($pagina_texto_apoio_html != false) {
					$template_conteudo .= "
                                <h2 class='h2-responsive d-flex justify-content-center'>$pagina_texto_apoio_titulo</h2>
                                <div class='special-li'>
                                  $pagina_texto_apoio_enunciado_html
                                  $pagina_texto_apoio_html
                                </div>
                            ";
				} else {
					$template_conteudo .= "<p class='text-muted'><em>{$pagina_translated['O conteúdo deste texto de apoio ainda não foi adicionado.']}</em></p>";
				}
				include 'templates/page_element.php';

			}

			if ($pagina_tipo == 'questao') {

				if ($pagina_questao_enunciado_html != false) {

					if ($pagina_questao_texto_apoio == 1) {
						$template_id = 'questao_texto_apoio';
						$template_titulo = $pagina_translated['Texto de apoio'];
						if ($pagina_questao_texto_apoio_pagina_id != false) {
							$template_botoes = "
                                <a href='pagina.php?pagina_id=$pagina_questao_texto_apoio_pagina_id' title='{$pagina_translated['Página deste texto de apoio']}' class='text-secondary'><li class='fad fa-external-link-square fa-fw'></li></a>
                                ";
						}
						$template_conteudo = false;
						$template_conteudo .= "<h3 class='h3-responsive'>$pagina_questao_texto_apoio_titulo</h3>";
						if ($pagina_questao_texto_apoio_id == false) {
							$template_conteudo .= "<p class='text-muted'><em>{$pagina_translated['depende texto apoio mas']}</em></p>";
						} else {
							if ($pagina_questao_texto_apoio_html != false) {
								$template_conteudo .= "
                                        <div class='special-li'>
                                        $pagina_questao_texto_apoio_enunciado_html
                                        $pagina_questao_texto_apoio_html
                                        </div>
                                    ";
							} else {
								$template_conteudo .= "<p class='text-muted'><em>{$pagina_translated['O conteúdo deste texto de apoio ainda não foi adicionado. Você poderá fazê-lo']} <a href='pagina.php?pagina_id=$pagina_questao_texto_apoio_pagina_id'>{$pagina_translated['na página deste texto de apoio']}</a>.</em></p>";
							}
						}
						include 'templates/page_element.php';
					}

					$template_id = 'gabarito_questao';
					if (($pagina_questao_tipo == 1) || ($pagina_questao_tipo == 2)) {
						$gabarito = true;
						$template_botoes = "
                                    <span id='mostrar_gabarito' title='{$pagina_translated['Mostrar gabarito']}'>
                                        <a href='javascript:void(0);' class='text-primary'><i class='fad fa-eye fa-fw'></i></a>
                                    </span>
                                ";
						$template_titulo = $pagina_translated['Itens e gabarito'];
					} else {
						$template_botoes = false;
						$template_titulo = $pagina_translated['Enunciado'];
					}
					$template_conteudo = false;
					$template_conteudo .= "<div id='special_li'>$pagina_questao_enunciado_html</div>";
					$template_conteudo .= "<ul class='list-group'>";
					$mask_cor = 'list-group-item-light';
					if ($pagina_questao_item1_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item1_gabarito);
						$template_conteudo .= "
                                    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>{$pagina_translated['Item']} 1</strong></li>
                                ";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                        $pagina_questao_item1_html
                                    </li>";
					}
					if ($pagina_questao_item2_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item2_gabarito);
						$template_conteudo .= "
                                    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>{$pagina_translated['Item']} 2</strong></li>
                                ";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                        $pagina_questao_item2_html
                                    </li>";
					}
					if ($pagina_questao_item3_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item3_gabarito);
						$template_conteudo .= "
                                    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>{$pagina_translated['Item']} 3</strong></li>
                                ";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                        $pagina_questao_item3_html
                                    </li>";
					}
					if ($pagina_questao_item4_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item4_gabarito);
						$template_conteudo .= "
                                    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>{$pagina_translated['Item']} 4</strong></li>
                                ";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                        $pagina_questao_item4_html
                                    </li>";
					}
					if ($pagina_questao_item5_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item5_gabarito);
						$template_conteudo .= "
                                    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>{$pagina_translated['Item']} 5</strong></li>
                                ";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                        $pagina_questao_item5_html
                                    </li>";
					}

					$template_conteudo .= "</ul>";
					include 'templates/page_element.php';
				}

				$query = prepare_query("SELECT pagina_id FROM Paginas_elementos WHERE tipo = 'questao' AND elemento_id = $pagina_item_id");
				$usos_questao = $conn->query($query);

				if ($usos_questao->num_rows > 0) {
					$template_id = 'paginas_questao';
					$template_titulo = $pagina_translated['Páginas relacionadas'];
					$template_conteudo = false;
					while ($uso_questao = $usos_questao->fetch_assoc()) {
						$uso_questao_pagina_id = $uso_questao['pagina_id'];
						$template_conteudo .= return_list_item($uso_questao_pagina_id);
					}
					$template_conteudo = list_wrap($template_conteudo);
					include 'templates/page_element.php';
				}

			}

			if ($pagina_tipo == 'texto_apoio') {
				$template_id = 'paginas_texto_apoio';
				$template_titulo = $pagina_translated['Páginas relacionadas'];
				$template_conteudo = false;
				$usos_texto_apoio = $conn->query("SELECT id FROM sim_questoes WHERE texto_apoio_id = $pagina_item_id");
				while ($uso_texto_apoio = $usos_texto_apoio->fetch_assoc()) {
					$uso_texto_apoio_questao_id = $uso_texto_apoio['id'];
					$uso_texto_apoio_pagina_id = return_pagina_id($uso_texto_apoio_questao_id, 'questao');
					$template_conteudo .= return_list_item($uso_texto_apoio_pagina_id);
				}
				$template_conteudo = list_wrap($template_conteudo);
				include 'templates/page_element.php';
			}

			if ($pagina_tipo == 'elemento') {
			    $template_id = 'usos_elemento';
			    $template_titulo = $pagina_translated['Páginas relacionadas'];
			    $template_conteudo = false;
			    $usos_elemento = $conn->query("SELECT pagina_id FROM Paginas_elementos WHERE tipo = '$elemento_tipo' AND elemento_id = $pagina_item_id AND estado = 1");
			    if ($usos_elemento->num_rows > 0) {
			        while ($uso_elemento = $usos_elemento->fetch_assoc()) {
                        $template_conteudo .= return_list_item($uso_elemento['pagina_id'], false, false, false, false, false, false, false, false, true);
                    }
                }
			    if ($template_conteudo != false) {
			        $template_conteudo = list_wrap($template_conteudo);
			        include 'templates/page_element.php';
                }
            }

			if ($pagina_tipo == 'curso') {
				$query = prepare_query("SELECT DISTINCT pagina_id FROM (SELECT pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND curso_id = $pagina_curso_id AND pagina_tipo = 'topico' GROUP BY id ORDER BY id DESC) t");
				$paginas = $conn->query($query);
				if ($paginas->num_rows > 0) {

					echo "</div>";
					echo "<div id='coluna_direita' class='$coluna_classes pagina_coluna'>";
					$template_id = 'paginas_recentes';
					$template_titulo = $pagina_translated['Verbetes recentemente modificados'];
					$template_botoes = false;
					$template_conteudo = false;
					$template_conteudo_no_col = false;
					$template_conteudo .= "<ul class='list-group list-group-flush paginas_recentes_collapse collapse show'>";
					$count = 0;
					while ($pagina = $paginas->fetch_assoc()) {
						$topico_pagina_id = $pagina['pagina_id'];
						$count++;
						if ($count > 12) {
							break;
						}
						$template_conteudo .= return_list_item($topico_pagina_id, false, false, true, false);
					}
					unset($topico_pagina_id);
					$template_conteudo .= "</ul>";
					include 'templates/page_element.php';
					echo "</div>";

				}


			}

			echo "</div>";
		?>
		<?php
			$carregar_quill_anotacoes = false;
			$paginas_com_anotacoes = array('topico', 'pagina', 'elemento', 'secao', 'questao');
			$subtipos_sem_anotacoes = array('escritorio', 'plano');
			if ($user_id == false) {
				$carregar_quill_anotacoes = false;
			} else {
				if (in_array($pagina_tipo, $paginas_com_anotacoes)) {
					$carregar_quill_anotacoes = true;
				}
				if ($pagina_tipo == 'pagina') {
					if (in_array($pagina_subtipo, $subtipos_sem_anotacoes)) {
						$carregar_quill_anotacoes = false;
					} else {
                        $carregar_quill_anotacoes = true;
					}
				}
				if ($pagina_subtipo == 'modelo') {
					if ($pagina_compartilhamento == 'privado') {
						if ($pagina_user_id == $user_id) {
							$carregar_quill_anotacoes = true;
						}
					} else {
						switch ($modelo_do_usuario) {
							case 'added':
							case 'hidden':
								$carregar_quill_anotacoes = true;
								break;
							case false:
							default:
								$carregar_quill_anotacoes = false;
						}
					}
				}
			}
			if ($carregar_quill_anotacoes == true) {
				include 'pagina/coluna_direita_anotacoes.php';
			}
		?>
    </div>
</div>
<?php
	if ($pagina_subtipo == 'plano') {
		include 'pagina/planner.php';
	}

	if (!isset($anotacoes_existem)) {
		$anotacoes_existem = false;
	}
	if ($carregar_quill_anotacoes == true) {
		if ($anotacoes_existem == true) {
			echo "<a id='mostrar_coluna_direita' class='text-light rgba-black-strong rounded m-1 p-1' tabindex='-1' title='{$pagina_translated['Notas privadas']}'><i class='fad fa-pen-alt fa-fw' style='--fa-secondary-color: #4285f4; --fa-secondary-opacity: 1.0;'></i></a>";
		} else {
			echo "<a id='mostrar_coluna_direita' class='text-light rgba-black-strong rounded m-1 p-1' tabindex='-1' title='{$pagina_translated['Notas privadas']}'><i class='fad fa-pen-alt fa-fw'></i></a>";
		}
	}
?>

<?php

	include 'pagina/etiquetas.php';

	if ($pagina_tipo == 'topico') {
		$template_modal_div_id = 'modal_verbetes_relacionados';
		$template_modal_titulo = $pagina_translated['Navegação'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = $breadcrumbs;
		include 'templates/modal.php';
	}

	$carregar_controle_estado = true;
	$template_modal_div_id = 'modal_estado';
	$template_modal_titulo = $pagina_translated['Estado da página'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= wrapp($pagina_translated['Qual das categorias abaixo melhor descreve o estado atual desta página?']);
	$template_modal_body_conteudo .= "
        <input type='hidden' value='$pagina_estado' name='novo_estado_pagina' id='novo_estado_pagina'>
        <div class='row justify-content-around'>";

	$artefato_tipo = 'estado_rascunho';
	$artefato_titulo = $pagina_translated['Rascunho'];
	$fa_icone = 'fa-acorn';
	$fa_color = 'text-info';
	if ($pagina_estado == 1) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-cyan-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$artefato_tipo = 'estado_aceitavel';
	$artefato_titulo = $pagina_translated['Aceitável'];
	$fa_icone = 'fa-seedling';
	$fa_color = 'text-danger';
	if ($pagina_estado == 2) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-red-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$artefato_tipo = 'estado_desejavel';
	$artefato_titulo = $pagina_translated['Desejável'];
	$fa_icone = 'fa-leaf';
	$fa_color = 'text-success';
	if ($pagina_estado == 3) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-green-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$artefato_tipo = 'estado_excepcional';
	$artefato_titulo = $pagina_translated['Excepcional'];
	$fa_icone = 'fa-spa';
	$fa_color = 'text-warning';
	if ($pagina_estado == 4) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-orange-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$template_modal_body_conteudo .= "
		</div>
    ";
	include 'templates/modal.php';

	include 'pagina/youtube.php';

	if ($pagina_tipo == 'elemento') {
		include 'pagina/modals_elemento.php';
	}

	if (($carregar_secoes == true) && ($privilegio_edicao == true)) {
		$template_modal_div_id = 'modal_partes_form';
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$template_modal_titulo = $pagina_translated['Adicionar episódio'];
			} elseif ($elemento_subtipo == 'livro') {
				$template_modal_titulo = $pagina_translated['Adicionar capítulo'];
			}
		}
		if (!isset($template_modal_titulo)) {
			$template_modal_titulo = $pagina_translated['Adicionar seção'];
		}
		$template_modal_submit_name = 'trigger_nova_secao';
		$modal_scrollable = true;
		$template_modal_body_conteudo = false;
		$secoes_sem_texto = true;
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$template_modal_body_conteudo .= "
					<p>{$pagina_translated['podcast add episode number']}</p>
				";
				$secoes_sem_texto = false;
			} else {
				$secoes_sem_texto = false;
				$template_modal_body_conteudo .= "
		        	<p>{$pagina_translated['please care add chapter']}</p>
		        	<p>{$pagina_translated['section examples']}</p>
		        	<p>{$pagina_translated['order details']}</p>
	          	";
			}
		} else {
			$secoes_sem_texto = true;
		}
		if ($secoes_sem_texto == true) {
			$template_modal_body_conteudo .= "
				<p>{$pagina_translated['you can sections']}</p>
			";
		}
		unset($nova_secao_titulo);
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$nova_secao_titulo = $pagina_translated['episodio titulo'];
				$nova_secao_numero = $pagina_translated['episodio numero'];
			} elseif ($elemento_subtipo == 'livro') {
				$nova_secao_titulo = $pagina_translated['capitulo titulo'];
				$nova_secao_numero = $pagina_translated['capitulo numero'];
			}
		}
		if (!isset($nova_secao_titulo)) {
			$nova_secao_titulo = $pagina_translated['nova secao titulo'];
			$nova_secao_numero = $pagina_translated['nova secao posicao'];
		}
		$template_modal_body_conteudo .= "
          <div class='md-form mb-2'>
              <input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
              <label for='elemento_nova_secao'>$nova_secao_titulo</label>
          </div>
          <div class='md-form mb-2'>
              <input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
              <label for='elemento_nova_secao_ordem'>$nova_secao_numero</label>
          </div>
        ";

		$lista_de_secoes = false;
		if ($secoes->num_rows > 0) {
			$template_modal_body_conteudo .= "
		      <h3>{$pagina_translated['Seções registradas desta página']}:</h3>
		      <ul class='list-group list-group-flush'>
    		";
			while ($secao = $secoes->fetch_assoc()) {
				$secao_ordem = $secao['ordem'];
				$secao_pagina_id = $secao['secao_pagina_id'];
				$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light text-center p-0 m-0 border-0'>$secao_ordem</li>";
				$item_de_secao = return_list_item($secao_pagina_id);
				$template_modal_body_conteudo .= $item_de_secao;
				$lista_de_secoes .= $item_de_secao;

				/*$secao_info = return_pagina_info($secao_pagina_id);
				$secao_titulo = $secao_info[6];
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$secao_pagina_id'><li class='list-group-item list-group-item-action'>$secao_ordem: $secao_titulo</li></a>";*/
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
	}

	if ($privilegio_edicao == true) {
		include 'pagina/modal_add_elemento.php';
		include 'pagina/modal_adicionar_imagem.php';
		if (($pagina_tipo == 'topico') || ($pagina_subtipo == 'simulado')) {
			include 'pagina/modals_questoes.php';
		}
	}
	if ($modal_pagina_dados == true) {

		$template_modal_div_id = 'modal_pagina_dados';
		$template_modal_titulo = $pagina_translated['Alterar dados'];
		$template_modal_show_buttons = true;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
        <div class='md-form mb-2'>
            <input type='text' id='pagina_novo_titulo' name='pagina_novo_titulo'
                   class='form-control validate' value=\"$pagina_titulo\" required>
            <label data-error='inválido' data-success='válido'
                   for='pagina_novo_titulo'>{$pagina_translated['Novo título']}</label>
        </div>
        ";

		if (isset($secoes)) {
			if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id) && ($secoes->num_rows == 0) && ($pagina_tipo == 'pagina') && ($pagina_titulo != false) && ($pagina_subtipo != 'produto') && ($pagina_subtipo != 'simulado') && ($pagina_subtipo != 'modelo') && ($pagina_subtipo != 'plano')) {
				$modal_novo_curso = true;
				$template_modal_body_conteudo .= "<h3>{$pagina_translated['change page nature']}</h3>";
				$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
				$template_modal_body_conteudo .= "<span data-toggle='modal' data-target='#modal_pagina_dados'>";
				$template_modal_body_conteudo .= put_together_list_item('modal', '#modal_novo_curso', 'text-default', 'fad fa-graduation-cap', $pagina_translated['Transformar em página inicial de curso'], false, false, 'list-group-item-action');
				$template_modal_body_conteudo .= "</span>";
				if ($user_tipo == 'admin') {
					$load_change_into_model = true;
					$template_modal_body_conteudo .= put_together_list_item('link_button', 'change_into_model', 'text-secondary', 'fad fa-pen-nib', $pagina_translated['Change page modelo'], false, false, 'list-group-item-action');
				}
				$template_modal_body_conteudo .= "</ul>";
			}
		}
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_remover_elementos';
		$template_modal_titulo = $pagina_translated['Remover elementos'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;

		include 'templates/modal.php';

	}
	if ($privilegio_edicao == true) {
		if ($modal_novo_curso == true) {
			$template_modal_div_id = 'modal_novo_curso';
			$template_modal_titulo = $pagina_translated['Transformar em página inicial de curso'];
			$template_modal_submit_name = 'novo_curso';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
	            <p>{$pagina_translated['new course explanation']}</p>
	            <div class='md-form mb-2'>
	                <input type='text' id='novo_curso_sigla' name='novo_curso_sigla' class='form-control validade' required>
	                <label data-error='inválido' data-success='válido' for='pagina_novo_titulo'>{$pagina_translated['Sigla do novo curso']}</label>
                </div>
	        ";
			include 'templates/modal.php';
		}
	}
	if (($pagina_subtipo == 'produto') && ($carregar_produto_setup == true) && ($privilegio_edicao == true) && (isset($imagem_opcoes))) {
		$template_modal_div_id = 'modal_produto_nova_imagem';
		$template_modal_titulo = $pagina_translated['Determinar imagem para o cartão do produto'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <div class='md-form mb-2'>
				<p>{$pagina_translated['Selecione uma entre as imagens desta página para fazer parte do cartão de oferta deste produto na Loja Virtual:']}</p>
            	<select class='$select_classes' name='produto_nova_imagem'>
            	<option value='' disabled selected>{$pagina_translated['Selecione uma imagem:']}</option>
           ";
		foreach ($imagem_opcoes as $imagem_opcao) {
			$imagem_opcao_id = $imagem_opcao[0];
			$imagem_opcao_titulo = $imagem_opcao[1];
			$template_modal_body_conteudo .= "<option value='$imagem_opcao_id'>$imagem_opcao_titulo</option>";
		}
		$template_modal_body_conteudo .= "
            </select>
            </div>
           ";
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_produto_preco';
		$template_modal_titulo = $pagina_translated['Determinar preço deste produto'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<div class='md-form mb-2'>
				<p>{$pagina_translated['Escreva abaixo o preço deste produto em Reais brasileiros (BRL).']}</p>
				<input type='number' name='novo_produto_preco' id='novo_produto_preco' value='$produto_preco'>
			</div>
		";
		include 'templates/modal.php';

	}

	include 'templates/etiquetas_modal.php';

	if ($pagina_tipo == 'secao') {
		$template_modal_div_id = 'modal_paginas_relacionadas';
		$template_modal_titulo = $pagina_translated['Página e seções'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = $pre_fab_modal_secoes;
		include 'templates/modal.php';
	}

	if ($carregar_partes_elemento_modal == true) {
		$template_modal_div_id = 'modal_partes_elemento';
		$template_modal_titulo = $partes_titulo;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		if ($privilegio_edicao == true) {
			$template_modal_body_conteudo .= "<span data-toggle='modal' data-target='#modal_partes_elemento'>";
			$template_modal_body_conteudo .= put_together_list_item('modal', '#modal_partes_form', false, 'fad fa-plus-square', $pagina_translated['Adicionar seção'], false, false, 'list-group-item-success');
			$template_modal_body_conteudo .= "</span>";
		}
		$template_modal_body_conteudo .= $lista_de_secoes;
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}

	if ($pagina_tipo == 'texto') {
		include 'pagina/modals_texto.php';
	}

	if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {

		$template_modal_div_id = 'modal_compartilhar_pagina';
		$template_modal_titulo = $pagina_translated['Colaboração e acesso'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<form method='post' id='form_modal_compartilhar_pagina'>
			<h3>{$pagina_translated['Acesso']}</h3>
	    ";

		$radio_privado = false;
		$radio_ubwiki = false;
		$radio_internet = false;
		$radio_active = 'checked';
		if ($pagina_publicacao == 'privado') {
			$radio_privado = $radio_active;
		} elseif ($pagina_publicacao == 'ubwiki') {
			$radio_ubwiki = $radio_active;
			$esconder_botao_determinar_acesso = true;
		} elseif ($pagina_publicacao == 'internet') {
			$radio_internet = $radio_active;
		}

		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input radio_publicar_opcao' name='radio_publicar_opcao' id='checkbox_publicar_ubwiki' value='ubwiki' $radio_ubwiki>
				<label class='form-check-label' for='checkbox_publicar_ubwiki'>{$pagina_translated['Acesso livre.']}</label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input radio_publicar_opcao' name='radio_publicar_opcao' id='checkbox_publicar_privado' value='privado' $radio_privado>
				<label class='form-check-label' for='checkbox_publicar_privado'>{$pagina_translated['Seletivo.']} <span class='text-muted font-italic'>{$pagina_translated['Você determina quem tem acesso']}</span>.</label>
			</div>
			<div id='botao_determinar_acesso' class='row d-flex justify-content-center botao_determinar_acesso'>
				<span data-toggle='modal' data-target='#modal_compartilhar_pagina'><a data-toggle='modal' data-target='#modal_outorgar_acesso'><button class='$button_classes botao_determinar_acesso btn-info' type='button'>{$pagina_translated['Dar acesso']}</button></a></span>
			</div>
        ";
		if ($pagina_publicacao == 'privado') {
			if ($pagina_link == false) {
				$pagina_compartilhamento_por_link = true;
				$template_modal_body_conteudo .= "<ul class='list-group list-group-flush mt-3'>";
				$template_modal_body_conteudo .= put_together_list_item('link_button', 'permitir_acesso_por_link', false, 'fad fa-link', $pagina_translated['Permitir compartilhamento por link.'], false, 'fad fa-sync', 'list-group-item-info');
				$template_modal_body_conteudo .= "</ul>";
			} else {
				$template_modal_body_conteudo .= "
                <div class='md-form mt-3'>
                    <input id='endereco_share' type='text' class='form-control' value='https://www.ubwiki.com.br/ubwiki/pagina.php?pagina_id=$pagina_id&acs=$pagina_link' readonly>
                    <label for='endereco_share'>{$pagina_translated['URL de compartilhamento:']}</label>
                </div>
		    ";
			}
		}

		$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Colaboração']}</h3>";

		$radio_colaboracao_exclusiva = false;
		$radio_colaboracao_aberta = false;
		$radio_colaboracao_selecionada = false;
		$radio_colaboracao_active = 'checked';
		if ($pagina_colaboracao == 'exclusiva') {
			$radio_colaboracao_exclusiva = $radio_colaboracao_active;
		} elseif ($pagina_colaboracao == 'aberta') {
			$radio_colaboracao_aberta = $radio_colaboracao_active;
		} elseif ($pagina_colaboracao == 'selecionada') {
			$radio_colaboracao_selecionada = $radio_colaboracao_active;
		}

		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_aberta' value='aberta' $radio_colaboracao_aberta>
				<label class='form-check-label' for='colaboracao_aberta'>{$pagina_translated['Livre.']} <span class='text-muted'><em>{$pagina_translated['Todos os grupos e indivíduos com acesso a esta página poderão editá-la.']}</em></span></label>
			</div>
			";
		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_exclusiva' value='exclusiva' $radio_colaboracao_exclusiva>
				<label class='form-check-label' for='colaboracao_exclusiva'>{$pagina_translated['Autoral.']} <span class='text-muted'><em>{$pagina_translated['Apenas você poderá editar o conteúdo desta página.']}</em></span></label>
			</div>
			";

		/*
		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_selecionada' value='selecionada' $radio_colaboracao_selecionada>
				<label class='form-check-label' for='colaboracao_selecionada'>Seletiva. <span class='text-muted'><em>Apenas grupos e indivíduos selecionados poderão editar o conteúdo desta página.</em></span></label>
			</div>
			<div class='row d-flex justify-content-center botao_determinar_colaboracao'>
				<span data-toggle='modal' data-target='#modal_compartilhar_pagina'><a data-toggle='modal' data-target='#modal_determinar_colaboracao'><button class='$button_classes botao_determinar_colaboracao btn-info'>Adicionar colaboradores</button></a></span>
			</div>
		";*/

		$template_modal_body_conteudo .= "</form>";

		include 'templates/modal.php';

		$template_modal_div_id = 'modal_outorgar_acesso';
		$template_modal_titulo = 'Outorgar acesso';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;

		$template_modal_body_conteudo .= "
        <p class='detalhes_acesso'>{$pagina_translated['Adicione pessoas e grupos de estudo abaixo para que tenham acesso à sua página. Apenas você, como criador original desta página, poderá alterar suas opções de compartilhamento.']}</p>
        <span id='esconder_modal_compartilhar_pagina' data-toggle='modal' data-target='#modal_outorgar_acesso' class='row justify-content-center detalhes_acesso'>";

		$artefato_tipo = 'compartilhar_grupo';
		$artefato_titulo = $pagina_translated['study groups'];
		$artefato_link = false;
		$artefato_criacao = false;
		$fa_icone = 'fa-users';
		$fa_color = 'text-default';
		$fa_size = 'fa-3x';
		$artefato_col_limit = 'col-lg-4 col-md-5';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';

		$artefato_tipo = 'compartilhar_usuario';
		$artefato_titulo = $pagina_translated['Colega'];
		$artefato_link = false;
		$artefato_criacao = false;
		$fa_icone = 'fa-user-friends';
		$fa_color = 'text-info';
		$fa_size = 'fa-3x';
		$artefato_col_limit = 'col-lg-4 col-md-5';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';

		$template_modal_body_conteudo .= "</span>";
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_compartilhar_grupo';
		$template_modal_titulo = $pagina_translated['Dar acesso a grupos de estudos'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <h3>{$pagina_translated['Grupos de estudos de que você faz parte']}</h3>
			  ";
		if ($user_id != false) {
			$query = prepare_query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
			$grupos_do_usuario = $conn->query($query);
			if ($grupos_do_usuario->num_rows > 0) {
				$template_modal_body_conteudo .= "
                  <form method='post'>
                    <select name='compartilhar_grupo_id' class='$select_classes'>
                        <option value='' disabled selected>{$pagina_translated['Selecione o grupo de estudos']}</option>
                ";
				while ($grupo_do_usuario = $grupos_do_usuario->fetch_assoc()) {
					$grupo_do_usuario_id = $grupo_do_usuario['grupo_id'];
					$grupo_do_usuario_titulo = return_grupo_titulo_id($grupo_do_usuario_id);
					$template_modal_body_conteudo .= "<option value='$grupo_do_usuario_id'>$grupo_do_usuario_titulo</option>";
				}
				$template_modal_body_conteudo .= "
                    </select>
                    <div class='row justify-content-center'>
                        <button class='$button_classes mt-3' name='trigger_compartilhar_grupo'>{$pagina_translated['Compartilhar com grupo']}</button>
                    </div>
                  </form>
                ";
			} else {
				$template_modal_body_conteudo .= "<p class='text-muted'><em>{$pagina_translated['Você não faz parte de nenhum grupo de estudos. Visite seu escritório para participar.']}</em></p>";
			}
		}
		$query = prepare_query("SELECT recipiente_id FROM Compartilhamento WHERE item_id = $pagina_id AND compartilhamento = 'grupo'");
		$comp_grupos = $conn->query($query);
		if ($comp_grupos->num_rows > 0) {
			$template_modal_body_conteudo .= "<h3 class='mt-5'>{$pagina_translated['Grupos de estudos com acesso:']}</h3>";
			$template_modal_body_conteudo .= "<ul class='list-group'>";
			while ($comp_grupo = $comp_grupos->fetch_assoc()) {
				$comp_grupo_id = $comp_grupo['recipiente_id'];
				$comp_grupo_info = return_grupo_info($comp_grupo_id);
				$comp_grupo_titulo = $comp_grupo_info[1];
				$comp_grupo_pagina_id = $comp_grupo_info[3];
				$template_modal_body_conteudo .= "<a href='javascript:void(0)' class='mt-1 remover_acesso_grupo' value='$comp_grupo_id'><li class='list-group-item list-group-item-info list-group-item-action'>$comp_grupo_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';

		$bottom_compartilhar_usuario = true;

		$template_modal_div_id = 'modal_compartilhar_usuario';
		$template_modal_titulo = 'Colaborar com usuário da Ubwiki';
		$modal_scrollable = true;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p><strong>{$pagina_translated['Colaboradores adicionados abaixo poderão alterar o conteúdo de sua página.']}</strong></p>
		";
		$template_modal_body_conteudo .= "
			<p>{$pagina_translated['Pesquise o convidado por seu apelido:']}</p>
	        <div class='md-form'>
	        	<input type='text' class='form-control' id='apelido_convidado_compartilhamento' novo='apelido_convidado_compartilhamento'>
	        	<label for='apelido_convidado_compartilhamento'>{$pagina_translated['Apelido']}</label>
	        </div>
	        <div class='md-form my-1'>
	            <button type='button' id='trigger_buscar_convidado_compartilhamento' name='trigger_buscar_convidado_compartilhamento' class='$button_classes btn-sm m-0'>{$pagina_translated['Buscar']}</button>
            </div>
            <div id='convite_resultados_compartilhamento' class='row border p-2 rounded mt-4'>
			</div>
		";
		$template_modal_body_conteudo .= "
		    <p class='mt-3'>{$pagina_translated['Atualmente compartilhado com os usuários listados abaixo:']}</p>
		    <p><strong>{$pagina_translated['Pressione para remover.']}</strong></p>
		    <ul class='list-group list-group-flush'>
		";
		$query = prepare_query("SELECT recipiente_id FROM Compartilhamento WHERE estado = 1 AND item_id = $pagina_id AND compartilhamento = 'usuario'");
		$usuarios_comp = $conn->query($query);
		if ($usuarios_comp->num_rows > 0) {
			while ($usuario_comp = $usuarios_comp->fetch_assoc()) {
				$usuario_comp_id = $usuario_comp['recipiente_id'];
				$usuario_comp_avatar_info = return_avatar($usuario_comp_id);
				$usuario_comp_avatar_icone = $usuario_comp_avatar_info[0];
				$usuario_comp_avatar_cor = $usuario_comp_avatar_info[1];
				$usuario_comp_apelido = return_apelido_user_id($usuario_comp_id);
				$template_modal_body_conteudo .= "<a href='javascript:void(0)' class='remover_compartilhamento_usuario' value='$usuario_comp_id'><li class='list-group-item list-group-item-action mt-1 border-top'><span class='$usuario_comp_avatar_cor'><i class='fad $usuario_comp_avatar_icone fa-fw'></i></span> $usuario_comp_apelido</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';

	}
	if ($pagina_tipo == 'texto') {
		$template_modal_div_id = 'modal_add_reply';
		$template_modal_titulo = $pagina_translated['Escrever resposta'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p>{$pagina_translated['pressione resposta']}</p>
			<div class='row justify-content-center'>
				<a href='pagina.php?original_id=$pagina_id&resposta_id=new'><button class='$button_classes'>{$pagina_translated['Escrever resposta']}</button></a>
			</div>
		";
		$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Respostas a este texto']}</h3>";
		if ($respostas->num_rows > 0) {
			while ($resposta = $respostas->fetch_assoc()) {
				$resposta_pagina_id = $resposta['elemento_id'];
				$resposta_pagina_info = return_pagina_info($resposta_pagina_id);
				$resposta_pagina_titulo = $resposta_pagina_info[6];
				$resposta_user_id = $resposta_pagina_info[5];
				$resposta_avatar_info = return_avatar($resposta_user_id);
				$resposta_avatar_icone = $resposta_avatar_info[0];
				$resposta_avatar_cor = $resposta_avatar_info[1];
				$resposta_user_apelido = return_apelido_user_id($resposta_user_id);
				if ($resposta_pagina_titulo != false) {
					$template_modal_body_conteudo .= "
                        <li class='list-group-item d-flex justify-content-start'>
                            <a href='pagina.php?user_id=$resposta_user_id' class='$resposta_avatar_cor'>
                                <i class='fa $resposta_avatar_icone fa-fw'></i>
                                <span class='text-primary'>$resposta_user_apelido</span>
                            </a>:
                            \"<a href='pagina.php?pagina_id=$resposta_pagina_id'>$resposta_pagina_titulo</a>\"
                        </li>
                    ";
				}
			}
		} else {
			$template_modal_body_conteudo .= "<p><span class='text-muted'>{$pagina_translated['Não há respostas a este texto.']}</span></p>";
		}
		include 'templates/modal.php';
	}

	if ($carregar_adicionar_materia == true) {
		$template_modal_div_id = 'modal_add_materia';
		$template_modal_titulo = $pagina_translated['Adicionar matéria'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>{$pagina_translated['Pesquise a nova matéria abaixo']}</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_materias' id='buscar_materias' required>
				<label for='buscar_materias'>{$pagina_translated['Buscar matéria']}</label>
                <button type='button' class='$button_classes' id='trigger_buscar_materias'>{$pagina_translated['Buscar']}</button>
			</div>
			<div class='row border p-2' id='materias_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}

	if ($carregar_adicionar_topico == true) {
		$template_modal_div_id = 'modal_add_topico';
		$template_modal_titulo = $pagina_translated['Adicionar tópico'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>{$pagina_translated['Pesquise o novo tópico abaixo']}</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_topicos' id='buscar_topicos' required>
				<label for='buscar_topicos'>{$pagina_translated['Buscar tópico']}</label>
                <button type='button' class='$button_classes btn-info' id='trigger_buscar_topicos'>{$pagina_translated['Buscar']}</button>
			</div>
			<div class='row border p-2' id='topicos_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}

	if ($carregar_adicionar_subtopico == true) {
		$template_modal_div_id = 'modal_add_subtopico';
		$template_modal_titulo = $pagina_translated['Adicionar subtópico'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>{$pagina_translated['Pesquise o novo subtópico abaixo']}</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_subtopicos' id='buscar_subtopicos' required>
				<label for='buscar_topicos'>{$pagina_translated['Buscar subtópico']}</label>
				<button type='button' class='$button_classes' id='trigger_buscar_subtopicos'>{$pagina_translated['Buscar']}</button>
			</div>
			<div class='row border p-2' id='subtopicos_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}

	if ($carregar_convite == true) {

		if ($link_compartilhamento_tipo == false) {
			$link_compartilhamento_tipo = 'disabled';
			$link_compartilhamento_codigo = generateRandomString(8, 'integers');
			$query = prepare_query("INSERT INTO Paginas_elementos (estado, pagina_id, pagina_tipo, tipo, subtipo, extra, user_id) VALUES (1, $pagina_id, '$pagina_tipo', 'linkshare', '$link_compartilhamento_tipo', '$link_compartilhamento_codigo', $user_id)");
			$conn->query($query);
		}

		$link_compartilhamento_disabled = false;
		$link_compartilhamento_open = false;
		$link_compartilhamento_onetimer = false;

		switch ($link_compartilhamento_tipo) {
			case false:
				$link_compartilhamento_disabled = 'checked';
				break;
			case 'disabled':
				$link_compartilhamento_disabled = 'checked';
				$link_compartilhamento_codigo = generateRandomString(8, 'integers');
				break;
			case 'open':
				$link_compartilhamento_open = 'checked';
				break;
			case 'onetimer':
				$link_compartilhamento_onetimer = 'checked';
				break;
		}

		if (!isset($link_compartilhamento)) {
			$link_compartilhamento = "https://www.ubwiki.com.br/ubwiki/pagina.php?grupo_id=$pagina_item_id&cd=$link_compartilhamento_codigo";
		}

		$template_modal_div_id = 'modal_link_compartilhamento';
		$template_modal_titulo = $pagina_translated['Criar link de convite'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<input type='hidden' value='$link_compartilhamento_codigo' name='link_compartilhamento_codigo'>
			<div class='md-form'>
				<input id='link_compartilhamento' type='text' class='form-control' readonly value='$link_compartilhamento'>
				<label>{$pagina_translated['Link de compartilhamento']}</label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input' id='link_compartilhamento_livre' name='link_compartilhamento_tipo' value='open' $link_compartilhamento_open>
				<label class='form-check-label' for='link_compartilhamento_livre'>{$pagina_translated['Link não expira.']}</label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input' id='link_compartilhamento_unico' name='link_compartilhamento_tipo' value='onetimer' $link_compartilhamento_onetimer>
				<label class='form-check-label' for='link_compartilhamento_unico'>{$pagina_translated['Link utilizável apenas uma vez.']}</label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input' id='link_compartilhamento_disabled' name='link_compartilhamento_tipo' value='disabled' $link_compartilhamento_disabled>
				<label class='form-check-label' for='link_compartilhamento_disabled'>{$pagina_translated['Link desativado.']}</label>
			</div>
		";
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_convidar_ou_remover';
		$template_modal_titulo = $pagina_translated['Gerenciar membros'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;

		$template_modal_body_conteudo .= "<span class='row d-flex justify-content-around' data-target='#modal_convidar_ou_remover' data-toggle='modal'>";

		$artefato_titulo = $pagina_translated['Criar link de convite'];
		$fa_color = 'text-primary';
		$fa_icone = 'fa-link';
		$artefato_modal = '#modal_link_compartilhamento';
		$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';

		$artefato_titulo = $pagina_translated['Convidar novos membros'];
		$fa_color = 'text-success';
		$fa_icone = 'fa-user-plus';
		$artefato_modal = '#modal_novo_membro';
		$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';

		$artefato_titulo = $pagina_translated['Remover membros'];
		$fa_color = 'text-danger';
		$fa_icone = 'fa-user-minus';
		$artefato_modal = '#modal_remover_membro';
		$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';

		$template_modal_body_conteudo .= "</span>";

		include 'templates/modal.php';

		$template_modal_div_id = 'modal_novo_membro';
		$template_modal_titulo = $pagina_translated['Convidar novo membro'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <p>{$pagina_translated['Pesquise o convidado por seu apelido:']}</p>
	        <div class='md-form'>
	        	<input type='text' class='form-control' id='apelido_convidado' novo='apelido_convidado'>
	        	<label for='apelido_convidado'>{$pagina_translated['Apelido']}</label>
	        </div>
	        <div class='md-form my-1'>
	            <button type='button' id='trigger_buscar_convidado' name='trigger_buscar_convidado' class='$button_classes btn-sm m-0'>{$pagina_translated['Buscar']}</button>
            </div>
            <div id='convite_resultados' class='row border p-2'>
			</div>
	    ";
		include 'templates/modal.php';

		$carregar_remover_usuarios = true;
		$template_modal_div_id = 'modal_remover_membro';
		$template_modal_titulo = $pagina_translated['Remover membro deste grupo'];
		$modal_scrollable = true;
		$template_modal_body_conteudo = false;
		$template_modal_show_buttons = false;
		if ($membros->num_rows > 1) {
			$template_modal_body_conteudo .= "
				<p class='mt-3'>{$pagina_translated['Selecione um membro abaixo para removê-lo do grupo ou cancelar seu convite.']}</p>
				<ul class='list-group list-group-flush'>
				<input type='hidden' id='remover_membro_grupo_id' value='$pagina_item_id'>
			";
			mysqli_data_seek($membros, 0);
			while ($membro = $membros->fetch_assoc()) {
				$membro_user_id = $membro['membro_user_id'];
				if ($membro_user_id == $user_id) {
					continue;
				}
				$membro_estado = $membro['estado'];
				$membro_user_apelido = return_apelido_user_id($membro_user_id);
				$avatar_info = return_avatar($membro_user_id);
				$fa_icone = $avatar_info[0];
				$fa_color = $avatar_info[1];
				$template_modal_body_conteudo .= "
					<a href='javascript:void(0);' class='remover_membro_grupo' value='$membro_user_id'><li class='list-group-item list-group-item-action border-0 border-top'><span class='$fa_color'><i class='fad $fa_icone'></i></span> $membro_user_apelido</li></a>
				";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
	}

	if ($pagina_subtipo == 'produto') {
		$template_modal_div_id = 'modal_adicionar_carrinho';
		$template_modal_titulo = $pagina_translated['Adicionar este produto a seu carrinho'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <input type='hidden' value='$pagina_id' name='adicionar_produto_pagina_id'>
			<p>{$pagina_translated['Confirma adicionar este produto a seu carrinho?']}</p>
			<p>{$pagina_translated['Produto:']} $pagina_titulo</p>
			<p>{$pagina_translated['Autor:']} $produto_autor</p>
			<p>{$pagina_translated['Preço:']} $produto_preco</p>
		";
		include 'templates/modal.php';
	}

	if ($pagina_tipo == 'curso') {
		$template_modal_div_id = 'modal_busca';
		$template_modal_titulo = $pagina_translated['slogan'];
		$template_modal_body_conteudo = false;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo .= "
            <form id='searchform' action='' method='post'>
            <div id='searchDiv' class='mt-5'>
                <input id='barra_busca' list='searchlist' type='text' class='barra_busca w-100'
                       name='searchBar' rows='1' autocomplete='off' spellcheck='false'
                       placeholder='{$pagina_translated['Busca de páginas deste curso']}' required>
                <datalist id='searchlist'>
        ";
		$query = prepare_query("SELECT chave FROM Searchbar WHERE curso_id = '$pagina_curso_id' ORDER BY ordem");
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row['chave'];
				$template_modal_body_conteudo .= "<option>$chave</option>";
			}
		}
		$template_modal_body_conteudo .= "
                            </datalist>";
		$template_modal_body_conteudo .= "<input id='trigger_busca' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
		$template_modal_body_conteudo .= "
        </div>
        </form>";
		$template_modal_body_conteudo .= "
            <form id='searchform' action='' method='post'>
            <div id='searchDiv_geral' class='mt-5'>
                <input id='barra_busca_geral' type='text' class='barra_busca w-100' name='searchBar_geral' rows='1' autocomplete='off' spellcheck='false' placeholder='{$pagina_translated['Busca geral']}' required>";
		$template_modal_body_conteudo .= "<input id='trigger_busca_geral' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
		$template_modal_body_conteudo .= "
        	</div>
        </form>";
		include 'templates/modal.php';
	}

	if ($carregar_modal_wikipedia == true) {
		$template_modal_div_id = 'modal_vinculos_wikipedia';
		$template_modal_titulo = $pagina_translated['Verbetes da Wikipédia relacionados'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
		<p>{$pagina_translated['Pressione para carregar artigo da Wikipédia:']}</p>
		<ul class='list-group list-group-flush'>
	";
		while ($vinculo_wikipedia = $vinculos_wikipedia->fetch_assoc()) {
			$vinculo_wikipedia_elemento_id = $vinculo_wikipedia['elemento_id'];
			$vinculo_wikipedia_titulo = $vinculo_wikipedia['extra'];
			$template_modal_body_conteudo .= "<a class='list-group-item list-group-item-action mt-1 border-top' href='pagina.php?pagina_id=$pagina_id&wiki_id=$vinculo_wikipedia_elemento_id'>$vinculo_wikipedia_titulo</a>";
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}

	if ($carregar_modal_destruir_pagina == true) {
		$template_modal_div_id = 'modal_destruir_pagina';
		$template_modal_titulo = $pagina_translated['Destruir esta página'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p>{$pagina_translated['Pressione para destruir esta página. Esse ato não pode ser desfeito.']}</p>
			<form method='post'>
			  <div class='md-form d-flex justify-content-center'>
				  <button class='$button_classes_red' name='trigger_apagar_pagina' id='trigger_apagar_pagina'>{$pagina_translated['Destruir esta página']}</button>
			  </div>
			</form>
		";
		include 'templates/modal.php';
	}

	if ($pagina_tipo == 'texto_apoio') {
		$template_modal_div_id = 'modal_texto_apoio_dados';
		$template_modal_titulo = $pagina_translated['Dados deste texto de apoio'];
		$template_modal_form_id = 'form_texto_apoio_alterar_dados';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<ul class='list-grou mb-3 w-responsive m-auto pb-3'>
				<li class='list-group-item list-group-item-info d-flex justify-content-center'>{$pagina_translated['Dados fixos']}</li>
				<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Concurso']}:</strong> $pagina_texto_apoio_curso_titulo</li>
				<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Edição']}:</strong> $pagina_texto_apoio_edicao_ano</li>
				<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Etapa']}:</strong> $pagina_texto_apoio_etapa_titulo</li>
				<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Prova']}:</strong> $pagina_texto_apoio_prova_titulo</li>
		";
		if ($pagina_texto_apoio_origem == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Origem']}:</strong> {$pagina_translated['Oficial do concurso']}.</li>";
		} elseif ($pagina_texto_apoio_origem == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Origem']}:</strong> {$pagina_translated['Não-oficial']}.</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
		$template_modal_body_conteudo .= "
							<h3>Título</h3>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' value='$pagina_texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>{$pagina_translated['Título do texto de apoio']}</label>
                            </div>
						";

		$template_modal_form_id = 'form_novo_texto_apoio';
		$template_modal_body_conteudo .= "<h3 class='text-center'>{$pagina_translated['Enunciado']}:</h3>";
		$sim_quill_id = 'texto_apoio_enunciado';
		$sim_quill_form = include('templates/sim_quill.php');
		$template_modal_body_conteudo .= $sim_quill_form;

		$template_modal_body_conteudo .= "
			<h3 class='text-center mt-3'>{$pagina_translated['Texto de apoio']}</h3>
			<p>{$pagina_translated['Textos com linhas numeradas devem ser adicionados linha por linha, em uma lista numerada.']}</p>
	    ";
		$sim_quill_id = 'texto_apoio';
		$sim_quill_form = include('templates/sim_quill.php');
		$template_modal_body_conteudo .= $sim_quill_form;

		$template_modal_submit_name = 'novo_texto_apoio_trigger';
		include 'templates/modal.php';
	}

	if ($pagina_tipo == 'questao') {
		$template_modal_div_id = 'modal_questao_dados';
		$template_modal_titulo = $pagina_translated['Dados desta questão'];
		$template_modal_form_id = 'form_questao_alterar_dados';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <ul class='list-group mb-3 w-responsive m-auto pb-3'>
                <li class='list-group-item list-group-item-info d-flex justify-content-center'>{$pagina_translated['Dados fixos']}</li>
                <li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Concurso']}:</strong> $pagina_questao_curso_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Edição']}:</strong> $pagina_questao_edicao_ano.</li>
                <li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Etapa']}:</strong> $pagina_questao_etapa_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Prova']}:</strong> $pagina_questao_prova_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Matéria']}:</strong> <a href='pagina.php?pagina_id=$pagina_questao_materia'>$pagina_questao_materia_titulo</a></li>
        ";

		$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Número']}:</strong> $pagina_questao_numero.</li>";
		if ($pagina_questao_origem == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Origem']}:</strong> {$pagina_translated['Oficial do concurso']}.</li>";
		} elseif ($pagina_questao_origem == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Tipo:']}</strong> {$pagina_translated['Não-oficial']}.</li>";
		}
		if ($pagina_questao_texto_apoio == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Texto de apoio']}:</strong> {$pagina_translated['sim']}.</li>";
		} elseif ($pagina_questao_texto_apoio == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Texto de apoio']}:</strong> {$pagina_translated['não']}.</li>";
		}
		if ($pagina_questao_tipo == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Tipo:']}</strong> {$pagina_translated['certo errado']}</li>";
		} elseif ($pagina_questao_tipo == 2) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Tipo:']}</strong> {$pagina_translated['múltipla escolha']}</li>";
		} elseif ($pagina_questao_tipo == 3) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>{$pagina_translated['Tipo:']}</strong> {$pagina_translated['dissertativa']}.</li>";
		}
		$template_modal_body_conteudo .= "</ul>";

		if ($pagina_questao_texto_apoio_id == false) {
			$selected = 'selected';
		} else {
			$selected = false;
		}

		if ($pagina_questao_texto_apoio == 1) {
			$template_modal_body_conteudo .= "
					<h3>Texto de apoio</h3>
					<select class='$select_classes' name='nova_questao_texto_de_apoio_id'>
					<option value='' disabled $selected>{$pagina_translated['Selecione o texto de apoio:']}</option>";
			if ($pagina_questao_texto_apoio_id == false) {
				$template_modal_body_conteudo .= "
					<option value='novo'>{$pagina_translated['Texto de apoio não-registrado']}</option>
				";
			}
			$query = prepare_query("SELECT id, origem, titulo FROM sim_textos_apoio WHERE prova_id = $pagina_questao_prova_id");
			$textos_apoio = $conn->query($query);
			if ($textos_apoio->num_rows > 0) {
				$selected = false;
				while ($texto_apoio = $textos_apoio->fetch_assoc()) {
					$texto_apoio_id = $texto_apoio['id'];
					$texto_apoio_origem = $texto_apoio['origem'];
					if ($texto_apoio_origem == 1) {
						$texto_apoio_origem_string = '(oficial)';
					} elseif ($texto_apoio_origem == 0) {
						$texto_apoio_origem_string = '(não-oficial)';
					}
					if ($texto_apoio_id == $pagina_questao_texto_apoio_id) {
						$selected = 'selected';
					} else {
						false;
					}
					$texto_apoio_titulo = $texto_apoio['titulo'];
					$template_modal_body_conteudo .= "<option value='$texto_apoio_id' $selected>$texto_apoio_titulo $texto_apoio_origem_string</option>";
				}
			}
		}
		$template_modal_body_conteudo .= "</select>";

		$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Enunciado']}</h3>";
		$sim_quill_id = 'questao_enunciado';
		$sim_quill_form = include 'templates/sim_quill.php';
		$template_modal_body_conteudo .= $sim_quill_form;
		if (($pagina_questao_tipo == 1) || ($pagina_questao_tipo == 2)) {

			$item1_certo = false;
			$item1_errado = false;
			$item1_anulado = false;
			$item1_none = false;
			$item2_certo = false;
			$item2_errado = false;
			$item2_anulado = false;
			$item2_none = false;
			$item3_certo = false;
			$item3_errado = false;
			$item3_anulado = false;
			$item3_none = false;
			$item4_certo = false;
			$item4_errado = false;
			$item4_anulado = false;
			$item4_none = false;
			$item5_certo = false;
			$item5_errado = false;
			$item5_anulado = false;
			$item5_none = false;

			if ($pagina_questao_item1_gabarito == 1) {
				$item1_certo = 'selected';
			} elseif ($pagina_questao_item1_gabarito == 2) {
				$item1_errado = 'selected';
			} elseif ($pagina_questao_item1_gabarito == 3) {
				$item1_anulado = 'selected';
			} else {
				$item1_none = 'selected';
			}
			if ($pagina_questao_item2_gabarito == 1) {
				$item2_certo = 'selected';
			} elseif ($pagina_questao_item2_gabarito == 2) {
				$item2_errado = 'selected';
			} elseif ($pagina_questao_item2_gabarito == 3) {
				$item2_anulado = 'selected';
			} else {
				$item2_none = 'selected';
			}
			if ($pagina_questao_item3_gabarito == 1) {
				$item3_certo = 'selected';
			} elseif ($pagina_questao_item3_gabarito == 2) {
				$item3_errado = 'selected';
			} elseif ($pagina_questao_item3_gabarito == 3) {
				$item3_anulado = 'selected';
			} else {
				$item3_none = 'selected';
			}
			if ($pagina_questao_item4_gabarito == 1) {
				$item4_certo = 'selected';
			} elseif ($pagina_questao_item4_gabarito == 2) {
				$item4_errado = 'selected';
			} elseif ($pagina_questao_item4_gabarito == 3) {
				$item4_anulado = 'selected';
			} else {
				$item4_none = 'selected';
			}
			if ($pagina_questao_item5_gabarito == 1) {
				$item5_certo = 'selected';
			} elseif ($pagina_questao_item5_gabarito == 2) {
				$item5_errado = 'selected';
			} elseif ($pagina_questao_item5_gabarito == 3) {
				$item5_anulado = 'selected';
			} else {
				$item5_none = 'selected';
			}

			$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Item']} 1</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled $item1_none>{$pagina_translated['Selecione o gabarito do primeiro item']}</option>
                                <option value='1' $item1_certo>{$pagina_translated['Certo']}</option>
                                <option value='2' $item1_errado>{$pagina_translated['Errado']}</option>
                                <option value='3' $item1_anulado>{$pagina_translated['Anulado']}</option>
                            </select>
						";
			$sim_quill_id = 'questao_item1';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Item']} 2</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled $item2_none>{$pagina_translated['Selecione o gabarito do segundo item']}</option>
                                <option value='1' $item2_certo>{$pagina_translated['Certo']}</option>
                                <option value='2' $item2_errado>{$pagina_translated['Errado']}</option>
                                <option value='3' $item2_anulado>{$pagina_translated['Anulado']}</option>
                            </select>
						";
			$sim_quill_id = 'questao_item2';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Item']} 3</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled $item3_none>{$pagina_translated['Selecione o gabarito do terceiro item']}</option>
                                <option value='1' $item3_certo>{$pagina_translated['Certo']}</option>
                                <option value='2' $item3_errado>{$pagina_translated['Errado']}</option>
                                <option value='3' $item3_anulado>{$pagina_translated['Anulado']}</option>
                            </select>
						";
			$sim_quill_id = 'questao_item3';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Item']} 4</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled $item4_none>{$pagina_translated['Selecione o gabarito do quarto item']}</option>
                                <option value='1' $item4_certo>{$pagina_translated['Certo']}</option>
                                <option value='2' $item4_errado>{$pagina_translated['Errado']}</option>
                                <option value='3' $item4_anulado>{$pagina_translated['Anulado']}</option>
                            </select>
						";
			$sim_quill_id = 'questao_item4';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>{$pagina_translated['Item']} 5</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled $item5_none>{$pagina_translated['Selecione o gabarito do quinto item']}</option>
                                <option value='1' $item5_certo>{$pagina_translated['Certo']}</option>
                                <option value='2' $item5_errado>{$pagina_translated['Errado']}</option>
                                <option value='3' $item5_anulado>{$pagina_translated['Anulado']}</option>
                            </select>
						";
			$sim_quill_id = 'questao_item5';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
		}
		include 'templates/modal.php';
	}

	if ($pagina_subtipo == 'modelo') {
		$template_modal_div_id = 'modal_modelo_config';
		$template_modal_titulo = $pagina_translated['Configurar modelo'];
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
		    <p>Em poucas palavras, o método Benjamin Franklin funciona assim:</p>
		    <ol>
		        <li>Identifique um trecho cujo estilo você admira;</li>
		        <li>Separadamente, faça notas gerais e diretas de todas as informações e argumentos expressados no trecho;</li>
		        <li>Esconda o trecho por algum tempo, de algumas horas a alguns dias, até que seus detalhes desapareçam de sua memória;</li>
		        <li>Resgate suas anotações e escreva o melhor texto de que é capaz, exprimindo os mesmos pontos;</li>
		        <li>Analise as diferenças entre o trecho original e a sua versão.</li>
            </ol>
            <p>Como se percebe, é um método simples, que qualquer pessoa pode colocar em prática sem muita dificuldade. A plataforma “BFranklin” da Ubwiki apenas facilita o processo, registra seu progresso e permite que uma comunidade se desenvolva em torno do exercício.</p>
		";
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush' method='post'>";
		if (($pagina_user_id == $user_id) && ($pagina_compartilhamento == 'privado')) {
			$carregar_publicar_modelo = true;
			$template_modal_body_conteudo .= put_together_list_item('link_button', 'publicar_modelo', false, 'fad fa-pen-nib', $pagina_translated['Publicar modelo'], false, false, 'list-group-item-warning mt-1');
		}
		switch ($modelo_do_usuario) {
			case false:
				$template_modal_body_conteudo .= put_together_list_item('link_button', 'adicionar_escritorio_modelo', false, 'fad fa-pen-nib', $pagina_translated['Adicionar seus modelos'], false, false, 'list-group-item-success mt-1');
				$esconder_paragrafo_hidden = 'hidden';
				break;
			case 'hidden':
				$template_modal_body_conteudo .= put_together_list_item('link_button', 'list_item_mostrar_paragrafo', false, 'fad fa-eye', $pagina_translated['Modelo mostrar paragrafo'], false, 'fa-pen-nib', 'list-group-item-success mt-1 modelo_mostrar_paragrafo');
				$esconder_paragrafo_hidden = 'hidden';
				break;
			case 'added':
			default:
				$esconder_paragrafo_hidden = false;
		}
		$template_modal_body_conteudo .= put_together_list_item('link_button', 'list_item_esconder_paragrafo', false, 'fad fa-eye-slash', $pagina_translated['Modelo esconder paragrafo'], false, 'fa-pen-nib', "list-group-item-info mt-1 modelo_esconder_paragrafo $esconder_paragrafo_hidden");
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}

	//TODO: Terminar essa história de publicação de respostas.
	if (!isset($carregar_publicar_resposta)) {
		$carregar_publicar_resposta = false;
	}
	if ($carregar_publicar_resposta == true) {
		$template_modal_div_id = 'modal_publicar_resposta';
		$template_modal_titulo = $pagina_translated['Publicar sua resposta'];
		$template_modal_body_conteudo = false;
		$link_li = put_together_list_item('link', "anotacoes.php?pagina_id=$pagina_id", 'text-secondary', 'fad fa-comment-alt-edit', $pagina_translated['Ver anotações publicadas'], 'text-primary', 'fad fa-external-link');
		$template_modal_body_conteudo .= list_wrap($link_li);
		$template_modal_body_conteudo .= wrapp($pagina_translated['sobre publicar respostas']);
		$template_modal_body_conteudo .= wrapp($pagina_translated['sobre publicar respostas 2']);
		$template_modal_body_conteudo .= "
	        <input type='hidden' name='publicar_anonimamente_pagina_id' value='$pagina_id'>
	        <div class='form-check'>
	            <input type='checkbox' class='form-check-input' id='publicar_anonimamente' name='publicar_anonimamente'>
	            <label class='form-check-label' for='publicar_anonimamente'>{$pagina_translated['Publicar anonimamente.']}</label>
            </div>
	    ";
		include 'templates/modal.php';
	}

	if ($carregar_modal_correcao == true) {
		$loaded_correcao_form = true;
		$template_modal_div_id = 'modal_correcao';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		if ($texto_revisao_ativa == false) {
			$template_modal_titulo = $pagina_translated['Solicitar correção'];
			if ($pagina_tipo == 'texto') {
				$pagina_texto_wordcount = str_word_count($texto_verbete_text);
			} elseif ($pagina_tipo == 'topico') {
				$topico_texto_info = return_texto_info($topico_texto_id);
				$topico_verbete_text = $topico_texto_info[6];
				$pagina_texto_wordcount = str_word_count($topico_verbete_text);
			}
			$revision_price = calculate_review_price($pagina_texto_wordcount, 'simplified', 'no_grade', 'with_chat', 'enfase_forma', 'revisao_diplomata');
			if ($user_wallet >= $revision_price) {
				$button_disabled = false;
			} else {
				$button_disabled = 'disabled';
			}

			$template_modal_body_conteudo .= "
			<p>{$pagina_translated['revision_paragraph']}</p>
            <form id='review_form' method='post' class='border rounded mx-2 px-4 py-2'>
                <p class='mb-1 mt-2'><strong>{$pagina_translated['Revisor:']}</strong></p>
                <div class='form-check'>
                    <input type='radio' id='enfase_forma' name='reviewer_choice' value='enfase_forma' class='disable_submit form-check-input' checked>
                    <label for='enfase_forma' class='form-check-label'>{$pagina_translated['revisor diplomata']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' id='enfase_conteudo' name='reviewer_choice' value='enfase_conteudo' class='disable_submit form-check-input'>
                    <label for='enfase_conteudo' class='form-check-label'>{$pagina_translated['professor especialista']}</label>
                </div>
                <p class='mb-1 mt-2'><strong>{$pagina_translated['Extensão da revisão:']}</strong></p>
                <div class='form-check'>
                    <input type='radio' id='simplified' name='extension' value='simplified' class='disable_submit form-check-input' checked>
                    <label for='simplified' class='form-check-label'>{$pagina_translated['simplified review']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' id='detailed' name='extension' value='detailed' class='disable_submit form-check-input'>
                    <label for='detailed' class='form-check-label'>{$pagina_translated['detailed review']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' id='rewrite' name='extension' value='rewrite' class='disable_submit form-check-input'>
                    <label for='rewrite' class='form-check-label'>{$pagina_translated['full rewrite']}</label>
                </div>
                <p class='mb-1 mt-2'><strong>{$pagina_translated['Revisao diplomata']}</strong></p>
                <div class='form-check'>
                    <input type='checkbox' id='revisao_diplomata' name='revisao_diplomata' class='disable_submit form-check-input' checked>
                    <label for='revisao_diplomata' class='form-check-label'>{$pagina_translated['Revisao diplomata item']}</label>
                </div>
                <p class='mb-1 mt-2'><strong>{$pagina_translated['Incluir uma nota aproximada?']}</strong></p>
                <div class='form-check'>
                    <input type='checkbox' id='review_grade' name='review_grade' class='disable_submit form-check-input'>
                    <label for='review_grade' class='form-check-label'>{$pagina_translated['review grade']}</label>
                </div>
                <p class='mb-1 mt-2'><strong>{$pagina_translated['Incluir conversa com o revisor:']}</strong></p>
                <div class='form-check'>
                    <input type='radio' name='reviewer_chat' value='no_chat' id='no_chat' class='disable_submit form-check-input' checked>
                    <label for='no_chat' class='form-check-label'>{$pagina_translated['none']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' name='reviewer_chat' value='chat_20' id='chat_20' class='disable_submit form-check-input'>
                    <label for='chat_20' class='form-check-label'>{$pagina_translated['20 minutes']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' name='reviewer_chat' value='chat_40' id='chat_40' class='disable_submit form-check-input'>
                    <label for='chat_40' class='form-check-label'>{$pagina_translated['40 minutes']}</label>
                </div>
                <div class='form-check'>
                    <input type='radio' name='reviewer_chat' value='chat_60' id='chat_60' class='disable_submit form-check-input'>
                    <label for='chat_60' class='form-check-label'>{$pagina_translated['60 minutes']}</label>
                </div>
				<input type='hidden' name='order_review_pagina_id' value='$pagina_id'>
				<p class='mb-1 mt-2'><strong>{$pagina_translated['Inclua seus comentários:']}</strong></p>
				<div class='md-form'>
					<textarea class='md-textarea form-control' id='new_review_comments' name='new_review_comments' rows='3' $button_disabled required></textarea>
					<label for='new_review_comments'>{$pagina_translated['Seus comentários']}</label>
				</div>
                <ul class='list-group'>
                    <li class='list-group-item'><strong>{$pagina_translated['Word count:']}</strong> <span class='fontstack-mono' id='review_wordcount'>$pagina_texto_wordcount</span></li>
                    <li class='list-group-item list-group-item-warning'><strong>{$pagina_translated['Revision price:']}</strong> <span class='fontstack-mono' id='review_price'>$revision_price</span></li>
                    <li class='list-group-item d-flex justify-content-between'><span><strong>{$pagina_translated['Your credits:']}</strong> <span id='review_wallet' class='fontstack-mono'>$user_wallet</span></span><a href='escritorio.php?wllt=1' target='_blank'>{$pagina_translated['Buy more']} <i class='fad fa-external-link fa-fw'></i></a></li>
                </ul>
				<div class='row d-flex justify-content-center'>
				    <button type='button' class='$button_classes_info hidden' name='trigger_review_recalc' id='trigger_review_recalc'>{$pagina_translated['recalculate']}</button>
					<button type='submit' class='$button_classes' name='trigger_review_send' id='trigger_review_send' $button_disabled>{$pagina_translated['Place order']}</button>
				</div>
            </form>
    	    ";
		} else {
			$template_modal_titulo = $pagina_translated['Correção em andamento'];
			if ($user_revisor == true) {
				$template_modal_body_conteudo .= "
			        <form method='post'>
			            <div class='row d-flex justify-content-center'>
			                <button name='finalizar_correcao' value='$pagina_id' class='$button_classes_info'>{$pagina_translated['Finalizar correção']}</button>
                        </div>
			        </form>
			    ";
			} else {
				$template_modal_body_conteudo = $pagina_translated['Solicitação recebida'];
			}
		}
		include 'templates/modal.php';
	}

	include 'pagina/modal_notificacoes.php';

	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}

	include 'pagina/modal_languages.php';

?>

</body>

<?php

	$mdb_select = true;
	if ($pagina_tipo == 'curso') {
		include 'templates/searchbar.html';
	}
	if ($pagina_tipo == 'texto') {
		$sticky_toolbar = true;
		$quill_extra_buttons = false;
		if ($texto_user_id == $user_id) {
			if ($pagina_compartilhamento == 'privado') {
				$quill_extra_buttons = "<a id='apagar_anotacao' class='text-danger ql-formats' title='Destruir anotação' data-toggle='modal' data-target='#modal_apagar_anotacao' href='javascript:void(0);'><i class='fad fa-shredder fa-fw'></i></a>";
			}
		}
		$quill_extra_buttons = mysqli_real_escape_string($conn, $quill_extra_buttons);
	}
	if ($pagina_tipo != 'texto') {
		$sistema_etiquetas_elementos = true;
	}
	$sistema_etiquetas_topicos = true;
	$sticky_toolbar = true;
	include 'templates/html_bottom.php';
	/*	if ($pagina_tipo != 'texto') {
			include 'templates/footer.html';
		}*/

	if ($pagina_tipo == 'questao') {
		echo "
        <script type='text/javascript'>
            quill_questao_enunciado.setContents($pagina_questao_enunciado_content);
            quill_questao_item1.setContents($pagina_questao_item1_content);
            quill_questao_item2.setContents($pagina_questao_item2_content);
            quill_questao_item3.setContents($pagina_questao_item3_content);
            quill_questao_item4.setContents($pagina_questao_item4_content);
            quill_questao_item5.setContents($pagina_questao_item5_content);
        </script>
    ";
	} elseif ($pagina_tipo == 'texto_apoio') {
		echo "
        <script type='text/javascript'>
            quill_texto_apoio_enunciado.setContents($pagina_texto_apoio_enunciado_content);
            quill_texto_apoio.setContents($pagina_texto_apoio_content);
        </script>
    	";
	}

	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>
