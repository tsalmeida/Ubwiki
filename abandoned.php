<?php
	/*if ((strpos($texto_tipo, 'anotac') !== false) || ($texto_tipo == 'verbete_user')) {
		$texto_anotacao = true;
		if (($texto_compartilhamento != false) && ($texto_user_id != $user_id)) {
			$comps = $conn->query("SELECT recipiente_id, compartilhamento FROM Compartilhamento WHERE item_tipo = 'texto' AND item_id = $pagina_id");
			if ($comps->num_rows > 0) {
				while ($comp = $comps->fetch_assoc()) {
					$item_comp_compartilhamento = $comp['compartilhamento'];
					if ($item_comp_compartilhamento == 'grupo') {
						$item_grupo_id = $comp['recipiente_id'];
						$check_membro_grupo = check_membro_grupo($user_id, $item_grupo_id);
						if ($check_membro_grupo == false) {
							header('Location:pagina.php?pagina_id=4');
							exit();
						}
					} elseif ($item_comp_compartilhamento == 'usuario') {
						$item_usuario_id = $comp['recipiente_id'];
						if ($item_usuario_id != $user_id) {
							header('Location:pagina.php?pagina_id=4');
							exit();
						}
					}
				}
			} else {
				header('Location:pagina.php?pagina_id=3');
				exit();
			}
		}
	}*/
	
	/*else {
		if ((strpos($template_id, 'anotac') !== false) || ($template_id == 'verbete_user')) {
			$template_quill_meta_tipo = 'anotacoes';
			$template_quill_toolbar_and_whitelist = 'anotacoes';
			$template_quill_initial_state = 'edicao';
			$template_classes = 'anotacoes_sticky';
			$template_quill_public = false;
		} else {
			$template_quill_meta_tipo = 'verbete';
			$template_quill_public = true;
			$template_quill_toolbar_and_whitelist = 'general';
			if (!isset($template_quill_initial_state)) {
				$template_quill_initial_state = 'leitura';
			}
		}
	}*/
	
	/*else {
		if (!isset($template_quill_pagina_id)) {
			if (isset($topico_id)) {
				$template_quill_pagina_id = $topico_id;
			} elseif (isset($elemento_id)) {
				$template_quill_pagina_id = $elemento_id;
			} elseif (isset($questao_id)) {
				$template_quill_pagina_id = $questao_id;
			} elseif (isset($texto_apoio_id)) {
				$template_quill_pagina_id = $texto_apoio_id;
			} elseif (isset($materia_id)) {
				$template_quill_pagina_id = $materia_id;
			} elseif (isset($curso_id)) {
				$template_quill_pagina_id = $curso_id;
			} else {
				$template_quill_pagina_id = false;
			}
		}
	}*/
	
?>