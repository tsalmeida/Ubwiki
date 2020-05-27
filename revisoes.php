<?php
	$pagina_tipo = 'revisoes';
	$pagina_id = 1;
	include 'engine.php';
	if (($user_revisor == false) || ($user_email == false)) {
		header('Location:escritorio.php');
		exit();
	}
	
	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
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
              $orders = $conn->query("SELECT * FROM Orders WHERE tipo = 'review' and estado = 1");
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
                      $template_conteudo .= "<li class='list-group-item d-flex justify-content-center'><span><span class='{$order_user_avatar[1]}'><i class='fad {$order_user_avatar[0]} fa-fw fa-2x'></i></span> <strong>$order_user_apelido</strong></span></li>";

                      $template_conteudo .= put_together_list_item('inactive', false, 'text-info', 'fad', 'fa-tally', "<strong>Número de palavras:</strong> <span>$order_option1</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-danger', 'fad', 'fa-eraser', "<strong>Extensão:</strong> <span>$order_option2</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-warning', 'fad', 'fa-award', "<strong>Nota:</strong> <span>$order_option3</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-success', 'fab', 'fa-whatsapp', "<strong>Conversa:</strong> <span>$order_option4</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-info', 'fad', 'fa-cube', "<strong>Ênfase:</strong> <span>$order_option5</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-dark', 'fad', 'fa-user-tie', "<strong>Opção por diplomata:</strong> <span>$order_option6</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-success', 'fad', 'fa-usd-circle', "<strong>Preço:</strong> <span>R$ {$order_option7},00</span>", false, false, false);
                      $template_conteudo .= put_together_list_item('inactive', false, 'text-secondary', 'fad', 'fa-comment', "<strong>Comentários:</strong> <span>$order_comments</span>", false, false, false);

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
    include 'templates/footer.html';
    $mdb_select = true;
    include 'templates/html_bottom.php';
?>
</body>
	
