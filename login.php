<?php
	
	$pagina_tipo = 'login';
	$pagina_id = false;
	include 'engine.php';
	include 'templates/html_head.php';
	if (isset($_SESSION['thinkific_email'])) {
		$thinkific_email = $_SESSION['thinkific_email'];
		$thinkific_bora = $_SESSION['thinkific_bora'];
	}
?>

<body class="grey lighten-5">
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
						$template_subtitulo = "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_login' class='text-primary'><i class='fad fa-sign-in-alt fa-fw'></i> {$pagina_translated['slogan']}</a>";
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
						
						$template_id = 'sobre_ubwiki';
						$template_titulo = $pagina_translated['about_ubwiki'];
						$template_botoes = false;
						$template_botoes_padrao = false;
						$template_conteudo = false;
						$texto_pagina_login = return_texto_pagina_login($user_language);
						$template_conteudo .= return_verbete_html($texto_pagina_login);
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

