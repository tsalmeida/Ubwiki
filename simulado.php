<?php
	include 'engine.php';
	include 'templates/html_head.php';
	
	$simulado_id = false;
	$simulado_tipo = false;
	if (isset($_GET['simulado_tipo'])) {
		$simulado_tipo = $_GET['simulado_tipo'];
	}
	if ($simulado_tipo == false) {
		header("Location:index.php");
	}
	$concurso_sigla = return_concurso_sigla($concurso_id);
?>

<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla: Simulado";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'simulado_metodo';
						$template_titulo = 'Método';
						$template_conteudo = false;
						$template_conteudo .= "<p>As suas respostas no simulado abaixo serão gravadas e influenciarão simulados gerados no futuro. Para que esse sistema funcione corretamente, recomendamos que você preencha as questões em condições próximas às reais por que você passará.</p>";
						$template_conteudo .= "<p>No entanto, sabemos que muitas pessoas usam simulados como uma forma de aprender o conteúdo e, portanto, podem preferir ter acesso a informações adicionais. Por esse motivo, nosso sistema inclui links para páginas sobre cada questão, assim como para páginas sobre os tópicos relacionados.</p>";
						$template_conteudo .= "<p>Nesses casos, recomendamos que você preencha o item de acordo com seu conhecimento, deixando para fazer sua pesquisa apenas posteriormente, sem que afete suas respostas iniciais. Dessa maneira, a análise posterior dos resultados não será influenciada.</p>";
						include 'templates/page_element.php';
						
						$template_id = 'simulado_dados';
						$template_titulo = 'Sobre este simulado';
						$template_conteudo = false;
						if ($simulado_tipo == 'todas_objetivas_oficiais') {
							$template_conteudo .= "<p>Este simulado inclui todas as questões oficiais deste curso de que dispomos em nosso banco de dados.</p>";
							$questoes = $conn->query("SELECT DISTINCT prova_id FROM sim_questoes WHERE concurso_id = $concurso_id AND origem = 1");
							if ($questoes->num_rows > 0) {
								$template_conteudo .= "<p>Provas oficiais registradas para este curso:
</p>";
								$template_conteudo .= "<ul class='list-group'>";
								$todas_as_provas = array();
								while ($questao = $questoes->fetch_assoc()) {
									$questao_prova_id = $questao['prova_id'];
									$questao_prova_info = return_info_prova_id($questao_prova_id);
									$questao_prova_titulo = $questao_prova_info[0];
									$questao_prova_tipo = $questao_prova_info[1];
									$questao_prova_tipo_string = convert_prova_tipo($questao_prova_tipo);
									$questao_edicao_ano = $questao_prova_info[2];
									$questao_edicao_titulo = $questao_prova_info[3];
									array_unshift($questao_prova_info, $questao_prova_id);
									array_push($todas_as_provas, $questao_prova_info);
									$template_conteudo .= "<li class='list-group-item list-group-item-action'><a href='prova.php?prova_id=$questao_prova_id' target='_blank'>$questao_edicao_ano: $questao_edicao_titulo: $questao_prova_titulo ($questao_prova_tipo_string)</a></li>";
								}
								$template_conteudo .= "<ul>";
								$simulado_id = false;
								$conn->query("INSERT INTO sim_gerados (user_id, concurso_id, tipo) VALUES ($user_id, $concurso_id, '$simulado_tipo')");
								$gerados = $conn->query("SELECT id FROM sim_gerados WHERE user_id = $user_id ORDER BY id DESC");
								while ($simulado = $gerados->fetch_assoc()) {
									$simulado_id = $simulado['id'];
									break;
								}
							} else {
								$template_conteudo .= "<p>Não há questões registradas para este curso.</p>";
							}
						}
						include 'templates/page_element.php';
						
						if (!isset($todas_as_provas)) {
							$todas_as_provas = false;
						} else {
							foreach ($todas_as_provas as $prova) {
								$prova_id = $prova[0];
								$prova_titulo = $prova[1];
								$prova_tipo = $prova[2];
								$prova_edicao_ano = $prova[3];
								$prova_edicao_titulo = $prova[4];
								
								$template_id = 'prova_dados';
								$template_titulo = "Prova de $prova_edicao_ano: $prova_titulo";
								$conn->query("INSERT INTO sim_detalhes (simulado_id, tipo, elemento_id) VALUES ($simulado_id, 'prova',
$prova_id)");
								$template_conteudo = false;
								include 'templates/page_element.php';
								$questoes_impressas = array();
								$textos_apoio_impressos = array();
								$materias = $conn->query("SELECT DISTINCT materia FROM sim_questoes WHERE prova_id = $prova_id ORDER BY numero");
								if ($materias->num_rows > 0) {
									while ($materia = $materias->fetch_assoc()) {
										$unique_materia_id = $materia['materia'];
										$unique_materia_titulo = return_materia_titulo_id($unique_materia_id);
										
										$template_id = "prova_materia_{$unique_materia_id}";
										$template_titulo = $unique_materia_titulo;
										$template_titulo_heading = 'h2';
										$template_conteudo = false;
										$conn->query("INSERT INTO sim_detalhes (simulado_id, tipo, elemento_id) VALUES ($simulado_id, 'materia', $unique_materia_id)");
										include 'templates/page_element.php';
										$questoes = $conn->query("SELECT id, texto_apoio_id, numero, enunciado, materia, tipo, item1, item2, item3, item4, item5, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito FROM sim_questoes WHERE prova_id = $prova_id AND materia = $unique_materia_id ORDER BY numero");
										if ($questoes->num_rows > 0) {
											while ($questao = $questoes->fetch_assoc()) {
												$questao_id = $questao['id'];
												$questao_texto_apoio_id = $questao['texto_apoio_id'];
												$questao_enunciado = $questao['enunciado'];
												$questao_numero = $questao['numero'];
												$questao_materia = $questao['materia'];
												$questao_tipo = $questao['tipo'];
												$questao_item1 = $questao['item1'];
												$questao_item2 = $questao['item2'];
												$questao_item3 = $questao['item3'];
												$questao_item4 = $questao['item4'];
												$questao_item5 = $questao['item5'];
												$questao_item1_gabarito = $questao['item1_gabarito'];
												$questao_item2_gabarito = $questao['item2_gabarito'];
												$questao_item3_gabarito = $questao['item3_gabarito'];
												$questao_item4_gabarito = $questao['item4_gabarito'];
												$questao_item5_gabarito = $questao['item5_gabarito'];
												if ($questao_texto_apoio_id != false) {
													$textos_apoio = $conn->query("SELECT titulo, enunciado FROM sim_textos_apoio WHERE id = $questao_texto_apoio_id");
													if ($textos_apoio->num_rows > 0) {
														while ($texto_apoio = $textos_apoio->fetch_assoc()) {
															$texto_apoio_titulo = $texto_apoio['titulo'];
															$texto_apoio_enunciado = $texto_apoio['enunciado'];
															$template_id = "texto_apoio_{$questao_texto_apoio_id}";
															$template_titulo = "Texto de Apoio: $texto_apoio_titulo";
															$template_titulo_heading = 'h3';
															$template_botoes = "
                                                                <span id='pagina_texto_apoio_{$questao_numero}' title='Página do texto de apoio'>
                                                                    <a href='textoapoio.php?texto_apoio_id=$questao_texto_apoio_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
                                                                </span>
                                                            ";
															$template_conteudo = false;
															$template_conteudo .= "<p>$texto_apoio_enunciado</p>";
															$texto_apoio_check = array_search($questao_texto_apoio_id, $textos_apoio_impressos);
															if ($texto_apoio_check === false) {
																array_push($textos_apoio_impressos, $questao_texto_apoio_id);
																$conn->query("INSERT INTO sim_detalhes (simulado_id, tipo, elemento_id) VALUES ($simulado_id, 'texto_apoio', $questao_texto_apoio_id)");
																include 'templates/page_element.php';
															}
															$questoes_texto_apoio = $conn->query("SELECT id FROM sim_questoes WHERE texto_apoio_id = $questao_texto_apoio_id");
															if ($questoes_texto_apoio->num_rows > 0) {
																while ($questao_texto_apoio = $questoes_texto_apoio->fetch_assoc()) {
																	$questao_check = array_search($questao_id, $questoes_impressas);
																	if ($questao_check === false) {
																		array_push($questoes_impressas, $questao_id);
																		$conn->query("INSERT INTO sim_detalhes (simulado_id, tipo, elemento_id) VALUES ($simulado_id, 'questao', $questao_id)");
																		include 'templates/simulado_questao.php';
																	}
																}
															}
														}
													}
												} else {
													array_push($questoes_impressas, $questao_id);
													$conn->query("INSERT INTO sim_detalhes (simulado_id, tipo, elemento_id) VALUES ($simulado_id, 'questao', $questao_id)");
													include 'templates/simulado_questao.php';
												}
											}
										}
									}
								}
							}
						}
						if ($simulado_id != false) {
							$template_id = 'ver_resultados';
							$template_titulo = 'Finalizar';
							$template_botoes = false;
							$template_conteudo = false;
							$template_conteudo .= "
						        <p>Ao pressionar o botão abaixo, você verá os resultados de todas as questões com respostas registradas. As questões não-registradas serão completamente ignoradas: não contarão como 'em branco' nem serão consideradas na análise.</p>
						        <p><strong>Não se esqueça de enviar as respostas de todas as questões!</strong></p>
						    ";
							$template_conteudo .= "
						        <div class='row d-flex justify-content-center'>
						            <a href='resultados.php?simulado_id=$simulado_id'>
						                <button type='button' class='$button_classes_red'>Finalizar simulado e ver resultados</button>
						            </a>
                                </div>
						    ";
							include 'templates/page_element.php';
						}
					
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

	