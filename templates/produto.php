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
	if ($produto_preco == false) {
		$produto_preco = $pagina_translated['Preço não-determinado'];
	} else {
		$produto_preco = "USD $produto_preco";
	}
	unset($produto_return);
	
	$apresentacao_limite = 300;
	$apresentacao_length = strlen($produto_apresentacao);
	if ($apresentacao_length > $apresentacao_limite) {
		$produto_apresentacao = crop_text($produto_apresentacao, $apresentacao_limite);
	}
	if ($produto_apresentacao == 0) {
		$produto_apresentacao = false;
	}
	
	$produto_return = "
					<div class='col-lg-3 col-md-4 col-sm-6 p-0 pr-2 pb-2'>
						<div class='card z-depth-0 bg-secondary p-1 my-1'>
							<div class='imagem_produto card-img-top bg-secondary rounded' style='background-image: url($produto_imagem);' title='$produto_titulo'>
								<div class='light-green rounded text-white p-1 m-1 small produto-preco'>$produto_preco</div>
							</div>
							<div class='card-body bg-white p-2 rounded mt-1 line-height-1'>
								<h4 class='card-title'>$produto_titulo</h4>
								<span class='card-text'><small class='line-height-1'>$produto_apresentacao</small></span>
								<div class='row justify-content-center d-flex'>
									<a type='button' href='pagina.php?pagina_id=$produto_pagina_id' class='btn btn-outline-primary'>Ver mais</a>
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
	unset($produto_preco);
	
	return $produto_return;
	
?>