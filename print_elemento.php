<?php
	include 'engine.php';
	$pagina_id = $_GET['pagina_id'];
	if ($pagina_id == false) {
		header('Location:pagina.php?pagina_id=8');
		exit();
	}

	$pagina_info = return_pagina_info($pagina_id);
	if ($pagina_info != false) {
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_titulo = $pagina_info[6];
	} else {
		header('Location:pagina.php?pagina_id=8');
		exit();
	}

	if ($pagina_tipo != 'elemento') {
		header('Location:pagina.php?pagina_id=8');
		exit();
	}

	$elemento_info = return_elemento_info($pagina_item_id);

	$elemento_autor = $elemento_info[5];

	$final_print = false;

	$final_print .= "<h1>$pagina_titulo<br><em class='text-muted'>$elemento_autor</em></h1>";

	$pagina_texto_id = return_pagina_texto_id($pagina_id);
	$pagina_verbete = return_verbete_html($pagina_texto_id);
	if ($pagina_verbete != false) {
		$final_print .= $pagina_verbete;
	}

	$pagina_anotacoes_texto_id = return_texto_id($pagina_tipo, 'anotacoes', $pagina_id, $user_id);
	if ($pagina_anotacoes_texto_id != false) {
		$pagina_anotacao_html = return_verbete_html($pagina_anotacoes_texto_id);
		if ($pagina_anotacao_html != false) {
			$final_print .= "<div class='border rounded p-2'>";
			$final_print .= $pagina_anotacao_html;
			$final_print .= "</div>";
		}
	}

	$secoes_pagina = $conn->query("SELECT secao_pagina_id FROM Secoes WHERE pagina_id = $pagina_id ORDER BY ordem, id");
	if ($secoes_pagina->num_rows > 0) {
		while ($secao_pagina = $secoes_pagina->fetch_assoc()) {
			$secao_pagina_id = $secao_pagina['secao_pagina_id'];
			$secao_pagina_texto_id = return_pagina_texto_id($secao_pagina_id);
			$secao_pagina_verbete = return_verbete_html($secao_pagina_texto_id);
			$secao_pagina_titulo = return_pagina_titulo($secao_pagina_id);
			$final_print .= "<h2>$secao_pagina_titulo</h2>";
			if ($secao_pagina_verbete != false) {
				$final_print .= $secao_pagina_verbete;
			}

			$secao_pagina_anotacao_texto_id = return_texto_id('secao', 'anotacoes', $secao_pagina_id, $user_id);
			if ($secao_pagina_anotacao_texto_id != false) {
				$secao_pagina_anotacao_verbete_html = return_verbete_html($secao_pagina_anotacao_texto_id);
				if ($secao_pagina_anotacao_verbete_html != false) {
					$final_print .= "<div class='border rounded p-2'>";
					$final_print .= $secao_pagina_anotacao_verbete_html;
					$final_print .= "</div>";
				}
			}

		}
	}


	include 'templates/html_head.php';

?>

    <body class="print-limit p-2">

	<?php

		echo $final_print;

	?>
    </body>
<?php
	include 'templates/html_bottom.php';