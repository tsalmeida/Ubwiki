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
		adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, 0, $user_id, 'privada', 'link', 0);
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
				adicionar_imagem($target_file, $nova_imagem_titulo, 0, $user_id, 'privada', 'upload', 0);
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
		$template_titulo = 'Seu acervo';
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row">
        <div id="coluna_unica" class="col">
					<?php
						
						$template_id = 'novo_artefato';
						$template_titulo = 'Adicionar item';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = false;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Nova anotação privada';
						$artefato_criacao = 'Pressione para criar uma nova anotação';
						$artefato_tipo = 'nova_anotacao';
						$artefato_link = 'edicao_textos.php?texto_id=0';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Nova imagem privada';
						$artefato_criacao = 'Pressione para enviar uma nova imagem privada';
						$artefato_tipo = 'nova_imagem';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Incluir material de estudo';
						$artefato_criacao = 'Pressione para acrescentar uma obra à sua biblioteca virtual';
						$artefato_tipo = 'nova_referencia';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Adicionar área de interesse';
						$artefato_criacao = 'Pressione para adicionar uma área de interesse';
						$artefato_tipo = 'novo_topico';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						//						$artefato_id = 0;
						//						$artefato_page_id = false;
						//						$artefato_titulo = 'Novo simulado';
						//						$artefato_criacao = 'Pressione para criar um novo simulado';
						//						$artefato_tipo = 'novo_simulado';
						//						$artefato_link = 'simulados.php';
						//						$template_conteudo .= include 'templates/artefato_item.php';
						
						//						$artefato_id = 0;
						//						$artefato_page_id = false;
						//						$artefato_titulo = 'Novo curso';
						//						$artefato_criacao = 'Pressione para criar um novo curso';
						//						$artefato_tipo = 'novo_curso';
						//						$artefato_link = false;
						//						$template_conteudo .= include 'templates/artefato_item.php';
						
						include 'templates/page_element.php';
						
						$template_id = 'acervo_virtual';
						$template_titulo = 'Material de estudo';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						$acervo = $conn->query("SELECT criacao, etiqueta_id, etiqueta_tipo, elemento_id FROM Acervo WHERE user_id = $user_id AND estado = 1 AND etiqueta_tipo NOT IN ('topico') ORDER BY id DESC");
						if ($acervo->num_rows > 0) {
							while ($acervo_item = $acervo->fetch_assoc()) {
								$acervo_item_criacao = $acervo_item['criacao'];
								$acervo_item_etiqueta_id = $acervo_item['etiqueta_id'];
								$acervo_item_etiqueta_tipo = $acervo_item['etiqueta_tipo'];
								$acervo_item_elemento_id = $acervo_item['elemento_id'];
								$acervo_item_info = return_etiqueta_info($acervo_item_etiqueta_id);
								$acervo_item_tipo = $acervo_item_info[1];
								$acervo_item_titulo = $acervo_item_info[2];
								
								$artefato_id = $acervo_item_etiqueta_id;
								$artefato_page_id = $acervo_item_elemento_id;
								$artefato_titulo = $acervo_item_titulo;
								$artefato_criacao = $acervo_item_criacao;
								$artefato_criacao = "Adicionado em $artefato_criacao";
								$artefato_tipo = $acervo_item_etiqueta_tipo;
								$artefato_link = "elemento.php?id=$acervo_item_elemento_id";
								
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						include 'templates/page_element.php';
						
						$template_id = 'anotacoes_privadas';
						$template_titulo = 'Anotações privadas';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						$count_anotacoes = 0;
						$anotacoes_privadas = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacao_privada' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_privadas->num_rows > 0) {
							while ($anotacao_privada = $anotacoes_privadas->fetch_assoc()) {
								$count_anotacoes++;
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
						$anotacoes_elementos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_elemento' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_elementos->num_rows > 0) {
							while ($anotacao_elemento = $anotacoes_elementos->fetch_assoc()) {
								$count_anotacoes++;
								$artefato_id = $anotacao_elemento['id'];
								$artefato_page_id = $anotacao_elemento['page_id'];
								$artefato_titulo = $anotacao_elemento['titulo'];
								$artefato_criacao = $anotacao_elemento['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = return_titulo_elemento($artefato_page_id);
								if ($artefato_titulo == false) {
									$artefato_titulo = 'Anotação de referência';
								}
								$artefato_tipo = 'anotacoes_elemento';
								$artefato_link = "elemento.php?id=$artefato_page_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						$anotacoes_materias = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_materia' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_materias->num_rows > 0) {
							while ($anotacao_materias = $anotacoes_materias->fetch_assoc()) {
								$count_anotacoes++;
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
								$count_anotacoes++;
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
								$count_anotacoes++;
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
								$count_anotacoes++;
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
								$count_anotacoes++;
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
						$anotacoes_cursos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_curso' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_cursos->num_rows > 0) {
							while ($anotacao_cursos = $anotacoes_cursos->fetch_assoc()) {
								$count_anotacoes++;
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
						if ($count_anotacoes > 10) {
							$template_load_invisible = true;
						}
						include 'templates/page_element.php';
						
						$imagens_privadas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem_privada' AND user_id = $user_id ORDER BY id DESC");
						if ($imagens_privadas->num_rows > 0) {
							if ($imagens_privadas->num_rows > 5) {
								$template_load_invisible = true;
							}
							$template_id = 'imagens_privadas';
							$template_titulo = 'Imagens privadas';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($imagem_privada = $imagens_privadas->fetch_assoc()) {
								$artefato_id = $imagem_privada['id'];
								$artefato_criacao = $imagem_privada['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = $imagem_privada['titulo'];
								$artefato_imagem_arquivo = $imagem_privada['arquivo'];
								$artefato_estado = $imagem_privada['estado'];
								$artefato_link = "elemento.php?id=$artefato_id";
								$artefato_tipo = 'imagem_publica';
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						$topicos_acervo = $conn->query("SELECT etiqueta_id FROM Acervo WHERE user_id = $user_id AND etiqueta_tipo = 'topico' ORDER BY id DESC");
						if ($topicos_acervo->num_rows > 0) {
							if ($topicos_acervo->num_rows > 5) {
								$template_load_invisible = true;
							}
							$template_id = 'topicos_interesse';
							$template_titulo = 'Áreas de interesse';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($topico_acervo = $topicos_acervo->fetch_assoc()) {
								$topico_acervo_etiqueta_id = $topico_acervo['etiqueta_id'];
								$topico_acervo_etiqueta_info = return_etiqueta_info($topico_acervo_etiqueta_id);
								$artefato_criacao = $topico_acervo_etiqueta_info[0];
								$artefato_titulo = $topico_acervo_etiqueta_info[2];
								$artefato_link = false;
								$artefato_tipo = 'topico_interesse';
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						$imagens_publicas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' ORDER BY id DESC");
						if ($imagens_publicas->num_rows > 0) {
							if ($imagens_publicas->num_rows > 5) {
								$template_load_invisible = true;
							}
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
		
		$template_modal_div_id = 'modal_nova_referencia';
		$template_modal_titulo = 'Adicionar item à sua biblioteca virtual';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
	        <h4 class='mt-3'>Biblioteca virtual</h4>
	        <p>Acrescente à sua biblioteca virtual livros que você tem, quer ter, pretende emprestar de um amigo, assim como artigos que quer ler, revistas, até mesmo álbuns de música ou filmes.</p>
	        <p>Uma vez que seu item tenha sido adicionado, será possível marcar capítulos e escrever fichamentos específicos, assim como resenhas e resumos. Cada anotação será inicialmente privada, podendo ser tornada pública se você assim desejar.</p>
		    <div class='md-form'>
			    <input type='text' class='form-control' name='busca_referencias' id='busca_referencias' required>
			    <label for='busca_referencias'>Buscar item para adicionar à sua biblioteca virtual, por título ou autor.</label>
		    </div>
		    <div class='row' id='referencias_disponiveis'>
		    
		    </div>
		    <div class='row' id='criar_referencia_form'>
		        <div class='col-12'>
                    <div class='md-form'>
                        <input type='text' class='form-control' name='criar_referencia_titulo' id='criar_referencia_titulo' required>
                        <label for='criar_referencia_titulo'>Título da nova referência</label>
                    </div>
                    <div class='md-form'>
                        <input type='text' class='form-control' name='criar_referencia_autor' id='criar_referencia_autor' required>
                        <label for='criar_referencia_autor'>Autor da nova referência</label>
                    </div>
                    <div class='row' id='autores_disponiveis'>
                    
                    </div>
                    <select class='mdb-select md-form' name='criar_referencia_tipo' id='criar_referencia_tipo'>
                        <option value='' disabled selected>Tipo do novo item:</option>
                        <option value='referencia'>Materia de leitura: livros, artigos etc.</option>
                        <option value='video'>Material videográfico: vídeos virtuais, filmes etc.</option>
                        <option value='album_musica'>Material em áudio: álbuns de música, podcasts</option>
                    </select>
                    <div class='row justify-content-center'>
                    	<button type='button' class='$button_classes' id='trigger_adicionar_referencia'>Cadastrar esta referência e acrescentá-la a seu acervo</button>
					</div>
                </div>
            </div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_novo_topico';
		$template_modal_titulo = 'Adicionar tópico';
		$etiquetas_carregar_remover = false;
		include 'templates/etiquetas_modal.php';
	?>
</div>
</body>
<?php
	include 'templates/footer.html';
	$etiquetas_bottom_adicionar = true;
	$texto_id = 0;
	$texto_tipo = 'acervo';
	$biblioteca_bottom_adicionar = true;
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>
