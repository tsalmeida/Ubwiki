<?php
	
	include 'engine.php';
	
	if (isset($_POST['trigger_atualizacao'])) {
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
	
	if (isset($_POST['reconstruir_busca'])) {
		$reconstruir_curso_id = $_POST['reconstruir_curso'];
		$ordem = 0;
		$conn->query("DELETE FROM Searchbar WHERE curso_id = $reconstruir_curso_id");
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE curso_id = $reconstruir_curso_id AND estado = 1 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$reconstruir_materia_id = $row['id'];
				$reconstruir_materia_titulo = $row["titulo"];
				$ordem++;
				$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_materia_id , '$reconstruir_materia_titulo', 'materia')");
			}
		}
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE curso_id = $reconstruir_curso_id AND estado = 1 ORDER BY ordem");
		while ($row = $result->fetch_assoc()) {
			$reconstruir_materia_id = $row['id'];
			$result2 = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = $reconstruir_materia_id ORDER BY ordem");
			if ($result2->num_rows > 0) {
				while ($row2 = $result2->fetch_assoc()) {
					$reconstruir_topico_id = $row2["id"];
					$reconstruir_topico_nivel = $row2['nivel'];
					$reconstruir_topico_nivel1 = $row2["nivel1"];
					$reconstruir_topico_nivel2 = $row2["nivel2"];
					$reconstruir_topico_nivel3 = $row2["nivel3"];
					$reconstruir_topico_nivel4 = $row2["nivel4"];
					$reconstruir_topico_nivel5 = $row2["nivel5"];
					$ordem++;
					if ($reconstruir_topico_nivel == 1) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel1', 'topico')");
					} elseif ($reconstruir_topico_nivel == 2) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel2', 'topico')");
					} elseif ($reconstruir_topico_nivel == 3) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel3', 'topico')");
					} elseif ($reconstruir_topico_nivel == 4) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel4', 'topico')");
					} elseif ($reconstruir_topico_nivel == 5) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel5', 'topico')");
					}
				}
			}
		}
	}
	
	if (isset($_POST['editar_topicos_curso'])) {
		$curso_editar = $_POST['editar_topicos_curso'];
		header("Location:edicao_topicos.php?curso_id=$curso_editar");
	}
	
	if (isset($_POST['novo_curso_titulo'])) {
		$novo_curso_titulo = $_POST['novo_curso_titulo'];
		$novo_curso_sigla = $_POST['novo_curso_sigla'];
		$result = $conn->query("SELECT titulo, sigla FROM cursos WHERE sigla = '$novo_curso_sigla' AND titulo = '$novo_curso_titulo'");
		if ($result->num_rows == 0) {
			$conn->query("INSERT INTO cursos (titulo, sigla) VALUES ('$novo_curso_titulo', '$novo_curso_sigla')");
		} else {
			return false;
		}
	}
	$lista_cursos = array();
	$cursos = $conn->query("SELECT id, titulo, estado FROM cursos");
	if ($cursos->num_rows > 0) {
		while ($curso = $cursos->fetch_assoc()) {
			$curso_id_list = $curso['id'];
			$curso_titulo = $curso['titulo'];
			$curso_estado = $curso['estado'];
			$um_curso = array($curso_id_list, $curso_titulo, $curso_estado);
			array_push($lista_cursos, $um_curso);
		}
	}
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
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
						$template_id = 'editar_topicos';
						$template_titulo = 'Editar tópicos';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
                <form method='post' formaction='edicao_topicos.php'>
                    <p>Com esta ferramenta, o administrador pode alterar a tabela de tópicos de um curso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
                     <label for='editar_topicos_curso'>Curso</label>
                        <select class='form-control' name='editar_topicos_curso'>
                        ";
						foreach ($lista_cursos as $um_curso) {
							if ($um_curso[2] == 0) {
								$estado_curso = '(desativado)';
							} else {
								$estado_curso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_curso[0]'>$um_curso[1] / $estado_curso</option>";
						}
						$template_conteudo .= "</select>";
						$template_conteudo .= "
							<div class='row justify-content-center'>
								<button class='$button_classes'>Acessar ferramenta</button></form>
							</div>
					    ";
						include 'templates/page_element.php';
						
						$template_id = 'acrescentar_curso';
						$template_titulo = 'Acrescentar curso';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<form method='post' formaction='edicao_topicos.php'>";
						$template_conteudo .= "
                            <p>Cada curso tem um título completo e uma sigla. Este é o primeiro passo no processo de inclusão de novos cursos.</p>
                            <input type='text' id='novo_curso_titulo' name='novo_curso_titulo' class='form-control validate' required>
                            <label data-error='inválido' data-successd='válido' for='novo_curso_titulo'>Título do curso</label>
                            <input type='text' id='novo_curso_sigla' name='novo_curso_sigla' class='form-control validate' required>
                            <label data-error='inválido' data-successd='válido' for='novo_curso_sigla'>Sigla do curso</label>
                            <div class='row justify-content-center'>
                            	<button class='$button_classes' type='submit'>Acrescentar curso</button>
                            </div>
                            </form>
                          ";
						include 'templates/page_element.php';
						
						$template_id = 'barra_busca';
						$template_titulo = 'Barra de busca';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
                            <form method='post'>
                			<p>Reconstruir tabela de opções da barra de busca.</p>
                    		<label for='editar_topicos_curso'>Curso</label>
                    		<select class='form-control' name='reconstruir_curso' id='reconstruir_curso'>
                        ";
						
						foreach ($lista_cursos as $um_curso) {
							if ($um_curso[2] == 0) {
								$estado_curso = '(desativado)';
							} else {
								$estado_curso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_curso[0]'>$um_curso[1] / $estado_curso</option>";
						}
						
						$template_conteudo .= "
                              </select>
                              <div class='row justify-content-center'>
                              	<button class='$button_classes' type='submit' name='reconstruir_busca'>Reconstruir
                              </div>
                              </button>
                          </form>
                        ";
						include 'templates/page_element.php';
						
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
        <div id='coluna_direita' class='<?php echo $coluna_classes; ?>'>
					<?php
						
						$template_id = 'anotacoes_admin';
						$template_titulo = 'Anotações';
						$template_quill_page_id = 0;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
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
