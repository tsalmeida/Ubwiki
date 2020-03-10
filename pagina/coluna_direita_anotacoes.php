<?php
	
	echo "<div id='coluna_direita' class='$coluna_classes pagina_coluna'>";
	
	$template_id = 'anotacoes';
	$template_titulo = $pagina_translated['Notas de estudo'];
	$template_botoes_padrao = true;
	$template_conteudo = include 'templates/template_quill.php';
	$template_classes = 'elemento-anotacoes sticky-top';
	include 'templates/page_element.php';
	
	echo "</div>";
	
	?>