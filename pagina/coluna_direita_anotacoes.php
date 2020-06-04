<?php

	echo "<div id='coluna_direita' class='$coluna_classes pagina_coluna'>";

	$template_id = 'anotacoes';
	if ($pagina_subtipo == 'modelo') {
		$template_titulo = $pagina_translated['Sua versão'];
	} else {
		$template_titulo = "<span class='text-muted'><i class='fad $user_avatar_icone fa-fw'></i></span>{$pagina_translated['Notas de estudo']}";
	}
	$template_botoes_padrao = true;
	$template_conteudo = include 'templates/template_quill.php';
	$template_classes = 'elemento-anotacoes sticky-top';
	include 'templates/page_element.php';

	echo "</div>";

?>