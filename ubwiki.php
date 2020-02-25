<?php
	include 'engine.php';
	$page_tipo = 'ubwiki';
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
								$template_titulo = 'Ambientes';
								$template_conteudo_no_col = true;
								$template_conteudo_class = 'justify-content-center';
								$template_conteudo = false;
								
								$texto_ambiente = return_verbete_html(1218);
								
								$template_conteudo .= "
                                    <div class='col-12'>
                                    $texto_ambiente
                                    </div>
				                ";
								
								$artefato_icone_background_link = 'icone-link primary-color text-white';
								
								$artefato_titulo = 'Seu escritório';
								$artefato_tipo = 'escritorio';
								$fa_icone = 'fa-lamp-desk';
								$fa_color = 'text-info';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = 'Ver escritório';
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
								
								$artefato_titulo = 'Cursos';
								$artefato_tipo = 'cursos';
								$fa_icone = 'fa-book-reader';
								$fa_color = 'text-default';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = 'Ver cursos';
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
								
								$artefato_titulo = 'Páginas livres';
								$artefato_tipo = 'areas_interesse';
								$artefato_criacao = false;
								$fa_icone = 'fa-tags';
								$fa_color = 'text-warning';
								$fa_size = 'fa-4x';
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = 'Ver páginas livres';
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
								
								$artefato_titulo = 'Biblioteca';
								$artefato_tipo = 'biblioteca';
								$fa_icone = 'fa-books';
								$fa_color = 'text-success';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = 'Ver biblioteca';
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
								
								$artefato_titulo = 'Fórum';
								$artefato_tipo = 'forum';
								$fa_icone = 'fa-comments-alt';
								$fa_color = 'text-secondary';
								$fa_size = 'fa-4x';
								$artefato_criacao = false;
								$artefato_class = 'icone_sobre_stuff';
								$template_conteudo .= include 'templates/artefato_item.php';
								
								$artefato_titulo = 'Ver fórum';
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
									$artefato_titulo = 'Loja';
									$artefato_tipo = 'loja';
									$fa_icone = 'fa-bags-shopping';
									$fa_color = 'text-danger';
									$fa_size = 'fa-4x';
									$artefato_criacao = false;
									$artefato_class = 'icone_sobre_stuff';
									$template_conteudo .= include 'templates/artefato_item.php';
									
									$artefato_titulo = 'Ver loja';
									$artefato_link = 'loja.php';
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
								$template_titulo = 'Sobre o escritório';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1220);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_cursos';
								$template_titulo = 'Sobre os cursos';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1222);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_areas_interesse';
								$template_titulo = 'Sobre as páginas livres';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1224);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_biblioteca';
								$template_titulo = 'Sobre a Biblioteca';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1226);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_forum';
								$template_titulo = 'Sobre o fórum';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1228);
								include 'templates/page_element.php';
								
								$template_id = 'sobre_loja';
								$template_titulo = 'Sobre a loja';
								$template_classes = 'sobre_stuff';
								$template_conteudo = false;
								$template_conteudo .= return_verbete_html(1230);
								include 'templates/page_element.php';
							
							?>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        $('.sobre_stuff').hide();
        $('.icones_links').hide();
        $('#artefato_escritorio').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_escritorio_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_escritorio').show();
            $('#artefato_escritorio').hide();
        });
        $('#artefato_cursos').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_cursos_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_cursos').show();
            $('#artefato_cursos').hide();
        });
        $('#artefato_areas_interesse').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_areas_interesse_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_areas_interesse').show();
            $('#artefato_areas_interesse').hide();
        });
        $('#artefato_biblioteca').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_biblioteca_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_biblioteca').show();
            $('#artefato_biblioteca').hide();
        });
        $('#artefato_forum').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_forum_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_forum').show();
            $('#artefato_forum').hide();
        });
        $('#artefato_loja').click(function () {
            $('.icone_sobre_stuff').show();
            $('.icones_links').hide();
            $('#artefato_loja_link').show();
            $('.sobre_stuff').hide();
            $('#sobre_loja').show();
            $('#artefato_loja').hide();
        });

    </script>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>