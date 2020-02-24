<?php

	include 'engine.php';

	$pagina_tipo = 'admin';
	
	if ($user_tipo != 'admin') {
		header("Location:ubwiki.php");
	}

	if (isset($_POST['trigger_atualizacao'])) {

	}

	if (isset($_POST['trigger_atualizar_textos_size'])) {
		$textos = $conn->query("SELECT id, verbete_content FROM Textos");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$conn->query("UPDATE Textos SET size = $verbete_value WHERE id = $texto_id");
			}
		}
		$textos = $conn->query("SELECT id, verbete_content FROM Textos_arquivo");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$conn->query("UPDATE Textos_arquivo SET size = $verbete_value WHERE id = $texto_id");
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
		$cursos = $conn->query("SELECT id FROM Cursos");
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$find_curso_id = $curso['id'];
				reconstruir_busca($find_curso_id);
			}
		}
	}

	if (isset($_POST['funcoes_gerais4'])) {

		$textos = $conn->query("SELECT * FROM Textos");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_tipo = $texto['tipo'];
				$texto_titulo = $texto['titulo'];
				$texto_page_id = $texto['page_id'];
				$texto_pagina_id = $texto['pagina_id'];
				$texto_texto_pagina_id = $texto['texto_pagina_id'];
				$texto_pagina_tipo = $texto['pagina_tipo'];
				$textos_arquivo = $conn->query("SELECT * FROM Textos_arquivo");
				if ($textos_arquivo->num_rows > 0) {
					while ($texto_arquivo = $textos_arquivo->fetch_assoc()) {
						$texto_arquivo_id = $texto_arquivo['id'];
						$texto_arquivo_tipo = $texto_arquivo['tipo'];
						$texto_arquivo_titulo = $texto_arquivo['titulo'];
						$texto_arquivo_page_id = $texto_arquivo['page_id'];
						$texto_arquivo_pagina_id = $texto_arquivo['pagina_id'];
						$texto_arquivo_texto_pagina_id = $texto_arquivo['texto_pagina_id'];
						$texto_arquivo_pagina_tipo = $texto_arquivo['pagina_tipo'];
						if (($texto_tipo == $texto_arquivo_tipo) && ($texto_page_id == $texto_arquivo_page_id) && ($texto_pagina_id == $texto_arquivo_pagina_id) && ($texto_texto_pagina_id == $texto_arquivo_texto_pagina_id) && ($texto_pagina_tipo == $texto_arquivo_pagina_tipo)) {
							$check = $conn->query("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
							$check = (int)$check;
							error_log($check);
							error_log("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
						}
					}
				}
			}
		}
		$textos_arquivo = $conn->query("SELECT id, tipo, page_id, pagina_id, pagina_tipo FROM Textos_arquivo WHERE texto_id IS NULL");
		if ($textos_arquivo->num_rows > 0) {
			while ($texto_arquivo = $textos_arquivo->fetch_assoc()) {
				$texto_arquivo_id = $texto_arquivo['id'];
				$texto_arquivo_tipo = $texto_arquivo['tipo'];
				$texto_arquivo_page_id = $texto_arquivo['page_id'];
				$texto_arquivo_pagina_id = $texto_arquivo['pagina_id'];
				$texto_arquivo_pagina_tipo = $texto_arquivo['pagina_tipo'];
				error_log("SELECT id FROM Textos WHERE tipo = '$texto_arquivo_tipo' AND page_id = $texto_arquivo_page_id AND pagina_id = $texto_arquivo_pagina_id AND pagina_tipo = '$texto_arquivo_pagina_tipo'");
				$textos = $conn->query("SELECT id FROM Textos WHERE tipo = '$texto_arquivo_tipo' AND page_id = $texto_arquivo_page_id AND pagina_id = $texto_arquivo_pagina_id AND pagina_tipo = '$texto_arquivo_pagina_tipo'");
				if ($textos->num_rows > 0) {
					while ($texto = $textos->fetch_assoc()) {
						$texto_id = $texto['id'];
						$check = $conn->query("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
						error_log($check);
						error_log("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
					}
				}
			}
		}
		$textos_arquivo = $conn->query("SELECT id, tipo, page_id, pagina_id, pagina_tipo FROM Textos_arquivo WHERE texto_id IS NULL");
		if ($textos_arquivo->num_rows > 0) {
			while ($texto_arquivo = $textos_arquivo->fetch_assoc()) {
				$texto_arquivo_id = $texto_arquivo['id'];
				$texto_arquivo_tipo = $texto_arquivo['tipo'];
				$texto_arquivo_page_id = $texto_arquivo['page_id'];
				$texto_arquivo_pagina_id = $texto_arquivo['pagina_id'];
				$texto_arquivo_pagina_tipo = $texto_arquivo['pagina_tipo'];
				error_log("SELECT id FROM Textos WHERE tipo = '$texto_arquivo_tipo' AND page_id = $texto_arquivo_page_id AND pagina_id = $texto_arquivo_pagina_id AND pagina_tipo = '$texto_arquivo_pagina_tipo'");
				$textos = $conn->query("SELECT id FROM Textos WHERE tipo = '$texto_arquivo_tipo' AND page_id = $texto_arquivo_page_id AND pagina_id = $texto_arquivo_pagina_id AND pagina_tipo = '$texto_arquivo_pagina_tipo'");
				if ($textos->num_rows > 0) {
					while ($texto = $textos->fetch_assoc()) {
						$texto_id = $texto['id'];
						$check = $conn->query("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
						error_log($check);
						error_log("UPDATE Textos_arquivo SET texto_id = $texto_id WHERE id = $texto_arquivo_id");
					}
				}
			}
		}
	}

	include 'templates/html_head.php';

?>
<body class="grey lighten-5">
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
						        <p>Reconstruir busca.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais3'>Reconstruir busca</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Função não-operacional que atualiza o Textos_arquivo.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais4'>Atualizar textos_arquivo</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Atualizar tamanhos dos textos.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='trigger_atualizar_textos_size'>Tamanhos dos textos</button>
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
