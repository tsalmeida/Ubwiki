<?php
	include 'engine.php';
	$nao_contar = false;
	if (isset($_GET['grupo_id'])) {
		$grupo_id = $_GET['grupo_id'];
	} else {
		header('Location:escritorio.php');
	}
	$grupos = $conn->query("SELECT criacao FROM Membros WHERE grupo_id = $grupo_id AND membro_user_id = $user_id AND estado = 1");
	if ($grupos->num_rows > 0) {
		while ($grupo = $grupos->fetch_assoc()) {
			$grupo_criacao = $grupo['criacao'];
		}
	} else {
		header('Location:escritorio.php');
	}
	
	$grupo_titulo = return_grupo_titulo_id($grupo_id);
	
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $grupo_id, 'grupo')");
	}
?>
<body>
<?php
	include 'templates/navbar.php';
?>

<div class="container">
	<?php
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		$template_titulo = $grupo_titulo;
		$template_subtitulo = 'Grupo de Estudos';
		include 'templates/titulo.php';
	?>
    <div class="row">
        <div class="col">
					<?php
						$template_id = 'membros_grupo';
						$template_titulo = 'Membros';
						$template_conteudo = false;
						$template_conteudo_class = 'justify-content-start';
						
						$membros = $conn->query("SELECT membro_user_id FROM Membros WHERE grupo_id = $grupo_id");
						if ($membros->num_rows > 0) {
							while ($membro = $membros->fetch_assoc()) {
								$membro_user_id = $membro['membro_user_id'];
								$membro_user_apelido = return_apelido_user_id($membro_user_id);
								
								$artefato_tipo = 'membro';
								$artefato_titulo = $membro_user_apelido;
								$artefato_link = "pagina.php?user_id=$membro_user_id";
								$artefato_criacao = false;
								$avatar_info = return_avatar($membro_user_id);
								$fa_icone = $avatar_info[0];
								$fa_color = $avatar_info[1];
								
								$artefato_resultado = include 'templates/artefato_item.php';
								$template_conteudo .= $artefato_resultado;
							}
						}
						
						include 'templates/page_element.php';
						
						
						$template_id = 'acervo_grupo';
						$template_titulo = 'Acervo compartilhado';
						$template_conteudo = false;
						$template_conteudo_class = 'justify-content-start';
						
						$itens = $conn->query("SELECT criacao, user_id, item_id, item_tipo FROM Compartilhamento WHERE recipiente_id = $grupo_id AND compartilhamento = 'grupo'");
						if ($itens->num_rows > 0) {
							while ($item = $itens->fetch_assoc()) {
								$item_criacao = $item['criacao'];
								$item_user_id = $item['user_id'];
								$item_id = $item['item_id'];
								$item_tipo = $item['item_tipo'];
								
								if ($item_tipo == 'texto') {
									$texto_info = return_texto_info($item_id);
									$texto_tipo = $texto_info[1];
									$texto_titulo = $texto_info[2];
									$texto_criacao = $texto_info[4];
									$texto_user_id = $texto_info[8];
									
									$artefato_tipo = $texto_tipo;
									$artefato_titulo = $texto_titulo;
									$artefato_link = "edicao_textos.php?texto_id=$item_id";
									$artefato_criacao = $texto_criacao;
									$artefato_resultado = include 'templates/artefato_item.php';
									$template_conteudo .= $artefato_resultado;
								}
							}
						}
						
						include 'templates/page_element.php';
					?>

        </div>
    </div>
</div>
</body>
<?
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

</html>
