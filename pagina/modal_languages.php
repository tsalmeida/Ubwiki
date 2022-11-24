<?php
	
	$template_modal_div_id = 'modal_languages';
	$template_modal_titulo = $pagina_translated['languages'];

	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<form method='post' class='row d-flex justify-content-center'>";
	
	$artefato_name = 'select_language';
	
	$artefato_id = 'modal_portugues';
	$artefato_titulo = 'Português';
	$artefato_button = 'pt';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'link-success';
	if ($user_language == 'pt') {
		$artefato_icone_background = 'bg-success';
		$fa_color = 'link-light';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_name = 'select_language';
	
	$artefato_id = 'modal_english';
	$artefato_titulo = 'English';
	$artefato_button = 'en';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'link-primary';
	if ($user_language == 'en') {
		$artefato_icone_background = 'bg-primary';
		$fa_color = 'link-light';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_name = 'select_language';
	
	$artefato_id = 'modal_espanol';
	$artefato_titulo = 'Español';
	$artefato_button = 'es';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'link-warning';
	if ($user_language == 'es') {
		$artefato_icone_background = 'bg-warning';
		$fa_color = 'link-light';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_name = 'select_language';
	
	$artefato_id = 'modal_francais';
	$artefato_titulo = 'Français';
	$artefato_button = 'fr';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'link-danger';
	if ($user_language == 'fr') {
		$artefato_icone_background = 'bg-danger';
		$fa_color = 'link-light';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</form>";
	
	include 'templates/modal.php';