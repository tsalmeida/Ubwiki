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
	if (!isset($template_modal_form_id)) {
		$template_modal_form_id = "form_$template_modal_div_id";
	}
	if (!isset($template_modal_enctype)) {
		$template_modal_enctype = false;
	}
	if (!isset($template_modal_show_buttons)) {
		$template_modal_show_buttons = true;
	}
	
	echo "
	<div class='modal fade' id='$template_modal_div_id' role='dialog' tabindex='-1'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <form id='$template_modal_form_id' method='post' $template_modal_enctype>
                <div class='modal-header text-center'>
                    <h2 class='h2-responsive modal-title w-100' > $template_modal_titulo </h2>
                    <button type='button' class='close' data-dismiss='modal'>
                        <i class='fal fa-times-circle' ></i>
                    </button >
                </div>
                <div class='modal-body mx-3'>
									$template_modal_body_conteudo
                </div>";
	if ($template_modal_show_buttons == true) {
		echo "
	                <div class='modal-footer d-flex justify-content-center' >
	                    <button type='button' class='$button_classes_light' data-dismiss='modal' ><i
	                                class='fal fa-times-circle' ></i > Cancelar
	                    </button>
	                    <button type='submit' class='$button_classes' name='$template_modal_submit_name'><i class='fal fa-check' ></i > Salvar
	                    </button>
	                </div>
	  ";
	}
	echo "
					</form>
        </div>
    </div>
	</div>
";
	
	unset($template_modal_div_id);
	unset($template_modal_titulo);
	unset($template_modal_body_conteudo);
	unset($template_modal_submit_name);
	unset($template_modal_form_id);
	unset($template_modal_enctype);
	unset($template_modal_show_buttons);

?>