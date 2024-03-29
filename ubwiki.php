<?php

	$pagina_tipo = 'ubwiki';
	$pagina_id = 0;

	include 'engine.php';

	include 'templates/html_head.php';

	$escritorio_col_limit = 'col-lg-2 col-md-3 col-sm-6';
?>

<body class="bg-light">
<?php
	if ($user_id != false) {
		include 'templates/navbar.php';
	}
?>
<div class="container">
	<?php
		if ($user_id == false) {
			echo "<div class='d-flex justify-content-end p-1'>";
			echo "<a data-bs-toggle='modal' data-bs-target='#modal_languages' class='link-teal mx-2' href='javascript:void(0);'><i class='fad fa-language fa-fw fa-2x'></i></a>";
			echo "<a data-bs-toggle='modal' data-bs-target='#modal_login' class='link-primary mx-2' href='javascript:void(0);'><i class='fad fa-user-circle fa-fw fa-2x'></i></a>";
			echo "</div>";
		}

	?>
    <div class="row d-flex justify-content-center">
        <div class="col align-self-center">
			<?php
				$template_titulo = "Ubwiki";
				if ($user_id != false) {
					$template_subtitulo = $pagina_translated['slogan'];
				} else {
					$template_subtitulo = "<a href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#modal_login' class='link-primary'>{$pagina_translated['slogan']}</a>";
				}
				$template_subtitulo_size = 'h2';
				include 'templates/titulo.php';
			?>
        </div>
		<?php
			if ($user_tipo == 'admin') {
				echo "<div class='col'>";
				$template_id = 'logo_ubwiki';
				$template_titulo = false;
				$template_conteudo = false;
				$template_spacing = false;
				$template_spacer = false;
				$template_botoes_padrao = false;
				$template_background = 'bg-light';
				$template_conteudo_no_col = true;
				$logo_ubwiki = '../imagens/verbetes/IA4flR71rqCSFuUJ.png';
				$template_conteudo .= "
                    	<div class='logo_ubwiki rounded m-1' style='background-image: url($logo_ubwiki)'></div>
                    ";
				include 'templates/page_element.php';
				echo "</div>";
			}
		?>
    </div>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
			<?php
				if ($user_id == false) {
					$template_id = 'sobre_ubwiki';
					//$template_titulo = $pagina_translated['about_ubwiki'];
					$template_titulo = false;
					$template_botoes = false;
					$template_botoes_padrao = false;
					$template_conteudo = false;
					$texto_pagina_login = return_texto_pagina_login($user_language);
					$template_conteudo .= return_verbete_html($texto_pagina_login);
					include 'templates/page_element.php';
				}

				$template_id = 'apresentacao';
				$template_titulo = $pagina_translated['environments'];
				$template_conteudo_no_col = true;
				$template_conteudo_class = 'justify-content-center';
				$template_conteudo = false;

				$texto_ambiente_id = return_texto_ambientes('ambientes', $user_language);
				$texto_ambiente = return_verbete_html($texto_ambiente_id);

				$template_conteudo .= "
                                    <div class='col-12 d-flex justify-content-center'>
                                    	<p class='text-center'>$texto_ambiente</p>
                                    </div>
				                ";

				$artefato_icone_background_link = 'icone-link primary-color link-light';

				$artefato_titulo = $pagina_translated['office'];
				$artefato_tipo = 'escritorio';
				//$artefato_link = '#sobre_escritorio';
				$artefato_col_limit = $escritorio_col_limit;
				$fa_icone = 'fa-lamp-desk';
				$fa_color = 'link-danger';
				$fa_size = 'fa-4x';
				$artefato_criacao = false;
				$artefato_class = 'icone_sobre_stuff';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_titulo = $pagina_translated['office'];
				if ($user_id == false) {
					$artefato_modal = '#modal_login';
				} else {
					$artefato_link = 'escritorio.php';
				}
				$artefato_tipo = 'escritorio_link';
				$artefato_col_limit = $escritorio_col_limit;

				$artefato_subtitulo = $pagina_translated['Visitar'];
				$artefato_titulo_class = 'link-primary';
				$fa_icone = 'fa-lamp-desk';
				$fa_color = 'link-light';
				$artefato_icone_background = 'icone-link bg-danger';
				$fa_size = 'fa-4x';
				$fa_invert = true;
				$artefato_class = 'icones_links';
				$artefato_criacao = false;
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_titulo = $pagina_translated['courses'];
				$artefato_tipo = 'cursos';
				$artefato_col_limit = $escritorio_col_limit;

				//$artefato_link = '#sobre_cursos';
				$fa_icone = 'fa-graduation-cap';
				$fa_color = 'link-primary';
				$fa_size = 'fa-4x';
				$artefato_criacao = false;
				$artefato_class = 'icone_sobre_stuff';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_titulo = $pagina_translated['courses'];;
				$artefato_subtitulo = $pagina_translated['Visitar'];
				$artefato_titulo_class = 'link-primary';
				$artefato_link = 'cursos.php';
				$artefato_tipo = 'cursos_link';
				$artefato_col_limit = $escritorio_col_limit;
				$fa_icone = 'fa-graduation-cap';
				$fa_color = 'link-light';
				$artefato_icone_background = 'icone-link bg-primary';
				$fa_size = 'fa-4x';
				$fa_invert = true;
				$artefato_class = 'icones_links';
				$artefato_criacao = false;
				$template_conteudo .= include 'templates/artefato_item.php';
				/*
								$artefato_titulo = $pagina_translated['freepages'];
								$artefato_tipo = 'areas_interesse';
								$artefato_col_limit = $escritorio_col_limit;
								//$artefato_link = '#sobre_areas_interesse';
								$artefato_criacao = false;
								$fa_icone = 'fa-tags';
								$fa_color = 'link-warning';
								$fa_size = 'fa-4x';
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';

								$artefato_titulo = $pagina_translated['freepages'];
								$artefato_subtitulo = $pagina_translated['Visitar'];
								$artefato_titulo_class = 'link-primary';
								$artefato_link = 'paginas_livres.php';
								$artefato_tipo = 'areas_interesse_link';
								$artefato_col_limit = $escritorio_col_limit;
								$fa_icone = 'fa-tags';
								$fa_color = 'link-light';
								$artefato_icone_background = 'icone-link bg-warning';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
				*/

				$artefato_titulo = $pagina_translated['library'];
				$artefato_tipo = 'biblioteca';
				$artefato_col_limit = $escritorio_col_limit;
				//$artefato_link = '#sobre_biblioteca';
				$fa_icone = 'fa-books';
				$fa_color = 'link-success';
				$fa_size = 'fa-4x';
				$artefato_criacao = false;
				$artefato_class = 'icone_sobre_stuff';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_titulo = $pagina_translated['library'];;
				$artefato_subtitulo = $pagina_translated['Visitar'];
				$artefato_titulo_class = 'link-primary';
				$artefato_link = 'biblioteca.php';
				$artefato_tipo = 'biblioteca_link';
				$artefato_col_limit = $escritorio_col_limit;
				$fa_icone = 'fa-books';
				$fa_color = 'link-light';
				$artefato_icone_background = 'icone-link bg-success';
				$fa_size = 'fa-4x';
				$fa_invert = true;
				$artefato_class = 'icones_links';
				$artefato_criacao = false;
				$template_conteudo .= include 'templates/artefato_item.php';

				/*
				$artefato_titulo = $pagina_translated['forum'];
				$artefato_tipo = 'forum';
				//$artefato_link = '#sobre_forum';
				$fa_icone = 'fa-comments-alt';
				$fa_color = 'link-purple';
				$fa_size = 'fa-4x';
				$artefato_criacao = false;
				$artefato_class = 'icone_sobre_stuff';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_titulo = $pagina_translated['forum'];
				$artefato_subtitulo = $pagina_translated['Visitar'];
				$artefato_titulo_class = 'link-primary';
				$artefato_link = 'forum.php';
				$artefato_tipo = 'forum_link';
				$fa_icone = 'fa-comments-alt';
				$fa_color = 'link-light';
				$artefato_icone_background = 'icone-link rgba-purple-strong';
				$fa_size = 'fa-4x';
				$fa_invert = true;
				$artefato_class = 'icones_links';
				$artefato_criacao = false;
				$template_conteudo .= include 'templates/artefato_item.php';
				*/
				/*
				if ($user_tipo == 'admin') {
					$artefato_titulo = $pagina_translated['market'];
					$artefato_tipo = 'loja';
					//$artefato_link = '#sobre_loja';
					$fa_icone = 'fa-bags-shopping';
					$fa_color = 'link-danger';
					$fa_size = 'fa-4x';
					$artefato_criacao = false;
					$artefato_class = 'icone_sobre_stuff';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_titulo = $pagina_translated['market'];
					$artefato_subtitulo = $pagina_translated['Visitar'];
					$artefato_titulo_class = 'link-primary';
					$artefato_link = 'mercado.php';
					$artefato_tipo = 'loja_link';
					$fa_icone = 'fa-bags-shopping';
					$fa_color = 'link-light';
					$artefato_icone_background = 'icone-link bg-danger';
					$fa_size = 'fa-4x';
					$fa_invert = true;
					$artefato_class = 'icones_links';
					$artefato_criacao = false;
					$template_conteudo .= include 'templates/artefato_item.php';
				}
				*/
				include 'templates/page_element.php';

				$template_id = 'sobre_escritorio';
				$template_titulo = $pagina_translated['about_office'];
				$template_classes = 'sobre_stuff';
				//if ($user_id != false) {
				//	$template_botoes = '<a href="escritorio.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				//} else {
				//	$template_botoes = '<a data-bs-toggle="modal" data-bs-target="#modal_login" class="link-teal"><i class="fad fa-user-circle fa-fw"></i></a>';
				//}
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('escritorio', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';

				$template_id = 'sobre_cursos';
				$template_titulo = $pagina_translated['about_courses'];
				$template_classes = 'sobre_stuff';
				//$template_botoes = '<a href="cursos.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('cursos', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';

				/*$template_id = 'sobre_areas_interesse';
				$template_titulo = $pagina_translated['about_free_pages'];
				$template_classes = 'sobre_stuff';
				//$template_botoes = '<a href="paginas_livres.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('paginaslivres', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';*/

				$template_id = 'sobre_biblioteca';
				$template_titulo = $pagina_translated['about_library'];
				$template_classes = 'sobre_stuff';
				//$template_botoes = '<a href="biblioteca.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('biblioteca', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';
				/*
				$template_id = 'sobre_forum';
				$template_titulo = $pagina_translated['about_forum'];
				$template_classes = 'sobre_stuff';
				//$template_botoes = '<a href="forum.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('forum', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';
				*/
				$template_id = 'sobre_loja';
				$template_titulo = $pagina_translated['about_market'];
				$template_classes = 'sobre_stuff';
				//$template_botoes = '<a href="mercado.php" class="link-primary"><i class="fad fa-external-link fa-fw fa-2x"></i></a>';
				$template_conteudo = false;
				$texto_ambiente_id = return_texto_ambientes('mercado', $user_language);
				$template_conteudo .= return_verbete_html($texto_ambiente_id);
				include 'templates/page_element.php';
			?>
        </div>
    </div>
</div>
<?php

	if ($user_id == false) {
		include 'pagina/modal_login.php';
	}

	include 'pagina/modal_languages.php';

?>
</body>
<?php
	$carregar_modal_login = true;
	include 'templates/html_bottom.php';

?>
</html>

