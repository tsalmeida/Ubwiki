<?php
	
	$paginas_etiqueta = $conn->query("SELECT id FROM Paginas WHERE etiqueta_id = $pagina_etiqueta_id");
	if ($paginas_etiqueta->num_rows > 0) {
		$template_id = 'paginas_etiqueta';
		$template_titulo = $pagina_translated['Este tópico em páginas'];
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($pagina_etiqueta = $paginas_etiqueta->fetch_assoc()) {
			$pagina_etiqueta_id = $pagina_etiqueta['id'];
			$pagina_etiqueta_info = return_pagina_info($pagina_etiqueta_id);
			$pagina_etiqueta_tipo = $pagina_etiqueta_info[2]; // preciso terminar isso, para diferenciar os diversos tipos de página e como aparecerão na lista.
			$pagina_etiqueta_titulo = $pagina_etiqueta_info[6];
			$pagina_etiqueta_estado = $pagina_etiqueta_info[3];
			$pagina_etiqueta_estado_icone = return_estado_icone($pagina_etiqueta_estado, 'curso');
			$pagina_familia_info = return_familia($pagina_etiqueta_id);
			$pagina_etiqueta_curso_pagina_id = $pagina_familia_info[1];
			$pagina_etiqueta_curso_pagina_titulo = return_pagina_titulo($pagina_etiqueta_curso_pagina_id);
			$template_conteudo .= "<a href='pagina.php?pagina_id=$pagina_etiqueta_id'><li class='list-group-item list-group-item-action list-group-item-warning mt-1 border-top d-flex justify-content-between'><span>$pagina_etiqueta_curso_pagina_titulo: $pagina_etiqueta_titulo</span><span><i class='$pagina_etiqueta_estado_icone'></i></span></li></a>";
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}