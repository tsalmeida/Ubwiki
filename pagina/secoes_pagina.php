<?php

//APARENTEMENTE ESSE ARQUIVO NÃO ESTÁ MAIS SENDO USADO

	$imprimir_secoes = false;
	$template_id = 'partes_elemento';
	if ($privilegio_edicao == true) {
		$template_botoes = "<a data-toggle='modal' data-target='#modal_partes_form' href='javascript:void(0);' class='text-default' title='{}'><i class='fad fa-plus-square fa-fw'></i></a>";
	}
	$template_conteudo = false;
	if ($secoes->num_rows > 0) {
		$imprimir_secoes = true;
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($secao = $secoes->fetch_assoc()) {
			$secao_pagina_id = $secao['secao_pagina_id'];
			$template_conteudo .= return_list_item($secao_pagina_id);
			/*
			$secao_pagina_info = return_pagina_info($secao_pagina_id);
			$secao_pagina_estado = $secao_pagina_info[3];
			$secao_pagina_titulo = $secao_pagina_info[6];
			$secao_estado_icone = return_estado_icone($secao_pagina_estado, 'elemento');
			$template_conteudo .=
				"
		      <a href='pagina.php?pagina_id=$secao_pagina_id'>
		      	<li class='list-group-item list-group-item-action mt-1 border-top d-flex justify-content-between align-items-center'>
		      	<span>$secao_pagina_titulo</span>
	          <span><i class='fad $secao_estado_icone'></i>
	          </li></span>
          </a>
        ";*/
		}
		$template_conteudo .= "</ul>";
	} else {
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$template_conteudo .= "<p>{$pagina_translated['Não há episódios registrados deste podcast.']}</p>";
			} elseif ($elemento_subtipo == 'livro') {
				$template_conteudo .= "<p>{$pagina_translated['Não há capítulos registrados deste livro.']}</p>";
			} else {
				$template_conteudo .= "<p>{$pagina_translated['Não há seções identificadas deste item.']}</p>";
			}
		} else {
			$template_conteudo .= "<p>{$pagina_translated['Não há seções identificadas desta página.']}</p>";
		}
	}
	if ($imprimir_secoes == true) {
		include 'templates/page_element.php';
	} else {
		unset($template_botoes);
	}
?>