<?php

	$query = prepare_query("SELECT id FROM Paginas WHERE etiqueta_id = $pagina_etiqueta_id");
	$paginas_etiqueta = $conn->query($query);
	if ($paginas_etiqueta->num_rows > 0) {
		$template_id = 'paginas_etiqueta';
		$template_titulo = $pagina_translated['Este tópico em páginas'];
		$template_botoes = false;
		$template_conteudo = false;
		while ($pagina_etiqueta = $paginas_etiqueta->fetch_assoc()) {
			$pagina_etiqueta_id = $pagina_etiqueta['id'];
			$template_conteudo .= return_list_item($pagina_etiqueta_id);
		}
		$template_conteudo = list_wrap($template_conteudo);
		include 'templates/page_element.php';
	}