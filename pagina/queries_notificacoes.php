<?php

	$notificacao_ativa = false;
	$notificacao_email = false;
	$pagina_notificacao = return_notificacao($pagina_id, $user_id);
	if ($pagina_notificacao != false) {
		$notificacao_ativa = (int)$pagina_notificacao[0];
		$notificacao_email = (int)$pagina_notificacao[1];
	}

	if ($notificacao_ativa == false) {
		$notificacao_cor = 'link-primary';
		$notificacao_icone = 'fa-bell-slash';
	} else {
		$notificacao_cor = 'link-teal';
		$notificacao_icone = 'fa-bell-on fa-swap-opacity';
	}