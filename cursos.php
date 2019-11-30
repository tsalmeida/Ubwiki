<?php
	
	include 'engine.php';
	include 'templates/html_head.php';

	if (isset($_POST['trocar_concurso_trigger'])) {
		if (isset($_POST['trocar_concurso'])) {
			$_SESSION['concurso_id'] = $_POST['trocar_concurso'];
			header("Location:index.php");
		}
	}
	?>

<body>
<?php
	include 'templates/navbar.php';
	$cursos = $conn->query("SELECT id, titulo, sigla FROM Concursos WHERE estado = 1");
	$cursos_do_usuario = $conn->query("SELECT id, titulo, sigla FROM Concursos WHERE user_id = $user_id");
	?>

<div class="container-fluid">
	<?php
		$template_titulo = 'Trocar curso';
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'selecao_curso';
						$template_titulo = 'Selecione um curso';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<select class='mdb-select md-form' name='trocar_concurso'>
                                                <option value='' disabled selected>Curso</option>";
						$selected = false;
						while ($curso = $cursos->fetch_assoc()) {
						    $curso_id = $curso['id'];
							if ($curso_id == 2) { $selected = 'selected'; }
							else { $selected = false; }
						    $curso_titulo = $curso['titulo'];
							$curso_sigla = $curso['sigla'];
							$template_conteudo .= "<option value='$curso_id' $selected>$curso_sigla: $curso_titulo</option>";
						}
						if ($cursos_do_usuario->num_rows > 0) {
						    $template_conteudo .= "<option disabled>Seus cursos:</option>";
						    while ($curso_do_usuario = $cursos_do_usuario->fetch_assoc()) {
						        $curso_do_usuario_id = $curso_do_usuario['id'];
						        $curso_do_usuario_sigla = $curso_do_usuario['sigla'];
						        $curso_do_usuario_titulo = $curso_do_usuario['titulo'];
						        $template_conteudo .= "<option value='$curso_do_usuario_id'>$curso_do_usuario_sigla: $curso_do_usuario_titulo</option>";
                            }
                        }
						$template_conteudo .= "</select>";
						$template_conteudo .= "
                            <div class='row justify-content-center'>
                                <button type='submit' name='trocar_concurso_trigger' class='$button_classes'>Acessar curso</button>
                            </div>
                            ";
						$template_conteudo .= "</form>";
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
?>

