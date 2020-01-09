<?php
	// VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS
	$topico_anterior = false;
	$topico_proximo = false;
	$breadcrumbs = false;
	
	$familia_info = return_familia($pagina_id);
	
	$topico_nivel = $familia_info[0];
	$topico_curso_id = $familia_info[1];
	$topico_curso_titulo = return_pagina_titulo($topico_curso_id);
	$topico_materia_id = $familia_info[2];
	$topico_materia_titulo = return_pagina_titulo($topico_materia_id);
	
	$breadcrumbs .= "<h4><strong>Curso:</strong> <a href='pagina.php?pagina_id=$topico_curso_id'>$topico_curso_titulo</a></h4>";
	$breadcrumbs .= "<h4><strong>Matéria:</strong> <a href='pagina.php?pagina_id=$topico_materia_id'>$topico_materia_titulo</a></h4>";
	
	$breadcrumbs .= "<h4>Tópicos:</h4>";
	
	if ($topico_nivel == 1) {
		/*$breadcrumbs .= "<p><span class='spacing1'>$pagina_titulo</span></p>";*/
	}
	if ($topico_nivel > 1) {
		$topico_nivel1_titulo = return_pagina_titulo($familia_info[3]);
		$breadcrumbs .= "<p><a href='pagina.php?pagina_id=$familia_info[3]'><span class=''>$topico_nivel1_titulo</span></a></p>";
	}
	if ($topico_nivel == 2) {
		$breadcrumbs .= "<p><span class='spacing1'>$pagina_titulo</span></p>";
	}
	if ($topico_nivel > 2) {
		$topico_nivel2_titulo = return_pagina_titulo($familia_info[4]);
		$breadcrumbs .= "<p class='spacing1'><a href='pagina.php?pagina_id=$familia_info[4]'><span class=''>$topico_nivel2_titulo</span></a></p>";
	}
	if ($topico_nivel == 3) {
		$breadcrumbs .= "<p class='spacing2'><span class='text-muted'>$pagina_titulo</span></p>";
	}
	if ($topico_nivel > 3) {
		$topico_nivel3_titulo = return_pagina_titulo($familia_info[5]);
		$breadcrumbs .= "<p class='spacing2'><a href='pagina.php?pagina_id=$familia_info[5]'><span class=''>$topico_nivel3_titulo</span></a></p>";
	}
	if ($topico_nivel == 4) {
		$breadcrumbs .= "<p class='spacing3'><span class='text-muted'>$pagina_titulo</span></p>";
	}
	if ($topico_nivel > 4) {
		$topico_nivel4_titulo = return_pagina_titulo($familia_info[6]);
		$breadcrumbs .= "<p class='spacing3'><a href='pagina.php?pagina_id=$familia_info[6]'><span class=''>$topico_nivel4_titulo</span></a></p>";
	}
	if ($topico_nivel == 5) {
		$breadcrumbs .= "<p class='spacing4'><span class='text-muted'>$pagina_titulo</span></p>";
	}
	
	?>
