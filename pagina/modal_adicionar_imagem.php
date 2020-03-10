<?php
	$template_modal_div_id = 'modal_adicionar_imagem';
	$template_modal_titulo = $pagina_translated['Adicionar imagem'];
	$template_modal_enctype = "enctype='multipart/form-data'";
	$template_modal_body_conteudo = "
				<input type='hidden' name='nova_imagem_subtipo' id='nova_imagem_subtipo'>
        <div class='md-form mb-2'>
            <input type='text' id='nova_imagem_titulo' name='nova_imagem_titulo'
                   class='form-control validate' required>
            <label data-error='inválido' data-success='válido'
                   for='nova_imagem_titulo'>{$pagina_translated['Título da imagem']}</label>
        </div>
        <div class='md-form mb-2'>
        <input type='url' id='nova_imagem_link' name='nova_imagem_link'
               class='form-control validate'>
        <label data-error='inválido' data-success='válido'
               for='nova_imagem_link'>{$pagina_translated['Link para a imagem']}</label>
        </div>
        <div class='md-form mb-2'>
            <div class='file-field'>
                <div class='btn btn-primary btn-sm float-left m-0'>
                    <span>{$pagina_translated['Selecione o arquivo']}</span>
                    <input type='file' name='nova_imagem_upload'>
                </div>
                <div class='file-path-wrapper'>
                    <input class='file-path validate' type='text' placeholder='{$pagina_translated['Faça upload da sua imagem']}'>
                </div>
            </div>
        </div>
    ";
	include 'templates/modal.php';
	