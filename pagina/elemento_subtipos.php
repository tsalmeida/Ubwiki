<?php
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'referencia')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'livro';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = $pagina_translated['Livros'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'pagina';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = $pagina_translated['Páginas virtuais'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'artigo';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = $pagina_translated['Artigos, colunas, notícias'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'wikipedia';
		$fa_type = 'fab';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = $pagina_translated['Verbetes da Wikipédia'];
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'album_musica')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'musica';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = $pagina_translated['Música'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'podcast';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = $pagina_translated['Podcasts'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'audiobook';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = $pagina_translated['Livros em áudio'];
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'imagem')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'mapasatelite';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Mapas e fotografias de satélite'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'grafico';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Gráficos'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'equacao';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Equações'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'retrato';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Retratos'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'arte';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Obras de arte'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'paisagem';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Paisagens'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'objeto';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Objetos'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'arquitetura';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Arquitetura'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'planta';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Plantas'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'animais';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Animais'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'outras';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = $pagina_translated['Outras'];
		include 'templates/subtipo_icone.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'video')) || ($pagina_tipo != 'elemento') || ($template_modal_div_id == 'modal_selecionar_subtipo')) {
		
		$template_subtipo = 'youtube';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = $pagina_translated['Vídeos do YouTube'];
		$fa_type = 'fab';
		if ($template_modal_div_id != 'modal_elemento_subtipo') {
			$artefato_modal = '#modal_adicionar_youtube';
		}
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'filme';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = $pagina_translated['Filmes'];
		include 'templates/subtipo_icone.php';
		
		$template_subtipo = 'aula';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = $pagina_translated['Aulas e cursos'];
		include 'templates/subtipo_icone.php';
		
	}
	
	if (isset($subtipo_artefato_modal)) {
		unset($subtipo_artefato_modal);
	}