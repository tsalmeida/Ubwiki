<?php
	
	$pagina_tipo = 'ubwiki';
	$pagina_id = false;
	include 'engine.php';
	include 'templates/html_head.php';
	if (isset($_SESSION['thinkific_email'])) {
		$thinkific_email = $_SESSION['thinkific_email'];
		$thinkific_bora = $_SESSION['thinkific_bora'];
	}
?>

<body class="grey lighten-5">
<?php
	if ($user_id != false) {
		include 'templates/navbar.php';
	}
?>
<div class="container">
	<?php
		if ($user_tipo == 'admin') {
			echo "
                <div class='row d-flex justify-content-end p-1'>
                    <a data-toggle='modal' data-target='#modal_languages' class='text-primary'><i class='fad fa-language fa-fw fa-2x'></i></a>
                </div>
            ";
		}
	?>
    <div class="row d-flex justify-content-center mt-2">
        <div class="col">
					<?php
						$template_titulo = "Ubwiki";
						if ($user_id != false) {
							$template_subtitulo = $pagina_translated['slogan'];
						} else {
							$template_subtitulo = "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_login' class='text-primary'>{$pagina_translated['slogan']}</a>";
						}
						$template_subtitulo_size = 'h2';
						$template_titulo_context = true;
						include 'templates/titulo.php';
					?>
        </div>
			<?php
				if ($user_tipo == 'admin') {
					echo "<div class='col'>";
					$template_id = 'logo_ubwiki';
					$template_titulo = false;
					$template_conteudo = false;
					$template_spacer = false;
					$template_botoes_padrao = false;
					$template_background = 'grey lighten-5';
					$template_conteudo_no_col = true;
					$logo_ubwiki = 'https://ubwiki.com.br/imagens/verbetes/IA4flR71rqCSFuUJ.png';
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
						if ($user_id == false) {
							$artefato_modal = '#modal_login';
						} else {
							$artefato_link = 'escritorio.php';
						}
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
<?php
	
	$template_modal_div_id = 'modal_languages';
	$template_modal_titulo = $pagina_translated['languages'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "<div method='post' class='row d-flex justify-content-center'>";
	
	$artefato_titulo = 'Português';
	$artefato_tipo = 'lg_pt';
	$artefato_link = 'login.php?lg=pt';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-success';
	if ($user_language == 'pt') {
		$artefato_icone_background = 'rgba-green-strong';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_titulo = 'English';
	$artefato_tipo = 'lg_en';
	$artefato_link = 'login.php?lg=en';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-primary';
	if ($user_language == 'en') {
		$artefato_icone_background = 'rgba-blue-strong';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_titulo = 'Español';
	$artefato_tipo = 'lg_es';
	$artefato_link = 'login.php?lg=es';
	$artefato_class = 'language_choose';
	$fa_icone = 'fa-globe';
	$fa_color = 'text-danger';
	if ($user_language == 'es') {
		$artefato_icone_background = 'rgba-red-strong';
		$fa_color = 'text-white';
	}
	$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</div>";
	
	include 'templates/modal.php';
	
	include 'pagina/modal_login.php';

?>
</body>
<?php
	$carregar_modal_login = true;
	include 'templates/html_bottom.php';
	include 'templates/footer.html';
?>
</html>

