<?php
	include 'engine.php';
	$page_tipo = 'cursos';
	include 'templates/html_head.php';
?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container">
	<?php
		$template_titulo = $pagina_translated['courses'];
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <h2 class='text-muted mb-5'><?php echo $pagina_translated['Cursos em que você se inscreveu:']; ?></h2>
        <div class="col-lg-10 col-md-12">
					<?php
						
						$usuario_cursos_inscrito = return_usuario_cursos_inscrito($user_id);
						$usuario_cursos_disponiveis = return_usuario_cursos($user_id);
						$usuario_cursos_nao_inscrito_disponiveis = array_diff($usuario_cursos_disponiveis, $usuario_cursos_inscrito);
						
						$list_cursos_cards = false;
						if ($usuario_cursos_inscrito != false) {
							foreach ($usuario_cursos_inscrito as $usuario_inscrito_curso_id) {
								$list_cursos_cards = return_curso_card($usuario_inscrito_curso_id, 'inscrito');
							}
						}

					?>
        </div>
        <h2 class='text-muted my-5'><?php echo $pagina_translated['Cursos a que você tem acesso:']; ?></h2>
        <div class="col-lg-10 col-md-12">
					
					<?php
						if ($usuario_cursos_nao_inscrito_disponiveis != false) {
							foreach ($usuario_cursos_nao_inscrito_disponiveis as $list_cursos_disponiveis) {
							    $list_cursos_cards = return_curso_card($list_cursos_disponiveis, 'disponivel');
							}
						}
					?>

        </div>
    </div>
</div>
<?php
	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
?>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
