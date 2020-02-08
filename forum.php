<?php
	
	include 'engine.php';
	
	if (isset($_GET['pagina_id'])) {
		$pagina_id = (int)$_GET['pagina_id'];
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
		} else {
            header('Location:ubwiki.php');
			exit();
		}
	} else {
	    $pagina_id = 1;
	    $pagina_titulo = 'Fórum geral';
    }
	
	$check_compartilhamento = check_compartilhamento($pagina_id, $user_id);
	
	if (isset($_GET['topico_id'])) {
		$forum_topico_id = $_GET['topico_id'];
		$forum_topico_titulo = return_forum_topico_titulo($forum_topico_id);
	} else {
		$forum_topico_id = false;
		$forum_topico_titulo = 'Debate geral';
		$topico_user_apelido = '.';
		$topico_user_id = false;
	}
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$novo_comentario = strip_tags($novo_comentario, false);
		if ($forum_topico_id == false) {
			$forum_topico_id_novo = "NULL";
		} else {
			$forum_topico_id_novo = $forum_topico_id;
		}
		$conn->query("INSERT INTO Forum (user_id, pagina_id, pagina_tipo, topico_id, tipo, comentario_text) VALUES ($user_id, $pagina_id, '$pagina_tipo', $forum_topico_id_novo, 'comentario', '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $pagina_id, 'forum', $pagina_tipo)");
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_topico_titulo'])) {
		$novo_topico_titulo = $_POST['novo_topico_titulo'];
		$novo_topico_titulo = mysqli_real_escape_string($conn, $novo_topico_titulo);
		$novo_topico_titulo = strip_tags($novo_topico_titulo, false);
		$conn->query("INSERT INTO Forum (user_id, pagina_id, pagina_tipo, tipo, comentario_text) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'topico', '$novo_topico_titulo')");
		$novo_topico_id = $conn->insert_id;
		if ($_POST['novo_topico_texto'] != false) {
			$novo_topico_comentario = $_POST['novo_topico_texto'];
			$novo_topico_comentario = mysqli_real_escape_string($conn, $novo_topico_comentario);
			$novo_topico_comentario = strip_tags($novo_topico_comentario, false);
			$conn->query("INSERT INTO Forum (user_id, pagina_id, pagina_tipo, tipo, topico_id, comentario_text) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'comentario', $novo_topico_id, '$novo_topico_comentario')");
		} else {
			$conn->query("INSERT INTO Forum (user_id, pagina_id, pagina_tipo, tipo, topico_id, comentario_text) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'comentario', $novo_topico_id, FALSE)");
		}
		header("Location:forum.php?pagina_id=$pagina_id&topico_id=$novo_topico_id");
	}
	
	$pagina_tipo = 'forum';
	
	include 'templates/html_head.php';

?>

<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container">
	<?php
		$template_titulo = $pagina_titulo;
		if ($pagina_id != 1) {
		    $template_subtitulo = "Fórum / <a href='pagina.php?pagina_id=$pagina_id'>Página</a>";
		} else {
		    $template_subtitulo = "Fórum / <a href='ubwiki.php'>Ubwiki</a>";
        }
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 px-3">
					<?php
						$template_id = 'forum_pagina';
						$template_titulo = 'Tópicos de debate';
						$template_conteudo = false;
						
						$item_classes = 'row b-0 border-top m-0 mt-1 p-2 list-group-item-action';

						$template_conteudo .= "<p>Selecione um tópico para participar do debate.</p>";

						if ($forum_topico_id == false) {
							$lista_active = 'list-group-item-info';
						} else {
							$lista_active = false;
						}
						
						$template_conteudo .= "<a href='forum.php?pagina_id=$pagina_id' class='$item_classes $lista_active'><strong>Debate geral</strong></a>";
						$query = "SELECT topico_id FROM Forum WHERE pagina_id = $pagina_id AND topico_id IS NOT NULL ORDER BY id DESC";
						$debates_recentes = $conn->query($query);
						$debates_lista = array();
						if ($debates_recentes->num_rows > 0) {
							while ($debate_recente = $debates_recentes->fetch_assoc()) {
								$debate_id = $debate_recente['topico_id'];
								if (in_array($debate_id, $debates_lista)) {
									continue;
								} else {
									array_push($debates_lista, $debate_id);
								}
								$debates = $conn->query("SELECT id, comentario_text, user_id FROM Forum WHERE id = $debate_id");
								if ($debates->num_rows > 0) {
									while ($debate = $debates->fetch_assoc()) {
										$lista_debate_id = $debate['id'];
										$lista_debate_titulo = $debate['comentario_text'];
										$lista_debate_user_id = $debate['user_id'];
										$lista_debate_user_apelido = return_apelido_user_id($lista_debate_user_id);
										if ($lista_debate_id == $forum_topico_id) {
											$topico_user_id = $lista_debate_user_id;
											$topico_user_apelido = $lista_debate_user_apelido;
											$lista_active = 'list-group-item-info';
										} else {
											$lista_active = false;
										}
										$template_conteudo .= "<a href='forum.php?pagina_id=$pagina_id&topico_id=$lista_debate_id' class='$item_classes $lista_active'>$lista_debate_titulo</a>";
									}
								}
							}
						}
                        $template_botoes = "<span data-toggle='modal' data-target='#modal_novo_topico' title='Novo tópico de debate'><a href='javascript:void(0);' class='text-info'><i class='fad fa-plus-square fa-fw'></i></a></span>";
                        $template_conteudo .= "
					        <div class='row d-flex justify-content-center mt-3'>
						        <span data-toggle='modal' data-target='#modal_novo_topico'><button class='$button_classes btn-info btn-sm'>Novo tópico de debate</button></span>
					        </div>
				        ";

						include 'templates/page_element.php';
					?>
        </div>
        <div id="coluna_direita" class="col-lg-7 px-3">
					<?php
						$template_id = 'comentarios';
						$template_titulo = false;
						$template_conteudo = false;
						
						if ($forum_topico_id != false) {
							$comments = $conn->query("SELECT timestamp, comentario_text, user_id FROM Forum WHERE pagina_id = $pagina_id AND topico_id = $forum_topico_id AND tipo = 'comentario' ORDER BY id");
						} else {
							$comments = $conn->query("SELECT timestamp, comentario_text, user_id FROM Forum WHERE pagina_id = $pagina_id AND topico_id IS NULL AND tipo = 'comentario' ORDER BY id");
						}
						
						$template_conteudo .= "
                            <div class='row grey lighten-4 rounded p-2 mt-1'>
                                <div class='col'>
                                	<div class='row justify-content-start'><a href='pagina.php?user_id=$topico_user_id' class='text-light'>$topico_user_apelido</a></div>
                                    <div class='row'>
                                    	<h2 class='col m-0 text-center'>$forum_topico_titulo</h2>
                                    </div>
                                    <div class='row text-light'>.</div>
                                </div>
                            </div>
                        ";
						
						if ($comments->num_rows > 0) {
							while ($comment = $comments->fetch_assoc()) {
								$timestamp_comentario = $comment['timestamp'];
								$texto_comentario = $comment['comentario_text'];
								if ($texto_comentario == false) {
									continue;
								}
								$autor_comentario_id = $comment['user_id'];
								$autor_comentario_apelido = return_apelido_user_id($autor_comentario_id);
								$autor_comentario_avatar_info = return_avatar($autor_comentario_id);
								$autor_comentario_avatar = $autor_comentario_avatar_info[0];
								$autor_comentario_cor = $autor_comentario_avatar_info[1];
								
								$template_conteudo .=
									"
									  <div class='row grey lighten-4 rounded p-2 mt-1'>
										<div class='col'>
                                            <span class='rounded row justify-content-between'>
                                              <a href='pagina.php?user_id=$autor_comentario_id' class='ml-1'>
                                                <span class='$autor_comentario_cor'>
                                                <i class='fad $autor_comentario_avatar'></i>
                                                <span class='text-primary'>$autor_comentario_apelido</span>
                                              </a>
                                              <span class='text-light comentario-timestamp'>
                                                  <em><small>$timestamp_comentario</small></em>
                                              </span>
                                            </span>
                                            <div class='row bg-white border rounded p-2 mt-1'>
                                                $texto_comentario
										    </div>
										</div>
									  </div>
              						";
							}
						}
						
						if ($user_apelido != false) {
							$template_conteudo .=
								"
	                            <form method='post'>
                                    <div class='md-form row px-0 mt-2 mb-0'>
                                        <textarea id='novo_comentario' name='novo_comentario' class='form-control border rounded p-2 row' rows='3' placeholder='Escreva aqui seu comentário' required></textarea>
                                    </div>
                                    <div class='row d-flex justify-content-center'>
                                        <button class='$button_classes btn-info btn-sm mt-0'>Enviar comentário</button>
                                    </div>
                                </form><!--
                                <div class='row d-flex justify-content-center'>
	                                <canvas class='visualizer rounded mb-1' height='60px'></canvas>
								</div>
                                <div class='row d-flex justify-content-center rounded border'>
                                    <section class='main-controls'>
                                        <div id='buttons'>
                                            <button class='$button_classes btn-success record'>Gravar áudio</button>
                                            <button class='$button_classes btn-danger stop'>Interromper gravação</button>
                                        </div>
                                    </section>
                                </div>
                                <div class='row rounded border grey lighten-5 mt-1 p-1'>
                                    <section class='sound-clips'></section>
                                </div>-->
	                        ";
						} else {
							$template_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em <a href='escritorio.php'>seu escritório</a>.</strong></p>";
						}
						
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
<?php
	$template_modal_div_id = 'modal_novo_topico';
	$template_modal_titulo = 'Iniciar nova discussão';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .=
		"
		<div class='md-form'>
			<input type='text' name='novo_topico_titulo' id='novo_topico_titulo' class='form-control' required>
			<label for='novo_topico_titulo'>Título</label>
		</div>
		<div class='md-form'>
			<textarea class='form-control border rounded p-2 row' rows='3' placeholder='Escreva aqui seu comentário (opcional)' name='novo_topico_texto' id='novo_topico_texto'></textarea>
		</div>
	";
	include 'templates/modal.php';
?>

</body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>
