<?php
	include 'engine.php';
	$pagina_tipo = 'biblioteca';
	include 'templates/html_head.php';
?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
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
						$template_id = 'biblioteca_virtual';
						$template_titulo = 'Biblioteca';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
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
						
						$acervo = $conn->query("SELECT pagina_id, tipo, titulo, autor FROM Elementos WHERE compartilhamento IS NULL AND estado = 1 ORDER BY titulo");
						
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
