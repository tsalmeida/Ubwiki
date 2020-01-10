<?php
	
	$template_id = 'membros_grupo';
	$template_titulo = 'Membros';
	$template_conteudo = false;
	$template_conteudo_class = 'justify-content-start';
	$template_conteudo_no_col = true;
	
	$membros = $conn->query("SELECT membro_user_id FROM Membros WHERE grupo_id = $grupo_id AND estado = 1");
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
	$template_conteudo_no_col = true;
	
	$itens = $conn->query("SELECT criacao, user_id, item_id, item_tipo FROM Compartilhamento WHERE recipiente_id = $grupo_id AND compartilhamento = 'grupo'");
	if ($itens->num_rows > 0) {
		while ($item = $itens->fetch_assoc()) {
			$item_criacao = $item['criacao'];
			$item_user_id = $item['user_id'];
			$item_id = $item['item_id'];
			$item_tipo = $item['item_tipo'];
			
			$artefato_tipo = $item_tipo;
			$artefato_titulo = return_pagina_titulo($item_id);
			$artefato_link = "pagina.php?pagina_id=$item_id";
			$artefato_criacao = $item_criacao;
			$artefato_resultado = include 'templates/artefato_item.php';
			$template_conteudo .= $artefato_resultado;
			
		}
	}
	include 'templates/page_element.php';
	
	?>
