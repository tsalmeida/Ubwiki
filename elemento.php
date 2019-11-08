<?php

	include 'engine.php';

	if (isset($_GET['id'])) {
		$id_elemento = $_GET['id'];
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
			$resolucao_elemento = $row['resolucao'];
			$orientacao_elemento = $row['orientacao'];
			$comentario_elemento = $row['comentario'];
			$trecho_elemento = $row['trecho'];
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

	top_page(false, 'quill_elemento');

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
							$template_conteudo = "<img class='imagem_pagina border' src='../imagens/verbetes/$arquivo_elemento'></img>";
													include 'templates/page_element.php';
						}
						elseif ($tipo_elemento == 'video') {
						    $template_div = 'video_div';
						    $template_titulo = $titulo_elemento;
						    $template_botoes = false;
						    $iframe_elemento = base64_decode($iframe_elemento);
						    $template_conteudo = $iframe_elemento;
						    include 'templates/page_element.php';
                        }

						$dados_elemento = "
						              <ul class='list-group'>
                                        <li class='list-group-item'><strong>Criado em:</strong> $criacao_elemento</li>
                                        <li class='list-group-item'><strong>Tipo:</strong> $tipo_elemento</li>
                                        <li class='list-group-item'><strong>Título:</strong> $titulo_elemento</li>
                                        <li class='list-group-item'><strong>Autor:</strong> $autor_elemento</li>
                                        <li class='list-group-item'><strong>Capítulo:</strong> $capitulo_elemento</li>
                                        <li class='list-group-item'><strong>Ano:</strong> $ano_elemento</li>
                                        <li class='list-group-item'><a href='$link_elemento' target='_blank'>Link</a></li>
                                        <li class='list-group-item'><a href='../imagens/verbetes/$arquivo_elemento' target='_blank'>Arquivo</a></li>
                                        <li class='list-group-item'><a href='../imagens/verbetes/thumbnails/$arquivo_elemento' target='_blank'>Thumbnail</a></li>
                                        <li class='list-group-item'><strong>Resolução:</strong> $resolucao_elemento</li>
                                        <li class='list-group-item'><strong>Orientação:</strong> $orientacao_elemento</li>
                                        <li class='list-group-item'><strong>Comentário:</strong> $comentario_elemento</li>
                                        <li class='list-group-item'><strong>Trecho:</strong> $trecho_elemento</li>
                                        <li class='list-group-item'>Adicionado pelo usuário <strong>$user_apelido_elemento</strong></li>
                                      </ul>
						";

						$template_id = 'dados_elemento_div';
						$template_titulo = 'Dados do elemento';
						$template_botoes = false;
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
</body>
<?php
	bottom_page('quill_elemento');
?>
</html>
