<?php
	
	if (!isset($template_modal_div_id)) {
		$template_modal_div_id = false;
	}
	if (!isset($template_modal_titulo)) {
		$template_modal_titulo = false;
	}
	if (!isset($template_modal_body_conteudo)) {
		$template_modal_body_conteudo = false;
	}
	if (!isset($template_modal_submit_name)) {
		$template_modal_submit_name = 'modal_form_submit';
	}
	
	echo "
	<div class='modal fade' id='$template_modal_div_id' role='dialog' tabindex='-1' >
    <div class='modal-dialog modal-lg' role = 'document' >
        <div class='modal-content' >
            <form method = 'post' >
                <div class='modal-header text-center' >
                    <h4 class='modal-title w-100' > $template_modal_titulo </h4 >
                    <button type = 'button' class='close' data-dismiss='modal' >
                        <i class='fal fa-times-circle' ></i >
                    </button >
                </div >
                <div class='modal-body mx-3' >
									$template_modal_body_conteudo
                </div >
                <div class='modal-footer d-flex justify-content-center' >
                    <button type = 'button' class='btn bg-lighter btn-md' data-dismiss='modal' ><i
                                class='fal fa-times-circle' ></i > Cancelar
                    </button >
                    <button type='submit' class='btn btn-primary btn-md' name='$template_modal_submit_name'><i class='fal fa-check' ></i > Salvar
                    </button >
                </div >
            </form >
        </div>
    </div>
	</div>
";
	
	unset($template_modal_div_id);
	unset($template_modal_titulo);
	unset($template_modal_body_conteudo);
	unset($template_modal_submit_name);

?>