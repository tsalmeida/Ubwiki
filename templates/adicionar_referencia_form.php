<?php
	if (!isset($adicionar_referencia_busca_texto)) {
		$adicionar_referencia_busca_texto = $pagina_translated['Preencha com o título da referência'];
	}
	if (!isset($adicionar_referencia_form_botao)) {
		$adicionar_referencia_form_botao = $pagina_translated['Adicionar referência'];
	}
	$form_return = false;
	
	$form_return .=  "
		<p>{$pagina_translated['Antes de criar uma referência, é necessário usar a ferramenta de busca, para garantir que não haja duplicidade em nosso banco de dados.']}</p>
		<div class='mb-3'>
	    <label for='busca_referencias' class='form-label'>$adicionar_referencia_busca_texto</label>
	    <input type='text' class='form-control' name='busca_referencias' id='busca_referencias' required>
	    
    </div>
    <div class='row d-flex justify-content-start'>
    	<div class='col'>
	    <button type='button' class='$button_classes btn-info ms-0' id='trigger_buscar_referencias'>{$pagina_translated['Buscar']}</button>
	    </div>
		</div>
    <div class='row border p-1 mt-1' id='referencias_disponiveis'>
    </div>
    <div class='row' id='criar_referencia_form'>
      <div class='col-12'>
        <div class='mb-3'>
          <label for='criar_referencia_titulo' class='form-label'>{$pagina_translated['Título da nova referência']}</label>
          <input type='text' class='form-control' name='criar_referencia_titulo' id='criar_referencia_titulo' required>
          
        </div>
        <div class='mb-3'>
        	<label for='criar_referencia_link' class='form-label'>{$pagina_translated['Link da nova referência (opcional)']}</label>
        	<input type='url' class='form-control' name='criar_referencia_link' id='criar_referencia_link'>
        	
				</div>
        <div class='mb-3'>
          <label for='criar_referencia_autor' class='form-label'>{$pagina_translated['Autor da nova referência (opcional)']}</label>
          <input type='text' class='form-control' name='criar_referencia_autor' id='criar_referencia_autor'>
          
          <button type='button' class='$button_classes btn-info' id='trigger_buscar_autores'>{$pagina_translated['Buscar']}</button>
        </div>
        <div class='row border p-1' id='autores_disponiveis'>
        </div>
        <input type='hidden' id='criar_referencia_tipo' name='criar_referencia_tipo' value='referencia'>
        <input type='hidden' id='criar_referencia_subtipo' name='criar_referencia_subtipo' value='livro'></input>
        <div class='row justify-content-center'>
          <button type='button' class='$button_classes' id='trigger_adicionar_referencia'>$adicionar_referencia_form_botao</button>
				</div>
      </div>
    </div>
  ";
	unset($adicionar_referencia_form_botao);
	unset($adicionar_referencia_busca_texto);
	return $form_return;