<?php
	$questao_item_string = converter_respostas('resposta', $template_questao_item);
	$questao_item_gabarito_string = converter_respostas('gabarito', $template_dado_questao_item_gabarito);
	$template_conteudo .= "
													    <div class='form-check-inline border border-light respostas_box justify-content-between grey lighten-5 rounded mt-1 px-3'>
													        <span><strong>$template_questao_item_nome</strong></span>
													        <span><strong>{$pagina_translated['Sua resposta']}:</strong> $questao_item_string</span>
                                	<span><strong>{$pagina_translated['Gabarito']}:</strong> $questao_item_gabarito_string</span>
                              </div>
													";
	if ($questao_item_gabarito_string == 'anulado') {
		$item_cor = 'list-group-item-warning';
	} else {
		if ($questao_item_string == $questao_item_gabarito_string) {
			$item_cor = 'list-group-item-success';
		} else {
			$item_cor = 'list-group-item-danger';
		}
		if ($questao_item_string == 'em branco') {
			$item_cor = 'list-group-item-secondary';
		}
	}
	$template_conteudo .= "<li class='list-group-item $item_cor rounded mt-1'>$template_dado_questao_item</li>";
?>