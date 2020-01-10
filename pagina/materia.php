<?php
	$template_id = 'topicos';
	$template_titulo = 'Tópicos';
	$template_botoes = false;
	$template_conteudo = false;
	$template_conteudo_no_col = true;
	
	$planos_estudos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'plano de estudos'");
	if ($planos_estudos->num_rows > 0) {
		while ($plano_estudos = $planos_estudos->fetch_assoc()) {
			$plano_estudos_pagina_id = $plano_estudos['elemento_id'];
			break;
		}
	} else {
		$conn->query("INSERT INTO Paginas (tipo, subtipo, item_id) VALUES ('pagina', 'Plano de estudos', $pagina_id)");
		$plano_estudos_pagina_id = $conn->insert_id;
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo) VALUES ($pagina_id, $plano_estudos_pagina_id, 'plano de estudos')");
	}
	
	$plano_estudos_pagina_info = return_pagina_info($plano_estudos_pagina_id);
	$plano_estudos_pagina_estado = $plano_estudos_pagina_info[3];
	if ($plano_estudos_pagina_estado != 0) {
		$plano_estudos_pagina_estado_icone = return_estado_icone($plano_estudos_pagina_estado, 'materia');
	} else {
		$plano_estudos_pagina_estado_icone = false;
	}
	
	$topicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico'");
	if ($topicos->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group list-group-flush'>";
		$template_conteudo .= "<a href='pagina.php?pagina_id=$plano_estudos_pagina_id'><li class='list-group-item list-group-item-action list-group-item-success d-flex justify-content-between'><span class='mr-5'>Plano de estudos</span><i class='$plano_estudos_pagina_estado_icone'></i></li></a>";
		while ($topico = $topicos->fetch_assoc()) {
			$topico_pagina_id = $topico['elemento_id'];
			if ($topico_pagina_id == false) {
				continue;
			}
			$topico_pagina_info = return_pagina_info($topico_pagina_id);
			$topico_pagina_estado = $topico_pagina_info[3];
			$topico_pagina_titulo = $topico_pagina_info[6];
			if ($topico_pagina_estado != 0) {
				$topico_pagina_estado_icone = return_estado_icone($topico_pagina_estado, 'materia');
			} else {
				$topico_pagina_estado_icone = false;
			}
			$template_conteudo .= "<a href='pagina.php?pagina_id=$topico_pagina_id' class='mt-3'><li class='list-group-item list-group-item-action list-group-item-primary d-flex justify-content-between'><span class='mr-5'>$topico_pagina_titulo</span><i class='$topico_pagina_estado_icone'></i></li></a>";
			$subtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $topico_pagina_id AND tipo = 'subtopico'");
			if ($subtopicos->num_rows > 0) {
				while ($subtopico = $subtopicos->fetch_assoc()) {
					$subtopico_pagina_id = $subtopico['elemento_id'];
					$subtopico_pagina_info = return_pagina_info($subtopico_pagina_id);
					$subtopico_pagina_estado = $subtopico_pagina_info[3];
					$subtopico_pagina_titulo = $subtopico_pagina_info[6];
					if ($subtopico_pagina_estado != false) {
						$subtopico_pagina_estado_icone = return_estado_icone($subtopico_pagina_estado, 'materia');
					} else {
						$subtopico_pagina_estado_icone = false;
					}
					$template_conteudo .= "<a href='pagina.php?pagina_id=$subtopico_pagina_id' class='mt-1'><li class='list-group-item list-group-item-action list-group-item-secondary d-flex justify-content-between'><span class='mr-5'>$subtopico_pagina_titulo</span><span><i class='$subtopico_pagina_estado_icone'></i></span></li></a>";
					
					$subsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subtopico_pagina_id AND tipo = 'subtopico'");
					if ($subsubtopicos->num_rows > 0) {
						while ($subsubtopico = $subsubtopicos->fetch_assoc()) {
							$subsubtopico_pagina_id = $subsubtopico['elemento_id'];
							$subsubtopico_pagina_info = return_pagina_info($subsubtopico_pagina_id);
							$subsubtopico_pagina_estado = $subsubtopico_pagina_info[3];
							$subsubtopico_pagina_titulo = $subsubtopico_pagina_info[6];
							if ($subsubtopico_pagina_estado != false) {
								$subsubtopico_pagina_estado_icone = return_estado_icone($subsubtopico_pagina_estado, 'materia');
							} else {
								$subsubtopico_pagina_estado_icone = false;
							}
							$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubtopico_pagina_id' class='mt-1'><li class='list-group-item list-group-item-action d-flex justify-content-between'><span class='mr-5'>$subsubtopico_pagina_titulo</span><span><i class='$subsubtopico_pagina_estado_icone'></i></span></li></a>";
							
							$subsubsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubtopico_pagina_id AND tipo = 'subtopico'");
							if ($subsubsubtopicos->num_rows > 0) {
								while ($subsubsubtopico = $subsubsubtopicos->fetch_assoc()) {
									$subsubsubtopico_pagina_id = $subsubsubtopico['elemento_id'];
									$subsubsubtopico_pagina_info = return_pagina_info($subsubsubtopico_pagina_id);
									$subsubsubtopico_pagina_estado = $subsubsubtopico_pagina_info[3];
									$subsubsubtopico_pagina_titulo = $subsubsubtopico_pagina_info[6];
									if ($subsubsubtopico_pagina_estado != false) {
										$subsubsubtopico_pagina_estado_icone = return_estado_icone($subsubsubtopico_pagina_estado, 'materia');
									} else {
										$subsubsubtopico_pagina_estado_icone = false;
									}
									$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubsubtopico_pagina_id' class='spacing1 mt-1'><li class='list-group-item list-group-item-action list-group-item-light d-flex justify-content-between'><em class='mr-5'>$subsubsubtopico_pagina_titulo</em><span><i class='$subsubsubtopico_pagina_estado_icone'></i></span></li></a>";
									
									$subsubsubsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubsubtopico_pagina_id AND tipo = 'subtopico'");
									if ($subsubsubsubtopicos->num_rows > 0) {
										while ($subsubsubsubtopico = $subsubsubsubtopicos->fetch_assoc()) {
											$subsubsubsubtopico_pagina_id = $subsubsubsubtopico['elemento_id'];
											$subsubsubsubtopico_pagina_info = return_pagina_info($subsubsubsubtopico_pagina_id);
											$subsubsubsubtopico_pagina_estado = $subsubsubsubtopico_pagina_info[3];
											$subsubsubsubtopico_pagina_titulo = $subsubsubsubtopico_pagina_info[6];
											if ($subsubsubsubtopico_pagina_estado != false) {
												$subsubsubsubtopico_pagina_estado_icone = return_estado_icone($subsubsubsubtopico_pagina_estado, 'materia');
											} else {
												$subsubsubsubtopico_pagina_estado_icone = false;
											}
											$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubsubsubtopico_pagina_id' class='spacing2 mt-1'><li class='list-group-item list-group-item-action list-group-item-light d-flex justify-content-between'><em class='mr-5'>$subsubsubsubtopico_pagina_titulo</em><span><i class='$subsubsubsubtopico_pagina_estado_icone'></i></span></li></a>";
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$template_conteudo .= "</ul>";
	} else {
		$template_conteudo .= "<p class='text-muted'>Não há tópicos registrados nesta matéria.</p>";
	}
	
	include 'templates/page_element.php';
?>