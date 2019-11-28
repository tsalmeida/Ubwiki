<?php
	$questao_item_string = converter_respostas($template_questao_item);
	$questao_item_gabarito_string = converter_respostas($template_dado_questao_item_gabarito);
	$template_conteudo .= "
													    <div class='form-check-inline border border-light respostas_box justify-content-around grey lighten-5 rounded mt-1'>
													        <span><strong>$template_questao_item_nome</strong></span>
													        <span><strong>Sua resposta:</strong> $questao_item_string</span>
                                	<span><strong>Gabarito:</strong> $questao_item_gabarito_string</span>
                              </div>
													";
	if ($questao_item_string == $questao_item_gabarito_string) {
		$item_cor = 'list-group-item-success';
	} else {
		$item_cor = 'list-group-item-danger';
	}
	if ($questao_item_string == 'em branco') {
		$item_cor = 'list-group-item-warning';
	}
	$template_conteudo .= "<li class='list-group-item $item_cor rounded mt-1'>$template_dado_questao_item</li>";
?>