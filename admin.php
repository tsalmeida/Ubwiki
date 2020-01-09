<?php
	
	include 'engine.php';
	
	if ($user_tipo != 'admin') {
	    header("Location:escritorio.php");
    }
	
	if (isset($_POST['trigger_atualizacao'])) {
		$conn->query("ALTER TABLE `Searchbar` CHANGE `page_id` `pagina_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Searchbar` DROP `tipo`;");
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
						        <p>Reconstruir busca.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais3'>Reconstruir busca</button>
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
