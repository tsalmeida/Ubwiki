<?php
	$result = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'referencia' AND estado = 1");
	if ($result->num_rows > 0) {
		$template_id = 'leia_mais';
		$template_titulo = $pagina_translated['Leia mais'];
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "<ul class='list-group list-group-flush rounded'>";
		while ($row = $result->fetch_assoc()) {
			$elemento_id = $row['elemento_id'];
			$result2 = $conn->query("SELECT titulo, autor, capitulo, estado FROM Elementos WHERE id = $elemento_id");
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					$referencia_titulo = $row['titulo'];
					$referencia_autor = $row['autor'];
					$referencia_capitulo = $row['capitulo'];
					$referencia_estado = $row['estado'];
					if ($referencia_estado == false) {
						continue;
					}
					if ($referencia_capitulo == false) {
						$template_conteudo .= "<a href='pagina.php?elemento_id=$elemento_id' target='_blank'><li class='list-group-item list-group-item-action mt-1 border-top'>$referencia_titulo / $referencia_autor</li></a>";
					} else {
						$template_conteudo .= "<a href='pagina.php?elemento_id=$elemento_id' target='_blank'><li class='list-group-item list-group-item-action mt-1 border-top'>$referencia_titulo / $referencia_autor // $referencia_capitulo</li></a>";
					}
				}
			}
		}
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}
	
	?>