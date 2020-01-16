<?php

	if (!isset($produto_secao_texto)) {
		return false;
	}
	
	return "
									<div class='container-fluid'>
										<div class='row justify-content-start'>
											<div class='col'>
											<p class='p-limit'>$produto_secao_texto</p>
											</div>
										</div>
									</div>
	";
	
	unset($produto_secao_texto);