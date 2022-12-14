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
		$template_modal_submit_name = "trigger_$template_modal_div_id";
	}
	if (!isset($template_modal_form_id)) {
		$template_modal_form_id = "form_$template_modal_div_id";
	}
	if (!isset($template_modal_enctype)) {
		$template_modal_enctype = false;
	}
	if (!isset($template_modal_show_buttons)) {
		$template_modal_show_buttons = false;
	}
	if (!isset($modal_scrollable)) {
		$modal_scrollable = false;
	}
	if (!isset($modal_focus)) {
		$modal_focus = false;
	}
	if ($modal_scrollable == true) {
		$modal_scrollable = 'modal-dialog-scrollable';
	}

	echo "<div class='modal fade' id='$template_modal_div_id' tabindex='-1'>";
	echo "<div id='inner_$template_modal_div_id' class='modal-dialog modal-lg modal-dialog-centered $modal_scrollable' role='document'>";

	if ($template_modal_show_buttons == true) {
		echo "<form id='$template_modal_form_id' name='$template_modal_form_id' method='post' $template_modal_enctype>";
	}
	echo "<div class='modal-content'>";
	echo "<div class='modal-header justify-content-around'>
		<h2 class='modal-title' >$template_modal_titulo</h2>
		<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
		</div>
		<div id='body_$template_modal_div_id' class='modal-body'>$template_modal_body_conteudo</div>";
	if ($template_modal_show_buttons == true) {
		echo "	<div class='modal-footer'>
					<button type='button' class='btn btn-secondary' data-bs-dismiss='modal' >{$pagina_translated['cancel']}</button>
					<button type='submit' class='btn btn-primary' name='$template_modal_submit_name'>{$pagina_translated['save']}</button>
				</div>
		";
	}
	echo "</div>";
	if ($template_modal_show_buttons == true) {
		echo "</form>";
	}
	echo "</div>
		</div>";

	echo "<script type='text/javascript'>
			var $template_modal_div_id = document.getElementById('$template_modal_div_id');";
	if ($modal_focus != false) {
		echo "
			var $modal_focus = document.getElementById('$modal_focus');
				$template_modal_div_id.addEventListener('shown.bs.modal', function () {
				$modal_focus.focus();
			});
		";
	}

	echo "</script>";

	unset($modal_focus);
	unset($template_modal_div_id);
	unset($template_modal_titulo);
	unset($template_modal_body_conteudo);
	unset($template_modal_submit_name);
	unset($template_modal_form_id);
	unset($template_modal_enctype);
	unset($template_modal_show_buttons);
	unset($modal_scrollable);

?>