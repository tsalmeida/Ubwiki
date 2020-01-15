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
	
	$produto_apresentacao = crop_text($produto_apresentacao, 180);
	
	return "
					<div class='col-3 p-0 pr-1'>
						<div class='card z-depth-0 grey lighten-2 p-1 my-1'>
							<div class='imagem_produto card-img-top grey lighten-3 rounded' style='background-image: url($produto_imagem);' title='$produto_titulo'></div>
							<div class='card-body bg-white p-2 rounded mt-1'>
								<h4 class='card-title'><a>$produto_titulo</a></h4>
								<p class='card-text'><small>$produto_apresentacao</small></p>
								<div class='row justify-content-center d-flex'>
									<a type='button' class='btn btn-primary btn-sm'>Ver mais</a>
								</div>
							</div>
						</div>
					</div>
				";

?>