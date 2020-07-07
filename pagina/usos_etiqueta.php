<?php

	$query = prepare_query("SELECT pagina_id, pagina_tipo FROM Paginas_elementos WHERE tipo = 'topico' AND extra = $pagina_item_id AND pagina_tipo <> 'escritorio'");
	$usos_etiqueta = $conn->query($query);
	if ($usos_etiqueta->num_rows > 0) {
		$template_id = 'usos_etiqueta';
		$template_titulo = $pagina_translated['Páginas relacionadas'];
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($uso_etiqueta = $usos_etiqueta->fetch_assoc()) {
			$uso_etiqueta_pagina_id = $uso_etiqueta['pagina_id'];
			$uso_etiqueta_pagina_tipo = $uso_etiqueta['pagina_tipo'];
			$uso_etiqueta_pagina_info = return_pagina_info($uso_etiqueta_pagina_id, true);
			$uso_etiqueta_pagina_titulo = $uso_etiqueta_pagina_info[6];
			$uso_etiqueta_pagina_estado = $uso_etiqueta_pagina_info[3];
			$uso_etiqueta_pagina_estado_icone = return_estado_icone($uso_etiqueta_pagina_estado);
			$uso_etiqueta_li_cor = false;
			if ($uso_etiqueta_pagina_tipo == 'topico') {
				$uso_etiqueta_li_cor = 'list-group-item-warning';
				$uso_etiqueta_familia_info = return_familia($uso_etiqueta_pagina_id);
				$uso_etiqueta_curso_pagina_id = $uso_etiqueta_familia_info[1];
				$uso_etiqueta_curso_pagina_titulo = return_pagina_titulo($uso_etiqueta_curso_pagina_id);
				$uso_etiqueta_pagina_titulo = "$uso_etiqueta_curso_pagina_titulo: $uso_etiqueta_pagina_titulo";
			} elseif ($uso_etiqueta_pagina_tipo == 'pagina') {
				$uso_etiqueta_li_cor = 'list-group-item-info';
			}
			$template_conteudo .= "<a href='pagina.php?pagina_id=$uso_etiqueta_pagina_id'><li class='list-group-item list-group-item-action $uso_etiqueta_li_cor border-top mt-1 d-flex justify-content-between'><span>$uso_etiqueta_pagina_titulo</span><span><i class='fad {$uso_etiqueta_pagina_estado_icone[0]} fa-fw'></i></span></li></a>";
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}

?>
