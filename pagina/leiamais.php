<?php
	$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'referencia' AND estado = 1");
	$pagina_elementos = $conn->query($query);
	if ($pagina_elementos->num_rows > 0) {
		$template_id = 'leia_mais';
		$template_titulo = $pagina_translated['Leia mais'];
		$template_conteudo = false;
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($pagina_elemento = $pagina_elementos->fetch_assoc()) {
			$pagina_elemento_id = $pagina_elemento['elemento_id'];
			$pagina_elemento_pagina_id = return_pagina_id($pagina_elemento_id, 'elemento');
			$template_conteudo .= return_list_item($pagina_elemento_pagina_id);
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}

	?>