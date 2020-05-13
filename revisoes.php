<?php
	$pagina_tipo = 'revisoes';
	$pagina_id = 1;
	include 'engine.php';
	if ((($user_tipo != 'admin') && ($user_tipo != 'revisor')) || ($user_email == false)) {
		header('Location:escritorio.php');
		exit();
	}
	
	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
<div class="container">
	<?php
		$template_titulo = $pagina_translated['review'];
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
          <?php
              $template_id = 'revisoes_disponiveis';
              $template_titulo = 'Revisões disponíveis';
              $template_conteudo = false;
              include 'templates/page_element.php';
          ?>
        </div>
    </div>
</div>
<?php
    include 'templates/footer.html';
    $mdb_select = true;
    include 'templates/html_bottom.php';
?>
</body>
	
