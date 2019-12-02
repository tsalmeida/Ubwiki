<?php
	include 'engine.php';
	
	if (isset($_GET['texto_apoio_id'])) {
		$texto_apoio_id = $_GET['texto_apoio_id'];
	} else {
		header('Location:index.php');
	}
	
	if (isset($_POST['novo_texto_apoio_trigger'])) {
		if (isset($_POST['novo_texto_apoio_origem'])) {
			$novo_texto_apoio_origem = true;
		} else {
			$novo_texto_apoio_origem = false;
		}
		if (isset($_POST['novo_texto_apoio_prova'])) {
			$novo_texto_apoio_prova = $_POST['novo_texto_apoio_prova'];
		}
		if (isset($_POST['novo_texto_apoio_titulo'])) {
			$novo_texto_apoio_titulo = $_POST['novo_texto_apoio_titulo'];
			$novo_texto_apoio_prova = mysqli_real_escape_string($conn, $novo_texto_apoio_prova);
		}
		$quill_novo_texto_apoio_enunciado_html = false;
		$quill_novo_texto_apoio_enunciado_text = false;
		$quill_novo_texto_apoio_enunciado_content = false;
		$quill_novo_texto_apoio_html = false;
		$quill_novo_texto_apoio_text = false;
		$quill_novo_texto_apoio_content = false;
		
		if (isset($_POST['quill_novo_texto_apoio_enunciado_html'])) {
			$novo_texto_apoio_enunciado_html = $_POST['quill_novo_texto_apoio_enunciado_html'];
			$novo_texto_apoio_enunciado_html = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_html);
			$novo_texto_apoio_enunciado_text = $_POST['quill_novo_texto_apoio_enunciado_text'];
			$novo_texto_apoio_enunciado_text = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_text);
			$novo_texto_apoio_enunciado_content = $_POST['quill_novo_texto_apoio_enunciado_content'];
			$novo_texto_apoio_enunciado_content = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_content);
		}
		if (isset($_POST['quill_novo_texto_apoio_html'])) {
			$novo_texto_apoio_html = $_POST['quill_novo_texto_apoio_html'];
			$novo_texto_apoio_html = mysqli_real_escape_string($conn, $novo_texto_apoio_html);
			$novo_texto_apoio_text = $_POST['quill_novo_texto_apoio_text'];
			$novo_texto_apoio_text = mysqli_real_escape_string($conn, $novo_texto_apoio_text);
			$novo_texto_apoio_content = $_POST['quill_novo_texto_apoio_content'];
			$novo_texto_apoio_content = mysqli_real_escape_string($conn, $novo_texto_apoio_content);
		}
		
		if (($novo_texto_apoio_origem != false) && ($novo_texto_apoio_prova != false) && ($novo_texto_apoio_titulo != false) && ($novo_texto_apoio_enunciado_html != false) && ($novo_texto_apoio_html != false)) {
            $conn->query("INSERT INTO sim_textos_apoio_arquivo (concurso_id, origem, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($concurso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado_html', '$novo_texto_apoio_enunciado_text', '$novo_texto_apoio_enunciado_content', '$novo_texto_apoio_html', '$novo_texto_apoio_text', '$novo_texto_apoio_content', $user_id)");
			$conn->query("UPDATE sim_textos_apoio SET origem = $novo_texto_apoio_origem, prova_id = $novo_texto_apoio_prova, titulo = '$novo_texto_apoio_titulo', enunciado_html = '$novo_texto_apoio_enunciado_html', enunciado_text = '$novo_texto_apoio_enunciado_text', enunciado_content = '$novo_texto_apoio_enunciado_content', texto_apoio_html = '$novo_texto_apoio_html', texto_apoio_text = '$novo_texto_apoio_text', texto_apoio_content = '$novo_texto_apoio_content', user_id = $user_id WHERE id = $texto_apoio_id");
		}
	}
	
	$textos_apoio = $conn->query("SELECT prova_id, titulo, enunciado_html, enunciado_content, texto_apoio_html, texto_apoio_content FROM sim_textos_apoio WHERE id = $texto_apoio_id");
	if ($textos_apoio->num_rows > 0) {
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$texto_apoio_prova_id = $texto_apoio['prova_id'];
			$texto_apoio_titulo = $texto_apoio['titulo'];
			$texto_apoio_enunciado = $texto_apoio['enunciado_html'];
			$texto_apoio_enunciado_content = $texto_apoio['enunciado_content'];
			$texto_apoio_html = $texto_apoio['texto_apoio_html'];
			$texto_apoio_content = $texto_apoio['texto_apoio_content'];
		}
	} else {
		header('Location:index.php');
	}
	
	$prova_info = return_info_prova_id($texto_apoio_prova_id);
	$texto_apoio_edicao_ano = $prova_info[2];
	$texto_apoio_prova_titulo = $prova_info[0];
	
	$provas = $conn->query("SELECT id, etapa_id, titulo, tipo FROM sim_provas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	
	$html_head_template_quill = true;
	$html_head_template_quill_sim = true;
	include 'templates/html_head.php';

?>

    <body>
		<?php
			include 'templates/navbar.php';
		?>
    <div class="container-fluid">
			<?php
				$template_titulo = "$texto_apoio_edicao_ano: $texto_apoio_prova_titulo: $texto_apoio_titulo";
				$template_titulo_context = true;
				$template_titulo_no_nav = true;
				include 'templates/titulo.php';
			?>
        <div class="row d-flex justify-content-around">
            <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
							<?php
								$template_id = 'verbete_texto_apoio';
								$template_titulo = 'Verbete';
								$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
								$template_botoes = false;
								$template_conteudo = include 'templates/template_quill.php';
								include 'templates/page_element.php';
								
								$template_id = 'texto_apoio';
								$template_titulo = 'Texto de apoio';
								$template_botoes = "
                                    <a data-toggle='modal' data-target='#modal_texto_apoio_form' href=''>
                                        <i class='fal fa-pen-square fa-fw'></i>
                                    </a>
                                ";
								$template_conteudo = false;
								$template_conteudo .= $texto_apoio_enunciado;
								$template_conteudo .= "<div id='special_li'>$texto_apoio_html</div>";
								include 'templates/page_element.php';
								
								$template_id = 'texto_apoio_questoes';
								$template_titulo = 'Questões deste texto de apoio';
								$template_conteudo = false;
								$questoes = $conn->query("SELECT ");
								
								
							?>
            </div>
            <div id="coluna_direita" class="<?php echo $coluna_classes; ?>">
							<?php
								$template_id = 'anotacoes_texto_apoio';
								$template_titulo = 'Anotações privadas';
								$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Você ainda não fez anotações sobre este texto de apoio.</p>";
								$template_botoes = false;
								$template_conteudo = include 'templates/template_quill.php';
								include 'templates/page_element.php';
							?>
            </div>
        </div>
        <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                    class='fas fa-pen-alt fa-fw'></i></button>
    </div>
		
		<?php
			$template_modal_div_id = 'modal_texto_apoio_form';
			$template_modal_titulo = 'Editar texto de apoio';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
                            <div class='form-check pl-0'>
                                <input id='novo_texto_apoio_origem' name='novo_texto_apoio_origem' type='checkbox' class='form-check-input' checked>
                                <label class='form-check-label' for='novo_texto_apoio_origem'>Texto de apoio oficial do concurso.</label>
                            </div>
						";
			$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='novo_texto_apoio_prova' required>
                              <option value='' disabled selected>Selecione a prova:</option>
                              <option value='0'>Texto de apoio não é oficial</option>";
			if ($provas->num_rows > 0) {
				while ($prova = $provas->fetch_assoc()) {
					$prova_id = $prova['id'];
					$prova_etapa_id = $prova['etapa_id'];
					$prova_titulo = $prova['titulo'];
					$prova_tipo = $prova['tipo'];
					if ($prova_tipo == 1) {
						$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
						$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
						if ($prova_etapa_edicao_ano_e_titulo != false) {
							$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
							$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
						} else {
							break;
						}
						$selected = false;
						if ($prova_id == $texto_apoio_prova_id) {
							$selected = 'selected';
						}
						$template_modal_body_conteudo .= "<option value='$prova_id' $selected>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
					}
				}
			}
			$template_modal_body_conteudo .= "</select>";
			$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' value='$texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
			
			$template_modal_form_id = 'form_novo_texto_apoio';
			$template_modal_body_conteudo .= "<h3 class='text-center'>Enunciado:</h3>";
			$sim_quill_id = 'texto_apoio_enunciado';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			
			$template_modal_body_conteudo .= "<h3 class='text-center'>Texto de apoio:</h3>";
			$sim_quill_id = 'texto_apoio';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			
			$template_modal_submit_name = 'novo_texto_apoio_trigger';
			include 'templates/modal.php';
		?>

    </body>

<?php
	
	echo "
        <script type='text/javascript'>
            quill_texto_apoio_enunciado.setContents($texto_apoio_enunciado_content);
            quill_texto_apoio.setContents($texto_apoio_content);
        </script>
    ";
	
	include 'templates/footer.html';
	$mdb_select = true;
	$anotacoes_id = 'anotacoes_texto_apoio';
	include 'templates/esconder_anotacoes.php';
	include 'templates/html_bottom.php';
?>