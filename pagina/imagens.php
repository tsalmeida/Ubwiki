<?php
	$imagens = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'imagem' AND estado = 1");
	$count = 0;
	if ($imagens->num_rows > 0) {
		$template_id = 'imagens';
		$template_titulo = 'Imagens';
		$template_botoes = false;
		$template_conteudo = false;
		$active = 'active';
		while ($imagem = $imagens->fetch_assoc()) {
			$elemento_id = $imagem['elemento_id'];
			$elementos = $conn->query("SELECT titulo, arquivo, estado FROM Elementos WHERE id = $elemento_id");
			if ($elementos->num_rows > 0) {
				while ($elemento = $elementos->fetch_assoc()) {
					$imagem_titulo = $elemento['titulo'];
					$imagem_arquivo = $elemento['arquivo'];
					$imagem_estado = $elemento['estado'];
					if ($imagem_estado == false) {
						continue;
					} else {
						$count++;
					}
					if ($count == 1) {
						$template_conteudo .= "
                                                <div id='carousel-imagens' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                                <div class='carousel-inner' role='listbox'>
                                            ";
					}
					$template_conteudo .= "
                                            <div class=' carousel-item $active text-center'>
                                              <figure class='col-12'>
                                                <a href='pagina.php?elemento_id=$elemento_id' target='_blank'>
                                                  <img src='/../imagens/verbetes/thumbnails/$imagem_arquivo'
                                                    class='img-fluid' style='height:300px'>
                                                </a>
                                        ";
					$template_conteudo .= "<figcaption>
                                           <strong class='h5-responsive mt-2'>$imagem_titulo</strong>";
					$template_conteudo .= "</figcaption>";
					$template_conteudo .= "</figure>";
					$template_conteudo .= "</div>";
					$active = false;
					break;
				}
			}
		}
		if ($count != 0) {
			$template_conteudo .= "</div>";
		}
		if ($count != 1) {
			$template_conteudo .= "
                                      <div class='controls-top'>
                                        <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                        <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                                      </div>
                                ";
		}
		if ($count != 0) {
			$template_conteudo .= "</div>";
		}
		include 'templates/page_element.php';
	}

	?>