<?php
	
	$pagina_tipo = 'escritorio';
	include 'engine.php';
	$pagina_id = return_pagina_id($user_id, $pagina_tipo);
	
	if (!isset($user_email)) {
		header('Locatino:ubwiki.php');
	}
	
	include 'pagina/shared_issets.php';
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	
	include 'templates/html_head.php';
	include 'templates/navbar.php';

?>
<body class="grey lighten-5">
<div class="container-fluid">
    <div class="row d-flex justify-content-between py-2">
        <div class="col">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_configuracoes_usuario"
               class="text-info"><i class="fad fa-user-cog fa-fw fa-2x"></i></a>


        </div>
        <div class="col"></div>
        <div class="col"></div>
    </div>
</div>
<div class="container">
	<?php
		if ($user_apelido != false) {
			$template_titulo = $user_apelido;
			$template_titulo_above = $pagina_translated['user_office'];
		} else {
			$template_titulo = $pagina_translated['user_office'];
		}
		$template_titulo_context = true;
		include 'templates/titulo.php'
	
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
					<?php
						$template_id = 'escritorio_primeira_janela';
						$template_titulo = false;
						$template_conteudo = false;
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						
						if ($curso_id != false) {
							$artefato_id = 'curso_ativo';
							$artefato_titulo = $curso_titulo;
							$artefato_subtitulo = 'Curso ativo';
							$artefato_icone_background = 'rgba-black-strong';
							$fa_icone = 'fa-pen-alt';
							$fa_color = 'text-white';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						
						$artefato_id = 'visitas_recentes';
						$artefato_titulo = 'Recentemente visitados';
						$artefato_icone_background = 'cyan lighten-1';
						$fa_icone = 'fa-history fa-swap-opacity';
						$fa_color = 'text-white';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'cursos';
						$artefato_titulo = 'Cursos';
						$artefato_icone_background = 'teal lighten-1';
						$fa_icone = 'fa-book-reader';
						$fa_color = 'text-white';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
</div>
<?php
	
	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
	include 'pagina/modal_languages.php';
?>
</body>

<?php
	
	include 'templates/footer.html';
	$sistema_etiquetas_elementos = true;
	$sistema_etiquetas_topicos = true;
	$mdb_select = true;
	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>