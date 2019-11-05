<?php
	
	include 'engine.php';
	
	if (isset($_POST['otimizar_temas_concurso'])) {
		$concurso = $_POST['otimizar_temas_concurso'];
		$ordem = 0;
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$id = $row["id"];
				$nivel1 = $row["nivel1"];
				$nivel2 = $row["nivel2"];
				$nivel3 = $row["nivel3"];
				$nivel4 = $row["nivel4"];
				$nivel5 = $row["nivel5"];
				if ($nivel5 != false) {
					$conn->query("UPDATE Temas SET nivel = 5 WHERE id = '$id'");
				} elseif ($nivel4 != false) {
					$conn->query("UPDATE Temas SET nivel = 4 WHERE id = '$id'");
				} elseif ($nivel3 != false) {
					$conn->query("UPDATE Temas SET nivel = 3 WHERE id = '$id'");
				} elseif ($nivel2 != false) {
					$conn->query("UPDATE Temas SET nivel = 2 WHERE id = '$id'");
				} else {
					$conn->query("UPDATE Temas SET nivel = 1 WHERE id = '$id'");
				}
			}
		}
	}
	
	if (isset($_POST['reconstruir_busca'])) {
		$concurso = $_POST['reconstruir_concurso'];
		$ordem = 0;
		$conn->query("DELETE FROM Searchbar WHERE concurso = '$concurso'");
		$result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$sigla = $row["sigla"];
				$materia = $row["materia"];
				$ordem++;
				$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', '$sigla', '$materia', 'materia')");
			}
		}
		$result = $conn->query("SELECT nivel1, nivel2, nivel3, nivel4, nivel5, id FROM Temas WHERE concurso = '$concurso' ORDER BY id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$id = $row["id"];
				$nivel1 = $row["nivel1"];
				$nivel2 = $row["nivel2"];
				$nivel3 = $row["nivel3"];
				$nivel4 = $row["nivel4"];
				$nivel5 = $row["nivel5"];
				$ordem++;
				if ($nivel5 != false) {
					$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel5', 'tema')");
				} elseif ($nivel4 != false) {
					$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel4', 'tema')");
				} elseif ($nivel3 != false) {
					$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel3', 'tema')");
				} elseif ($nivel2 != false) {
					$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel2', 'tema')");
				} else {
					$conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel1', 'tema')");
				}
			}
		}
	}
	
	if (isset($_POST['editar_topicos_concurso'])) {
		$metalinguagem_concurso = $_POST['editar_topicos_concurso'];
		header("Location:edicao_topicos.php?concurso=$metalinguagem_concurso");
	}
	
	$admin_mensagens = false;
	
	if (isset($_POST['quill_nova_mensagem_html'])) {
		$nova_mensagem = $_POST['quill_nova_mensagem_html'];
		$nova_mensagem = strip_tags($nova_mensagem, '<p><li><ul><ol><h2><h3><blockquote><em><sup><s>');
		$conn->query("INSERT INTO Admin_data (tipo, conteudo) VALUES ('notas', '$nova_mensagem')");
		$admin_mensagens = $nova_mensagem;
	} else {
		$result = $conn->query("SELECT conteudo FROM Admin_data WHERE tipo = 'notas' ORDER BY id DESC");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$admin_mensagens = $row['conteudo'];
				break;
			}
		}
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
	$result = $conn->query("SELECT titulo, sigla, estado FROM Concursos");
	while ($row = $result->fetch_assoc()) {
		$sigla = $row['sigla'];
		$titulo = $row['titulo'];
		$estado = $row['estado'];
		$um_concurso = array($sigla, $titulo, $estado);
		array_push($lista_concursos, $um_concurso);
	}
	
	top_page(false, "quill_admin");
?>
<body>
<?php
	carregar_navbar('dark');
	standard_jumbotron("Página dos Administradores", false);
?>
<div class="container-fluid my-5 px-3">
    <div class="row justify-content-around">
        <div class="col-lg-5 col-sm-12">
	        <?php
		        $template_id = 'editar_topicos';
		        $template_collapse_stuff = false;
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
				        $estado = '(desativado)';
			        } else {
				        $estado = '(ativado)';
			        }
			        $template_conteudo .= "<option value='$um_concurso[0]'>$um_concurso[1] / $estado</option>";
		        }
		        $template_conteudo .= "</select>";
		        $template_conteudo .= "<button class='btn btn-primary btn-block btn-md'>Acessar ferramenta</button></form>";
		        include 'templates/page_element.php';
		
		        $template_id = 'acrescentar_concurso';
		        $template_collapse_stuff = false;
		        $template_titulo = 'Acrescentar concurso';
		        $template_botoes = false;
		        $template_conteudo = false;
		        $template_conteudo .= "<form method='post' formaction='edicao_topicos.php'>";
		        $template_conteudo .= "
                <p>Cada concurso tem um título completo e uma sigla. Este é o primeiro passo no processo de inclusão de novos concursos.</p>
              <div class='row'>
                <input type='text' id='novo_concurso_titulo' name='novo_concurso_titulo' class='form-control validate' required>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_concurso_titulo'>Título do concurso</label>
              </div>
              <div class='row'>
                <input type='text' id='novo_concurso_sigla' name='novo_concurso_sigla' class='form-control validate' required>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_concurso_sigla'>Sigla do concurso</label>
              </div>
            <button class='btn btn-primary btn-block btn-md' type='submit'>Acrescentar concurso</button>
            </form>
              ";
		        include 'templates/page_element.php';
		
		        $template_id = 'barra_busca';
		        $template_collapse_stuff = false;
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
				        $estado = '(desativado)';
			        } else {
				        $estado = '(ativado)';
			        }
			        $template_conteudo .= "<option value='$um_concurso[0]'>$um_concurso[1] / $estado</option>";
		        }
		
		        $template_conteudo .= "
                                            </select>
                <button class='btn btn-primary btn-block btn-md' type='submit' name='reconstruir_busca'>Reconstruir
                </button>
            </form>
                        ";
		        include 'templates/page_element.php';
		
		        $template_id = 'otimizar_tabela';
		        $template_collapse_stuff = false;
		        $template_titulo = 'Otimizar tabela de tópicos';
		        $template_botoes = false;
		        $template_conteudo = false;
		        $template_conteudo .= "<form method='post'>";
		        $template_conteudo .= "<p>Essa ferramenta determina o nível relevante de cada entrada na tabela de tópicos, de 1 a 5.</p>";
		        $template_conteudo .= "
		    				<label for='editar_topicos_concurso'>Concurso</label>
                            <select class='form-control' name='otimizar_temas_concurso'>
						";
		        foreach ($lista_concursos as $um_concurso) {
			        if ($um_concurso[2] == 0) {
				        $estado = '(desativado)';
			        } else {
				        $estado = '(ativado)';
			        }
			        $template_conteudo .= "<option value='$um_concurso[0]'>$um_concurso[1] $estado</option>";
		        }
		        $template_conteudo .= "</select>";
		        $template_conteudo .= "<button class='btn btn-primary btn-block btn-md' type='submit'>Otimizar</button>";
		        $template_conteudo .= '</form>';
		        include 'templates/page_element.php';
	        ?>

        </div>
        <div class='col-lg-5 col-sm-12'>
	        <?php
		
		        $template_id = 'notas_administradores';
		        $template_collapse_stuff = false;
		        $template_titulo = 'Notas dos administradores';
		        $template_botoes = false;
		        $template_conteudo = false;
		        $template_conteudo .= "<p>Estas anotações são compartilhadas entre todos os administradores, por exemplo, para registrar idéias de atualizações futuras da página.</p>";
		
		        $template_quill_form_id = 'quill_admin_form';
		        $template_quill_conteudo_html = 'quill_nova_mensagem_html';
		        $template_quill_conteudo_text = 'quill_nova_mensagem_text';
		        $template_quill_conteudo_content = 'quill_nova_mensagem_content';
		        $template_quill_container_id = 'quill_container_admin';
		        $template_quill_editor_id = 'quill_editor_admin';
		        $template_quill_editor_classes = 'quill_editor_height';
		        $template_quill_conteudo_opcional = $admin_mensagens;
		        $template_quill_botoes_collapse_stuff = false;
		
		        $template_conteudo .= include 'templates/quill_form.php';
		        include 'templates/page_element.php';
	
	        ?>
        
        </div>
    </div>
</div>
</body>
<?php
	load_footer();
	bottom_page("quill_admin");
?>
</html>
