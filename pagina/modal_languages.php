<?php
	
	$template_modal_div_id = 'modal_languages';
	$template_modal_titulo = $pagina_translated['languages'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<form method='post' class='row d-flex justify-content-center'>";
	
	$artefato_name = 'select_language';
	
	$artefato_titulo = 'Português';
	$artefato_tipo = 'lg_pt';
	$artefato_button = 'pt';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-success';
	if ($user_language == 'pt') {
		$artefato_icone_background = 'success-color';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_titulo = 'English';
	$artefato_tipo = 'lg_en';
	$artefato_button = 'en';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-primary';
	if ($user_language == 'en') {
		$artefato_icone_background = 'primary-color';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_titulo = 'Español';
	$artefato_button = 'es';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-warning';
	if ($user_language == 'es') {
		$artefato_icone_background = 'warning-color';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_titulo = 'Français';
	$artefato_button = 'fr';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-danger';
	if ($user_language == 'fr') {
		$artefato_icone_background = 'danger-color';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</form>";
	
	unset($artefato_name);
	
	include 'templates/modal.php';