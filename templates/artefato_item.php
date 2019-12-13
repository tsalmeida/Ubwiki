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
		$artefato_tipo = false;
	}
	
	$artefato_icone_cores = convert_artefato_icone_cores($artefato_tipo);
	$fa_icone = $artefato_icone_cores[0];
	$fa_color = $artefato_icone_cores[1];
	
	
	
	if ($artefato_icone != false) {
		$fa_icone = $artefato_icone;
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
	
	$artefato_link_1 = false;
	$artefato_link_2 = false;
	if ($artefato_link != false) {
		$artefato_link_1 = "<a href='$artefato_link' class='$fa_class $fa_color' title='$artefato_criacao'>";
		$artefato_link_2 = "</a>";
	} else {
		$artefato_link_1 = "<span id='novo_$artefato_tipo' data-toggle='modal' data-target='#modal_$artefato_tipo' title='$artefato_criacao'><a href='javascript:void(0);' class='$fa_color'>";
		$artefato_link_2 = "</a></span>";
	}

	if (!isset($fa_size)) {
		$fa_size = 'fa-4x';
	}

	$artefato_template_result = false;
	$artefato_template_result .= "
     <div class='col-lg-2 col-md-3 col-sm-4 col-xs-12 py-3 artefato rounded' $artefato_template_thumb>
        $artefato_link_1<span class='row justify-content-center text-center'><i class='fad $fa_icone $fa_size fa-fw d-block'></i></span>
        <span class='row justify-content-center text-center mt-2 text-dark'>$artefato_titulo</span>
        <span class='row justify-content-center text-center text-muted'><em>$artefato_subtitulo</em></span>$artefato_link_2
    </div>
	";
	
	unset($artefato_criacao);
	unset($artefato_link);
	unset($fa_icone);
	unset($fa_primary_color);
	unset($fa_secondary_color);
	unset($artefato_titulo);
	unset($artefato_page_id_titulo);
	unset($artefato_estado);
	unset($artefato_icone);
	unset($artefato_subtitulo);
	unset($artefato_subtipo);
	unset($artefato_tipo);
	unset($fa_size);
	
	return $artefato_template_result;

?>