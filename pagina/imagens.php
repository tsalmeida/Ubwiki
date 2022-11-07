<?php

	$query = prepare_query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'imagem' AND estado = 1");
	$imagens = $conn->query($query);
	$imagens_count = $imagens->num_rows;
	if ($imagens_count == 0) {
		return;
	}

	$template_id = 'imagens';
	$template_titulo = $pagina_translated['Imagens'];
	$template_botoes = false;
	$template_conteudo = false;
	$template_conteudo .= "
	<div id=\"carousel-imagens\" class=\"carousel slide\" data-bs-ride=\"carousel\">
	  <div class=\"carousel-inner\" align='center'>";
	$active = "active";
	while ($imagem = $imagens->fetch_assoc()) {
		$elemento_id = $imagem['elemento_id'];
		$elemento_info = return_elemento_info($elemento_id);
		$template_conteudo .= "
		
			<div class='carousel-item $active bg-light border rounded'>
				<a href='pagina.php?pagina_id={$elemento_info[19]}' target='_blank'>
					<img src='/../imagens/verbetes/thumbnails/{$elemento_info[11]}' class='mx-auto'>
				</a>
				<div class='carousel-caption d-none d-md-block link-white rounded transparent-background p-1 mt-5'>
					<h5 class='m-0'>{$elemento_info[4]}</h5>
				</div>
			
			</div>
		";
		$active = false;
	}
	$template_conteudo .= "</div>";
	$template_conteudo .= "
		<button class=\"carousel-control-prev transparent-background\" type=\"button\" data-bs-target='#carousel-imagens' data-bs-slide='prev'>
			<span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
			<span class=\"visually-hidden\">Previous</span>
		</button>
		<button class=\"carousel-control-next transparent-background\" type=\"button\" data-bs-target='#carousel-imagens' data-bs-slide='next'>
			<span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
			<span class=\"visually-hidden\">Next</span>
		</button>
	";
	$template_conteudo .= "</div>";

	include 'templates/page_element.php';

	return;

	$count = 0;
	if ($imagens->num_rows > 0) {
		$template_id = 'imagens';
		$template_titulo = $pagina_translated['Imagens'];
		$template_botoes = false;
		$template_conteudo = false;
		$active = 'active';
		if ($pagina_subtipo == 'produto') {
			$imagem_opcoes = array();
		}
		while ($imagem = $imagens->fetch_assoc()) {
			$elemento_id = $imagem['elemento_id'];
			$query = prepare_query("SELECT titulo, arquivo, estado, compartilhamento, user_id, id FROM Elementos WHERE id = $elemento_id");
			$elementos = $conn->query($query);
			if ($elementos->num_rows > 0) {
				while ($elemento = $elementos->fetch_assoc()) {
					$imagem_elemento_id = $elemento['id'];
					$imagem_titulo = $elemento['titulo'];
					$imagem_arquivo = $elemento['arquivo'];
					$imagem_estado = $elemento['estado'];
					$imagem_compartilhamento = $elemento['compartilhamento'];
					$imagem_user_id = $elemento['user_id'];
					if ($imagem_compartilhamento == 'privado') {
						if ($imagem_user_id != $user_id) {
							continue;
						}
					}
					if ($imagem_estado == false) {
						continue;
					} else {
						$count++;
					}
					if (isset($imagem_opcoes)) {
						$imagem_opcao = array($imagem_elemento_id, $imagem_titulo);
						array_push($imagem_opcoes, $imagem_opcao);
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
                                           <span class='fst-italic text-muted'>$imagem_titulo</span>";
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
                                        <a class='btn btn-floating bg-secondary z-depth-0' href='#carousel-imagens' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                        <a class='btn btn-floating bg-secondary z-depth-0' href='#carousel-imagens' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                                      </div>
                                ";
		}
		if ($count != 0) {
			$template_conteudo .= "</div>";
		}
		include 'templates/page_element.php';
	}

?>