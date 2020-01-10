<?php
	// VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS
	$topico_anterior = false;
	$topico_proximo = false;
	$breadcrumbs = false;
	
	$familia_info = return_familia($pagina_id);
	
	$topico_nivel = $familia_info[0];
	$topico_curso_id = $familia_info[1];
	$topico_curso_titulo = return_pagina_titulo($topico_curso_id);
	$topico_materia_id = $familia_info[2];
	$topico_materia_titulo = return_pagina_titulo($topico_materia_id);
	
	$breadcrumbs .= "<h4><strong>Curso:</strong> <a href='pagina.php?pagina_id=$topico_curso_id'>$topico_curso_titulo</a></h4>";
	$breadcrumbs .= "<h4><strong>Matéria:</strong> <a href='pagina.php?pagina_id=$topico_materia_id'>$topico_materia_titulo</a></h4>";
	
	if ($topico_nivel == 1) {
		$breadcrumbs .= "<h4>Tópicos:</h4>";
		$fam_niveis1 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id");
		if ($fam_niveis1->num_rows > 0) {
			$breadcrumbs .= "<ol>";
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$fam_nivel1_titulo = return_pagina_titulo($fam_nivel1_pagina_id);
					$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel1_pagina_id'>$fam_nivel1_titulo</a></span></li>";
				} else {
					$breadcrumbs .= "<li>$pagina_titulo</li>";
					$fam_niveis2 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_id");
					if ($fam_niveis2->num_rows > 0) {
						$breadcrumbs .= "<ol>";
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$fam_nivel2_titulo = return_pagina_titulo($fam_nivel2_pagina_id);
							$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel2_pagina_id'>$fam_nivel2_titulo</a></span></li>";
						}
						$breadcrumbs .= "</ol>";
					}
				}
			}
			$breadcrumbs .= "</ol>";
		}
	}
	if ($topico_nivel > 1) {
		$topico_nivel1_titulo = return_pagina_titulo($familia_info[3]);
		$breadcrumbs .= "<h4>Tópico: <a href='pagina.php?pagina_id=$familia_info[3]'><span class=''>$topico_nivel1_titulo</span></a></h4>";
	}
	if ($topico_nivel == 2) {
		$fam_niveis1 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		if ($fam_niveis1->num_rows > 0) {
			$breadcrumbs .= "<h4>Subtópicos:</h4>";
			$breadcrumbs .= "<ol>";
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$fam_nivel1_titulo = return_pagina_titulo($fam_nivel1_pagina_id);
					$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel1_pagina_id'>$fam_nivel1_titulo</a></span></li>";
				} else {
					$breadcrumbs .= "<li>$pagina_titulo</li>";
					$fam_niveis2 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					if ($fam_niveis2->num_rows > 0) {
						$breadcrumbs .= "<ol>";
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$fam_nivel2_titulo = return_pagina_titulo($fam_nivel2_pagina_id);
							$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel2_pagina_id'>$fam_nivel2_titulo</a></span></li>";
						}
						$breadcrumbs .= "</ol>";
					}
				}
			}
			$breadcrumbs .= "</ol>";
		}
	}
	if ($topico_nivel > 2) {
		$topico_nivel2_titulo = return_pagina_titulo($familia_info[4]);
		$breadcrumbs .= "<h4>Subtópico: <a href='pagina.php?pagina_id=$familia_info[4]'>$topico_nivel2_titulo</a></h4>";
	}
	if ($topico_nivel == 3) {
		$fam_niveis1 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		if ($fam_niveis1->num_rows > 0) {
			$breadcrumbs .= "<h4>Subtópicos:</h4>";
			$breadcrumbs .= "<ol>";
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$fam_nivel1_titulo = return_pagina_titulo($fam_nivel1_pagina_id);
					$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel1_pagina_id'>$fam_nivel1_titulo</a></span></li>";
				} else {
					$breadcrumbs .= "<li>$pagina_titulo</li>";
					$fam_niveis2 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					if ($fam_niveis2->num_rows > 0) {
						$breadcrumbs .= "<ol>";
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$fam_nivel2_titulo = return_pagina_titulo($fam_nivel2_pagina_id);
							$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel2_pagina_id'>$fam_nivel2_titulo</a></span></li>";
						}
						$breadcrumbs .= "</ol>";
					}
				}
			}
			$breadcrumbs .= "</ol>";
		}
	}
	if ($topico_nivel > 3) {
		$topico_nivel3_titulo = return_pagina_titulo($familia_info[5]);
		$breadcrumbs .= "<h4>Subtópico: <a href='pagina.php?pagina_id=$familia_info[5]'><span class=''>$topico_nivel3_titulo</span></a></h4>";
	}
	if ($topico_nivel == 4) {
		$fam_niveis1 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		if ($fam_niveis1->num_rows > 0) {
			$breadcrumbs .= "<h4>Subtópicos:</h4>";
			$breadcrumbs .= "<ol>";
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$fam_nivel1_titulo = return_pagina_titulo($fam_nivel1_pagina_id);
					$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel1_pagina_id'>$fam_nivel1_titulo</a></span></li>";
				} else {
					$breadcrumbs .= "<li>$pagina_titulo</li>";
					$fam_niveis2 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					if ($fam_niveis2->num_rows > 0) {
						$breadcrumbs .= "<ol>";
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$fam_nivel2_titulo = return_pagina_titulo($fam_nivel2_pagina_id);
							$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel2_pagina_id'>$fam_nivel2_titulo</a></span></li>";
						}
						$breadcrumbs .= "</ol>";
					}
				}
			}
			$breadcrumbs .= "</ol>";
		}
	}
	if ($topico_nivel > 4) {
		$topico_nivel4_titulo = return_pagina_titulo($familia_info[6]);
		$breadcrumbs .= "<h4>Subtópico: <a href='pagina.php?pagina_id=$familia_info[6]'><span class=''>$topico_nivel4_titulo</span></a></h4>";
	}
	if ($topico_nivel == 5) {
		$fam_niveis1 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		if ($fam_niveis1->num_rows > 0) {
			$breadcrumbs .= "<h4>Subtópicos:</h4>";
			$breadcrumbs .= "<ol>";
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$fam_nivel1_titulo = return_pagina_titulo($fam_nivel1_pagina_id);
					$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel1_pagina_id'>$fam_nivel1_titulo</a></span></li>";
				} else {
					$breadcrumbs .= "<li>$pagina_titulo</li>";
					$fam_niveis2 = $conn->query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					if ($fam_niveis2->num_rows > 0) {
						$breadcrumbs .= "<ol>";
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$fam_nivel2_titulo = return_pagina_titulo($fam_nivel2_pagina_id);
							$breadcrumbs .= "<li><span><a href='pagina.php?pagina_id=$fam_nivel2_pagina_id'>$fam_nivel2_titulo</a></span></li>";
						}
						$breadcrumbs .= "</ol>";
					}
				}
			}
			$breadcrumbs .= "</ol>";
		}
	}

?>
