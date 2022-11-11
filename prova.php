<?php
	include 'engine.php';
	
	if (isset($_GET['prova_id'])) {
		$prova_id = $_GET['prova_id'];
	} else {
		header('Location:index.php');
	}
	
	if (isset($_POST['nova_prova_trigger'])) {
		if (isset($_POST['nova_prova_etapa'])) {
			$nova_prova_etapa = $_POST['nova_prova_etapa'];
		}
		if (isset($_POST['nova_prova_titulo'])) {
			$nova_prova_titulo = $_POST['nova_prova_titulo'];
			$nova_prova_titulo = mysqli_real_escape_string($conn, $nova_prova_titulo);
		}
		if (isset($_POST['nova_prova_tipo'])) {
			$nova_prova_tipo = $_POST['nova_prova_tipo'];
		}
		if (($nova_prova_etapa != false) && ($nova_prova_titulo != false) && ($nova_prova_tipo != false)) {
			$conn->query("INSERT INTO sim_provas_arquivo (concurso_id, etapa_id, titulo, tipo, user_id) VALUES ($concurso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
			$conn->query("UPDATE sim_provas SET etapa_id = $nova_prova_etapa, titulo = '$nova_prova_titulo', tipo = $nova_prova_tipo, user_id = $user_id WHERE id = $prova_id");
		}
	}
	
	$prova_info = return_info_prova_id($prova_id);
	$prova_titulo = $prova_info[0];
	$prova_tipo = $prova_info[1];
	$prova_tipo_string = convert_prova_tipo($prova_tipo);
	$edicao_ano = $prova_info[2];
	$edicao_titulo = $prova_info[3];
	$prova_etapa_id = $prova_info[4];
	
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	
	$edicoes = $conn->query("SELECT id, ano, titulo FROM sim_edicoes WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$etapas = $conn->query("SELECT id, edicao_id, titulo FROM sim_etapas WHERE concurso_id = $concurso_id ORDER BY id DESC");

?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla: Prova: $prova_titulo ($prova_tipo_string) de $edicao_ano";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'verbete_prova';
						$template_titulo = 'Verbete';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_quill_page_id = $prova_id;
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
						
						$template_id = 'dados_prova';
						$template_titulo = 'Dados da prova';
						$template_botoes = "
						    <a data-bs-toggle='modal' data-bs-target='#modal_prova_form' href=''>
                                <i class='fal fa-pen-square fa-fw'></i>
                            </a>
						";
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group'>";
						$template_conteudo .= "<li class='list-group-item'><strong>Ano: </strong>$edicao_ano</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Título: </strong>$prova_titulo</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Tipo: </strong>$prova_tipo_string</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Edição: </strong>$edicao_titulo</li>";
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
						
						$template_id = 'questoes_prova';
						$template_titulo = 'Questões e textos de apoio registrados';
						$template_conteudo = false;
						
						$questoes = $conn->query("SELECT id, numero, materia FROM sim_questoes WHERE prova_id = $prova_id");
						if ($questoes->num_rows > 0) {
							$template_conteudo .= "<h2>Questões</h2>";
							$template_conteudo .= "<ul class='list-group'>";
							while ($questao = $questoes->fetch_assoc()) {
								$questao_id = $questao['id'];
								$questao_numero = $questao['numero'];
								$questao_materia_id = $questao['materia'];
								$questao_materia_titulo = return_materia_titulo_id($questao_materia_id);
								$template_conteudo .= "<a href='questao.php?questao_id=$questao_id' target='_blank'><li class='list-group-item list-group-item-action'>$questao_materia_titulo: Questão $questao_numero</li></a>";
							}
							$template_conteudo .= "</ul>";
						}
						
						$textos_apoio = $conn->query("SELECT id, titulo FROM sim_textos_apoio WHERE prova_id = $prova_id");
						if ($textos_apoio->num_rows > 0) {
						    $template_conteudo .= "<h2>Textos de apoio</h2>";
						    $template_conteudo .= "<ul class='list-group'>";
						    while ($texto_apoio = $textos_apoio->fetch_assoc()) {
						        $texto_apoio_id = $texto_apoio['id'];
						        $texto_apoio_titulo = $texto_apoio['titulo'];
						        $template_conteudo .= "<a href='textoapoio.php?texto_apoio_id=$texto_apoio_id' target='_blank'><li class='list-group-item list-group-item-action'>$texto_apoio_titulo</li></a>";
                            }
						    $template_conteudo .= "</ul>";
                        }
						
						
						include 'templates/page_element.php';
					
					
					?>
        </div>
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'anotacoes_prova';
						$template_titulo = 'Anotações privadas';
						$template_quill_page_id = $prova_id;
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Você ainda não fez anotações sobre esta prova.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-dark position-absolute top-50 end-0 translate-middle-y' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
	<?php
		$template_modal_div_id = 'modal_prova_form';
		$template_modal_titulo = 'Adicionar prova da etapa';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_etapa' required>
                                  <option value='' disabled selected>Etapa do concurso:</option>";
		while ($etapa = $etapas->fetch_assoc()) {
			$etapa_id = $etapa['id'];
			$etapa_titulo = $etapa['titulo'];
			$etapa_edicao_id = $etapa['edicao_id'];
			$edicoes = $conn->query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
			while ($edicao = $edicoes->fetch_assoc()) {
				$edicao_ano = $edicao['ano'];
				$edicao_titulo = $edicao['titulo'];
			}
			$selected = false;
			if ($etapa_id = $prova_etapa_id) {
				$selected = 'selected';
			}
			$template_modal_body_conteudo .= "<option value='$etapa_id' $selected>$edicao_ano: $edicao_titulo: $etapa_titulo</option>";
		}
		$template_modal_body_conteudo .= "</select>";
		$tipo_objetiva = false;
		$tipo_dissertativa = false;
		$tipo_oral = false;
		$tipo_fisica = false;
		if ($prova_tipo == 1) {
			$tipo_objetiva = 'selected';
		} elseif ($prova_tipo == 2) {
			$tipo_dissertativa = 'selected';
		} elseif ($prova_tipo == 3) {
			$tipo_oral = 'selected';
		} elseif ($prova_tipo == 4) {
			$tipo_fisica = 'selected';
		}
		$template_modal_body_conteudo .= "
                            <div class='mb-3'>
                              <label for='nova_prova_titulo' class='form-label'>Título da prova</label>
                              <input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo' value='$prova_titulo' required>
                            </div>
                            <select class='$select_classes' name='nova_prova_tipo' required>
                              <option value='' disabled selected>Tipo de prova:</option>
                              <option value='1' $tipo_objetiva>Objetiva</option>
                              <option value='2' $tipo_dissertativa>Dissertativa</option>
                              <option value='3' $tipo_oral>Oral</option>
                              <option value='4' $tipo_fisica>Física</option>
                            </select>
                        ";
		$template_modal_submit_name = 'nova_prova_trigger';
		include 'templates/modal.php';
	?>

</div>
</body>

<?php
	include 'templates/footer.html';
	$mdb_select = true;
	$anotacoes_id = 'anotacoes_prova';
	include 'templates/esconder_anotacoes.php';
	include 'templates/html_bottom.php';
?>
