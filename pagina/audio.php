<?php
	
	$audios = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'album_musica' AND estado = 1");
	if ($audios->num_rows > 0) {
		$template_id = 'material_audio';
		$template_titulo = 'Áudio';
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "
								<ul class='list-group list-group-flush'>
							";
		while ($audio = $audios->fetch_assoc()) {
			$audio_elemento_id = $audio['elemento_id'];
			$audio_elemento_info = return_elemento_info($audio_elemento_id);
			$audio_elemento_titulo = $audio_elemento_info[4];
			$audio_elemento_autor = $audio_elemento_info[5];
			$template_conteudo .= "
						    	  <a href='pagina.php?elemento_id=$audio_elemento_id' target='_blank'><li class='list-group-item list-group-item-action mt-1 border-top'>$audio_elemento_titulo / $audio_elemento_autor</li></a>
						    	";
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}
	
	?>
