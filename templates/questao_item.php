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
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_certo_{$questao_id}' name='item{$template_questao_item}_questao_{$questao_id}' value='1'>
							<label class='form-check-label' for='item{$template_questao_item}_certo_{$questao_id}'>Certo</label>
						</div>
						<div class='form-check form-check-inline'>
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_errado_{$questao_id}' name='item{$template_questao_item}_questao_{$questao_id}' value='2'>
							<label class='form-check-label' for='item{$template_questao_item}_errado_{$questao_id}'>Errado</label>
						</div>
						<div class='form-check form-check-inline'>
							<input type='radio' class='form-check-input' id='item{$template_questao_item}_branco_{$questao_id}' name='item{$template_questao_item}_questao_{$questao_id}' value='0' checked>
							<label class='form-check-label' for='item{$template_questao_item}_branco_{$questao_id}'><span class='text-muted'>Branco</span></label>
						</div>
					</div>
					";
	
	unset($template_questao_item);
	
	return $template_questao_result;
	
?>