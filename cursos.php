<?php
	
	include 'engine.php';
	include 'templates/html_head.php';

?>

<body>
<?php
	include 'templates/navbar.php';
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

