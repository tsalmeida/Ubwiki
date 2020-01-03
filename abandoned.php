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
?>