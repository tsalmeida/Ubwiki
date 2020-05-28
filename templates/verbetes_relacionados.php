<?php
	$topico_anterior = false;
	$topico_proximo = false;
	$breadcrumbs = false;
	
	$breadcrumbs .= "<ul class='list-group list-group-flush'>";
	
	$breadcrumbs .= return_list_item($topico_curso_pagina_id);
	$breadcrumbs .= return_list_item($topico_materia_pagina_id);
	
	if ($topico_nivel == 1) {
		$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		$fam_niveis1 = $conn->query($query);
		if ($fam_niveis1->num_rows > 0) {
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$breadcrumbs .= return_list_item($fam_nivel1_pagina_id, 'link', false);
				} else {
					$breadcrumbs .= return_list_item($pagina_id, 'inactive', 'list-group-item-warning');
					$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					$fam_niveis2 = $conn->query($query);
					if ($fam_niveis2->num_rows > 0) {
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$breadcrumbs .= return_list_item($fam_nivel2_pagina_id, 'link', 'spacing2');
						}
					}
				}
			}
		}
	}
	if ($topico_nivel > 1) {
		$breadcrumbs .= return_list_item($familia_info[3]);
	}
	if ($topico_nivel == 2) {
		$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		$fam_niveis1 = $conn->query($query);
		if ($fam_niveis1->num_rows > 0) {
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$breadcrumbs .= return_list_item($fam_nivel1_pagina_id, 'link', 'spacing2');
				} else {
					$breadcrumbs .= return_list_item($pagina_id, 'inactive', 'list-group-item-warning spacing2');
					$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					$fam_niveis2 = $conn->query($query);
					if ($fam_niveis2->num_rows > 0) {
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$breadcrumbs .= return_list_item($fam_nivel2_pagina_id, 'link', 'spacing3');
						}
					}
				}
			}
		}
	}
	if ($topico_nivel > 2) {
		$breadcrumbs .= return_list_item($familia_info[4], 'link', 'spacing2');
	}
	if ($topico_nivel == 3) {
		$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		$fam_niveis1 = $conn->query($query);
		if ($fam_niveis1->num_rows > 0) {
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$breadcrumbs .= return_list_item($fam_nivel1_pagina_id, 'link', 'spacing3');
				} else {
					$breadcrumbs .= return_list_item($pagina_id, 'inactive', 'list-group-item-warning spacing3');
					$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					$fam_niveis2 = $conn->query($query);
					if ($fam_niveis2->num_rows > 0) {
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$breadcrumbs .= return_list_item($fam_nivel2_pagina_id, 'link', 'spacing4');
						}
					}
				}
			}
		}
	}
	if ($topico_nivel > 3) {
		$breadcrumbs .= return_list_item($familia_info[5], 'link', 'spacing3');
	}
	if ($topico_nivel == 4) {
		$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		$fam_niveis1 = $conn->query($query);
		if ($fam_niveis1->num_rows > 0) {
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$breadcrumbs .= return_list_item($fam_nivel1_pagina_id, 'link', 'spacing4');
				} else {
					$breadcrumbs .= return_list_item($pagina_id, 'inactive', 'list-group-item-warning spacing4');
					$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_id AND tipo = 'topico'");
					$fam_niveis2 = $conn->query($query);
					if ($fam_niveis2->num_rows > 0) {
						while ($fam_nivel2 = $fam_niveis2->fetch_assoc()) {
							$fam_nivel2_pagina_id = $fam_nivel2['id'];
							$breadcrumbs .= return_list_item($fam_nivel2_pagina_id, 'link', 'spacing5');
						}
					}
				}
			}
		}
	}
	if ($topico_nivel > 4) {
		$breadcrumbs .= return_list_item($familia_info[6], 'link', 'spacing4');
	}
	if ($topico_nivel == 5) {
		$query = prepare_query("SELECT id FROM Paginas WHERE item_id = $pagina_item_id AND tipo = 'topico'");
		$fam_niveis1 = $conn->query($query);
		if ($fam_niveis1->num_rows > 0) {
			while ($fam_nivel1 = $fam_niveis1->fetch_assoc()) {
				$fam_nivel1_pagina_id = $fam_nivel1['id'];
				if ($fam_nivel1_pagina_id != $pagina_id) {
					$breadcrumbs .= return_list_item($fam_nivel1_pagina_id, 'link', 'spacing5');
				} else {
					$breadcrumbs .= return_list_item($pagina_id, 'inactive', 'list-group-item-warning spacing5');
				}
			}
		}
	}
	
	$breadcrumbs .= "</ul>";

?>
