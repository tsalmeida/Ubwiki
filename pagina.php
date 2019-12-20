<?php
	
	include 'engine.php';
	if (!isset($_GET['pagina_id'])) {
		header('Location:escritorio.php');
	} else {
		$pagina_id = $_GET['pagina_id'];
	}
	
	$paginas = $conn->query("SELECT page_id, tipo FROM Paginas WHERE id = $pagina_id");
	if ($paginas->num_rows > 0) {
		while ($pagina = $paginas->fetch_assoc()) {
			$pagina_page_id = $pagina['page_id'];
			$pagina_tipo = $pagina['tipo'];
		}
		if ($pagina_tipo == 'verbete') {
			$topico_id = $pagina_page_id;
			$concurso_id = return_concurso_id_topico($topico_id);
			$topico_anterior = false;
			$topico_proximo = false;
		} elseif ($pagina_tipo == 'materia') {
			$materia_id = $pagina_page_id;
			$concurso_id = return_concurso_id_materia($materia_id);
		} elseif ($pagina_tipo == 'elemento') {
			$elemento_id = $pagina_page_id;
		} elseif ($pagina_tipo == 'grupo') {
			$grupo_id = $pagina_page_id;
		}
	} else {
		if (isset($_GET['topico_id'])) {
			$topico_id = $_GET['topico_id'];
			$pagina_tipo = 'verbete';
		} elseif (isset($_GET['elemento_id'])) {
			$elemento_id = $_GET['elemento_id'];
			$pagina_tipo = 'elemento';
		} elseif (isset($_GET['materia_id'])) {
			$materia_id = $_GET['materia_id'];
			$pagina_tipo = 'materia';
		} elseif (isset($_GET['grupo_id'])) {
			$grupo_id = $_GET['grupo_id'];
			$pagina_tipo = 'grupo';
		} else {
			header('Location:escritorio.php');
		}
	}
	
	if ($pagina_tipo == 'verbete') {
		$topicos = $conn->query("SELECT estado_pagina, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE concurso_id = $concurso_id AND id = $topico_id");
		if ($topicos->num_rows > 0) {
			while ($topico = $topicos->fetch_assoc()) {
				$topico_estado_pagina = $topico['estado_pagina'];
				$topico_materia_id = $topico['materia_id'];
				$topico_nivel = $topico['nivel'];
				$topico_ordem = $topico['ordem'];
				$topico_nivel1 = $topico['nivel1'];
				$topico_nivel2 = $topico['nivel2'];
				$topico_nivel3 = $topico['nivel3'];
				$topico_nivel4 = $topico['nivel4'];
				$topico_nivel5 = $topico['nivel5'];
				if ($topico_nivel == 2) {
					$topico_titulo = $topico_nivel2;
				} elseif ($topico_nivel == 1) {
					$topico_titulo = $topico_nivel1;
				} elseif ($topico_nivel == 3) {
					$topico_titulo = $topico_nivel3;
				} elseif ($topico_nivel == 4) {
					$topico_titulo = $topico_nivel4;
				} elseif ($topico_nivel == 5) {
					$topico_titulo = $topico_nivel5;
				}
			}
		}
	}
	
	
	$nao_contar = false;
	
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, $pagina_tipo)");
	}
?>
<body>
<?
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row justify-content-between"></div>
</div>
<div class="container">
	<?php
		$template_titulo_context = true;
		if ($pagina_tipo == 'verbete') {
			$template_titulo = $topico_titulo;
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="row justify-content-around">

</div>
