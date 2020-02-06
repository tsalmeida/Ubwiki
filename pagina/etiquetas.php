<?php
	
	$template_id = 'secao_etiquetas';
	$template_titulo = 'Páginas livres relacionadas';
	$template_botoes = false;
	$template_conteudo = false;
	$template_conteudo .= "<ul class='list-group list-group-flush rounded'>";
	$carregar_areas_relacionadas = false;
	//if (($pagina_etiqueta_id != false) && ($pagina_subtipo != 'etiqueta') && ($pagina_tipo != 'curso') && ($pagina_tipo != 'materia') && ($pagina_tipo != 'topico')) {
	if (($pagina_etiqueta_id != false) && ($pagina_subtipo != 'etiqueta')) {

		$carregar_areas_relacionadas = true;
		$pagina_etiqueta_info = return_etiqueta_info($pagina_etiqueta_id);
		$pagina_etiqueta_titulo = $pagina_etiqueta_info[2];
		$pagina_etiqueta_pagina_id = $pagina_etiqueta_info[4];
		$pagina_etiqueta_pagina_info = return_pagina_info($pagina_etiqueta_pagina_id);
		$pagina_etiqueta_pagina_estado = $pagina_etiqueta_pagina_info[3];
		$pagina_etiqueta_pagina_estado_icone = return_estado_icone($pagina_etiqueta_pagina_estado, 'curso');
		$template_conteudo .= "<a href='pagina.php?etiqueta_id=$pagina_etiqueta_id'><li class='list-group-item border-top list-group-item-action d-flex justify-content-between mt-1 list-group-item-warning'><span><span class='text-warning'><i class='fad fa-tag fa-fw'></i><span></span></span> $pagina_etiqueta_titulo</span><span><i class='fad $pagina_etiqueta_pagina_estado_icone fa-fw'></i></span></li></a>";
	}
	
	$etiquetas = $conn->query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	if ($etiquetas->num_rows > 0) {
		while ($etiqueta = $etiquetas->fetch_assoc()) {
			$carregar_areas_relacionadas = true;
			$etiqueta_id = $etiqueta['extra'];
			$etiqueta_info = return_etiqueta_info($etiqueta_id);
			$etiqueta_titulo = $etiqueta_info[2];
			$etiqueta_pagina_id = $etiqueta_info[4];
			$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
			$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
			$etiqueta_pagina_estado_icone = return_estado_icone($etiqueta_pagina_estado, 'curso');
			$template_conteudo .= "<a href='pagina.php?etiqueta_id=$etiqueta_id'><li class='list-group-item border-top list-group-item-action d-flex justify-content-between mt-1'><span><span class='text-warning'><i class='fad fa-tag fa-fw'></i></span> $etiqueta_titulo</span><span><i class='fad $etiqueta_pagina_estado_icone fa-fw'></i></span></li></a>";
		}
	}
	$template_conteudo .= "</ul>";
	if ($carregar_areas_relacionadas == true) {
		include 'templates/page_element.php';
	}
?>
