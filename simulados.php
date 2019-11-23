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
						$template_id = 'adicionar_questao';
						$template_titulo = 'Adicionar questão';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "
						<div class='form-check pl-0'>
                            <input id='nova_questao_origem' name='nova_questao_origem' type='checkbox' class='form-check-input' checked>
                            <label class='form-check-label' for='nova_questao_origem'>Questão oficial do concurso.</label>
                        </div>
						";
											
                        $template_conteudo .= "
                            <div class='form-check pl-0'>
                                <input id='nova_questao_sem_apoio' name='nova_questao_sem_apoio' type='checkbox' class='form-check-input' checked>
                                <label class='form-check-label' for='nova_questao_sem_apoio'>Esta questão possui texto de apoio.</label>
                            </div>
						";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_texto_apoio'>
                              <option value='' disabled selected>Selecione o texto de apoio:</option>
                              <option value='1'>Texto de apoio 1</option>
                              <option value='2'>Texto de apoio 2</option>
                              <option value='3'>Texto de apoio 3</option>
                              <option value='4'>Texto de apoio 4</option>
                            </select>
						";
						$template_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_enunciado' name='nova_questao_enunciado' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_enunciado'>Enunciado da questão</label>
                            </div>
						";
						$template_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero'>
                              <label for='nova_questao_numero'>Número da questão</label>
                            </div>
						";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_materia'>
                              <option value='' disabled selected>Selecione a matéria:</option>
                              <option value='1'>Matéria 1</option>
                              <option value='2'>Matéria 2</option>
                              <option value='3'>Matéria 3</option>
                              <option value='4'>Matéria 4</option>
                            </select>
						";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_tipoi'>
                              <option value='' disabled selected>Selecione o tipo da questão:</option>
                              <option value='1'>Certo e errado</option>
                              <option value='2'>Múltipla escolha</option>
                              <option value='3'>Dissertativa</option>
                            </select>
						";
						$template_conteudo .= "<h2>Itens</h2>";
						$template_conteudo .= "
                            <div class='md-form'>
                                  <input type='text' class='form-control' name='novo_item_texto' id='novo_item_texto'>
                                  <label for='novo_item_texto'>Texto do primeiro item</label>
                            </div>
                            <div class='md-form'>
                                  <input type='text' class='form-control' name='novo_item_texto' id='novo_item_texto'>
                                  <label for='novo_item_texto'>Texto do segundo item</label>
                            </div>
                            <div class='md-form'>
                                  <input type='text' class='form-control' name='novo_item_texto' id='novo_item_texto'>
                                  <label for='novo_item_texto'>Texto do terceiro item</label>
                            </div>
                            <div class='md-form'>
                                  <input type='text' class='form-control' name='novo_item_texto' id='novo_item_texto'>
                                  <label for='novo_item_texto'>Texto do quarto item</label>
                            </div>
                            <div class='md-form'>
                                  <input type='text' class='form-control' name='novo_item_texto' id='novo_item_texto'>
                                  <label for='novo_item_texto'>Texto do quinto item</label>
                            </div>
						";
						$template_conteudo .= "
                            <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Adicionar questão</button>
                            </div>
						";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';
						
						$template_id = 'adicionar_texto_apoio';
						$template_titulo = 'Adicionar texto de apoio';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
                        $template_conteudo .= "
                            <div class='form-check pl-0'>
                                <input id='novo_texto_apoio_origem' name='novo_texto_apoio_origem' type='checkbox' class='form-check-input' checked>
                                <label class='form-check-label' for='novo_texto_apoio_origem'>Texto de apoio oficial do concurso.</label>
                            </div>
						";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='novo_texto_apoio_prova'>
                              <option value='' disabled selected>Selecione a prova:</option>
                              <option value='1'>Prova 1</option>
                              <option value='2'>Prova 2</option>
                              <option value='3'>Prova 3</option>
                              <option value='4'>Prova 4</option>
                            </select>
						";
						$template_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo'>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
						$template_conteudo .= "
                            <div class='md-form'>
                              <textarea id='novo_texto_apoio_enunciado' name='novo_texto_apoio_enunciado' class='md-textarea form-control' rows='3'></textarea>
                              <label for='novo_texto_apoio_enunciado'>Enunciado do texto de apoio</label>
                            </div>
						";
						$template_conteudo .= "
                            <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Adicionar texto de apoio</button>
                            </div>
						";
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';
						
						$template_id = 'adicionar_prova';
						$template_titulo = 'Adicionar prova';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='nova_prova_etapa'>
                                  <option value='' disabled selected>Etapa do concurso:</option>
                                  <option value='1'>Etapa</option>
                                  <option value='2'>Etapa</option>
                                  <option value='3'>Etapa</option>
                                  <option value='4'>Etapa</option>
                                </select>
                        ";
						$template_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo'>
                              <label for='nova_prova_titulo'>Título da prova</label>
                            </div>
                            <select class='mdb-select md-form' name='nova_prova_tipo'>
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
                            <select class='mdb-select md-form' name='nova_etapa_concurso'>
                                  <option value='' disabled selected>Edição do concurso:</option>
                                  <option value='1'>Edição</option>
                                  <option value='2'>Edição</option>
                                  <option value='3'>Edição</option>
                                  <option value='4'>Edição</option>
                                </select>
                        ";
						$template_conteudo .= "
						    <div class='md-form'>
                              <input type='text' class='form-control' name='nova_etapa' id='nova_etapa'>
                              <label for='nova_etapa'>Título da etapa</label>
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
                              <input type='number' class='form-control' name='nova_edicao_ano' id='nova_edicao_ano'>
                              <label for='nova_edicao_ano'>Ano</label>
                            </div>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_edicao_titulo' id='nova_edicao_titulo'>
                              <label for='nova_edicao_titulo'>Título</label>
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
						$template_conteudo .= "
						    <p>O ato de fazer simulados é muito importante, mas deve ser realizado com muito cuidado, de mandeira que de fato traga você mais próximo a seus objetivos. Fazer provas é uma habilidade que pode e deve ser desenvolvida pela prática, esse é seu objetivo principal ao fazer simulados, e não necessariamente o aprendizado de conteúdo.</p>
						    <p>Somente recomendamos que você comece a fazer simulados após haver estudado todo o conteúdo do seu concurso pelo menos uma vez, mesmo que em um primeiro nível introdutório e superficial.</p>
						";
						$template_conteudo .= "
                            <select class='mdb-select md-form' name='novo_simulado_tipo'>
                                  <option value='' disabled selected>Tipo:</option>
                                  <option value='inteligente'>Criado por nosso algoritmo</option>
                                  <option value='questao_errou'>Apenas questões em que você errou pelo menos um item</option>
                                  <option value='itens_errou'>Apenas itens que você errou no passado</option>
                                  <option value='questoes_estudados'>Apenas questões de tópicos que você marcou como estudados</option>
                                </select>
                        ";
						$template_conteudo .= "
						    <div class='row justify-content-center'>
                                <button type='button' class='$button_classes'>Gerar simulado</button>
                            </div>
						";
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
