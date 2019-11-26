<?php
	
	include 'engine.php';
	
	if (isset($_POST['funcoes_gerais'])) {
        $results = $conn->query("SELECT id, verbete_content FROM Textos WHERE verbete_content IS NOT NULL");
        if ($results->num_rows > 0) {
            while ($result = $results->fetch_assoc()) {
                $id = $result['id'];
                $verbete_content = $result['verbete_content'];
                $verbete_content = urldecode($verbete_content);
                $verbete_content = mysqli_real_escape_string($conn, $verbete_content);
                $conn->query("UPDATE Textos SET verbete_content = '$verbete_content' WHERE id = $id");
            }
        }
    }
	
	if (isset($_POST['reconstruir_busca'])) {
		$reconstruir_concurso_id = $_POST['reconstruir_concurso'];
		$ordem = 0;
		$conn->query("DELETE FROM Searchbar WHERE concurso_id = $reconstruir_concurso_id");
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE concurso_id = $reconstruir_concurso_id AND estado = 1 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$reconstruir_materia_id = $row['id'];
				$reconstruir_materia_titulo = $row["titulo"];
				$ordem++;
				$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_materia_id , '$reconstruir_materia_titulo', 'materia')");
			}
		}
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE concurso_id = $reconstruir_concurso_id AND estado = 1 ORDER BY ordem");
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
						$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel1', 'topico')");
					} elseif ($reconstruir_topico_nivel == 2) {
						$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel2', 'topico')");
					} elseif ($reconstruir_topico_nivel == 3) {
						$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel3', 'topico')");
					} elseif ($reconstruir_topico_nivel == 4) {
						$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel4', 'topico')");
					} elseif ($reconstruir_topico_nivel == 5) {
						$conn->query("INSERT INTO Searchbar (ordem, concurso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_concurso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel5', 'topico')");
					}
				}
			}
		}
	}
	
	if (isset($_POST['editar_topicos_concurso'])) {
		$concurso_editar = $_POST['editar_topicos_concurso'];
		header("Location:edicao_topicos.php?concurso_id=$concurso_editar");
	}
	
	if (isset($_POST['novo_concurso_titulo'])) {
		$novo_concurso_titulo = $_POST['novo_concurso_titulo'];
		$novo_concurso_sigla = $_POST['novo_concurso_sigla'];
		$result = $conn->query("SELECT titulo, sigla FROM Concursos WHERE sigla = '$novo_concurso_sigla' AND titulo = '$novo_concurso_titulo'");
		if ($result->num_rows == 0) {
			$conn->query("INSERT INTO Concursos (titulo, sigla) VALUES ('$novo_concurso_titulo', '$novo_concurso_sigla')");
		} else {
			return false;
		}
	}
	$lista_concursos = array();
	$result = $conn->query("SELECT id, titulo, estado FROM Concursos");
	while ($row = $result->fetch_assoc()) {
		$concurso_id = $row['id'];
		$concurso_titulo = $row['titulo'];
		$concurso_estado = $row['estado'];
		$um_concurso = array($concurso_id, $concurso_titulo, $concurso_estado);
		array_push($lista_concursos, $um_concurso);
	}
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';
	include 'templates/imagehandler.php';

?>
<body>
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
                    <p>Com esta ferramenta, o administrador pode alterar a tabela de tópicos de um concurso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
                     <label for='editar_topicos_concurso'>Concurso</label>
                        <select class='form-control' name='editar_topicos_concurso'>
                        ";
						foreach ($lista_concursos as $um_concurso) {
							if ($um_concurso[2] == 0) {
								$estado_concurso = '(desativado)';
							} else {
								$estado_concurso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_concurso[0]'>$um_concurso[1] / $estado_concurso</option>";
						}
						$template_conteudo .= "</select>";
						$template_conteudo .= "<button class='$button_classes'>Acessar ferramenta</button></form>";
						include 'templates/page_element.php';

						$template_id = 'acrescentar_concurso';
						$template_titulo = 'Acrescentar concurso';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<form method='post' formaction='edicao_topicos.php'>";
						$template_conteudo .= "
                <p>Cada concurso tem um título completo e uma sigla. Este é o primeiro passo no processo de inclusão de novos concursos.</p>
              <div class='row'>
                <input type='text' id='novo_concurso_titulo' name='novo_concurso_titulo' class='form-control validate' required>
                <label data-error='inválido' data-successd='válido' for='novo_concurso_titulo'>Título do concurso</label>
              </div>
              <div class='row'>
                <input type='text' id='novo_concurso_sigla' name='novo_concurso_sigla' class='form-control validate' required>
                <label data-error='inválido' data-successd='válido' for='novo_concurso_sigla'>Sigla do concurso</label>
              </div>
            <button class='$button_classes' type='submit'>Acrescentar concurso</button>
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
                    <label for='editar_topicos_concurso'>Concurso</label>
                    <select class='form-control' name='reconstruir_concurso' id='reconstruir_concurso'>
                        ";
						
						foreach ($lista_concursos as $um_concurso) {
							if ($um_concurso[2] == 0) {
								$estado_concurso = '(desativado)';
							} else {
								$estado_concurso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_concurso[0]'>$um_concurso[1] / $estado_concurso</option>";
						}
						
						$template_conteudo .= "
                                            </select>
                <button class='$button_classes' type='submit' name='reconstruir_busca'>Reconstruir
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
						        <p>desfazer URI.</p>
						        <button class='$button_classes' type='submit' name='funcoes_gerais'>Ativar função</button>
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
						$template_conteudo = include 'templates/quill_form.php';
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
