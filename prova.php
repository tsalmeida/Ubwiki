<?php
	include 'engine.php';
	include 'templates/html_head.php';
	
	if (isset($_GET['prova_id'])) {
		$prova_id = $_GET['prova_id'];
	} else {
		header('Location:index.php');
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
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<p>Quill aqui.</p>";
						include 'templates/page_element.php';
					?>
        </div>
	    <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
		    <?php
			    $template_id = 'anotacoes_prova';
			    $template_titulo = 'Anotações';
			    $template_botoes = false;
			    $template_conteudo = false;
			    $template_conteudo .= "<p>Quill aqui.</p>";
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
