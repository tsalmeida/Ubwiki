<?php
	
	include 'engine.php';
	
	if (isset($_GET['id'])) {
		$elemento_id = $_GET['id'];
	}
	
	if (isset($_POST['elemento_novo_titulo'])) {
		$elemento_novo_titulo = $_POST['elemento_novo_titulo'];
		$elemento_novo_autor = $_POST['elemento_novo_autor'];
		$elemento_novo_capitulo = $_POST['elemento_novo_capitulo'];
		$elemento_novo_ano = $_POST['elemento_novo_ano'];
		$update = $conn->query("UPDATE Elementos SET titulo = '$elemento_novo_titulo', autor = '$elemento_novo_autor', capitulo = '$elemento_novo_capitulo', ano = '$elemento_novo_ano' WHERE id = $elemento_id");
		error_log("UPDATE Elementos SET titulo = '$elemento_novo_titulo', autor = '$elemento_novo_autor', capitulo = '$elemento_novo_capitulo', ano = '$elemento_novo_ano' WHERE id = $elemento_id");
	}
	
	$result = $conn->query("SELECT criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
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
	
	// VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
	
	$result = $conn->query("SELECT verbete_content, user_id FROM Verbetes WHERE elemento_id = $elemento_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$verbete_content = $row['verbete_content'];
		}
	} else {
		$verbete_content = '%7B%22ops%22:%5B%7B%22insert%22:%22Este%20verbete%20ainda%20n%C3%A3o%20come%C3%A7ou%20a%20ser%20escrito.%5Cn%22%7D%5D%7D';
	}
	
	if (isset($_POST['quill_novo_verbete_html'])) {
		$novo_verbete_html = $_POST['quill_novo_verbete_html'];
		$novo_verbete_text = $_POST['quill_novo_verbete_text'];
		$novo_verbete_content = $_POST['quill_novo_verbete_content'];
		$novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
		$result = $conn->query("SELECT id FROM Verbetes WHERE elemento_id = $elemento_id");
		if ($result->num_rows > 0) {
			$result = $conn->query("UPDATE Verbetes SET verbete_html = '$novo_verbete_html', verbete_text = '$novo_verbete_text', verbete_content = '$novo_verbete_content', user_id = '$user_id' WHERE elemento_id = $elemento_id");
			$result = $conn->query("INSERT INTO Verbetes_arquivo (elemento_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$elemento_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
		} else {
			$result = $conn->query("INSERT INTO Verbetes (elemento_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$elemento_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
			$result = $conn->query("INSERT INTO Verbetes_arquivo (elemento_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$elemento_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
		}
		$verbete_content = $novo_verbete_content;
	}
	
	$verbete_content = urldecode($verbete_content);
	
	// ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO
	
	$result = $conn->query("SELECT anotacao_content FROM Anotacoes WHERE elemento_id = $elemento_id AND user_id = $user_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$anotacoes_content = $row['anotacao_content'];
		}
	} else {
		$anotacoes_content = '';
	}
	
	if (isset($_POST['quill_novo_anotacoes_html'])) {
		$novo_anotacoes_html = $_POST['quill_novo_anotacoes_html'];
		$novo_anotacoes_text = $_POST['quill_novo_anotacoes_text'];
		$novo_anotacoes_content = $_POST['quill_novo_anotacoes_content'];
		$novo_anotacoes_html = strip_tags($novo_anotacoes_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
		$result = $conn->query("SELECT id FROM Anotacoes WHERE elemento_id = $elemento_id AND user_id = $user_id");
		if ($result->num_rows > 0) {
			$result = $conn->query("UPDATE Anotacoes SET anotacao_html = '$novo_anotacoes_html', anotacao_text = '$novo_anotacoes_text', anotacao_content = '$novo_anotacoes_content', user_id = '$user_id' WHERE elemento_id = $elemento_id");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (elemento_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$elemento_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		} else {
			$result = $conn->query("INSERT INTO Anotacoes (elemento_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$elemento_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (elemento_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$elemento_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		}
		$anotacoes_content = $novo_anotacoes_content;
	}
	
	$anotacoes_content = urldecode($anotacoes_content);
	
	// FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$insert = $conn->query("INSERT INTO Forum (user_id, elemento_id, comentario)  VALUES ($user_id, $elemento_id, '$novo_comentario')");
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
	include 'templates/imagehandler.php';

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
            <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'><a
                        href='javascript:void(0);'><i
                            class='fal fa-comments-alt fa-fw'></i></a></span>
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
        <div id='coluna_esquerda' class='col-lg-5 col-sm-12'>
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
						
						$dados_elemento = "
						              <ul class='list-group'>
                                        <li class='list-group-item'><strong>Criado em:</strong> $criacao_elemento</li>
                                        <li class='list-group-item'><strong>Título:</strong> $titulo_elemento</li>
                                        <li class='list-group-item'><strong>Autor:</strong> $autor_elemento</li>
                                        <li class='list-group-item'><strong>Capítulo:</strong> $capitulo_elemento</li>
                                        <li class='list-group-item'><strong>Ano:</strong> $ano_elemento</li>
                                        <li class='list-group-item'><a href='$link_elemento' target='_blank'>Link original</a></li>
                                        <li class='list-group-item'><a href='../imagens/verbetes/$arquivo_elemento' target='_blank'>Arquivo</a></li>
                                        <li class='list-group-item'><a href='../imagens/verbetes/thumbnails/$arquivo_elemento' target='_blank'>Versão reduzida</a></li>
                                        <li class='list-group-item'>Adicionado pelo usuário <strong>$user_apelido_elemento</strong></li>
                                      </ul>
						";
						
						$template_id = 'dados_elemento_div';
						$template_titulo = 'Dados';
						$template_botoes = "
                                  <a data-toggle='modal' data-target='#modal_elemento_form' href=''>
                                    <i class='fal fa-edit fa-fw'></i>
                                  </a>
                            ";
						$template_conteudo = $dados_elemento;
						include 'templates/page_element.php';
						
						//VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
						
						$template_id = 'verbete';
						$template_titulo = 'Verbete';
						$template_botoes = "
<a href='historico_verbete.php?elemento=$elemento_id' target='_blank'><i class='fal fa-history fa-fw'></i></a>
<span class='verbete_editor_collapse collapse' id='travar_verbete' data-toggle='collapse'
      data-target='.verbete_editor_collapse' title='travar para edição'><a
            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
<span class='verbete_editor_collapse collapse show' id='destravar_verbete' data-toggle='collapse'
      data-target='.verbete_editor_collapse' title='permitir edição'><a
            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>
        ";
						
						$template_quill_unique_name = 'verbete';
						$template_quill_initial_state = 'leitura';
						$template_quill_conteudo = $verbete_content;
						
						$template_conteudo = include 'templates/quill_form.php';
						include 'templates/page_element.php';
					
					?>
        </div>
        <div id='coluna_direita' class='col-lg-5 col-sm-12 anotacoes_collapse collapse show'>
					
					<?php
						$template_id = 'sticky_anotacoes';
						$template_titulo = 'Anotações privadas';
						$template_botoes = "<span class='anotacoes_editor_collapse collapse show' id='travar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='travar para edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='anotacoes_editor_collapse collapse' id='destravar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='permitir edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>";
						
						$template_quill_unique_name = 'anotacoes';
						$template_quill_initial_state = 'edicao';
						$template_quill_conteudo = $anotacoes_content;
						
						$template_conteudo = include 'templates/quill_form.php';
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
	$template_modal_body_conteudo .= "<div class='md-form mb-2'>";
	$template_modal_body_conteudo .= "<input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$titulo_elemento'>";
	$template_modal_body_conteudo .= "<label for='elemento_novo_titulo'>Título</label>";
	$template_modal_body_conteudo .= "</div>";
	
	$template_modal_body_conteudo .= "<div class='md-form mb-2'>";
	$template_modal_body_conteudo .= "<input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$autor_elemento'>";
	$template_modal_body_conteudo .= "<label for='elemento_novo_autor'>Autor</label>";
	$template_modal_body_conteudo .= "</div>";
	
	$template_modal_body_conteudo .= "<div class='md-form mb-2'>";
	$template_modal_body_conteudo .= "<input type='text' id='elemento_novo_capitulo' name='elemento_novo_capitulo' class='form-control' value='$capitulo_elemento'>";
	$template_modal_body_conteudo .= "<label for='elemento_novo_capitulo'>Capítulo</label>";
	$template_modal_body_conteudo .= "</div>";
	
	$template_modal_body_conteudo .= "<div class='md-form mb-2'>";
	$template_modal_body_conteudo .= "<input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$ano_elemento'>";
	$template_modal_body_conteudo .= "<label for='elemento_novo_ano'>Ano</label>";
	$template_modal_body_conteudo .= "</div>";
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	$result = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE elemento_id = $elemento_id");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $result->fetch_assoc()) {
			$timestamp_comentario = $row['timestamp'];
			$texto_comentario = $row['comentario'];
			$autor_comentario = $row['user_id'];
			$result2 = $conn->query("SELECT apelido FROM usuarios WHERE id = $autor_comentario");
			while ($row2 = $result2->fetch_assoc()) {
				$autor_comentario = $row2['apelido'];
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p><strong>$autor_comentario</strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
	}
	$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id AND apelido IS NOT NULL");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <p>Adicione seu comentário:</p>
                    <input type='text' id='novo_comentario' name='novo_comentario' class='form-control validate' required></input>
                    <label data-error='preenchimento incorreto' data-success='preenchimento válido' for='novo_comentario'></label>
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
	include 'templates/sticky_anotacoes.html';
	include 'templates/bookmarks.php';
	include 'templates/lock_unlock_quill.php';
?>
</html>
