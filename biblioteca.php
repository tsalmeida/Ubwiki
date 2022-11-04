<?php
    //TODO: Não deveria haver a opção "salvar" na busca de referências.

	include 'engine.php';
	$pagina_tipo = 'biblioteca';
	$pagina_id = 1;
	include 'templates/html_head.php';
	
	$busca = false;
	if (isset($_POST['termos_busca'])) {
		$busca = $_POST['termos_busca'];
	}
	if (isset($_POST['trigger_listar_todas'])) {
		$busca = $_POST['trigger_listar_todas'];
	}
	$trigger_subcategoria = false;
	if (isset($_POST['trigger_subcategoria'])) {
		$trigger_subcategoria = $_POST['trigger_subcategoria'];
	}
	
	include 'pagina/shared_issets.php';

?>
<body class="bg-light">
<?php
	include 'templates/navbar.php';
	if ($user_id != false) {
	    $modal_add_elementos = '#modal_add_elementos';
    } else {
	    $modal_add_elementos = '#modal_login';
    }
?>
<div class="container-fluid">
    <div class="row d-flex justify-content-between p-1">
        <div class="col">
            <div class="row d-flex justify-content-start">
                <a data-bs-toggle='modal' data-bs-target='<?php echo $modal_add_elementos; ?>' class='link-success ms-1 mostrar_categorias'
                   title='<?php echo $pagina_translated['Adicionar item à biblioteca']; ?>' href='javascript:void(0);'><i
                            class='fad fa-plus-circle fa-2x fa-fw'></i></a>
            </div>
        </div>
        <div class="col d-flex justify-content-center">
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal_busca" class="text-dark"><i
                        class="fad fa-search fa-fw"></i></a></div>
        <div class='col d-flex justify-content-end'>
            <a href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#modal_listar_itens'
               class='ms-1 link-teal esconder_subcategorias'><i class='fad fa-eye fa-2x fa-fw'></i></a>
        </div>
    </div>
</div>
<div class="container">
	<?php
		$template_titulo = $pagina_translated['library'];
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						$acervo = false;
						if ($busca == '!all') {
						    $query = prepare_query("SELECT id, pagina_id, tipo, subtipo, titulo, autor, iframe FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 ORDER BY titulo");
							$acervo = $conn->query($query);
						} elseif ($trigger_subcategoria != false) {
						    $query = prepare_query("SELECT id, pagina_id, tipo, subtipo, titulo, autor, iframe FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 AND subtipo = '$trigger_subcategoria' ORDER BY titulo");
							$acervo = $conn->query($query);
						} elseif ($busca != false) {
						    $query = prepare_query("SELECT id, pagina_id, tipo, subtipo, titulo, autor, iframe FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 AND titulo LIKE '%$busca%' ORDER BY titulo");
							$acervo = $conn->query($query);
						}
						if ($acervo != false) {
							$template_id = 'biblioteca_virtual';
							$template_titulo = $pagina_translated['library'];
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							/*
							$artefato_id = 'adicionar_item_biblioteca';
							$artefato_titulo = $pagina_translated['Adicionar item'];
							$fa_icone = 'fa-plus-circle';
							$fa_color = 'link-teal';
							$artefato_criacao = $pagina_translated['Pressione para adicionar um item à biblioteca'];
							$artefato_tipo = 'nova_referencia';
							$artefato_modal = $modal_add_elementos;
							$artefato_link = false;
							$template_conteudo .= include 'templates/artefato_item.php';*/
							
							while ($acervo_item = $acervo->fetch_assoc()) {
								$acervo_item_id = $acervo_item['id'];
								$acervo_item_pagina_id = $acervo_item['pagina_id'];
								$acervo_item_tipo = $acervo_item['tipo'];
								$acervo_item_subtipo = $acervo_item['subtipo'];
								$acervo_item_titulo = $acervo_item['titulo'];
								$acervo_item_autor = $acervo_item['autor'];
								$acervo_item_iframe = $acervo_item['iframe'];
								
								if ($acervo_item_tipo == 'topico') {
									continue;
								} elseif ($acervo_item_tipo == 'wikipedia') {
									continue;
								} elseif ($acervo_item_tipo == false) {
									continue;
								}
								if ($acervo_item_pagina_id == false) {
									$acervo_item_pagina_id = return_pagina_id($acervo_item_id, 'elemento');
								}
								
								$artefato_id = "elemento_$acervo_item_pagina_id";
								$artefato_titulo = $acervo_item_titulo;
								$artefato_subtitulo = $acervo_item_autor;
								$artefato_tipo = 'acervo_item';
								$artefato_info = return_icone_subtipo($acervo_item_tipo, $acervo_item_subtipo);
								$fa_icone = $artefato_info[0];
								$fa_color = $artefato_info[1];
								
								$artefato_link = "pagina.php?pagina_id=$acervo_item_pagina_id";
								
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						} else {
							
							$template_id = 'recentemente_adicionados';
							$template_titulo = $pagina_translated['Recentemente adicionados'];
							$template_conteudo_class = 'justify-content-start';
							$template_botoes = "
								  <a data-bs-toggle='modal' data-bs-target='$modal_add_elementos' class='link-success ms-1 mostrar_categorias' title='{$pagina_translated['Adicionar item à biblioteca']}' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
							$template_conteudo_no_col = true;
							$template_conteudo = false;

							$query = prepare_query("SELECT id, pagina_id, titulo, autor, tipo, subtipo FROM Elementos WHERE compartilhamento IS NULL ORDER BY id DESC");
							$criados = $conn->query($query);
							if ($criados->num_rows > 0) {
								$count = 0;
								while ($criado = $criados->fetch_assoc()) {
									$criado_id = $criado['id'];
									$criado_pagina_id = $criado['pagina_id'];
									if ($criado_pagina_id == false) {
										$criado_pagina_id = return_pagina_id($criado_id, 'elemento');
									}
									$criado_titulo = $criado['titulo'];
									$criado_autor = $criado['autor'];
									$criado_tipo = $criado['tipo'];
									$criado_subtipo = $criado['subtipo'];
									
									if ($criado_tipo == 'wikipedia') {
										continue;
									}
									$count++;
									if ($count > 12) {
										break;
									}
									$artefato_id = "elemento_$criado_pagina_id";
									$artefato_titulo = $criado_titulo;
									$artefato_subtitulo = $criado_autor;
									$artefato_tipo = $criado_tipo;
									
									$artefato_info = return_icone_subtipo($criado_tipo, $criado_subtipo);
									$fa_icone = $artefato_info[0];
									$fa_color = $artefato_info[1];
									
									$artefato_link = "pagina.php?pagina_id=$criado_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
								}
								include 'templates/page_element.php';
							}
							
							$template_id = 'biblioteca_mudancas_recentes';
							$template_titulo = $pagina_translated['Recentemente modificados'];
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							$template_botoes = "
								<a data-bs-toggle='modal' data-bs-target='$modal_add_elementos' class='link-success ms-1 mostrar_categorias' title='Adicionar item à biblioteca' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
							";
							/*
							$artefato_id = 'adicionar_item_biblioteca';
							$artefato_titulo = 'Adicionar item';
							$artefato_criacao = 'Pressione para adicionar um item à biblioteca';
							$artefato_tipo = 'nova_referencia';
							$artefato_modal = '#modal_add_elementos';
							$artefato_link = false;
							$template_conteudo .= include 'templates/artefato_item.php';*/
							
							$elementos_contados = array();
							$query = prepare_query("SELECT pagina_id, verbete_html FROM Textos_arquivo WHERE pagina_tipo = 'elemento' AND compartilhamento IS NULL ORDER BY id DESC");
							$modificados = $conn->query($query);
							if ($modificados->num_rows > 0) {
								$count = 0;
								while ($modificado = $modificados->fetch_assoc()) {
									if ($count == 12) {
										break;
									}
									$modificado_pagina_id = $modificado['pagina_id'];
									$modificado_verbete = $modificado['verbete_html'];
									if ($modificado_verbete == false) {
										continue;
									}
									if (in_array($modificado_pagina_id, $elementos_contados)) {
										continue;
									} else {
										array_push($elementos_contados, $modificado_pagina_id);
										$count++;
									}
									$pagina_info = return_pagina_info($modificado_pagina_id, true);
									$modificado_pagina_titulo = $pagina_info[6];
									$modificado_elemento_id = $pagina_info[1];
									$modificado_elemento_info = return_elemento_info($modificado_elemento_id);
									$modificado_elemento_tipo = $modificado_elemento_info[3];
									$modificado_elemento_subtipo = $modificado_elemento_info[18];
									
									$artefato_info = return_icone_subtipo($modificado_elemento_tipo, $modificado_elemento_subtipo);
									$fa_icone = $artefato_info[0];
									$fa_color = $artefato_info[1];
									
									if ($modificado_elemento_tipo == false) {
										continue;
									}
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
	$template_modal_titulo = $pagina_translated['Busca de referências'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='md-form'>
			<input type='text' id='termos_busca' name='termos_busca' class='form-control'>
			<label for='termos_busca'>Termos de busca</label>
		</div>
	";
	include 'templates/modal.php';
	
	
	$template_modal_div_id = 'modal_listar_itens';
	$template_modal_titulo = $pagina_translated['Listar itens'];
	$lista_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<form method='post' class='row d-flex justify-content-center'>";
	
	$artefato_tipo = 'listar_todas';
	$artefato_titulo = $pagina_translated['Todos os itens'];
	$artefato_col_limit = $lista_col_limit;
	$artefato_button = '!all';
	$artefato_name = 'trigger_listar_todas';
	$artefato_class = 'mb-1';
	$fa_icone = 'fa-asterisk';
	$fa_color = 'link-purple';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</form>";
	
	$template_modal_body_conteudo .= "<div class='row d-flex justify-content-start rounded bg-light mb-3'>";
	
	$artefato_tipo = 'listar_referencias';
	$artefato_titulo = $pagina_translated['Material de leitura'];
	$artefato_class = 'selecionar_listar';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-glasses';
	$fa_color = 'link-success';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'ativo_listar_referencias';
	$artefato_titulo = $pagina_translated['Material de leitura'];
	$artefato_class = 'ativo_listar hidden';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-glasses';
	$artefato_icone_background = 'bg-success';
	$fa_color = 'text-white';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'listar_audio';
	$artefato_titulo = $pagina_translated['Áudio'];
	$artefato_class = 'selecionar_listar';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-volume-up';
	$fa_color = 'link-warning';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'ativo_listar_audio';
	$artefato_titulo = $pagina_translated['Áudio'];
	$artefato_class = 'ativo_listar hidden';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-volume-up';
	$artefato_icone_background = 'bg-warning';
	$fa_color = 'text-white';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'listar_imagens';
	$artefato_titulo = $pagina_translated['Imagens'];
	$artefato_class = 'selecionar_listar';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-images';
	$fa_color = 'link-danger';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'ativo_listar_imagens';
	$artefato_titulo = $pagina_translated['Imagens'];
	$artefato_class = 'ativo_listar hidden';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-images';
	$artefato_icone_background = 'bg-danger';
	$fa_color = 'text-white';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'listar_video';
	$artefato_titulo = $pagina_translated['Vídeo'];
	$artefato_class = 'selecionar_listar';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-play-circle';
	$fa_color = 'link-teal';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'ativo_listar_video';
	$artefato_titulo = $pagina_translated['Vídeo'];
	$artefato_class = 'ativo_listar hidden';
	$artefato_col_limit = $lista_col_limit;
	$fa_icone = 'fa-play-circle';
	$artefato_icone_background = 'bg-info';
	$fa_color = 'text-white';
	$fa_size = 'fa-3x';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</div>";
	
	$template_modal_body_conteudo .= "<form method='post' class='row d-flex justify-content-center border p-1'>";
	include 'pagina/elemento_subtipos.php';
	$template_modal_body_conteudo .= "</form>";
	
	include 'templates/modal.php';
	
	include 'pagina/modal_add_elemento.php';
	include 'pagina/modal_adicionar_imagem.php';
	include 'pagina/youtube.php';
	
	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
	include 'pagina/modal_languages.php';


?>
</body>
<script type="text/javascript">
    $('.subcategorias').addClass('hidden');
    $(document).on('click', '.mostrar_categorias', function() {
        $('.subcategorias').removeClass('hidden');
    })
    $(document).on('click', '.esconder_subcategorias', function() {
        $('.subcategorias').addClass('hidden');
    })
    $(document).on('click', '#trigger_listar_referencias', function () {
        $('.ativo_listar').addClass('hidden');
        $('.selecionar_listar').removeClass('hidden');
        $('#artefato_listar_referencias').addClass('hidden');
        $('#artefato_ativo_listar_referencias').removeClass('hidden');
        $('.subcategorias').addClass('hidden');
        $('.subcategoria_referencia').removeClass('hidden');
    });
    $(document).on('click', '#trigger_listar_audio', function () {
        $('.ativo_listar').addClass('hidden');
        $('.selecionar_listar').removeClass('hidden');
        $('#artefato_listar_audio').addClass('hidden');
        $('#artefato_ativo_listar_audio').removeClass('hidden');
        $('.subcategorias').addClass('hidden');
        $('.subcategoria_album_musica').removeClass('hidden');
    });
    $(document).on('click', '#trigger_listar_imagens', function () {
        $('.ativo_listar').addClass('hidden');
        $('.selecionar_listar').removeClass('hidden');
        $('#artefato_listar_imagens').addClass('hidden');
        $('#artefato_ativo_listar_imagens').removeClass('hidden');
        $('.subcategorias').addClass('hidden');
        $('.subcategoria_imagem').removeClass('hidden');
    });
    $(document).on('click', '#trigger_listar_video', function () {
        $('.ativo_listar').addClass('hidden');
        $('.selecionar_listar').removeClass('hidden');
        $('#artefato_listar_video').addClass('hidden');
        $('#artefato_ativo_listar_video').removeClass('hidden');
        $('.subcategorias').addClass('hidden');
        $('.subcategoria_video').removeClass('hidden');
    });

</script>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	$sistema_etiquetas_elementos = true;
	include 'templates/html_bottom.php';
?>
