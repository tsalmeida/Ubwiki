<?php
	include 'engine.php';
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	
	$simulado_id = false;
	if (isset($_GET['simulado_id'])) {
		$simulado_id = $_GET['simulado_id'];
	} else {
		header('Location:index.php');
	}
	
	$simulado_user_id = false;
	$simulado_tipo = false;
	$simulados = $conn->query("SELECT criacao, user_id, tipo FROM sim_gerados WHERE id = $simulado_id");
	if ($simulados->num_rows > 0) {
		while ($simulado = $simulados->fetch_assoc()) {
			$simulado_criacao = $simulado['criacao'];
			$simulado_user_id = $simulado['user_id'];
			$simulado_tipo = $simulado['tipo'];
		}
	}
	
	if ($simulado_user_id != $user_id) {
		header('Location:index.php');
	}
	
	if ($simulado_tipo == 'todas_objetivas_oficiais') {
	   $simulado_tipo_questoes = 'objetivas';
    }
	elseif ($simulado_tipo == 'todas_dissertativas_oficiais') {
		$simulado_tipo_questoes = 'dissertativas';
	}
	
	$simulados_elementos = $conn->query("SELECT tipo, elemento_id FROM sim_detalhes WHERE simulado_id = $simulado_id");
	if ($simulados_elementos->num_rows > 0) {
		$questoes_count = 0;
		$materias_count = 0;
		$textos_apoio_count = 0;
		while ($simulado_elemento = $simulados_elementos->fetch_assoc()) {
			$simulado_elemento_tipo = $simulado_elemento['tipo'];
			$simulado_elemento_id = $simulado_elemento['elemento_id'];
			if ($simulado_elemento_tipo == 'questao') {
				$questoes_count++;
			} elseif ($simulado_elemento_tipo == 'materia') {
				$materias_count++;
			} elseif ($simulado_elemento_tipo == 'texto_apoio') {
				$textos_apoio_count++;
			}
		}
	}

?>

<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "Resultados de simulado";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_pouco_maior_classes; ?>">
					<?php
						$template_id = 'simulado_dados';
						$template_titulo = 'Dados';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<p>Este simulado foi gerado em $simulado_criacao</p>";
						$template_conteudo .= "<p>Tipo de simulado: $simulado_tipo</p>";
						if ($simulado_tipo_questoes == 'objetivas') {
							$template_conteudo .= "<p>No documento abaixo, os itens que você acertou aparecem em verde, enquanto os que errou, em vermelho e os items que deixou em branco, em cinza. Itens anulados aparecem em amarelo.</p>";
						}
						elseif ($simulado_tipo_questoes == 'dissertativas') {
							$template_conteudo .= "<p>No documento abaixo, você encontrará as dissertações que escreveu em resposta às questões deste simulado.</p>";
						}
						
						if ($questoes_count == 0) {
							$template_conteudo .= "<p>Não há questão com resposta registrada para este simulado.</p>";
							include 'templates/page_element.php';
						} else {
							include 'templates/page_element.php';
							
							mysqli_data_seek($simulados_elementos, 0);
							while ($simulado_elemento = $simulados_elementos->fetch_assoc()) {
								$simulado_elemento_tipo = $simulado_elemento['tipo'];
								$simulado_elemento_id = $simulado_elemento['elemento_id'];
								if ($simulado_elemento_tipo == 'prova') {
									$prova_info = return_info_prova_id($simulado_elemento_id);
									$prova_titulo = $prova_info[0];
									$prova_edicao_ano = $prova_info[2];
									$template_id = "prova_{$simulado_elemento_id}";
									$template_titulo = "Prova de $prova_edicao_ano: $prova_titulo";
									$template_botoes = "
                                        <span id='pagina_prova_{$simulado_elemento_id}' title='Página da prova'>
                                            <a href='prova.php?prova_id=$simulado_elemento_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
                                        </span>
                                    ";
									include 'templates/page_element.php';
								} elseif ($simulado_elemento_tipo == 'materia') {
									$materia_titulo = return_materia_titulo_id($simulado_elemento_id);
									$template_id = "simulado_materia_{$simulado_elemento_id}";
									$template_titulo = $materia_titulo;
									$template_titulo_heading = 'h2';
									include 'templates/page_element.php';
								} elseif ($simulado_elemento_tipo == 'texto_apoio') {
									
									$textos_apoio = $conn->query("SELECT titulo, enunciado_html, texto_apoio_html FROM sim_textos_apoio WHERE id = $simulado_elemento_id");
									if ($textos_apoio->num_rows > 0) {
										while ($texto_apoio = $textos_apoio->fetch_assoc()) {
											$texto_apoio_titulo = $texto_apoio['titulo'];
											$texto_apoio_enunciado = $texto_apoio['enunciado_html'];
											$texto_apoio_html = $texto_apoio['texto_apoio_html'];
											$template_id = "simulado_texto_apoio_{$simulado_elemento_id}";
											$template_titulo = "Texto de apoio: $texto_apoio_titulo";
											$template_titulo_heading = 'h3';
											$template_botoes = "
                                                <span id='pagina_texto_apoio_{$simulado_elemento_id}' title='Página do texto de apoio'>
                                                    <a href='textoapoio.php?texto_apoio_id=$simulado_elemento_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
                                                </span>
                                            ";
											$template_conteudo = false;
											$template_conteudo .= $texto_apoio_enunciado;
											$template_load_invisible = true;
											$template_conteudo .= "<div id='special_li'>$texto_apoio_html</div>";
											include 'templates/page_element.php';
											break;
										}
									}
								} elseif ($simulado_elemento_tipo == 'questao') {
									$questoes = $conn->query("SELECT questao_numero, item1, item2, item3, item4, item5, multipla, redacao_html, redacao_text FROM sim_respostas WHERE questao_id = $simulado_elemento_id AND simulado_id = $simulado_id");
									if ($questoes->num_rows > 0) {
										while ($questao = $questoes->fetch_assoc()) {
											$questao_id = $simulado_elemento_id;
											$questao_numero = $questao['questao_numero'];
											$questao_item1 = $questao['item1'];
											$questao_item2 = $questao['item2'];
											$questao_item3 = $questao['item3'];
											$questao_item4 = $questao['item4'];
											$questao_item5 = $questao['item5'];
											$questao_multipla = $questao['multipla'];
											$questao_redacao_html = $questao['redacao_html'];
											$questao_redacao_text = $questao['redacao_text'];
											
											$dados_questoes = $conn->query("SELECT enunciado_html, item1_html, item2_html, item3_html, item4_html, item5_html, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito FROM sim_questoes WHERE id = $questao_id");
											if ($dados_questoes->num_rows > 0) {
												while ($dado_questao = $dados_questoes->fetch_assoc()) {
													$dado_questao_enunciado = $dado_questao['enunciado_html'];
													$dado_questao_item1 = $dado_questao['item1_html'];
													$dado_questao_item2 = $dado_questao['item2_html'];
													$dado_questao_item3 = $dado_questao['item3_html'];
													$dado_questao_item4 = $dado_questao['item4_html'];
													$dado_questao_item5 = $dado_questao['item5_html'];
													$dado_questao_item1_gabarito = $dado_questao['item1_gabarito'];
													$dado_questao_item2_gabarito = $dado_questao['item2_gabarito'];
													$dado_questao_item3_gabarito = $dado_questao['item3_gabarito'];
													$dado_questao_item4_gabarito = $dado_questao['item4_gabarito'];
													$dado_questao_item5_gabarito = $dado_questao['item5_gabarito'];
													$template_id = "questao_{$questao_id}";
													$template_titulo = "Questão $questao_numero";
													$template_botoes = false;
													$template_conteudo = false;
													if ($questao_redacao_html == false) {
														if ($questao_multipla == false) {
															$template_conteudo .= "<div id='special_li'>$dado_questao_enunciado</div>";
															$template_conteudo .= "<ul class='list-group'>";
															if ($dado_questao_item1 != false) {
																$template_questao_item_nome = 'Item 1';
																$template_questao_item = $questao_item1;
																$template_dado_questao_item_gabarito = $dado_questao_item1_gabarito;
																$template_dado_questao_item = $dado_questao_item1;
																include 'templates/sim_resultados_questao.php';
															}
															if ($dado_questao_item2 != false) {
																$template_questao_item_nome = 'Item 2';
																$template_questao_item = $questao_item2;
																$template_dado_questao_item_gabarito = $dado_questao_item2_gabarito;
																$template_dado_questao_item = $dado_questao_item2;
																include 'templates/sim_resultados_questao.php';
															}
															if ($dado_questao_item3 != false) {
																$template_questao_item_nome = 'Item 3';
																$template_questao_item = $questao_item3;
																$template_dado_questao_item_gabarito = $dado_questao_item3_gabarito;
																$template_dado_questao_item = $dado_questao_item3;
																include 'templates/sim_resultados_questao.php';
															}
															if ($dado_questao_item4 != false) {
																$template_questao_item_nome = 'Item 4';
																$template_questao_item = $questao_item4;
																$template_dado_questao_item_gabarito = $dado_questao_item4_gabarito;
																$template_dado_questao_item = $dado_questao_item4;
																include 'templates/sim_resultados_questao.php';
															}
															if ($dado_questao_item5 != false) {
																$template_questao_item_nome = 'Item 5';
																$template_questao_item = $questao_item5;
																$template_dado_questao_item_gabarito = $dado_questao_item5_gabarito;
																$template_dado_questao_item = $dado_questao_item5;
																include 'templates/sim_resultados_questao.php';
															}
															$template_conteudo .= "</ul>";
														} else {
															$template_conteudo .= "<p>Você escolheu o item $questao_multipla.</p>";
														}
													} else {
														$template_conteudo .= $dado_questao_enunciado;
														$questao_redacao_wordcount = str_word_count($questao_redacao_text);
														$template_conteudo .= "<h5 class='mt-5'>Sua resposta:</h5>";
														$template_conteudo .= "
															<ul class='list-group mb-3'>
																<li class='list-group-item list-group-item-secondary'><strong>Quantidade de palavras:</strong> $questao_redacao_wordcount</li>
															</ul>
														";
														$template_conteudo .= $questao_redacao_html;
													}
													$template_titulo_heading = 'h4';
													$template_botoes = "
                                                        <span id='pagina_questao_{$questao_id}' title='Página da questão'>
                                                            <a href='questao.php?questao_id=$questao_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
                                                        </span>
                                                    ";
													include 'templates/page_element.php';
												}
											}
										}
									}
								}
							}
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
