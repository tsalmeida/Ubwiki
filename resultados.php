<?php
	include 'engine.php';
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	include 'templates/imagehandler.php';
	
	$simulado_id = false;
	if (isset($_GET['simulado_id'])) {
		$simulado_id = $_GET['simulado_id'];
	} else {
		header('Location:index.php');
	}
	
	$simulado_user_id = false;
	$simulado_tipo = false;
	$simulados = $conn->query("SELECT criacao, user_id, tipo FROM sim_gerados WHERE id = $simulado_id");
	if ($simulados->num_rows > 0) {
		while ($simulado = $simulados->fetch_assoc()) {
			$simulado_criacao = $simulado['criacao'];
			$simulado_user_id = $simulado['user_id'];
			$simulado_tipo = $simulado['tipo'];
		}
	}
	
	if ($simulado_user_id != $user_id) {
		header('Location:index.php');
	}
	
	$questoes = $conn->query("SELECT criacao, questao_id, item1, item2, item3, item4, item5, multipla, redacao FROM sim_respostas WHERE user_id = $user_id AND simulado_id = $simulado_id");
	if ($questoes->num_rows > 0) {
		$questoes_numero_total = $questoes->num_rows;
	}
	else {
		$questoes_numero_total = false;
	}

?>

<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo = "Resultados de simulado";
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-around">
        <div id="coluna_esquerda" class="col-lg-5 col-sm-12">
	        <?php
		        $template_id = 'simulado_dados';
		        $template_titulo = 'Dados';
		        $template_botoes = false;
		        $template_conteudo = false;
		        $template_conteudo .= "<p>Este simulado foi gerado em $simulado_criacao</p>";
		        $template_conteudo .= "<p>Tipo de simulado: $simulado_tipo</p>";
		        if ($questoes_numero_total == false) {
		        	$template_conteudo .= "<p>Não há questões com resposta registrada para este simulado.</p>";
			        include 'templates/page_element.php';
		        }
		        else {
			        include 'templates/page_element.php';
		        	$template_id = 'simulado_questoes';
		        	$template_titulo = 'Questões';
		        	$template_botoes = false;
		        	$template_conteudo = false;
		        	$template_conteudo .= "<p>Questões com respostas registradas:</p>";
		        	$template_conteudo .= "<ul class='list-group'>";
		        	while ($questao = $questoes->fetch_assoc()) {
		        		$questao_id = $questao['questao_id'];
		        		$template_conteudo .= "<li class='list-group-item'>$questao_id</li>";
			        }
			        $template_conteudo .= "</ul>";
			        include 'templates/page_element.php';
		        }
	        ?>
        </div>
    </div>
</div>
</body>
