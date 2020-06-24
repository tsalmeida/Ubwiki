<?php
	$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'album_musica' AND estado = 1");
	$pagina_audios = $conn->query($query);
	if ($pagina_audios->num_rows > 0) {
		$template_id = 'audios';
		$template_titulo = $pagina_translated['Áudio'];
		$template_conteudo = false;
		while ($pagina_audio = $pagina_audios->fetch_assoc()) {
			$pagina_audio_id = $pagina_audio['elemento_id'];
			$pagina_audio_pagina_id = return_pagina_id($pagina_audio_id, 'elemento');
			$template_conteudo .= return_list_item($pagina_audio_pagina_id);
		}
		$template_conteudo = list_wrap($template_conteudo);
		include 'templates/page_element.php';
	}

?>