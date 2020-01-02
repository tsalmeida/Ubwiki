<?php

	include 'engine.php';
	$nao_contar = false;
	if (!isset($_GET['pagina_id'])) {
		if (isset($_GET['topico_id'])) {
			$topico_id = $_GET['topico_id'];
			$pagina_id = return_pagina_id($topico_id, 'topico');
		} elseif (isset($_GET['elemento_id'])) {
			$elemento_id = $_GET['elemento_id'];
			$pagina_id = return_pagina_id($elemento_id, 'elemento');
		} elseif (isset($_GET['curso_id'])) {
			$curso_id = $_GET['curso_id'];
			$pagina_id = return_pagina_id($curso_id, 'curso');
		} elseif (isset($_GET['materia_id'])) {
			$materia_id = $_GET['materia_id'];
			$pagina_id = return_pagina_id($materia_id, 'materia');
		} elseif (isset($_GET['texto_id'])) {
			$texto_anotacao = false;
			$pagina_texto_id = $_GET['texto_id'];
			if ($pagina_texto_id == 'new') {
				$conn->query("INSERT INTO Textos (tipo, compartilhamento, page_id, user_id, verbete_html, verbete_text, verbete_content) VALUES ('anotacoes', 'privado', 0, $user_id, FALSE, FALSE, FALSE)");
				$pagina_texto_id = $conn->insert_id;
				header("Location:pagina.php?texto_id=$pagina_texto_id");
				exit();
			}
			$pagina_id = return_pagina_id($pagina_texto_id, 'texto');
		} elseif (isset($_GET['grupo_id'])) {
			$grupo_id = $_GET['grupo_id'];
			$pagina_id = return_pagina_id($grupo_id, 'grupo');
		} elseif (isset($_GET['user_id'])) {
			$escritorio_user_id = $_GET['user_id'];
			$escritorio_user_apelido = return_apelido_user_id($escritorio_user_id);
			if ($escritorio_user_apelido == false) {
				header("Location:pagina.php?pagina_id=6");
				exit();
			}
			$escritorio_id = return_escritorio_id($escritorio_user_id);
			$pagina_id = $escritorio_id;
			header("Location:pagina.php?pagina_id=$escritorio_id");
			exit();
		} else {
			header('Location:pagina.php?pagina_id=4');
			exit();
		}
	} else {
		$pagina_id = $_GET['pagina_id'];
		if ($pagina_id == 'new') {
			$conn->query("INSERT INTO Paginas (tipo, compartilhamento, user_id) VALUES ('pagina', 'privado', $user_id)");
			$pagina_id = $conn->insert_id;
			header("Location:pagina.php?pagina_id=$pagina_id");
			exit();
		}
	}

	$pagina_info = return_pagina_info($pagina_id);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = $pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = $pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = $pagina_info[5];
		$pagina_titulo = $pagina_info[6];
		$pagina_etiqueta_id = $pagina_info[7];
	} else {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}

	if ($pagina_compartilhamento == 'privado') {
		if ($pagina_user_id != $user_id) {
			$check_compartilhamento = return_compartilhamento($pagina_id, $user_id);
			if ($check_compartilhamento == false) {
				header('Location:pagina.php?pagina_id=4');
				exit();
			}
		}
	}

	if (isset($pagina_texto_id)) {
		$pagina_tipo = 'texto';
	}

	if ($pagina_tipo == 'topico') {
		$topico_id = $pagina_item_id;
		$topico_curso_id = return_curso_id_topico($topico_id);
		$topico_curso_sigla = return_curso_sigla($topico_curso_id);
	} elseif ($pagina_tipo == 'materia') {
		$materia_id = $pagina_item_id;
		$materia_curso_id = return_curso_id_materia($materia_id);
		$materia_curso_sigla = return_curso_sigla($materia_curso_id);
	} elseif ($pagina_tipo == 'elemento') {
		$elemento_id = $pagina_item_id;
	} elseif ($pagina_tipo == 'grupo') {
		$grupo_id = $pagina_item_id;
	} elseif ($pagina_tipo == 'texto') {
		$pagina_texto_id = $pagina_item_id;
	} elseif ($pagina_tipo == 'secao') {
		$pagina_original_info = return_pagina_info($pagina_item_id);
		$pagina_original_compartilhamento = $pagina_original_info[4];
		$pagina_compartilhamento = $pagina_original_compartilhamento;
		$pagina_original_user_id = $pagina_original_info[5];
		if (($pagina_original_user_id != $user_id) && ($pagina_original_compartilhamento == 'privado')) {
			$check_compartilhamento = return_compartilhamento($pagina_item_id, $pagina_original_user_id);
			if ($check_compartilhamento == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
		}
	}

	if ($pagina_tipo == 'curso') {
		$curso_sigla = return_curso_sigla($curso_id);
		$curso_titulo = return_curso_titulo_id($curso_id);
	} elseif ($pagina_tipo == 'materia') {
		$materia_titulo = return_materia_titulo_id($materia_id);
		$materia_curso_id = return_curso_id_materia($materia_id);
		$materia_curso_sigla = return_curso_sigla($curso_id);
	} elseif ($pagina_tipo == 'texto') {
		$texto_info = return_texto_info($pagina_texto_id);
		$texto_curso_id = $texto_info[0];
		$texto_tipo = $texto_info[1];
		$texto_titulo = $texto_info[2];
		$texto_page_id = $texto_info[3];
		$texto_criacao = $texto_info[4];
		$texto_verbete_html = $texto_info[5];
		$texto_user_id = $texto_info[8];
		$texto_pagina_id = $texto_info[9];
		$texto_compartilhamento = $texto_info[11];
		$texto_texto_pagina_id = $texto_info[12];
		$pagina_id = $texto_texto_pagina_id;
		if ((strpos($texto_tipo, 'anotac') !== false) || ($texto_tipo == 'verbete_user')) {
			$texto_anotacao = true;
			if (($texto_compartilhamento != false) && ($texto_user_id != $user_id)) {
				$comps = $conn->query("SELECT recipiente_id, compartilhamento FROM Compartilhamento WHERE item_tipo = 'texto' AND item_id = $pagina_id");
				if ($comps->num_rows > 0) {
					while ($comp = $comps->fetch_assoc()) {
						$item_comp_compartilhamento = $comp['compartilhamento'];
						if ($item_comp_compartilhamento == 'grupo') {
							$item_grupo_id = $comp['recipiente_id'];
							$check_membro_grupo = check_membro_grupo($user_id, $item_grupo_id);
							if ($check_membro_grupo == false) {
								header('Location:pagina.php?pagina_id=4');
								exit();
							}
						} elseif ($item_comp_compartilhamento == 'usuario') {
							$item_usuario_id = $comp['recipiente_id'];
							if ($item_usuario_id != $user_id) {
								header('Location:pagina.php?pagina_id=4');
								exit();
							}
						}
					}
				} else {
					header('Location:pagina.php?pagina_id=3');
					exit();
				}
			}
		}
		if (isset($_POST['destruir_anotacao'])) {
			$conn->query("DELETE FROM Textos WHERE id = $texto_id");
			header('Location:pagina.php?pagina_id=5');
			exit();
		}
		if ($texto_page_id === 0) {
			$texto_editar_titulo = true;
		}
	} elseif ($pagina_tipo == 'grupo') {
		$check_membro = check_membro_grupo($user_id, $grupo_id);
		if ($check_membro == false) {
			header('Location:pagina.php?pagina_id=3');
			exit();
		}
	}

	if ($pagina_tipo == 'elemento') {
		include 'pagina/isset_elemento.php';
	}

	if ($pagina_tipo == 'topico') {
		$topicos = $conn->query("SELECT estado_pagina, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE curso_id = $topico_curso_id AND id = $topico_id");
		if ($topicos->num_rows > 0) {
			while ($topico = $topicos->fetch_assoc()) {
				$topico_materia_id = $topico['materia_id'];
				$topico_nivel = $topico['nivel'];
				$topico_ordem = $topico['ordem'];
				$topico_nivel1 = $topico['nivel1'];
				$topico_nivel2 = $topico['nivel2'];
				$topico_nivel3 = $topico['nivel3'];
				$topico_nivel4 = $topico['nivel4'];
				$topico_nivel5 = $topico['nivel5'];
				$topico_materia_titulo = return_materia_titulo_id($topico_materia_id);
				if ($topico_nivel == 2) {
					$topico_titulo = $topico_nivel2;
				} elseif ($topico_nivel == 1) {
					$topico_titulo = $topico_nivel1;
				} elseif ($topico_nivel == 3) {
					$topico_titulo = $topico_nivel3;
				} elseif ($topico_nivel == 4) {
					$topico_titulo = $topico_nivel4;
				} elseif ($topico_nivel == 5) {
					$topico_titulo = $topico_nivel5;
				}
			}
		}
	} elseif ($pagina_tipo == 'elemento') {
		include 'pagina/queries_elemento.php';
	}

	include 'pagina/shared_issets.php';

	if (($pagina_tipo == 'elemento') || ($pagina_tipo == 'pagina')) {
		if (isset($_POST['trigger_nova_secao'])) {
			$nova_secao_titulo = $_POST['elemento_nova_secao'];
			$nova_secao_titulo = mysqli_real_escape_string($conn, $nova_secao_titulo);
			$nova_secao_ordem = (int)$_POST['elemento_nova_secao_ordem'];
			$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($pagina_id, 'secao', 'igual à página original', $user_id)");
			$nova_pagina_id = $conn->insert_id;
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_pagina_id, 'secao', 'titulo', '$nova_secao_titulo', $user_id)");
			$conn->query("INSERT INTO Secoes (ordem, user_id, pagina_id, secao_pagina_id) VALUES ($nova_secao_ordem, $user_id, $pagina_id, $nova_pagina_id)");
			if ($pagina_tipo == 'elemento') {
				$nova_etiqueta_titulo = "$elemento_titulo // $nova_secao_titulo";
				$nova_etiqueta_titulo = mysqli_real_escape_string($conn, $nova_etiqueta_titulo);
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$nova_etiqueta_titulo', $user_id)");
				$nova_etiqueta_id = $conn->insert_id;
				$conn->query("UPDATE Paginas SET etiqueta_id = $nova_etiqueta_id WHERE id = $nova_pagina_id");
			}
			$nao_contar = true;
		}
	}

	if ($pagina_tipo != 'sistema') {
		$pagina_bookmark = false;
		$bookmarks = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND pagina_id = $pagina_id AND active = 1 ORDER BY id DESC");
		if ($bookmarks->num_rows > 0) {
			while ($bookmark = $bookmarks->fetch_assoc()) {
				$pagina_bookmark = $bookmark['bookmark'];
				break;
			}
		}

		$estado_estudo = false;
		$estudos = $conn->query("SELECT estado FROM Completed WHERE user_id = $user_id AND pagina_id = $pagina_id AND active = 1 ORDER BY id DESC");
		if ($estudos->num_rows > 0) {
			while ($estado = $estudos->fetch_assoc()) {
				$estado_estudo = $estado['estado'];
				break;
			}
		}
	} else {
		$pagina_bookmark = false;
		$estado_estudo = false;
	}
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	if ($user_id != false) {
		if ($nao_contar == false) {
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia')) {
				$visualizacao_extra = $curso_id;
			} elseif ($pagina_tipo == 'elemento') {
				$visualizacao_extra = $elemento_tipo;
			} elseif ($pagina_tipo == 'texto') {
				$visualizacao_extra = $pagina_texto_id;
			} else {
				$visualizacao_extra = "NULL";
			}
			$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra, extra2) VALUES ($user_id, $pagina_id, '$pagina_tipo', '$visualizacao_extra', 'pagina')");
		}
	}

	if (isset($_POST['compartilhar_grupo_id'])) {
		$compartilhar_grupo_id = $_POST['compartilhar_grupo_id'];
		$conn->query("INSERT INTO Compartilhamento (user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'grupo', $compartilhar_grupo_id)");
	}

?>
<body class="carrara">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row justify-content-between">
        <div class='py-2 text-left col'>
					<?php
						if (($pagina_tipo != 'sistema') && ($pagina_tipo != 'texto') && ($pagina_compartilhamento != 'escritorio')) {
							echo "<span id='add_elements' class='mx-1' title='' data-toggle='modal' data-target='#modal_add_elementos'><a href='javascript:void(0)' title='Adicionar elemento'><i     class='fad fa-2x fa-plus-circle fa-fw'></i></a></span>";
						}
						if ($pagina_tipo == 'elemento') {
							echo "<span id='elemento_dados' class='mx-1'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_dados_elemento' class='text-info'><i class='fad fa-info-circle fa-fw fa-2x'></i></a></span>";
						} elseif ((($pagina_tipo == 'sistema') && ($user_tipo == 'admin')) || (($pagina_tipo == 'pagina') && ($pagina_user_id == $user_id)) || (($pagina_tipo == 'texto') && ($pagina_user_id = $user_id) && ($texto_page_id == 0))) {
							echo "<span id='pagina_dados' class='mx-1'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_pagina_dados' class='text-info'><i class='fad fa-info-circle fa-fw fa-2x'></i></a></span>";
						}
					?>
        </div>
        <div class="py-2 text-center col">
					<?php
						if ($pagina_tipo == 'topico') {
							include 'templates/verbetes_relacionados.php';
							if ($topico_anterior != false) {
								$topico_anterior_link = "pagina.php?topico_id=$topico_anterior";
								echo "<span id='verbete_anterior' class='mx-1' title='Verbete anterior'><a href='$topico_anterior_link'><i class='fal fa-arrow-left fa-fw'></i></a></span>";
							}
							echo "<span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='modal' data-target='#modal_verbetes_relacionados'><a href='javascript:void(0);'><i class='fal fa-project-diagram fa-fw'></i></a></span>";
							if ($topico_proximo != false) {
								$topico_proximo_link = "pagina.php?topico_id=$topico_proximo";
								echo "<span id='verbete_proximo' class='mx-1' title='Próximo verbete'><a href='$topico_proximo_link'><i class='fal fa-arrow-right fa-fw'></i></a></span>";
							}
						} elseif ($pagina_tipo == 'secao') {
							echo "<span id='secoes' class='mx-1' title='Página e seções' data-toggle='modal' data-target='#modal_paginas_relacionadas'><a href='javascript:void(0);'><i class='fal fa-project-diagram fa-fw'></i></a></span>";
						}
					?>
        </div>
        <div class='py-2 text-right col'>
					<?php
						if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {
							echo "
	                          <span id='compartilhar_anotacao' class='ml-1' title='Editar compartilhamento desta anotação'
                                 data-toggle='modal' data-target='#modal_compartilhar_anotacao'>
                                <a href='javascript:void(0);'>
                                    <i class='fal fa-users fa-fw'></i>
                                </a>
                              </span>
	                        ";
						}
					?>
            <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
	                    if (($user_id != false) && ($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
		                    $comments = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE pagina_id = $pagina_id");
		                    if ($comments->num_rows == 0) {
			                    echo "
                                <a href='javascript:void(0);'>
                                    <i class='fal fa-comments-alt fa-fw'></i>
                                </a>
                            ";
		                    } else {
			                    echo "
                                <a href='javascript:void(0);'>
		                            <span class='text-secondary'>
                                        <i class='fas fa-comments-alt fa-fw'></i>
                                    </span>
                                </a>
		                    ";
		                    }
	                    }
                    ?>
                </span>
					<?php
						if (($user_id != false) && ($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
							echo "
                              <span id='adicionar_etiqueta' class='ml-1' title='Adicionar etiqueta' data-toggle='modal'
                                    data-target='#modal_gerenciar_etiquetas'>
                                  <a href='javascript:void(0);'>
                                      <i class='fal fa-tags fa-fw'></i>
                                  </a>
                              </span>
                            ";
							if ($estado_estudo == false) {
								echo "
                              <span id='add_completed' class='ml-1' title='Estudo completo' value='$pagina_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
                              <span id='remove_completed' class='ml-1 collapse' title='Desmarcar como completo' value='$pagina_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
                            ";
							} else {
								echo "
                              <span id='add_completed' class='ml-1 collapse' title='Estudo completo' value='$pagina_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
                              <span id='remove_completed' class='ml-1' title='Desmarcar como completo' value='$pagina_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
                            ";
							}
							if ($pagina_bookmark == false) {
								echo "
                              <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$pagina_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
                              <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$pagina_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
                            ";
							} else {
								echo "
                              <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$pagina_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
                              <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$pagina_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
                            ";
							}
							$estado_cor = false;
							$estado_icone = return_estado_icone($pagina_estado, 'pagina');
							if ($pagina_estado > 3) {
								$estado_cor = 'text-warning';
							} else {
								$estado_cor = 'text-info';
							}
							echo "
                                <span id='change_estado_pagina' class='ml-1' title='Estado da página' data-toggle='modal' data-target='#modal_estado'><a href='javascript:void(0);'><span class='$estado_cor'><i class='$estado_icone fa-fw'></i></span></a></span>
                            ";
						}
					?>
        </div>
    </div>
</div>
<div class="container-fluid">
	<?php
		$template_titulo_context = true;
		if ($pagina_tipo == 'topico') {
			$template_titulo = $topico_titulo;
			$template_subtitulo = "$topico_materia_titulo / $topico_curso_sigla";
		} elseif ($pagina_tipo == 'elemento') {
			$template_titulo = $elemento_titulo;
			$template_subtitulo = $elemento_autor;
		} elseif ($pagina_tipo == 'curso') {
			$template_titulo = $curso_titulo;
			$template_subtitulo = 'Curso';
		} elseif ($pagina_tipo == 'materia') {
			$template_titulo = $materia_titulo;
			$template_subtitulo = "$curso_sigla";
		} elseif ($pagina_tipo == 'texto') {
			if ($texto_page_id != false) {
				$template_titulo = return_pagina_titulo($texto_pagina_id);
			}
			$template_subtitulo = false;
			if ($texto_page_id == 0) {
				if ($texto_titulo != false) {
					$template_titulo = $texto_titulo;
				} else {
					$template_titulo = 'Texto sem título';
				}
				$template_subtitulo = 'Texto privado';
			}
		} elseif ($pagina_tipo == 'sistema') {
			$pagina_titulo = return_pagina_titulo($pagina_id);
			$template_titulo = $pagina_titulo;
		} elseif ($pagina_tipo == 'pagina') {
			$pagina_titulo = return_pagina_titulo($pagina_id);
			if ($pagina_titulo == false) {
				$template_titulo = 'Página sem título';
			} else {
				$template_titulo = $pagina_titulo;
			}
			if ($pagina_compartilhamento == 'privado') {
				$template_subtitulo = 'Página privada';
			} elseif ($pagina_compartilhamento == 'publico') {
				$template_subtitulo = 'Página pública';
			} elseif ($pagina_compartilhamento == 'escritorio') {
				$pagina_user_apelido = return_apelido_user_id($pagina_user_id);
				$template_subtitulo = "Escritório de $pagina_user_apelido";
			} else {
				$template_subtitulo = $pagina_compartilhamento;
			}
		} elseif ($pagina_tipo == 'secao') {
			$template_titulo = $pagina_titulo;
			$paginal_original_info = return_pagina_info($pagina_item_id);
			$pagina_original_titulo = $pagina_original_info[6];
			$pagina_original_compartilhamento = $pagina_original_info[4];
			$template_subtitulo = "Seção de \"$pagina_original_titulo\"";
			if ($pagina_original_compartilhamento == 'privado') {
				$template_subtitulo = "$template_subtitulo (Página e seções privadas)";
			}
		} elseif ($pagina_tipo == 'grupo') {
			$template_titulo = return_grupo_titulo_id($grupo_id);
			$template_subtitulo = 'Grupo de estudos';
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row justify-content-around <?php echo $row_classes; ?>">
        <div id="coluna_unica" class="col-lg-10 col-md-12 pagina_coluna">
					<?php
						if ($pagina_tipo == 'grupo') {
							include 'pagina/grupo.php';
						} elseif ($pagina_tipo == 'curso') {
							include 'pagina/curso.php';

						} elseif ($pagina_tipo == 'materia') {
							include 'pagina/materia.php';
						} elseif ($pagina_tipo == 'texto') {
							$template_id = $texto_tipo;
							$template_titulo = false;
							$template_conteudo_no_col = true;
							$template_quill_initial_state = 'edicao';
							$template_quill_page_id = $texto_page_id;
							$template_quill_pagina_id = $pagina_id;
							$template_quill_pagina_de_edicao = true;
							$template_quill_botoes = false;
							$template_botoes_padrao = false;
							$template_conteudo = include 'templates/template_quill.php';
							include 'templates/page_element.php';
						}
					?>
        </div>
			<?php
				echo "<div id='coluna_esquerda' class='$coluna_classes pagina_coluna'>";

				if ($pagina_tipo == 'elemento') {

					if ($elemento_tipo == 'imagem') {
						$template_id = 'imagem_div';
						$template_titulo = false;
						$template_botoes = false;
						$template_conteudo = "<a href='../imagens/verbetes/$elemento_arquivo' target='_blank'><img class='imagem_pagina border' src='../imagens/verbetes/$elemento_arquivo'></img></a>";
						include 'templates/page_element.php';
					} elseif (($elemento_tipo == 'video') && ($elemento_iframe != false)) {
						$template_div = 'video_div';
						$template_titulo = false;
						$template_botoes = false;
						$template_conteudo = $elemento_iframe;
						$template_conteudo_class = 'text-center';
						include 'templates/page_element.php';
					}
				}
				if ($pagina_tipo != 'texto') {
					$template_id = 'verbete';
					$template_titulo = false;
					if ((($pagina_tipo == 'sistema') && ($user_tipo != 'admin')) || (($pagina_tipo == 'escritorio') || ($pagina_user_id != $user_id))) {
						$template_quill_initial_state = 'leitura';
						$template_quill_botoes = false;
						$template_botoes_padrao = false;
					}
					$template_conteudo = include 'templates/template_quill.php';
					include 'templates/page_element.php';
					if ((($pagina_tipo == 'elemento') || ($pagina_tipo == 'pagina')) && ($pagina_compartilhamento != 'escritorio')) {
						include 'pagina/secoes_pagina.php';
					}

					include 'pagina/leiamais.php';

					include 'pagina/videos.php';

					include 'pagina/imagens.php';

					include 'pagina/audio.php';

				}

				echo "</div>";
			?>
			<?php
				if (($pagina_tipo != 'sistema') && ($pagina_tipo != 'texto') && ($pagina_compartilhamento != 'escritorio')) {

					include 'pagina/coluna_direita_anotacoes.php';

				}
			?>
    </div>
</div>
<?php
	echo "<button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
            class='fas fa-pen-alt fa-fw'></i></button>";
?>
</div>

<?php
	if ($pagina_tipo == 'topico') {
		$template_modal_div_id = 'modal_verbetes_relacionados';
		$template_modal_titulo = 'Verbetes relacionados';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = $breadcrumbs;
		include 'templates/modal.php';
	}

	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;

	if (isset($comments)) {
		if ($comments->num_rows > 0) {
			$template_modal_body_conteudo .= "<ul class='list-group'>";
			while ($comment = $comments->fetch_assoc()) {
				$timestamp_comentario = $comment['timestamp'];
				$texto_comentario = $comment['comentario'];
				$autor_comentario_id = $comment['user_id'];
				$autor_comentario_apelido = return_apelido_user_id($autor_comentario_id);
				$autor_comentario_avatar_info = return_avatar($autor_comentario_id);
				$autor_comentario_avatar = $autor_comentario_avatar_info[0];
				$autor_comentario_cor = $autor_comentario_avatar_info[1];

				$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p></span><strong><a href='pagina.php?user_id=$autor_comentario_id' target='_blank'><span class='$autor_comentario_cor'><i class='fad $autor_comentario_avatar fa-fw fa-2x'></i></span>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
			}
			$template_modal_body_conteudo .= "</ul>";
		} else {
			$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
		}
	}

	if ($user_apelido != false) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <textarea id='novo_comentario' name='novo_comentario' class='md-textarea form-control' rows='3' required></textarea>
                    <label for='novo_comentario'>Adicione seu comentário:</label>
                </div>
            ";
	} else {
		$template_modal_body_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em <a href='escritorio.php'>seu escritório</a>.</strong></p>";
	}
	include 'templates/modal.php';


	$active1 = false;
	$active2 = false;
	$active3 = false;
	$active4 = false;
	$inactive = false;
	if ($pagina_estado == 1) {
		$active1 = 'selected';
	} elseif ($pagina_estado == 2) {
		$active2 = 'selected';
	} elseif ($pagina_estado == 3) {
		$active3 = 'selected';
	} elseif ($pagina_estado == 4) {
		$active4 = 'selected';
	} else {
		$inactive = 'selected';
	}
	$template_modal_div_id = 'modal_estado';
	$template_modal_titulo = 'Qualidade da página';
	$template_modal_body_conteudo = "
        <p>Por favor, determine abaixo sua avaliação sobre o estado atual desta página:</p>
        <div class='md-form mb-2'>
            <span>Estado</span>
            <select class='mdb-select' name='novo_estado_pagina'>
                <option value='' disabled $inactive>Escolha uma opção</option>
                <option value='1' $active1>Rascunho</option>
                <option value='2' $active2>Aceitável</option>
                <option value='3' $active3>Desejável</option>
                <option value='4' $active4>Excepcional</option>
            </select>
        </div>
    ";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_adicionar_youtube';
	$template_modal_titulo = 'Adicionar vídeo do Youtube';
	$template_modal_body_conteudo = "
                    <div class='md-form mb-2'>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate'
                               required>
                        <label data-error='inválido' data-success='válido'
                               for='novo_video_link'>Link para o vídeo (Youtube)</label>
                    </div>
	";

	include 'templates/modal.php';

	if ($pagina_tipo == 'elemento') {
		include 'pagina/modals_elemento.php';
	}

	if (($pagina_tipo == 'pagina') || ($pagina_tipo == 'elemento')) {
		$template_modal_div_id = 'modal_partes_form';
		$template_modal_titulo = 'Adicionar seção';
		$template_modal_submit_name = 'trigger_nova_secao';
		$template_modal_body_conteudo = false;
		if ($pagina_tipo == 'elemento') {
			$template_modal_body_conteudo .= "
		        <p>Por favor, tome cuidado para que não haja duplicidade. Se possível, é preferencial que todas as seções sejam acrescentadas na ordem em que aparecem na edição de que você dispõe. Ao inserir a ordem da nova seção, por favor considere Introdução, Prefácio etc. Se houver mais de um prefácio, considere a possibilidade de consolidá-los em apenas uma seção, por exemplo, no caso de prefácios a edições que somente mencionam adições ou correções realizadas. Seções de agradecimento, caso nada incluam de especialmente relevante, podem ser ignoradas, assim como tabelas de referência, listas de anexos, glossários e seções afim.</p>
		        <p>Exemplos de seções adequadas: \"Capítulo 1\", \"Capítulo 2: Título do Capítulo\", \"Parte 1: As Origens\", \"Introdução\".</p>
		        <p>É possível determinar a ordem como \"0\". É preferível usar essa opção para elementos anteriores ao primeiro capítulo, como Introdução e Prefácio, pois dessa forma o primeiro capítulo terá a ordem \"1\", o segundo a ordem \"2\" etc.</p>
	          ";
		} elseif ($pagina_tipo == 'pagina') {
			$template_modal_body_conteudo .= "
				<p>Você pode criar seções de sua página, mas recomenda-se cuidado para que não haja duplicidade. Preferencialmente, as seções devem ser adicionadas na ordem final de sua preferência. Nesse caso, é possível ignorar o campo 'ordem' completamente.</p>
			";
		}
		$template_modal_body_conteudo .= "
          <div class='md-form mb-2'>
              <input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
              <label for='elemento_nova_secao'>Título da nova seção</label>
          </div>
          <div class='md-form mb-2'>
              <input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
              <label for='elemento_nova_secao_ordem'>Posição da nova seção</label>
          </div>
        ";

		$secoes = $conn->query("SELECT secao_pagina_id, ordem FROM Secoes WHERE pagina_id = $pagina_id");
		if ($secoes->num_rows > 0) {
			$template_modal_body_conteudo .= "
		<h3>Seções registradas desta página:</h3>
		<ul class='list-group'>
    ";
			while ($secao = $secoes->fetch_assoc()) {
				$secao_ordem = $secao['ordem'];
				$secao_pagina_id = $secao['secao_pagina_id'];
				$secao_info = return_pagina_info($secao_pagina_id);
				$secao_titulo = $secao_info[6];
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$secao_pagina_id' target='_blank'><li class='list-group-item list-group-item-action'>$secao_ordem: $secao_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
	}

	include 'pagina/modal_add_elemento.php';

	include 'pagina/modal_adicionar_imagem.php';

	if ((($pagina_tipo == 'sistema') && ($user_tipo == 'admin')) || (($pagina_tipo == 'pagina') && ($pagina_user_id == $user_id)) || (($pagina_tipo == 'texto') && ($pagina_user_id == $user_id))) {
		if (($pagina_tipo == 'pagina') || ($pagina_tipo == 'sistema')) {
			$mudar_titulo_texto = 'da página';
		} elseif ($pagina_tipo == 'texto') {
			$mudar_titulo_texto = 'do texto';
		}
		$template_modal_div_id = 'modal_pagina_dados';
		$template_modal_titulo = "Alterar dados $mudar_titulo_texto";
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <div class='md-form mb-2'>
                <input type='text' id='pagina_novo_titulo' name='pagina_novo_titulo'
                       class='form-control validate' value='$pagina_titulo' required>
                <label data-error='inválido' data-success='válido'
                       for='pagina_novo_titulo'>Novo título</label>
            </div>
        ";
		include 'templates/modal.php';
	}

	include 'templates/etiquetas_modal.php';

	if ($pagina_tipo == 'secao') {
		$template_modal_div_id = 'modal_paginas_relacionadas';
		$template_modal_titulo = 'Página e seções';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$pagina_item_id'><li class='list-group-item list-group-item-action list-group-item-primary'>$pagina_original_titulo</li></a>";
		$parentes = $conn->query("SELECT id FROM Paginas WHERE tipo = 'secao' AND item_id = $pagina_item_id");
		if ($parentes->num_rows > 0) {
			while ($parente = $parentes->fetch_assoc()) {
				$parente_id = $parente['id'];
				$parente_titulo = return_pagina_titulo($parente_id);
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$parente_id'><li class='list-group-item list-group-item-action'>$parente_titulo</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}

	if ($pagina_tipo == 'texto') {
		include 'pagina/modals_texto.php';
	}

	if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {

		$template_modal_div_id = 'modal_compartilhar_anotacao';
		$template_modal_titulo = 'Compartilhamento';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <p>Apenas você, como criador original desta anotação, poderá alterar suas opções de compartilhamento. Por favor, analise cuidadosamente as opções abaixo. Versões anteriores do documento estarão sempre disponíveis no histórico (para todos os que tenham acesso à sua versão atual). Todo usuário com acesso à anotação poderá alterar suas etiquetas.</p>
			  <h3>Compartilhar com grupo de estudos</h3>
			  ";
		$grupos_do_usuario = $conn->query("SELECT id, titulo FROM Grupos WHERE user_id = $user_id AND estado = 1");
		if ($grupos_do_usuario->num_rows > 0) {
			$template_modal_body_conteudo .= "
                  <form method='post'>
                    <select name='compartilhar_grupo_id' class='$select_classes'>
                        <option value='' disabled selected>Selecione o grupo de estudos</option>
                ";
			while ($grupo_do_usuario = $grupos_do_usuario->fetch_assoc()) {
				$grupo_do_usuario_id = $grupo_do_usuario['grupo_id'];
				$grupo_do_usuario_titulo = return_grupo_titulo_id($grupo_do_usuario_id);
				$template_modal_body_conteudo .= "<option value='$grupo_do_usuario_id'>$grupo_do_usuario_titulo</option>";
			}
			$template_modal_body_conteudo .= "
                    </select>
                    <div class='row justify-content-center'>
                        <button class='$button_classes' name='trigger_compartilhar_grupo'>Compartilhar com grupo</button>
                    </div>
                  </form>
                ";
		} else {
			$template_modal_body_conteudo .= "<p class='text-muted'><em>Você não faz parte de nenhum grupo de estudos. Visite seu escritório para participar.</em></p>";
		}

		/*$template_modal_body_conteudo .= "
			<form>
			<h3>Compartilhar com outro usuário</h3>
				<select name='compartilhar_usuario' class='$select_classes'>
						<option value='' disabled selected>Selecione o usuário</option>
				</select>
				<div class='row justify-content-center'>
						<button class='$button_classes' name='trigger_compartilhar_usuario'>Compartilhar com usuário</button>
				</div>
			</form>
			<h3>Tornar anotação pública.</h3>
			<p>Todo usuário da Ubwiki poderá ler sua anotação, mas não poderá editá-la.</p>
			<h3>Tornar pública e aberta.</h3>
			<p>Todo usuário da Ubwiki poderá ler e editar sua anotação.</p>
			";*/

		include 'templates/modal.php';

	}
?>

</body>

<?php
	$mdb_select = true;
	if ($pagina_tipo == 'curso') {
		include 'templates/searchbar.html';
	}
	if ($pagina_tipo == 'texto') {
		$sticky_toolbar = true;
		$quill_extra_buttons = "
              <span id='salvar_anotacao' class='ml-1' title='Salvar anotação'>
				  <a href='javascript:void(0);'>
					  <i class='fal fa-save fa-fw'></i>
				  </a>
			  </span>
              <span id='anotacao_salva' class='ml-1 text-success' title='Salvar anotação'>
	              <i class='fas fa-save fa-fw'></i>
			  </span>
        ";
		$quill_extra_buttons .= "
              <span class='ml-1' title='Ver histórico'>
                <a href='historico_verbete.php?texto_id=$pagina_texto_id'>
                  <i class='fal fa-history fa-fw'></i>
                </a>
              </span>
    	";
		if ($texto_user_id == $user_id) {
			if (($texto_anotacao == true) && ($texto_tipo != 'verbete_user')) {
				$quill_extra_buttons .= "
                  <span id='apagar_anotacao' class='ml-1' title='Destruir anotação'
                           data-toggle='modal' data-target='#modal_apagar_anotacao'>
                      <a href='javascript:void(0);' class='text-danger'>
                          <i class='fal fa-shredder fa-fw'></i>
                      </a>
                  </span>
                ";
			}
		}
		$quill_extra_buttons .= "<br>";
		$quill_extra_buttons = mysqli_real_escape_string($conn, $quill_extra_buttons);
	}
	if ($pagina_tipo != 'texto') {
		$sistema_etiquetas_elementos = true;
	}
	$sistema_etiquetas_topicos = true;
	include 'templates/html_bottom.php';
	if ($pagina_tipo != 'texto') {
		include 'templates/footer.html';
	}
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>