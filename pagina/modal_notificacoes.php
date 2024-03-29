<?php
	
	$template_modal_div_id = 'modal_notificacoes';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Notificações'];
	$template_modal_body_conteudo = false;
	$carregar_notificacoes = true;
	$template_modal_body_conteudo .= "
        <input type='hidden' name='notificacao_pagina_id' id='notificacao_pagina_id' value='$pagina_id' required>
        <input type='hidden' name='notificacao_ativa' id='notificacao_ativa' value='$notificacao_ativa' required>
        <input type='hidden' name='notificacao_email' id='notificacao_email' value='$notificacao_email' required>
        <div class='row d-flex justify-content-center'>
    ";
	
	$artefato_tipo = 'notificar_ativo';
	$artefato_titulo = $pagina_translated['Notificar'];
	$fa_icone = 'fa-bell';
	$fa_color = 'link-warning';
	$artefato_class = 'artefato_opcao_notificar';
	$artefato_col_limit = 'col-4';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'notificar_inativo';
	$artefato_titulo = $pagina_translated['Não notificar'];
	$fa_icone = 'fa-bell-slash';
	$fa_color = 'link-secondary';
	$artefato_class = 'artefato_opcao_nao_notificar';
	$artefato_col_limit = 'col-4';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	if ($user_tipo == 'admin') {
		$artefato_tipo = 'notificar_email';
		$artefato_titulo = $pagina_translated['Notificar por email'];
		$fa_icone = 'fa-mailbox';
		$fa_color = 'link-primary';
		$artefato_class = 'artefato_opcao_notificar_email';
		$artefato_col_limit = 'col-4';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'nao_notificar_email';
		$artefato_titulo = $pagina_translated['Não notificar por email'];
		$fa_icone = 'fa-mailbox';
		$fa_color = 'text-muted';
		$artefato_class = 'artefato_opcao_nao_notificar_email';
		$artefato_col_limit = 'col-4';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	}
	
	$template_modal_body_conteudo .= "</div>";
	
	include 'templates/modal.php';