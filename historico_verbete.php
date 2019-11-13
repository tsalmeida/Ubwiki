<?php
	
	include 'engine.php';
	
	if (isset($_GET['tema'])) {
		$tema_id = $_GET['tema'];
	}
	
	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}
	
	$result = $conn->query("SELECT sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND id = $tema_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$sigla_materia = $row['sigla_materia'];
			$nivel = $row['nivel'];
			$ordem = $row['ordem'];
			$nivel1 = $row['nivel1'];
			$nivel2 = $row['nivel2'];
			$nivel3 = $row['nivel3'];
			$nivel4 = $row['nivel4'];
			$nivel5 = $row['nivel5'];
			if ($nivel == 1) {
				$tema_titulo = $nivel1;
			} elseif ($nivel == 2) {
				$tema_titulo = $nivel2;
			} elseif ($nivel == 3) {
				$tema_titulo = $nivel3;
			} elseif ($nivel == 4) {
				$tema_titulo = $nivel4;
			} elseif ($nivel == 5) {
				$tema_titulo = $nivel5;
			}
		}
	}
	
	$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia = $row["materia"];
		}
	}
	
	// ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO
	
	$result = $conn->query("SELECT anotacao_content FROM Anotacoes WHERE tema_id = $tema_id AND user_id = $user_id AND tipo = 'historico'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$anotacoes_content = $row['anotacao_content'];
		}
	} else {
		$anotacoes_content = '';
	}
	
	if (isset($_POST['quill_novo_anotacoes_html'])) {
		$novo_anotacoes_html = $_POST['quill_novo_anotacoes_html'];
		$novo_anotacoes_text = $_POST['quill_novo_anotacoes_text'];
		$novo_anotacoes_content = $_POST['quill_novo_anotacoes_content'];
		$novo_anotacoes_html = strip_tags($novo_anotacoes_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
		$result = $conn->query("SELECT id FROM Anotacoes WHERE tema_id = $tema_id AND tipo ='historico'");
		if ($result->num_rows > 0) {
			$result = $conn->query("UPDATE Anotacoes SET anotacao_html = '$novo_anotacoes_html', anotacao_text = '$novo_anotacoes_text', anotacao_content = '$novo_anotacoes_content', user_id = '$user_id' WHERE tema_id = $tema_id AND tipo = 'historico'");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id, tipo) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id', 'historico')");
		} else {
			$result = $conn->query("INSERT INTO Anotacoes (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id, tipo) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id', 'historico')");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id, tipo) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id', 'historico')");
		}
		$anotacoes_content = $novo_anotacoes_content;
	}
	
	$anotacoes_content = urldecode($anotacoes_content);
	
	// HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HTML HEAD HTML HEAD HTML HEAD
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var tema_id=$tema_id;
          var concurso='$concurso';
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

<div id="page_height" class="container-fluid">
	<?php
		$template_titulo_context = true;
		$template_titulo = "Histórico: $tema_titulo";
		include 'templates/titulo.php';
	?>
    <div class="row justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 col-sm-12">
					<?php
					
					?>
        </div>
        <div id='coluna_direita' class='col-lg-5 col-sm-12 anotacoes_collapse collapse show'>
					
					<?php
						$template_id = 'sticky_anotacoes';
						$template_titulo = 'Anotações';
						$template_botoes = "<span class='anotacoes_editor_collapse collapse show' id='travar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='travar para edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='anotacoes_editor_collapse collapse' id='destravar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='permitir edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>";
						
						$template_quill_unique_name = 'anotacoes';
						$template_quill_initial_state = 'edicao';
						$template_quill_conteudo = $anotacoes_content;
						
						$template_conteudo = include 'templates/quill_form.php';
						include 'templates/page_element.php';
					
					?>

        </div>
        <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                    class='fas fa-pen-alt fa-fw'></i></button>
    </div>
</div>


</body>
</html>