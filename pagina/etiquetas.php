<?php
	
	$etiquetas = $conn->query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	if ($etiquetas->num_rows > 0) {
		$template_id = 'secao_etiquetas';
		$template_titulo = 'Áreas de interesse relacionadas';
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "<ul class='list-group list-group-flush rounded'>";
		while ($etiqueta = $etiquetas->fetch_assoc()) {
			$etiqueta_id = $etiqueta['extra'];
			$etiqueta_info = return_etiqueta_info($etiqueta_id);
			$etiqueta_titulo = $etiqueta_info[2];
			$etiqueta_pagina_id = $etiqueta_info[4];
			$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
			$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
			$etiqueta_pagina_estado_icone = return_estado_icone($etiqueta_pagina_estado, 'curso');
			$template_conteudo .= "<a href='pagina.php?etiqueta_id=$etiqueta_id'><li class='list-group-item border-top list-group-item-action d-flex justify-content-between mt-1'><span><span class='text-warning'><i class='fad fa-tag fa-fw'></i></span> $etiqueta_titulo</span><span><i class='fad $etiqueta_pagina_estado_icone fa-fw'></i></span></li></a>";
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}
	
	?>
