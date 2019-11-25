<?php
	
	include 'engine.php';
	include 'templates/html_head.php';
	
	if (!isset($concurso_id)) {
		header("Location:index.php");
	}
	$concurso_sigla = return_concurso_sigla($concurso_id);
	
	if (isset($_POST['nova_edicao_trigger'])) {
		if (isset($_POST['nova_edicao_ano'])) {
			$nova_edicao_ano = $_POST['nova_edicao_ano'];
		}
		if (isset($_POST['nova_edicao_titulo'])) {
			$nova_edicao_titulo = $_POST['nova_edicao_titulo'];
			$nova_edicao_titulo = escape_quotes($nova_edicao_titulo);
		}
		if (($nova_edicao_ano != false) && ($nova_edicao_titulo != false)) {
			$conn->query("INSERT INTO sim_edicoes (concurso_id, ano, titulo, user_id) VALUES ($concurso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
		}
	}
	
	if (isset($_POST['nova_etapa_trigger'])) {
		if (isset($_POST['nova_etapa_titulo'])) {
			$nova_etapa_titulo = $_POST['nova_etapa_titulo'];
			$nova_etapa_titulo = escape_quotes($nova_etapa_titulo);
		}
		if (isset($_POST['nova_etapa_edicao'])) {
			$nova_etapa_edicao = $_POST['nova_etapa_edicao'];
		}
		if (($nova_etapa_titulo != false) && ($nova_etapa_edicao != false)) {
			$conn->query("INSERT INTO sim_etapas (concurso_id, edicao_id, titulo, user_id) VALUES ($concurso_id, $nova_etapa_edicao, '$nova_etapa_titulo', $user_id)");
		}
	}
	
	if (isset($_POST['nova_prova_trigger'])) {
		if (isset($_POST['nova_prova_etapa'])) {
			$nova_prova_etapa = $_POST['nova_prova_etapa'];
		}
		if (isset($_POST['nova_prova_titulo'])) {
			$nova_prova_titulo = $_POST['nova_prova_titulo'];
			$nova_prova_titulo = escape_quotes($nova_prova_titulo);
		}
		if (isset($_POST['nova_prova_tipo'])) {
			$nova_prova_tipo = $_POST['nova_prova_tipo'];
		}
		if (($nova_prova_etapa != false) && ($nova_prova_titulo != false) && ($nova_prova_tipo != false)) {
			$conn->query("INSERT INTO sim_provas (concurso_id, etapa_id, titulo, tipo, user_id) VALUES ($concurso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
		}
	}
	
	if (isset($_POST['novo_texto_apoio_trigger'])) {
		if (isset($_POST['novo_texto_apoio_origem'])) {
			$novo_texto_apoio_origem = true;
		} else {
			$novo_texto_apoio_origem = false;
		}
		if (isset($_POST['novo_texto_apoio_prova'])) {
			$novo_texto_apoio_prova = $_POST['novo_texto_apoio_prova'];
		}
		if (isset($_POST['novo_texto_apoio_titulo'])) {
			$novo_texto_apoio_titulo = $_POST['novo_texto_apoio_titulo'];
			$novo_texto_apoio_prova = escape_quotes($novo_texto_apoio_prova);
		}
		if (isset($_POST['novo_texto_apoio_enunciado'])) {
			$novo_texto_apoio_enunciado = $_POST['novo_texto_apoio_enunciado'];
			$novo_texto_apoio_enunciado = escape_quotes($novo_texto_apoio_enunciado);
		}
		if (($novo_texto_apoio_origem != false) && ($novo_texto_apoio_prova != false) && ($novo_texto_apoio_titulo != false) && ($novo_texto_apoio_enunciado != false)) {
			$conn->query("INSERT INTO sim_textos_apoio (concurso_id, origem, prova_id, titulo, enunciado, user_id) VALUES ($concurso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado', $user_id)");
		}
	}
	
	if (isset($_POST['nova_questao_trigger'])) {
		if (isset($_POST['nova_questao_origem'])) {
			$nova_questao_origem = true;
		} else {
			$nova_questao_origem = false;
		}
		if (isset($_POST['nova_questao_texto_apoio'])) {
			$nova_questao_texto_apoio = $_POST['nova_questao_texto_apoio'];
		} else {
			$nova_questao_texto_apoio = false;
		}
		if (isset($_POST['nova_questao_prova'])) {
			$nova_questao_prova = $_POST['nova_questao_prova'];
		} else {
			if ($nova_questao_texto_apoio != false) {
				$nova_questao_prova = return_texto_apoio_prova_id($nova_questao_texto_apoio);
			} else {
				$nova_questao_prova = false;
			}
		}
		if (isset($_POST['nova_questao_enunciado'])) {
			$nova_questao_enunciado = $_POST['nova_questao_enunciado'];
			$nova_questao_enunciado = escape_quotes($nova_questao_enunciado);
		} else {
			$nova_questao_enunciado = false;
		}
		if (isset($_POST['nova_questao_numero'])) {
			$nova_questao_numero = $_POST['nova_questao_numero'];
		} else {
			$nova_questao_numero = false;
		}
		if (isset($_POST['nova_questao_materia'])) {
			$nova_questao_materia = $_POST['nova_questao_materia'];
		} else {
			$nova_questao_materia = false;
		}
		if (isset($_POST['nova_questao_tipo'])) {
			$nova_questao_tipo = $_POST['nova_questao_tipo'];
		} else {
			$nova_questao_tipo = false;
		}
		if ($_POST['nova_questao_item1_texto'] != '') {
			$nova_questao_item1_texto = $_POST['nova_questao_item1_texto'];
			$nova_questao_item1_texto = escape_quotes($nova_questao_item1_texto);
			$nova_questao_item1_texto = "'$nova_questao_item1_texto'";
		} else {
			$nova_questao_item1_texto = "NULL";
		}
		if ($_POST['nova_questao_item2_texto'] != '') {
			$nova_questao_item2_texto = $_POST['nova_questao_item2_texto'];
			$nova_questao_item2_texto = escape_quotes($nova_questao_item2_texto);
			$nova_questao_item2_texto = "'$nova_questao_item2_texto'";
		} else {
			$nova_questao_item2_texto = "NULL";
		}
		if ($_POST['nova_questao_item3_texto'] != '') {
			$nova_questao_item3_texto = $_POST['nova_questao_item3_texto'];
			$nova_questao_item3_texto = escape_quotes($nova_questao_item3_texto);
			$nova_questao_item3_texto = "'$nova_questao_item3_texto'";
		} else {
			$nova_questao_item3_texto = "NULL";
		}
		if ($_POST['nova_questao_item4_texto'] != '') {
			$nova_questao_item4_texto = $_POST['nova_questao_item4_texto'];
			$nova_questao_item4_texto = escape_quotes($nova_questao_item4_texto);
			$nova_questao_item4_texto = "'$nova_questao_item4_texto'";
		} else {
			$nova_questao_item4_texto = "NULL";
		}
		if ($_POST['nova_questao_item5_texto'] != '') {
			$nova_questao_item5_texto = $_POST['nova_questao_item5_texto'];
			$nova_questao_item5_texto = escape_quotes($nova_questao_item5_texto);
			$nova_questao_item5_texto = "'$nova_questao_item5_texto'";
		} else {
			$nova_questao_item5_texto = "NULL";
		}
		if (isset($_POST['nova_questao_item1_gabarito'])) {
			$nova_questao_item1_gabarito = $_POST['nova_questao_item1_gabarito'];
		} else {
			$nova_questao_item1_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item2_gabarito'])) {
			$nova_questao_item2_gabarito = $_POST['nova_questao_item2_gabarito'];
		} else {
			$nova_questao_item2_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item3_gabarito'])) {
			$nova_questao_item3_gabarito = $_POST['nova_questao_item3_gabarito'];
		} else {
			$nova_questao_item3_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item4_gabarito'])) {
			$nova_questao_item4_gabarito = $_POST['nova_questao_item4_gabarito'];
		} else {
			$nova_questao_item4_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item5_gabarito'])) {
			$nova_questao_item5_gabarito = $_POST['nova_questao_item5_gabarito'];
		} else {
			$nova_questao_item5_gabarito = "NULL";
		}
		$conn->query("INSERT INTO sim_questoes (origem, concurso_id, texto_apoio_id, prova_id, enunciado, numero, materia, tipo, item1, item2, item3, item4, item5, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $concurso_id, $nova_questao_texto_apoio, $nova_questao_prova, '$nova_questao_enunciado', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $nova_questao_item1_texto, $nova_questao_item2_texto, $nova_questao_item3_texto, $nova_questao_item4_texto, $nova_questao_item5_texto, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
	}
	
	if (isset($_POST['novo_simulado_trigger'])) {
		$novo_simulado_tipo = $_POST['novo_simulado_tipo'];
		header("Location:simulado.php?simulado_tipo=$novo_simulado_tipo");
	}
	
	$edicoes = $conn->query("SELECT id, ano, titulo FROM sim_edicoes WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$etapas = $conn->query("SELECT id, edicao_id, titulo FROM sim_etapas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$provas = $conn->query("SELECT id, etapa_id, titulo, tipo FROM sim_provas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$textos_apoio = $conn->query("SELECT id, origem, prova_id, titulo FROM sim_textos_apoio WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$materias = $conn->query("SELECT id, titulo FROM Materias WHERE concurso_id = $concurso_id ORDER BY ordem");
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
								
								$template_id = 'sobre_adicao_simulados';
								$template_titulo = 'Plataforma de simulados';
								$template_conteudo = false;
								$template_conteudo .= "
                            <p>A plataforma de simulados é populada por questões oficiais de concursos e por questões criadas pelos usuários da página.</p>
                            <p>Para acrescentar questões de edições passadas do concurso, é necessário registrar algumas informações prévias:</p>
                            <ol>
                                <li>A edição do concurso</li>
                                <li>A etapa da edição</li>
                                <li>A prova da etapa</li>
                            </ol>
                            <p>Caso a questão possua um texto de apoio, será também necessário registrá-lo primeiro.</p>
                            <p>É importante prestar atenção para que todos as questões não-oficiais sejam devidamente identificadas como tal. É possível criar textos de apoio não oficiais, mas também criar questões com os textos de apoio de edições passadas do concurso.</p>
                            <p>Selecione a opção abaixo para acessar os sistemas que permitem acrescentar provas anteriores do concurso:</p>
                        ";
								$template_conteudo .= "
                            <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_edicao'>Adicionar edição do concurso</button>
                        ";
								if ($edicoes->num_rows > 0) {
									$template_conteudo .= "
                                <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_etapa'>Adicionar etapa da edição</button>
                            ";
								}
								if ($etapas->num_rows > 0) {
									$template_conteudo .= "
                                <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_prova'>Adicionar prova da etapa</button>
                            ";
								}
								$template_conteudo .= "
                            <button type='button' class='$button_classes btn-block' data-toggle='modal' data-target='#modal_adicionar_texto_apoio'>Adicionar texto de apoio</button>
                        ";
								$template_conteudo .= "
                            <button type='button' class='$button_classes btn-block' data-toggle='modal' data-target='#modal_adicionar_questao'>Adicionar questão</button>
                        ";
								
								include 'templates/page_element.php';
							
							?>
            </div>
            <div id="coluna_direita" class="col-lg-5 col-sm-12">
							<?php
								$template_id = 'gerar_simulado';
								$template_titulo = 'Gerar simulado';
								$template_conteudo = false;
								$template_conteudo .= "
						    <p>Fazer simulados é muito importante, mas é necessário algum cuidado para que realmente ajude a trazer você mais próximo de seus objetivos. Fazer provas é uma habilidade que pode e deve ser desenvolvida pela prática, esse é seu objetivo principal ao fazer simulados, e não necessariamente o aprendizado do conteúdo.</p>
						    <p>Somente recomendamos que você comece a fazer simulados após haver estudado todo o conteúdo do seu concurso pelo menos uma vez, mesmo que em um primeiro nível introdutório e superficial.</p>
						";
								$template_conteudo .= "
                            <form method='post'>
                            <select class='$select_classes' name='novo_simulado_tipo' required>
                                  <option value='' disabled selected>Tipo:</option>
                                  <option value='todas_objetivas_oficiais'>Todas as questões objetivas oficiais</option>
                                  <option value='todas_objetivas_inoficiais'>Todas as questões objetivas não-oficiais</option>
                                  <option value='inteligente'>Criado por nosso algoritmo</option>
                                  <option value='questao_errou'>Apenas questões em que você errou pelo menos um item</option>
                                  <option value='itens_errou'>Apenas itens que você errou no passado</option>
                                  <option value='questoes_estudados'>Apenas questões de tópicos que você marcou como estudados</option>
                                </select>
                        ";
								$template_conteudo .= "
						    <div class='row justify-content-center'>
						        <button name='novo_simulado_trigger' class='$button_classes'>Gerar simulado</button>
						    </div>
						    </form>
						";
								include 'templates/page_element.php';
							?>
            </div>
        </div>
    </div>
		
		<?php
			
			$template_modal_div_id = 'modal_adicionar_edicao';
			$template_modal_titulo = 'Adicionar edição do concurso';
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
			$template_modal_submit_name = 'nova_edicao_trigger';
			include 'templates/modal.php';
			
			$template_modal_div_id = 'modal_adicionar_etapa';
			$template_modal_titulo = 'Adicionar etapa da edição';
			$template_modal_body_conteudo = false;
			
			$template_modal_body_conteudo .= "
                                <select class='$select_classes' name='nova_etapa_edicao' required>
                                      <option value='' disabled selected>Edição do concurso:</option>
                                ";
			while ($edicao = $edicoes->fetch_assoc()) {
				$edicao_id = $edicao['id'];
				$edicao_ano = $edicao['ano'];
				$edicao_titulo = $edicao['titulo'];
				$template_modal_body_conteudo .= "<option value='$edicao_id'>$edicao_ano : $edicao_titulo</option>";
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                                <div class='md-form'>
                                  <input type='text' class='form-control' name='nova_etapa_titulo' id='nova_etapa_titulo' required>
                                  <label for='nova_etapa_titulo'>Título da etapa</label>
                                </div>
                            ";
			$template_modal_submit_name = 'nova_etapa_trigger';
			include 'templates/modal.php';
			
			$template_modal_div_id = 'modal_adicionar_prova';
			$template_modal_titulo = 'Adicionar prova da etapa';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_etapa' required>
                                  <option value='' disabled selected>Etapa do concurso:</option>";
			while ($etapa = $etapas->fetch_assoc()) {
				$etapa_id = $etapa['id'];
				$etapa_titulo = $etapa['titulo'];
				$etapa_edicao_id = $etapa['edicao_id'];
				$edicoes = $conn->query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
				while ($edicao = $edicoes->fetch_assoc()) {
					$edicao_ano = $edicao['ano'];
					$edicao_titulo = $edicao['titulo'];
				}
				$template_modal_body_conteudo .= "<option value='$etapa_id'>$edicao_ano: $edicao_titulo: $etapa_titulo</option>";
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo' required>
                              <label for='nova_prova_titulo'>Título da prova</label>
                            </div>
                            <select class='$select_classes' name='nova_prova_tipo' required>
                              <option value='' disabled selected>Tipo de prova:</option>
                              <option value='1'>Objetiva</option>
                              <option value='2'>Discursiva</option>
                              <option value='3'>Oral</option>
                              <option value='4'>Física</option>
                            </select>
                        ";
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
					if ($prova_tipo == 1) {
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
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='novo_texto_apoio_enunciado' name='novo_texto_apoio_enunciado' class='md-textarea form-control' rows='3' required></textarea>
                              <label for='novo_texto_apoio_enunciado'>Enunciado do texto de apoio</label>
                            </div>
						";
			$template_modal_submit_name = 'novo_texto_apoio_trigger';
			include 'templates/modal.php';
			
			$template_modal_div_id = 'modal_adicionar_questao';
			$template_modal_titulo = 'Adicionar questão';
			$template_modal_body_conteudo = false;
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
					$template_modal_body_conteudo .= "<option value='$texto_apoio_id'>$prova_edicao_ano: ($texto_apoio_origem_string) ($prova_tipo_string) $prova_edicao_titulo: $prova_titulo : $texto_apoio_titulo</option>";
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
					if ($prova_tipo == 1) {
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
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_enunciado' name='nova_questao_enunciado' class='md-textarea form-control' rows='3' required></textarea>
                              <label for='nova_questao_enunciado'>Enunciado da questão</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero' required>
                              <label for='nova_questao_numero'>Número da questão</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_materia' required>
                              <option value='' disabled selected>Selecione a matéria:</option>
                        ";
			if ($materias->num_rows > 0) {
				while ($materia = $materias->fetch_assoc()) {
					$materia_id = $materia['id'];
					$materia_titulo = $materia['titulo'];
					$template_modal_body_conteudo .= "<option value='$materia_id'>$materia_titulo</option>";
				}
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_tipo' required>
                              <option value='' disabled selected>Selecione o tipo da questão:</option>
                              <option value='1'>Certo e errado</option>
                              <option value='2'>Múltipla escolha</option>
                              <option value='3'>Dissertativa</option>
                            </select>
						";
			$template_modal_body_conteudo .= "<h2 class='mt-2'>Itens</h2>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do primeiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_item1_texto' name='nova_questao_item1_texto' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_item1_texto'>Enunciado do primeiro item</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do segundo item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_item2_texto' name='nova_questao_item2_texto' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_item2_texto'>Enunciado do segundo item</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do terceiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_item3_texto' name='nova_questao_item3_texto' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_item3_texto'>Enunciado do terceiro item</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quarto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_item4_texto' name='nova_questao_item4_texto' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_item4_texto'>Enunciado do quarto item</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quinto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <textarea id='nova_questao_item5_texto' name='nova_questao_item5_texto' class='md-textarea form-control' rows='3'></textarea>
                              <label for='nova_questao_item5_texto'>Enunciado do quinto item</label>
                            </div>
						";
			$template_modal_submit_name = 'nova_questao_trigger';
			include 'templates/modal.php';
		
		?>

    </body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>