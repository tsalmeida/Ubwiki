<?php
	if ($texto_user_id == $user_id) {
		
		$template_modal_div_id = 'modal_apagar_anotacao';
		$template_modal_titulo = 'Triturar documento';

		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <p>{$pagina_translated['Tem certeza? Não será possível recuperar sua anotação.']}</p>
	          <form method='post'>
	            <div class='d-grid gap-2 col-6 mx-auto'>
		            <button class='btn btn-danger' name='destruir_anotacao'>{$pagina_translated['Destruir']}</button>
	            </div>
	          </form>
            ";
		include 'templates/modal.php';
	}
	?>
