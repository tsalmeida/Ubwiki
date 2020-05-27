<?php
	$pagina_elementos = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'album_musica' AND estado = 1");
	if ($pagina_elementos->num_rows > 0) {
		$template_id = 'audios';
		$template_titulo = $pagina_translated['Áudio'];
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