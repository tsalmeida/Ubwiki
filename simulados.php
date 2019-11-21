<?php
	
	include 'engine.php';
	include 'templates/html_head.php';
	
	$concurso_id = false;
	$concurso_sigla = false;
	if (isset($_GET['concurso_id'])) {
		$concurso_id = $_GET['concurso_id'];
		$concursos = $conn->query("SELECT sigla FROM Concursos WHERE id = $concurso_id");
		if ($concursos->num_rows > 0) {
			while ($concurso = $concursos->fetch_assoc()) {
				$concurso_sigla = $concurso['sigla'];
			}
		}
	}
	else {
		header('Location:index.php');
	}
	
	
?>

<body>

<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla: Plataforma de Simulados";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
	<div class="row d-flex justify-content-around">
		<div id="coluna_esquerda" class="col-lg-5 col-sm-12">
			<?php
				$template_id = 'adicionar_questao';
				$template_titulo = 'Adicionar questão';
				$template_conteudo = false;
				$template_conteudo .= "<form method='post'>";
				$template_conteudo .= "";
				$template_conteudo .= "</form>";
				include 'templates/page_element.php';
			?>
		</div>
		<div id="coluna_direita" class="col-lg-5 col-sm-12">
			<?php
				$template_id = 'gerar_simulado';
				$template_titulo = 'Gerar simulado';
				$template_conteudo = false;
				include 'templates/page_element.php';
			?>
		</div>
	</div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
