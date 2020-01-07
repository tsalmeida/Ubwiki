<?php
	include 'engine.php';
	include 'templates/html_head.php';
	if (isset($_GET['busca'])) {
		$busca = $_GET['busca'];
	} else {
		header('Location:pagina.php?pagina_id=4');
	}
	
	$curso_paginas = return_curso_paginas($curso_id, 'all');

?>
<body class="carrara">
<?php
	include 'templates/navbar.php';
?>
<div class="container py-5">
	<?php
		$template_id = 'busca_titulo';
		$template_titulo = 'Resultados de busca';
		$template_botoes = false;
		$template_botoes_padrao = false;
		$template_conteudo = false;
		$template_conteudo .= "<h3>Termos de busca: \"$busca\"</h3>";
		$template_conteudo .= "<h3>Curso: $curso_titulo</h3>";
		include 'templates/page_element.php';
		
		$template_id = 'busca_paginas';
		$template_titulo = 'Busca em títulos de páginas';
		$template_botoes = false;
		$template_conteudo = false;
		
		$busca_titulos = false;
		foreach ($curso_paginas as $curso_pagina_id) {
			$curso_pagina_info = return_pagina_info($curso_pagina_id);
			$curso_pagina_titulo = $curso_pagina_info[6];
			$curso_pagina_estado = $curso_pagina_info[3];
			$curso_pagina_icone = return_estado_icone($curso_pagina_estado, 'materia');
			if (stripos($curso_pagina_titulo, $busca) !== false) {
				$busca_titulos = true;
				$template_conteudo .= "<a href='pagina.php?pagina_id=$curso_pagina_id'><li class='list-group-item list-group-item-action d-flex justify-content-between'><span>$curso_pagina_titulo</span><span><i class='$curso_pagina_icone'></i></span></li></a>";
			}
		}
		if ($busca_titulos == false) {
			$template_conteudo .= "<p><span class='text-muted'>Nenhum resultado encontrado.</span></p>";
		}
		
		include 'templates/page_element.php';
		
		$template_id = 'busca_verbetes';
		$template_titulo = 'Busca em verbetes';
		$template_botoes = false;
		
		$template_conteudo = false;
		$verbetes_resultados_materias = false;
		$verbetes_resultados_topicos = false;
		$verbetes = $conn->query("SELECT pagina_tipo, pagina_id, verbete_text FROM Textos WHERE verbete_text LIKE '%$busca%' AND curso_id = $curso_id ORDER BY pagina_tipo");
		if ($verbetes->num_rows > 0) {
			while ($verbete = $verbetes->fetch_assoc()) {
				$verbete_texto_pagina_id = $verbete['pagina_id'];
				$verbete_texto_pagina_titulo = return_pagina_titulo($verbete_texto_pagina_id);
				$verbete_texto_pagina_tipo = $verbete['pagina_tipo'];
				$verbete_text = $verbete['verbete_text'];
				$verbete_text = substr($verbete_text, 0, 200);
				if ($verbete_texto_pagina_tipo == 'topico') {
					$verbetes_resultados_topicos .= "<a href='pagina.php?pagina_id=$verbete_texto_pagina_id'><li class='list-group-item list-group-item-action p-limit'><strong class='d-block'>$verbete_texto_pagina_titulo</strong> $verbete_text...</li></a>";
				} elseif ($verbete_texto_pagina_tipo == 'materia') {
					$verbetes_resultados_materias .= "<a href='pagina.php?pagina_id=$verbete_texto_pagina_id'><li class='list-group-item list-group-item-action p-limit'><strong class='d-block'>$verbete_texto_pagina_titulo</strong> $verbete_text...</li></a>";
				}
			}
		}
		$template_conteudo .= "<h2>Matérias</h2>";
		if ($verbetes_resultados_materias != false) {
			$template_conteudo .= $verbetes_resultados_materias;
		} else {
			$template_conteudo .= "<p><span class='text-muted'>Nenhum resultado encontrado.</span></p>";
		}
		$template_conteudo .= "<h2 class='mt-3'>Tópicos</h2>";
		if ($verbetes_resultados_topicos != false) {
			$template_conteudo .= $verbetes_resultados_topicos;
		} else {
			$template_conteudo .= "<p><span class='text-muted'>Nenhum resultado encontrado.</span></p>";
		}
		include 'templates/page_element.php';
	
	?>
</div>
</body>
<?php
	include 'templates/html_bottom.php';
?>
