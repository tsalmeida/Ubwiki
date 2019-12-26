<?php
	$template_div = 'partes_elemento';
	$template_titulo = 'Seções';
	$template_botoes = "
                            	<a data-toggle='modal' data-target='#modal_partes_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
                            ";
	$template_conteudo = false;
	$secoes = $conn->query("SELECT secao_pagina_id FROM Secoes WHERE pagina_id = $pagina_id ORDER BY ordem");
	if ($secoes->num_rows > 0) {
		$template_conteudo .= "<p>Seções identificadas desta página:</p>";
		$template_conteudo .= "<ul class='list-group'>";
		while ($secao = $secoes->fetch_assoc()) {
			$secao_pagina_id = $secao['secao_pagina_id'];
			$secao_pagina_info = return_pagina_info($secao_pagina_id);
			$secao_pagina_estado = $secao_pagina_info[3];
			$secao_pagina_titulo = $secao_pagina_info[6];
			$secao_estado_icone = return_estado_icone($secao_pagina_estado, 'elemento');
			$template_conteudo .=
				"
		      <a href='pagina.php?pagina_id=$secao_pagina_id' target='_blank' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
	              $secao_pagina_titulo
			      <span class='ml-3 badge grey lighten-3 text-dark badge-pill z-depth-0'>
	                                <i class='fa $secao_estado_icone'></i>
	                            </span>
	          </a>
        ";
		}
		$template_conteudo .= "</ul>";
	} else {
		$template_conteudo .= "<p>Não há seções identificadas desta página.</p>";
	}
	
	include 'templates/page_element.php';
	?>