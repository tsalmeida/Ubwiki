<?php
	$template_modal_div_id = 'modal_adicionar_youtube';
	$template_modal_titulo = $pagina_translated['Adicionar vídeo do YouTube'];
	$template_modal_body_conteudo = "
                    <div class='mb-3'>
                        <label data-error='inválido' data-success='válido' for='novo_video_link' class='form-label'>{$pagina_translated['Link para vídeo do YouTube']}</label>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate' required>
                    </div>
	";
	
	include 'templates/modal.php';