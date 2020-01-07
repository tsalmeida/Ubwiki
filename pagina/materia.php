<?php
	$template_id = 'topicos';
	$template_titulo = 'Tópicos';
	$template_conteudo_no_col = true;
	$template_botoes = false;
	$template_conteudo = false;
	
	$topicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico'");
	if ($topicos->num_rows > 0) {
		$template_conteudo .= "<ul class='list-group w-100 px-3'>";
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
			$template_conteudo .= "<a href='pagina.php?pagina_id=$topico_pagina_id'><li class='list-group-item list-group-item-action list-group-item-primary active d-flex justify-content-between mt-3'><span>$topico_pagina_titulo</span><span><i class='$topico_pagina_estado_icone'></i></span></li></a>";
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
					$template_conteudo .= "<a href='pagina.php?pagina_id=$subtopico_pagina_id'><li class='list-group-item list-group-item-action list-group-item-secondary d-flex justify-content-between'><span>$subtopico_pagina_titulo</span><span><i class='$subtopico_pagina_estado_icone'></i></span></li></a>";
					
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
							$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubtopico_pagina_id' class='spacing1'><li class='list-group-item list-group-item-action d-flex justify-content-between'><span>$subsubtopico_pagina_titulo</span><span><i class='$subsubtopico_pagina_estado_icone'></i></span></li></a>";
							
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
									$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubsubtopico_pagina_id' class='spacing2'><li class='list-group-item list-group-item-action d-flex justify-content-between'><span>$subsubsubtopico_pagina_titulo</span><span><i class='$subsubsubtopico_pagina_estado_icone'></i></span></li></a>";
									
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
											$template_conteudo .= "<a href='pagina.php?pagina_id=$subsubsubsubtopico_pagina_id' class='spacing3'><li class='list-group-item list-group-item-action d-flex justify-content-between'><span>$subsubsubsubtopico_pagina_titulo</span><span><i class='$subsubsubsubtopico_pagina_estado_icone'></i></span></li></a>";
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
	
	$apagar_em_breve = false;
	if ($apagar_em_breve != false) {
		$result = $conn->query("SELECT DISTINCT nivel FROM Topicos WHERE materia_id = '$materia_id'");
		$nivel_count = 0;
		while ($row = mysqli_fetch_array($result)) {
			$nivel_count++;
		}
		$cor_nivel1 = false;
		$cor_nivel2 = false;
		$cor_nivel3 = false;
		$cor_nivel4 = false;
		$cor_nivel5 = false;
		
		if ($nivel_count == 5) {
			$cor_nivel1 = 'grey lighten-1';
			$cor_nivel2 = 'grey lighten-2';
			$cor_nivel3 = 'grey lighten-3';
			$cor_nivel4 = 'grey lighten-4';
			$cor_nivel5 = 'grey lighten-5';
		} elseif ($nivel_count == 4) {
			$cor_nivel1 = 'grey lighten-2';
			$cor_nivel2 = 'grey lighten-3';
			$cor_nivel3 = 'grey lighten-4';
			$cor_nivel4 = 'grey lighten-5';
		} elseif ($nivel_count == 3) {
			$cor_nivel1 = 'grey lighten-2';
			$cor_nivel2 = 'grey lighten-3';
			$cor_nivel3 = 'grey lighten-5';
		} elseif ($nivel_count == 2) {
			$cor_nivel1 = 'grey lighten-3';
			$cor_nivel2 = 'grey lighten-5';
		}
		
		$spacing1 = "style=''";
		$spacing2 = "style='padding-left: 5ch'";
		$spacing3 = "style='padding-left: 10ch'";
		$spacing4 = "style='padding-left: 15ch'";
		$spacing5 = "style='padding-left: 20ch'";
		
		$result = $conn->query("SELECT id, estado_pagina, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = '$materia_id' ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$topico_id = $row["id"];
				$estado_pagina = $row['estado_pagina'];
				$nivel1 = $row["nivel1"];
				$nivel2 = $row["nivel2"];
				$nivel3 = $row["nivel3"];
				$nivel4 = $row["nivel4"];
				$nivel5 = $row["nivel5"];
				
				if ($estado_pagina != 0) {
					$estado_cor = false;
					$icone_estado = return_estado_icone($estado_pagina, 'materia');
					if ($estado_pagina > 3) {
						$estado_cor = 'text-warning';
					} else {
						$estado_cor = 'text-dark';
					}
					$cor_badge = 'bg-white';
					$icone_badge = "
				                        <span class='ml-3 badge $cor_badge $estado_cor badge-pill z-depth-0'>
                                            <i class='fa $icone_estado'></i>
                                        </span>
				                    ";
				} else {
					$icone_badge = false;
				}
				
				if ($nivel5 != false) {
					$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel5 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing5>
                                            $nivel5
                                            $icone_badge
                                        </a>
                                    ";
				} elseif ($nivel4 != false) {
					$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel4 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing4>
                                            $nivel4
                                            $icone_badge
                                        </a>
                                    ";
				} elseif ($nivel3 != false) {
					$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel3 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing3>
                                            $nivel3
                                            $icone_badge
                                        </a>
                                    ";
				} elseif ($nivel2 != false) {
					$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel2 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing2>
                                            $nivel2
                                            $icone_badge
                                        </a>
                                    ";
				} elseif ($nivel1 != false) {
					$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel1 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing1>
                                            $nivel1
                                            $icone_badge
                                        </a>
                                    ";
				}
			}
			unset($topico_id);
		}
		$template_conteudo .= "</ul>";
	}
	
	
	include 'templates/page_element.php';
?>