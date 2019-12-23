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
			
		} else {
			header('Location:escritorio.php');
		}
	} else {
		$pagina_id = $_GET['pagina_id'];
	}
	
	$paginas = $conn->query("SELECT item_id, tipo, estado FROM Paginas WHERE id = $pagina_id");
	if ($paginas->num_rows > 0) {
		while ($pagina = $paginas->fetch_assoc()) {
			$pagina_item_id = $pagina['item_id'];
			$pagina_tipo = $pagina['tipo'];
			$pagina_estado = $pagina['estado'];
		}
		if ($pagina_tipo == 'topico') {
			$topico_id = $pagina_item_id;
			$curso_id = return_curso_id_topico($topico_id);
		} elseif ($pagina_tipo == 'materia') {
			$materia_id = $pagina_item_id;
			$curso_id = return_curso_id_materia($materia_id);
		} elseif ($pagina_tipo == 'elemento') {
			$elemento_id = $pagina_item_id;
		} elseif ($pagina_tipo == 'grupo') {
			$grupo_id = $pagina_item_id;
		}
	}
	
	if ($pagina_tipo == 'curso') {
		$curso_sigla = return_curso_sigla($curso_id);
		$curso_titulo = return_curso_titulo_id($curso_id);
	} elseif ($pagina_tipo == 'materia') {
		$materia_titulo = return_materia_titulo_id($materia_id);
		$curso_id = return_curso_id_materia($materia_id);
		$curso_sigla = return_curso_sigla($curso_id);
	}
	
	if (isset($_POST['submit_elemento_dados'])) {
		$elemento_mudanca_estado = 0;
		if (isset($_POST['elemento_mudanca_estado'])) {
			$elemento_mudanca_estado = 1;
		}
		$elemento_novo_titulo = "NULL";
		$elemento_novo_autor = "NULL";
		$elemento_novo_capitulo = "NULL";
		$elemento_novo_ano = "NULL";
		if (isset($_POST['elemento_novo_titulo'])) {
			$elemento_novo_titulo = $_POST['elemento_novo_titulo'];
			$elemento_novo_titulo = mysqli_real_escape_string($conn, $elemento_novo_titulo);
			if ($elemento_novo_titulo == '') {
				$elemento_novo_titulo = "NULL";
			} else {
				$elemento_novo_titulo = "'$elemento_novo_titulo'";
			}
		}
		if (isset($_POST['elemento_novo_autor'])) {
			$elemento_novo_autor = $_POST['elemento_novo_autor'];
			$elemento_novo_autor = mysqli_real_escape_string($conn, $elemento_novo_autor);
			if ($elemento_novo_autor == '') {
				$elemento_novo_autor = "NULL";
			} else {
				$elemento_novo_autor = "'$elemento_novo_autor'";
			}
		}
		if (isset($_POST['elemento_novo_capitulo'])) {
			$elemento_novo_capitulo = $_POST['elemento_novo_capitulo'];
			$elemento_novo_capitulo = mysqli_real_escape_string($conn, $elemento_novo_capitulo);
			if ($elemento_novo_capitulo == '') {
				$elemento_novo_capitulo = "NULL";
			} else {
				$elemento_novo_capitulo = "'$elemento_novo_capitulo'";
			}
		}
		if (isset($_POST['elemento_novo_ano'])) {
			$elemento_novo_ano = $_POST['elemento_novo_ano'];
			if ($elemento_novo_ano == '') {
				$elemento_novo_ano = "NULL";
			}
		}
		$update = $conn->query("UPDATE Elementos SET estado = $elemento_mudanca_estado, titulo = $elemento_novo_titulo, autor = $elemento_novo_autor, capitulo = $elemento_novo_capitulo, ano = $elemento_novo_ano WHERE id = $elemento_id");
		update_etiqueta_elemento($elemento_id, $user_id);
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_dados')");
		$nao_contar = true;
	}
	
	if ($pagina_tipo == 'topico') {
		$topicos = $conn->query("SELECT estado_pagina, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE curso_id = $curso_id AND id = $topico_id");
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
		$elementos = $conn->query("SELECT estado, criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_estado = $elemento['estado'];
				$elemento_criacao = $elemento['criacao'];
				$elemento_tipo = $elemento['tipo'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				$elemento_capitulo = $elemento['capitulo'];
				$elemento_ano = $elemento['ano'];
				$elemento_link = $elemento['link'];
				$elemento_iframe = $elemento['iframe'];
				$elemento_arquivo = $elemento['arquivo'];
				$elemento_user_id = $elemento['user_id'];
				if (($elemento_tipo == 'imagem_privada') && ($elemento_user_id != $user_id)) {
					header('Location:escritorio.php');
				}
				$elemento_user_apelido = return_apelido_user_id($elemento_user_id);
				if ($elemento_user_apelido == false) {
					$elemento_user_apelido = '(usuário não-identificado)';
				}
			}
		}
		
		if (isset($_POST['trigger_nova_secao'])) {
			$nova_secao_titulo = $_POST['elemento_nova_secao'];
			$nova_secao_titulo = mysqli_real_escape_string($conn, $nova_secao_titulo);
			$nova_secao_ordem = (int)$_POST['elemento_nova_secao_ordem'];
			if ($conn->query("INSERT INTO Textos (tipo, titulo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('secao_elemento', '$nova_secao_titulo', $elemento_id, FALSE, FALSE, FALSE, $user_id)") === true) {
				$nova_secao_texto_id = $conn->insert_id;
				$conn->query("INSERT INTO Secoes (elemento_id, titulo, ordem, user_id, texto_id) VALUES ($elemento_id, '$nova_secao_titulo', $nova_secao_ordem, $user_id, $nova_secao_texto_id)");
				$nova_etiqueta_titulo = "$elemento_titulo // $nova_secao_titulo";
				$nova_etiqueta_titulo = mysqli_real_escape_string($conn, $nova_etiqueta_titulo);
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$nova_etiqueta_titulo', $user_id)");
			}
			$nao_contar = true;
		}
		$secoes = $conn->query("SELECT ordem, titulo, texto_id, estado_texto FROM Secoes WHERE elemento_id = $elemento_id ORDER BY ordem");
		
	}
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$conn->query("INSERT INTO Forum (user_id, pagina_id, pagina_tipo, comentario)  VALUES ($user_id, $pagina_id, '$pagina_tipo', '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $pagina_id, 'forum', $pagina_tipo)");
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		$conn->query("UPDATE Paginas SET estado = $novo_estado_pagina WHERE id = $pagina_id");
		$pagina_estado = $novo_estado_pagina;
		$nao_contar = true;
	}
	
	if (isset($_POST['nova_imagem_titulo'])) {
		
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		
		if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
			$nova_imagem_link = $_POST['nova_imagem_link'];
			$nova_imagem_link = base64_encode($nova_imagem_link);
			adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'link', $curso_id);
		} else {
			$upload_ok = false;
			if (isset($_FILES['nova_imagem_upload'])) {
				$nova_imagem_upload = $_FILES['nova_imagem_upload'];
				$target_dir = '../imagens/uploads/';
				$target_file = $target_dir . basename($_FILES['nova_imagem_upload']['name']);
				$image_filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				// check if file is actually an image
				$check = getimagesize($_FILES['nova_imagem_upload']['tmp_name']);
				if ($check !== false) {
					$upload_ok = true;
				}
				if ($upload_ok == true) {
					if ($image_filetype != 'jpg' && $image_filetype != 'png' && $image_filetype != 'jpeg'
						&& $image_filetype != 'gif') {
						$upload_ok = false;
					}
				}
				if ($upload_ok != false) {
					move_uploaded_file($_FILES['nova_imagem_upload']['tmp_name'], $target_file);
					$target_file = base64_encode($target_file);
					adicionar_imagem($target_file, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'upload', $curso_id);
				}
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_imagem')");
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_video_link'])) {
		$novo_video_link = $_POST['novo_video_link'];
		$novo_video_data = get_youtube($novo_video_link);
		if ($novo_video_data != false) {
			$novo_video_titulo = $novo_video_data['title'];
			$novo_video_autor = $novo_video_data['author_name'];
			$novo_video_thumbnail = $novo_video_data['thumbnail_url'];
			$novo_video_iframe = $novo_video_data['html'];
			$videos = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
			if ($videos->num_rows > 0) {
				while ($video = $videos->fetch_assoc()) {
					$video_id = $video['id'];
					$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $video_id, 'video', $user_id)");
					$video_etiqueta_id = return_elemento_etiqueta_id($video_id);
				}
			} else {
				$novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
				$novo_video_etiqueta = criar_etiqueta($novo_video_titulo, $novo_video_autor, 'video', $user_id, false);
				$novo_video_titulo = mysqli_real_escape_string($conn, $novo_video_titulo);
				$novo_video_autor = mysqli_real_escape_string($conn, $novo_video_autor);
				$novo_video_iframe = mysqli_real_escape_string($conn, $novo_video_iframe);
				$novo_video_etiqueta_id = $novo_video_etiqueta[0];
				$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ($novo_video_etiqueta_id, 'video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
				$novo_video_elemento_id = $conn->insert_id;
				$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $novo_video_elemento_id, 'video', $user_id)");
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_video')");
		$nao_contar = true;
	}
	
	
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
	
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo')");
	}
?>
<body class="carrara">
<?
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row justify-content-between">
        <div class='py-2 text-left col'>
            <span id="add_elements" class="mx-1" title="" data-toggle="modal" data-target="#modal_add_elementos"><a
                        href="javascript:void(0)" title="Adicionar elemento"><i
                            class="fad fa-2x fa-plus-circle fa-fw"></i></a></span>
					<?php
						if ($pagina_tipo == 'elemento') {
							echo "<span id='elemento_dados' class='mx-1'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_dados_elemento' class='text-info'><i class='fad fa-info-circle fa-fw fa-2x'></i></a></span>";
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
						}
					?>
        </div>
        <div class='py-2 text-right col'>
        <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
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
                    ?>
                </span>
					<?php
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
					?>


        </div>
    </div>
</div>
<div class="container-fluid">
	<?php
		$template_titulo_context = true;
		if ($pagina_tipo == 'topico') {
			$template_titulo = $topico_titulo;
			$template_subtitulo = $curso_sigla;
		} elseif ($pagina_tipo == 'elemento') {
			$template_titulo = $elemento_titulo;
			$template_subtitulo = $elemento_autor;
		} elseif ($pagina_tipo == 'curso') {
			$template_titulo = $curso_titulo;
		} elseif ($pagina_tipo == 'materia') {
			$template_titulo = $materia_titulo;
			$template_subtitulo = "Matéria / $curso_sigla";
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row justify-content-around <?php echo $row_classes; ?>">
        <div id="coluna_unica" class="col-lg-10 col-md-12 pagina_coluna">
					<?php
						
						if ($pagina_tipo == 'curso') {
							
							$template_id = 'curso_busca';
							$template_titulo = 'Busca';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo .= "
                        <form id='searchform' action='' method='post'>
                        <div id='searchDiv'>
                            <input id='barra_busca' list='searchlist' type='text' class='barra_busca text-muted'
                                   name='searchBar' rows='1' autocomplete='off' spellcheck='false'
                                   placeholder='O que você vai aprender hoje?' required>
                            <datalist id='searchlist'>";
							$result = $conn->query("SELECT chave FROM Searchbar WHERE curso_id = '$curso_id' ORDER BY ordem");
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									$chave = $row['chave'];
									$template_conteudo .= "<option>$chave</option>";
								}
							}
							$template_conteudo .= "
                            </datalist>";
							$template_conteudo .= "<input id='trigger_busca' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
							$template_conteudo .= "
						</div>
            			</form>";
							
							include 'templates/page_element.php';
							
							$template_id = 'materias';
							$template_titulo = 'Matérias';
							$template_conteudo_no_col = true;
							$template_botoes = false;
							$template_conteudo = false;
							
							$row_items = 2;
							$materias = $conn->query("SELECT titulo, id FROM Materias WHERE curso_id = '$curso_id' AND estado = 1 ORDER BY ordem");
							$rowcount = mysqli_num_rows($materias);
							if ($rowcount > 8) {
								$row_items = 3;
							} elseif ($rowcount > 4) {
								$row_items = 2;
							} elseif ($rowcount < 5) {
								$row_items = 1;
							}
							if ($materias->num_rows > 0) {
								$count = 0;
								$count2 = 0;
								$count3 = 0;
								while ($materia = $materias->fetch_assoc()) {
									if ($count == 0) {
										$count2++;
										$template_conteudo .= "<div class='col-xl col-lg-6 col-md-6 col-sm-12'>";
									}
									$count++;
									$materia_titulo = $materia['titulo'];
									$materia_id = $materia["id"];
									$template_conteudo .= "
                                          <a href='pagina.php?materia_id=$materia_id'><div class='btn btn-block btn-light rounded oswald btn-md text-center grey lighten-3 text-muted mb-3'>
                                            <span class=''>$materia_titulo</span>
                                          </div></a>
                                        ";
									if ($count == $row_items) {
										$count3++;
										$template_conteudo .= "</div>";
										$count = 0;
									}
								}
								if ($count2 != $count3) {
									$template_conteudo .= "</div>";
								}
								unset($materia_id);
							}
							
							include 'templates/page_element.php';
							
							$template_id = 'paginas_recentes';
							$template_titulo = 'Verbetes recentemente modificados';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo_no_col = true;
							$paginas = $conn->query("SELECT DISTINCT page_id FROM (SELECT id, page_id FROM Textos_arquivo WHERE tipo = 'verbete' AND page_id IS NOT NULL GROUP BY id ORDER BY id DESC) t");
							if ($paginas->num_rows > 0) {
								$template_conteudo .= "<ul class='list-group rounded'>";
								$count = 0;
								while ($pagina = $paginas->fetch_assoc()) {
									$topico_id = $pagina['page_id'];
									$topico_materia_id = return_materia_id_topico($topico_id);
									$topico_materia_titulo = return_materia_titulo_id($topico_materia_id);
									$topico_titulo = return_titulo_topico($topico_id);
									$topicos = $conn->query("SELECT estado_pagina FROM Topicos WHERE id = $topico_id AND curso_id = $curso_id");
									if ($count == 15) {
										break;
									}
									if ($topicos->num_rows > 0) {
										while ($topico = $topicos->fetch_assoc()) {
											$topico_estado_pagina = $topico['estado_pagina'];
											$icone_estado = return_estado_icone($topico_estado_pagina, 'materia');
											if ($topico_estado_pagina > 3) {
												$estado_cor = 'text-warning';
											} else {
												$estado_cor = 'text-dark';
											}
											$cor_badge = 'grey lighten-3';
											$icone_badge = "
                                            <span class='badge $cor_badge $estado_cor badge-pill z-depth-0 ml-3'>
                                                <i class='fa $icone_estado fa-fw'></i>
                                            </span>
											";
											$template_conteudo .= "
                                            <a href='pagina.php?topico_id=$topico_id'>
                                                <li class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                                                    <span>
                                                        <strong>$topico_materia_titulo: </strong>
                                                        $topico_titulo
                                                    </span>
                                                    $icone_badge
                                                </li>
                                            </a>";
											$count++;
											break;
										}
									}
								}
								unset($topico_id);
								$template_conteudo .= "</ul>";
							}
							include 'templates/page_element.php';
							
						} elseif ($pagina_tipo == 'materia') {
							$template_id = 'topicos';
							$template_titulo = 'Tópicos';
							$template_conteudo_no_col = true;
							$template_botoes = false;
							$template_conteudo = false;
							
							
							$template_conteudo .= "<ul class='list-group'>";
							
							$result = $conn->query("SELECT DISTINCT nivel FROM Topicos WHERE materia_id = '$materia_id'");
							$nivel_count = 0;
							while ($row = mysqli_fetch_array($result)) {
								$nivel_count++;
							}
							$cor_nivel1 = false;
							$cor_nivel2 = false;
							$cor_nivel3 = false;
							$cor_nivel4 = false;
							$cor_nivel5 = false;
							
							if ($nivel_count == 5) {
								$cor_nivel1 = 'grey lighten-1';
								$cor_nivel2 = 'grey lighten-2';
								$cor_nivel3 = 'grey lighten-3';
								$cor_nivel4 = 'grey lighten-4';
								$cor_nivel5 = 'grey lighten-5';
							} elseif ($nivel_count == 4) {
								$cor_nivel1 = 'grey lighten-2';
								$cor_nivel2 = 'grey lighten-3';
								$cor_nivel3 = 'grey lighten-4';
								$cor_nivel4 = 'grey lighten-5';
							} elseif ($nivel_count == 3) {
								$cor_nivel1 = 'grey lighten-2';
								$cor_nivel2 = 'grey lighten-3';
								$cor_nivel3 = 'grey lighten-5';
							} elseif ($nivel_count == 2) {
								$cor_nivel1 = 'grey lighten-3';
								$cor_nivel2 = 'grey lighten-5';
							}
							
							$spacing1 = "style=''";
							$spacing2 = "style='padding-left: 5ch'";
							$spacing3 = "style='padding-left: 10ch'";
							$spacing4 = "style='padding-left: 15ch'";
							$spacing5 = "style='padding-left: 20ch'";
							
							$result = $conn->query("SELECT id, estado_pagina, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = '$materia_id' ORDER BY ordem");
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									$topico_id = $row["id"];
									$estado_pagina = $row['estado_pagina'];
									$nivel1 = $row["nivel1"];
									$nivel2 = $row["nivel2"];
									$nivel3 = $row["nivel3"];
									$nivel4 = $row["nivel4"];
									$nivel5 = $row["nivel5"];
									
									if ($estado_pagina != 0) {
										$estado_cor = false;
										$icone_estado = return_estado_icone($estado_pagina, 'materia');
										if ($estado_pagina > 3) {
											$estado_cor = 'text-warning';
										} else {
											$estado_cor = 'text-dark';
										}
										$cor_badge = 'bg-white';
										$icone_badge = "
				                        <span class='ml-3 badge $cor_badge $estado_cor badge-pill z-depth-0'>
                                            <i class='fa $icone_estado'></i>
                                        </span>
				                    ";
									} else {
										$icone_badge = false;
									}
									
									if ($nivel5 != false) {
										$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel5 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing5>
                                            $nivel5
                                            $icone_badge
                                        </a>
                                    ";
									} elseif ($nivel4 != false) {
										$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel4 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing4>
                                            $nivel4
                                            $icone_badge
                                        </a>
                                    ";
									} elseif ($nivel3 != false) {
										$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel3 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing3>
                                            $nivel3
                                            $icone_badge
                                        </a>
                                    ";
									} elseif ($nivel2 != false) {
										$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel2 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing2>
                                            $nivel2
                                            $icone_badge
                                        </a>
                                    ";
									} elseif ($nivel1 != false) {
										$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel1 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing1>
                                            $nivel1
                                            $icone_badge
                                        </a>
                                    ";
									}
								}
								unset($topico_id);
							}
							$template_conteudo .= "</ul>";
							
							
							include 'templates/page_element.php';
						}
					?>
        </div>
        <div id='coluna_esquerda' class="<?php echo $coluna_classes; ?> pagina_coluna">
					<?php
						if ($pagina_tipo == 'elemento') {
							
							if (($elemento_tipo == 'imagem') || ($elemento_tipo == 'imagem_privada')) {
								$template_id = 'imagem_div';
								$template_titulo = false;
								$template_botoes = false;
								$template_conteudo = "<a href='../imagens/verbetes/$elemento_arquivo' target='_blank'><img class='imagem_pagina border' src='../imagens/verbetes/$elemento_arquivo'></img></a>";
								include 'templates/page_element.php';
							} elseif ($elemento_tipo == 'video') {
								$template_div = 'video_div';
								$template_titulo = false;
								$template_botoes = false;
								$template_conteudo = $elemento_iframe;
								$template_conteudo_class = 'text-center';
								include 'templates/page_element.php';
							}
						}
						
						$template_id = 'verbete';
						$template_titulo = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						if ($pagina_tipo == 'elemento') {
							$template_div = 'partes_elemento';
							$template_titulo = 'Seções';
							$template_botoes = "
                            	<a data-toggle='modal' data-target='#modal_partes_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
                            ";
							$template_conteudo = false;
							$secoes = $conn->query("SELECT ordem, titulo, texto_id, estado_texto FROM Secoes WHERE elemento_id = $elemento_id ORDER BY ordem");
							if ($secoes->num_rows > 0) {
								$template_conteudo .= "<p>Seções identificadas deste elemento e seus textos correspondentes:</p>";
								$template_conteudo .= "<ul class='list-group'>";
								while ($secao = $secoes->fetch_assoc()) {
									$secao_titulo = $secao['titulo'];
									$secao_texto_id = $secao['texto_id'];
									$secao_estado_texto = $secao['estado_texto'];
									$secao_estado_icone = return_estado_icone($secao_estado_texto, 'elemento');
									$template_conteudo .=
										"
								      <a href='edicao_textos.php?texto_id=$secao_texto_id' target='_blank' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
							              $secao_titulo
									      <span class='ml-3 badge grey lighten-3 text-dark badge-pill z-depth-0'>
                                              <i class='fa $secao_estado_icone'></i>
                                          </span>
							          </a>
						        	";
								}
								$template_conteudo .= "</ul>";
							} else {
								$template_conteudo .= "<p>Não há seções identificadas deste elemento.</p>";
							}
							
							include 'templates/page_element.php';
						}
						
						$result = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'referencia'");
						if ($result->num_rows > 0) {
							$template_id = 'leia_mais';
							$template_titulo = 'Leia mais';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo .= "<ul class='list-group'>";
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, autor, capitulo, estado FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row = $result2->fetch_assoc()) {
										$referencia_titulo = $row['titulo'];
										$referencia_autor = $row['autor'];
										$referencia_capitulo = $row['capitulo'];
										$referencia_estado = $row['estado'];
										if ($referencia_estado == false) {
											continue;
										}
										if ($referencia_capitulo == false) {
											$template_conteudo .= "<a href='pagina.php?elemento_id=$elemento_id' target='_blank'><li class='list-group-item'>$referencia_titulo / $referencia_autor</li></a>";
										} else {
											$template_conteudo .= "<a href='pagina.php?elemento_id=$elemento_id' target='_blank'><li class='list-group-item'>$referencia_titulo / $referencia_autor // $referencia_capitulo</li></a>";
										}
									}
								}
							}
							$template_conteudo .= "</ul>";
							include 'templates/page_element.php';
						}
						
						$videos = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'video'");
						$count = 0;
						if ($videos->num_rows > 0) {
							$template_id = 'videos';
							$template_titulo = 'Vídeos';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo .= "
                                <div id='carousel-videos' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                <div class='carousel-inner' role='listbox'>
                            ";
							$active = 'active';
							while ($video = $videos->fetch_assoc()) {
								$elemento_id = $video['elemento_id'];
								$elementos = $conn->query("SELECT titulo, autor, arquivo FROM Elementos WHERE id = $elemento_id");
								if ($elementos->num_rows > 0) {
									while ($elemento = $elementos->fetch_assoc()) {
										$count++;
										$video_titulo = $elemento['titulo'];
										$video_autor = $elemento['autor'];
										$video_arquivo = $elemento['arquivo'];
										$template_conteudo .= "
                                            <div class=' carousel-item $active text-center'>
                                              <figure class='col-12'>
                                                <a href='pagina.php?elemento_id=$elemento_id' target='_blank'>
                                                  <img src='/../imagens/youthumb/$video_arquivo'
                                                    class='img-fluid' style='height:300px'>
                                                </a>
                                        ";
										$template_conteudo .= "
                                            <figcaption>
                                           <strong class='h5-responsive mt-2'>$video_titulo</strong>
                                        ";
										$template_conteudo .= "<p>$video_autor</p>";
										$template_conteudo .= "</figcaption>";
										$template_conteudo .= "</figure>
                                            </div>
                                        ";
										$active = false;
										break;
									}
								}
							}
							if ($count != 1) {
								$template_conteudo .= "
                            </div>
                              <div class='controls-top'>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-videos' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-videos' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                            ";
							}
							$template_conteudo .= "</div></div>";
							include 'templates/page_element.php';
						}
						
						
						$imagens = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'imagem'");
						$count = 0;
						if ($imagens->num_rows > 0) {
							$template_id = 'imagens';
							$template_titulo = 'Imagens';
							$template_botoes = false;
							$template_conteudo = false;
							$active = 'active';
							while ($imagem = $imagens->fetch_assoc()) {
								$elemento_id = $imagem['elemento_id'];
								$elementos = $conn->query("SELECT titulo, arquivo, estado FROM Elementos WHERE id = $elemento_id");
								if ($elementos->num_rows > 0) {
									while ($elemento = $elementos->fetch_assoc()) {
										$imagem_titulo = $elemento['titulo'];
										$imagem_arquivo = $elemento['arquivo'];
										$imagem_estado = $elemento['estado'];
										if ($imagem_estado == false) {
											continue;
										} else {
											$count++;
										}
										if ($count == 1) {
											$template_conteudo .= "
                                                <div id='carousel-imagens' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                                <div class='carousel-inner' role='listbox'>
                                            ";
										}
										$template_conteudo .= "
                                            <div class=' carousel-item $active text-center'>
                                              <figure class='col-12'>
                                                <a href='pagina.php?elemento_id=$elemento_id' target='_blank'>
                                                  <img src='/../imagens/verbetes/thumbnails/$imagem_arquivo'
                                                    class='img-fluid' style='height:300px'>
                                                </a>
                                        ";
										$template_conteudo .= "<figcaption>
                                           <strong class='h5-responsive mt-2'>$imagem_titulo</strong>";
										$template_conteudo .= "</figcaption>";
										$template_conteudo .= "</figure>";
										$template_conteudo .= "</div>";
										$active = false;
										break;
									}
								}
							}
							if ($count != 0) {
								$template_conteudo .= "</div>";
							}
							if ($count != 1) {
								$template_conteudo .= "
                                      <div class='controls-top'>
                                        <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                        <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                                      </div>
                                ";
							}
							if ($count != 0) {
								$template_conteudo .= "</div>";
							}
							include 'templates/page_element.php';
						}
					
					
					?>
        </div>
        <div id='coluna_direita' class="<?php echo $coluna_classes ?> pagina_coluna">
					<?php
						$template_id = 'anotacoes';
						$template_titulo = 'Notas de estudo';
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
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
                                                <p></span><strong><a href='perfil.php?pub_user_id=$autor_comentario_id' target='_blank'><span class='$autor_comentario_cor'><i class='fad $autor_comentario_avatar fa-fw fa-2x'></i></span>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
	}
	$usuario_apelido = return_apelido_user_id($user_id);
	if ($usuario_apelido != false) {
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
	
	$template_modal_div_id = 'modal_adicionar_imagem';
	$template_modal_titulo = 'Adicionar imagem';
	$template_modal_enctype = "enctype='multipart/form-data'";
	$template_modal_body_conteudo = "
        <div class='md-form mb-2'>
            <input type='text' id='nova_imagem_titulo' name='nova_imagem_titulo'
                   class='form-control validate' required>
            <label data-error='inválido' data-success='válido'
                   for='nova_imagem_titulo'>Título da imagem</label>
        </div>
        <div class='md-form mb-2'>
        <input type='url' id='nova_imagem_link' name='nova_imagem_link'
               class='form-control validate'>
        <label data-error='inválido' data-success='válido'
               for='nova_imagem_link'>Link para a imagem</label>
        </div>
        <div class='md-form mb-2'>
            <div class='file-field'>
                <div class='btn btn-primary btn-sm float-left m-0'>
                    <span>Selecione o arquivo</span>
                    <input type='file' name='nova_imagem_upload'>
                </div>
                <div class='file-path-wrapper'>
                    <input class='file-path validate' type='text' placeholder='Faça upload da sua imagem'>
                </div>
            </div>
        </div>
    ";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_referencia';
	$template_modal_titulo = 'Adicionar material de leitura';
	$template_modal_body_conteudo = "
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_titulo' name='nova_referencia_titulo'
                   class='form-control validate' required>
            <label data-error='inválido' data-success='válido'
                   for='nova_referencia_titulo'>Título da obra</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_autor' name='nova_referencia_autor'
                   class='form-control validate' required>
            <label data-error='inválido' data-success='válido'
                   for='nova_referencia_autor'>Nome do autor</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_capitulo' name='nova_referencia_capitulo'
                   class='form-control validate'>
            <label data-error='inválido' data-success='válido'
                   for='nova_referencia_capitulo'>Capítulo (opcional)</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_ano' name='nova_referencia_ano'
                   class='form-control validate'>
            <label data-error='inválido' data-success='válido'
                   for='nova_referencia_ano'>Ano (opcional)</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_link' name='nova_referencia_link'
                   class='form-control validate'>
            <label data-error='inválido' data-success='válido'
                   for='nova_referencia_link'>Link (opcional)</label>
        </div>
	";
	
	include 'templates/modal.php';
	
	if ($pagina_tipo == 'elemento') {
		if ($elemento_estado == true) {
			$elemento_estado_visivel = 'publicado';
		} else {
			$elemento_estado_visivel = 'removido';
		}
		
		$dados_elemento = false;
		if ($elemento_tipo == 'imagem_privada') {
			$dados_elemento .= "<p>Esta imagem é privada e não pode ser vista por outros usuários.</p>";
		}
		$dados_elemento .= "
                            <ul class='list-group'>
						        <li class='list-group-item'><strong>Criado em:</strong> $elemento_criacao</li>";
		if ($elemento_tipo != 'imagem_privada') {
			$dados_elemento .= "<li class='list-group-item'><strong>Estado de publicação:</strong> $elemento_estado_visivel</li>";
		}
		if ($elemento_titulo != false) {
			$dados_elemento .= "<li class='list-group-item'><strong>Título:</strong> $elemento_titulo</li>";
		}
		if ($elemento_autor != false) {
			$dados_elemento .= "<li class='list-group-item'><strong>Autor:</strong> $elemento_autor</li>";
		}
		if ($elemento_capitulo != false) {
			$dados_elemento .= "<li class='list-group-item'><strong>Capítulo:</strong> $elemento_capitulo</li>";
		}
		if ($elemento_ano != 0) {
			$dados_elemento .= "<li class='list-group-item'><strong>Ano:</strong> $elemento_ano</li>";
		}
		if ($elemento_link != false) {
			$dados_elemento .= "<li class='list-group-item'><a href='$elemento_link' target='_blank'>Link original</a></li>";
		}
		$dados_elemento .= "<li class='list-group-item'>Adicionado pelo usuário <strong><a href='perfil.php?pub_user_id=$elemento_user_id' target='_blank'>$elemento_user_apelido</a></strong></li>";
		$dados_elemento .= "</ul>";
		
		$template_modal_div_id = 'modal_dados_elemento';
		$template_modal_titulo = 'Dados';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= $dados_elemento;
		$template_modal_body_conteudo .= "
				<div class='row justify-content-center'>
					<span data-toggle='modal' data-target='#modal_dados_elemento'>
						<button type='button' data-toggle='modal' data-target='#modal_elemento_form' class='$button_classes'>Editar</button>
					</span>
				</div>
			";
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_elemento_form';
		$template_modal_titulo = 'Alterar dados do elemento';
		$template_modal_body_conteudo = false;
		
		$estado_elemento_checkbox = false;
		if ($elemento_estado == true) {
			$estado_elemento_checkbox = 'checked';
		}
		if ($elemento_tipo != 'imagem_privada') {
			$template_modal_body_conteudo .= "
                  <div class='form-check pl-0'>
                      <input type='checkbox' class='form-check-input' id='elemento_mudanca_estado' name='elemento_mudanca_estado' $estado_elemento_checkbox>
                      <label class='form-check-label' for='elemento_mudanca_estado'>Adequado para publicação</label>
                  </div>
                ";
		} else {
			$template_modal_body_conteudo .= "
				    <input type='hidden' name='elemento_mudanca_estado' value='true'>
			    ";
		}
		
		$template_modal_body_conteudo .= "
		      <div class='md-form mb-2'>
                  <input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$elemento_titulo'>
                  <label for='elemento_novo_titulo'>Título</label>
              </div>
          
              <div class='md-form mb-2'>
                  <input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$elemento_autor'>
                  <label for='elemento_novo_autor'>Autor</label>
              </div>
            ";
		$template_modal_body_conteudo .= "
		        <div class='md-form mb-2'>
                    <input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$elemento_ano'>
                    <label for='elemento_novo_ano'>Ano</label>
                </div>
	        ";
		
		$template_modal_submit_name = 'submit_elemento_dados';
		
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_partes_form';
		$template_modal_titulo = 'Adicionar seção';
		$template_modal_submit_name = 'trigger_nova_secao';
		$template_modal_body_conteudo = "
		<p>Por favor, tome cuidado para que não haja duplicidade. Se possível, é preferencial que todas as seções sejam acrescentadas na ordem em que aparecem na edição de que você dispõe. Ao inserir a ordem da nova seção, por favor considere Introdução, Prefácio etc. Se houver mais de um prefácio, considere a possibilidade de consolidá-los em apenas uma seção, por exemplo, no caso de prefácios a edições que somente mencionam adições ou correções realizadas. Seções de agradecimento, caso nada incluam de especialmente relevante, podem ser ignoradas, assim como tabelas de referência, listas de anexos, glossários e seções afim.</p>
		<p>Exemplos de seções adequadas: \"Capítulo 1\", \"Capítulo 2: Título do Capítulo\", \"Parte 1: As Origens\", \"Introdução\".</p>
		<p>É possível determinar a ordem como \"0\". É preferível usar essa opção para elementos anteriores ao primeiro capítulo, como Introdução e Prefácio, pois o primeiro capítulo terá a ordem \"1\", o segundo a ordem \"2\" etc.</p>
		<div class='md-form mb-2'>
			<input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
            <label for='elemento_nova_secao'>Título da nova seção</label>
		</div>
		<div class='md-form mb-2'>
			<input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
            <label for='elemento_nova_secao_ordem'>Posição da nova seção</label>
		</div>
	";
		
		mysqli_data_seek($secoes, 0);
		if ($secoes->num_rows > 0) {
			$template_modal_body_conteudo .= "
		<h3>Seções registradas para este elemento:</h3>
		<ul class='list-group'>
    ";
			while ($secao = $secoes->fetch_assoc()) {
				$secao_ordem = $secao['ordem'];
				$secao_titulo = $secao['titulo'];
				$secao_texto_id = $secao['texto_id'];
				$template_modal_body_conteudo .= "<a href='edicao_textos.php?texto_id=$secao_texto_id' target='_blank'><li class='list-group-item list-group-item-action'>$secao_ordem: $secao_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
	}
	
	$template_modal_div_id = 'modal_add_elementos';
	$template_modal_titulo = 'Adicionar elemento';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		  	<span id='esconder_adicionar_elemento' data-toggle='modal' data-target='#modal_add_elementos' class='row justify-content-center'>";
	
	$artefato_tipo = 'adicionar_youtube';
	$artefato_titulo = 'Novo vídeo do Youtube';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-5';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'adicionar_imagem';
	$artefato_titulo = 'Nova imagem';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-5';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</span>
	";
	
	$template_modal_body_conteudo .= "
        <h2>Buscar elemento</h2>
        <p>Antes de criar novo registro de elemento, por favor use o mecanismo de busca para que não haja duplicidade.</p>
";
	
	$adicionar_referencia_busca_texto = "Digite aqui o título da nova referência";
	$adicionar_referencia_form_botao = "Adicionar essa referência a esta página.";
	$template_modal_body_conteudo .= include 'templates/adicionar_referencia_form.php';
	
	include 'templates/modal.php';

?>

</body>

<?php
	include 'templates/footer.html';
	$mdb_select = true;
	$biblioteca_bottom_adicionar = true;
	if ($pagina_tipo == 'curso') {
		include 'templates/searchbar.html';
	}
	include 'templates/html_bottom.php';
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>