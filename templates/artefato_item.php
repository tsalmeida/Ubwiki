<?php
	$fa_secondary_color_anotacao = '#2196f3';
	$fa_icone_anotacao = 'fa-file-alt';
	$fa_secondary_color_imagem = "#ff5722";
	$fa_primary_color_imagem = "#ffab91";
	$fa_icone_imagem = 'fa-file-image';
	$fa_secondary_opacity = "--fa-secondary-opacity:  1.0";
	if (!isset($fa_class)) {
		$fa_class = false;
	}
	if (!isset($artefato_template_thumb)) {
		$artefato_template_thumb = false;
	}
	if (!isset($artefato_icone)) {
		$artefato_icone = false;
	}
	
	if ($artefato_tipo == 'anotacao_topico') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#ffe082';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'anotacao_materia') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#ffe082';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'anotacoes_elemento') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#a5d6a7';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'anotacao_curso') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#b0bec5';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'simulado') {
		$fa_icone = 'fa-file-check';
		$fa_primary_color = '#b39ddb'; // purple
		$fa_secondary_color = '#673ab7';
	} elseif (($artefato_tipo == 'anotacao_prova') || ($artefato_tipo == 'anotacao_texto_apoio') || ($artefato_tipo == 'anotacao_questao')) {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#b39ddb'; // purple
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'imagem_publica') {
		$fa_icone = $fa_icone_imagem;
		$fa_primary_color = $fa_primary_color_imagem; // red
		$fa_secondary_color = $fa_secondary_color_imagem;
	} elseif ($artefato_tipo == 'nova_anotacao') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#90caf9'; // blue
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'nova_imagem') {
		$fa_icone = 'fa-file-plus'; // red
		$fa_primary_color = $fa_primary_color_imagem;
		$fa_secondary_color = $fa_secondary_color_imagem;
	} elseif ($artefato_tipo == 'anotacao_privada') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#90caf9'; // blue
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'novo_topico') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#ffe082';
		$fa_secondary_color = '#ffc107';
	} elseif ($artefato_tipo == 'nova_referencia') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#a5d6a7'; // green
		$fa_secondary_color = '#4caf50';
	} elseif ($artefato_tipo == 'nova_materia') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#ffe082';
		$fa_secondary_color = '#ffc107';
	} elseif ($artefato_tipo == 'novo_curso') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#b0bec5';
		$fa_secondary_color = '#607d8b';
	} elseif ($artefato_tipo == 'novo_simulado') {
		$fa_icone = 'fa-file-plus';
		$fa_primary_color = '#b39ddb';
		$fa_secondary_color = '#673ab7';
	}
	
	if ($artefato_icone != false) {
		$fa_icone = $artefato_icone;
	}
	
	if (!isset($artefato_page_id_titulo)) {
		$artefato_page_id_titulo = false;
	}
	
	if (isset($artefato_estado)) {
		if ($artefato_estado == false) {
			$fa_secondary_opacity = false;
		}
	}
	
	$artefato_link_1 = false;
	$artefato_link_2 = false;
	if ($artefato_link != false) {
		$artefato_link_1 = "<a href='$artefato_link' target='_blank' class='$fa_class'>";
		$artefato_link_2 = "</a>";
	} else {
		$artefato_link_1 = "<span id='novo_$artefato_tipo' data-toggle='modal' data-target='#modal_$artefato_tipo'><a href='javascript:void(0);'>";
		$artefato_link_2 = "</a></span>";
	}
	
	$artefato_template_result = false;
	$artefato_template_result .= "
     <div class='col-lg-2 col-md-3 col-sm-4 col-xs-12 py-3 artefato' title='$artefato_criacao' $artefato_template_thumb>
        <span class='row justify-content-center text-center'>$artefato_link_1<i class='fad $fa_icone fa-6x fa-fw d-block' style='--fa-primary-color: $fa_primary_color; --fa-secondary-color: $fa_secondary_color; $fa_secondary_opacity'></i>$artefato_link_2</span>
        <span class='row justify-content-center text-center mt-2'>$artefato_titulo</span>
        <span class='row justify-content-center text-center text-muted'>$artefato_page_id_titulo</span>
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
	
	return $artefato_template_result;

?>