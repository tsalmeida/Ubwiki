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
						$template_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_titulo_prova' id='novo_titulo_prova'>
                              <label for='novo_titulo_prova'>Título da prova</label>
                            </div>
                            <select class='mdb-select md-form'>
                              <option value='' disabled selected>Tipo de prova:</option>
                              <option value='1'>Objetiva</option>
                              <option value='2'>Discursiva</option>
                              <option value='3'>Oral</option>
                              <option value='4'>Física</option>
                            </select>
                            <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Adicionar prova</button>
                            </div>
						";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_etapa';
						$template_titulo = 'Adicionar etapa';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "
						    <div class='md-form'>
                              <input type='text' class='form-control' name='nova_etapa_1' id='nova_etapa_1'>
                              <label for='nova_etapa_1'>Etapa</label>
                            </div>
                            <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Adicionar etapa</button>
                            </div>
						";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';

						$template_id = 'adicionar_edicao';
						$template_titulo = 'Adicionar edição do concurso';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_edicao_ano' id='nova_edicao_ano'>
                              <label for='nova_edicao_ano'>Ano</label>
                            </div>
                            <div class='form-check pl-0'>
                                <input type='checkbox' class='form-check-input' id='unica_edicao_ano'>
                                <label class='form-check-label' for='unica_edicao_ano'>Única edição do concurso neste ano?</label>
                            </div>
                            <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Adicionar edição do concurso</button>
                            </div>
						";
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
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>
