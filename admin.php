<?php
	
	include 'engine.php';
	
	$pagina_tipo = 'admin';
	
	if ($user_tipo != 'admin') {
		header("Location:ubwiki.php");
	}
	
	if (isset($_POST['trigger_atualizacao'])) {
		$conn->query("UPDATE Paginas SET subtipo = 'escritorio' WHERE compartilhamento = 'escritorio'");
		$conn->query("ALTER TABLE `Forum` ADD `familia0` VARCHAR(255) NULL DEFAULT NULL AFTER `comentario_content`, ADD `familia1` INT(11) NULL DEFAULT NULL AFTER `familia0`, ADD `familia2` INT(11) NULL DEFAULT NULL AFTER `familia1`, ADD `familia3` INT(11) NULL DEFAULT NULL AFTER `familia2`, ADD `familia4` INT(11) NULL DEFAULT NULL AFTER `familia3`, ADD `familia5` INT(11) NULL DEFAULT NULL AFTER `familia4`, ADD `familia6` INT(11) NULL DEFAULT NULL AFTER `familia5`, ADD `familia7` INT(11) NULL DEFAULT NULL AFTER `familia6`;");
		$forums = $conn->query("SELECT id, pagina_id FROM Forum");
		if ($forums->num_rows > 0) {
			while ($forum = $forums->fetch_assoc()) {
				$forum_id = $forum['id'];
				$forum_pagina_id = $forum['pagina_id'];
				$familia = return_familia($forum_pagina_id);
				$familia0 = $familia[0];
				$familia1 = $familia[1];
				$familia2 = $familia[2];
				$familia3 = $familia[3];
				$familia4 = $familia[4];
				$familia5 = $familia[5];
				$familia6 = $familia[6];
				$familia7 = $familia[7];
				if ($familia0 == false) {
					$familia0 = "NULL";
				}
				if ($familia1 == false) {
					$familia1 = "NULL";
				}
				if ($familia2 == false) {
					$familia2 = "NULL";
				}
				if ($familia3 == false) {
					$familia3 = "NULL";
				}
				if ($familia4 == false) {
					$familia4 = "NULL";
				}
				if ($familia5 == false) {
					$familia5 = "NULL";
				}
				if ($familia6 == false) {
					$familia6 = "NULL";
				}
				if ($familia7 == false) {
					$familia7 = "NULL";
				}
				$conn->query("UPDATE Forum SET familia0 = '$familia0', familia1 = $familia1, familia2 = $familia2, familia3 = $familia3, familia4 = $familia4, familia5 = $familia5, familia6 = $familia6, familia7 = $familia7 WHERE id = $forum_id");
			}
		}
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
		$textos_antigos = $conn->query("SELECT id, pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND texto_id IS NULL");
		if ($textos_antigos->num_rows > 0) {
			while ($texto_antigo = $textos_antigos->fetch_assoc()) {
				$texto_antigo_id = $texto_antigo['id'];
				$texto_antigo_pagina_id = $texto_antigo['pagina_id'];
				$texto_antigo_texto_id = return_texto_id('topico', 'verbete', $texto_antigo_pagina_id, false);
				if ($texto_antigo_texto_id != false) {
					$conn->query("UPDATE Textos_arquivo SET texto_id = $texto_antigo_texto_id WHERE id = $texto_antigo_id");
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
						
						$template_id = 'traducoes';
						$template_titulo = 'Traduções';
						$template_conteudo = false;
						$template_conteudo .= "<div class='row d-flex justify-content-center'>";
						
						$artefato_tipo = 'acesso_traducoes';
						$artefato_titulo = 'Acessar página de traduções';
						$artefato_col_limit = 'col-lg-4';
						$artefato_link = 'traducoes.php';
						$fa_icone = 'fa-language';
						$fa_color = 'text-primary';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$template_conteudo .= "</div>";
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
