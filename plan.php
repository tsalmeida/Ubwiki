<?php
	include 'engine.php';
	$pagina_tipo = 'planner';
	$pagina_id = $user_escritorio;
	if ($user_email == false) {
		header('Location:ubwiki.php');
		exit();
	}
	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
<div class="container mt-1">
	<?php
		$template_titulo = $pagina_translated['Study Planner'];
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
			<?php
				$items_biblioteca = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $user_escritorio AND estado = 1 AND elemento_id IS NOT NULL ORDER BY id ASC");
				$all_items_biblioteca = array();
				if ($items_biblioteca->num_rows > 0) {
					while ($item_biblioteca = $items_biblioteca->fetch_assoc()) {
						$item_biblioteca_elemento_id = $item_biblioteca['elemento_id'];
						array_push($all_items_biblioteca, $item_biblioteca_elemento_id);
					}
				}

				$items_planejamento = $conn->query("SELECT * FROM Planejamento WHERE user_id = $user_id ORDER BY id DESC");
				$all_items_planejamento = array();
				$registered_rows = false;
				if ($items_planejamento->num_rows > 0) {
					while ($item_planejamento = $items_planejamento->fetch_assoc()) {
						$item_planejamento_elemento_id = $item_planejamento['elemento_id'];
						array_push($all_items_planejamento, $item_planejamento_elemento_id);
                        $item_planejamento_estado = $item_planejamento['estado'];
                        $item_planejamento_classificacao = $item_planejamento['classificacao'];
                        $item_planejamento_comments = $item_planejamento['comments'];
                        if ($item_planejamento_comments == false) {
                            $item_planejamento_comments = $pagina_translated['no comments'];
                        }
						$item_row = return_plan_row($item_planejamento_elemento_id, $item_planejamento_estado, $item_planejamento_classificacao, $item_planejamento_comments);
                        $registered_rows .= $item_row;
					}
				}

				$all_items_nao_registrados = array_diff($all_items_biblioteca, $all_items_planejamento);
				foreach ($all_items_nao_registrados as $registrar_elemento_id) {
				    $conn->query("INSERT INTO Planejamento (elemento_id, user_id) VALUES ($registrar_elemento_id, $user_id)");
				    $item_row = return_plan_row($registrar_elemento_id, false, false, $pagina_translated['no comments']);
				    echo $item_row;
                }
				echo $registered_rows;
			?>
        </div>
    </div>
</div>
<?php

    $template_modal_div_id = 'modal_set_state';
    $template_modal_titulo = 'Set state';
    $template_modal_body_conteudo = false;
    $template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
    $estado = 15;
    while ($estado > -1) {
        $icone = return_plan_icon($estado);
        $template_modal_body_conteudo .= put_together_list_item('link_button', 'set_this_state', "$icone[1] $icone[0] p-1 rounded align-self-center", false, $icone[2], $icone[3], false, false, false);
        $estado = $estado - 1;
    }
    $template_modal_body_conteudo .= "</ul>";
    $template_modal_show_buttons = false;
    include 'templates/modal.php';

	include 'pagina/modal_languages.php';
?>
</body>
<?php
	include 'templates/html_bottom.php';
?>
</html>
