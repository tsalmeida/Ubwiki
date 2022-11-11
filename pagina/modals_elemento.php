<?php
	if ($elemento_estado == true) {
		$elemento_estado_visivel = 'publicado';
	} else {
		$elemento_estado_visivel = 'removido';
	}
	
	$dados_elemento = false;
	if ($elemento_tipo == 'imagem_privada') {
		$dados_elemento .= "<p>{$pagina_translated['Esta imagem é privada e não pode ser vista por outros usuários.']}</p>";
	}
	$dados_elemento .= "
                            <ul class='list-group mb-3'>
						        <li class='list-group-item'><strong>{$pagina_translated['Criado em']}:</strong> $elemento_criacao</li>";
	if ($elemento_tipo != 'imagem_privada') {
		$dados_elemento .= "<li class='list-group-item'><strong>{$pagina_translated['Estado de publicação']}:</strong> $elemento_estado_visivel</li>";
	}
	if ($elemento_titulo != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>{$pagina_translated['Título']}:</strong> $elemento_titulo</li>";
	}
	if ($elemento_autor != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>{$pagina_translated['Autor']}:</strong> $elemento_autor</li>";
	}
	if ($elemento_capitulo != false) {
		$dados_elemento .= "<li class='list-group-item'><strong>{$pagina_traslated['Capítulo']}:</strong> $elemento_capitulo</li>";
	}
	if ($elemento_ano != 0) {
		$dados_elemento .= "<li class='list-group-item'><strong>{$pagina_translated['Ano']}:</strong> $elemento_ano</li>";
	}
	if ($elemento_link != false) {
		$dados_elemento .= "<li class='list-group-item'><a href='$elemento_link' target='_blank'>{$pagina_translated['Link original']}</a></li>";
	}
	$dados_elemento .= "<li class='list-group-item'>{$pagina_translated['Adicionado por']} <strong><a href='pagina.php?user_id=$elemento_user_id' target='_blank'>$elemento_user_apelido</a></strong></li>";
	$dados_elemento .= "</ul>";
	
	$template_modal_div_id = 'modal_dados_elemento';
	$template_modal_titulo = $pagina_translated['Dados'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= $dados_elemento;
	$template_modal_body_conteudo .= "
				<div class='row justify-content-center'>
					<span data-bs-toggle='modal' data-bs-target='#modal_dados_elemento'>
						<button type='button' data-bs-toggle='modal' data-bs-target='#modal_elemento_form' class='btn btn-primary'>{$pagina_translated['Editar']}</button>
					</span>
				</div>
			";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_elemento_form';
	$template_modal_titulo = $pagina_translated['Alterar dados do elemento'];
	$template_modal_body_conteudo = false;
	
	$estado_elemento_checkbox = false;
	if ($elemento_estado == true) {
		$estado_elemento_checkbox = 'checked';
	}
	if ($elemento_tipo != 'imagem_privada') {
		$template_modal_body_conteudo .= "
                  <div class='form-check pl-0'>
                      <input type='checkbox' class='form-check-input' id='elemento_mudanca_estado' name='elemento_mudanca_estado' $estado_elemento_checkbox>
                      <label class='form-check-label' for='elemento_mudanca_estado'>{$pagina_translated['Adequado para publicação']}</label>
                  </div>
                ";
	} else {
		$template_modal_body_conteudo .= "
				    <input type='hidden' name='elemento_mudanca_estado' value='true'>
			    ";
	}
	
	$template_modal_body_conteudo .= "
		      <div class='mb-3'>
                  <label for='elemento_novo_titulo' class='form-label'>{$pagina_translated['Título']}</label>
                  <input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$elemento_titulo'>
                  
              </div>
          
              <div class='mb-3'>
                  <label for='elemento_novo_autor' class='form-label'>{$pagina_translated['Autor']}</label>
                  <input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$elemento_autor'>
                  
              </div>
            ";
	$template_modal_body_conteudo .= "
		        <div class='mb-3'>
                    <label for='elemento_novo_ano' class='form-label'>{$pagina_translated['Ano']}</label>
                    <input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$elemento_ano'>
                    
                </div>
	        ";
	
	$template_modal_submit_name = 'submit_elemento_dados';
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_elemento_subtipo';
	$template_modal_titulo = $pagina_translated['Classificar subtipo'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<form method='post' class='row d-flex justify-content-center'>";
	
	include 'pagina/elemento_subtipos.php';
	
	$template_modal_body_conteudo .= "</form>";
	
	include 'templates/modal.php';
?>