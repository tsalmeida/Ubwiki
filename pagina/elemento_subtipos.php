<?php
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'referencia')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'livro';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = 'Livros';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'pagina';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = 'Páginas virtuais';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'artigo';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = 'Artigos, colunas, notícias';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'wikipedia';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = 'Verbetes da Wikipédia';
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'album_musica')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'musica';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = 'Música';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'podcast';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = 'Podcasts';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'audiobook';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = 'Livros em áudio';
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'imagem')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'mapasatelite';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Mapas e fotografias de satélite';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'grafico';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Gráficos';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'equacao';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Equações';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'retrato';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Retratos';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'arte';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Obras de arte';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'paisagem';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Paisagens';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'objeto';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Objetos';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'arquitetura';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Arquitetura';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'planta';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Plantas';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'animais';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Animais';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'outras';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Outras';
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'video')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'youtube';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = 'Vídeos do YouTube';
		$artefato_modal = '#modal_adicionar_youtube';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'filme';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = 'Filmes';
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'aula';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = 'Aulas e cursos';
		include 'templates/subtipo_icone.php';
		
	}
	
	if (isset($subtipo_artefato_modal)) {
		unset($subtipo_artefato_modal);
	}