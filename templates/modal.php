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
		$template_modal_submit_name = "trigger_{$template_modal_div_id}";
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
	if (!isset($modal_scrollable)) {
		$modal_scrollable = false;
	}
	if ($modal_scrollable == true) {
		$modal_scrollable = 'modal-dialog-scrollable';
	}
	
	echo "<div class='modal fade' id='$template_modal_div_id' role='dialog' tabindex='-1'>";
	echo "
    <div class='modal-dialog $modal_scrollable modal-lg' role='document'>";
	
	if ($template_modal_show_buttons == true) {
		echo "
          <form id='$template_modal_form_id' name='$template_modal_form_id' method='post' $template_modal_enctype>
    ";
	}
        echo "<div class='modal-content'>";
	echo "
          <div class='modal-header justify-content-around'>
	            <h2 class='h2-responsive modal-title w-100' > $template_modal_titulo </h2>
	            <button type='button' class='close' data-dismiss='modal'>
	                <i class='fad fa-times-circle' ></i>
	            </button >
          </div>";
	echo "
          <div id='body_$template_modal_div_id' class='modal-body mx-3'>
  ";
	echo "
					$template_modal_body_conteudo
          </div>
	";
	if ($template_modal_show_buttons == true) {
		echo "
	          <div class='modal-footer d-flex justify-content-center'>
	              <button type='button' class='$button_classes_light' data-dismiss='modal' ><i class='far fa-times-circle fa-fw'></i> {$pagina_translated['cancel']}</button>
	              <button type='submit' class='$button_classes' name='$template_modal_submit_name'><i class='far fa-check fa-fw'></i > {$pagina_translated['save']}</button>
	          </div>
		";
	}
	echo "</div>";
	if ($template_modal_show_buttons == true) {
		echo "</form>";
	}
	echo "</div>";
	echo "</div>";
	
	unset($template_modal_div_id);
	unset($template_modal_titulo);
	unset($template_modal_body_conteudo);
	unset($template_modal_submit_name);
	unset($template_modal_form_id);
	unset($template_modal_enctype);
	unset($template_modal_show_buttons);
	unset($modal_scrollable);

?>