<?php
	$template_div = 'partes_elemento';
	$template_titulo = 'Seções';
	if ($privilegio_edicao == true) {
		$template_botoes = "<a data-toggle='modal' data-target='#modal_partes_form' href='javascript:void(0);' class='text-success' title='Adicionar seção'><i class='fad fa-plus-square fa-fw'></i></a>";
	}
	$template_conteudo = false;
	if ($secoes->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($secao = $secoes->fetch_assoc()) {
			$secao_pagina_id = $secao['secao_pagina_id'];
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
        ";
		}
		$template_conteudo .= "</ul>";
	} else {
		$template_conteudo .= "<p>Não há seções identificadas desta página.</p>";
	}
	
	include 'templates/page_element.php';
	?>