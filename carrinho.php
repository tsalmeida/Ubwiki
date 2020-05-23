<?php

	include 'engine.php';
	
	$pagina_tipo = 'carrinho';
	
	
	include 'templates/html_head.php';

?>
    <body class="grey lighten-5">
		<?php
			include 'templates/navbar.php';
		?>
    <div class="container-fluid">
			<?php
				$template_titulo = 'Seu carrinho';
				$template_subtitulo = false;
				$template_titulo_context = true;
				include 'templates/titulo.php';
			?>
        <div class="row d-flex justify-content-center">
					<?php
						echo "<div id='coluna_unica' class='$coluna_classes'>";
						
						
						$template_id = 'secao_wallet';
						$template_titulo = $pagina_translated['Your wallet'];
						$template_conteudo = false;
						if ($user_wallet != false) {
							$template_conteudo .= "
	                            <ul class='list-group'>
	                                <li class='list-group-item list-group-item-light'>{$pagina_translated['Credits in your wallet:']} $user_wallet</li>
                                </ul>
	                        ";
						} else {
							$template_conteudo .= "
			                    <p class='text-muted'><em>{$pagina_translated['Your wallet is empty.']}</em></p>
		                    ";
						}

						$hide_and_show_wallet_form = true;

						include 'templates/page_element.php';
						
						$template_id = 'carrinho_conteudo';
						$template_titulo = $pagina_translated['Conteúdo do seu carrinho'];
						$template_conteudo_class = 'p-limit';
						$template_conteudo = false;
						
						$produtos = $conn->query("SELECT produto_pagina_id FROM Carrinho WHERE user_id = $user_id AND estado = 1");
						if ($produtos->num_rows > 0) {
							$template_conteudo .= "<p>{$pagina_translated['Conteúdo do seu carrinho']}:</p>";
							$template_conteudo .= "<ul class='list-group mb-3'>";
							$soma = 0;
							while ($produto = $produtos->fetch_assoc()) {
								$produto_pagina_id = $produto['produto_pagina_id'];
								$produto_info = return_produto_info($produto_pagina_id);
								$produto_titulo = $produto_info[0];
								$produto_preco = $produto_info[2];
								$soma = $soma + $produto_preco;
								$produto_autor = $produto_info[3];
								$template_conteudo .= "<li class='list-group-item d-flex justify-content-between p-1'><span>R$ $produto_preco</span> <span>\"$produto_titulo\", de $produto_autor</span><span><button type='button' value='$produto_pagina_id' class='danger-color-dark text-white remover-carrinho border-0 rounded btn-sm m-0'>Remover</button></span></li>";
							}
							$template_conteudo .= "<li class='list-group-item list-group-item-info p-1'>Total: R$ $soma</li>";
							$template_conteudo .= "</ul>";
							$template_conteudo .= "<li class='list-group-item'>Para pagar, é necessário adicionar créditos à sua carteira.</li>";
						} else {
							$template_conteudo .= "<p class='text-muted'><em>{$pagina_translated['Não há produtos em seu carrinho.']}</em></p>";
						}
						
						include 'templates/page_element.php';
						
						echo "</div>";
					?>
        </div>
    </div>
    </body>
	
<?php
	
	echo "
		<script type='text/javascript'>
			$(document).on('click', '.remover-carrinho', function() {
				remover_pagina_id = $(this).attr('value');
				$.post('engine.php', {
				   'remover_carrinho_pagina_id': remover_pagina_id
				   }, function(data) {
				   	if (data != 0) {
				   	    window.location.replace('carrinho.php');
				   	} else {
				   	    alert('{$pagina_translated['Aconteceu algum problema.']}');
				   	}
				});
			});
		</script>
	";
	
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>