<?php
	
	$template_id = 'curso_busca';
	$template_titulo = 'Busca';
	$template_botoes = false;
	$template_botoes_padrao = false;
	$template_conteudo = false;
	$template_conteudo .= "
                        <form id='searchform' action='' method='post'>
                        <div id='searchDiv'>
                            <input id='barra_busca' list='searchlist' type='text' class='barra_busca text-muted'
                                   name='searchBar' rows='1' autocomplete='off' spellcheck='false'
                                   placeholder='O que você vai aprender hoje?' required>
                            <datalist id='searchlist'>";
	$result = $conn->query("SELECT chave FROM Searchbar WHERE curso_id = '$curso_id' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$chave = $row['chave'];
			$template_conteudo .= "<option>$chave</option>";
		}
	}
	$template_conteudo .= "
                            </datalist>";
	$template_conteudo .= "<input id='trigger_busca' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
	$template_conteudo .= "
						</div>
            			</form>";
	
	include 'templates/page_element.php';
	
	$template_id = 'materias';
	$template_titulo = 'Matérias';
	$template_conteudo_no_col = true;
	$template_botoes = false;
	$template_botoes_padrao = false;
	$template_conteudo = false;
	$template_conteudo_class = 'justify-content-start';
	
	$row_items = 2;
	$materias = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE tipo = 'materia' AND pagina_id = $pagina_id");
	
	$rowcount = mysqli_num_rows($materias);
	if ($materias->num_rows > 0) {
		while ($materia = $materias->fetch_assoc()) {
			$materia_pagina_id = $materia['elemento_id'];
			$materia_pagina_titulo = return_pagina_titulo($materia_pagina_id);
			$template_conteudo .= "
	                            <span class='col-lg-4 col-md-6'><a href='pagina.php?pagina_id=$materia_pagina_id'><button type='button' class='btn btn-light oswald col-12 grey lighten-3 text-muted rounded materia_hover mx-0 px-0'>$materia_pagina_titulo</button></a></span>
                            ";
		}
		unset($materia_id);
	}
	
	include 'templates/page_element.php';
	
	$template_id = 'paginas_recentes';
	$template_titulo = 'Verbetes recentemente modificados';
	$template_botoes = false;
	$template_conteudo = false;
	$template_conteudo_no_col = true;
	$paginas = $conn->query("SELECT DISTINCT pagina_id FROM (SELECT pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND curso_id = $curso_id AND pagina_tipo = 'topico' GROUP BY id ORDER BY id DESC) t");
	if ($paginas->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
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
                              <span><i class='fad $icone_estado fa-fw'></i></span>
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
	}
	include 'templates/page_element.php';
?>