<?php
	
	if (!isset($template_questao_item)) {
		return false;
	}
	
	$template_questao_result = "
					<div class='form-check-inline border border-light respostas_box justify-content-around grey lighten-5 rounded'>
						<div class='form-check form-check-inline'>
							<strong>Item $template_questao_item: </strong>
						</div>
						<div class='form-check form-check-inline'>
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_certo_{$count}' name='item{$template_questao_item}_questao_{$count}' value='1'>
							<label class='form-check-label' for='item{$template_questao_item}_certo_{$count}'>Certo</label>
						</div>
						<div class='form-check form-check-inline'>
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_errado_{$count}' name='item{$template_questao_item}_questao_{$count}' value='2'>
							<label class='form-check-label' for='item{$template_questao_item}_errado_{$count}'>Errado</label>
						</div>
						<div class='form-check form-check-inline'>
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_branco_{$count}' name='item{$template_questao_item}_questao_{$count}' value='0' checked>
							<label class='form-check-label' for='item{$template_questao_item}_branco_{$count}'><span class='text-muted'>Deixar em branco</span></label>
						</div>
					</div>
					";
	
	unset($template_questao_item);
	
	return $template_questao_result;
	
?>