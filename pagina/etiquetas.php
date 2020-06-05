<?php
	$template_modal_div_id = 'modal_secao_etiquetas';
	$template_modal_titulo = $pagina_translated['freepages'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<ul class='list-group list-group-flush rounded'>";
	$carregar_areas_relacionadas = false;
	if (($pagina_etiqueta_id != false) && ($pagina_subtipo != 'etiqueta')) {
		$pagina_etiqueta_info = return_etiqueta_info($pagina_etiqueta_id);
		$pagina_etiqueta_tipo = $pagina_etiqueta_info[1];
		if (($pagina_etiqueta_tipo != 'referencia') && ($pagina_etiqueta_tipo != 'secao')) {
			$carregar_areas_relacionadas = true;
			$pagina_etiqueta_pagina_id = $pagina_etiqueta_info[4];
			$template_modal_body_conteudo .= return_list_item($pagina_etiqueta_pagina_id);
			/*
			$pagina_etiqueta_titulo = $pagina_etiqueta_info[2];
			$pagina_etiqueta_pagina_info = return_pagina_info($pagina_etiqueta_pagina_id);
			$pagina_etiqueta_pagina_estado = $pagina_etiqueta_pagina_info[3];
			$pagina_etiqueta_pagina_estado_icone = return_estado_icone($pagina_etiqueta_pagina_estado, 'curso');
			$template_modal_body_conteudo .= "<a href='pagina.php?etiqueta_id=$pagina_etiqueta_id'><li class='list-group-item border-top list-group-item-action d-flex justify-content-between mt-1 list-group-item-warning'><span><span class='text-warning'><i class='fad fa-tag fa-fw'></i><span></span></span> $pagina_etiqueta_titulo</span><span><i class='fad $pagina_etiqueta_pagina_estado_icone fa-fw'></i></span></li></a>";*/
		}
	}

	$query = prepare_query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	$etiquetas = $conn->query($query);
	if ($etiquetas->num_rows > 0) {
		while ($etiqueta = $etiquetas->fetch_assoc()) {
			$carregar_areas_relacionadas = true;
			$etiqueta_id = $etiqueta['extra'];
			if ($etiqueta_id == false) {
				continue;
			}
			$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
			$template_modal_body_conteudo .= return_list_item($etiqueta_pagina_id);
			/*
			$etiqueta_titulo = $etiqueta_info[2];
			$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
			$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
			$etiqueta_pagina_estado_icone = return_estado_icone($etiqueta_pagina_estado, 'curso');
			$template_modal_body_conteudo .= "<a href='pagina.php?etiqueta_id=$etiqueta_id'><li class='list-group-item border-top list-group-item-action d-flex justify-content-between mt-1'><span><span class='text-warning'><i class='fad fa-tag fa-fw'></i></span> $etiqueta_titulo</span><span><i class='fad $etiqueta_pagina_estado_icone fa-fw'></i></span></li></a>";*/
		}
	}
	$template_modal_body_conteudo .= "<span data-toggle='modal' data-target='#modal_secao_etiquetas'>";
	$template_modal_body_conteudo .= put_together_list_item('modal', '#modal_gerenciar_etiquetas', false, 'fad', 'fa-tags', $pagina_translated['Adicionar etiqueta'], false, false, 'list-group-item-warning', false);
	$template_modal_body_conteudo .= "</span>";
	$template_modal_body_conteudo .= "</ul>";
	
	include 'templates/modal.php';
?>
