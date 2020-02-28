<?php
	
	if (!isset($template_subtipo)) {
		$template_subtipo = 'livro';
	}
	if (!isset($template_subtipo_tipo)) {
		$template_subtipo_tipo = 'referencia';
	}
	if (!isset($template_subtipo_titulo)) {
		$template_subtipo_titulo = 'Livros';
	}
	if (!isset($artefato_col_limit)) {
		$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	}
	if (!isset($subtipo_artefato_modal)) {
		$subtipo_artefato_modal = false;
	}
	if (!isset($artefato_tipo)) {
		$artefato_tipo = "listar_$template_subtipo";
	}
	if (!isset($artefato_titulo)) {
		$artefato_titulo = $template_subtipo_titulo;
	}
	if (!isset($subtipo_artefato_link_classes)) {
		$subtipo_artefato_link_classes = 'selecionar_subcategoria';
	}
	
	$subtipo_artefato_link_classes_2 = $subtipo_artefato_link_classes;
	$subtipo_artefato_link_classes_2 .= "_$template_subtipo_tipo";
	
	$artefato_class = "subcategoria_$template_subtipo_tipo subcategorias";
	$artefato_link_classes = "$subtipo_artefato_link_classes $subtipo_artefato_link_classes_2";
	if ($template_subtipo == 'generico') {
		$artefato_value = $template_subtipo_tipo;
	} else {
		$artefato_value = $template_subtipo;
	}
	
	if (!isset($artefato_modal)) {
		if ($subtipo_artefato_modal != false) {
			$artefato_modal = $subtipo_artefato_modal;
		} else {
			$artefato_button = $template_subtipo;
			$artefato_name = 'trigger_subcategoria';
		}
	}
	$artefato_info = return_icone_subtipo($template_subtipo_tipo, $template_subtipo);
	$fa_icone = $artefato_info[0];
	$fa_color = $artefato_info[1];
	if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
		$artefato_icone_background = $artefato_info[2];
		$fa_color = 'text-white';
	}
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	unset($template_subtipo);
	unset($template_subtipo_tipo);
	unset($template_subtipo_titulo);
	unset($subtipo_artefato_link_classes);