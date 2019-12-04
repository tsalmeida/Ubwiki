<?php
	include 'engine.php';
	include 'templates/html_head.php';
?>
<body>
<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid">
	
	<?php
		$template_titulo = 'Seus artefatos';
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row">
        <div id="coluna_unica" class="col-12">
					<?php
						$template_id = 'anotacoes_privadas';
						$template_titulo = 'Anotações privadas';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						$anotacoes_cursos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_curso' AND user_id = $user_id ORDER BY id DESC");
						if ($anotacoes_cursos->num_rows > 0) {
							$template_conteudo = false;
							while ($anotacao_cursos = $anotacoes_cursos->fetch_assoc()) {
								$artefato_id = $anotacao_cursos['id'];
								$artefato_page_id = $anotacao_cursos['page_id'];
								$artefato_titulo = $anotacao_cursos['titulo'];
								$artefato_criacao = $anotacao_cursos['criacao'];
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
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
