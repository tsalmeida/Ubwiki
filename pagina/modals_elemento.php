<?php
	if ($elemento_estado == true) {
		$elemento_estado_visivel = 'publicado';
	} else {
		$elemento_estado_visivel = 'removido';
	}
	
	$dados_elemento = false;
	if ($elemento_tipo == 'imagem_privada') {
		$dados_elemento .= "<p>Esta imagem é privada e não pode ser vista por outros usuários.</p>";
	}
	$dados_elemento .= "
                            <ul class='list-group'>
						        <li class='list-group-item'><strong>Criado em:</strong> $elemento_criacao</li>";
	if ($elemento_tipo != 'imagem_privada') {
		$dados_elemento .= "<li class='list-group-item'><strong>Estado de publicação:</strong> $elemento_estado_visivel</li>";
	}
	if ($elemento_titulo != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>Título:</strong> $elemento_titulo</li>";
	}
	if ($elemento_autor != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>Autor:</strong> $elemento_autor</li>";
	}
	if ($elemento_capitulo != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>Capítulo:</strong> $elemento_capitulo</li>";
	}
	if ($elemento_ano != 0) {
		$dados_elemento .= "<li class='list-group-item'><strong>Ano:</strong> $elemento_ano</li>";
	}
	if ($elemento_link != false) {
		$dados_elemento .= "<li class='list-group-item'><a href='$elemento_link' target='_blank'>Link original</a></li>";
	}
	$dados_elemento .= "<li class='list-group-item'>Adicionado pelo usuário <strong><a href='pagina.php?user_id=$elemento_user_id' target='_blank'>$elemento_user_apelido</a></strong></li>";
	$dados_elemento .= "</ul>";
	
	$template_modal_div_id = 'modal_dados_elemento';
	$template_modal_titulo = 'Dados';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= $dados_elemento;
	$template_modal_body_conteudo .= "
				<div class='row justify-content-center'>
					<span data-toggle='modal' data-target='#modal_dados_elemento'>
						<button type='button' data-toggle='modal' data-target='#modal_elemento_form' class='$button_classes'>Editar</button>
					</span>
				</div>
			";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_elemento_form';
	$template_modal_titulo = 'Alterar dados do elemento';
	$template_modal_body_conteudo = false;
	
	$estado_elemento_checkbox = false;
	if ($elemento_estado == true) {
		$estado_elemento_checkbox = 'checked';
	}
	if ($elemento_tipo != 'imagem_privada') {
		$template_modal_body_conteudo .= "
                  <div class='form-check pl-0'>
                      <input type='checkbox' class='form-check-input' id='elemento_mudanca_estado' name='elemento_mudanca_estado' $estado_elemento_checkbox>
                      <label class='form-check-label' for='elemento_mudanca_estado'>Adequado para publicação</label>
                  </div>
                ";
	} else {
		$template_modal_body_conteudo .= "
				    <input type='hidden' name='elemento_mudanca_estado' value='true'>
			    ";
	}
	
	$template_modal_body_conteudo .= "
		      <div class='md-form mb-2'>
                  <input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$elemento_titulo'>
                  <label for='elemento_novo_titulo'>Título</label>
              </div>
          
              <div class='md-form mb-2'>
                  <input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$elemento_autor'>
                  <label for='elemento_novo_autor'>Autor</label>
              </div>
            ";
	$template_modal_body_conteudo .= "
		        <div class='md-form mb-2'>
                    <input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$elemento_ano'>
                    <label for='elemento_novo_ano'>Ano</label>
                </div>
	        ";
	
	$template_modal_submit_name = 'submit_elemento_dados';
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_elemento_subtipo';
	$template_modal_titulo = "Classificar subtipo";
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<form method='post'><div class='row d-flex justify-content-center'>";
	
	include 'pagina/elemento_subtipos.php';
	
	$template_modal_body_conteudo .= "</form></div>";
	
	include 'templates/modal.php';
?>