<?php
	include 'engine.php';
	$pagina_tipo = 'areas_interesse';
	$pagina_id = 1;
	include 'templates/html_head.php';
	$busca = false;

	if (isset($_POST['nova_pagina_livre'])) {
		$nova_pagina_livre = $_POST['nova_pagina_livre'];
		$query = prepare_query("SELECT pagina_id FROM Etiquetas WHERE titulo = '$nova_pagina_livre'");
		$check_etiquetas = $conn->query($query);
		if ($check_etiquetas->num_rows == 0) {
		    $query = prepare_query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$nova_pagina_livre', $user_id)");
			$conn->query($query);
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
<body class="bg-light">
<?php
	include 'templates/navbar.php';

	if ($user_id != false) {
		$adicionar_pagina_livre = '#adicionar_pagina_livre';
	} else {
		$adicionar_pagina_livre = '#modal_login';
	}

?>
<div class="container-fluid">

    <div class="row d-flex justify-content-between p-1">
        <div class="col">
            <div class="row d-flex justify-content-start">
                <a data-bs-toggle="modal" data-bs-target="<?php echo $adicionar_pagina_livre; ?>" class="link-success ms-1" title="<?php echo $pagina_translated['Criar nova página livre'] ?>" href="javascript:void(0);">
                    <i class="fad fa-plus-circle fa-2x fa-fw"></i>
                </a>
            </div>
        </div>
        <div class="col d-flex justify-content-center"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal_busca" class="text-dark"><i
                        class="fad fa-search fa-fw"></i></a></div>
		<?php
			echo "<div class='col d-flex justify-content-end'><form method='post'><button name='listar_todas' id='listar_todas' value='!all' class='btn btn-outline-primary'>{$pagina_translated['Listar todas']}</button></form></div>";
		?>
    </div>
</div>
<div class="container">
	<?php
		$template_titulo = $pagina_translated['freepages'];
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
				    $query = prepare_query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico' ORDER BY id DESC");
					$etiquetas = $conn->query($query);
				} else {
				    $query = prepare_query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico' AND titulo LIKE '%$busca%'");
					$etiquetas = $conn->query($query);
				}
				if ($etiquetas != false) {
					$template_id = 'etiquetas';
					$template_titulo = 'Tópicos';
					$template_conteudo_no_col = true;
					$template_botoes = "
								  <a data-bs-toggle='modal' data-bs-target='$adicionar_pagina_livre' class='link-success ms-1' title='{$pagina_translated['Adicionar página livre']}' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
					$template_conteudo = false;
					if ($etiquetas->num_rows > 0) {
						while ($etiqueta = $etiquetas->fetch_assoc()) {
							$etiqueta_id = $etiqueta['id'];
							$etiqueta_titulo = $etiqueta['titulo'];
							$etiqueta_pagina_id = $etiqueta['pagina_id'];
							$etiqueta_pagina_info = return_pagina_info($etiqueta_pagina_id);
							if ($etiqueta_pagina_info != false) {
								$etiqueta_pagina_estado = $etiqueta_pagina_info[3];
								/*if ($etiqueta_pagina_id == false) {
												$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
											}
											$etiqueta_texto_id = return_texto_id('pagina', 'verbete', $etiqueta_pagina_id, false);
											$etiqueta_verbete = return_verbete_html($etiqueta_texto_id);*/

								$artefato_titulo = $etiqueta_titulo;
								$fa_icone = 'fa-tag fa-swap-opacity';
								if ($etiqueta_pagina_estado != 0) {
									$fa_color = 'link-warning';
									$artefato_badge = return_estado_icone($etiqueta_pagina_estado);
									$artefato_badge = $artefato_badge[0];
								} else {
									$fa_color = 'link-purple';
								}
								$fa_size = 'fa-4x';
								$artefato_link = "pagina.php?pagina_id=$etiqueta_pagina_id";
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
					}
					include 'templates/page_element.php';
				} else {

					$template_id = 'etiquetas_adicionadas';
					$template_titulo = $pagina_translated['Recentemente adicionadas'];
					$template_conteudo_no_col = true;
					$template_botoes = "
								  <a data-bs-toggle='modal' data-bs-target='$adicionar_pagina_livre' class='link-success ms-1' title='{$pagina_translated['Adicionar página livre']}' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
					$template_conteudo = false;
					$query = prepare_query("SELECT id  FROM Etiquetas WHERE tipo = 'topico' ORDER BY id DESC");
					$etiquetas = $conn->query($query);
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
							$fa_icone = 'fa-tag fa-swap-opacity';
							if ($list_etiqueta_pagina_estado != 0) {
								$fa_color = 'link-warning';
								$artefato_badge = return_estado_icone($list_etiqueta_pagina_estado);
								$artefato_badge = $artefato_badge[0];
							} else {
								$fa_color = 'link-purple';
							}
							$fa_size = 'fa-4x';
							$artefato_link = "pagina.php?pagina_id=$list_etiqueta_pagina_id";
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
					}

					$template_id = 'etiquetas_recentes';
					$template_titulo = $pagina_translated['Recentemente modificadas'];
					$template_conteudo_no_col = true;
					$template_botoes = "
								  <a data-bs-toggle='modal' data-bs-target='$adicionar_pagina_livre' class='link-success ms-1' title='{$pagina_translated['Adicionar página livre']}' href='javascript:void(0);'><i class='fad fa-plus-square fa-fw'></i></a>
								";
					$template_conteudo = false;
					$paginas_contadas = array();
					$query = prepare_query("SELECT pagina_id, verbete_content FROM Textos_arquivo WHERE pagina_subtipo = 'etiqueta' ORDER BY id DESC");
					$etiquetas = $conn->query($query);
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
							$fa_icone = 'fa-tag fa-swap-opacity';
							if ($etiqueta_pagina_estado != 0) {
								$fa_color = 'link-warning';
								$artefato_badge = return_estado_icone($etiqueta_pagina_estado);
								$artefato_badge = $artefato_badge[0];
							} else {
								$fa_color = 'link-purple';
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
	$template_modal_titulo = $pagina_translated['Busca de páginas livres'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = true;
	$template_modal_body_conteudo .= "
		<div class='mb-3'>
			<label for='termos_busca' class='form-label'>{$pagina_translated['Termos de busca']}</label>
			<input type='text' id='termos_busca' name='termos_busca' class='form-control'>
			
		</div>
	";
	include 'templates/modal.php';

	$template_modal_div_id = 'adicionar_pagina_livre';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Criar página livre'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='mb-3'>
            <label for='nova_pagina_livre' class='form-label'>{$pagina_translated['Título da nova página livre']}</label>
			<input type='text' id='nova_pagina_livre' name='nova_pagina_livre' class='form-control'>
		</div>
	";
	include 'templates/modal.php';

	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
	include 'pagina/modal_languages.php';


?>
</body>
<?php

	include 'templates/html_bottom.php';
?>

