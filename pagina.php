<?php
	
	include 'engine.php';
	$nao_contar = false;
	if (!isset($_GET['pagina_id'])) {
		header('Location:escritorio.php');
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
			$concurso_id = return_concurso_id_topico($topico_id);
			$topico_anterior = false;
			$topico_proximo = false;
		} elseif ($pagina_tipo == 'materia') {
			$materia_id = $pagina_item_id;
			$concurso_id = return_concurso_id_materia($materia_id);
		} elseif ($pagina_tipo == 'elemento') {
			$elemento_id = $pagina_item_id;
		} elseif ($pagina_tipo == 'grupo') {
			$grupo_id = $pagina_item_id;
		}
	} else {
		if (isset($_GET['topico_id'])) {
			$topico_id = $_GET['topico_id'];
			$pagina_tipo = 'topico';
			$concurso_id = return_concurso_id_topico($topico_id);
			$pagina_id = return_pagina_id($topico_id, $pagina_tipo);
		} elseif (isset($_GET['elemento_id'])) {
			$elemento_id = $_GET['elemento_id'];
			$pagina_tipo = 'elemento';
			$pagina_id = return_pagina_id($elemento_id, $pagina_tipo);
		} elseif (isset($_GET['materia_id'])) {
			$materia_id = $_GET['materia_id'];
			$concurso_id = return_concurso_id_materia($materia_id);
			$pagina_tipo = 'materia';
			$pagina_id = return_pagina_id($materia_id, $pagina_tipo);
		} elseif (isset($_GET['grupo_id'])) {
			$grupo_id = $_GET['grupo_id'];
			$pagina_tipo = 'grupo';
			$pagina_id = return_pagina_id($grupo_id, $pagina_tipo);
		} else {
			header('Location:escritorio.php');
		}
	}
	
	if ($pagina_tipo == 'topico') {
		$topicos = $conn->query("SELECT estado_pagina, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE concurso_id = $concurso_id AND id = $topico_id");
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
	}
	
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$conn->query("INSERT INTO Forum (user_id, pagina_id, comentario)  VALUES ($user_id, $pagina_id, '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $pagina_id, 'forum', $pagina_tipo)");
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		$conn->query("UPDATE Paginas SET estado = $novo_estado_pagina WHERE id = $pagina_id");
		$pagina_estado = $novo_estado_pagina;
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_video_link'])) {
		$novo_video_link = $_POST['novo_video_link'];
		$novo_video_data = get_youtube($novo_video_link);
		if ($novo_video_data != false) {
			$novo_video_titulo = $novo_video_data['title'];
			$novo_video_titulo = mysqli_real_escape_string($conn, $novo_video_titulo);
			$novo_video_autor = $novo_video_data['author_name'];
			$novo_video_autor = mysqli_real_escape_string($conn, $novo_video_autor);
			$novo_video_thumbnail = $novo_video_data['thumbnail_url'];
			$novo_video_iframe = $novo_video_data['html'];
			$novo_video_iframe = mysqli_real_escape_string($novo_video_iframe);
			$videos = $conn->query("SELECT id FROM Elementos WHERE link = $novo_video_link");
			if ($videos->num_rows > 0) {
				while ($video = $videos->fetch_assoc()) {
					$video_id = $video['id'];
					$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $video_id, 'video', $user_id)");
				}
			} else {
			    $novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
			    $conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('video', '$novo_video_titulo', $user_id)");
                $novo_video_etiqueta_id = $conn->insert_id;
                $conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ($novo_video_etiqueta_id, 'video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
                $novo_video_elemento_id = $conn->insert_id;
                $conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $novo_video_elemento_id, 'video', $user_id)");
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'insercao_video')");
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
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $pagina_id, $pagina_tipo, 'pagina')");
	}
?>
<body class="grey lighten-5">
<?
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row justify-content-between">
        <div class='py-2 text-left col'>
					<?php
						if ($pagina_tipo == 'topico') {
							include 'templates/verbetes_relacionados.php';
							if ($topico_anterior != false) {
								$topico_anterior_link = "verbete.php?topico_id=$topico_anterior";
								echo "<span id='verbete_anterior' class='mx-1' title='Verbete anterior'><a href='$topico_anterior_link'><i class='fal fa-arrow-left fa-fw'></i></a></span>";
							}
							echo "<span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='modal' data-target='#modal_verbetes_relacionados'><a href='javascript:void(0);'><i class='fal fa-project-diagram fa-fw'></i></a></span>";
							if ($topico_proximo != false) {
								$topico_proximo_link = "verbete.php?topico_id=$topico_proximo";
								echo "<span id='verbete_proximo' class='mx-1' title='Próximo verbete'><a href='$topico_proximo_link'><i class='fal fa-arrow-right fa-fw'></i></a></span>";
							}
						}
					?>
        </div>
        <div class="py-2 text-center col">
            <span id="show_add_elements" class="mx-1" title=""><a href="javascript:void(0)"
                                                                  title="Adicionar elemento"><i
                            class="fad fa-2x fa-plus-circle fa-fw"></i></a></span>
            <span class="mx-1 add_elements" title="" data-toggle="modal" data-target="#modal_referencia_form"
                  title="Adicionar referência de leitura"><a href="javascript:void(0);" class="text-success"><i
                            class="fad fa-book fa-2x fa-fw"></i></a></span>
            <span class="mx-1 add_elements" title="" data-toggle="modal" data-target="#modal_videos_form"
                  title="Adicionar conteúdo em vídeo"><a href="javascript:void(0);" class="text-info"><i
                            class="fad fa-film fa-2x fa-fw"></i></a></span>
            <span class="mx-1 add_elements" title="" data-toggle="modal" data-target="#modal_imagens_form"
                  title="Adicionar imagem"><a href="javascript:void(0);" class="text-danger"><i
                            class="fad fa-image fa-2x fa-fw"></i></a></span>
            <!--<span id="" class="mx-1" title="" data-toggle="modal" data-target="#modal_audio_form"><a href="javascript:void(0);"><i class="fad fa-microphone fa-fw"></i></a></span>-->
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
			$template_subtitulo = $concurso_sigla;
		} elseif ($pagina_tipo == 'elemento') {
			$template_titulo = $elemento_titulo;
			$template_subtitulo = $elemento_autor;
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row justify-content-around <?php echo $row_classes ?>">
        <div id='coluna_esquerda' class="<?php echo $coluna_classes ?>">
					<?php
						$template_id = 'verbete';
						$template_titulo = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						$videos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE page_id = $pagina_id AND tipo = 'video' AND pagina_tipo = '$pagina_tipo'");
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
                                                <a href='elemento.php?id=$elemento_id' target='_blank'>
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
					
					
					?>
        </div>
        <div id='coluna_direita' class="<?php echo $coluna_classes ?>">
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
	$template_modal_div_id = 'modal_verbetes_relacionados';
	$template_modal_titulo = 'Verbetes relacionados';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = $breadcrumbs;
	include 'templates/modal.php';
	
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
	if ($pagina_estado == 1) {
		$active1 = 'selected';
	} elseif ($pagina_estado == 2) {
		$active2 = 'selected';
	} elseif ($pagina_estado == 3) {
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
	include 'templates/modal.php';
	
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


?>

</body>

<?php
	include 'templates/footer.html';
	$mdb_select = true;
	$hide_add_elements = true;
	include 'templates/html_bottom.php';
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>