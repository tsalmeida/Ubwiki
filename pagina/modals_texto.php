<?php
	if ($texto_user_id == $user_id) {
		
		$template_modal_div_id = 'modal_apagar_anotacao';
		$template_modal_titulo = 'Triturar documento';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <p>Tem certeza? Não será possível recuperar sua anotação.</p>
	          <form method='post'>
	            <div class='row justify-content-center'>
		            <button class='$button_classes_red' name='destruir_anotacao'>Destruir</button>
	            </div>
	          </form>
            ";
		include 'templates/modal.php';
	}
	?>
