<?php
	
	$lista_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'referencia')) || $pagina_tipo != 'elemento') {
		
		$artefato_tipo = 'listar_livros';
		$artefato_titulo = 'Livros';
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_leitura subcategorias';
		$artefato_button = 'livro';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_links';
		$artefato_titulo = 'Páginas virtuais';
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_leitura subcategorias';
		$artefato_button = 'pagina';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_artigos';
		$artefato_titulo = 'Artigos, colunas, notícias';
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_leitura subcategorias';
		$artefato_button = 'artigo';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_wikipedia';
		$artefato_titulo = 'Verbete da Wikipédia';
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_leitura subcategorias';
		$artefato_button = 'wikipedia';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'album_musica')) || $pagina_tipo != 'elemento') {
		
		$artefato_tipo = 'listar_musica';
		$artefato_titulo = 'Música';
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_audio subcategorias';
		$artefato_button = 'musica';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_podcasts';
		$artefato_titulo = "Podcasts";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_audio subcategorias';
		$artefato_button = 'podcast';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_audio_books';
		$artefato_titulo = "Livros em áudio";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_audio subcategorias';
		$artefato_button = 'audiobook';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'imagem')) || $pagina_tipo != 'elemento') {
		
		$artefato_tipo = 'listar_mapas';
		$artefato_titulo = "Mapas e fotografias de satélite";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'mapasatelite';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_graficos';
		$artefato_titulo = "Gráficos";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'grafico';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_retratos';
		$artefato_titulo = "Retratos";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'retrato';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_pinturas';
		$artefato_titulo = "Obras de arte";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'arte';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_paisagens';
		$artefato_titulo = "Paisagens";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'paisagem';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_objetos';
		$artefato_titulo = "Objetos";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'objeto';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_arquitetura';
		$artefato_titulo = "Arquitetura";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'arquitetura';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_plantas';
		$artefato_titulo = "Plantas";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'planta';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_animais';
		$artefato_titulo = "Animais";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'animais';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_fotografias';
		$artefato_titulo = "Outras";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_imagens subcategorias';
		$artefato_button = 'outras';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
	}
	
	if ((($pagina_tipo == 'elemento') && ($elemento_tipo == 'video')) || $pagina_tipo != 'elemento') {
		
		$artefato_tipo = 'listar_youtube';
		$artefato_titulo = "Vídeos do YouTube";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_video subcategorias';
		$artefato_button = 'youtube';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_filmes';
		$artefato_titulo = "Filmes";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_video subcategorias';
		$artefato_button = 'filme';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'listar_aulas';
		$artefato_titulo = "Aulas e cursos";
		$artefato_col_limit = $lista_col_limit;
		$artefato_class = 'subcategoria_video subcategorias';
		$artefato_button = 'aula';
		$artefato_name = 'trigger_subcategoria';
		$artefato_info = return_icone_subtipo($artefato_button);
		$fa_icone = $artefato_info[0];
		$fa_color = $artefato_info[1];
		if (isset($elemento_subtipo) && ($elemento_subtipo == $artefato_button)) {
			$artefato_icone_background = $artefato_info[2];
			$fa_color = 'text-white';
		}
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
	}