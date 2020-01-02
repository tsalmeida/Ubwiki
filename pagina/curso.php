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
	
	$row_items = 2;
	$materias = $conn->query("SELECT titulo, id FROM Materias WHERE curso_id = '$curso_id' AND estado = 1 ORDER BY ordem");
	$rowcount = mysqli_num_rows($materias);
	if ($rowcount > 8) {
		$row_items = 3;
	} elseif ($rowcount > 4) {
		$row_items = 2;
	} elseif ($rowcount < 5) {
		$row_items = 1;
	}
	if ($materias->num_rows > 0) {
		$count = 0;
		$count2 = 0;
		$count3 = 0;
		while ($materia = $materias->fetch_assoc()) {
			if ($count == 0) {
				$count2++;
				$template_conteudo .= "<div class='col-xl col-lg-6 col-md-6 col-sm-12'>";
			}
			$count++;
			$materia_titulo = $materia['titulo'];
			$materia_id = $materia["id"];
			$template_conteudo .= "
                                          <a href='pagina.php?materia_id=$materia_id'><div class='btn btn-block btn-light rounded oswald btn-md text-center grey lighten-3 text-muted mb-3'>
                                            <span class=''>$materia_titulo</span>
                                          </div></a>
                                        ";
			if ($count == $row_items) {
				$count3++;
				$template_conteudo .= "</div>";
				$count = 0;
			}
		}
		if ($count2 != $count3) {
			$template_conteudo .= "</div>";
		}
		unset($materia_id);
	}
	
	include 'templates/page_element.php';
	
	$template_id = 'paginas_recentes';
	$template_titulo = 'Verbetes recentemente modificados';
	$template_botoes = false;
	$template_conteudo = false;
	$template_conteudo_no_col = true;
	$paginas = $conn->query("SELECT DISTINCT page_id FROM (SELECT id, page_id FROM Textos_arquivo WHERE tipo = 'verbete' AND page_id IS NOT NULL GROUP BY id ORDER BY id DESC) t");
	if ($paginas->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group rounded'>";
		$count = 0;
		while ($pagina = $paginas->fetch_assoc()) {
			$topico_id = $pagina['page_id'];
			$topico_materia_id = return_materia_id_topico($topico_id);
			$topico_materia_titulo = return_materia_titulo_id($topico_materia_id);
			$topico_titulo = return_titulo_topico($topico_id);
			$topicos = $conn->query("SELECT estado_pagina FROM Topicos WHERE id = $topico_id AND curso_id = $curso_id");
			if ($count == 15) {
				break;
			}
			if ($topicos->num_rows > 0) {
				while ($topico = $topicos->fetch_assoc()) {
					$topico_estado_pagina = $topico['estado_pagina'];
					$icone_estado = return_estado_icone($topico_estado_pagina, 'materia');
					if ($topico_estado_pagina > 3) {
						$estado_cor = 'text-warning';
					} else {
						$estado_cor = 'text-dark';
					}
					$cor_badge = 'grey lighten-3';
					$icone_badge = "
                                            <span class='badge $cor_badge $estado_cor badge-pill z-depth-0 ml-3'>
                                                <i class='fa $icone_estado fa-fw'></i>
                                            </span>
											";
					$template_conteudo .= "
                                            <a href='pagina.php?topico_id=$topico_id'>
                                                <li class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                                                    <span>
                                                        <strong>$topico_materia_titulo: </strong>
                                                        $topico_titulo
                                                    </span>
                                                    $icone_badge
                                                </li>
                                            </a>";
					$count++;
					break;
				}
			}
		}
		unset($topico_id);
		$template_conteudo .= "</ul>";
	}
	include 'templates/page_element.php';
	?>