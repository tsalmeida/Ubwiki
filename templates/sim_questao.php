<?php
	
	$template_id = "questao_{$questao_numero}_{$prova_id}";
	$template_titulo = "Questão $questao_numero";
	$template_titulo_heading = 'h4';
	$template_botoes = "
    <span id='pagina_questao_{$questao_numero}' title='{$pagina_translated['Página da questão']}'>
        <a href='questao.php?questao_id=$questao_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
    </span>
											    ";
	$template_conteudo = false;
	$template_conteudo .= "<div id='special_li'>$questao_enunciado</div>";
	if ($questao_tipo == 1) {
		$template_conteudo .= "<ol class='list-group'>";
		if ($questao_item1 != false) {
			$template_questao_item = '1';
			$template_conteudo .= include('templates/sim_questao_item.php');
			$template_conteudo .= $questao_item1;
		}
		if ($questao_item2 != false) {
			$template_questao_item = '2';
			$template_conteudo .= include('templates/sim_questao_item.php');
			$template_conteudo .= $questao_item2;
		}
		if ($questao_item3 != false) {
			$template_questao_item = '3';
			$template_conteudo .= include('templates/sim_questao_item.php');
			$template_conteudo .= $questao_item3;
		}
		if ($questao_item4 != false) {
			$template_questao_item = '4';
			$template_conteudo .= include('templates/sim_questao_item.php');
			$template_conteudo .= $questao_item4;
		}
		if ($questao_item5 != false) {
			$template_questao_item = '5';
			$template_conteudo .= include('templates/sim_questao_item.php');
			$template_conteudo .= $questao_item5;
		}
		
		$template_conteudo .= "</ol>";
	} elseif ($questao_tipo == 2) {
		$template_conteudo .= "
													<div class='form-check mb-3'>
														<input type='radio' class='form-check-input' id='item_branco_{$questao_id}' name='multipla_{$questao_id}' checked>
														<label class='form-check-label' for='item_branco_{$questao_id}' value='branco'><span class='text-muted'>Deixar em branco</span></label>
													</div>
												";
		if ($questao_item1 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item1_{$questao_id}' name='multipla_{$questao_id}'>
															<label class='form-check-label' for='item1_{$questao_id}' value='item1'>$questao_item1</label>
														</div>
													";
		}
		if ($questao_item2 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item2_{$questao_id}' name='multipla_{$questao_id}'>
															<label class='form-check-label' for='item2_{$questao_id}' value='item2'>$questao_item2</label>
														</div>
													";
		}
		if ($questao_item3 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item3_{$questao_id}' name='multipla_{$questao_id}'>
															<label class='form-check-label' for='item3_{$questao_id}' value='item3'>$questao_item3</label>
														</div>
													";
		}
		if ($questao_item4 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item4_{$questao_id}' name='multipla_{$questao_id}'>
															<label class='form-check-label' for='item4_{$questao_id}' value='item4'>$questao_item4</label>
														</div>
													";
		}
		if ($questao_item5 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item5_{$questao_id}' name='multipla_{$questao_id}'>
															<label class='form-check-label' for='item5_{$questao_id}' value='item5'>$questao_item5</label>
														</div>
													";
		}
	} elseif ($questao_tipo == 3) {
		
		$sim_quill_id = "dissertativa_{$questao_id}";
		$sim_quill_trigger = "enviar_respostas_{$questao_id}";
		$sim_quill_context = 'simulado_dissertativa';
		$sim_quill_result = include 'templates/sim_quill.php';
		$template_conteudo .= $sim_quill_result;
		
	}
	$template_conteudo .= "
			<div class='row d-flex justify-content-center'>
				<button type='button' class='btn btn-primary' id='enviar_respostas_{$questao_id}' value='$questao_id'>Enviar respostas da questão $questao_numero</button>
			</div>
		";
	if ($questao_tipo == 1) {
		$template_conteudo .= "
			<script type='text/javascript'>
				$(document).ready(function(){
					$('#enviar_respostas_{$questao_id}').click(function () {
						var item1_certo = $('input[id=item1_certo_{$questao_id}]:checked').val();
						if (item1_certo === undefined) {
						    item1_certo = false;
						}
						var item1_errado = $('input[id=item1_errado_{$questao_id}]:checked').val();
						if (item1_errado === undefined) {
						    item1_errado = false;
						}
						var item1_branco = $('input[id=item1_branco_{$questao_id}]:checked').val();
						if (item1_branco === undefined) {
						    item1_branco = false;
						}
						var item2_certo = $('input[id=item2_certo_{$questao_id}]:checked').val();
						if (item2_certo === undefined) {
						    item2_certo = false;
						}
						var item2_errado = $('input[id=item2_errado_{$questao_id}]:checked').val();
						if (item2_errado === undefined) {
						    item2_errado = false;
						}
						var item2_branco = $('input[id=item2_branco_{$questao_id}]:checked').val();
						if (item2_branco === undefined) {
						    item2_branco = false;
						}
						var item3_certo = $('input[id=item3_certo_{$questao_id}]:checked').val();
						if (item3_certo === undefined) {
						    item3_certo = false;
						}
						var item3_errado = $('input[id=item3_errado_{$questao_id}]:checked').val();
						if (item3_errado === undefined) {
						    item3_errado = false;
						}
						var item3_branco = $('input[id=item3_branco_{$questao_id}]:checked').val();
						if (item3_branco === undefined) {
						    item3_branco = false;
						}
						var item4_certo = $('input[id=item4_certo_{$questao_id}]:checked').val();
						if (item4_certo === undefined) {
						    item4_certo = false;
						}
						var item4_errado = $('input[id=item4_errado_{$questao_id}]:checked').val();
						if (item4_errado === undefined) {
						    item4_errado = false;
						}
						var item4_branco = $('input[id=item4_branco_{$questao_id}]:checked').val();
						if (item4_branco === undefined) {
						    item4_branco = false;
						}
						var item5_certo = $('input[id=item5_certo_{$questao_id}]:checked').val();
						if (item5_certo === undefined) {
						    item5_certo = false;
						}
						var item5_errado = $('input[id=item5_errado_{$questao_id}]:checked').val();
						if (item5_errado === undefined) {
						    item5_errado = false;
						}
						var item5_branco = $('input[id=item5_branco_{$questao_id}]:checked').val();
						if (item5_branco === undefined) {
						    item5_branco = false;
						}
						if (item1_certo !== false) {
						    var item1_resposta = 1;
						}	else if (item1_errado !== false) {
						    var item1_resposta = 2;
						} else if (item1_branco !== false) {
						    var item1_resposta = 0;
						} else {
						    var item1_resposta = 'null';
						}
						if (item2_certo !== false) {
						    var item2_resposta = 1;
						}	else if (item2_errado !== false) {
						    var item2_resposta = 2;
						} else if (item2_branco !== false) {
						    var item2_resposta = 0;
						} else {
						    var item2_resposta = 'null';
						}
						if (item3_certo !== false) {
						    var item3_resposta = 1;
						}	else if (item3_errado !== false) {
						    var item3_resposta = 2;
						} else if (item3_branco !== false) {
						    var item3_resposta = 0;
						} else {
						    var item3_resposta = 'null';
						}
						if (item4_certo !== false) {
						    var item4_resposta = 1;
						}	else if (item4_errado !== false) {
						    var item4_resposta = 2;
						} else if (item4_branco !== false) {
						    var item4_resposta = 0;
						} else {
						    var item4_resposta = 'null';
						}
						if (item5_certo !== false) {
						    var item5_resposta = 1;
						}	else if (item5_errado !== false) {
						    var item5_resposta = 2;
						} else if (item5_branco !== false) {
						    var item5_resposta = 0;
						} else {
						    var item5_resposta = 'null';
						}
						$.post('engine.php', {'user_id': {$user_id}, 'concurso_id': {$concurso_id}, 'questao_id': {$questao_id}, 'questao_numero': {$questao_numero}, 'questao_tipo': {$questao_tipo}, 'simulado_id': {$simulado_id}, 'item1': item1_resposta, 'item2': item2_resposta, 'item3': item3_resposta, 'item4': item4_resposta, 'item5': item5_resposta}, function(data) {
						    if (data != 0) {
									$('#enviar_respostas_{$questao_id}').prop('disabled', true);
						    }
						})
					});
				});
			</script>
		";
	} elseif ($questao_tipo == 2) {
		$template_conteudo .= "
			<script type='text/javascript'>
				$(document).ready(function(){
					$('#enviar_respostas_{$questao_id}').click(function () {
				    var item_branco = $('input[id=item_branco_{$questao_id}]:checked').val();
				    var item1_resposta = $('input[id=item1_{$questao_id}]:checked').val();
				    var item2_resposta = $('input[id=item2_{$questao_id}]:checked').val();
				    var item3_resposta = $('input[id=item3_{$questao_id}]:checked').val();
				    var item4_resposta = $('input[id=item4_{$questao_id}]:checked').val();
				    var item5_resposta = $('input[id=item5_{$questao_id}]:checked').val();
						if (item_branco !== undefined) {
						    var item_resposta = 0;
						}
						else if (item1_resposta !== undefined) {
						    var item_resposta = 1;
						}
						else if (item2_resposta !== undefined) {
						    var item_resposta = 2;
						}
						else if (item3_resposta !== undefined) {
						    var item_resposta = 3;
						}
						else if (item4_resposta !== undefined) {
						    var item_resposta = 4;
						}
						else if (item5_resposta !== undefined) {
						    var item_resposta = 5;
						}
						$.post('engine.php', {'user_id': {$user_id}, 'concurso_id': {$concurso_id}, 'questao_id': {$questao_id}, 'questao_numero': {$questao_numero}, 'questao_tipo': {$questao_tipo}, 'simulado_id': {$simulado_id}, 'resposta': item_resposta}, function(data) {
						    if (data != 0) {
									$('#enviar_respostas_{$questao_id}').prop('disabled', true);
						    }
						})
					});
				});
			</script>
		";
	} elseif ($questao_tipo == 3) {
		$template_conteudo .= "
			<script type='text/javascript'>
				$('body').on('click', '#enviar_respostas_{$questao_id}', function() {
	        var quill_novo_dissertativa_{$questao_id}_html = quill_dissertativa_{$questao_id}.root.innerHTML;
	        
	        var quill_novo_dissertativa_{$questao_id}_text = quill_dissertativa_{$questao_id}.getText();
	        
	        var quill_novo_dissertativa_{$questao_id}_content = quill_dissertativa_{$questao_id}.getContents();
	        quill_novo_dissertativa_{$questao_id}_content = JSON.stringify(quill_novo_dissertativa_{$questao_id}_content);

	        $.post('engine.php', {
	            'user_id': {$user_id},
	            'concurso_id': {$concurso_id},
	            'questao_id': {$questao_id},
	            'questao_numero': {$questao_numero},
	            'questao_tipo': {$questao_tipo},
	            'simulado_id': {$simulado_id},
	            'redacao_html': quill_novo_dissertativa_{$questao_id}_html,
	            'redacao_text': quill_novo_dissertativa_{$questao_id}_text,
	            'redacao_content': quill_novo_dissertativa_{$questao_id}_content
	        }, function(data) {
	            if (data != 0) {
	                $('#enviar_respostas_{$questao_id}').prop('disabled', true)
	            }
	        });
				});
			</script>
		";
	}
	include 'templates/page_element.php';