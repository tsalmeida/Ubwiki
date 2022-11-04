<?php

	/*

	$template_modal_div_id = 'modal_adicionar_simulado';
	$template_modal_titulo = 'Adicionar questão de prova';
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "
                            <p>A plataforma de simulados é populada por questões oficiais de concursos e por questões criadas pelos usuários da página.</p>
                            <p>Para acrescentar questões de edições passadas do concurso, é necessário registrar algumas informações prévias:</p>
                            <ol>
                                <li>A edição do concurso</li>
                                <li>A etapa da edição</li>
                                <li>A prova da etapa</li>
                            </ol>
                            <p>Caso a questão possua um texto de apoio, será também necessário registrá-lo anteriormente.</p>
                            <p>É importante prestar atenção para que todos as questões não-oficiais sejam devidamente identificadas como tal. É possível criar textos de apoio não oficiais, mas também criar questões não-oficiais com os textos de apoio de edições passadas do concurso.</p>
                            <p>Selecione a opção abaixo para acessar os sistemas que permitem acrescentar provas anteriores do concurso:</p>
                        ";
	$template_modal_body_conteudo .= "
                            <span data-bs-toggle='modal' data-bs-target='#modal_adicionar_simulado'><button type='button' class='$button_classes_info btn-block ms-0' data-bs-toggle='modal' data-bs-target='#modal_adicionar_edicao'>Adicionar edição do concurso</button></span>
                        ";
	if ($edicoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
                            <span data-bs-toggle='modal' data-bs-target='#modal_adicionar_simulado'><button type='button' class='$button_classes_info btn-block ms-0' data-bs-toggle='modal' data-bs-target='#modal_adicionar_etapa'>Adicionar etapa da edição</button></span>
                            ";
	}
	if ($etapas->num_rows > 0) {
		$template_modal_body_conteudo .= "
                            <span data-bs-toggle='modal' data-bs-target='#modal_adicionar_simulado'><button type='button' class='$button_classes_info btn-block ms-0' data-bs-toggle='modal' data-bs-target='#modal_adicionar_prova'>Adicionar prova da etapa</button></span>
                            ";
	}
	$template_modal_body_conteudo .= "
                            <span data-bs-toggle='modal' data-bs-target='#modal_adicionar_simulado'><button type='button' class='$button_classes btn-block ms-0' data-bs-toggle='modal' data-bs-target='#modal_adicionar_texto_apoio'>Adicionar texto de apoio</button></span>
                        ";
	$template_modal_body_conteudo .= "
                            <span data-bs-toggle='modal' data-bs-target='#modal_adicionar_simulado'><button type='button' class='$button_classes btn-block ms-0' data-bs-toggle='modal' data-bs-target='#modal_adicionar_questao'>Adicionar questão</button></span>
                        ";
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_edicao';
	$template_modal_titulo = 'Adicionar edição do curso';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_edicao_ano' id='nova_edicao_ano' required>
                              <label for='nova_edicao_ano'>Ano</label>
                            </div>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_edicao_titulo' id='nova_edicao_titulo' required>
                              <label for='nova_edicao_titulo'>Título</label>
                            </div>
						";
	if ($edicoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Edições registradas para o $pagina_curso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_id = $edicao['id'];
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
			$template_modal_body_conteudo .= "
				<li class='list-group-item'>$edicao_ano: $edicao_titulo</li>
			";
		}
		$template_modal_body_conteudo .= "
			</ul>
		";
	}
	$template_modal_submit_name = 'nova_edicao_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_etapa';
	$template_modal_titulo = 'Adicionar etapa da edição';
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "
                                <select class='$select_classes' name='nova_etapa_edicao' required>
                                      <option value='' disabled selected>Edição do concurso:</option>
                                ";
	mysqli_data_seek($edicoes, 0);
	if ($edicoes->num_rows > 0) {
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_id = $edicao['id'];
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
			$template_modal_body_conteudo .= "<option value='$edicao_id'>$edicao_ano : $edicao_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                                <div class='md-form'>
                                  <input type='text' class='form-control' name='nova_etapa_titulo' id='nova_etapa_titulo' required>
                                  <label for='nova_etapa_titulo'>Título da nova etapa</label>
                                </div>
                            ";
	if ($etapas->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Etapas registradas para o $pagina_curso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($etapa = $etapas->fetch_assoc()) {
			$etapa_titulo = $etapa['titulo'];
			$etapa_edicao_id = $etapa['edicao_id'];
			$query = prepare_query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
			$edicoes = $conn->query($query);
			while ($edicao = $edicoes->fetch_assoc()) {
				$edicao_ano = $edicao['ano'];
				$edicao_titulo = $edicao['titulo'];
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>$edicao_ano: $edicao_titulo: $etapa_titulo</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_submit_name = 'nova_etapa_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_prova';
	$template_modal_titulo = 'Adicionar prova da etapa';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_etapa' required>
                                  <option value='' disabled selected>Etapa do concurso:</option>";
	mysqli_data_seek($etapas, 0);
	while ($etapa = $etapas->fetch_assoc()) {
		$etapa_id = $etapa['id'];
		$etapa_titulo = $etapa['titulo'];
		$etapa_edicao_id = $etapa['edicao_id'];
		mysqli_data_seek($edicoes, 0);
		$query = prepare_query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
		$edicoes = $conn->query($query);
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
		}
		$template_modal_body_conteudo .= "<option value='$etapa_id'>$edicao_ano: $edicao_titulo: $etapa_titulo</option>";
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_tipo' required>
                              <option value='' disabled selected>Tipo de prova:</option>
                              <option value='1'>Objetiva</option>
                              <option value='2'>Dissertativa</option>
                              <option value='3'>Oral</option>
                              <option value='4'>Física</option>
                            </select>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo' required>
                              <label for='nova_prova_titulo'>Título da prova</label>
                            </div>
                        ";
	if ($provas->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Provas registradas para o $pagina_curso_sigla</h3>
			<ul class='list-group'>
		";
		while ($prova = $provas->fetch_assoc()) {
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo ($prova_tipo_string)</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_submit_name = 'nova_prova_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_texto_apoio';
	$template_modal_titulo = 'Adicionar texto de apoio';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <div class='form-check pl-0'>
                                <input id='novo_texto_apoio_origem' name='novo_texto_apoio_origem' type='checkbox' class='form-check-input' checked>
                                <label class='form-check-label' for='novo_texto_apoio_origem'>Texto de apoio oficial do concurso.</label>
                            </div>
						";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='novo_texto_apoio_prova' required>
                              <option value='' disabled selected>Selecione a prova:</option>
                              <option value='0'>Texto de apoio não é oficial</option>";
	if ($provas->num_rows > 0) {
		while ($prova = $provas->fetch_assoc()) {
			$prova_id = $prova['id'];
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<option value='$prova_id'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
	
	$template_modal_form_id = 'form_novo_texto_apoio';
	$template_modal_body_conteudo .= "<h3>Enunciado:</h3>";
	$sim_quill_id = 'texto_apoio_enunciado';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Texto de apoio:</h3>";
	$sim_quill_id = 'texto_apoio';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	if ($textos_apoio->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3 class='mt-3'>Textos de apoio registrados para o $pagina_curso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$texto_apoio_id = $texto_apoio['id'];
			$texto_apoio_origem = $texto_apoio['origem'];
			if ($texto_apoio_origem == 1) {
				$texto_apoio_origem_string = 'oficial';
			} else {
				$texto_apoio_origem_string = 'não-oficial';
			}
			$texto_apoio_prova_id = $texto_apoio['prova_id'];
			$texto_apoio_titulo = $texto_apoio['titulo'];
			$find_prova_info = return_info_prova_id($texto_apoio_prova_id);
			$prova_titulo = $find_prova_info[0];
			$prova_tipo = $find_prova_info[1];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_edicao_ano = $find_prova_info[2];
			$prova_edicao_titulo = $find_prova_info[3];
			$template_modal_body_conteudo .= "<li class='list-group-item'>$prova_edicao_ano: $prova_edicao_titulo: $texto_apoio_titulo</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	
	
	$template_modal_submit_name = 'novo_texto_apoio_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_questao';
	$template_modal_titulo = 'Adicionar questão';
	$template_modal_form_id = 'form_nova_questao';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                <h3>Dados gerais</h3>
            ";
	$template_modal_body_conteudo .= "
						<div class='form-check pl-0'>
                            <input id='nova_questao_origem' name='nova_questao_origem' type='checkbox' class='form-check-input' checked>
                            <label class='form-check-label' for='nova_questao_origem'>Questão oficial do concurso.</label>
                        </div>
						";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_texto_apoio'>
                              <option value='' disabled selected>Selecione o texto de apoio:</option>
                              <option value='0'>Questão não tem texto de apoio</option>
                              ";
	mysqli_data_seek($textos_apoio, 0);
	if ($textos_apoio->num_rows > 0) {
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$texto_apoio_id = $texto_apoio['id'];
			$texto_apoio_origem = $texto_apoio['origem'];
			if ($texto_apoio_origem == 1) {
				$texto_apoio_origem_string = 'oficial';
			} else {
				$texto_apoio_origem_string = 'não-oficial';
			}
			$texto_apoio_prova_id = $texto_apoio['prova_id'];
			$texto_apoio_titulo = $texto_apoio['titulo'];
			$find_prova_info = return_info_prova_id($texto_apoio_prova_id);
			$prova_titulo = $find_prova_info[0];
			$prova_tipo = $find_prova_info[1];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_edicao_ano = $find_prova_info[2];
			$prova_edicao_titulo = $find_prova_info[3];
			$template_modal_body_conteudo .= "<option value='$texto_apoio_id'>$prova_edicao_ano: $texto_apoio_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "<p class='mt-2'>Se a questão não tem texto de apoio, é necessário indicar a prova de que faz parte:</p>";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_prova'>
                                <option value='' disabled selected>Selecione a prova:</option>
                                <option value='0'>Questão livre e não-oficial, não faz parte de prova</option>
    ";
	mysqli_data_seek($provas, 0);
	if ($provas->num_rows > 0) {
		while ($prova = $provas->fetch_assoc()) {
			$prova_id = $prova['id'];
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<option value='$prova_id'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_materia' required>
                              <option value='' disabled selected>Selecione a matéria:</option>
                        ";
	if ($materias->num_rows > 0) {
		while ($materia = $materias->fetch_assoc()) {
			$materia_pagina_id = $materia['id'];
			$materia_titulo = return_pagina_titulo($materia_pagina_id);
			if ($materia_titulo == false) {
				continue;
			}
			$template_modal_body_conteudo .= "<option value='$materia_pagina_id'>$materia_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero' required>
                              <label for='nova_questao_numero'>Número da questão</label>
                            </div>
						";
	$template_modal_body_conteudo .= "
                <select class='mdb-select md-form' name='nova_questao_tipo' required>
                  <option value='' disabled selected>Selecione o tipo da questão:</option>
                  <option value='1'>Certo e errado</option>
                  <option value='2'>Múltipla escolha</option>
                  <option value='3'>Dissertativa</option>
                </select>
            ";
	$template_modal_body_conteudo .= "<h3>Enunciado</h3>";
	$sim_quill_id = 'questao_enunciado';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 1</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do primeiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item1';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 2</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do segundo item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item2';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 3</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do terceiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item3';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 4</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quarto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item4';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 5</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quinto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item5';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;

	$query = prepare_query("SELECT edicao_ano, numero, materia, tipo FROM sim_questoes WHERE curso_id = $pagina_id AND origem = 1");
	$questoes = $conn->query($query);
	if ($questoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3 class='mt-3'>Questões registradas para o $pagina_curso_sigla</h3>
			<ul class='list-group'>
		";
		while ($questao = $questoes->fetch_assoc()) {
			$questao_edicao_ano = $questao['edicao_ano'];
			$questao_numero = $questao['numero'];
			$questao_materia = $questao['materia'];
			$questao_materia_titulo = return_pagina_titulo($questao_materia);
			$questao_tipo = $questao['tipo'];
			$template_modal_body_conteudo .= "<li class='list-group-item'>$questao_edicao_ano: $questao_materia_titulo: Questão $questao_numero</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	
	$template_modal_submit_name = 'nova_questao_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_novo_simulado';
	$template_modal_titulo = 'Gerar novo simulado';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
	    <p>Fazer simulados é muito importante, mas é necessário algum cuidado para que realmente ajude a trazer você mais próximo de seus objetivos. Fazer provas é uma habilidade que pode e deve ser desenvolvida pela prática, esse é seu objetivo principal ao fazer simulados, e não necessariamente o aprendizado do conteúdo.</p>
	    <p>Somente recomendamos que você comece a fazer simulados após haver estudado thodo o conteúdo do seu concurso pelo menos uma vez, mesmo que em um primeiro nível introdutório e superficial.</p>
	";
	
	$template_modal_body_conteudo .= "
        <form method='post'>
        <select class='$select_classes' name='novo_simulado_tipo' required>
              <option value='' disabled selected>Tipo:</option>
              <option value='todas_objetivas_oficiais'>Todas as questões objetivas oficiais</option>
              <option value='todas_dissertativas_oficiais'>Todas as questões dissertativas oficiais</option>
              <option value='todas_dissertativas_nao_oficiais'>Todas as questões dissertativas não-oficiais</option>
              <option value='todas_objetivas_inoficiais' disabled>Todas as questões objetivas não-oficiais</option>
              <option value='inteligente' disabled>Criado por nosso algoritmo</option>
              <option value='questao_errou' disabled>Apenas questões em que você errou pelo menos um item</option>
              <option value='itens_errou' disabled>Apenas itens que você errou no passado</option>
              <option value='questoes_estudados' disabled>Apenas questões de tópicos que você marcou como estudados</option>
            </select>
    ";
	$template_modal_body_conteudo .= "
        <div class='row justify-content-center'>
            <button name='novo_simulado_trigger' class='$button_classes'>Gerar simulado</button>
        </div>
        </form>
    ";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_criar_simulado';
	$template_modal_titulo = 'Criar simulado';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<p>Para criar um simulado é preciso determinar seu título e as questões que dele farão parte. Você pode fazer mudanças em seu simulado até que decida publicá-lo. Após sua publicação, não será possível alterá-lo, nem desfazer sua publicação. Caso seu simulado inclua questões dissertativas, alunos poderão pagar uma taxa, por você determinada, para que você corrija suas respostas.</p>
	";
	include 'templates/modal.php';
	
	*/
?>