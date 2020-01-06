<?php
	
	include 'engine.php';
	
	if (isset($_POST['trigger_atualizacao'])) {
		$conn->query("UPDATE Materias SET user_id = 1 WHERE curso_id = 1");
		$conn->query("UPDATE Materias SET user_id = 1 WHERE curso_id = 2");
		$conn->query("UPDATE Materias SET user_id = 1 WHERE curso_id = 4");
		$conn->query("UPDATE Materias SET user_id = 4 WHERE curso_id = 5");
		$cursos = $conn->query("SELECT id, pagina_id, titulo, user_id FROM Cursos");
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$curso_id = $curso['id'];
				$curso_pagina_id = $curso['pagina_id'];
				$curso_titulo = $curso['titulo'];
				$curso_user_id = $curso['user_id'];
				$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($curso_pagina_id, 'curso', NULL, 'titulo', '$curso_titulo', $curso_user_id)");
			}
		}
		$cursos = $conn->query("SELECT pagina_id, id FROM Cursos");
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$curso_id = $curso['id'];
				$curso_pagina_id = $curso['pagina_id'];
				$materias = $conn->query("SELECT pagina_id, titulo, user_id FROM Materias WHERE curso_id = $curso_id");
				if ($materias->num_rows > 0) {
					while ($materia = $materias->fetch_assoc()) {
						$materia_pagina_id = $materia['pagina_id'];
						$materia_titulo = $materia['titulo'];
						$materia_user_id = $materia['user_id'];
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo) VALUES ($curso_pagina_id, 'curso', $materia_pagina_id, 'materia')");
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($materia_pagina_id, 'pagina', 'titulo', '$materia_titulo')");
						$conn->query("UPDATE Paginas SET item_id = $curso_pagina_id, user_id = $materia_user_id WHERE id = $materia_pagina_id");
					}
				}
			}
		}
		$materias = $conn->query("SELECT id, pagina_id, user_id FROM Materias");
		if ($materias->num_rows > 0) {
			while ($materia = $materias->fetch_assoc()) {
				$materia_id = $materia['id'];
				$materia_pagina_id = $materia['pagina_id'];
				$materia_user_id = $materia['user_id'];
				$topicos1 = $conn->query("SELECT pagina_id, nivel1, id FROM Topicos WHERE materia_id = $materia_id AND nivel = 1");
				if ($topicos1->num_rows > 0) {
					while ($topico1 = $topicos1->fetch_assoc()) {
						$topico1_pagina_id = $topico1['pagina_id'];
						$topico1_titulo = $topico1['nivel1'];
						$topico1_id = $topico1['id'];
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($topico1_pagina_id, 'pagina', 'titulo', '$topico1_titulo')");
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo) VALUES ($materia_pagina_id, 'materia', $topico1_pagina_id, 'topico')");
						$conn->query("UPDATE Paginas SET item_id = $materia_pagina_id, user_id = $materia_user_id WHERE id = $topico1_pagina_id");
						
						$topicos2 = $conn->query("SELECT pagina_id, nivel2, id FROM Topicos WHERE nivel1 = $topico1_id AND nivel = 2");
						if ($topicos2->num_rows > 0) {
							while ($topico2 = $topicos2->fetch_assoc()) {
								$topico2_pagina_id = $topico2['pagina_id'];
								$topico2_titulo = $topico2['nivel2'];
								$topico2_id = $topico2['id'];
								$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($topico2_pagina_id, 'pagina', 'titulo', '$topico2_titulo')");
								$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo) VALUES ($topico1_pagina_id, 'topico', $topico2_pagina_id, 'subtopico')");
								$conn->query("UPDATE Paginas SET item_id = $topico1_pagina_id, user_id = $materia_user_id WHERE id = $topico2_pagina_id");
								
								$topicos3 = $conn->query("SELECT pagina_id, nivel3, id FROM Topicos WHERE nivel2 = $topico2_id AND nivel = 3");
								if ($topicos3->num_rows > 0) {
									while ($topico3 = $topicos3->fetch_assoc()) {
										$topico3_pagina_id = $topico3['pagina_id'];
										$topico3_titulo = $topico3['nivel3'];
										$topico3_id = $topico3['id'];
										$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($topico3_pagina_id, 'pagina', 'titulo', '$topico3_titulo')");
										$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo) VALUES ($topico2_pagina_id, 'topico', $topico3_pagina_id, 'subtopico')");
										$conn->query("UPDATE Paginas SET item_id = $topico2_pagina_id, user_id = $materia_user_id WHERE id = $topico3_pagina_id");
										
										$topicos4 = $conn->query("SELECT pagina_id, nivel4, id FROM Topicos WHERE nivel3 = $topico3_id AND nivel = 4");
										if ($topicos4->num_rows > 0) {
											while ($topico4 = $topicos4->fetch_assoc()) {
												$topico4_pagina_id = $topico4['pagina_id'];
												$topico4_titulo = $topico4['nivel4'];
												$topico4_id = $topico4['id'];
												$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($topico4_pagina_id, 'pagina', 'titulo', '$topico4_titulo')");
												$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo) VALUES ($topico3_pagina_id, 'topico', $topico4_pagina_id, 'subtopico')");
												$conn->query("UPDATE Paginas SET item_id = $topico3_pagina_id, user_id = $materia_user_id WHERE id = $topico4_pagina_id");
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	if (isset($_POST['funcoes_gerais'])) {
		$conn->query("TRUNCATE `Ubwiki`.`sim_detalhes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_edicoes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_edicoes_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_etapas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_etapas_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_gerados`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_provas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_provas_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_questoes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_questoes_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_respostas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_textos_apoio`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_textos_apoio_arquivo`");
	}
	
	if (isset($_POST['funcoes_gerais2'])) {
		$conn->query("TRUNCATE `Ubwiki`.`sim_detalhes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_gerados`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_respostas`");
	}
	
	if (isset($_POST['funcoes_gerais3'])) {
	
	}
	
	
	include 'templates/html_head.php';

?>
<body class="carrara">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo_context = true;
		$template_titulo = 'Página de Administradores';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row justify-content-around">
        <div id='coluna_esquerda' class="<?php echo $coluna_classes; ?>">
					<?php
						
						$template_id = 'funcoes_gerais';
						$template_titulo = 'Funções gerais';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
						    <form method='post'>
						        <p>Simulados.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes_red' type='submit' name='funcoes_gerais'>Apagar todos os dados sobre simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Simulados/usuários.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais2'>Apagar dados de regitro em simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Função geral de atualização.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais3'>Função geral de atualização</button>
						        </div>
						    </form>
						";
						include 'templates/page_element.php';
						
						$template_id = 'atualizacao';
						$template_titulo = 'Atualização';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
						    <form method='post'>
						        <p>Atualização desde 20191204</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='trigger_atualizacao' value='20191210'>Atualizar página</button>
				                </div>
						    </form>
						";
						include 'templates/page_element.php';
					
					?>

        </div>
    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_admin';
	include 'templates/esconder_anotacoes.php';
?>
</html>
