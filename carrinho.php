<?php
	
	//Public key (chave pública):TEST-ffcb8ddf-dd3d-42b5-aa04-aff72bfcc077
    //Access token (chave privada):TEST-5216550082263030-040313-0c3674cff483b90f9d02c7e51ac53825-509336387

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
						if ($user_tipo == 'admin') {
                          /*$template_conteudo .= "
                              <form id='wallet_deposit_form_hidden' class='border p-3 hidden' method='post'>
                                  <p class='pl-2 mb-2'>{$pagina_translated['Deposit value']}:</p>
                                  <div class='md-form input-group mb-3'>
                                                  <div class='input-group-prepend'>
                                                      <span class='input-group-text md-addon'>$</span>
                                                  </div>
                                                  <input type='number' class='form-control' id='wallet_deposit_value' name='wallet_deposit_value'>
                                                  <div class='input-group-append'>
                                                      <span class='input-group-text md-addon'>.00</span>
                                                  </div>
                                              </div>
                                  <div class='row d-flex justify-content-center'>
                                      <button class='$button_classes_info'>{$pagina_translated['Make deposit']}</button>
                                  </div>
                              </form>
                          ";
                          $template_conteudo .= "
                            <div class='row d-flex justify-content-center'>
                                        <button type='button' class='$all_buttons_classes btn-success' id='trigger_add_credits'>{$pagina_translated['Add credits to your wallet']}</button>
                            </div>";
                          */
						}
						$template_conteudo .= '
<form action="/processar_pagamento" method="post" id="pay" name="pay" >
    <fieldset>
        <p>
            <label for="description">Descrição</label>
            <input type="text" name="description" id="description" value="Ítem selecionado"/>
        </p>
        <p>
            <label for="transaction_amount">Valor a pagar</label>
            <input name="transaction_amount" id="transaction_amount" value="100"/>
        </p>
        <p>
            <label for="cardNumber">Número do cartão</label>
            <input type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </p>
        <p>
            <label for="cardholderName">Nome e sobrenome</label>
            <input type="text" id="cardholderName" data-checkout="cardholderName" />
        </p>
        <p>
            <label for="cardExpirationMonth">Mês de vencimento</label>
            <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </p>
        <p>
            <label for="cardExpirationYear">Ano de vencimento</label>
            <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </p>
        <p>
            <label for="securityCode">Código de segurança</label>
            <input type="text" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </p>
        <p>
            <label for="installments">Parcelas</label>
            <select id="installments" class="form-control" name="installments"></select>
        </p>
        <p>
            <label for="docType">Tipo de documento</label>
            <select id="docType" data-checkout="docType"></select>
        </p>
        <p>
            <label for="docNumber">Número do documento</label>
            <input type="text" id="docNumber" data-checkout="docNumber"/>
        </p>
        <p>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="test@test.com"/>
        </p>
        <input type="hidden" name="payment_method_id" id="payment_method_id"/>
        <input type="submit" value="Pagar"/>
    </fieldset>
</form>

	                    ';
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