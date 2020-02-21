<?php

	if (!isset($fa_class)) {
		$fa_class = false;
	}
	if (!isset($artefato_template_thumb)) {
		$artefato_template_thumb = false;
	}
	if (!isset($artefato_icone)) {
		$artefato_icone = false;
	}
	if (!isset($artefato_subtipo)) {
		$artefato_subtipo = false;
	}
	if (!isset($artefato_tipo)) {
		$artefato_tipo = 'artefato';
	}
	if (!isset($artefato_class)) {
		$artefato_class = false;
	}
	if (!isset($artefato_icone_background)) {
		$artefato_icone_background = false;
	}
	if (!isset($fa_invert)) {
		$fa_invert = false;
	}
	
	if ($fa_invert == true) {
		$fa_invert = 'fa-swap-opacity';
	}
	
	$fa_color_talvez = 'text-primary';
	if (!isset($fa_icone)) {
		$artefato_icone_cores = convert_artefato_icone_cores($artefato_tipo);
		$fa_icone = $artefato_icone_cores[0];
		$fa_color_talvez = $artefato_icone_cores[1];
	}
	if (!isset($fa_color)) {
		$fa_color = $fa_color_talvez;
	}
	
	if (!isset($fa_icone)) {
	
	}
	if ($artefato_icone != false) {
		$fa_icone = $artefato_icone;
	}
	
	if (($fa_icone == 'fa-youtube-square') || ($fa_icone == 'fa-wikipedia-w') || ($fa_icone == 'fa-youtube')) {
		$fa_icone = "fab $fa_icone";
	} else {
		$fa_icone = "fad $fa_icone";
	}
	
	if (!isset($artefato_subtitulo)) {
		$artefato_subtitulo = false;
	}
	
	if (isset($artefato_estado)) {
		if ($artefato_estado == false) {
			$fa_color = 'text-light';
		}
	}
	
	if (($artefato_subtipo == 'imagem') || ($artefato_subtipo == 'imagem_privada')) {
		$fa_color = 'text-danger';
	}
	
	if (!isset($artefato_modal)) {
		$artefato_modal = "#modal_{$artefato_tipo}";
	}
	
	if (!isset($fa_color)) {
		$fa_color = 'text-primary';
	}
	
	if (!isset($artefato_link)) {
		$artefato_link = false;
	}
	
	if (!isset($artefato_criacao)) {
		$artefato_criacao = false;
	}
	
	$artefato_link_1 = false;
	$artefato_link_2 = false;
	if ($artefato_link != false) {
		$artefato_link_1 = "<a id='link_$artefato_tipo' href='$artefato_link' class='$fa_class $fa_color' title='$artefato_criacao'>";
		$artefato_link_2 = "</a>";
	} else {
		$artefato_link_1 = "<span data-toggle='modal' data-target='$artefato_modal' title='$artefato_criacao'><a id='trigger_$artefato_tipo' href='javascript:void(0);' class='$fa_color'>";
		$artefato_link_2 = "</a></span>";
	}

	if (!isset($fa_size)) {
		$fa_size = 'fa-5x';
	}

	if (!isset($artefato_col_limit)) {
		$artefato_col_limit = 'col-lg-2 col-md-3 col-sm-3 col-xs-3';
	}
	
	$length_check = "$artefato_titulo $artefato_subtitulo";
	$length_check = strip_tags($length_check, false);
	
	$titulo_class = false;
	$titulo_length = strlen($length_check);
	if ($titulo_length > 70) {
		$titulo_class = 'small';
	}
	
	if(!isset($artefato_background)) {
		$artefato_background = false;
	}
	
	if (!isset($artefato_badge)) {
		$artefato_badge = false;
	} else {
		$artefato_badge = "<span class='badge badge-pill grey lighten-5 text-dark artefato-badge position-absolute z-depth-0'><i class='fad $artefato_badge fa-fw'></i></span>";
	}
	
	$artefato_template_result = false;
	
	$artefato_template_result .= "
     <div id='artefato_$artefato_tipo' class='$artefato_col_limit py-3 artefato rounded $artefato_background $artefato_class' $artefato_template_thumb>
        $artefato_link_1
        $artefato_badge
        <span class='row justify-content-center text-center p-1 mx-1 rounded $artefato_icone_background'><i class='$fa_icone $fa_size fa-fw $fa_invert d-block'></i></span>
        <span class='row justify-content-center text-center mt-2 text-dark p-1 $titulo_class'>$artefato_titulo</span>
        <span class='row justify-content-center text-center text-muted p-1 $titulo_class'><em>$artefato_subtitulo</em></span>
        $artefato_link_2
      </div>
	";
	
	unset($artefato_icone_background);
	unset($artefato_criacao);
	unset($artefato_link);
	unset($fa_icone);
	unset($fa_color);
	unset($artefato_titulo);
	unset($artefato_page_id_titulo);
	unset($artefato_estado);
	unset($artefato_icone);
	unset($artefato_subtitulo);
	unset($artefato_subtipo);
	unset($artefato_tipo);
	unset($fa_size);
	unset($col_limit);
	unset($titulo_class);
	unset($artefato_modal);
	unset($length_check);
	unset($artefato_background);
	unset($artefato_badge);
	unset($artefato_class);
	unset($fa_invert);
	
	return $artefato_template_result;

?>