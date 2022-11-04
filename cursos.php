<?php
	include 'engine.php';
	$page_tipo = 'cursos';
	$pagina_id = 1;
	$usuario_cursos_nao_inscrito_disponiveis = false;

	include 'templates/html_head.php';
?>
<body class="bg-light">
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
		<?php
			if ($user_email != false) {

				$usuario_cursos_inscrito = return_usuario_cursos_inscrito($user_id);
				$usuario_cursos_disponiveis = return_usuario_cursos($user_id);
				$usuario_cursos_nao_inscrito_disponiveis = array_diff($usuario_cursos_disponiveis, $usuario_cursos_inscrito);

				$list_cursos_cards = false;
				if ($usuario_cursos_inscrito != false) {
					echo "<h2 class='text-muted mb-5'>{$pagina_translated['Cursos em que você se inscreveu:']}</h2>";
					echo "<div class='col-lg-10 col-md-12'>";
					foreach ($usuario_cursos_inscrito as $usuario_inscrito_curso_id) {
						$list_cursos_cards = return_curso_card($usuario_inscrito_curso_id, 'inscrito');
					}
				} else {
					echo "<div class='col-lg-10 col-md-12'>";
				}
			} else {
				echo "<div class='col-lg-10 col-md-12'>";
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
			} else {
			    $query = prepare_query("SELECT pagina_id FROM Cursos");
			    $lista_cursos = $conn->query($query);
			    if ($lista_cursos->num_rows > 0) {
			        while ($lista_curso = $lista_cursos->fetch_assoc()) {
			            $lista_curso_pagina_id = $lista_curso['pagina_id'];
			            $lista_curso_compartilhamento = return_compartilhamento($lista_curso_pagina_id, false);
			            if ($lista_curso_compartilhamento == true) {
			                return_curso_card($lista_curso_pagina_id, 'disponivel');
                        }
                    }
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
	include 'pagina/modal_languages.php';
?>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
