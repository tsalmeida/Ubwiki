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
						/*$cursos = $conn->query("SELECT pagina_id, sigla FROM Cursos WHERE estado = 1 ORDER BY id");
								if ($cursos->num_rows > 0) {
									while ($curso = $cursos->fetch_assoc()) {
										$lista_curso_pagina_id = $curso['pagina_id'];
										$lista_curso_sigla = $curso['sigla'];
										$lista_curso_pagina_info = return_pagina_info($lista_curso_pagina_id);
										$lista_curso_pagina_estado = $lista_curso_pagina_info[3];
										$lista_curso_pagina_user_id = $lista_curso_pagina_info[5];
										$lista_curso_pagina_titulo = $lista_curso_pagina_info[6];
										$lista_curso_pagina_publicacao = $lista_curso_pagina_info[9];
										$lista_curso_pagina_colaboracao = $lista_curso_pagina_info[10];
										$lista_curso_texto_id = return_texto_id('curso', 'verbete', $lista_curso_pagina_id, false);
										$lista_curso_verbete_html = return_verbete_html($lista_curso_texto_id);
										
										$template_id = "curso_$lista_curso_sigla";
										$template_titulo = "<a href='pagina.php?pagina_id=$lista_curso_pagina_id'>$lista_curso_pagina_titulo</a>";
										$template_classes = 'col-lg-6 col-sm-12';
										$template_conteudo = false;
										$template_conteudo .= $lista_curso_verbete_html;
										include 'templates/page_element.php';
									}
								}
								echo "</div>";*/
						
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
								error_log($list_cursos_disponiveis);
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
