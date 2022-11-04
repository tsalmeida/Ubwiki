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
							header('Location:pagina.php?pagina_id=3');
							exit();
						}
					} elseif ($item_comp_compartilhamento == 'usuario') {
						$item_usuario_id = $comp['recipiente_id'];
						if ($item_usuario_id != $user_id) {
							header('Location:pagina.php?pagina_id=3');
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
	
	/*if ($conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_etiqueta_titulo', $user_id)") === true) {
	$nova_etiqueta_id = $conn->insert_id;
	$conn->query("INSERT INTO Acervo (user_id, etiqueta_id, etiqueta_tipo, elemento_id) VALUES ($user_id, $nova_etiqueta_id, 'topico', 0)");
	if ($criar_etiqueta_page_tipo != 'acervo') {
		$conn->query("INSERT INTO Etiquetados (etiqueta_id, page_id, page_tipo, user_id) VALUES ($nova_etiqueta_id, $criar_etiqueta_page_id, '$criar_etiqueta_page_tipo', $user_id)");
	}
	if (isset($criar_etiqueta_page_id)) {
		echo "<a href='javascript:void(0);' class='$tag_ativa_classes $criar_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</a>";
	} else {
		echo "<span href='javascript:void(0);' class='$tag_neutra_classes $criar_etiqueta_cor'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</span>";
	}
} else {
	echo false;
}*/
	
	/*if ($conn->query("UPDATE Etiquetados SET estado = 0 WHERE etiqueta_id = $remover_etiqueta_id AND page_id = $remover_etiqueta_page_id AND page_tipo = '$remover_etiqueta_page_tipo'")
	===
	true) {
	echo true;
} else {
	echo false;
}*/
	
	/*
	$topico_anterior = false;
	$topico_proximo = false;
	
	$breadcrumbs = "
    <div class='d-block'><a href='index.php'>$curso_sigla</a></div>
    <div class='d-block spacing0'><i class='fad fa-level-up fa-rotate-90 fa-fw'></i><a href='pagina.php?materia_id=$topico_materia_id'>$topico_materia_titulo</a></div>
  ";
	
	$result = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = $topico_materia_id ORDER BY ordem");
	
	if ($topico_nivel == 1) {
		$count = 0;
		$fawesome = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row = $result->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fad fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel1 = $row['id'];
			$titulo_nivel1 = $row['nivel1'];
			$nivel_nivel1 = $row['nivel'];
			if ($nivel_nivel1 == 1) {
				if ($titulo_nivel1 == $topico_nivel1) {
					$breadcrumbs .= "<div class='spacing1'>$fawesome$titulo_nivel1</div>";
					$count2 = 0;
					$fawesome = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
					$result2 = $conn->query("SELECT id, nivel2 FROM Topicos WHERE nivel1 = '$id_nivel1' AND nivel = 2 ORDER BY ordem");
					while ($row2 = $result2->fetch_assoc()) {
						$id_nivel2 = $row2['id'];
						$titulo_nivel2 = $row2['nivel2'];
						$count2++;
						if ($count2 == 2) {
							$fawesome = "<i class='fad fa-long-arrow-right fa-fw'></i>";
						}
						$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='pagina.php?topico_id=$id_nivel2'>$titulo_nivel2</a></div>";
					}
				} else {
					$breadcrumbs .= "<div class='spacing1'>$fawesome<a href='pagina.php?topico_id=$id_nivel1'>$titulo_nivel1</a></div>";
				}
			}
		}
	}
	if ($topico_nivel > 1) {
		$titulo_nivel1 = return_titulo_topico($topico_nivel1);
		$breadcrumbs .= "<div class='spacing1'><i class='fad fa-level-up fa-rotate-90 fa-fw'></i><a href='pagina.php?topico_id=$topico_nivel1'>$titulo_nivel1</a></div>";
	}
	if ($topico_nivel == 2) {
		$result2 = $conn->query("SELECT id, nivel2 FROM Topicos WHERE nivel1 = $topico_nivel1 AND nivel = 2 ORDER BY ordem");
		$count = 0;
		$fawesome = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row2 = $result2->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fad fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel2 = $row2['id'];
			$titulo_nivel2 = $row2['nivel2'];
			if ($titulo_nivel2 == $topico_nivel2) {
				$breadcrumbs .= "<div class='spacing2'>$fawesome$topico_nivel2</div>";
				$result3 = $conn->query("SELECT id, nivel3 FROM Topicos WHERE materia_id = $topico_materia_id AND nivel = 3 AND nivel2 = $id_nivel2 ORDER BY ordem");
				$count = 0;
				$fawesome3 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row3 = $result3->fetch_assoc()) {
					$id_nivel3 = $row3['id'];
					$titulo_nivel3 = $row3['nivel3'];
					$count++;
					if ($count == 2) {
						$fawesome3 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='pagina.php?topico_id=$id_nivel3'>$titulo_nivel3</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='pagina.php?topico_id=$id_nivel2'>$titulo_nivel2</a></div>";
			}
		}
	}
	if ($topico_nivel > 2) {
		$titulo_nivel2 = return_titulo_topico($topico_nivel2);
		$breadcrumbs .= "<div class='spacing2'><i class='fad fa-level-up fa-rotate-90 fa-fw'></i><a href='pagina.php?topico_id=$topico_nivel2'>$titulo_nivel2</a></div>";
	}
	if ($topico_nivel == 3) {
		$result3 = $conn->query("SELECT id, nivel3 FROM Topicos WHERE nivel2 = $topico_nivel2 AND nivel = 3 ORDER BY ordem");
		$count = 0;
		$fawesome3 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row3 = $result3->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome3 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel3 = $row3['id'];
			$titulo_nivel3 = $row3['nivel3'];
			if ($titulo_nivel3 == $topico_nivel3) {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3$topico_nivel3</div>";
				$result4 = $conn->query("SELECT id, nivel4 FROM Topicos WHERE materia_id = $topico_materia_id AND nivel = 4 AND nivel3 = $id_nivel3 ORDER BY ordem");
				$count = 0;
				$fawesome4 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row4 = $result4->fetch_assoc()) {
					$id_nivel4 = $row4['id'];
					$titulo_nivel4 = $row4['nivel4'];
					$count++;
					if ($count == 2) {
						$fawesome4 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='pagina.php?topico_id=$id_nivel4'>$titulo_nivel4</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='pagina.php?topico_id=$id_nivel3'>$titulo_nivel3</a></div>";
			}
		}
	}
	if ($topico_nivel > 3) {
		$titulo_nivel3 = return_titulo_topico($topico_nivel3);
		$breadcrumbs .= "<div class='spacing3'><i class='fad fa-level-up fa-rotate-90 fa-fw'></i><a href='pagina.php?topico_id=$topico_nivel3'>$titulo_nivel3</a></div>";
	}
	if ($topico_nivel == 4) {
		$result4 = $conn->query("SELECT id, nivel4 FROM Topicos WHERE nivel3 = $topico_nivel3 AND nivel = 4 ORDER BY ordem");
		$count = 0;
		$fawesome4 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row4 = $result4->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome4 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel4 = $row4['id'];
			$titulo_nivel4 = $row4['nivel4'];
			if ($titulo_nivel4 == $topico_nivel4) {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4$topico_nivel4</div>";
				$result5 = $conn->query("SELECT id, nivel5 FROM Topicos WHERE materia_id = $topico_materia_id AND nivel = 5 AND nivel4 = $id_nivel4 ORDER BY ordem");
				$count = 0;
				$fawesome5 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row5 = $result5->fetch_assoc()) {
					$id_nivel5 = $row5['id'];
					$titulo_nivel5 = $row5['nivel5'];
					$count++;
					if ($count == 2) {
						$fawesome5 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing5'>$fawesome5<a href='pagina.php?topico_id=$id_nivel5'>$titulo_nivel5</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='pagina.php?topico_id=$id_nivel4'>$titulo_nivel4</a></div>";
			}
		}
	}
	if ($topico_nivel > 4) {
		$titulo_nivel4 = return_titulo_topico($topico_nivel4);
		$breadcrumbs .= "<div class='spacing4'><i class='fad fa-level-up fa-rotate-90 fa-fw'></i><a href='pagina.php?topico_id=$topico_nivel4'>$titulo_nivel4</a></div>";
	}
	if ($topico_nivel == 5) {
		$result5 = $conn->query("SELECT id, nivel5 FROM Topicos WHERE nivel4 = $nivel4 AND nivel = 5 ORDER BY ordem");
		$count = 0;
		$fawesome5 = "<i class='fad fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row5 = $result5->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome5 = "<i class='fad fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel5 = $row5['id'];
			$titulo_nivel5 = $row5['nivel5'];
			if ($titulo_nivel5 == $topico_nivel5) {
				$breadcrumbs .= "<div class='spacing5'>$fawesome5$topico_nivel5</div>";
			} else {
				$breadcrumbs .= "<div class='spacing5'>$fawesome5<a href='pagina.php?topico_id=$id_nivel5'>$titulo_nivel5</a></div>";
			}
		}
	}
	 */
	
	if ($grupos_do_usuario->num_rows > 0) {
		$template_id = 'convidar_grupo';
		$template_titulo = 'Convide alguém para seu grupo de estudos';
		$template_botoes = false;
		$template_classes = 'esconder_sessao justify-content-center';
		$template_conteudo = false;
		$template_conteudo .=
			"
        <form method='post'>
            <div class='mb-3'>
                <select class='$select_classes form-select' name='convidar_apelido' id='convidar_apelido' required>
                    <option value='' disabled selected>Apelido do convidado</option>
                                    ";
		$query = prepare_query("SELECT apelido, id FROM Usuarios WHERE apelido IS NOT NULL ORDER BY apelido");
		$usuarios = $conn->query($query);
		while ($usuario = $usuarios->fetch_assoc()) {
			$usuario_apelido = $usuario['apelido'];
			$usuario_id = $usuario['id'];
			$template_conteudo .= "<option value='$usuario_id'>$usuario_apelido</option>";
		}
		$template_conteudo .= "
                                </select>
                                </div>
                                ";
		
		$template_conteudo .= "
                                <div class='mb-3'>
                                    <select class='$select_classes form-select' name='convidar_grupo_id' id='convidar_grupo_id' required>
                                        <option value='' disabled selected>Selecione o grupo de estudo</option>
                                ";
		while ($grupo_do_usuario = $grupos_do_usuario->fetch_assoc()) {
			$grupo_do_usuario_id = $grupo_do_usuario['id'];
			$grupo_do_usuario_titulo = $grupo_do_usuario['titulo'];
			$template_conteudo .= "<option value='$grupo_do_usuario_id'>$grupo_do_usuario_titulo</option>";
		}
		$template_conteudo .= "
                                    </select>
                                    </div>
                                    <div class='row justify-content-center'>
                                        <button name='trigger_convidar_grupo' class='$button_classes'>Enviar convite</button>
                                    </div>
                                    </form>
							    ";
		include 'templates/page_element.php';
	}
	
	echo "
        <div class='mb-3'>
			<select class='form-select' name='criar_referencia_tipo' id='criar_referencia_tipo' required>
			  <option value='' disabled selected>Tipo da nova referência:</option>
			  <option value='referencia'>Materia de leitura: livros, artigos, páginas virtuais etc.</option>
			  <option value='video'>Material videográfico: vídeos virtuais, filmes etc.</option>
			  <option value='album_musica'>Material em áudio: álbuns de música, podcasts</option>
			</select>
        </div>
	";
	
	/*
@font-face {
  font-family: "Cooper-Hewitt-Book";
  src: url('../CHtypeface/CooperHewitt-Book.woff') format('woff');
}

@font-face {
  font-family: "Cooper-Hewitt-Book";
  src: url('../CHtypeface/CooperHewitt-BookItalic.woff') format('woff');
  font-style: italic;
}

@font-face {
  font-family: "Cooper-Hewitt-Book";
  src: url('../CHtypeface/CooperHewitt-Medium.woff') format('woff');
  font-weight: bold;
}

@font-face {
  font-family: "Cooper-Hewitt-Book";
  src: url('../CHtypeface/CooperHewitt-MediumItalic.woff') format('woff');
  font-weight: bold;
  font-style: italic;
}

@font-face {
  font-family: "Cooper-Hewitt-Medium";
  src: url('../CHtypeface/CooperHewitt-Medium.woff') format('woff');
}

@font-face {
  font-family: "Cooper-Hewitt-Medium";
  src: url('../CHtypeface/CooperHewitt-MediumItalic.woff') format('woff');
  font-style: italic;
}*/

?>