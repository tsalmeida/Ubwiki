<?php

	//TODO: Ownership e classificação por ícones.
	//TODO: When clicking a comment, its current content should load on the form.
	//TODO: Copy a plan to make it your own.
	//TODO: I need to transfer my own Personal Library plan to a separate plan.
	//TODO: Mouseover on the top center icons.

	if (isset($_POST['adicionar_pagina_id'])) {
		$adicionar_pagina_id = $_POST['adicionar_pagina_id'];
		$check = return_compartilhamento($adicionar_pagina_id, $user_id);
		if ($check == true) {
			$query = prepare_query("SELECT id FROM Planejamento WHERE plano_id = $pagina_item_id AND pagina_id = $pagina_id AND elemento_id = $adicionar_pagina_id AND tipo = 'pagina' AND user_id = $user_id");
			$check2 = $conn->query($query);
			if ($check2->num_rows == 0) {
				$query = prepare_query("INSERT INTO Planejamento (plano_id, pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_item_id, $pagina_id, $adicionar_pagina_id, 'pagina', $user_id)");
				$conn->query($query);
			}
		}
	}

	echo "<div class='container-fluid px-3'>";

	$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND estado = 1 AND elemento_id IS NOT NULL AND tipo != 'modelo' ORDER BY id ASC");
	$items_biblioteca = $conn->query($query);
	$all_items_biblioteca = array();
	if ($items_biblioteca->num_rows > 0) {
		while ($item_biblioteca = $items_biblioteca->fetch_assoc()) {
			$item_biblioteca_elemento_id = $item_biblioteca['elemento_id'];
			array_push($all_items_biblioteca, $item_biblioteca_elemento_id);
		}
	}

	$query = prepare_query("SELECT * FROM Planejamento WHERE plano_id = $pagina_item_id ORDER BY estado DESC");
	$items_planejamento = $conn->query($query);
	$all_items_planejamento = array();
	$registered_rows = false;
	if ($items_planejamento->num_rows > 0) {
		while ($item_planejamento = $items_planejamento->fetch_assoc()) {
			$item_planejamento_entrada_id = $item_planejamento['id'];
			$item_planejamento_tipo = $item_planejamento['tipo'];
			$item_planejamento_elemento_id = $item_planejamento['elemento_id'];
			array_push($all_items_planejamento, $item_planejamento_elemento_id);
			$item_planejamento_estado = $item_planejamento['estado'];
			if ($plan_show_completed == false) {
				if ($item_planejamento_estado > 11) {
					continue;
				}
			}
			if ($plan_show_low == false) {
				if ($item_planejamento_estado < 6) {
					if ($item_planejamento_estado != 0) {
						continue;
					}
				}
			}
			$item_planejamento_classificacao = $item_planejamento['classificacao'];
			$item_planejamento_classificacao = return_pagina_titulo($item_planejamento_classificacao);
			$item_planejamento_comments = $item_planejamento['comments'];
			if ($item_planejamento_comments == false) {
				$item_planejamento_comments = $pagina_translated['no comments'];
			}
			$item_row = return_plan_row($item_planejamento_elemento_id, $item_planejamento_estado, $item_planejamento_classificacao, $item_planejamento_comments, $item_planejamento_tipo, $item_planejamento_entrada_id);
			$registered_rows .= $item_row;
		}
	}

	$all_items_nao_registrados = array_diff($all_items_biblioteca, $all_items_planejamento);
	foreach ($all_items_nao_registrados as $registrar_elemento_id) {
		$registrar_elemento_pagina_id = return_pagina_id($registrar_elemento_id, 'elemento');
		$query = prepare_query("INSERT INTO Planejamento (plano_id, pagina_id, elemento_id, user_id, estado) VALUES ($pagina_item_id, $registrar_elemento_pagina_id, $registrar_elemento_id, $user_id, 0)");
		$conn->query($query);
		$nova_entrada_id = $conn->insert_id;
		$item_row = return_plan_row($registrar_elemento_id, false, false, $pagina_translated['no comments'], 'elemento', $nova_entrada_id);
		echo $item_row;
	}
	echo $registered_rows;

	echo "</div>";

	$template_modal_div_id = 'modal_set_state';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Set state'];

	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<input type='hidden' value='' id='set_state_entrada_id'>";
	$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
	$estado = 17;
	while ($estado != 0) {
		$icone = return_plan_icon($estado);
		$icone[3] = $pagina_translated[$icone[3]];
		$template_modal_body_conteudo .= put_together_list_item('link_button', $estado, "$icone[1] $icone[0] p-1 rounded align-self-center", $icone[2], $icone[3], false, false, false, 'set_this_state');
		$estado = $estado - 1;
	}
	$template_modal_body_conteudo .= "</ul>";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_add_comment';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Add comment'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<input type='hidden' value='' id='set_comment_elemento_id' name='set_comment_elemento_id'>";
	$template_modal_body_conteudo .= "
        <div class='mb-3'>
            <label for='set_comment' class='form-label'>{$pagina_translated['Write comment here']}</label>
            <textarea class='md-textarea form-control' id='set_comment' name='set_comment'></textarea>
            
        </div>
    ";
	include 'templates/modal.php';

	//TODO: Focus on the input when the modal opens.
	$template_modal_div_id = 'modal_set_tag';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Set plan tag'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
        <input type='hidden' value='' id='set_tag_elemento_id' name='set_tag_elemento_id'>
        <div class='mb-3'>
            <label for='plan_set_tag' class='form-label'>{$pagina_translated['Set category']}</label>
            <input type='text' class='form-control' name='plan_set_tag' id='plan_set_tag'>
        </div>
    ";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_adicionar_pagina';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['add page'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
            <p>{$pagina_translated['explanation page id']}</p>
            <div class='mb-3'>
                <label for='adicionar_pagina_id' class='form-label'>{$pagina_translated['page id']}</label>
                <input id='adicionar_pagina_id' name='adicionar_pagina_id' type='text' class='form-control' value=''>
            </div>
        ";
	include 'templates/modal.php';