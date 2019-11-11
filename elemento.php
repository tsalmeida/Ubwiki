<?php
	
	include 'engine.php';
	
	if (isset($_GET['id'])) {
		$id_elemento = $_GET['id'];
	}
	
	if (isset($_POST['elemento_novo_titulo'])) {
		$elemento_novo_titulo = $_POST['elemento_novo_titulo'];
		$elemento_novo_autor = $_POST['elemento_novo_autor'];
		$elemento_novo_capitulo = $_POST['elemento_novo_capitulo'];
		$elemento_novo_ano = $_POST['elemento_novo_ano'];
		$update = $conn->query("UPDATE Elementos SET titulo = '$elemento_novo_titulo', autor = '$elemento_novo_autor', capitulo = '$elemento_novo_capitulo', ano = $elemento_novo_ano WHERE id = $id_elemento");
	}
	
	$result = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user_email'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$user_id = $row['id'];
			$user_tipo = $row['tipo'];
			$user_criacao = $row['criacao'];
			$user_apelido = $row['apelido'];
			$user_nome = $row['nome'];
			$user_sobrenome = $row['sobrenome'];
		}
	}
	
	$result = $conn->query("SELECT criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $id_elemento");
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
			$user_id_elemento = $row['user_id'];
			$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id_elemento");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$user_apelido_elemento = $row['apelido'];
				}
			} else {
				$user_apelido_elemeto = "(usuário não-identificado)";
			}
			break;
		}
		
		if (isset($_POST['quill_nova_mensagem_html'])) {
			$nova_mensagem = $_POST['quill_nova_mensagem_html'];
			$nova_mensagem = strip_tags($nova_mensagem, '<p><li><ul><ol><h2><h3><blockquote><em><sup><s>');
			$result = $conn->query("SELECT analise, user_id FROM Elementos_analise WHERE id_elemento = $id_elemento");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$analise_user_id = $row['user_id'];
					$analise_previa = $row['analise'];
					$conn->query("UPDATE Elementos_analise SET analise = '$nova_mensagem' WHERE id_elemento = $id_elemento");
					$conn->query("INSERT INTO Elementos_analise_arquivo (user_id, id_elemento, analise) VALUES ($analise_user_id, $id_elemento, '$analise_previa')");
				}
			} else {
				$conn->query("INSERT INTO Elementos_analise (user_id, id_elemento, analise) VALUES ($user_id, $id_elemento, '$nova_mensagem')");
			}
			$elemento_analise = $nova_mensagem;
		} else {
			$result = $conn->query("SELECT analise_content FROM Elementos_analise WHERE id_elemento = $id_elemento");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$elemento_analise = $row['analise'];
				}
			} else {
				$elemento_analise = false;
			}
		}
	}
	
	$html_head_template_quill_theme = true;
	include 'templates/html_head.php';

?>
<body>
<?php
	carregar_navbar('dark');
	standard_jumbotron("Página de Elemento", false);
?>
<div class="container-fluid my-5">
    <div class='row justify-content-around'>
        <div class="col-lg-5 col-sm-12">
					<?php
						if ($tipo_elemento == 'imagem') {
							$template_id = 'imagem_div';
							$template_titulo = 'Imagem';
							$template_botoes = false;
							$template_conteudo = "<a href='../imagens/verbetes/$arquivo_elemento' target='_blank'><img class='imagem_pagina border' src='../imagens/verbetes/$arquivo_elemento'></img></a>";
							include 'templates/page_element.php';
						} elseif ($tipo_elemento == 'video') {
							$template_div = 'video_div';
							$template_titulo = $titulo_elemento;
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
						$template_titulo = 'Dados do elemento';
						$template_botoes = "
                                  <a data-toggle='modal' data-target='#modal_elemento_form' href=''>
                                    <i class='fal fa-edit fa-fw'></i>
                                  </a>
                            ";
						$template_conteudo = $dados_elemento;
						include 'templates/page_element.php';
					
					
					?>
        </div>
        <div class='col-lg-5 col-sm-12'>
					<?php
						$template_id = 'anotacoes_elemento_div';
						$template_titulo = 'Análise';
						$template_botoes = false;
						$template_conteudo = false;
						
						$template_quill_form_id = 'quill_elemento_form';
						$template_quill_conteudo_html = 'quill_nova_mensagem_html';
						$template_quill_conteudo_text = 'quill_nova_mensagem_texto';
						$template_quill_conteudo_content = 'quill_nova_mensagem_content';
						$template_quill_container_id = 'quill_container_elemento';
						$template_quill_editor_id = 'quill_editor_elemento';
						$template_quill_editor_classes = 'quill_editor_height';
						$template_quill_conteudo_opcional = $elemento_analise;
						$template_quill_botoes_collapse_stuff = false;
						$template_conteudo = include 'templates/quill_form.php';
						
						include 'templates/page_element.php';
					?>
        </div>
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
	?>
</body>
<?php
	bottom_page('quill_elemento');
?>
</html>
