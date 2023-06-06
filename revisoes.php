<?php
	$pagina_tipo = 'revisoes';
	$pagina_id = 1;
	include 'engine.php';
	if (($user_revisor == false) || ($_SESSION['user_email'] == false)) {
		header('Location:escritorio.php');
		exit();
	}

	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="bg-light">
<div class="container">
	<?php
		$template_titulo = $pagina_translated['review'];
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
			<?php
				$template_id = 'revisoes_disponiveis';
				$template_titulo = 'Revisões disponíveis';
				$template_conteudo = false;
				$query = prepare_query("SELECT * FROM Orders WHERE tipo = 'review' and estado = 1");
				$orders = $conn->query($query);
				if ($orders->num_rows > 0) {
					while ($order = $orders->fetch_assoc()) {
						$template_conteudo .= "<ul class='list-group list-group-flush border p-3 my-3 primary-color rounded'>";
						$order_pagina_id = $order['pagina_id'];
						$order_comments = $order['comments'];
						$order_user_id = $order['user_id'];
						$order_option1 = $order['option1'];
						$order_option2 = $order['option2'];
						$order_option3 = $order['option3'];
						$order_option4 = $order['option4'];
						$order_option5 = $order['option5'];
						$order_option6 = $order['option6'];
						$order_option7 = $order['option7'];
						$order_user_apelido = return_apelido_user_id($order_user_id);
						$order_user_avatar = return_avatar($order_user_id);
						$template_conteudo .= put_together_list_item('inactive', false, false, false, 'Cliente:', false, false, 'text-center list-group-item-info');
						$template_conteudo .= put_together_list_item('inactive', false, $order_user_avatar[1], $order_user_avatar[0], $order_user_apelido, false, false, 'text-center');
						$template_conteudo .= put_together_list_item('inactive', false, false, false, 'Informações do pedido:', false, false, 'text-center list-group-item-info');
						$template_conteudo .= put_together_list_item('inactive', false, 'link-teal', 'fad fa-tally', "$order_option1 palavras", false, false, false);

						switch ($order_option2) {
							case 'simplified':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-danger', 'fad fa-eraser', "Revisão simplificada", false, false, false);
								break;
							case 'detailed':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-danger', 'fad fa-eraser', "Revisão detalhada", false, false, false);
								break;
							case 'rewrite':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-danger', 'fad fa-eraser', "Co-autoria", false, false, false);
								break;
						}

						switch ($order_option3) {
							case 'with_grade':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-warning', 'fad fa-award', "Incluir nota", false, false, false);
								break;
							case 'no_grade':
								$template_conteudo .= put_together_list_item('inactive', false, false, 'fad fa-award', "Não incluir nota", false, false, 'list-group-item-light');
								break;
						}

						switch ($order_option4) {
							case 'chat_20':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-success', 'fad fa-comments-dollar', "Inclui conversa telefônica com o revisor por 20 minutos", false, false, false);
								break;
							case 'chat_40':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-success', 'fad fa-comments-dollar', "Inclui conversa telefônica com o revisor por 40 minutos", false, false, false);
								break;
							case 'chat_60':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-success', 'fad fa-comments-dollar', "inclui conversa telefônica com o revisor por 60 minutos", false, false, false);
								break;
							case 'no_chat':
								$template_conteudo .= put_together_list_item('inactive', false, false, 'fad fa-comments-dollar', "Não inclui conversa telefônica com o revisor", false, false, 'list-group-item-light');
								break;
						}
						switch ($order_option5) {
							case 'enfase_forma':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-teal', 'fad fa-cube', "Ênfase na forma", false, false, false);
								break;

							case 'enfase_conteudo':
								$template_conteudo .= put_together_list_item('inactive', false, 'link-danger', 'fad fa-box-full', "Ênfase no conteúdo", false, false, false);
								break;

						}
						if ($order_option6 == 'revisao_diplomata') {
							$template_conteudo .= put_together_list_item('inactive', false, 'link-dark', 'fad fa-user-tie', "Revisor deve ser diplomata", false, false, false);
                        } else {
							$template_conteudo .= put_together_list_item('inactive', false, false, 'fad fa-user-tie', "Revisor não precisa ser diplomata", false, false, 'list-group-item-light');

						}

						$template_conteudo .= put_together_list_item('inactive', false, 'link-success', 'fad fa-usd-circle', "$order_option7 Créditos Ubwiki", false, false, false);
						$template_conteudo .= put_together_list_item('inactive', false, 'link-purple', 'fad fa-comment', "$order_comments", false, false, 'fst-italic');
						$template_conteudo .= put_together_list_item('inactive', false, false, "Link do texto:", false, false, 'list-group-item-info text-center');
						$template_conteudo .= return_list_item($order_pagina_id);
						$template_conteudo .= "</ul>";
					}
				}
				include 'templates/page_element.php';
			?>
        </div>
    </div>
</div>
<?php


	include 'templates/html_bottom.php';
?>
</body>
	
