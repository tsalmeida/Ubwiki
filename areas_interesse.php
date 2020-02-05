<?php
	include 'engine.php';
	$page_tipo = 'areas_interesse';
	include 'templates/html_head.php';
	?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container">
	<?php
		$template_titulo = 'Áreas de interesse';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
	<div class="row d-flex justify-content-center">
		<div id="coluna_unica" class="col">
			<?php
				
				$template_id = 'etiquetas';
				$template_titulo = 'Áreas de interesse';
				$template_conteudo_no_col = true;
				$template_conteudo = false;
				
				$etiquetas = $conn->query("SELECT id, titulo, pagina_id FROM Etiquetas WHERE tipo = 'topico'");
				if ($etiquetas->num_rows > 0) {
					while ($etiqueta = $etiquetas->fetch_assoc()) {
						$etiqueta_id = $etiqueta['id'];
						$etiqueta_titulo = $etiqueta['titulo'];
						$etiqueta_pagina_id = $etiqueta['pagina_id'];
						if ($etiqueta_pagina_id == false) {
							$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
						}
						$etiqueta_texto_id = return_texto_id('pagina', 'verbete', $etiqueta_pagina_id, false);
						$etiqueta_verbete = return_verbete_html($etiqueta_texto_id);
						
						$artefato_titulo = $etiqueta_titulo;
						$fa_icone = 'fa-tag';
						$fa_color = 'text-warning';
						$fa_size = 'fa-4x';
						$artefato_link = "pagina.php?pagina_id=$etiqueta_pagina_id";
						$template_conteudo .= include 'templates/artefato_item.php';
						
					}
				}
				include 'templates/page_element.php';
			?>
		</div>
	</div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

