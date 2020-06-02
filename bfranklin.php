<?php
	include 'engine.php';
	$pagina_tipo = 'bfranklin';
	$pagina_id = $user_escritorio;
	if ($user_tipo != 'admin') {
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
				$template_titulo = 'Your models';
				$template_conteudo = false;
				$cada_modelo_do_usuario = array();
				$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'modelo' AND estado = 1");
				$modelos_do_usuario = $conn->query($query);
				if ($modelos_do_usuario->num_rows > 0) {
					$template_conteudo .= "<ul class='list-group list-group-flush'>";
					while ($modelo_do_usuario = $modelos_do_usuario->fetch_assoc()) {
					    $modelo_do_usuario_pagina_id = $modelo_do_usuario['elemento_id'];
					    array_push($cada_modelo_do_usuario, $modelo_do_usuario_pagina_id);
						$template_conteudo .= return_list_item($modelo_do_usuario_pagina_id);
					}
					$template_conteudo .= "</ul>";
				} else {
					$template_conteudo .= "<p class='text-muted font-italic'>Você ainda não acrescentou nenhum parágrafo do método BFranklin para prática futura.</p>";
				}
				include 'templates/page_element.php';

				$template_id = 'modelos_diponiveis';
				$template_titulo = 'Other available models';
				$template_conteudo = false;
				$query = prepare_query("SELECT id, compartilhamento, user_id FROM Paginas WHERE subtipo = 'modelo' ORDER BY id DESC");
				$rascunhos_de_modelo_do_usuario = array();
				$modelos_disponiveis_criados_pelo_usuario = array();
				$ha_outros_modelos_disponiveis = false;
				$modelos_disponiveis = $conn->query($query);
				if ($modelos_disponiveis->num_rows > 0) {
					$template_conteudo .= "<ul class='list-group list-group-flush'>";
					while ($modelo_disponivel = $modelos_disponiveis->fetch_assoc()) {
						$modelo_disponivel_pagina_id = $modelo_disponivel['id'];
						$modelo_disponivel_compartilhamento = $modelo_disponivel['compartilhamento'];
						$modelo_disponivel_user_id = $modelo_disponivel['user_id'];
						if (in_array($modelo_disponivel_pagina_id, $cada_modelo_do_usuario)) {
							continue;
						}
						if ($modelo_disponivel_compartilhamento == 'privado') {
						    if ($modelo_disponivel_user_id == $user_id) {
								array_push($rascunhos_de_modelo_do_usuario, $modelo_disponivel_pagina_id);
							}
                            continue;
                        } else {
							if ($modelo_disponivel_user_id == $user_id) {
								array_push($modelos_disponiveis_criados_pelo_usuario, $modelo_disponivel_pagina_id);
								continue;
							}
                        }
						$ha_outros_modelos_disponiveis = true;
						$modelo_disponivel_pagina_id = $modelo_disponivel['id'];
						$template_conteudo .= return_list_item($modelo_disponivel_pagina_id);
					}
					$template_conteudo .= "</ul>";
				} else {
					$template_conteudo .= "<p class='text-muted font-italic'>Não há modelos disponíveis a que você tenha acesso.</p>";
				}

				$template_return = true;
				$page_element_modelos_disponiveis = include 'templates/page_element.php';

				if ($rascunhos_de_modelo_do_usuario != false) {
				    $template_id = 'rascunhos_usuario';
				    $template_titulo = $pagina_translated['Seus rascunhos'];
				    $template_botoes = false;
					$template_botoes .= "
	    			    <a class='text-secondary criar_novo_modelo'><i class='fad fa-plus-square fa-fw'></i></a>
    				";
                    $template_conteudo = false;
                    $template_conteudo .= "<ul class='list-group list-group-flush'>";
                    $template_conteudo .= put_together_list_item('link_button', 'criar_novo_modelo', 'criar_novo_modelo text-secondary', 'fad', 'fa-plus-square', 'Criar novo modelo', false, 'fad fa-pen-nib', 'purple lighten-5 text-secondary');
				    foreach ($rascunhos_de_modelo_do_usuario as $rascunho_pagina_id) {
                        $template_conteudo .= return_list_item($rascunho_pagina_id);
                    }
					$template_conteudo .= "</ul>";
					include 'templates/page_element.php';
                }

				if ($modelos_disponiveis_criados_pelo_usuario != false) {
					$template_id = 'disponiveis_usuario';
					$template_titulo = 'Available models you created';
					$template_conteudo = false;
					$template_conteudo .= "<ul class='list-group list-group-flush'>";
					foreach ($modelos_disponiveis_criados_pelo_usuario as $disponivel_usuario_pagina_id) {
						$template_conteudo .= return_list_item($disponivel_usuario_pagina_id);
					}
					$template_conteudo .= "</ul>";
					include 'templates/page_element.php';
				}

				if ($ha_outros_modelos_disponiveis == true) {
					echo $page_element_modelos_disponiveis;
				}

			?>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/html_bottom.php';
?>
</html>
