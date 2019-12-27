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
			$artefato_link = "perfil.php?pub_user_id=$membro_user_id";
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
	$template_titulo = 'Textos compartilhados';
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
