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
						$template_conteudo .= "
	                        <div class='row d-flex justify-content-center'>
	                        	<p>Para comprar 250 créditos Ubwiki por R$200,00:</p>
	                        </div>
	                        <div class='row d-flex justify-content-center'>
                                <div id='product-component-1586095949391'></div>
							</div>
	                    ";
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
	
	<script type="text/javascript">
      /*<![CDATA[*/
      (function () {
          var scriptURL = 'https://sdks.shopifycdn.com/buy-button/latest/buy-button-storefront.min.js';
          if (window.ShopifyBuy) {
              if (window.ShopifyBuy.UI) {
                  ShopifyBuyInit();
              } else {
                  loadScript();
              }
          } else {
              loadScript();
          }
          function loadScript() {
              var script = document.createElement('script');
              script.async = true;
              script.src = scriptURL;
              (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
              script.onload = ShopifyBuyInit;
          }
          function ShopifyBuyInit() {
              var client = ShopifyBuy.buildClient({
                  domain: 'grupo-ubique.myshopify.com',
                  storefrontAccessToken: '184e4b8b490f4fc2516ec8bac628e0e3',
              });
              ShopifyBuy.UI.onReady(client).then(function (ui) {
                  ui.createComponent('product', {
                      id: '4618505650262',
                      node: document.getElementById('product-component-1586095949391'),
                      moneyFormat: 'R%24%20%7B%7Bamount_with_comma_separator%7D%7D',
                      options: {
                          "product": {
                              "styles": {
                                  "product": {
                                      "@media (min-width: 601px)": {
                                          "max-width": "calc(25% - 20px)",
                                          "margin-left": "20px",
                                          "margin-bottom": "50px"
                                      }
                                  },
                                  "button": {
                                      "font-family": "Roboto, sans-serif",
                                      "font-size": "18px",
                                      "padding-top": "17px",
                                      "padding-bottom": "17px",
                                      ":hover": {
                                          "background-color": "#589361"
                                      },
                                      "background-color": "#62a36c",
                                      ":focus": {
                                          "background-color": "#589361"
                                      },
                                      "padding-left": "80px",
                                      "padding-right": "80px"
                                  },
                                  "quantityInput": {
                                      "font-size": "18px",
                                      "padding-top": "17px",
                                      "padding-bottom": "17px"
                                  },
                                  "price": {
                                      "font-family": "Lato, sans-serif"
                                  },
                                  "compareAt": {
                                      "font-family": "Lato, sans-serif"
                                  },
                                  "unitPrice": {
                                      "font-family": "Lato, sans-serif"
                                  },
                                  "description": {
                                      "font-family": "Open Sans, sans-serif",
                                      "font-size": "15px"
                                  }
                              },
                              "buttonDestination": "checkout",
                              "contents": {
                                  "img": false,
                                  "title": false,
                                  "price": false
                              },
                              "text": {
                                  "button": "Buy now"
                              },
                              "googleFonts": [
                                  "Lato",
                                  "Open Sans",
                                  "Roboto"
                              ]
                          },
                          "productSet": {
                              "styles": {
                                  "products": {
                                      "@media (min-width: 601px)": {
                                          "margin-left": "-20px"
                                      }
                                  }
                              }
                          },
                          "modalProduct": {
                              "contents": {
                                  "img": false,
                                  "imgWithCarousel": true,
                                  "button": false,
                                  "buttonWithQuantity": true
                              },
                              "styles": {
                                  "product": {
                                      "@media (min-width: 601px)": {
                                          "max-width": "100%",
                                          "margin-left": "0px",
                                          "margin-bottom": "0px"
                                      }
                                  },
                                  "button": {
                                      "font-family": "Roboto, sans-serif",
                                      "font-size": "18px",
                                      "padding-top": "17px",
                                      "padding-bottom": "17px",
                                      ":hover": {
                                          "background-color": "#589361"
                                      },
                                      "background-color": "#62a36c",
                                      ":focus": {
                                          "background-color": "#589361"
                                      },
                                      "padding-left": "80px",
                                      "padding-right": "80px"
                                  },
                                  "quantityInput": {
                                      "font-size": "18px",
                                      "padding-top": "17px",
                                      "padding-bottom": "17px"
                                  }
                              },
                              "googleFonts": [
                                  "Roboto"
                              ],
                              "text": {
                                  "button": "Add to cart"
                              }
                          },
                          "cart": {
                              "styles": {
                                  "button": {
                                      "font-family": "Roboto, sans-serif",
                                      "font-size": "18px",
                                      "padding-top": "17px",
                                      "padding-bottom": "17px",
                                      ":hover": {
                                          "background-color": "#589361"
                                      },
                                      "background-color": "#62a36c",
                                      ":focus": {
                                          "background-color": "#589361"
                                      }
                                  }
                              },
                              "text": {
                                  "total": "Subtotal",
                                  "button": "Checkout"
                              },
                              "googleFonts": [
                                  "Roboto"
                              ]
                          },
                          "toggle": {
                              "styles": {
                                  "toggle": {
                                      "font-family": "Roboto, sans-serif",
                                      "background-color": "#62a36c",
                                      ":hover": {
                                          "background-color": "#589361"
                                      },
                                      ":focus": {
                                          "background-color": "#589361"
                                      }
                                  },
                                  "count": {
                                      "font-size": "18px"
                                  }
                              },
                              "googleFonts": [
                                  "Roboto"
                              ]
                          }
                      },
                  });
              });
          }
      })();
      /*]]>*/
	</script>
	
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