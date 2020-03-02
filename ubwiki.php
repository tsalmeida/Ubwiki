<?php
	$pagina_tipo = 'ubwiki';
	include 'engine.php';
	include 'templates/html_head.php';
?>
    <body class='grey lighten-5'>
		<?php
			include 'templates/navbar.php';
		?>
    <div class="container">
			<?php
				$template_titulo = 'Ubwiki';
				$template_titulo_context = true;
				$template_titulo_no_nav = true;
				include 'templates/titulo.php';
			?>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div id='coluna_unica' class="col">
							<?php
								$template_id = 'apresentacao';
								$template_titulo = $pagina_translated['environments'];
								$template_conteudo_no_col = true;
								$template_conteudo_class = 'justify-content-center';
								$template_conteudo = false;
								
								$texto_ambiente_id = return_texto_ambientes('ambientes', $user_language);
								$texto_ambiente = return_verbete_html($texto_ambiente_id);
								
								$template_conteudo .= "
                                    <div class='col-12'>
                                    $texto_ambiente
                                    </div>
				                ";
								
								$artefato_icone_background_link = 'icone-link primary-color text-white';
								
								$artefato_titulo = $pagina_translated['office'];
								$artefato_tipo = 'escritorio';
								$fa_icone = 'fa-lamp-desk';
								$fa_color = 'text-info';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['visit_office'];
								$artefato_link = 'escritorio.php';
								$artefato_tipo = 'escritorio_link';
								$fa_icone = 'fa-lamp-desk';
								$fa_color = 'text-white';
								$artefato_icone_background = 'icone-link rgba-cyan-strong';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['courses'];
								$artefato_tipo = 'cursos';
								$fa_icone = 'fa-book-reader';
								$fa_color = 'text-default';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['visit_courses'];;
								$artefato_link = 'cursos.php';
								$artefato_tipo = 'cursos_link';
								$fa_icone = 'fa-book-reader';
								$fa_color = 'text-white';
								$artefato_icone_background = 'icone-link rgba-teal-strong';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['freepages'];
								$artefato_tipo = 'areas_interesse';
								$artefato_criacao = false;
								$fa_icone = 'fa-tags';
								$fa_color = 'text-warning';
								$fa_size = 'fa-4x';
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['visit_free_pages'];
								$artefato_link = 'paginas_livres.php';
								$artefato_tipo = 'areas_interesse_link';
								$fa_icone = 'fa-tags';
								$fa_color = 'text-white';
								$artefato_icone_background = 'icone-link rgba-orange-strong';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['library'];
								$artefato_tipo = 'biblioteca';
								$fa_icone = 'fa-books';
								$fa_color = 'text-success';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['visit_library'];;
								$artefato_link = 'biblioteca.php';
								$artefato_tipo = 'biblioteca_link';
								$fa_icone = 'fa-books';
								$fa_color = 'text-white';
								$artefato_icone_background = 'icone-link rgba-green-strong';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['forum'];
								$artefato_tipo = 'forum';
								$fa_icone = 'fa-comments-alt';
								$fa_color = 'text-secondary';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = $pagina_translated['visit_forum'];
								$artefato_link = 'forum.php';
								$artefato_tipo = 'forum_link';
								$fa_icone = 'fa-comments-alt';
								$fa_color = 'text-white';
								$artefato_icone_background = 'icone-link rgba-purple-strong';
								$fa_size = 'fa-4x';
								$fa_invert = true;
								$artefato_class = 'icones_links';
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								
								if ($user_tipo == 'admin') {
									$artefato_titulo = $pagina_translated['market'];
									$artefato_tipo = 'loja';
									$fa_icone = 'fa-bags-shopping';
									$fa_color = 'text-danger';
									$fa_size = 'fa-4x';
									$artefato_criacao = false;
									$artefato_class = 'icone_sobre_stuff';
									$template_conteudo .= include 'templates/artefato_item.php';
									
									$artefato_titulo = $pagina_translated['visit_market'];
									$artefato_link = 'mercado.php';
									$artefato_tipo = 'loja_link';
									$fa_icone = 'fa-bags-shopping';
									$fa_color = 'text-white';
									$artefato_icone_background = 'icone-link rgba-red-strong';
									$fa_size = 'fa-4x';
									$fa_invert = true;
									$artefato_class = 'icones_links';
									$artefato_criacao = false;
									$template_conteudo .= include 'templates/artefato_item.php';
								}
								
								include 'templates/page_element.php';
								
								$template_id = 'sobre_escritorio';
								$template_titulo = $pagina_translated['about_office'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('escritorio', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_cursos';
								$template_titulo = $pagina_translated['about_courses'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('cursos', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_areas_interesse';
								$template_titulo = $pagina_translated['about_free_pages'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('paginaslivres', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_biblioteca';
								$template_titulo = $pagina_translated['about_library'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('biblioteca', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_forum';
								$template_titulo = $pagina_translated['about_forum'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('forum', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_loja';
								$template_titulo = $pagina_translated['about_market'];
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$texto_ambiente_id = return_texto_ambientes('mercado', $user_language);
								$template_conteudo .= return_verbete_html($texto_ambiente_id);
								include 'templates/page_element.php';
							
							?>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        $('.sobre_stuff').addClass('hidden');
        $('.icones_links').addClass('hidden');
        $('#artefato_escritorio').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_escritorio_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_escritorio').removeClass('hidden');
            $('#artefato_escritorio').addClass('hidden');
        });
        $('#artefato_cursos').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_cursos_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_cursos').removeClass('hidden');
            $('#artefato_cursos').addClass('hidden');
        });
        $('#artefato_areas_interesse').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_areas_interesse_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_areas_interesse').removeClass('hidden');
            $('#artefato_areas_interesse').addClass('hidden');
        });
        $('#artefato_biblioteca').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_biblioteca_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_biblioteca').removeClass('hidden');
            $('#artefato_biblioteca').addClass('hidden');
        });
        $('#artefato_forum').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_forum_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_forum').removeClass('hidden');
            $('#artefato_forum').addClass('hidden');
        });
        $('#artefato_loja').click(function () {
            $('.icone_sobre_stuff').removeClass('hidden');
            $('.icones_links').addClass('hidden');
            $('#artefato_loja_link').removeClass('hidden');
            $('.sobre_stuff').addClass('hidden');
            $('#sobre_loja').removeClass('hidden');
            $('#artefato_loja').addClass('hidden');
        });

    </script>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>