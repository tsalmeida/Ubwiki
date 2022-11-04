<?php
	include 'engine.php';
	
	if (isset($_GET['questao_id'])) {
		$questao_id = $_GET['questao_id'];
	} else {
		header('Location:index.php');
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
		$nova_questao_prova_info = return_info_prova_id($nova_questao_prova);
		$nova_questao_edicao_ano = $nova_questao_prova_info[2];
		$nova_questao_etapa_id = $nova_questao_prova_info[4];
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
		// ENUNCIADO
		if (isset($_POST['quill_novo_questao_enunciado_html'])) {
			$quill_novo_questao_enunciado_html = $_POST['quill_novo_questao_enunciado_html'];
			$quill_novo_questao_enunciado_html = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_html);
		} else {
			$quill_novo_questao_enunciado_html = false;
		}
		if (isset($_POST['quill_novo_questao_enunciado_text'])) {
			$quill_novo_questao_enunciado_text = $_POST['quill_novo_questao_enunciado_text'];
			$quill_novo_questao_enunciado_text = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_text);
		} else {
			$quill_novo_questao_enunciado_text = false;
		}
		if (isset($_POST['quill_novo_questao_enunciado_content'])) {
			$quill_novo_questao_enunciado_content = $_POST['quill_novo_questao_enunciado_content'];
			$quill_novo_questao_enunciado_content = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_content);
		} else {
			$quill_novo_questao_enunciado_content = false;
		}
		// ITEM 1
		$quill_novo_questao_item1_html = false;
		$quill_novo_questao_item1_text = false;
		$quill_novo_questao_item1_content = false;
		$quill_novo_questao_item2_html = false;
		$quill_novo_questao_item2_text = false;
		$quill_novo_questao_item2_content = false;
		$quill_novo_questao_item3_html = false;
		$quill_novo_questao_item3_text = false;
		$quill_novo_questao_item3_content = false;
		$quill_novo_questao_item4_html = false;
		$quill_novo_questao_item4_text = false;
		$quill_novo_questao_item4_content = false;
		$quill_novo_questao_item5_html = false;
		$quill_novo_questao_item5_text = false;
		$quill_novo_questao_item5_content = false;
		$nova_questao_item1_gabarito = "NULL";
		$nova_questao_item2_gabarito = "NULL";
		$nova_questao_item3_gabarito = "NULL";
		$nova_questao_item4_gabarito = "NULL";
		$nova_questao_item5_gabarito = "NULL";
		
		// ITEM 1
		if (isset($_POST['nova_questao_item1_gabarito'])) {
			$nova_questao_item1_gabarito = $_POST['nova_questao_item1_gabarito'];
			if (isset($_POST['quill_novo_questao_item1_html'])) {
				$quill_novo_questao_item1_html = $_POST['quill_novo_questao_item1_html'];
				$quill_novo_questao_item1_html = mysqli_real_escape_string($conn, $quill_novo_questao_item1_html);
				$quill_novo_questao_item1_html = "'$quill_novo_questao_item1_html'";
				$quill_novo_questao_item1_text = $_POST['quill_novo_questao_item1_text'];
				$quill_novo_questao_item1_text = mysqli_real_escape_string($conn, $quill_novo_questao_item1_text);
				$quill_novo_questao_item1_text = "'$quill_novo_questao_item1_text'";
				$quill_novo_questao_item1_content = $_POST['quill_novo_questao_item1_content'];
				$quill_novo_questao_item1_content = mysqli_real_escape_string($conn, $quill_novo_questao_item1_content);
				$quill_novo_questao_item1_content = "'$quill_novo_questao_item1_content'";
			}
		}
		if ($quill_novo_questao_item1_html == false) {
			$quill_novo_questao_item1_html = "NULL";
			$quill_novo_questao_item1_text = "NULL";
			$quill_novo_questao_item1_content = "NULL";
		}
		// ITEM 2
		if (isset($_POST['nova_questao_item2_gabarito'])) {
			$nova_questao_item2_gabarito = $_POST['nova_questao_item2_gabarito'];
			if (isset($_POST['quill_novo_questao_item2_html'])) {
				$quill_novo_questao_item2_html = $_POST['quill_novo_questao_item2_html'];
				$quill_novo_questao_item2_html = mysqli_real_escape_string($conn, $quill_novo_questao_item2_html);
				$quill_novo_questao_item2_html = "'$quill_novo_questao_item2_html'";
				$quill_novo_questao_item2_text = $_POST['quill_novo_questao_item2_text'];
				$quill_novo_questao_item2_text = mysqli_real_escape_string($conn, $quill_novo_questao_item2_text);
				$quill_novo_questao_item2_text = "'$quill_novo_questao_item2_text'";
				$quill_novo_questao_item2_content = $_POST['quill_novo_questao_item2_content'];
				$quill_novo_questao_item2_content = mysqli_real_escape_string($conn, $quill_novo_questao_item2_content);
				$quill_novo_questao_item2_content = "'$quill_novo_questao_item2_content'";
			}
		}
		if ($quill_novo_questao_item2_html == false) {
			$quill_novo_questao_item2_html = "NULL";
			$quill_novo_questao_item2_text = "NULL";
			$quill_novo_questao_item2_content = "NULL";
		}
		// ITEM 3
		if (isset($_POST['nova_questao_item3_gabarito'])) {
			$nova_questao_item3_gabarito = $_POST['nova_questao_item3_gabarito'];
			if (isset($_POST['quill_novo_questao_item3_html'])) {
				$quill_novo_questao_item3_html = $_POST['quill_novo_questao_item3_html'];
				$quill_novo_questao_item3_html = mysqli_real_escape_string($conn, $quill_novo_questao_item3_html);
				$quill_novo_questao_item3_html = "'$quill_novo_questao_item3_html'";
				$quill_novo_questao_item3_text = $_POST['quill_novo_questao_item3_text'];
				$quill_novo_questao_item3_text = mysqli_real_escape_string($conn, $quill_novo_questao_item3_text);
				$quill_novo_questao_item3_text = "'$quill_novo_questao_item3_text'";
				$quill_novo_questao_item3_content = $_POST['quill_novo_questao_item3_content'];
				$quill_novo_questao_item3_content = mysqli_real_escape_string($conn, $quill_novo_questao_item3_content);
				$quill_novo_questao_item3_content = "'$quill_novo_questao_item3_content'";
			}
		}
		if ($quill_novo_questao_item3_html == false) {
			$quill_novo_questao_item3_html = "NULL";
			$quill_novo_questao_item3_text = "NULL";
			$quill_novo_questao_item3_content = "NULL";
		}
		// ITEM 4
		if (isset($_POST['nova_questao_item4_gabarito'])) {
			$nova_questao_item4_gabarito = $_POST['nova_questao_item4_gabarito'];
			if (isset($_POST['quill_novo_questao_item4_html'])) {
				$quill_novo_questao_item4_html = $_POST['quill_novo_questao_item4_html'];
				$quill_novo_questao_item4_html = mysqli_real_escape_string($conn, $quill_novo_questao_item4_html);
				$quill_novo_questao_item4_html = "'$quill_novo_questao_item4_html'";
				$quill_novo_questao_item4_text = $_POST['quill_novo_questao_item4_text'];
				$quill_novo_questao_item4_text = mysqli_real_escape_string($conn, $quill_novo_questao_item4_text);
				$quill_novo_questao_item4_text = "'$quill_novo_questao_item4_text'";
				$quill_novo_questao_item4_content = $_POST['quill_novo_questao_item4_content'];
				$quill_novo_questao_item4_content = mysqli_real_escape_string($conn, $quill_novo_questao_item4_content);
				$quill_novo_questao_item4_content = "'$quill_novo_questao_item4_content'";
			}
		}
		if ($quill_novo_questao_item4_html == false) {
			$quill_novo_questao_item4_html = "NULL";
			$quill_novo_questao_item4_text = "NULL";
			$quill_novo_questao_item4_content = "NULL";
		}
		// ITEM 5
		if (isset($_POST['nova_questao_item5_gabarito'])) {
			$nova_questao_item5_gabarito = $_POST['nova_questao_item5_gabarito'];
			if (isset($_POST['quill_novo_questao_item5_html'])) {
				$quill_novo_questao_item5_html = $_POST['quill_novo_questao_item5_html'];
				$quill_novo_questao_item5_html = mysqli_real_escape_string($conn, $quill_novo_questao_item5_html);
				$quill_novo_questao_item5_html = "'$quill_novo_questao_item5_html'";
				$quill_novo_questao_item5_text = $_POST['quill_novo_questao_item5_text'];
				$quill_novo_questao_item5_text = mysqli_real_escape_string($conn, $quill_novo_questao_item5_text);
				$quill_novo_questao_item5_text = "'$quill_novo_questao_item5_text'";
				$quill_novo_questao_item5_content = $_POST['quill_novo_questao_item5_content'];
				$quill_novo_questao_item5_content = mysqli_real_escape_string($conn, $quill_novo_questao_item5_content);
				$quill_novo_questao_item5_content = "'$quill_novo_questao_item5_content'";
			}
		}
		if ($quill_novo_questao_item5_html == false) {
			$quill_novo_questao_item5_html = "NULL";
			$quill_novo_questao_item5_text = "NULL";
			$quill_novo_questao_item5_content = "NULL";
		}
		
		// GABARITOS
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

		$conn->query("UPDATE sim_questoes SET origem = $nova_questao_origem, edicao_ano = $nova_questao_edicao_ano, texto_apoio_id = $nova_questao_texto_apoio, etapa_id = $nova_questao_etapa_id, prova_id = $nova_questao_prova, enunciado_html = '$quill_novo_questao_enunciado_html', enunciado_text = '$quill_novo_questao_enunciado_text', enunciado_content = '$quill_novo_questao_enunciado_content', numero = $nova_questao_numero, materia = $nova_questao_materia, tipo = $nova_questao_tipo, item1_html = $quill_novo_questao_item1_html, item1_text = $quill_novo_questao_item1_text, item1_content = $quill_novo_questao_item1_content, item2_html = $quill_novo_questao_item2_html, item2_text = $quill_novo_questao_item2_text, item2_content = $quill_novo_questao_item2_content, item3_html = $quill_novo_questao_item3_html, item3_text = $quill_novo_questao_item3_text, item3_content = $quill_novo_questao_item3_content, item4_html = $quill_novo_questao_item4_html, item4_text = $quill_novo_questao_item4_text, item4_content = $quill_novo_questao_item4_content, item5_html = $quill_novo_questao_item5_html, item5_text = $quill_novo_questao_item5_text, item5_content = $quill_novo_questao_item5_content, item1_gabarito = $nova_questao_item1_gabarito, item2_gabarito = $nova_questao_item2_gabarito, item3_gabarito = $nova_questao_item3_gabarito, item4_gabarito = $nova_questao_item4_gabarito, item5_gabarito = $nova_questao_item5_gabarito WHERE id = $questao_id");

		$nova_questao_id = $conn->insert_id;
		$nova_questao_pagina_id = return_pagina_id($nova_questao_id, 'questao');
		
		$conn->query("INSERT INTO sim_questoes_arquivo (origem, edicao_ano, concurso_id, etapa_id, texto_apoio_id, prova_id, enunciado_html, enunciado_text, enunciado_content, numero, materia, tipo, item1_html, item1_text, item1_content, item2_html, item2_text, item2_content, item3_html, item3_text, item3_content, item4_html, item4_text, item4_content, item5_html, item5_text, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $concurso_id, $nova_questao_edicao_ano, $nova_questao_texto_apoio, $nova_questao_etapa_id, $nova_questao_prova, '$quill_novo_questao_enunciado_html', '$quill_novo_questao_enunciado_text', '$quill_novo_questao_enunciado_content', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $quill_novo_questao_item1_html, $quill_novo_questao_item1_text, $quill_novo_questao_item1_content, $quill_novo_questao_item2_html, $quill_novo_questao_item2_text, $quill_novo_questao_item2_content, $quill_novo_questao_item3_html, $quill_novo_questao_item3_text, $quill_novo_questao_item3_content, $quill_novo_questao_item4_html, $quill_novo_questao_item4_text, $quill_novo_questao_item4_content, $quill_novo_questao_item5_html, $quill_novo_questao_item5_text, $quill_novo_questao_item5_content, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
	}
	
	$questoes = $conn->query("SELECT origem, concurso_id, texto_apoio_id, prova_id, enunciado_html, enunciado_content, numero, materia, tipo, item1_html, item2_html, item3_html, item4_html, item5_html, item1_content, item2_content, item3_content, item4_content, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito FROM sim_questoes WHERE id = $questao_id");
	if ($questoes->num_rows > 0) {
		while ($questao = $questoes->fetch_assoc()) {
			$questao_texto_apoio_id = $questao['texto_apoio_id'];
			$questao_prova_id = $questao['prova_id'];
			$questao_enunciado = $questao['enunciado_html'];
			$questao_enunciado_content = $questao['enunciado_content'];
			$questao_numero = $questao['numero'];
			$questao_materia = $questao['materia'];
			$questao_tipo = $questao['tipo'];
			$questao_item1 = $questao['item1_html'];
			$questao_item2 = $questao['item2_html'];
			$questao_item3 = $questao['item3_html'];
			$questao_item4 = $questao['item4_html'];
			$questao_item5 = $questao['item5_html'];
			$questao_item1_content = $questao['item1_content'];
			$questao_item2_content = $questao['item2_content'];
			$questao_item3_content = $questao['item3_content'];
			$questao_item4_content = $questao['item4_content'];
			$questao_item5_content = $questao['item5_content'];
			$questao_item1_gabarito = $questao['item1_gabarito'];
			$questao_item2_gabarito = $questao['item2_gabarito'];
			$questao_item3_gabarito = $questao['item3_gabarito'];
			$questao_item4_gabarito = $questao['item4_gabarito'];
			$questao_item5_gabarito = $questao['item5_gabarito'];
			break;
		}
	}
	
	$prova_info = return_info_prova_id($questao_prova_id);
	$prova_titulo = $prova_info[0];
	$prova_tipo = $prova_info[1];
	$prova_tipo_string = convert_prova_tipo($prova_tipo);
	$edicao_ano = $prova_info[2];
	$edicao_titulo = $prova_info[3];
	
	$textos_apoio = $conn->query("SELECT id, origem, prova_id, titulo FROM sim_textos_apoio WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$provas = $conn->query("SELECT id, etapa_id, titulo, tipo FROM sim_provas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$materias = $conn->query("SELECT id, titulo FROM Materias WHERE concurso_id = $concurso_id ORDER BY ordem");
	
	$html_head_template_quill = true;
	$html_head_template_quill_sim = true;
	include 'templates/html_head.php';
?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla $edicao_ano: $prova_titulo ($prova_tipo_string): Questão $questao_numero";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						
						$template_id = 'verbete_questao';
						$template_titulo = 'Verbete';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_botoes = false;
						$template_load_invisible = true;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						$template_id = 'dados_questao';
						$template_titulo = 'Dados da Questão';
						$template_botoes = "
						    <a data-bs-toggle='modal' data-bs-target='#modal_questao_form' href=''>
                                <i class='fal fa-pen-square fa-fw'></i>
                            </a>
						";
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group'>";
						$template_conteudo .= "
						<li class='list-group-item'><strong>Concurso:</strong> $concurso_sigla</li>
						<li class='list-group-item'><strong>Ano:</strong> $edicao_ano</li>
						<li class='list-group-item'><strong>Prova:</strong> $prova_titulo</li>
						<li class='list-group-item'><strong>Tipo:</strong> $prova_tipo_string</li>
						<li class='list-group-item'><strong>Número da questão:</strong> $questao_numero</li>";
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
						
						$template_id = 'gabarito_questao';
						if (($questao_tipo == 1) || ($questao_tipo == 2)) {
							$template_botoes = "
                                <span id='mostrar_gabarito' title='Mostrar gabarito'>
                                    <a href='javascript:void(0);'><i class='fal fa-eye fa-fw'></i></a>
                                </span>
                            ";
							$template_titulo = 'Itens e gabarito';
						} else {
						    $template_botoes = false;
						    $template_titulo = 'Enunciado';
                        }
						$template_conteudo = false;
						$template_conteudo .= "<div id='special_li'>$questao_enunciado</div>";
						$template_conteudo .= "<ul class='list-group'>";
						$mask_cor = 'list-group-item-light';
						if ($questao_item1 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item1_gabarito);
							$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1'><strong>Item 1:</strong></li>
							";
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $questao_item1
                                </li>";
						}
						if ($questao_item2 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item2_gabarito);
							$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1'><strong>Item 2:</strong></li>
							";
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $questao_item2
                                </li>";
						}
						if ($questao_item3 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item3_gabarito);
							$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1'><strong>Item 3:</strong></li>
							";
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $questao_item3
                                </li>";
						}
						if ($questao_item4 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item4_gabarito);
							$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1'><strong>Item 4:</strong></li>
							";
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $questao_item4
                                </li>";
						}
						if ($questao_item5 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item5_gabarito);
							$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1'><strong>Item 5:</strong></li>
							";
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $questao_item5
                                </li>";
						}
						
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
						
						if ($questao_texto_apoio_id != false) {
							$template_id = 'questao_texto_apoio';
							$template_titulo = 'Texto de apoio';
							$template_botoes = "
                                        <span id='pagina_texto_apoio' title='Página do texto de apoio'>
                                            <a href='textoapoio.php?texto_apoio_id=$questao_texto_apoio_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
                                        </span>
                                    ";
							$template_conteudo = false;
							$textos_apoio2 = $conn->query("SELECT texto_apoio_html FROM sim_textos_apoio WHERE id = $questao_texto_apoio_id");
							if ($textos_apoio2->num_rows > 0) {
								while ($texto_apoio2 = $textos_apoio2->fetch_assoc()) {
									$texto_apoio2_html = $texto_apoio2['texto_apoio_html'];
									$template_conteudo .= "<div id='special_li'>$texto_apoio2_html</div>";
								}
							}
							include 'templates/page_element.php';
						}
					
					
					?>
        </div>
        <div id="coluna_direita" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'anotacoes_questao';
						$template_titulo = 'Anotações privadas';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Você ainda não fez anotações sobre esta questão.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md bg-dark link-light p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
<?php
	
	$template_modal_div_id = 'modal_questao_form';
	$template_modal_titulo = 'Alterar questão';
	$template_modal_form_id = 'form_editar_questao';
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
                            ";
	if ($textos_apoio->num_rows > 0) {
		$count = 0;
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$count++;
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
			if ($count == 1) {
				if ($questao_texto_apoio_id == false) {
					$template_modal_body_conteudo .= "<option value='0' selected>Questão não tem texto de apoio</option>";
				} else {
					$template_modal_body_conteudo .= "<option value='0'>Questão não tem texto de apoio</option>";
				}
			}
			if ($texto_apoio_id == $questao_texto_apoio_id) {
				$template_modal_body_conteudo .= "<option value='$texto_apoio_id' selected>$prova_edicao_ano: $texto_apoio_titulo</option>";
			} else {
				$template_modal_body_conteudo .= "<option value='$texto_apoio_id'>$prova_edicao_ano: $texto_apoio_titulo</option>";
			}
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
			if ($prova_id == $questao_prova_id) {
				$template_modal_body_conteudo .= "<option value='$prova_id' selected>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
			} else {
				$template_modal_body_conteudo .= "<option value='$prova_id'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
			}
		}
	}
	$template_modal_body_conteudo .= "</select>";
	
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_materia' required>
                              <option value='' disabled>Selecione a matéria:</option>
                        ";
	if ($materias->num_rows > 0) {
		while ($materia = $materias->fetch_assoc()) {
			$materia_id = $materia['id'];
			$materia_titulo = $materia['titulo'];
			if ($materia_id == $questao_materia) {
				$template_modal_body_conteudo .= "<option value='$materia_id' selected>$materia_titulo</option>";
			} else {
				$template_modal_body_conteudo .= "<option value='$materia_id'>$materia_titulo</option>";
			}
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero' value='$questao_numero' required>
                              <label for='nova_questao_numero'>Número da questão</label>
                            </div>
						";
	$ce_selected = false;
	$me_selected = false;
	$di_selected = false;
	if ($questao_tipo == 1) {
		$ce_selected = 'selected';
	} elseif ($questao_tipo == 2) {
		$me_selected = 'selected';
	} elseif ($questao_tipo == 3) {
		$di_selected = 'selected';
	}
	$template_modal_body_conteudo .= "
                <select class='mdb-select md-form' name='nova_questao_tipo' required>
                  <option value='' disabled>Selecione o tipo da questão:</option>
                  <option value='1' $ce_selected>Certo e errado</option>
                  <option value='2' $me_selected>Múltipla escolha</option>
                  <option value='3' $di_selected>Dissertativa</option>
                </select>
            ";
	$template_modal_body_conteudo .= "<h3>Enunciado</h3>";
	$sim_quill_id = 'questao_enunciado';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 1</h3>";
	$item_certo = false;
	$item_errado = false;
	$item_anulado = false;
	$item_inexiste = false;
	if ($questao_item1_gabarito == 1) {
		$item_certo = 'selected';
	} elseif ($questao_item1_gabarito == 2) {
		$item_errado = 'selected';
	} elseif ($questao_item1_gabarito === 0) {
		$item_anulado = 'selected';
	} else {
		$item_inexiste = 'selected';
	}
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled $item_inexiste>Selecione o gabarito do primeiro item</option>
                                <option value='1' $item_certo>Certo</option>
                                <option value='2' $item_errado>Errado</option>
                                <option value='0' $item_anulado>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item1';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 2</h3>";
	$item_certo = false;
	$item_errado = false;
	$item_anulado = false;
	$item_inexiste = false;
	if ($questao_item2_gabarito == 1) {
		$item_certo = 'selected';
	} elseif ($questao_item2_gabarito == 2) {
		$item_errado = 'selected';
	} elseif ($questao_item2_gabarito === 0) {
		$item_anulado = 'selected';
	} else {
		$item_inexiste = 'selected';
	}
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled $item_inexiste>Selecione o gabarito do segundo item</option>
                                <option value='1' $item_certo>Certo</option>
                                <option value='2' $item_errado>Errado</option>
                                <option value='0' $item_anulado>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item2';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 3</h3>";
	$item_certo = false;
	$item_errado = false;
	$item_anulado = false;
	$item_inexiste = false;
	if ($questao_item3_gabarito == 1) {
		$item_certo = 'selected';
	} elseif ($questao_item3_gabarito == 2) {
		$item_errado = 'selected';
	} elseif ($questao_item3_gabarito === 0) {
		$item_anulado = 'selected';
	} else {
		$item_inexiste = 'selected';
	}
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled $item_inexiste>Selecione o gabarito do terceiro item</option>
                                <option value='1' $item_certo>Certo</option>
                                <option value='2' $item_errado>Errado</option>
                                <option value='0' $item_anulado>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item3';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 4</h3>";
	$item_certo = false;
	$item_errado = false;
	$item_anulado = false;
	$item_inexiste = false;
	if ($questao_item4_gabarito == 1) {
		$item_certo = 'selected';
	} elseif ($questao_item4_gabarito == 2) {
		$item_errado = 'selected';
	} elseif ($questao_item4_gabarito === 0) {
		$item_anulado = 'selected';
	} else {
		$item_inexiste = 'selected';
	}
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled $item_inexiste>Selecione o gabarito do quarto item</option>
                                <option value='1' $item_certo>Certo</option>
                                <option value='2' $item_errado>Errado</option>
                                <option value='0' $item_anulado>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item4';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 5</h3>";
	$item_certo = false;
	$item_errado = false;
	$item_anulado = false;
	$item_inexiste = false;
	if ($questao_item5_gabarito == 1) {
		$item_certo = 'selected';
	} elseif ($questao_item5_gabarito == 2) {
		$item_errado = 'selected';
	} elseif ($questao_item5_gabarito === 0) {
		$item_anulado = 'selected';
	} else {
		$item_inexiste = 'selected';
	}
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled $item_inexiste>Selecione o gabarito do quinto item</option>
                                <option value='1' $item_certo>Certo</option>
                                <option value='2' $item_errado>Errado</option>
                                <option value='0' $item_anulado>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item5';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_submit_name = 'nova_questao_trigger';
	include 'templates/modal.php';

?>
</body>

<?php
	
	echo "
        <script type='text/javascript'>
            quill_questao_enunciado.setContents($questao_enunciado_content);
            quill_questao_item1.setContents($questao_item1_content);
            quill_questao_item2.setContents($questao_item2_content);
            quill_questao_item3.setContents($questao_item3_content);
            quill_questao_item4.setContents($questao_item4_content);
            quill_questao_item5.setContents($questao_item5_content);
        </script>
    ";
	
	
	$mdb_select = true;
	$gabarito = true;
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_questao';
	include 'templates/esconder_anotacoes.php';
	include 'templates/footer.html';
?>
