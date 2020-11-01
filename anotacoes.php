<?php
	include 'engine.php';
	$pagina_tipo = 'anotacoes';
	if (isset($_GET['pagina_id'])) {
		$pagina_id = $_GET['pagina_id'];
	} else {
		header('Location:index.php');
		exit();
	}

	$check_compartilhamento = return_compartilhamento($pagina_id, $user_id);
	if ($check_compartilhamento == false) {
		header('Location:pagina.php?pagina_id=3');
		exit();
	}
	$pagina_info = return_pagina_info($pagina_id, true);
	if ($pagina_info != false) {
		$pagina_titulo = $pagina_info[6];
	} else {
		header('Location:pagina.php?pagina_id=3');
		exit();
	}

	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
<div class="container mt-1">
	<?php
		$template_titulo = $pagina_titulo;
		$template_subtitulo = "{$pagina_translated['Anotações publicadas']} / <a href='pagina.php?pagina_id=$pagina_id'>{$pagina_translated['Página']}</a>";
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
			<?php
				$anotacoes_publicadas = $conn->query("SELECT id, texto_id, anonimato, user_id, criacao FROM Anotacoes WHERE pagina_id = $pagina_id AND estado = 1");
				if ($anotacoes_publicadas->num_rows > 0) {
					while ($anotacao_publicada = $anotacoes_publicadas->fetch_assoc()) {
						$anotacao_publicada_id = $anotacao_publicada['id'];
						$anotacao_publicada_texto_id = $anotacao_publicada['texto_id'];
						$anotacao_publicada_anonimato = $anotacao_publicada['anonimato'];
						$anotacao_publicada_user_id = $anotacao_publicada['user_id'];
						$template_id = $anotacao_publicada['criacao'];
						$template_botoes = false;
						$template_classes = 'vh-100 overflow-auto';
						$anotacao_score_info = return_anotacao_score($anotacao_publicada_id, 'anotacao_publicada', $user_id, $pagina_id);
						$anotacao_score = $anotacao_score_info[0];
						$anotacao_score_usuario = $anotacao_score_info[1];
						if ($anotacao_score_usuario == 0) {
							$thumbs_color = 'text-muted';
						} else {
							$thumbs_color = 'text-success';
						}
						$template_botoes .= "<a class='$thumbs_color fontstack-subtitle upvote' id='upvote_$anotacao_publicada_id' value='$anotacao_publicada_id'><i class='fad fa-thumbs-up fa-fw'></i><span class='upvote_score' id='upvote_$anotacao_publicada_id'>$anotacao_score</span></a>";
						if ($anotacao_publicada_anonimato == true) {
							$template_titulo = $pagina_translated['Anotação anônima'];
						} else {
							$anotacao_publicada_autor_apelido = return_apelido_user_id($anotacao_publicada_user_id);
							$anotacao_publicada_avatar_info = return_avatar($anotacao_publicada_user_id);
							$anotacao_publicada_avatar_cor = $anotacao_publicada_avatar_info[1];
							$anotacao_publicada_avatar_icone = $anotacao_publicada_avatar_info[0];
							$template_titulo = "{$pagina_translated['Autor:']} <a href='pagina.php?user_id=$anotacao_publicada_user_id' class='$anotacao_publicada_avatar_cor'><i class='fad $anotacao_publicada_avatar_icone fa-fw'></i><span class='text-muted'> $anotacao_publicada_autor_apelido</span></a>";
						}
						$template_conteudo = return_verbete_html($anotacao_publicada_texto_id);
						include 'templates/page_element.php';
					}
				}
			?>
        </div>
    </div>
</div>
<?php
	include 'pagina/modal_languages.php';
?>
</body>
<?php
	if ($user_id == false) {
		include 'pagina/modal_login.php';
	}

	include 'pagina/modal_languages.php';
	include 'templates/html_bottom.php';
?>
</html>
