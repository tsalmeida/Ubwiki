<?php
	
	include 'engine.php';
	
	if (isset($_GET['id'])) {
		$elemento_id = $_GET['id'];
	}
	
	$nao_contar = false;
	
	if (isset($_POST['submit_elemento_dados'])) {
		$elemento_mudanca_estado = 0;
		if (isset($_POST['elemento_mudanca_estado'])) {
			$elemento_mudanca_estado = 1;
		}
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
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_dados')");
		$nao_contar = true;
	}
	
	$result = $conn->query("SELECT estado, criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$estado_elemento = $row['estado'];
			$criacao_elemento = $row['criacao'];
			$tipo_elemento = $row['tipo'];
			$titulo_elemento = $row['titulo'];
			$autor_elemento = $row['autor'];
			$capitulo_elemento = $row['capitulo'];
			$ano_elemento = $row['ano'];
			$link_elemento = $row['link'];
			$iframe_elemento = $row['iframe'];
			$arquivo_elemento = $row['arquivo'];
			$user_elemento_id = $row['user_id'];
			$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_elemento_id");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$user_apelido_elemento = $row['apelido'];
				}
			} else {
				$user_apelido_elemeto = "(usuário não-identificado)";
			}
			break;
		}
	}
	
	$elemento_bookmark = false;
	$bookmark = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND elemento_id = $elemento_id AND active = 1");
	if ($bookmark->num_rows > 0) {
		while ($row = $bookmark->fetch_assoc()) {
			$elemento_bookmark = $row['bookmark'];
			break;
		}
	}
	
	// FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$insert = $conn->query("INSERT INTO Forum (user_id, elemento_id, comentario)  VALUES ($user_id, $elemento_id, '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_forum')");
		$nao_contar = true;
	}
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var elemento_id=$elemento_id;
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento')");
	}
?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid bg-white">
    <div class='row'>
        <div class='col-lg-8 col-sm-12'></div>
        <div class='col-lg-4 col-sm-12'>
            <div class='text-right py-2'>
            <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
	                    $comments = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE elemento_id = $elemento_id");
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
								if ($elemento_bookmark == false) {
									echo "
              <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$elemento_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$elemento_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								} else {
									echo "
              <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$elemento_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$elemento_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								}
							?>
            </div>
        </div>
    </div>
</div>
<div id='page_height' class='container-fluid'>
    <div class='row d-flex justify-content-center'>
        <div class='col-lg-10 col-sm-12 text-center py-2'>
					<?php
						$template_titulo = $titulo_elemento;
						include 'templates/titulo.php'
					?>
        </div>
    </div>
    <div class='row justify-content-around'>
        <div id='coluna_esquerda' class='<?php echo $coluna_classes; ?>'>
					<?php
						if ($tipo_elemento == 'imagem') {
							$template_id = 'imagem_div';
							$template_titulo = 'Imagem';
							$template_botoes = false;
							$template_conteudo = "<a href='../imagens/verbetes/$arquivo_elemento' target='_blank'><img class='imagem_pagina border' src='../imagens/verbetes/$arquivo_elemento'></img></a>";
							include 'templates/page_element.php';
						} elseif ($tipo_elemento == 'video') {
							$template_div = 'video_div';
							$template_titulo = 'Vídeo';
							$template_botoes = false;
							$iframe_elemento = base64_decode($iframe_elemento);
							$template_conteudo = $iframe_elemento;
							$template_conteudo_class = 'text-center';
							include 'templates/page_element.php';
						}
						
						if ($estado_elemento == true) {
							$estado_elemento_visivel = 'publicado';
						} else {
							$estado_elemento_visivel = 'removido';
						}
						
						$dados_elemento = false;
						$dados_elemento .= "
                            <ul class='list-group'>
						        <li class='list-group-item'><strong>Criado em:</strong> $criacao_elemento</li>
						        <li class='list-group-item'><strong>Estado de publicação:</strong> $estado_elemento_visivel</li>";
						if ($titulo_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Título:</strong> $titulo_elemento</li>";
						}
						if ($autor_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Autor:</strong> $autor_elemento</li>";
						}
						if ($capitulo_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Capítulo:</strong> $capitulo_elemento</li>";
						}
						if ($ano_elemento != 0) {
							$dados_elemento .= "<li class='list-group-item'><strong>Ano:</strong> $ano_elemento</li>";
						}
						if ($link_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><a href='$link_elemento' target='_blank'>Link original</a></li>";
						}
						$dados_elemento .= "<li class='list-group-item'>Adicionado pelo usuário <strong><a href='perfil.php?pub_user_id=$user_elemento_id' target='_blank'>$user_apelido_elemento</a></strong></li>";
						$dados_elemento .= "</ul>";
						
						$template_id = 'dados_elemento_div';
						$template_titulo = 'Dados';
						$template_botoes = "
                                  <a data-toggle='modal' data-target='#modal_elemento_form' href=''>
                                    <i class='fal fa-edit fa-fw'></i>
                                  </a>
                            ";
						$template_conteudo = false;
						$template_conteudo .= $dados_elemento;
						include 'templates/page_element.php';
						
						//VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
						
						$template_id = 'verbete_elemento';
						$template_titulo = 'Verbete';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					
					?>
        </div>
        <div id='coluna_direita' class='<?php echo $coluna_classes; ?> anotacoes_collapse collapse show'>
					
					<?php
						
						$template_id = 'anotacoes_elemento';
						$template_titulo = 'Anotações privadas';
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					
					?>

        </div>


    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
<?php
	$template_modal_div_id = 'modal_elemento_form';
	$template_modal_titulo = 'Alterar dados do elemento';
	$template_modal_body_conteudo = false;
	
	$estado_elemento_checkbox = false;
	if ($estado_elemento == true) {
		$estado_elemento_checkbox = 'checked';
	}
	$template_modal_body_conteudo .= "
        <div class='form-check pl-0'>
            <input type='checkbox' class='form-check-input' id='elemento_mudanca_estado' name='elemento_mudanca_estado' $estado_elemento_checkbox>
            <label class='form-check-label' for='elemento_mudanca_estado'>Adequado para publicação</label>
        </div>
    
        <div class='md-form mb-2'>
            <input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$titulo_elemento'>
            <label for='elemento_novo_titulo'>Título</label>
        </div>
    
        <div class='md-form mb-2'>
            <input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$autor_elemento'>
            <label for='elemento_novo_autor'>Autor</label>
        </div>";
	if ($tipo_elemento == 'referencia') {
		$template_modal_body_conteudo .= "<div class='md-form mb-2'>
            <input type='text' id='elemento_novo_capitulo' name='elemento_novo_capitulo' class='form-control' value='$capitulo_elemento'>
            <label for='elemento_novo_capitulo'>Capítulo</label>
        </div>";
	}
	$template_modal_body_conteudo .= "
		<div class='md-form mb-2'>
            <input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$ano_elemento'>
            <label for='elemento_novo_ano'>Ano</label>
        </div>
	";
	
	$template_modal_submit_name = 'submit_elemento_dados';
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	if ($comments->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $comments->fetch_assoc()) {
			$timestamp_comentario = $row['timestamp'];
			$texto_comentario = $row['comentario'];
			$autor_comentario_id = $row['user_id'];
			$result2 = $conn->query("SELECT apelido FROM usuarios WHERE id = $autor_comentario_id");
			while ($row2 = $result2->fetch_assoc()) {
				$autor_comentario_apelido = $row2['apelido'];
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p><strong><a href='perfil.php?pub_user_id=$autor_comentario_id' target='_blank'>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este elemento.</strong></p>";
	}
	$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id AND apelido IS NOT NULL");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <textarea id='novo_comentario' name='novo_comentario' class='md-textarea form-control' rows='3' required></textarea>
                    <label for='novo_comentario'>Adicione seu comentário:</label>
                </div>
            ";
	} else {
		$template_modal_body_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em sua <a href='usuario.php' target='_blank'>página de usuário</a>.</strong></p>";
	}
	
	include 'templates/modal.php';


?>
</body>
<?php
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_elemento';
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/footer.html';
?>
</html>
