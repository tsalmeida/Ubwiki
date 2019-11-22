<?php

	include 'engine.php';
	include 'templates/html_head.php';

	if (!isset($concurso_id)) {
		$concurso_id = return_concurso_id_topico($topico_id);
	}
	$concurso_sigla = return_concurso_sigla($concurso_id);

?>

<body>

<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla: Plataforma de Simulados";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'adicionar_items';
						$template_titulo = 'Adicionar items';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Adicionar items.</p>";
						$template_conteudo .= "<p>Adicionar items.</p>";
						$template_conteudo .= "<p>Adicionar items.</p>";
						$template_conteudo .= "<p>Adicionar items.</p>";
						$template_conteudo .= "<p>Adicionar items.</p>";
						$template_conteudo .= "<p>(quill).</p>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_questao';
						$template_titulo = 'Adicionar questão';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Adicionar enunciado.</p>";
						$template_conteudo .= "<p>Adicionar número da questão.</p>";
						$template_conteudo .= "<p>Selecionar matéria ou matérias.</p>";
						$template_conteudo .= "<p>Adicionar tipo de questão: certo e errado, múltipla escolha.</p>";
						$template_conteudo .= "<p>(quill).</p>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_texto_apoio';
						$template_titulo = 'Adicionar texto de apoio';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Selecione edição do concurso.</p>";
						$template_conteudo .= "<p>Adicionar título do texto de apoio.</p>";
						$template_conteudo .= "<p>(quill).</p>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_prova';
						$template_titulo = 'Adicionar prova';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Quantas provas?</p>";
						$template_conteudo .= "<p>Títulos das provas?</p>";
						$template_conteudo .= "<p>Tipo (discursiva, objetiva, oral, física)?</p>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_etapa';
						$template_titulo = 'Adicionar etapa';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Quantas etapas?</p>";
						$template_conteudo .= "<p>Título da etapa?</p>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_edicao';
						$template_titulo = 'Adicionar edição do concurso';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Digite o ano</p>";
						$template_conteudo .= "<input type='checkbox'>Único do ano?</input>";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';
					?>
        </div>
        <div id="coluna_direita" class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'gerar_simulado';
						$template_titulo = 'Gerar simulado';
						$template_conteudo = false;
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
