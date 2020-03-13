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
						$artefato_titulo = 'Estudos recentes';
						$fa_icone = 'fa-history fa-swap-opacity';
						$fa_color = 'text-info';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'cursos';
						$artefato_titulo = 'Seus cursos';
						$fa_icone = 'fa-book-reader';
						$fa_color = 'text-success';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'typewriter';
						$artefato_titulo = 'Suas páginas e textos';
						$fa_icone = 'fa-typewriter';
						$fa_color = 'text-primary';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'suas_paginas_livres';
						$artefato_titulo = 'Páginas livres de seu interesse';
						$fa_icone = 'fa-tags';
						$fa_color = 'text-warning';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'biblioteca_particular';
						$artefato_titulo = 'Sua biblioteca particular';
						$fa_icone = 'fa-books';
						$fa_color = 'text-success';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seus_grupos_estudo';
						$artefato_titulo = 'Seus grupos de estudo';
						$fa_icone = 'fa-users';
						$fa_color = 'text-default';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'suas_notificacoes';
						$artefato_titulo = 'Suas notificações';
						$fa_icone = 'fa-bell fa-swap-opacity';
						$fa_color = 'text-info';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seu_forum';
						$artefato_titulo = 'Suas participações em fórum';
						$fa_icone = 'fa-comments-alt';
						$fa_color = 'text-secondary';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seus_bookmarks';
						$artefato_titulo = 'Sua lista de leitura';
						$fa_icone = 'fa-bookmark';
						$fa_color = 'text-danger';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seu_artigos';
						$artefato_titulo = 'Artigos que você ajudou a escrever';
						$fa_icone = 'fa-spa';
						$fa_color = 'text-warning';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'sala_visitas';
						$artefato_titulo = 'Sua sala de visitas';
						$fa_icone = 'fa-mug-tea';
						$fa_color = 'text-secondary';
						$template_conteudo .= include "templates/artefato_item.php";
						
						
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