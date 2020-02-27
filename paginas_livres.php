<?php
	include 'engine.php';
	$page_tipo = 'areas_interesse';
	$pagina_id = 1;
	include 'templates/html_head.php';
	$busca = false;
	
	if (isset($_POST['nova_pagina_livre'])) {
		$nova_pagina_livre = $_POST['nova_pagina_livre'];
		$check_etiquetas = $conn->query("SELECT pagina_id FROM Etiquetas WHERE titulo = '$nova_pagina_livre'");
		if ($check_etiquetas->num_rows == 0) {
			$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$nova_pagina_livre', $user_id)");
		} else {
			$busca = $nova_pagina_livre;
		}
	}
	
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
		<div class="col">
			<div class="row d-flex justify-content-start">
				<a data-toggle="modal" data-target="#adicionar_pagina_livre" class="text-success ml-1" title="Criar nova página livre" href="javascript:void(0);">
					<i class="fad fa-plus-circle fa-2x fa-fw"></i>
				</a>
			</div>
		</div>
		<div class="col d-flex justify-content-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_busca" class="text-dark"><i
					class="fad fa-search fa-fw"></i></a></div>
		<?php
			echo "<div class='col d-flex justify-content-end'><form method='post'><button name='listar_todas' id='listar_todas' value='!all' class='$button_classes btn-info btn-sm m-0'>Listar todas</button></form></div>";
		?>
	</div>
</div>
<div class="container">
	<?php
		$template_titulo = 'Páginas livres';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						
						if ($busca == false) {
							$etiquetas = false;
						} elseif ($busca == '!all') {
							$etiquetas = $conn->query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico' ORDER BY id DESC");
						} else {
							$etiquetas = $conn->query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico' AND titulo LIKE '%$busca%'");
						}
						if ($etiquetas != false) {
							$template_id = 'etiquetas';
							$template_titulo = 'Tópicos';
							$template_conteudo_no_col = true;
							$template_botoes = "
								  <a data-toggle='modal' data-target='#adicionar_pagina_livre' class='text-success ml-1' title='Adicionar página livre' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
							$template_conteudo = false;
							if ($etiquetas->num_rows > 0) {
								while ($etiqueta = $etiquetas->fetch_assoc()) {
									$etiqueta_id = $etiqueta['id'];
									$etiqueta_titulo = $etiqueta['titulo'];
									$etiqueta_pagina_id = $etiqueta['pagina_id'];
									$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
									$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
									/*if ($etiqueta_pagina_id == false) {
													$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
												}
												$etiqueta_texto_id = return_texto_id('pagina', 'verbete', $etiqueta_pagina_id, false);
												$etiqueta_verbete = return_verbete_html($etiqueta_texto_id);*/
									
									$artefato_titulo = $etiqueta_titulo;
									$fa_icone = 'fa-tag';
									if ($etiqueta_pagina_estado != 0) {
									    $fa_color = 'text-warning';
										$artefato_badge = return_estado_icone($etiqueta_pagina_estado, 'curso');
									} else {
									    $fa_color = 'text-light';
                                    }
									$fa_size = 'fa-4x';
									$artefato_link = "pagina.php?pagina_id=$etiqueta_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
								}
							}
							include 'templates/page_element.php';
						} else {
							
							$template_id = 'etiquetas_adicionadas';
							$template_titulo = 'Recentemente adicionadas';
							$template_conteudo_no_col = true;
							$template_botoes = "
								  <a data-toggle='modal' data-target='#adicionar_pagina_livre' class='text-success ml-1' title='Adicionar página livre' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
							$template_conteudo = false;
							$etiquetas = $conn->query("SELECT id  FROM Etiquetas WHERE tipo = 'topico' ORDER BY id DESC");
							if ($etiquetas->num_rows > 0) {
								$count = 0;
								while ($etiqueta = $etiquetas->fetch_assoc()) {
									$list_etiqueta_id = $etiqueta['id'];
									$list_etiqueta_info = return_etiqueta_info($list_etiqueta_id);
									$list_etiqueta_pagina_id = $list_etiqueta_info[4];
									$list_etiqueta_titulo = $list_etiqueta_info[2];
									$list_etiqueta_pagina_info = return_pagina_info($list_etiqueta_pagina_id);
									$list_etiqueta_pagina_estado = $list_etiqueta_pagina_info[3];
									$count++;
									if ($count > 12) {
										break;
									}
									$artefato_titulo = $list_etiqueta_titulo;
									$fa_icone = 'fa-tag';
									if ($list_etiqueta_pagina_estado != 0) {
										$fa_color = 'text-warning';
										$artefato_badge = return_estado_icone($list_etiqueta_pagina_estado, 'curso');
									} else {
										$fa_color = 'text-light';
									}
									$fa_size = 'fa-4x';
									$artefato_link = "pagina.php?pagina_id=$list_etiqueta_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
								}
								include 'templates/page_element.php';
							}
							
							$template_id = 'etiquetas_recentes';
							$template_titulo = 'Recentemente modificadas';
							$template_conteudo_no_col = true;
							$template_botoes = "
								  <a data-toggle='modal' data-target='#adicionar_pagina_livre' class='text-success ml-1' title='Adicionar página livre' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
							$template_conteudo = false;
							$paginas_contadas = array();
							$etiquetas = $conn->query("SELECT pagina_id, verbete_content FROM Textos_arquivo WHERE pagina_subtipo = 'etiqueta' ORDER BY id DESC");
							$count = 0;
							if ($etiquetas->num_rows > 0) {
								while ($etiqueta = $etiquetas->fetch_assoc()) {
									$etiqueta_pagina_id = $etiqueta['pagina_id'];
									if (in_array($etiqueta_pagina_id, $paginas_contadas)) {
										continue;
									} else {
										array_push($paginas_contadas, $etiqueta_pagina_id);
									}
									$etiqueta_pagina_verbete_content = $etiqueta['verbete_content'];
									if ($etiqueta_pagina_verbete_content == false) {
										continue;
									}
									$count++;
									if ($count > 12) {
									    break;
                                    }
									$etiqueta_titulo = return_pagina_titulo($etiqueta_pagina_id);
									$artefato_titulo = $etiqueta_titulo;
									$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
									$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
									$fa_icone = 'fa-tag';
									if ($etiqueta_pagina_estado != 0) {
										$fa_color = 'text-warning';
										$artefato_badge = return_estado_icone($etiqueta_pagina_estado, 'curso');
									} else {
										$fa_color = 'text-light';
									}
									$fa_size = 'fa-4x';
									$artefato_link = "pagina.php?pagina_id=$etiqueta_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
								}
							}
							include 'templates/page_element.php';
						}
					?>
        </div>
    </div>
</div>
<?php
	$template_modal_div_id = 'modal_busca';
	$template_modal_titulo = 'Busca de páginas livres';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='md-form'>
			<input type='text' id='termos_busca' name='termos_busca' class='form-control'>
			<label for='termos_busca'>Termos de busca</label>
		</div>
	";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'adicionar_pagina_livre';
	$template_modal_titulo = 'Criar página livre';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='md-form'>
			<input type='text' id='nova_pagina_livre' name='nova_pagina_livre' class='form-control'>
			<label for='nova_pagina_livre'>Título da nova página livre</label>
		</div>
	";
	include 'templates/modal.php';
?>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

