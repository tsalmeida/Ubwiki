<?php
	include 'engine.php';
	$pagina_tipo = 'biblioteca';
	include 'templates/html_head.php';
	
	$busca = false;
	if (isset($_POST['termos_busca'])) {
		$busca = $_POST['termos_busca'];
	}
	if (isset($_POST['listar_todas'])) {
		$busca = $_POST['listar_todas'];
	}
?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">

    <div class="row d-flex justify-content-between p-1">
        <div class="col"></div>
        <div class="col d-flex justify-content-center"><a href="javascript:void(0);" data-toggle="modal"
                                                          data-target="#modal_busca" class="text-dark"><i
                        class="fad fa-search fa-fw"></i></a></div>
			<?php
				echo "<div class='col d-flex justify-content-end'><form method='post'><button name='listar_todas' id='listar_todas' value='!all' class='$button_classes btn-info btn-sm m-0'>Listar todos</button></form></div>";
			?>
    </div>
</div>
<div class="container">
	<?php
		$template_titulo = 'Biblioteca';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						/*
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Adicionar item';
						$artefato_criacao = 'Pressione para adicionar um item a seu acervo';
						$artefato_tipo = 'nova_referencia';
						$artefato_modal = '#modal_add_elementos';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						*/
						
						if ($busca == false) {
							$acervo = false;
						} elseif ($busca == '!all') {
							$acervo = $conn->query("SELECT pagina_id, tipo, titulo, autor FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 ORDER BY titulo");
						} else {
							$acervo = $conn->query("SELECT pagina_id, tipo, titulo, autor FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 AND titulo LIKE '%$busca%' ORDER BY titulo");
						}
						
						if ($acervo != false) {
							$template_id = 'biblioteca_virtual';
							$template_titulo = 'Biblioteca';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							while ($acervo_item = $acervo->fetch_assoc()) {
								$acervo_item_pagina_id = $acervo_item['pagina_id'];
								$acervo_item_tipo = $acervo_item['tipo'];
								$acervo_item_titulo = $acervo_item['titulo'];
								$acervo_item_autor = $acervo_item['autor'];
								
								if ($acervo_item_tipo == 'topico') {
									continue;
								}
								if ($acervo_item_pagina_id == false) {
									continue;
								}
								
								$artefato_id = "elemento_$acervo_item_pagina_id";
								$artefato_titulo = $acervo_item_titulo;
								$artefato_subtitulo = $acervo_item_autor;
								$artefato_tipo = $acervo_item_tipo;
								$artefato_link = "pagina.php?pagina_id=$acervo_item_pagina_id";
								
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						} else {
							$template_id = 'biblioteca_mudancas_recentes';
							$template_titulo = 'Recentemente modificados';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							$elementos_contados = array();
							$modificados = $conn->query("SELECT pagina_id, verbete_html FROM Textos_arquivo WHERE pagina_tipo = 'elemento' ORDER BY id DESC");
							if ($modificados->num_rows > 0) {
								$count = 0;
								while ($modificado = $modificados->fetch_assoc()) {
									if ($count == 18) {
										break;
									}
									$modificado_pagina_id = $modificado['pagina_id'];
									$modificado_verbete = $modificado['verbete_html'];
									if ($modificado_verbete == false) {
										continue;
									}
									if (in_array($modificado_pagina_id, $elementos_contados)) {
										continue;
									}
									else {
										array_push($elementos_contados, $modificado_pagina_id);
										$count++;
									}
									$pagina_info = return_pagina_info($modificado_pagina_id);
									$modificado_pagina_titulo = $pagina_info[6];
									$modificado_elemento_id = $pagina_info[1];
									$modificado_elemento_info = return_elemento_info($modificado_elemento_id);
									$modificado_elemento_tipo = $modificado_elemento_info[3];
									$modificado_elemento_autor = $modificado_elemento_info[5];
									
									$artefato_id = "elemento_$modificado_elemento_id";
									$artefato_titulo = $modificado_pagina_titulo;
									$artefato_subtitulo = $modificado_elemento_autor;
									$artefato_tipo = $modificado_elemento_tipo;
									$artefato_link = "pagina.php?pagina_id=$modificado_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
									
								}
							include 'templates/page_element.php';
							}
						}
					?>
        </div>
    </div>
</div>
<?php
	$template_modal_div_id = 'modal_busca';
	$template_modal_titulo = 'Busca de referências';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='md-form'>
			<input type='text' id='termos_busca' name='termos_busca' class='form-control'>
			<label for='termos_busca'>Termos de busca</label>
		</div>
	";
	include 'templates/modal.php';
?>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
