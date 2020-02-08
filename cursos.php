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
		$template_titulo = 'Cursos';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
	<div class="row d-flex justify-content-center">
		<div id="coluna_unica" class="col">
			<?php
				$cursos = $conn->query("SELECT pagina_id, sigla FROM Cursos WHERE estado = 1 ORDER BY id");
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
						$template_conteudo = false;
						$template_conteudo .= $lista_curso_verbete_html;
						include 'templates/page_element.php';
					}
				}
				$cursos_usuario = return_usuario_cursos($user_id);
				if ($cursos_usuario != false) {
				    echo "<h1 class='my-5 text-center'>Cursos privados a que você tem acesso</h1>";
				    foreach ($cursos_usuario as $curso_usuario) {
				        $curso_usuario_pagina_id = $curso_usuario;
				        $curso_usuario_titulo = return_pagina_titulo($curso_usuario_pagina_id);
				        $curso_usuario_texto_id = return_texto_id('curso', 'verbete', $curso_usuario_pagina_id, false);
                        $curso_usuario_verbete = return_verbete_html($curso_usuario_texto_id);
				        
				        $template_id = "curso_$curso_usuario_pagina_id";
				        $template_titulo = "<a href='pagina.php?pagina_id=$curso_usuario_pagina_id'>$curso_usuario_titulo</a>";
				        $template_conteudo = false;
				        if ($curso_usuario_verbete != false) {
				            $template_conteudo .= $curso_usuario_verbete;
                        }
				        include 'templates/page_element.php';
                    }
                }
			?>
		</div>
	</div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
