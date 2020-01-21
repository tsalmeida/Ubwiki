<?php
	
	echo "<div id='coluna_direita' class='$coluna_classes pagina_coluna'>";
	
	$template_id = 'anotacoes';
	$template_titulo = 'Notas de estudo';
	$template_botoes_padrao = true;
	$template_conteudo = include 'templates/template_quill.php';
	include 'templates/page_element.php';
	
	echo "</div>";
	
	?>