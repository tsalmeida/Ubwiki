<?php

	if (!isset($fa_class)) {
		$fa_class = false;
	}
	if (!isset($artefato_titulo)) {
		$artefato_titulo = false;
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
		if (isset($artefato_id)) {
			$artefato_tipo = $artefato_id;
		} else {
			$artefato_tipo = generateRandomString(6);
		}
	}
	if (!isset($artefato_class)) {
		$artefato_class = false;
	}

	if (!isset($fa_invert)) {
		$fa_invert = false;
	}

	if ($fa_invert == true) {
		$fa_invert = 'fa-swap-opacity';
	}

	$fa_color_talvez = 'link-primary';
	if (!isset($fa_icone)) {
		$fa_icone = 'fa-circle-notch fa-spin';
		$fa_color_talvez = 'link-purple';
	}
	if (!isset($fa_color)) {
		$fa_color = $fa_color_talvez;
	}
	if (!isset($fa_icone)) {
		$fa_icone = 'fa-circle-notch fa-spin';
	}
	if ($artefato_icone != false) {
		$fa_icone = $artefato_icone;
	}
	if (!isset($fa_type)) {
		$fa_type = 'fad';
	}
	if (!isset($fa_icone)) {
		$fa_icone = 'fa-circle-notch';
	}
	if (!isset($artefato_subtitulo)) {
		$artefato_subtitulo = false;
	}
	if (isset($artefato_estado)) {
		if ($artefato_estado == false) {
			$fa_color = 'link-purple';
		}
	}

	if (!isset($artefato_modal)) {
		$artefato_modal = "#modal_{$artefato_tipo}";
	}

	if (!isset($artefato_link)) {
		$artefato_link = false;
	}

	if (!isset($artefato_criacao)) {
		$artefato_criacao = false;
	}

	if (!isset($artefato_button)) {
		$artefato_button = false;
	}
	if (!isset($artefato_value)) {
		$artefato_value = false;
	} else {
		$artefato_value = "value='$artefato_value'";
	}
	if (!isset($fa_size)) {
		$fa_size = 'fa-5x';
	}
	if (!isset($artefato_name)) {
		$artefato_name = "trigger_$artefato_tipo";
	}
	if (!isset($artefato_col_limit)) {
		$artefato_col_limit = 'col-lg-2 col-md-4 col-sm-6';
	}
	if (!isset($artefato_link_classes)) {
		$artefato_link_classes = false;
	}
	if (!isset($artefato_titulo_class)) {
		$artefato_titulo_class = 'link-dark';
	}
	if (!isset($artefato_subtitulo_class)) {
		$artefato_subtitulo_class = 'link-secondary';
	}

	$length_check = "$artefato_titulo $artefato_subtitulo";
	$length_check = strip_tags($length_check, false);

	$titulo_class = false;
	$titulo_length = strlen($length_check);
	if ($titulo_length > 75) {
		$titulo_class = 'small';
	}
	if (!isset($artefato_background)) {
		$artefato_background = false;
	}
	if (!isset($artefato_badge)) {
		$artefato_badge = false;
	} else {
		$artefato_badge = "<span class='badge bg-secondary link-dark bg-light position-fixed translate-middle'><i class='fad $artefato_badge fa-fw'></i></span>";
	}

	if (!isset($artefato_icone_background)) {
//		$artefato_icone_background = convert_background($fa_color);
		$artefato_icone_background = false;
	}

	if (!isset($artefato_classes_detail)) {
		$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center border border-white mt-1';
	}

	$artefato_classes = "$artefato_col_limit $artefato_background $artefato_class $artefato_classes_detail";

	$artefato_link_1 = false;
	$artefato_link_2 = false;
	if ($artefato_button != false) {
		$artefato_link_1 = "<div id='artefato_$artefato_tipo' class='$artefato_classes'><button class='$fa_color bg-white border-0' name='$artefato_name' id='$artefato_name' value='$artefato_button' $artefato_template_thumb>";
		$artefato_link_2 = "</button></div>";
	} else {
		if ($artefato_link != false) {
			$artefato_link_1 = "<div class='$artefato_classes' id='artefato_$artefato_tipo'><a id='link_$artefato_tipo' href='$artefato_link' class='$fa_class $fa_color $artefato_link_classes' title='$artefato_criacao' $artefato_value $artefato_template_thumb>";
			$artefato_link_2 = "</a></div>";
		} else {
			$artefato_link_1 = "<span id='artefato_$artefato_tipo' data-bs-toggle='modal' data-bs-target='$artefato_modal' title='$artefato_criacao' class='$artefato_classes'><a id='trigger_$artefato_tipo' href='javascript:void(0);' class='$fa_color $artefato_link_classes' $artefato_value $artefato_template_thumb>";
			$artefato_link_2 = "</a></span>";
		}
	}

	$artefato_template_result = false;

	$artefato_template_result .= "
				$artefato_link_1
				$artefato_badge
		<span class='row justify-content-center d-flex text-center p-1 m-auto rounded $artefato_icone_background'>
        	<i class='$fa_type $fa_icone $fa_size fa-fw $fa_invert d-block'></i>
        </span>
        <span class='row justify-content-center d-flex text-center mt-2 p-1 artefato-titulo $titulo_class $artefato_titulo_class'>
        	$artefato_titulo
        </span>
        <span class='m-0 row justify-content-center d-flex fst-italic text-center p-1 $titulo_class $artefato_subtitulo_class'>
            $artefato_subtitulo
        </span>
        $artefato_link_2
  ";

	unset($artefato_name);
	unset($artefato_icone_background);
	unset($artefato_criacao);
	unset($artefato_link);
	unset($fa_icone);
	unset($fa_color);
	unset($fa_type);
	unset($artefato_button);
	unset($artefato_titulo);
	unset($artefato_page_id_titulo);
	unset($artefato_estado);
	unset($artefato_icone);
	unset($artefato_subtitulo);
	unset($artefato_subtipo);
	unset($artefato_tipo);
	unset($fa_size);
	unset($artefato_col_limit);
	unset($titulo_class);
	unset($artefato_modal);
	unset($length_check);
	unset($artefato_background);
	unset($artefato_badge);
	unset($artefato_class);
	unset($fa_invert);
	unset($artefato_value);
	unset($artefato_link_classes);
	unset($artefato_titulo_class);
	unset($artefato_subtitulo_class);
	unset($artefato_classes_detail);

	return $artefato_template_result;