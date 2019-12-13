<?php
	
	include 'engine.php';
	
	if (isset($_GET['id'])) {
		$elemento_id = $_GET['id'];
	}
	
	$nao_contar = false;
	
	if (isset($_POST['submit_elemento_dados'])) {
		$elemento_mudanca_estado = 0;
		if (isset($_POST['elemento_mudanca_estado'])) {
			$elemento_mudanca_estado = 1;
		}
		$elemento_novo_titulo = "NULL";
		$elemento_novo_autor = "NULL";
		$elemento_novo_capitulo = "NULL";
		$elemento_novo_ano = "NULL";
		if (isset($_POST['elemento_novo_titulo'])) {
			$elemento_novo_titulo = $_POST['elemento_novo_titulo'];
			$elemento_novo_titulo = mysqli_real_escape_string($conn, $elemento_novo_titulo);
			if ($elemento_novo_titulo == '') {
				$elemento_novo_titulo = "NULL";
			} else {
				$elemento_novo_titulo = "'$elemento_novo_titulo'";
			}
		}
		if (isset($_POST['elemento_novo_autor'])) {
			$elemento_novo_autor = $_POST['elemento_novo_autor'];
			$elemento_novo_autor = mysqli_real_escape_string($conn, $elemento_novo_autor);
			if ($elemento_novo_autor == '') {
				$elemento_novo_autor = "NULL";
			} else {
				$elemento_novo_autor = "'$elemento_novo_autor'";
			}
		}
		if (isset($_POST['elemento_novo_capitulo'])) {
			$elemento_novo_capitulo = $_POST['elemento_novo_capitulo'];
			$elemento_novo_capitulo = mysqli_real_escape_string($conn, $elemento_novo_capitulo);
			if ($elemento_novo_capitulo == '') {
				$elemento_novo_capitulo = "NULL";
			} else {
				$elemento_novo_capitulo = "'$elemento_novo_capitulo'";
			}
		}
		if (isset($_POST['elemento_novo_ano'])) {
			$elemento_novo_ano = $_POST['elemento_novo_ano'];
			if ($elemento_novo_ano == '') {
				$elemento_novo_ano = "NULL";
			}
		}
		$update = $conn->query("UPDATE Elementos SET estado = $elemento_mudanca_estado, titulo = $elemento_novo_titulo, autor = $elemento_novo_autor, capitulo = $elemento_novo_capitulo, ano = $elemento_novo_ano WHERE id = $elemento_id");
		update_etiqueta_elemento($elemento_id, $user_id);
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_dados')");
		$nao_contar = true;
	}
	
	$result = $conn->query("SELECT estado, criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$estado_elemento = $row['estado'];
			$criacao_elemento = $row['criacao'];
			$tipo_elemento = $row['tipo'];
			$titulo_elemento = $row['titulo'];
			$autor_elemento = $row['autor'];
			$capitulo_elemento = $row['capitulo'];
			$ano_elemento = $row['ano'];
			$link_elemento = $row['link'];
			$iframe_elemento = $row['iframe'];
			$arquivo_elemento = $row['arquivo'];
			$user_elemento_id = $row['user_id'];
			if (($tipo_elemento == 'imagem_privada') && ($user_elemento_id != $user_id)) {
				header('Location:index.php');
			}
			$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_elemento_id");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$user_apelido_elemento = $row['apelido'];
				}
			} else {
				$user_apelido_elemeto = "(usuário não-identificado)";
			}
			break;
		}
	}
	
	$elemento_bookmark = false;
	$bookmark = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND elemento_id = $elemento_id AND active = 1");
	if ($bookmark->num_rows > 0) {
		while ($row = $bookmark->fetch_assoc()) {
			$elemento_bookmark = $row['bookmark'];
			break;
		}
	}
	
	// FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$novo_comentario = mysqli_real_escape_string($conn, $novo_comentario);
		$insert = $conn->query("INSERT INTO Forum (user_id, page_id, page_tipo, comentario)  VALUES ($user_id, $elemento_id, 'elemento', '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_forum')");
		$nao_contar = true;
	}
	
	// SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES SEÇÕES
	
	if (isset($_POST['trigger_nova_secao'])) {
		$nova_secao_titulo = $_POST['elemento_nova_secao'];
		$nova_secao_titulo = mysqli_real_escape_string($conn, $nova_secao_titulo);
		$nova_secao_ordem = (int)$_POST['elemento_nova_secao_ordem'];
		if ($conn->query("INSERT INTO Textos (tipo, titulo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('secao_elemento', '$nova_secao_titulo', $elemento_id, FALSE, FALSE, FALSE, $user_id)") === true) {
			$nova_secao_texto_id = $conn->insert_id;
			$conn->query("INSERT INTO Secoes (elemento_id, titulo, ordem, user_id, texto_id) VALUES ($elemento_id, '$nova_secao_titulo', $nova_secao_ordem, $user_id, $nova_secao_texto_id)");
			$nova_etiqueta_titulo = "$titulo_elemento // $nova_secao_titulo";
			$nova_etiqueta_titulo = mysqli_real_escape_string($conn, $nova_etiqueta_titulo);
			$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$nova_etiqueta_titulo', $user_id)");
		}
		$nao_contar = true;
	}
	$secoes = $conn->query("SELECT ordem, titulo, texto_id, estado_texto FROM Secoes WHERE elemento_id = $elemento_id ORDER BY ordem");
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var elemento_id=$elemento_id;
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';
	
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $elemento_id, 'elemento', '$tipo_elemento')");
	}

?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid bg-white">
    <div class='row'>
        <div class='col-lg-8 col-sm-12'></div>
        <div class='col-lg-4 col-sm-12'>
            <div class='text-right py-2'>
            <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
	                    $comments = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE page_id = $elemento_id AND page_tipo = 'elemento'");
	                    if ($comments->num_rows == 0) {
		                    echo "
                                <a href='javascript:void(0);'>
                                    <i class='fal fa-comments-alt fa-fw'></i>
                                </a>
                            ";
	                    } else {
		                    echo "
                                <a href='javascript:void(0);'>
		                            <span class='text-secondary'>
                                        <i class='fas fa-comments-alt fa-fw'></i>
                                    </span>
                                </a>
		                    ";
	                    }
                    ?>
            </span>
							<?php
								if ($elemento_bookmark == false) {
									echo "
              <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$elemento_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$elemento_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								} else {
									echo "
              <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$elemento_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$elemento_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								}
							?>
            </div>
        </div>
    </div>
</div>
<div id='page_height' class='container-fluid'>
	<?php
		$template_titulo = $titulo_elemento;
		$template_titulo_context = true;
		$template_titulo_no_nav = false;
		include 'templates/titulo.php'
	?>
    <div class='row justify-content-around'>
        <div id='coluna_esquerda' class='<?php echo $coluna_classes; ?>'>
					<?php
						if (($tipo_elemento == 'imagem') || ($tipo_elemento == 'imagem_privada')) {
							$template_id = 'imagem_div';
							$template_titulo = 'Imagem';
							$template_botoes = false;
							$template_conteudo = "<a href='../imagens/verbetes/$arquivo_elemento' target='_blank'><img class='imagem_pagina border' src='../imagens/verbetes/$arquivo_elemento'></img></a>";
							include 'templates/page_element.php';
						} elseif ($tipo_elemento == 'video') {
							$template_div = 'video_div';
							$template_titulo = 'Vídeo';
							$template_botoes = false;
							$iframe_elemento = base64_decode($iframe_elemento);
							$template_conteudo = $iframe_elemento;
							$template_conteudo_class = 'text-center';
							include 'templates/page_element.php';
						}
						
						if ($estado_elemento == true) {
							$estado_elemento_visivel = 'publicado';
						} else {
							$estado_elemento_visivel = 'removido';
						}
						
						$dados_elemento = false;
						if ($tipo_elemento == 'imagem_privada') {
							$dados_elemento .= "<p>Esta imagem é privada e não pode ser vista por outros usuários.</p>";
						}
						$dados_elemento .= "
                            <ul class='list-group'>
						        <li class='list-group-item'><strong>Criado em:</strong> $criacao_elemento</li>";
						if ($tipo_elemento != 'imagem_privada') {
							$dados_elemento .= "<li class='list-group-item'><strong>Estado de publicação:</strong> $estado_elemento_visivel</li>";
						}
						if ($titulo_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Título:</strong> $titulo_elemento</li>";
						}
						if ($autor_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Autor:</strong> $autor_elemento</li>";
						}
						if ($capitulo_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><strong>Capítulo:</strong> $capitulo_elemento</li>";
						}
						if ($ano_elemento != 0) {
							$dados_elemento .= "<li class='list-group-item'><strong>Ano:</strong> $ano_elemento</li>";
						}
						if ($link_elemento != false) {
							$dados_elemento .= "<li class='list-group-item'><a href='$link_elemento' target='_blank'>Link original</a></li>";
						}
						$dados_elemento .= "<li class='list-group-item'>Adicionado pelo usuário <strong><a href='perfil.php?pub_user_id=$user_elemento_id' target='_blank'>$user_apelido_elemento</a></strong></li>";
						$dados_elemento .= "</ul>";
						
						$template_id = 'dados_elemento_div';
						$template_titulo = 'Dados';
						$template_botoes = "
                                  <a data-toggle='modal' data-target='#modal_elemento_form' href=''>
                                    <i class='fal fa-edit fa-fw'></i>
                                  </a>
                            ";
						$template_conteudo = false;
						$template_conteudo .= $dados_elemento;
						include 'templates/page_element.php';
						
						$template_id = 'verbete_elemento';
						$template_titulo = 'Apresentação';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para escrever a apresentação deste item.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						$template_div = 'partes_elemento';
						$template_titulo = 'Seções';
						$template_botoes = "
                            <a data-toggle='modal' data-target='#modal_partes_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
                        ";
						$template_conteudo = false;
						
						if ($secoes->num_rows > 0) {
							$template_conteudo .= "<p>Seções identificadas deste elemento e seus textos correspondentes:</p>";
							$template_conteudo .= "<ul class='list-group'>";
							while ($secao = $secoes->fetch_assoc()) {
								$secao_titulo = $secao['titulo'];
								$secao_texto_id = $secao['texto_id'];
								$secao_estado_texto = $secao['estado_texto'];
								$secao_estado_icone = return_estado_icone($secao_estado_texto, 'elemento');
								$template_conteudo .=
							    "
								  <a href='edicao_textos.php?texto_id=$secao_texto_id' target='_blank' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
							          $secao_titulo
									  <span class='ml-3 badge grey lighten-3 text-dark badge-pill z-depth-0'>
                                          <i class='fa $secao_estado_icone'></i>
                                      </span>
							      </a>
						        ";
							}
							$template_conteudo .= "</ul>";
						} else {
							$template_conteudo .= "<p>Não há seções identificadas deste elemento.</p>";
						}
						
						include 'templates/page_element.php';
					
					?>
        </div>
        <div id='coluna_direita' class='<?php echo $coluna_classes; ?> anotacoes_collapse collapse show'>
					
					<?php
						
						$template_id = 'anotacoes_elemento';
						$template_titulo = 'Nota de estudos';
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					
					?>

        </div>


    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
<?php
	$template_modal_div_id = 'modal_elemento_form';
	$template_modal_titulo = 'Alterar dados do elemento';
	$template_modal_body_conteudo = false;
	
	$estado_elemento_checkbox = false;
	if ($estado_elemento == true) {
		$estado_elemento_checkbox = 'checked';
	}
	if ($tipo_elemento != 'imagem_privada') {
		$template_modal_body_conteudo .= "
          <div class='form-check pl-0'>
              <input type='checkbox' class='form-check-input' id='elemento_mudanca_estado' name='elemento_mudanca_estado' $estado_elemento_checkbox>
              <label class='form-check-label' for='elemento_mudanca_estado'>Adequado para publicação</label>
          </div>";
	} else {
		$template_modal_body_conteudo .= "
			<input type='hidden' name='elemento_mudanca_estado' value='true'>
		";
	}
	
	$template_modal_body_conteudo .= "
		<div class='md-form mb-2'>
            <input type='text' id='elemento_novo_titulo' name='elemento_novo_titulo' class='form-control' value='$titulo_elemento'>
            <label for='elemento_novo_titulo'>Título</label>
        </div>
    
        <div class='md-form mb-2'>
            <input type='text' id='elemento_novo_autor' name='elemento_novo_autor' class='form-control' value='$autor_elemento'>
            <label for='elemento_novo_autor'>Autor</label>
        </div>";
	$template_modal_body_conteudo .= "
		  <div class='md-form mb-2'>
              <input type='number' id='elemento_novo_ano' name='elemento_novo_ano' class='form-control' value='$ano_elemento'>
              <label for='elemento_novo_ano'>Ano</label>
          </div>
	  ";
	
	$template_modal_submit_name = 'submit_elemento_dados';
	
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	if ($comments->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $comments->fetch_assoc()) {
			$timestamp_comentario = $row['timestamp'];
			$texto_comentario = $row['comentario'];
			$autor_comentario_id = $row['user_id'];
			$result2 = $conn->query("SELECT apelido FROM usuarios WHERE id = $autor_comentario_id");
			while ($row2 = $result2->fetch_assoc()) {
				$autor_comentario_apelido = $row2['apelido'];
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p><strong><a href='perfil.php?pub_user_id=$autor_comentario_id' target='_blank'>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este elemento.</strong></p>";
	}
	$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id AND apelido IS NOT NULL");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <textarea id='novo_comentario' name='novo_comentario' class='md-textarea form-control' rows='3' required></textarea>
                    <label for='novo_comentario'>Adicione seu comentário:</label>
                </div>
            ";
	} else {
		$template_modal_body_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em <a href='escritorio.php'>seu escritório</a>.</strong></p>";
	}
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_partes_form';
	$template_modal_titulo = 'Adicionar seção';
	$template_modal_submit_name = 'trigger_nova_secao';
	$template_modal_body_conteudo = "
		<p>Por favor, tome cuidado para que não haja duplicidade. Se possível, é preferencial que todas as seções sejam acrescentadas na ordem em que aparecem na edição de que você dispõe. Ao inserir a ordem da nova seção, por favor considere Introdução, Prefácio etc. Se houver mais de um prefácio, considere a possibilidade de consolidá-los em apenas uma seção, por exemplo, no caso de prefácios a edições que somente mencionam adições ou correções realizadas. Seções de agradecimento, caso nada incluam de especialmente relevante, podem ser ignoradas, assim como tabelas de referência, listas de anexos, glossários e seções afim.</p>
		<p>Exemplos de seções adequadas: \"Capítulo 1\", \"Capítulo 2: Título do Capítulo\", \"Parte 1: As Origens\", \"Introdução\".</p>
		<p>É possível determinar a ordem como \"0\". É preferível usar essa opção para elementos anteriores ao primeiro capítulo, como Introdução e Prefácio, pois o primeiro capítulo terá a ordem \"1\", o segundo a ordem \"2\" etc.</p>
		<div class='md-form mb-2'>
			<input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
            <label for='elemento_nova_secao'>Título da nova seção</label>
		</div>
		<div class='md-form mb-2'>
			<input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
            <label for='elemento_nova_secao_ordem'>Posição da nova seção</label>
		</div>
	";
	
	mysqli_data_seek($secoes, 0);
	if ($secoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
		<h3>Seções registradas para este elemento:</h3>
		<ul class='list-group'>
    ";
		while ($secao = $secoes->fetch_assoc()) {
			$secao_ordem = $secao['ordem'];
			$secao_titulo = $secao['titulo'];
			$secao_texto_id = $secao['texto_id'];
			$template_modal_body_conteudo .= "<a href='edicao_textos.php?texto_id=$secao_texto_id' target='_blank'><li class='list-group-item list-group-item-action'>$secao_ordem: $secao_titulo</li></a>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	include 'templates/modal.php';

?>
</body>
<?php
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_elemento';
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/footer.html';
?>
</html>
