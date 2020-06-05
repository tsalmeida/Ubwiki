<?php
	
	$template_id = 'topicos';
	$template_titulo = $pagina_translated['Tópicos'];
	$template_botoes = false;
	$template_botoes_padrao = false;
	$template_conteudo = false;
	$template_conteudo_no_col = true;

	$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'plano de estudos'");
	$planos_estudos = $conn->query($query);
	if ($planos_estudos->num_rows > 0) {
		while ($plano_estudos = $planos_estudos->fetch_assoc()) {
			$plano_estudos_pagina_id = $plano_estudos['elemento_id'];
			break;
		}
	} else {
		$query = prepare_query("INSERT INTO Paginas (tipo, subtipo, item_id) VALUES ('pagina', 'Plano de estudos', $pagina_id)");
		$conn->query($query);
		$plano_estudos_pagina_id = $conn->insert_id;
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo) VALUES ($pagina_id, $plano_estudos_pagina_id, 'plano de estudos')");
		$conn->query($query);
	}

	if ($user_id != false) {
		$query = prepare_query("SELECT pagina_id FROM Completed WHERE user_id = $user_id AND estado = 1");
		$completos = $conn->query($query);
		$usuario_completos = array();
		if ($completos->num_rows > 0) {
			while ($completo = $completos->fetch_assoc()) {
				$completo_pagina_id = $completo['pagina_id'];
				array_push($usuario_completos, $completo_pagina_id);
			}
		}
	} else {
		$completos = false;
		$usuario_completos = array();
	}
	
	$completo_efeito = 'border border-success green-text';

	$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico'");
	$topicos = $conn->query($query);
	if ($topicos->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group list-group-flush mt-1 min-w70 topicos_collapse collapse show'>";
		$template_conteudo .= return_list_item($plano_estudos_pagina_id, false, 'list-group-item-success', true, false, 'none');

		while ($topico = $topicos->fetch_assoc()) {
			$topico_pagina_id = $topico['elemento_id'];
			if ($topico_pagina_id == false) {
				continue;
			}
			$topico_completo = false;
			if (in_array($topico_pagina_id, $usuario_completos)) {
				$topico_completo = $completo_efeito;
			}
			$topico_pagina_info = return_pagina_info($topico_pagina_id);
			if ($topico_pagina_info == false) {
				continue;
			}
			$topico_pagina_estado = $topico_pagina_info[3];
			$topico_pagina_titulo = $topico_pagina_info[6];
			if ($topico_pagina_estado != 0) {
				$topico_pagina_estado_icone = return_estado_icone($topico_pagina_estado, 'materia');
			} else {
				$topico_pagina_estado_icone = false;
			}
			$template_conteudo .= "<ul class='list-group grey lighten-4 rounded p-1 mt-1'>";
			$template_conteudo .= put_together_list_item('link', "pagina.php?pagina_id=$topico_pagina_id", false, false, false, $topico_pagina_titulo, false, $topico_pagina_estado_icone, "list-group-item-primary $topico_completo");

			$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $topico_pagina_id AND tipo = 'subtopico'");
			$subtopicos = $conn->query($query);
			if ($subtopicos->num_rows > 0) {
				while ($subtopico = $subtopicos->fetch_assoc()) {
					$subtopico_pagina_id = $subtopico['elemento_id'];
					$subtopico_pagina_info = return_pagina_info($subtopico_pagina_id);
					$subtopico_pagina_estado = $subtopico_pagina_info[3];
					$subtopico_pagina_titulo = $subtopico_pagina_info[6];
					$topico_completo = false;
					if (in_array($subtopico_pagina_id, $usuario_completos)) {
						$topico_completo = $completo_efeito;
					}
					if ($subtopico_pagina_estado != false) {
						$subtopico_pagina_estado_icone = return_estado_icone($subtopico_pagina_estado, 'materia');
					} else {
						$subtopico_pagina_estado_icone = false;
					}
					$template_conteudo .= put_together_list_item('link', "pagina.php?pagina_id=$subtopico_pagina_id", false, false, false, $subtopico_pagina_titulo, false, $subtopico_pagina_estado_icone, "list-group-item-secondary $topico_completo", 'mt-1 spacing1');

					$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subtopico_pagina_id AND tipo = 'subtopico'");
					$subsubtopicos = $conn->query($query);
					if ($subsubtopicos->num_rows > 0) {
						while ($subsubtopico = $subsubtopicos->fetch_assoc()) {
							$subsubtopico_pagina_id = $subsubtopico['elemento_id'];
							$subsubtopico_pagina_info = return_pagina_info($subsubtopico_pagina_id);
							$subsubtopico_pagina_estado = $subsubtopico_pagina_info[3];
							$subsubtopico_pagina_titulo = $subsubtopico_pagina_info[6];
							$topico_completo = false;
							if (in_array($subsubtopico_pagina_id, $usuario_completos)) {
								$topico_completo = $completo_efeito;
							}
							if ($subsubtopico_pagina_estado != false) {
								$subsubtopico_pagina_estado_icone = return_estado_icone($subsubtopico_pagina_estado, 'materia');
							} else {
								$subsubtopico_pagina_estado_icone = false;
							}
							$template_conteudo .= put_together_list_item('link', "pagina.php?pagina_id=$subsubtopico_pagina_id", false, false, false, $subsubtopico_pagina_titulo, false, $subsubtopico_pagina_estado_icone, $topico_completo, 'mt-1 spacing2');

							$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubtopico_pagina_id AND tipo = 'subtopico'");
							$subsubsubtopicos = $conn->query($query);
							if ($subsubsubtopicos->num_rows > 0) {
								while ($subsubsubtopico = $subsubsubtopicos->fetch_assoc()) {
									$subsubsubtopico_pagina_id = $subsubsubtopico['elemento_id'];
									$subsubsubtopico_pagina_info = return_pagina_info($subsubsubtopico_pagina_id);
									$subsubsubtopico_pagina_estado = $subsubsubtopico_pagina_info[3];
									$subsubsubtopico_pagina_titulo = $subsubsubtopico_pagina_info[6];
									$topico_completo = false;
									if (in_array($subsubsubtopico_pagina_id, $usuario_completos)) {
										$topico_completo = $completo_efeito;
									}
									if ($subsubsubtopico_pagina_estado != false) {
										$subsubsubtopico_pagina_estado_icone = return_estado_icone($subsubsubtopico_pagina_estado, 'materia');
									} else {
										$subsubsubtopico_pagina_estado_icone = false;
									}

									$template_conteudo .= put_together_list_item('link', "pagina.php?pagina_id=$subsubsubtopico_pagina_id", false, false, false, $subsubsubtopico_pagina_titulo, false, $subsubsubtopico_pagina_estado_icone, "$topico_completo font-italic text-muted", 'mt-1 spacing3');

									$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubsubtopico_pagina_id AND tipo = 'subtopico'");
									$subsubsubsubtopicos = $conn->query($query);
									if ($subsubsubsubtopicos->num_rows > 0) {
										while ($subsubsubsubtopico = $subsubsubsubtopicos->fetch_assoc()) {
											$subsubsubsubtopico_pagina_id = $subsubsubsubtopico['elemento_id'];
											$subsubsubsubtopico_pagina_info = return_pagina_info($subsubsubsubtopico_pagina_id);
											$subsubsubsubtopico_pagina_estado = $subsubsubsubtopico_pagina_info[3];
											$subsubsubsubtopico_pagina_titulo = $subsubsubsubtopico_pagina_info[6];
											$topico_completo = false;
											if (in_array($subsubsubsubtopico_pagina_id, $usuario_completos)) {
												$topico_completo = $completo_efeito;
											}
											if ($subsubsubsubtopico_pagina_estado != false) {
												$subsubsubsubtopico_pagina_estado_icone = return_estado_icone($subsubsubsubtopico_pagina_estado, 'materia');
											} else {
												$subsubsubsubtopico_pagina_estado_icone = false;
											}
											$template_conteudo .= put_together_list_item('link', "pagina.php?pagina_id=$subsubsubsubtopico_pagina_id", false, false, false, $subsubsubsubtopico_pagina_titulo, false, $subsubsubsubtopico_pagina_estado_icone, "$topico_completo font-italic text-muted", 'mt-1 spacing4');
										}
									}
								}
							}
						}
					}
				}
			}
			$template_conteudo .= "</ul>";
		}
		$template_conteudo .= "</ul>";
	} else {
		$template_conteudo .= "<p class='text-muted'>{$pagina_translated['Não há tópicos registrados neste módulo.']}</p>";
	}
	
	include 'templates/page_element.php';
?>