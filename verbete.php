<?php
	
	include 'engine.php';
	
	if (isset($_GET['topico_id'])) {
		$topico_id = $_GET['topico_id'];
	}
	
	if (!isset($concurso_id)) {
		$concurso_id = return_concurso_id_topico($topico_id);
	}
	$concurso_sigla = return_concurso_sigla($concurso_id);
	
	$nao_contar = false;
	$topico_anterior = false;
	$topico_proximo = false;
	
	$result = $conn->query("SELECT estado_pagina, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE concurso_id = '$concurso_id' AND id = $topico_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$estado_pagina = $row['estado_pagina'];
			$materia_id = $row['materia_id'];
			$nivel = $row['nivel'];
			$ordem = $row['ordem'];
			$nivel1 = $row['nivel1'];
			$nivel2 = $row['nivel2'];
			$nivel3 = $row['nivel3'];
			$nivel4 = $row['nivel4'];
			$nivel5 = $row['nivel5'];
		}
		if ($nivel == 1) {
			$topico_titulo = $nivel1;
		} elseif ($nivel == 2) {
			$topico_titulo = $nivel2;
		} elseif ($nivel == 3) {
			$topico_titulo = $nivel3;
		} elseif ($nivel == 4) {
			$topico_titulo = $nivel4;
		} elseif ($nivel == 5) {
			$topico_titulo = $nivel5;
		}
	}
	
	if (isset($_POST['quill_trigger_verbete'])) {
		if ($estado_pagina == 0) {
			$conn->query("UPDATE Topicos SET estado_pagina = 1 WHERE id = $topico_id");
			$estado_pagina = 1;
		}
	}
	
	$result = $conn->query("SELECT titulo FROM Materias WHERE concurso_id = '$concurso_id' AND estado = 1 AND id = $materia_id ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia_titulo = $row["titulo"];
		}
	}
	
	
	// IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM
	
	if (isset($_POST['nova_imagem_titulo'])) {
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $topico_id, 'topico_imagem')");
	}
	
	if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
		$nova_imagem_link = $_POST['nova_imagem_link'];
		$nova_imagem_link = base64_encode($nova_imagem_link);
		adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $topico_id, $user_id, 'verbete', 'link', $concurso_id);
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
				adicionar_imagem($target_file, $nova_imagem_titulo, $topico_id, $user_id, 'verbete', 'upload', $concurso_id);
			}
		}
	}
	
	// REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA
	
	if (isset($_POST['nova_referencia_titulo'])) {
		$nova_referencia_titulo = $_POST['nova_referencia_titulo'];
		$nova_referencia_titulo = mysqli_real_escape_string($conn, $nova_referencia_titulo);
		$nova_referencia_autor = $_POST['nova_referencia_autor'];
		$nova_referencia_autor = mysqli_real_escape_string($conn, $nova_referencia_autor);
		$nova_referencia_capitulo = $_POST['nova_referencia_capitulo'];
		$nova_referencia_capitulo = mysqli_real_escape_string($conn, $nova_referencia_capitulo);
		$nova_referencia_ano = $_POST['nova_referencia_ano'];
		if ($nova_referencia_ano == false) {
			$nova_referencia_ano = 0;
		}
		$nova_referencia_link = $_POST['nova_referencia_link'];
		$nova_referencia_link = mysqli_real_escape_string($conn, $nova_referencia_link);
		$result = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
		if ($result->num_rows == 0) {
			$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('referencia', '$nova_referencia_titulo', $user_id)");
			$nova_referencia_etiqueta_id = $conn->insert_id;
			$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, autor, capitulo, link, ano, user_id) VALUES ($nova_referencia_etiqueta_id, 'referencia', '$nova_referencia_titulo', '$nova_referencia_autor', NULLIF('$nova_referencia_capitulo',''), NULLIF('$nova_referencia_link',''), NULLIF($nova_referencia_ano,0), $user_id)");
			$result2 = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					$nova_referencia_id = $row['id'];
					$conn->query("INSERT INTO Verbetes_elementos (page_id, concurso_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($topico_id, $concurso_id, 'verbete', $nova_referencia_id, 'referencia', $user_id)");
					break;
				}
			}
		} else {
			while ($row = $result->fetch_assoc()) {
				$nova_referencia_id = $row['id'];
				$conn->query("INSERT INTO Verbetes_elementos (page_id, concurso_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($topico_id, $concurso_id, 'verbete', $nova_referencia_id, 'referencia', $user_id)");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $topico_id, 'topico_referencia')");
	}
	
	// VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO
	
	if (isset($_POST['novo_video_link'])) {
		$novo_video_link = $_POST['novo_video_link'];
		$novo_video_data = get_youtube($novo_video_link);
		if ($novo_video_data == false) {
			return false;
		}
		$novo_video_titulo = $novo_video_data['title'];
		$novo_video_titulo = mysqli_real_escape_string($conn, $novo_video_titulo);
		$novo_video_autor = $novo_video_data['author_name'];
		$novo_video_autor = mysqli_real_escape_string($conn, $novo_video_autor);
		$novo_video_thumbnail = $novo_video_data['thumbnail_url'];
		$novo_video_iframe = $novo_video_data['html'];
		$novo_video_iframe = base64_encode($novo_video_iframe);
		$result = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$id_video_preexistente = $row['id'];
				$insert = $conn->query("INSERT INTO Verbetes_elementos (topico_id, concurso_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($topico_id, $concurso_id, 'verbete', $id_video_preexistente, 'youtube', $user_id)");
				break;
			}
		} else {
			$novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
			$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('video', '$novo_video_titulo', $user_id)");
			$novo_video_etiqueta_id = $conn->insert_id;
			$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ($novo_video_etiqueta_id, 'video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
			$result = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$novo_video_id = $row['id'];
					$insert = $conn->query("INSERT INTO Verbetes_elementos (topico_id, concurso_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($topico_id, $concurso_id, 'verbete', $novo_video_id, 'video', $user_id)");
					break;
				}
			} else {
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $topico_id, 'topico_video')");
	}
	
	// FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$conn->query("INSERT INTO Forum (user_id, page_id, page_tipo, comentario)  VALUES ($user_id, $topico_id, 'topico', '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $topico_id, 'topico_forum')");
	}
	
	// COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED
	
	$estado_estudo = false;
	$estudos = $conn->query("SELECT estado FROM Completed WHERE user_id = $user_id AND topico_id = $topico_id AND active = 1 ORDER BY id DESC");
	if ($estudos->num_rows > 0) {
		while ($row = $estudos->fetch_assoc()) {
			$estado_estudo = $row['estado'];
			break;
		}
	}
	
	// BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK
	
	$topico_bookmark = false;
	$bookmark = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND topico_id = $topico_id AND active = 1 ORDER BY id DESC");
	if ($bookmark->num_rows > 0) {
		while ($row = $bookmark->fetch_assoc()) {
			$topico_bookmark = $row['bookmark'];
			break;
		}
	}
	
	// ESTADO PAGINA ESTADO PAGINA ESTADO PAGINA ESTADO PAGINA ESTADO PAGINA ESTADO PAGINA ESTADO PAGINA
	
	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		$conn->query("UPDATE Topicos SET estado_pagina = $novo_estado_pagina WHERE id = $topico_id");
		$estado_pagina = $novo_estado_pagina;
		$nao_contar = true;
	}
	
	// HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD
	
	include 'templates/verbetes_relacionados.php';
 
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var topico_id=$topico_id;
          var concurso_id='$concurso_id';
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $topico_id, 'topico')");
	}
?>
<body>
<?php
	include 'templates/navbar.php';
	
	// PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA


?>
<div class='container-fluid'>
    <div class='row justify-content-between'>
        <div class='py-2 text-left col'>
					<?php
						if ($topico_anterior != false) {
							$topico_anterior_link = "verbete.php?topico_id=$topico_anterior";
							echo "<span id='verbete_anterior' class='mx-1' title='Verbete anterior'><a href='$topico_anterior_link'><i class='fal fa-arrow-left fa-fw'></i></a></span>";
						}
						echo "<span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='modal' data-target='#modal_verbetes_relacionados'><a href='javascript:void(0);'><i class='fal fa-project-diagram fa-fw'></i></a></span>";
						if ($topico_proximo != false) {
							$topico_proximo_link = "verbete.php?topico_id=$topico_proximo";
							echo "<span id='verbete_proximo' class='mx-1' title='Próximo verbete'><a href='$topico_proximo_link'><i class='fal fa-arrow-right fa-fw'></i></a></span>";
						}
					?>
        </div>
        <div class="py-2 text-center col">
            <span class="mx-1" title="" data-toggle="modal" data-target="#modal_referencia_form"><a href="javascript:void(0);" class="text-success"><i class="fad fa-book fa-fw"></i></a></span>
            <span class="mx-1" title="" data-toggle="modal" data-target="#modal_videos_form"><a href="javascript:void(0);" class="text-info"><i class="fad fa-film fa-fw"></i></a></span>
            <span class="mx-1" title="" data-toggle="modal" data-target="#modal_imagens_form"><a href="javascript:void(0);" class="text-danger"><i class="fad fa-image fa-fw"></i></a></span>
            <!--<span id="" class="mx-1" title="" data-toggle="modal" data-target="#modal_audio_form"><a href="javascript:void(0);"><i class="fad fa-microphone fa-fw"></i></a></span>-->
        </div>
        <div class='py-2 text-right col'>
                <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
	                    $comments = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE page_id = $topico_id AND page_tipo = 'topico'");
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
      <span id='add_completed' class='ml-1' title='Estudo completo' value='$topico_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
      <span id='remove_completed' class='ml-1 collapse' title='Desmarcar como completo' value='$topico_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
    ";
						} else {
							echo "
      <span id='add_completed' class='ml-1 collapse' title='Estudo completo' value='$topico_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
      <span id='remove_completed' class='ml-1' title='Desmarcar como completo' value='$topico_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
    ";
						}
						if ($topico_bookmark == false) {
							echo "
              <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$topico_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$topico_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
						} else {
							echo "
              <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$topico_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$topico_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
						}
						if ($estado_pagina != 0) {
							$estado_cor = false;
							$icone_estado = return_estado_icone($estado_pagina, 'verbete');
							if ($estado_pagina > 3) {
								$estado_cor = 'text-warning';
							} else {
								$estado_cor = 'text-info';
							}
							echo "
								    <span id='change_estado_pagina' class='ml-1' title='Estado da página' data-toggle='modal' data-target='#modal_estado'><a href='javascript:void(0);'><span class='$estado_cor'><i class='$icone_estado fa-fw'></i></span></a></span>
								";
						}
					?>
        </div>
    </div>
</div>

<div id='page_height' class='container-fluid'>
	<?php
		$template_titulo_context = true;
		$template_titulo = $topico_titulo;
		include 'templates/titulo.php';
	?>
    <div class='row justify-content-around'>
        <div id='coluna_esquerda' class='<?php echo $coluna_classes; ?>'>
					<?php
						//VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
						
						$template_id = 'verbete';
						$template_titulo = 'Verbete';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						//VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS
						
						$result = $conn->query("SELECT elemento_id FROM Verbetes_elementos WHERE page_id = $topico_id AND tipo = 'video' AND tipo_pagina = 'verbete' AND concurso_id = $concurso_id");
						$count = 0;
						if ($result->num_rows > 0) {
							$template_id = 'videos';
							$template_titulo = 'Vídeos e aulas';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo .= "
                                <div id='carousel-videos' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                <div class='carousel-inner' role='listbox'>
                            ";
							$active = 'active';
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, autor, arquivo FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row = $result2->fetch_assoc()) {
										$count++;
										$video_titulo = $row['titulo'];
										$video_autor = $row['autor'];
										$video_arquivo = $row['arquivo'];
										$template_conteudo .= "
                                <div class=' carousel-item $active text-center'>
                                  <figure class='col-12'>
                                    <a href='elemento.php?id=$elemento_id' target='_blank'>
                                      <img src='/../imagens/youthumb/$video_arquivo'
                                        class='img-fluid' style='height:300px'>
                                    </a>";
										$template_conteudo .= "<figcaption>
                                           <strong class='h5-responsive mt-2'>$video_titulo</strong>";
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
						
						
						//LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS
						
						$result = $conn->query("SELECT DISTINCT elemento_id FROM Verbetes_elementos WHERE page_id = $topico_id AND tipo = 'referencia' AND tipo_pagina = 'verbete' AND concurso_id = $concurso_id ORDER BY id");
						if ($result->num_rows > 0) {
							$template_id = 'bibliografia';
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
											$template_conteudo .= "<a href='elemento.php?id=$elemento_id' target='_blank'><li class='list-group-item'>$referencia_titulo : $referencia_autor</li></a>";
										} else {
											$template_conteudo .= "<a href='elemento.php?id=$elemento_id' target='_blank'><li class='list-group-item'>$referencia_titulo : $referencia_autor : $referencia_capitulo</li></a>";
										}
									}
								}
							}
							$template_conteudo .= "</ul>";
							include 'templates/page_element.php';
						}
						
						// IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS
						
						$result = $conn->query("SELECT DISTINCT elemento_id FROM Verbetes_elementos WHERE page_id = $topico_id AND tipo = 'imagem' AND tipo_pagina = 'verbete' AND concurso_id = $concurso_id");
						$count = 0;
						if ($result->num_rows > 0) {
							$template_id = 'imagens';
							$template_titulo = 'Imagens';
							$template_botoes = false;
							$template_conteudo = false;
							$active = 'active';
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, arquivo, estado FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row2 = $result2->fetch_assoc()) {
										$imagem_titulo = $row2['titulo'];
										$imagem_arquivo = $row2['arquivo'];
										$imagem_estado = $row2['estado'];
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
                                                <a href='elemento.php?id=$elemento_id' target='_blank'>
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

        <!-- COLUNA DIREITA COLUNA DIRETA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA -->

        <div id='coluna_direita' class='<?php echo $coluna_classes; ?> anotacoes_collapse collapse show'>
					
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
	
	//IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL
	
	$template_modal_div_id = 'modal_imagens_form';
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
                <div class='btn btn-primary btn-sm float-left'>
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
	
	//LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL
	
	$template_modal_div_id = 'modal_referencia_form';
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
	
	// VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL
	
	$template_modal_div_id = 'modal_videos_form';
	$template_modal_titulo = 'Adicionar vídeo ou aula';
	$template_modal_body_conteudo = "
                    <p>No momento, apenas vídeos do Youtube podem ser adicionados. Outras opções estarão disponíveis em breve.</p>
                    <div class='md-form mb-2'>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate'
                               required>
                        <label data-error='inválido' data-success='válido'
                               for='novo_video_link'>Link para o vídeo (Youtube)</label>
                    </div>
	";
	
	include 'templates/modal.php';
	
	// ESTADO MODAL ESTADO MODAL ESTADO MODAL ESTADO MODAL ESTADO MODAL ESTADO MODAL ESTADO MODAL ESTADO MODAL
	
	$active1 = false;
	$active2 = false;
	$active3 = false;
	$active4 = false;
	
	if ($estado_pagina == 1) {
		$active1 = 'selected';
	} elseif ($estado_pagina == 2) {
		$active2 = 'selected';
	} elseif ($estado_pagina == 3) {
		$active3 = 'selected';
	} else {
		$active4 = 'selected';
	}
	
	$template_modal_div_id = 'modal_estado';
	$template_modal_titulo = 'Qualidade da página';
	$template_modal_body_conteudo = "
        <p>Por favor, determine abaixo sua avaliação sobre o estado atual desta página:</p>
        <div class='md-form mb-2'>
            <span>Estado</span>
            <select class='mdb-select' name='novo_estado_pagina'>
                <option value='' disabled>Escolha uma opção</option>
                <option value='1' $active1>Rascunho</option>
                <option value='2' $active2>Aceitável</option>
                <option value='3' $active3>Desejável</option>
                <option value='4' $active4>Excepcional</option>
            </select>
        </div>
    ";
	
	if ($estado_pagina != 0) {
		include 'templates/modal.php';
	}
	
	// FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	if ($comments->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $comments->fetch_assoc()) {
			$timestamp_comentario = $row['timestamp'];
			$texto_comentario = $row['comentario'];
			$autor_comentario_id = $row['user_id'];
			$result2 = $conn->query("SELECT apelido FROM Usuarios WHERE id = $autor_comentario_id");
			if ($result2->num_rows > 0) {
				while ($row2 = $result2->fetch_assoc()) {
					$autor_comentario_apelido = $row2['apelido'];
					break;
				}
			} else {
				$autor_comentario_apelido = false;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p><strong><a href='perfil.php?pub_user_id=$autor_comentario_id' target='_blank'>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
	}
	$apelido = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id AND apelido IS NOT NULL");
	if ($apelido->num_rows > 0) {
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
	
	$template_modal_div_id = 'modal_verbetes_relacionados';
	$template_modal_titulo = 'Verbetes relacionados';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = $breadcrumbs;
	include 'templates/modal.php';

?>

</div>
</body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>
