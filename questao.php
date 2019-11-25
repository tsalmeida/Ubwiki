<?php
	include 'engine.php';
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	
	if (isset($_GET['questao_id'])) {
		$questao_id = $_GET['questao_id'];
	} else {
		header('Location:index.php');
	}
	
	$questoes = $conn->query("SELECT origem, concurso_id, texto_apoio_id, prova_id, enunciado, numero, materia, tipo, item1, item2, item3, item4, item5, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito FROM sim_questoes WHERE id = $questao_id");
	if ($questoes->num_rows > 0) {
		while ($questao = $questoes->fetch_assoc()) {
			$texto_apoio_id = $questao['texto_apoio_id'];
			$prova_id = $questao['prova_id'];
			$questao_enunciado = $questao['enunciado'];
			$questao_numero = $questao['numero'];
			$questao_materia = $questao['materia'];
			$questao_tipo = $questao['tipo'];
			$questao_item1 = $questao['item1'];
			$questao_item2 = $questao['item2'];
			$questao_item3 = $questao['item3'];
			$questao_item4 = $questao['item4'];
			$questao_item5 = $questao['item5'];
			$questao_item1_gabarito = $questao['item1_gabarito'];
			$questao_item2_gabarito = $questao['item2_gabarito'];
			$questao_item3_gabarito = $questao['item3_gabarito'];
			$questao_item4_gabarito = $questao['item4_gabarito'];
			$questao_item5_gabarito = $questao['item5_gabarito'];
			break;
		}
	}
	
	$prova_info = return_info_prova_id($prova_id);
	$prova_titulo = $prova_info[0];
	$prova_tipo = $prova_info[1];
	$prova_tipo_string = convert_prova_tipo($prova_tipo);
	$edicao_ano = $prova_info[2];
	$edicao_titulo = $prova_info[3];


?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "$concurso_sigla: Prova: $prova_titulo ($prova_tipo_string) de $edicao_ano: Questão: $questao_numero";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 col-sm-12">
					<?php
						
						$template_id = 'verbete_questao';
						$template_titulo = 'Verbete';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/quill_form.php';
						include 'templates/page_element.php';
						
						$template_id = 'dados_questao';
						$template_titulo = 'Dados da Questão';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group'>";
						$template_conteudo .= "
						<li class='list-group-item'>Concurso: $concurso_sigla</li>
						<li class='list-group-item'>Ano: $edicao_ano</li>
						<li class='list-group-item'>Prova: $prova_titulo</li>
						<li class='list-group-item'>Tipo: $prova_tipo_string</li>
						<li class='list-group-item'>Número da questão: $questao_numero</li>";
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
						
						$template_id = 'gabarito_questao';
						$template_titulo = 'Itens e gabarito';
						$template_botoes = "
                            <span id='mostrar_gabarito' title='Mostrar gabarito'>
                                <a href='javascript:void(0);'><i class='fal fa-eye fa-fw'></i></a>
                            </span>
                        ";
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group'>";
						$mask_cor = 'list-group-item-light';
						if ($questao_item1 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item1_gabarito);
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>$questao_item1</li>";
						}
						if ($questao_item2 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item2_gabarito);
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>$questao_item2</li>";
						}
						if ($questao_item3 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item3_gabarito);
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>$questao_item3</li>";
						}
						if ($questao_item4 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item4_gabarito);
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>$questao_item4</li>";
						}
						if ($questao_item5 != false) {
							$gabarito_cor = convert_gabarito_cor($questao_item5_gabarito);
							$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>$questao_item5</li>";
						}
						
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
					?>
        </div>
        <div id="coluna_direita" class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'anotacoes_questao';
						$template_titulo = 'Anotações';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<p>Quill aqui.</p>";
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
</body>

<?php
	$mdb_select = true;
	$gabarito = true;
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_questao';
	include 'templates/esconder_anotacoes.php';
	include 'templates/footer.html';
?>
