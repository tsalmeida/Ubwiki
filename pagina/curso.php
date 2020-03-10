<?php
	
	$paginas = $conn->query("SELECT DISTINCT pagina_id FROM (SELECT pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND curso_id = $pagina_curso_id AND pagina_tipo = 'topico' GROUP BY id ORDER BY id DESC) t");
	if ($paginas->num_rows > 0) {
		$template_id = 'paginas_recentes';
		$template_titulo = $pagina_translated['Verbetes recentemente modificados'];
		$template_botoes = false;
		$template_conteudo = false;
		$template_conteudo_no_col = false;
		$template_conteudo .= "<ul class='list-group list-group-flush paginas_recentes_collapse collapse show'>";
		$count = 0;
		while ($pagina = $paginas->fetch_assoc()) {
			$topico_pagina_id = $pagina['pagina_id'];
			$topico_titulo = return_pagina_titulo($topico_pagina_id);
			$topico_familia = return_familia($topico_pagina_id);
			$topico_materia_pagina_id = $topico_familia[2];
			$topico_materia_pagina_titulo = return_pagina_titulo($topico_materia_pagina_id);
			$topicos = $conn->query("SELECT estado FROM Paginas WHERE id = $topico_pagina_id");
			if ($count == 10) {
				break;
			}
			if ($topicos->num_rows > 0) {
				while ($topico = $topicos->fetch_assoc()) {
					$topico_estado_pagina = $topico['estado'];
					$icone_estado = return_estado_icone($topico_estado_pagina, 'materia');
					$template_conteudo .= "
                      <a href='pagina.php?pagina_id=$topico_pagina_id'>
                          <li class='list-group-item list-group-item-action d-flex justify-content-between mt-1 border-top'>
                              <span class='mr-5'>
                                  <strong>$topico_materia_pagina_titulo: </strong>
                                  $topico_titulo
                              </span>
                              <span class='text-muted'><i class='fad $icone_estado fa-fw'></i></span>
                          </li>
                      </a>
          ";
					$count++;
					break;
				}
			}
		}
		unset($topico_pagina_id);
		$template_conteudo .= "</ul>";
		include 'templates/page_element.php';
	}
?>