<?php
	include 'engine.php';
	include 'templates/html_head.php';
	
	// IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM
	
	if (isset($_POST['nova_imagem_titulo'])) {
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, 0, 'nova_imagem_privada')");
	}
	
	if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
		$nova_imagem_link = $_POST['nova_imagem_link'];
		$nova_imagem_link = base64_encode($nova_imagem_link);
		adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, 0, $user_id, 'privada', 'link');
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
				adicionar_imagem($target_file, $nova_imagem_titulo, 0, $user_id, 'privada', 'upload');
			}
		}
	}


?>
<body>
<?php
	include 'templates/navbar.php';
?>

<div class="container">
	
	<?php
		$template_titulo = 'Seus artefatos';
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row">
        <div id="coluna_unica" class="col">
					<?php
						
						$template_id = 'novo_artefato';
						$template_titulo = 'Criar novo artefato privado';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = false;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Nova anotação';
						$artefato_criacao = 'Pressione para criar uma nova anotação';
						$artefato_tipo = 'nova_anotacao';
						$artefato_link = 'edicao_textos.php?texto_id=0';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Nova imagem';
						$artefato_criacao = 'Pressione para enviar nova imagem privada';
						$artefato_tipo = 'nova_imagem';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						include 'templates/page_element.php';
						
						$template_id = 'anotacoes_privadas';
						$template_titulo = 'Anotações privadas';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						$anotacoes_privadas = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacao_privada' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_privadas->num_rows > 0) {
							while ($anotacao_privada = $anotacoes_privadas->fetch_assoc()) {
								$artefato_id = $anotacao_privada['id'];
								$artefato_page_id = $anotacao_privada['page_id'];
								$artefato_titulo = $anotacao_privada['titulo'];
								$artefato_criacao = $anotacao_privada['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								if ($artefato_titulo == false) {
									$artefato_titulo = 'Anotação privada';
								}
								$artefato_tipo = 'anotacao_privada';
								$artefato_link = "edicao_textos.php?texto_id=$artefato_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_cursos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_curso' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_cursos->num_rows > 0) {
							while ($anotacao_cursos = $anotacoes_cursos->fetch_assoc()) {
								$artefato_id = $anotacao_cursos['id'];
								$artefato_page_id = $anotacao_cursos['page_id'];
								$artefato_titulo = $anotacao_cursos['titulo'];
								$artefato_criacao = $anotacao_cursos['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_titulo = return_concurso_titulo_id($artefato_page_id);
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_curso';
								$artefato_link = false;
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_materias = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_materia' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_materias->num_rows > 0) {
							while ($anotacao_materias = $anotacoes_materias->fetch_assoc()) {
								$artefato_id = $anotacao_materias['id'];
								$artefato_page_id = $anotacao_materias['page_id'];
								$artefato_titulo = $anotacao_materias['titulo'];
								$artefato_criacao = $anotacao_materias['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_titulo = return_materia_titulo_id($artefato_page_id);
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_materia';
								$artefato_link = "materia.php?materia_id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_topicos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_topicos->num_rows > 0) {
							while ($anotacao_topicos = $anotacoes_topicos->fetch_assoc()) {
								$artefato_id = $anotacao_topicos['id'];
								$artefato_page_id = $anotacao_topicos['page_id'];
								$artefato_titulo = $anotacao_topicos['titulo'];
								$artefato_criacao = $anotacao_topicos['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_titulo = return_titulo_topico($artefato_page_id);
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_topico';
								$artefato_link = "verbete.php?topico_id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_provas = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_prova' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_provas->num_rows > 0) {
							while ($anotacao_provas = $anotacoes_provas->fetch_assoc()) {
								$artefato_id = $anotacao_provas['id'];
								$artefato_page_id = $anotacao_provas['page_id'];
								$artefato_titulo = $anotacao_provas['titulo'];
								$artefato_criacao = $anotacao_provas['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_info = return_info_prova_id($artefato_page_id);
								$artefato_page_id_titulo = "$artefato_page_id_info[3]: $artefato_page_id_info[0]";
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_prova';
								$artefato_link = "prova.php?prova_id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_textos_apoio = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_texto_apoio' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_textos_apoio->num_rows > 0) {
							while ($anotacao_textos_apoio = $anotacoes_textos_apoio->fetch_assoc()) {
								$artefato_id = $anotacao_textos_apoio['id'];
								$artefato_page_id = $anotacao_textos_apoio['page_id'];
								$artefato_titulo = $anotacao_textos_apoio['titulo'];
								$artefato_criacao = $anotacao_textos_apoio['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_titulo = "Texto de apoio";
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_texto_apoio';
								$artefato_link = "textoapoio.php?texto_apoio_id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_questao = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_questao' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_questao->num_rows > 0) {
							while ($anotacao_questao = $anotacoes_questao->fetch_assoc()) {
								$artefato_id = $anotacao_questao['id'];
								$artefato_page_id = $anotacao_questao['page_id'];
								$artefato_titulo = $anotacao_questao['titulo'];
								$artefato_criacao = $anotacao_questao['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_page_id_titulo = "Questão";
								if ($artefato_titulo == false) {
									$artefato_titulo = $artefato_page_id_titulo;
									$artefato_page_id_titulo = false;
								}
								$artefato_tipo = 'anotacao_questao';
								$artefato_link = "questao.php?questao_id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						include 'templates/page_element.php';
						
						$imagens_publicas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem_privada' AND user_id = $user_id ORDER BY id DESC");
						if ($imagens_publicas->num_rows > 0) {
							$template_id = 'imagens_privadas';
							$template_titulo = 'Imagens privadas';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($imagem_publica = $imagens_publicas->fetch_assoc()) {
								$artefato_id = $imagem_publica['id'];
								$artefato_criacao = $imagem_publica['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = $imagem_publica['titulo'];
								$artefato_imagem_arquivo = $imagem_publica['arquivo'];
								$artefato_estado = $imagem_publica['estado'];
								$artefato_link = "elemento.php?id=$artefato_id";
								$artefato_tipo = 'imagem_publica';
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						$imagens_publicas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' ORDER BY id DESC");
						if ($imagens_publicas->num_rows > 0) {
							$template_id = 'imagens_publicas';
							$template_titulo = 'Imagens públicas';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($imagem_publica = $imagens_publicas->fetch_assoc()) {
								$artefato_id = $imagem_publica['id'];
								$artefato_criacao = $imagem_publica['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = $imagem_publica['titulo'];
								$artefato_imagem_arquivo = $imagem_publica['arquivo'];
								$artefato_estado = $imagem_publica['estado'];
								$artefato_link = "elemento.php?id=$artefato_id";
								$artefato_tipo = 'imagem_publica';
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						$respostas = $conn->query("SELECT DISTINCT simulado_id, questao_tipo FROM sim_respostas WHERE user_id = $user_id ORDER BY id DESC");
						if ($respostas->num_rows > 0) {
							$template_id = 'simulados';
							$template_titulo = 'Simulados';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							while ($resposta = $respostas->fetch_assoc()) {
								$artefato_id = $resposta['simulado_id'];
								$artefato_questao_tipo = $resposta['questao_tipo'];
								$simulado_info = return_simulado_info($artefato_id);
								$simulado_criacao = $simulado_info[0];
								$simulado_tipo = $simulado_info[1];
								$simulado_tipo_string = converter_simulado_tipo($simulado_tipo);
								$simulado_concurso_id = $simulado_info[2];
								$simulado_concurso_sigla = return_concurso_sigla($simulado_concurso_id);
								$artefato_criacao = $simulado_criacao;
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = "$simulado_concurso_sigla: $simulado_tipo_string";
								$artefato_link = "resultados.php?simulado_id=$artefato_id";
								$artefato_tipo = 'simulado';
								if ($artefato_questao_tipo == 3) {
									$artefato_icone = 'fa-file-edit';
								}
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
					?>
        </div>
    </div>
	<?php
		$template_modal_div_id = 'modal_nova_imagem';
		$template_modal_titulo = 'Nova imagem privada';
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
	?>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
