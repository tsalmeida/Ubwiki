<?php
	include 'engine.php';
	$page_tipo = 'areas_interesse';
	$pagina_id = 1;
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
							$etiquetas = $conn->query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico'");
						} else {
							$etiquetas = $conn->query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico' AND titulo LIKE '%$busca%'");
							
						}
						if ($etiquetas != false) {
							$template_id = 'etiquetas';
							$template_titulo = 'Tópicos';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							if ($etiquetas->num_rows > 0) {
								while ($etiqueta = $etiquetas->fetch_assoc()) {
									$etiqueta_id = $etiqueta['id'];
									$etiqueta_titulo = $etiqueta['titulo'];
									$etiqueta_pagina_id = $etiqueta['pagina_id'];
									/*if ($etiqueta_pagina_id == false) {
													$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
												}
												$etiqueta_texto_id = return_texto_id('pagina', 'verbete', $etiqueta_pagina_id, false);
												$etiqueta_verbete = return_verbete_html($etiqueta_texto_id);*/
									
									$artefato_titulo = $etiqueta_titulo;
									$fa_icone = 'fa-tag';
									$fa_color = 'text-warning';
									$fa_size = 'fa-4x';
									$artefato_link = "pagina.php?pagina_id=$etiqueta_pagina_id";
									$template_conteudo .= include 'templates/artefato_item.php';
								}
							}
							include 'templates/page_element.php';
						} else {
							$template_id = 'etiquetas_recentes';
							$template_titulo = 'Recentemente modificadas';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							$paginas_contadas = array();
							$etiquetas = $conn->query("SELECT pagina_id, verbete_content FROM Textos_arquivo WHERE pagina_subtipo = 'etiqueta' ORDER BY id DESC");
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
									$etiqueta_titulo = return_pagina_titulo($etiqueta_pagina_id);
									$artefato_titulo = $etiqueta_titulo;
									$fa_icone = 'fa-tag';
									$fa_color = 'text-warning';
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
?>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

