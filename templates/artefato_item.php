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
	
	if ($artefato_tipo == 'anotacao_topico') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#90caf9';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'anotacao_materia') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#a5d6a7';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'anotacao_curso') {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#ffcc80';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'simulado') {
		$fa_icone = 'fa-file-check';
		$fa_primary_color = '#b39ddb';
		$fa_secondary_color = '#673ab7';
	} elseif (($artefato_tipo == 'anotacao_prova') || ($artefato_tipo == 'anotacao_texto_apoio') || ($artefato_tipo == 'anotacao_questao')) {
		$fa_icone = $fa_icone_anotacao;
		$fa_primary_color = '#b39ddb';
		$fa_secondary_color = $fa_secondary_color_anotacao;
	} elseif ($artefato_tipo == 'imagem_publica') {
		//$artefato_template_thumb = "style='background-image: url(../imagens/verbetes/thumbnails/$artefato_imagem_arquivo);";
		$fa_icone = $fa_icone_imagem;
		$fa_primary_color = $fa_primary_color_imagem;
		$fa_secondary_color = $fa_secondary_color_imagem;
	}

	if (!isset($artefato_page_id_titulo)) {
		$artefato_page_id_titulo = false;
	}
	
	if (isset($artefato_estado)) {
		if ($artefato_estado == false) {
			$fa_secondary_opacity = false;
		}
	}

	$artefato_template_result = false;
	$artefato_template_result .= "
     <div class='col-lg-2 col-md-3 col-sm-4 col-xs-12 py-3 artefato' title='Criado em: $artefato_criacao' $artefato_template_thumb>
        <span class='row justify-content-center text-center'><a href='$artefato_link' target='_blank' class='$fa_class'><i class='fad $fa_icone fa-6x fa-fw d-block' style='--fa-primary-color: $fa_primary_color; --fa-secondary-color: $fa_secondary_color; $fa_secondary_opacity'></i></a></span>
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

	return $artefato_template_result;

?>