<?php
	include 'engine.php';
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	include 'templates/imagehandler.php';
	
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
                        $template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
                        $template_botoes = false;
                        $template_conteudo = include 'templates/template_quill.php';
                        include 'templates/page_element.php';
					?>
        </div>
	    <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
			    <?php
				    $template_id = 'anotacoes_prova';
				    $template_titulo = 'Anotações privadas';
				    $template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Você ainda não fez anotaçõees sobre esta prova.</p>";
				    $template_botoes = false;
				    $template_conteudo = include 'templates/template_quill.php';
				    include 'templates/page_element.php';
			    ?>
	    </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i class='fas fa-pen-alt fa-fw'></i></button>
</div>
</body>

<?php
	include 'templates/footer.html';
	$mdb_select = true;
	$anotacoes_id = 'anotacoes_prova';
	include 'templates/esconder_anotacoes.php';
	include 'templates/html_bottom.php';
?>
