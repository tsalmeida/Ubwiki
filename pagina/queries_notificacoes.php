<?php
	
	$pagina_notificacao = return_notificacao($pagina_id, $user_id);
	$notificacao_ativa = (int)$pagina_notificacao[0];
	$notificacao_email = (int)$pagina_notificacao[1];
	
	if ($notificacao_ativa == false) {
		$notificacao_cor = 'text-muted';
		$notificacao_icone = 'fa-bell-slash';
	} else {
		$notificacao_cor = 'text-warning';
		$notificacao_icone = 'fa-bell';
	}