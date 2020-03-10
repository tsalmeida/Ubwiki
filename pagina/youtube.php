<?php
	$template_modal_div_id = 'modal_adicionar_youtube';
	$template_modal_titulo = $pagina_translated['Adicionar vídeo do YouTube'];
	$template_modal_body_conteudo = "
                    <div class='md-form mb-2'>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate'
                               required>
                        <label data-error='inválido' data-success='válido'
                               for='novo_video_link'>{$pagina_translated['Link para vídeo do YouTube']}</label>
                    </div>
	";
	
	include 'templates/modal.php';