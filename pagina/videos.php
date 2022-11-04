<?php
	$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'video' AND estado = 1");
	$videos = $conn->query($query);
	$count = 0;
	if ($videos->num_rows > 0) {
		$template_id = 'videos';
		$template_titulo = $pagina_translated['Vídeos'];
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo .= "
                          <div id='carousel-videos' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                          <div class='carousel-inner' role='listbox'>
                          ";
		$active = 'active';
		while ($video = $videos->fetch_assoc()) {
			$elemento_id = $video['elemento_id'];
			$query = prepare_query("SELECT titulo, autor, arquivo FROM Elementos WHERE id = $elemento_id");
			$elementos = $conn->query($query);
			if ($elementos->num_rows > 0) {
				while ($elemento = $elementos->fetch_assoc()) {
					$count++;
					$video_titulo = $elemento['titulo'];
					$video_autor = $elemento['autor'];
					$video_arquivo = $elemento['arquivo'];
					$template_conteudo .= "
                                <div class=' carousel-item $active text-center'>
                                  <figure class='col-12'>
                                    <a href='pagina.php?elemento_id=$elemento_id' target='_blank'>
                                      <img src='/../imagens/youthumb/$video_arquivo'
                                        class='img-fluid' style='height:300px'>
                                    </a>
                                ";
					$template_conteudo .= "
                                    <figcaption>
                                   <strong class='h5-responsive mt-2'>$video_titulo</strong>
                                ";
					$template_conteudo .= "<p>$video_autor</p>";
					$template_conteudo .= "</figcaption>";
					$template_conteudo .= "</figure>
                                </div>
                                ";
					$active = false;
					break;
				}
			}
		}
		if ($count != 1) {
			$template_conteudo .= "
                            </div>
                              <div class='controls-top'>
                                <a class='btn btn-floating bg-secondary z-depth-0' href='#carousel-videos' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                <a class='btn btn-floating bg-secondary z-depth-0' href='#carousel-videos' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                            ";
		}
		$template_conteudo .= "</div></div>";
		include 'templates/page_element.php';
	}
	
	?>
