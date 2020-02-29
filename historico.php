<?php
	
	$pagina_tipo = 'historico';
	$pagina_id = false;
	include 'engine.php';
	
	if (isset($_GET['texto_id'])) {
		$texto_id = $_GET['texto_id'];
		$texto_info = return_texto_info($texto_id);
		$texto_texto_pagina_id = $texto_info[12];
		$texto_page_id = $texto_info[9];
		$texto_tipo = $texto_info[1];
		if ($texto_tipo == 'verbete') {
			$pagina_id = $texto_info[9];
		} elseif ($texto_tipo == 'anotacoes') {
			$pagina_id = $texto_texto_pagina_id;
		}
	} else {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}
	
	if (isset($pagina_id)) {
		$pagina_info = return_pagina_info($pagina_id);
		if ($pagina_info != false) {
			$pagina_criacao = $pagina_info[0];
			$pagina_item_id = (int)$pagina_info[1];
			$pagina_tipo = $pagina_info[2];
			$pagina_estado = (int)$pagina_info[3];
			$pagina_compartilhamento = $pagina_info[4];
			$pagina_user_id = (int)$pagina_info[5];
			$pagina_titulo = $pagina_info[6];
			$pagina_etiqueta_id = (int)$pagina_info[7];
			$pagina_subtipo = $pagina_info[8];
			$pagina_publicacao = $pagina_info[9];
			$pagina_colaboracao = $pagina_info[10];
		} else {
			header('Location:ubwiki.php');
			exit();
		}
	} else {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}
	
	if ($pagina_tipo != 'texto') {
		$verbete_tipo = 'verbete';
	} else {
		$verbete_tipo = 'anotacoes';
	}
	
	$check_compartilhamento = return_compartilhamento($pagina_id, $user_id);
	if ($check_compartilhamento == false) {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}
	
	$edicao_coluna_esquerda = false;
	$edicao_coluna_direita = false;
	$edicao_coluna_esquerda_html = false;
	$edicao_coluna_direita_html = false;
	
	if (isset($_GET['l'])) {
		$edicao_coluna_esquerda = (int)$_GET['l'];
		$edicao_coluna_esquerda_html = return_texto_historico_html($edicao_coluna_esquerda);
	}
	
	if (isset($_GET['r'])) {
		$edicao_coluna_direita = (int)$_GET['r'];
		$edicao_coluna_direita_html = return_texto_historico_html($edicao_coluna_direita);
		require_once('HtmlDiff.php');
		$diff = new HtmlDiff($edicao_coluna_esquerda_html, $edicao_coluna_direita_html);
		$diff->build();
		$edicao_coluna_direita_html = $diff->getDifference();
	}
	
	include 'templates/html_head.php';
?>

    <body class="grey lighten-5">
		
		<?php
			include 'templates/navbar.php';
			include 'pagina/queries_notificacoes.php';
		?>

    <div class="container-fluid">
        <div class="row">
            <div class="col col-12 d-flex justify-content-end p-2">
                <a href="javascript:void(0);" class="<?php echo $notificacao_cor; ?> ml-1" data-toggle="modal"
                   data-target="#modal_notificacoes"><i class="fad <?php echo $notificacao_icone; ?> fa-fw"></i></a>
            </div>
        </div>
    </div>
    <div class="container">
			<?php
				$template_titulo = $pagina_titulo;
				$template_subtitulo = "Histórico / <a href='pagina.php?pagina_id=$pagina_id'>Página</a>";
				$template_titulo_context = true;
				$template_titulo_no_nav = false;
				include 'templates/titulo.php';
			?>
    </div>
    <div class="container-fluid">
        <div class="row d-flex justify-content-around">
            <div id="coluna_esquerda_historico" class="col-lg-6 px-5">
							<?php
								$template_id = 'historico_esquerda';
								if ($edicao_coluna_esquerda == false) {
									$template_titulo = 'Carregar versão';
								} else {
									$template_titulo = 'Versão mais antiga';
								}
                                $template_classes = 'elemento-anotacoes';
								$template_botoes = "<a class='text-info' data-toggle='modal' data-target='#modal_edicao_esquerda' ><i class='fad fa-archive fa-fw'></i></a>";
								$template_conteudo = false;
								if ($edicao_coluna_esquerda_html != false) {
									$template_conteudo .= $edicao_coluna_esquerda_html;
								} else {
									$template_conteudo .= "
										<p>Para carregar uma versão deste texto, pressione o botão no canto superior direito desta seção.</p>
									";
								}
								include 'templates/page_element.php'
							?>
            </div>
					<?php
						if ($edicao_coluna_esquerda != false) {
							echo "<div id='coluna_direita_historico' class='col-lg-6 px-5'>";
							
							$template_id = 'historico_direita';
							$template_titulo = 'Versão mais recente';
							$template_classes = 'elemento-anotacoes';
							$template_conteudo = false;
							if ($edicao_coluna_esquerda != false) {
								$template_botoes = "<a class='text-success' data-toggle='modal' data-target='#modal_edicao_direita' ><i class='fad fa-archive fa-fw'></i></a>";
							}
							if ($edicao_coluna_direita_html != false) {
								$template_conteudo .= $edicao_coluna_direita_html;
							} else {
								$template_conteudo .= "
							        <p>Para carregar uma versão mais recente deste texto, pressione o botão no campo superior direito desta seção.</p>
							    ";
							}
							include 'templates/page_element.php';
							
							echo "</div>";
						}
					
					?>
        </div>
    </div>
		<?php
			$template_modal_div_id = 'modal_edicao_esquerda';
			$template_modal_titulo = "Edição a carregar na coluna esquerda";
			$template_modal_show_buttons = false;
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
                <p>Para comparação, esta edição é necessariamente <strong>mais antiga</strong> do que a versão da coluna direita.</p>
                <p>Selecione, por exemplo, sua edição mais recente. Em seguida, você poderá a versão mais recente do texto, que será carregada na coluna direita.</p>
            ";
			$list_edicoes = $conn->query("SELECT id, criacao, user_id FROM Textos_arquivo WHERE texto_id = $texto_id ORDER BY id DESC");
			if ($list_edicoes->num_rows > 0) {
				$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
				while ($list_edicao = $list_edicoes->fetch_assoc()) {
					$list_edicao_id = $list_edicao['id'];
					$list_edicao_criacao = $list_edicao['criacao'];
					$list_edicao_user_id = $list_edicao['user_id'];
					$list_edicao_user_id_avatar_info = return_avatar($list_edicao_user_id);
					$list_edicao_user_id_avatar_icone = $list_edicao_user_id_avatar_info[0];
					$list_edicao_user_id_avatar_cor = $list_edicao_user_id_avatar_info[1];
					$list_edicao_user_apelido = return_apelido_user_id($list_edicao_user_id);
					
					if ($list_edicao_user_id == $user_id) {
						$option_to_delete = "<a href='javascript:void(0);' class='text-danger delete_edit force-size' value='$list_edicao_id' title='Apagar esta edição'><i class='fad fa-times-hexagon fa-fw'></i></a>";
					} else {
						$option_to_delete = "<span class='text-white force-size'><i class='fad fa-times-hexagon fa-fw'></i></span>";
					}
					
					$template_modal_body_conteudo .= "
						<li class='list-group-item list-group-item-light d-flex justify-content-between p-1 border-bottom-0 edicao_$list_edicao_id'>
                            <a class='text-info force-size' name='edicao_coluna_esquerda' href='historico.php?texto_id=$texto_id&l=$list_edicao_id'>
                                <i class='fad fa-eye fa-fw'></i>
                            </a>
                            <a href='pagina.php?user_id=$list_edicao_user_id' class='border rounded p-1 $list_edicao_user_id_avatar_cor'>
                                <i class='fad $list_edicao_user_id_avatar_icone fa-fw'></i> <span class='text-primary'>$list_edicao_user_apelido</span>
                            </a>
                            <span class='fontstack-mono'><small>$list_edicao_criacao</small> $option_to_delete</span>
                        </li>";
				}
				$template_modal_body_conteudo .= "</ul>";
			} else {
				$template_modal_body_conteudo .= "<p class='text-muted'><em>Não há versões registradas deste texto.</em></p>";
			}
			include 'templates/modal.php';
			
			if ($edicao_coluna_esquerda != false) {
				$template_modal_div_id = 'modal_edicao_direita';
				$template_modal_titulo = "Edição a carregar na coluna direita";
				$template_modal_show_buttons = false;
				$template_modal_body_conteudo = false;
				$template_modal_body_conteudo .= "
	                <p>Para comparação, esta edição é necessariamente <strong>mais recente</strong> do que a versão da coluna esquerda.</p>
	            ";
				mysqli_data_seek($list_edicoes, 0);
				if ($list_edicoes->num_rows > 0) {
					$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
					$edicao_recente_presente = false;
					while ($list_edicao = $list_edicoes->fetch_assoc()) {
						$list_edicao_id = $list_edicao['id'];
						if ($list_edicao_id <= $edicao_coluna_esquerda) {
							continue;
						}
						$list_edicao_criacao = $list_edicao['criacao'];
						$list_edicao_user_id = $list_edicao['user_id'];
						$list_edicao_user_id_avatar_info = return_avatar($list_edicao_user_id);
						$list_edicao_user_id_avatar_icone = $list_edicao_user_id_avatar_info[0];
						$list_edicao_user_id_avatar_cor = $list_edicao_user_id_avatar_info[1];
						$list_edicao_user_apelido = return_apelido_user_id($list_edicao_user_id);
						$edicao_recente_presente = true;
						
						$template_modal_body_conteudo .= "
                            <li class='list-group-item list-group-item-light mt-1 d-flex justify-content-between p-1 border-bottom-0 edicao_$list_edicao_id'>
                              <a class='text-success force-size' name='edicao_coluna_esquerda' href='historico.php?texto_id=$texto_id&l=$edicao_coluna_esquerda&r=$list_edicao_id'>
                                  <i class='fad fa-eye fa-fw'></i>
                              </a>
                              <a href='pagina.php?user_id=$list_edicao_user_id' class='border rounded p-1 ml-3'>
                                  <span class='$list_edicao_user_id_avatar_cor'>
                                      <i class='fad $list_edicao_user_id_avatar_icone fa-fw'></i>
                                  </span> $list_edicao_user_apelido
                              </a>
                              <small class='fontstack-mono mt-2'>$list_edicao_criacao</small>
                            </li>
                        ";
					}
					$template_modal_body_conteudo .= "</ul>";
					if ($edicao_recente_presente == false) {
						$template_modal_body_conteudo .= "<p class='text-muted'><em>Não há edições mais recentes do que a atualmente selecionada para a coluna esquerda.</em></p>";
					}
				}
				include 'templates/modal.php';
			}
			
			include 'pagina/modal_notificacoes.php';
		
		?>
    </body>
    <script type="text/javascript">
        $(document).on('click', '.delete_edit', function () {
            delete_this_edit = $(this).attr('value');
            $.post('engine.php', {
                'delete_this_edit': delete_this_edit
            }, function (data) {
                if (data == true) {
                    $('.edicao_' + delete_this_edit).addClass('hidden');
                }
            });
        })
    </script>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>