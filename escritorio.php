<?php

	//TODO: Notificações
	//TODO: Etiquetas como pastas que organizam os textos e páginas do usuário.
	//TODO: A sala de visitas deve ter espaço para adicionar os perfis do usuário em sites de mídia social.
	//TODO: Show every one of the user's entry into Textos_arquivo, allow him to delete them.
	//TODO: Transferência de planos de estudos.

	$pagina_tipo = 'escritorio';
	include 'engine.php';
	$pagina_id = $user_escritorio;

	if ($user_escritorio == false) {
		header('Location:ubwiki.php');
		exit();
	}

	//Ideally this query would only be sent when the user clicked to open the modal.
	$etiquetados = false;
	$query = prepare_query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	$etiquetados = $conn->query($query);

	if ($_SESSION['user_email'] == false) {
		header('Location:ubwiki.php');
		exit();
	}

	if (isset($_POST['novo_nome'])) {
		unset($_SESSION['user_opcoes']);
		$novo_user_nome = $_POST['novo_nome'];
		$novo_user_sobrenome = $_POST['novo_sobrenome'];
		$novo_user_apelido = $_POST['novo_apelido'];
		$query = prepare_query("SELECT id FROM usuarios WHERE apelido = '$novo_user_apelido' AND id <> $user_id");
		$apelidos = $conn->query($query);
		if ($apelidos->num_rows == 0) {
			$query = prepare_query("UPDATE usuarios SET nome = '$novo_user_nome', sobrenome = '$novo_user_sobrenome', apelido = '$novo_user_apelido' WHERE id = $user_id");
			$conn->query($query);
			$user_nome = $novo_user_nome;
			$user_sobrenome = $novo_user_sobrenome;
			$user_apelido = $novo_user_apelido;
			if (isset($_POST['opcao_texto_justificado'])) {
				$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'texto_justificado'");
				$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 1)");
				$conn->query($query);
				$opcao_texto_justificado_value = true;
			} else {
				$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'texto_justificado'");
				$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 0)");
				$conn->query($query);
				$opcao_texto_justificado_value = false;
			}
			if (isset($_POST['hide_navbar_option'])) {
				$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'hide_navbar'");
				$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'hide_navbar', 1)");
				$conn->query($query);
				$opcao_hide_navbar = true;
			} else {
				$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'hide_navbar'");
				$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'hide_navbar', 0)");
				$conn->query($query);
				$opcao_hide_navbar = false;
			}
		}
	}

	if (isset($_POST['selecionar_avatar'])) {
		$acceptable_avatars = array('fa-user', 'fa-user-tie', 'fa-user-secret', 'fa-user-robot', 'fa-user-ninja', 'fa-user-md', 'fa-user-injured', 'fa-user-hard-hat', 'fa-user-graduate', 'fa-user-crown', 'fa-user-cowboy', 'fa-user-astronaut', 'fa-user-alien', 'fa-cat', 'fa-cat-space', 'fa-dog', 'fa-ghost');
		$novo_avatar = $_POST['selecionar_avatar'];
		if (in_array($novo_avatar, $acceptable_avatars)) {
			$_SESSION['user_avatar_icone'] = $_POST['selecionar_avatar'];
			$query = prepare_query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar'");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar', '$novo_avatar')");
			$conn->query($query);
		}
	}

	if (isset($_POST['selecionar_cor'])) {
		$acceptable_avatar_colors = array('link-primary', 'link-danger', 'link-success', 'link-warning', 'link-purple', 'link-teal', 'link-teal', 'link-dark');
		$nova_cor = $_POST['selecionar_cor'];
		if (in_array($nova_cor, $acceptable_avatar_colors)) {
			$_SESSION['user_avatar_cor'] = $_POST['selecionar_cor'];
			$query = prepare_query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar_cor'");
			$conn->query($query);
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar_cor', '$nova_cor')");
			$conn->query($query);
		}
	}

	include 'pagina/shared_issets.php';

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='{$_SESSION['user_email']}';
        </script>
    ";

	include 'templates/html_head.php';
	include 'templates/navbar.php';

	$all_icons = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28);
	$icons_to_show = array(1, 2, 3, 4, 6, 13, 16, 21, 25, 26, 27, 28);
	if ($user_bookmarks != array()) {
		array_push($icons_to_show, 10);
	}	if ($user_areas_interesse != array()) {
		array_push($icons_to_show, 5);
	}
	if (isset($_SESSION['user_opcoes']['show_planos'])) {
		if ($_SESSION['user_opcoes']['show_planos'][0] == true) {
			array_push($icons_to_show, 19);
		}
	}

	if (isset($_SESSION['user_opcoes']['show_bfranklin'])) {
		if ($_SESSION['user_opcoes']['show_bfranklin'][0] == true) {
			array_push($icons_to_show, 18);
		}
	}

	if (isset($_SESSION['user_opcoes']['grupos_estudo'])) {
		if ($_SESSION['user_opcoes']['grupos_estudo'][0] == true) {
			array_push($icons_to_show, 7);
		}
	}

	if (isset($_SESSION['user_opcoes']['docs_shared'])) {
		array_push($icons_to_show, 23);
	}

	if ($user_tipo == 'admin') {
		array_push($icons_to_show, 14);
		array_push($icons_to_show, 20);
	}

	if ($user_revisor == true) {
		array_push($icons_to_show, 17);
	}
	//	$icons_to_hide = array_diff($all_icons, $icons_to_show);

?>
<body class="bg-light">
<div class="container mt-1">
	<?php
		if ($opcao_hide_navbar == false) {
			if ($user_apelido != false) {
				$template_titulo = $user_apelido;
				$template_titulo_above = $pagina_translated['user_office'];
			} else {
				$template_titulo = $pagina_translated['user_office'];
			}
			include 'templates/titulo.php';
		}
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
			<?php

				$template_conteudo_hidden = false;

				$template_id = 'escritorio_primeira_janela';
				$template_titulo = false;
				$template_conteudo = false;
				$template_conteudo_class = 'justify-content-start';
				$template_botoes = "<a id='show_hidden_icons' class='link-success' title='{$pagina_translated['Hidden icons']}' href='javascript:void(0);'><i class='fad fa-exchange fa-fw fa-lg'></i></a>";
				$template_conteudo_no_col = true;

				if ($raiz_ativa != false) {
					$artefato_id = 'curso_ativo';
					$artefato_titulo = return_pagina_titulo($raiz_ativa);
					$artefato_badge = 'fa-external-link';
					$artefato_icone_background = 'bg-primary';
					$artefato_link = "pagina.php?pagina_id=$raiz_ativa";
					$artefato_criacao = $pagina_translated['Curso ativo'];
					$fa_type = 'fas';
					$fa_icone = 'fa-pen-alt';
					$fa_color = 'link-light';
					if (in_array(1, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}
				}

				$artefato_id = 'estudos_recentes';
				$artefato_subtitulo = $pagina_translated['recent_visits'];
				$artefato_modal = '#modal_estudos_recentes';
				$fa_icone = 'fa-history fa-swap-opacity';
				$fa_color = 'link-info';
				if (in_array(2, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'cursos';
				$artefato_subtitulo = $pagina_translated['Seus cursos'];
				$fa_icone = 'fa-graduation-cap';
				$fa_color = 'link-teal';
				$artefato_modal = '#modal_cursos';
				if (in_array(3, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'typewriter';
				$artefato_subtitulo = $pagina_translated['Suas páginas e documentos de texto'];
				$fa_icone = 'fa-typewriter';
				$fa_color = 'link-primary';
				$artefato_modal = '#modal_paginas_textos';
				if (in_array(4, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'suas_paginas_livres';
				$artefato_subtitulo = $pagina_translated['your areas of interest'];
				$fa_icone = 'fa-tags';
				$fa_color = 'link-warning';
				$artefato_modal = '#modal_areas_interesse';
				if (in_array(5, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'biblioteca_particular';
				$artefato_subtitulo = $pagina_translated['your collection'];
				$fa_icone = 'fa-books';
				$fa_color = 'link-success';
				$artefato_modal = '#modal_biblioteca_particular';
				if (in_array(6, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}


				$artefato_id = 'reading_planner';
				$artefato_subtitulo = $pagina_translated['Study Planner'];
				$artefato_modal = '#modal_planner';
				$fa_icone = 'fa-calendar-check';
				$fa_color = 'link-info';
				if (in_array(19, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'grupos_estudo';
				$artefato_subtitulo = $pagina_translated['your study groups'];
				$fa_icone = 'fa-users';
				$fa_color = 'link-purple';
				if (in_array(7, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'docs_shared';
				$artefato_subtitulo = $pagina_translated['docs shared'];
				$fa_icone = 'fa-share-square';
				$fa_color = 'link-orange';
				if (in_array(23, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'notificacoes';
				$artefato_subtitulo = $pagina_translated['notifications'];
				$fa_icone = 'fa-bell fa-swap-opacity';
				$fa_color = 'link-info';
				if (in_array(8, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'comments';
				$artefato_subtitulo = $pagina_translated['Suas participações no fórum'];
				$fa_icone = 'fa-comments-alt';
				$fa_color = 'link-purple';
				if (in_array(9, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'bookmarks';
				$artefato_subtitulo = $pagina_translated['bookmarks'];
				$fa_icone = 'fa-bookmark';
				$fa_color = 'link-danger';
				if (in_array(10, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'contribuicoes';
				$artefato_subtitulo = $pagina_translated['Verbetes em que contribuiu'];
				$fa_icone = 'fa-spa';
				$fa_color = 'link-warning';
				if (in_array(11, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'referencias';
				$artefato_subtitulo = $pagina_translated['sent references'];
				$fa_icone = 'fa-photo-video';
				$fa_color = 'link-danger';
				if (in_array(12, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$fa_icone = $_SESSION['user_avatar_icone'];
				$fa_color = $_SESSION['user_avatar_cor'];
				$artefato_modal = '#modal_opcoes';
				$artefato_badge = 'fa-cog fa-swap-opacity';
				$artefato_subtitulo = $pagina_translated['user settings'];
				if (in_array(13, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				if (in_array(14, $icons_to_show)) {
					$artefato_id = 'administradores';
					$artefato_subtitulo = $pagina_translated['administrators page'];
					$artefato_link = 'admin.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-user-crown';
					$fa_color = 'link-primary';
					$template_conteudo .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'sala_visitas';
				$artefato_subtitulo = $pagina_translated['your office lounge'];
				$artefato_link = "pagina.php?pagina_id=$user_lounge";
				$artefato_badge = 'fa-external-link';
				$fa_icone = 'fa-mug-tea';
				$fa_color = 'link-orange';
				if (in_array(15, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

//				$artefato_id = 'wallet';
//				$artefato_titulo = $pagina_translated['sua carteira'];
//				$artefato_subtitulo = $pagina_translated['creditos ubwiki'];
//				$artefato_modal = '#modal_wallet';
//                $modal_scrollable = false;
//				$fa_icone = 'fa-wallet';
//				$fa_color = 'link-success';
//				if (in_array(16, $icons_to_show)) {
//					$template_conteudo .= include 'templates/artefato_item.php';
//				} else {
//					$template_conteudo_hidden .= include 'templates/artefato_item.php';
//				}

//				if ($user_revisor == true) {
//					$artefato_id = 'review';
//					$artefato_subtitulo = $pagina_translated['review'];
//					$artefato_link = 'revisoes.php';
//					$artefato_badge = 'fa-external-link';
//					$fa_icone = 'fa-highlighter';
//					$fa_color = 'link-warning';
//					if (in_array(17, $icons_to_show)) {
//						$template_conteudo .= include 'templates/artefato_item.php';
//					} else {
//						$template_conteudo_hidden .= include 'templates/artefato_item.php';
//					}
//				}

				$artefato_id = 'bfranklin';
				$artefato_subtitulo = $pagina_translated['metodo bfranklin'];
				$artefato_link = 'bfranklin.php';
				$artefato_badge = 'fa-external-link';
				$fa_icone = 'fa-pen-nib';
				$fa_color = 'link-purple';
				if (in_array(18, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				if ($user_tipo == 'admin') {
					$artefato_id = 'simulados';
					$artefato_subtitulo = $pagina_translated['Simulados'];
					$artefato_modal = '#modal_simulados';
					$fa_icone = 'fa-ballot-check';
					$fa_color = 'link-teal';
					if (in_array(20, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}
				}

				if ($user_id == 1) {

					$artefato_id = 'all_historico';
					$artefato_subtitulo = $pagina_translated['todos registros'];
					$artefato_modal = '#modal_all_historico';
					$fa_icone = 'fa-file-edit';
					$fa_color = 'link-info';
					if (in_array(24, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

//					$artefato_id = 'repeated_words';
//					$artefato_subtitulo = "Find repeated words";
//					$artefato_link = 'notepad.php';
//					$artefato_badge = 'fa-external-link';
//					$fa_icone = 'fa-magnifying-glass';
//					$fa_color = 'link-purple';
//					$artefato_icone_background = return_background($fa_color);
//					if (in_array(25, $icons_to_show)) {
//						$template_conteudo .= include 'templates/artefato_item.php';
//					} else {
//						$template_conteudo_hidden .= include 'templates/artefato_item.php';
//					}

					$artefato_id = 'homepage';
//					$artefato_subtitulo = $pagina_translated['Homepage'];
					$artefato_subtitulo = 'Nexus Startpage';
					$artefato_link = 'nexus.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-house-turret';
					$fa_color = 'link-orange';
//					$artefato_icone_background = return_background($fa_color);
					if (in_array(21, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

                    $artefato_id = 'cosmography';
//					$artefato_subtitulo = $pagina_translated['Homepage'];
					$artefato_subtitulo = 'Cosmography';
					$artefato_link = 'cosmography.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-galaxy';
					$fa_color = 'link-purple';
//					$artefato_icone_background = return_background($fa_color);
					if (in_array(27, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

                    $artefato_id = 'travelogue';
//					$artefato_subtitulo = $pagina_translated['Homepage'];
					$artefato_subtitulo = 'Travelogue';
					$artefato_link = 'travelogue.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-ticket';
					$fa_color = 'link-success';
//					$artefato_icone_background = return_background($fa_color);
					if (in_array(28, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

					$artefato_id = 'memory_planner';
					$artefato_subtitulo = $pagina_translated['memory palace planner'];
					$artefato_link = 'mpalace.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-landmark-alt';
					$fa_color = 'link-warning';
					$artefato_icone_background = return_background($fa_color);
					if (in_array(22, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

				}
				/*
                    $artefato_id = 'hidden_settings';
                    $artefato_subtitulo = $pagina_translated['Hidden icons settings'];
                    $artefato_modal = '#modal_hidden_settings';
                    $fa_icone = 'fa-eye';
                    $fa_color = 'link-teal';
                    $artefato_icone_background = 'bg-teal';
                    $template_conteudo_hidden .= include 'templates/artefato_item.php';*/

				include 'templates/page_element.php';

				$template_id = 'escritorio_segunda_janela';
				$template_titulo = false;
				$template_classes = 'd-none';
				$template_conteudo_class = 'justify-content-start';
				$template_botoes = "<a id='hide_hidden_icons' class='link-success' title='{$pagina_translated['Hidden icons']}' href='javascript:void(0);'><i class='fad fa-exchange fa-swap-opacity fa-fw fa-lg'></i></a>";
				$template_conteudo_no_col = true;
				$template_conteudo = $template_conteudo_hidden;

				include 'templates/page_element.php';

			?>
        </div>
    </div>
</div>
</div>
<?php

	include 'pagina/modal_languages.php';

	$template_modal_div_id = 'modal_opcoes';
	$template_modal_titulo = $pagina_translated['user settings'];
	$texto_justificado_checked = false;
	if ($opcao_texto_justificado_value == true) {
		$texto_justificado_checked = 'checked';
	}
	$hide_navbar_checked = false;
	if ($opcao_hide_navbar == true) {
		$hide_navbar_checked = 'checked';
	}
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<h3>Avatar</h3>
		<div class='d-flex justify-content-center'>
			<a href='pagina.php?user_id=$user_id' class='{$_SESSION['user_avatar_cor']}'><i class='fad {$_SESSION['user_avatar_icone']} fa-3x fa-fw'></i></a>
		</div>
		<p>{$pagina_translated['Alterar']}:</p>
		<select name='selecionar_avatar' class='$select_classes form-select'>
			<option disabled selected value=''>{$pagina_translated['Selecione seu avatar']}</option>
			<option value='fa-user'>{$pagina_translated['Padrão']}</option>
			<option value='fa-user-tie'>{$pagina_translated['De terno']}</option>
			<option value='fa-user-secret'>{$pagina_translated['Espião']}</option>
			<option value='fa-user-robot'>{$pagina_translated['Robô']}</option>
			<option value='fa-user-ninja'>{$pagina_translated['Ninja']}</option>
			<option value='fa-user-md'>{$pagina_translated['Médico']}</option>
			<option value='fa-user-injured'>{$pagina_translated['Machucado']}</option>
			<option value='fa-user-hard-hat'>{$pagina_translated['Capacete de segurança']}</option>
			<option value='fa-user-graduate'>{$pagina_translated['Formatura']}</option>
			<option value='fa-user-crown'>{$pagina_translated['Rei']}</option>
			<option value='fa-user-cowboy'>{$pagina_translated['Cowboy']}</option>
			<option value='fa-user-astronaut'>{$pagina_translated['Astronauta']}</option>
			<option value='fa-user-alien'>{$pagina_translated['Alienígena']}</option>
			<option value='fa-cat'>{$pagina_translated['Gato']}</option>
			<option value='fa-cat-space'>{$pagina_translated['Gato astronauta']}</option>
			<option value='fa-dog'>{$pagina_translated['Cachorro']}</option>
			<option value='fa-ghost'>{$pagina_translated['Fantasma']}</option>
		</select>
		<select name='selecionar_cor' class='$select_classes form-select'>
			<option disabled selected value=''>{$pagina_translated['Cor do seu avatar']}</option>
			<option value='link-primary'>{$pagina_translated['Azul']}</option>
			<option value='link-danger'>{$pagina_translated['Vermelho']}</option>
			<option value='link-success'>{$pagina_translated['Verde']}</option>
			<option value='link-warning'>{$pagina_translated['Amarelo']}</option>
			<option value='link-purple'>{$pagina_translated['Roxo']}</option>
			<option value='link-teal'>{$pagina_translated['Azul-claro']}</option>
			<option value='link-teal'>{$pagina_translated['Verde-azulado']}</option>
			<option value='link-dark'>{$pagina_translated['Preto']}</option>
		</select>
		<h3 class='mt-3'>{$pagina_translated['Perfil']}</h3>
        <p>{$pagina_translated['Você é identificado exclusivamente por seu apelido em todas as suas atividades públicas.']}</p>
        <div class='mb-3'>
        <label data-error='inválido' data-successd='válido' for='novo_apelido' class='form-label' required>{$pagina_translated['Apelido']}</label>
        <input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required>
        </div>
        <p>{$pagina_translated['Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.']}</p>
        <div class='mb-3'>
               <label data-error='inválido' data-successd='válido' for='novo_nome' class='form-label'>{$pagina_translated['Nome']}</label>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>
        </div>
        <div class='mb-3'>
            <label data-error='inválido' data-successd='válido' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' class='form-label' required>{$pagina_translated['Sobrenome']}</label>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required>
        </div>
        <h3>{$pagina_translated['options']}</h3>
        <div class='mb-3'>
            <input type='checkbox' class='form-check-input' id='hide_navbar_option' name='hide_navbar_option' $hide_navbar_checked>
            <label for='hide_navbar_option' class='form-check-label'>{$pagina_translated['Hide title and navbar']}</label>
        </div>
        <div class='mb-3'>
        	<input type='checkbox' class='form-check-input' id='opcao_texto_justificado' name='opcao_texto_justificado' $texto_justificado_checked>
        	<label class='form-check-label' for='opcao_texto_justificado'>{$pagina_translated['Mostrar verbetes com texto justificado']}</label>

		</div>
    ";
	$template_modal_body_conteudo .= "
        <h3>{$pagina_translated['Dados de cadastro']}</h3>
        <ul class='list-group'>
            <li class='list-group-item'><strong>{$pagina_translated['Conta criada em']}:</strong> $user_criacao</li>
            <li class='list-group-item'><strong>{$pagina_translated['Email']}:</strong> {$_SESSION['user_email']}</li>
        </ul>
	";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_estudos_recentes';
	$template_modal_titulo = $pagina_translated['recent_visits'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_biblioteca_particular';
	$template_modal_titulo = $pagina_translated['your collection'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_docs_shared';
	$template_modal_titulo = $pagina_translated['docs shared'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_grupos_estudo';
	$template_modal_titulo = $pagina_translated['your study groups'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_comments';
	$template_modal_titulo = $pagina_translated['Suas participações no fórum'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_bookmarks';
	$template_modal_titulo = $pagina_translated['bookmarks'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_cursos';
	$template_modal_titulo = $pagina_translated['Seus cursos'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_notificacoes';
	$template_modal_titulo = $pagina_translated['notifications'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_referencias';
	$template_modal_titulo = $pagina_translated['sent references'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_areas_interesse';
	$template_modal_titulo = $pagina_translated['Gerenciar etiquetas'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_contribuicoes';
	$template_modal_titulo = $pagina_translated['Verbetes em que contribuiu'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_simulados';
	$template_modal_titulo = $pagina_translated['Simulados'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_planner';
	$template_modal_titulo = $pagina_translated['Study Planner'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_all_historico';
	$template_modal_titulo = $pagina_translated['todos registros'];
	$template_modal_body_conteudo = false;

	include 'templates/modal.php';

	$template_modal_div_id = 'modal_wallet';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['sua carteira'];
	$template_modal_body_conteudo = false;
	//$template_modal_body_conteudo .= "<p>{$pagina_translated['creditos visite']} <a href='https://www.grupoubique
	//.com.br' target='_blank'>www.grupoubique.com.br</a></p>";
	$template_modal_body_conteudo .= "<p>Formas de comprar créditos Ubwiki:</p>";
	$template_modal_body_conteudo .= "
        <form id='formulario_codigo' method='post' class='border rounded p-3 mb-2 hidden'>
            <div class='mb-3'>
                <label for='adicionar_credito_codigo' class='form-label'>{$pagina_translated['adicionar credito codigo']}</label>
                <input type='text' class='form-control' id='adicionar_credito_codigo' name='adicionar_credito_codigo'>
                <div class='row d-flex justify-content-center'>
                    <div class='col-12 d-flex justify-content-center'>
                        <button class='btn btn-primary mt-3 px-5'>{$pagina_translated['send']}</button>
                    </div>
                </div>
            </div>
        </form>
	";
	$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
	$template_modal_body_conteudo .= put_together_list_item('link_button', 'mostrar_formulario_codigo', 'link-teal', 'fad fa-gift-card', 'Adicionar crédito por código', false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N9U85AQL7RBF8";
	$oferta_texto = "Comprar 100 Créditos Ubwiki por R$ 100";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'link-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=43NNZHEDD6278";
	$oferta_texto = "Comprar 300 Créditos Ubwiki por R$ 285";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'link-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AN49TH77ETDY6";
	$oferta_texto = "Comprar 600 Créditos Ubwiki for R$ 550";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'link-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$carteira_texto = "<strong>{$pagina_translated['Ubwiki credit current']} </strong>{$user_wallet}";
	$template_modal_body_conteudo .= put_together_list_item('inactive', false, false, 'fad fa-usd-circle', $carteira_texto, false, false, 'mt-2 bg-success b-0 text-light fst-italic d-flex justify-content-around');


	include 'templates/modal.php';

	$template_modal_div_id = 'modal_paginas_textos';
	$template_modal_titulo = $pagina_translated['Suas páginas e documentos de texto'];

	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<div class='row d-flex justify-content-around border rounded m-1'>";

	$artefato_id = 'nova_pagina';
	$artefato_titulo = $pagina_translated['new_private_page'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?pagina_id=new';
	$fa_icone = 'fa-columns';
	$fa_color = 'link-info';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$artefato_id = 'novo_documento_texto';
	$artefato_titulo = $pagina_translated['Nova anotação privada'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?texto_id=new';
	$fa_icone = 'fa-file-alt fa-swap-opacity';
	$fa_color = 'link-primary';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	$template_modal_body_conteudo .= "</div>";
	$template_modal_body_conteudo .= "<h3 id='user_pages_hide' class='hidden mt-1'>{$pagina_translated['your_pages']}</h3>";
	$template_modal_body_conteudo .= "<ul id='user_pages' class='list-group list-group-flush'></ul>";
	$template_modal_body_conteudo .= "<h3 id='user_texts_hide' class='hidden mt-3'>{$pagina_translated['texts and study notes']}</h3>";
	$template_modal_body_conteudo .= "<ul id='user_texts' class='list-group list-group-flush'></ul>";
	include 'templates/modal.php';

	include 'pagina/modal_add_elemento.php';

	$template_modal_div_id = 'modal_criar_grupo';
	$template_modal_titulo = $pagina_translated['Criar grupo de estudos'];
	$template_modal_body_conteudo = false;


	$template_modal_body_conteudo .= "
							<form method='post'>
								<div class='mb-3'>
									<label data-error='inválido' data-success='válido' for='novo_grupo_titulo' class='form-label'>{$pagina_translated['Nome do novo grupo de estudos']}</label>
									<input type='text' name='novo_grupo_titulo' id='novo_grupo_titulo' class='form-control validate mb-1' required>
								</div>
								<div class='row justify-content-center'>
                                    <div class='col-12'>
                                        <button name='trigger_novo_grupo' class='btn btn-primary'>{$pagina_translated['Criar grupo de estudos']}</button>
                                    </div>
								</div>
							</form>
						    ";

	include 'templates/modal.php';

	$query = prepare_query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado IS NULL");
	$convites_ativos = $conn->query($query);
	if ($convites_ativos->num_rows > 0) {
		$template_modal_div_id = 'modal_reagir_convite';
		$template_modal_titulo = $pagina_translated['Você recebeu convite para participar de grupos de estudos:'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>{$pagina_translated['joining study group explanation']}</p>";

		if ($user_apelido == false) {
			$template_modal_body_conteudo .= "<p><strong>{$pagina_translated['study groups need for nickname']} <span class='link-teal'><i class='fad fa-user-cog'></i></span></strong></p>";
		} else {
			if ($convites_ativos->num_rows > 0) {
				$template_modal_body_conteudo .= "
                                <h2>{$pagina_translated['Você recebeu convite para participar de grupos de estudos:']}</h2>
                                <form method='post'>
                                    <div class='mb-3'>
                                        <select class='$select_classes form-select' name='responder_convite_grupo_id' id='responder_convite_grupo_id'>
                                        <option value='' disabled selected>{$pagina_translated['Selecione o grupo de estudos']}:</option>
                            ";
				while ($convite_ativo = $convites_ativos->fetch_assoc()) {
					$convite_ativo_grupo_id = $convite_ativo['grupo_id'];
					$convite_ativo_grupo_titulo = return_grupo_titulo_id($convite_ativo_grupo_id);
					$template_modal_body_conteudo .= "<option value='$convite_ativo_grupo_id'>$convite_ativo_grupo_titulo</option>";
				}
				$template_modal_body_conteudo .= "
                                </select>
                                </div>
                                <div class='row justify-content-center'>
                                    <button name='trigger_aceitar_convite' class='btn btn-primary'>{$pagina_translated['Aceitar convite']}</button>
                                    <button name='trigger_rejeitar_convite' class='btn btn-danger'>{$pagina_translated['Rejeitar convite']}</button>
                                </div>
                                </form>
                            ";
			}
		}


		include 'templates/modal.php';
	}
	$template_modal_div_id = 'modal_gerenciar_etiquetas';
	$template_modal_show_buttons = true;
	$template_modal_titulo = $pagina_translated['Incluir área de interesse'];
	include 'templates/etiquetas_modal.php';

?>
</body>

<script type="text/javascript">
    $(document).on('click', '#artefato_estudos_recentes', function () {
        $.post('engine.php', {
            'list_estudos_recentes': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_estudos_recentes').empty();
                $('#body_modal_estudos_recentes').append(data);
            }
        });
    });
    $(document).on('click', '#artefato_suas_paginas_livres', function () {
        $.post('engine.php', {
            'list_areas_interesse': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_areas_interesse').empty();
                $('#body_modal_areas_interesse').append(data);
            }
        });
    });
    $(document).on('click', '#artefato_biblioteca_particular', function () {
        $.post('engine.php', {
            'list_biblioteca_particular': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_biblioteca_particular').empty();
                $('#body_modal_biblioteca_particular').append(data);
            }
        });
    });
    $(document).on('click', '#artefato_comments', function () {
        $.post('engine.php', {
            'list_comments': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_comments').empty();
                $('#body_modal_comments').append(data);
            }
        });
    });
    $(document).on('click', '#artefato_notificacoes', function () {
        $.post('engine.php', {
            'list_notificacoes': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_notificacoes').empty();
                $('#body_modal_notificacoes').append(data);
            }
        });
    });
    $(document).on('click', '#artefato_grupos_estudo', function () {
        $.post('engine.php', {
            'list_grupos_estudo': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_grupos_estudo').empty();
                $('#body_modal_grupos_estudo').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_docs_shared', function () {
        $.post('engine.php', {
            'list_docs_shared': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_docs_shared').empty();
                $('#body_modal_docs_shared').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_referencias', function () {
        $.post('engine.php', {
            'list_referencias': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_referencias').empty();
                $('#body_modal_referencias').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_reading_planner', function () {
        $.post('engine.php', {
            'list_planos': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_planner').empty();
                $('#body_modal_planner').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_bookmarks', function () {
        $.post('engine.php', {
            'list_bookmarks': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_bookmarks').empty();
                $('#body_modal_bookmarks').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_contribuicoes', function () {
        $.post('engine.php', {
            'list_contribuicoes': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_contribuicoes').empty();
                $('#body_modal_contribuicoes').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_cursos', function () {
        $.post('engine.php', {
            'list_cursos': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_cursos').empty();
                $('#body_modal_cursos').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_simulados', function () {
        $.post('engine.php', {
            'list_simulados': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_simulados').empty();
                $('#body_modal_simulados').append(data);
            }
        })
    })
    $(document).on('click', '#artefato_all_historico', function () {
        $.post('engine.php', {
            'list_historico': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_all_historico').empty();
                $('#body_modal_all_historico').append(data);
            }
        })
    })
    $(document).on('click', '.delete_edit', function () {
        delete_this_edit = $(this).attr('value');
        $.post('engine.php', {
            'delete_this_edit': delete_this_edit
        }, function (data) {
            if (data != 0) {
                $('#' + delete_this_edit).remove();
            }
        })
    })
    $(document).on('click', '#artefato_typewriter', function () {
        $.post('engine.php', {
            'list_user_pages': true
        }, function (data) {
            if (data != 0) {
                $('#user_pages_hide').removeClass('d-none');
                $('#user_pages').empty();
                $('#user_pages').append(data);
            } else {
                $('#user_pages_hide').addClass('d-none');
            }
        });
        $.post('engine.php', {
            'list_user_texts': true
        }, function (data) {
            if (data != 0) {
                $('#user_texts_hide').removeClass('d-none');
                $('#user_texts').empty();
                $('#user_texts').append(data);
            } else {
                $('#user_texts_hide').addClass('d-none');
            }
        });
    });
    $(document).on('click', '#hide_hidden_icons', function () {
        $('#escritorio_segunda_janela').addClass('d-none')
        $('#escritorio_primeira_janela').removeClass('d-none')
    })
    $(document).on('click', '#show_hidden_icons', function () {
        $('#escritorio_primeira_janela').addClass('d-none')
        $('#escritorio_segunda_janela').removeClass('d-none')
    })
</script>

<?php


	$sistema_etiquetas_elementos = true;
	$sistema_etiquetas_topicos = true;

	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>
