<?php
	$fa_secondary_color_anotacao = '#2196f3';
	$fa_icone_anotacao = 'fa-file-alt';
	$fa_secondary_color_imagem = "#ff5722";
	$fa_primary_color_imagem = "#ffab91";
	$fa_icone_imagem = 'fa-file-image';
	
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
	} elseif ($artefato_tipo == 'imagem_publica') {
		$fa_icone = $fa_icone_imagem;
		$fa_primary_color = $fa_primary_color_imagem;
		$fa_secondary_color = $fa_secondary_color_imagem;
	}
	
	if (!isset($artefato_page_id_titulo)) {
		$artefato_page_id_titulo = false;
	}
	
	echo "
     <div class='col-lg-2 col-md-3 col-sm-4 px-3 pb-5 artefato' title='Criado em: $artefato_criacao'>
        <a href='$artefato_link' target='_blank'><span class='row justify-content-center text-center'><i class='fad $fa_icone fa-6x fa-fw fa-swap-opacity d-block' style='--fa-primary-color: $fa_primary_color; --fa-secondary-color: $fa_secondary_color; --fa-secondary-opacity:  1.0'></i></span></a>
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
?>