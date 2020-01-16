<?php
	
	if (!isset($produto_titulo)) {
		return false;
	}
	if (!isset($produto_apresentacao)) {
		return false;
	}
	if (!isset($produto_imagem)) {
		return false;
	}
	if (!isset($produto_pagina_id)) {
		return false;
	}
	unset($produto_return);
	
	$apresentacao_limite = 180;
	$apresentacao_length = strlen($produto_apresentacao);
	if ($apresentacao_length > $apresentacao_limite) {
		$produto_apresentacao = crop_text($produto_apresentacao, $apresentacao_limite);
	}
	
	$produto_return = "
					<div class='col-lg-3 col-md-4 col-sm-6 p-0 pr-2 pb-2'>
						<div class='card z-depth-0 grey lighten-2 p-1 my-1'>
							<div class='imagem_produto card-img-top grey lighten-3 rounded' style='background-image: url($produto_imagem);' title='$produto_titulo'></div>
							<div class='card-body bg-white p-2 rounded mt-1'>
								<h4 class='card-title'><a>$produto_titulo</a></h4>
								<span class='card-text'><small>$produto_apresentacao</small></span>
								<div class='row justify-content-center d-flex'>
									<a type='button' href='pagina.php?pagina_id=$produto_pagina_id' class='btn btn-primary btn-sm'>Ver mais</a>
								</div>
							</div>
						</div>
					</div>
				";
	
	unset($apresentacao_limite);
	unset($apresentacao_length);
	unset($produto_titulo);
	unset($produto_apresentacao);
	unset($produto_imagem);
	unset($produto_pagina_id);
	
	
	return $produto_return;
	
?>