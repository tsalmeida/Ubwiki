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

    <div class="row d-flex justify-content-start">
        <h2 class="col-12 text-center mb-5">Anotações privadas</h2>
			<?php
				$anotacoes_cursos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_curso' AND user_id = $user_id ORDER BY id DESC");
				if ($anotacoes_cursos->num_rows > 0) {
					while ($anotacao_cursos = $anotacoes_cursos->fetch_assoc()) {
						$artefato_id = $anotacao_cursos['id'];
						$artefato_page_id = $anotacao_cursos['page_id'];
						$artefato_titulo = $anotacao_cursos['titulo'];
						$artefato_criacao = $anotacao_cursos['criacao'];
						$artefato_tipo = 'Anotação de tópico';
						$artefato_page_id_titulo = return_concurso_titulo_id($artefato_page_id);
						$artefato_tipo = 'anotacao_curso';
						$artefato_link = false;
						include 'templates/artefato_item.php';
					}
				}
				$anotacoes_materias = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes_materia' AND user_id = $user_id ORDER BY id DESC");
				if ($anotacoes_materias->num_rows > 0) {
					while ($anotacao_materias = $anotacoes_materias->fetch_assoc()) {
						$artefato_id = $anotacao_materias['id'];
						$artefato_page_id = $anotacao_materias['page_id'];
						$artefato_titulo = $anotacao_materias['titulo'];
						$artefato_criacao = $anotacao_materias['criacao'];
						$artefato_tipo = 'Anotação de tópico';
						$artefato_page_id_titulo = return_materia_titulo_id($artefato_page_id);
						$artefato_tipo = 'anotacao_materia';
						$artefato_link = "materia.php?materia_id=$artefato_page_id";
						include 'templates/artefato_item.php';
					}
				}
				$anotacoes_topicos = $conn->query("SELECT id, page_id, titulo, criacao FROM Textos WHERE tipo = 'anotacoes' AND user_id = $user_id ORDER BY id DESC");
				if ($anotacoes_topicos->num_rows > 0) {
					while ($anotacao_topicos = $anotacoes_topicos->fetch_assoc()) {
						$artefato_id = $anotacao_topicos['id'];
						$artefato_page_id = $anotacao_topicos['page_id'];
						$artefato_titulo = $anotacao_topicos['titulo'];
						$artefato_criacao = $anotacao_topicos['criacao'];
						$artefato_tipo = 'Anotação de tópico';
						$artefato_page_id_titulo = return_titulo_topico($artefato_page_id);
						$artefato_tipo = 'anotacao_topico';
						$artefato_link = "verbete.php?topico_id=$artefato_page_id";
						include 'templates/artefato_item.php';
					}
				}
			?>
			<?php
				$imagens_publicas = $conn->query("SELECT id, criacao, titulo, arquivo FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' ORDER BY id DESC");
				if ($imagens_publicas->num_rows > 0) {
					echo "<h2 class='col-12 text-center mb-5'>Imagens públicas</h2>";
					while ($imagem_publica = $imagens_publicas->fetch_assoc()) {
						$artefato_id = $imagem_publica['id'];
						$artefato_criacao = $imagem_publica['criacao'];
						$artefato_titulo = $imagem_publica['titulo'];
						$artefato_imagem_arquivo = $imagem_publica['arquivo'];
						$artefato_link = "elemento.php?id=$artefato_id";
						$artefato_tipo = 'imagem_publica';
						include 'templates/artefato_item.php';
					}
				}
			?>


    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
