<?php
	include 'engine.php';
	$pagina_tipo = 'bfranklin';
	$pagina_id = return_pagina_id($user_id, 'escritorio');
	if ($user_id != 1) {
		header('Location:ubwiki.php');
		exit();
	}
	if ($user_email == false) {
		header('Locatin:ubwiki.php');
		exit();
	}

	$html_head_template_quill = true;
	include 'templates/html_head.php';
	include 'templates/navbar.php';
?>
<body class="grey lighten-5">
<div class="container mt-1">
	<?php
		$template_titulo = $pagina_translated['metodo bfranklin'];
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
			<?php

				$template_id = 'seus_modelos';
				$template_titulo = 'Seus modelos';
				$template_conteudo = false;
				$cada_modelo_do_usuario = array();
				$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'modelo'");
				$modelos_do_usuario = $conn->query($query);
				if ($modelos_do_usuario->num_rows > 0) {
					$template_conteudo .= "<ul class='list-group list-group-flush'>";
					while ($modelo_do_usuario = $modelos_do_usuario->fetch_assoc()) {
						$modelo_do_usuario_id = $modelo_do_usuario['modelo_id'];
						array_push($cada_modelo_do_usuario, $modelo_do_usuario_id);
						$modelo_do_usuario_pagina_id = return_pagina_id($modelo_do_usuario_id, 'modelo');
						$template_conteudo .= return_list_item($modelo_do_usuario_pagina_id);
					}
					$template_conteudo .= "</ul>";
				} else {
					$template_conteudo .= "<p class='text-muted font-italic'>Você ainda não acrescentou nenhum parágrafo do método BFranklin para prática futura.</p>";
				}
				include 'templates/page_element.php';

				$template_id = 'modelos_diponiveis';
				$template_titulo = 'Outros modelos disponíveis';
				$template_conteudo = false;
				$query = prepare_query("SELECT id FROM Paginas WHERE tipo = 'modelo' ORDER BY id DESC");
				$modelos_disponiveis = $conn->query($query);
				if ($modelos_disponiveis->num_rows > 0) {
					$template_conteudo .= "<ul class='list-group list-group-flush'>";
					while ($modelo_disponivel = $modelos_disponiveis->fetch_assoc()) {
						$modelo_disponivel_id = $modelo_disponivel['id'];
						if (in_array($modelo_disponivel_id, $cada_modelo_do_usuario)) {
							continue;
						}
						$modelo_disponivel_pagina_id = $modelo_disponivel['pagina_id'];
						$template_conteudo .= return_list_item($modelo_disponivel_pagina_id);
					}
					$template_conteudo .= "</ul>";
				} else {
					$template_conteudo .= "<p class='text-muted font-italic'>Não há modelos disponíveis a que você tenha acesso.</p>";
				}
				include 'templates/page_element.php';

			?>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/html_bottom.php';
?>
</html>
