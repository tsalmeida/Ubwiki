<?php
	include 'engine.php';
	$pagina_tipo = 'planner';
	$pagina_id = $user_escritorio;
	if ($user_email == false) {
		header('Location:ubwiki.php');
		exit();
	}
	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
<div class="container mt-1">
	<?php
		$template_titulo = $pagina_translated['Study Planner'];
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
<div class="row d-flex justify-content-center mx-1">
    <div id="coluna_unica" class="col">
        <?php

        ?>
    </div>
</div>
</div>
<?php
	include 'pagina/modal_languages.php';
?>
</body>
<?php
    include 'templates/html_bottom.php';
?>
</html>
