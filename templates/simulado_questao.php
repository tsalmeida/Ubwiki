<?php
	// Variáveis que devem ser registradas:
	// $questao_numero
	// $prova_id
	// $questao_enunciado
	// $questao_tipo
	// $questao_item1
	// $questao_item2
	// $questao_item3
	// $questao_item4
	// $questao_item5

	$template_id = "questao_{$questao_numero}_{$prova_id}";
	$template_titulo = "Questão $questao_numero";
	$template_titulo_heading = 'h4';
	$template_botoes = "
    <span id='pagina_questao_{$questao_numero}' title='Página da questão'>
        <a href='questao.php?questao_id=$questao_id' target='_blank'><i class='fal fa-external-link-square fa-fw'></i></a>
    </span>
											    ";
	$template_conteudo = false;
	$template_conteudo .= "<p>$questao_enunciado</p>";
	if ($questao_tipo == 1) {
		$template_conteudo .= "<ol class='list-group'>";
		if ($questao_item1 != false) {
			$template_questao_item = '1';
			$template_conteudo .= include('templates/questao_item.php');
			$template_conteudo .= "<p class='mt-2'>$questao_item1</p>";
		}
		if ($questao_item2 != false) {
			$template_questao_item = '2';
			$template_conteudo .= include('templates/questao_item.php');
			$template_conteudo .= "<p class='mt-2'>$questao_item2</p>";
		}
		if ($questao_item3 != false) {
			$template_questao_item = '3';
			$template_conteudo .= include('templates/questao_item.php');
			$template_conteudo .= "<p class='mt-2'>$questao_item3</p>";
		}
		if ($questao_item4 != false) {
			$template_questao_item = '4';
			$template_conteudo .= include('templates/questao_item.php');
			$template_conteudo .= "<p class='mt-2'>$questao_item4</p>";
		}
		if ($questao_item5 != false) {
			$template_questao_item = '5';
			$template_conteudo .= include('templates/questao_item.php');
			$template_conteudo .= "<p class='mt-2'>$questao_item5</p>";
		}

		$template_conteudo .= "</ol>";
	} elseif ($questao_tipo == 2) {
		$template_conteudo .= "
													<div class='form-check mb-3'>
														<input type='radio' class='form-check-input' id='item_branco_{$questao_id}' name='questao_{$questao_id}' checked>
														<label class='form-check-label' for='item_branco_{$questao_id}' value='branco'><span class='text-muted'>Deixar em branco</span></label>
													</div>
												";
		if ($questao_item1 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item_1_{$questao_id}' name='questao_{$questao_id}'>
															<label class='form-check-label' for='item_1_{$questao_id}' value='item1'>$questao_item1</label>
														</div>
													";
		}
		if ($questao_item2 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item_2_{$questao_id}' name='questao_{$questao_id}'>
															<label class='form-check-label' for='item_2_{$questao_id}' value='item2'>$questao_item2</label>
														</div>
													";
		}
		if ($questao_item3 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item_3_{$questao_id}' name='questao_{$questao_id}'>
															<label class='form-check-label' for='item_3_{$questao_id}' value='item3'>$questao_item3</label>
														</div>
													";
		}
		if ($questao_item4 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item_4_{$questao_id}' name='questao_{$questao_id}'>
															<label class='form-check-label' for='item_4_{$questao_id}' value='item4'>$questao_item4</label>
														</div>
													";
		}
		if ($questao_item5 != false) {
			$template_conteudo .= "
														<div class='form-check'>
															<input type='radio' class='form-check-input mt-1' id='item_5_{$questao_id}' name='questao_{$questao_id}'>
															<label class='form-check-label' for='item_5_{$questao_id}' value='item5'>$questao_item5</label>
														</div>
													";
		}
	}
	include 'templates/page_element.php';