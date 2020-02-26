<?php
	
	$template_id = 'membros_grupo';
	$template_titulo = 'Membros';
	$template_conteudo = false;
	$template_conteudo_class = 'justify-content-start';
	$template_conteudo_no_col = true;
	
	if ($pagina_user_id == $user_id) {
		$carregar_convite = true;
		$artefato_tipo = 'novo_membro';
		$artefato_titulo = 'Gerenciar membros';
		$artefato_modal = '#modal_convidar_ou_remover';
		$fa_icone = 'fa-users-cog';
		$fa_color = 'text-default';
		$artefato_link = false;
		$artefato_criacao = false;
		$template_conteudo .= include 'templates/artefato_item.php';
	}
	
	if ($membros->num_rows > 0) {
		while ($membro = $membros->fetch_assoc()) {
			$membro_user_id = $membro['membro_user_id'];
			$membro_estado = $membro['estado'];
			$membro_user_apelido = return_apelido_user_id($membro_user_id);
			
			$artefato_tipo = 'membro';
			$artefato_titulo = $membro_user_apelido;
			$artefato_link = "pagina.php?user_id=$membro_user_id";
			$artefato_criacao = false;
			$avatar_info = return_avatar($membro_user_id);
			$fa_icone = $avatar_info[0];
			$fa_color = $avatar_info[1];
			
			if (is_null($membro_estado)) {
				$fa_color = 'text-light';
				$artefato_subtitulo = 'Convite enviado';
			}
			
			if ($pagina_user_id == $membro_user_id) {
				$artefato_subtitulo = 'Fundador';
			}
			
			$template_conteudo .= include 'templates/artefato_item.php';
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
			$item_user_apelido = return_apelido_user_id($item_user_id);
			
			$artefato_tipo = $item_tipo;
			$artefato_pagina_info = return_pagina_info($item_id);
			$artefato_titulo = $artefato_pagina_info[6];
			$artefato_pagina_tipo = $artefato_pagina_info[2];
			$artefato_pagina_subtipo = $artefato_pagina_info[8];
			if ($artefato_pagina_tipo == 'texto') {
				$fa_icone = 'fa-file-alt fa-swap-opacity';
			} else {
				$artefato_info = return_pagina_icone_cor($artefato_pagina_tipo, $artefato_pagina_subtipo);
				$fa_icone = $artefato_info[0];
				$fa_color = $artefato_info[1];
			}
			/*if ($artefato_titulo == false) {
				if ($item_tipo == 'texto') {
					$nota_texto_id = $artefato_pagina_info[1];
					$nota_texto_info = return_texto_info($nota_texto_id);
					$nota_texto_page_id = $nota_texto_info[3];
					$artefato_titulo = return_pagina_titulo($nota_texto_page_id);
					$artefato_titulo = $artefato_titulo;
				}
			}*/
			$artefato_subtitulo = $item_user_apelido;
			$artefato_link = "pagina.php?pagina_id=$item_id";
			$artefato_criacao = $item_criacao;
			$artefato_resultado = include 'templates/artefato_item.php';
			$template_conteudo .= $artefato_resultado;
			
		}
	}
	include 'templates/page_element.php';
	
	?>
