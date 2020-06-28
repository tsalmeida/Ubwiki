<?php

	//TODO: Notificações
	//TODO: Os ícones devem ser determinados de acordo com as práticas do usuário. Quando há bookmarks, o ícone de bookmarks aparece, por exemplo. Essas informações precisam ser registradas em variáveis da sessão, determinadas no momento do login.
	//TODO: Etiquetas como pastas que organizam os textos e páginas do usuário.
	//TODO: A sala de visitas deve ter espaço para adicionar os perfis do usuário em sites de mídia social.
	//TODO: Grupos de Estudos é um ótimo candidato para próximo ícone responsivo.

	$pagina_tipo = 'escritorio';
	include 'engine.php';
	$pagina_id = $user_escritorio;
	$query = prepare_query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	$etiquetados = $conn->query($query);

	if ($user_email == false) {
		header('Location:ubwiki.php');
		exit();
	}

	if (isset($_POST['novo_nome'])) {
		unset($_SESSION['user_opcoes']);
		$novo_user_nome = $_POST['novo_nome'];
		$novo_user_sobrenome = $_POST['novo_sobrenome'];
		$novo_user_apelido = $_POST['novo_apelido'];
		$query = prepare_query("SELECT id FROM Usuarios WHERE apelido = '$novo_user_apelido' AND id <> $user_id");
		$apelidos = $conn->query($query);
		if ($apelidos->num_rows == 0) {
			$query = prepare_query("UPDATE Usuarios SET nome = '$novo_user_nome', sobrenome = '$novo_user_sobrenome', apelido = '$novo_user_apelido' WHERE id = $user_id");
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
			$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar')");
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar', '$novo_avatar')");
			$conn->query($query);
		}
	}

	if (isset($_POST['selecionar_cor'])) {
		$acceptable_avatar_colors = array('text-primary', 'text-danger', 'text-success', 'text-warning', 'text-secondary', 'text-info', 'text-default', 'text-dark');
		$nova_cor = $_POST['selecionar_cor'];
		if (in_array($nova_cor, $acceptable_avatar_colors)) {
			$_SESSION['user_avatar_cor'] = $_POST['selecionar_cor'];
			$conn->query("DELETE FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar_cor')");
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar_cor', '$nova_cor')");
			$conn->query($query);
		}
	}

	include 'pagina/shared_issets.php';

	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";

	include 'templates/html_head.php';
	include 'templates/navbar.php';

	$all_icons = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
	$icons_to_show = array(1, 2, 3, 4, 6, 13, 16);
	if ($user_bookmarks != array()) {
		array_push($icons_to_show, 10);
	}
	if ($user_areas_interesse != array()) {
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

	if ($user_tipo == 'admin') {
		array_push($icons_to_show, 14);
		array_push($icons_to_show, 20);
	}

	if ($user_revisor == true) {
		array_push($icons_to_show, 17);
	}
	//	$icons_to_hide = array_diff($all_icons, $icons_to_show);

?>
<body class="grey lighten-5">
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
				$template_botoes = "<a id='show_hidden_icons' class='text-info' title='{$pagina_translated['Hidden icons']}'><i class='fad fa-exchange fa-fw fa-lg'></i></a>";
				$template_conteudo_no_col = true;

				if ($raiz_ativa != false) {
					$artefato_id = 'curso_ativo';
					$artefato_titulo = return_pagina_titulo($raiz_ativa);
					$artefato_badge = 'fa-external-link';
					$artefato_icone_background = 'teal lighten-2';
					$artefato_link = "pagina.php?pagina_id=$raiz_ativa";
					$artefato_criacao = $pagina_translated['Curso ativo'];
					$fa_type = 'fas';
					$fa_icone = 'fa-pen-alt';
					$fa_color = 'text-white';
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
				$fa_color = 'text-info';
				if (in_array(2, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'cursos';
				$artefato_subtitulo = $pagina_translated['Seus cursos'];
				$fa_icone = 'fa-graduation-cap';
				$fa_color = 'text-default';
				$artefato_modal = '#modal_cursos';
				if (in_array(3, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'typewriter';
				$artefato_subtitulo = $pagina_translated['Suas páginas e documentos de texto'];
				$fa_icone = 'fa-typewriter';
				$fa_color = 'text-primary';
				$artefato_modal = '#modal_paginas_textos';
				if (in_array(4, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'suas_paginas_livres';
				$artefato_subtitulo = $pagina_translated['your areas of interest'];
				$fa_icone = 'fa-tags';
				$fa_color = 'text-warning';
				$artefato_modal = '#modal_areas_interesse';
				if (in_array(5, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'biblioteca_particular';
				$artefato_subtitulo = $pagina_translated['your collection'];
				$fa_icone = 'fa-books';
				$fa_color = 'text-success';
				$artefato_modal = '#modal_biblioteca_particular';
				if (in_array(6, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'grupos_estudo';
				$artefato_subtitulo = $pagina_translated['your study groups'];
				$fa_icone = 'fa-users';
				$fa_color = 'text-default';
				if (in_array(7, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'notificacoes';
				$artefato_subtitulo = $pagina_translated['notifications'];
				$fa_icone = 'fa-bell fa-swap-opacity';
				$fa_color = 'text-info';
				if (in_array(8, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'comments';
				$artefato_subtitulo = $pagina_translated['Suas participações no fórum'];
				$fa_icone = 'fa-comments-alt';
				$fa_color = 'text-secondary';
				if (in_array(9, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'bookmarks';
				$artefato_subtitulo = $pagina_translated['bookmarks'];
				$fa_icone = 'fa-bookmark';
				$fa_color = 'text-danger';
				if (in_array(10, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'contribuicoes';
				$artefato_subtitulo = $pagina_translated['Verbetes em que contribuiu'];
				$fa_icone = 'fa-spa';
				$fa_color = 'text-warning';
				if (in_array(11, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'referencias';
				$artefato_subtitulo = $pagina_translated['sent references'];
				$fa_icone = 'fa-photo-video';
				$fa_color = 'text-danger';
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
					$fa_color = 'text-primary';
					$template_conteudo .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'sala_visitas';
				$artefato_subtitulo = $pagina_translated['your office lounge'];
				$artefato_link = "pagina.php?pagina_id=$user_lounge";
				$artefato_badge = 'fa-external-link';
				$fa_icone = 'fa-mug-tea';
				$fa_color = 'text-secondary';
				if (in_array(15, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'wallet';
				$artefato_titulo = $pagina_translated['sua carteira'];
				$artefato_subtitulo = $pagina_translated['creditos ubwiki'];
				$artefato_modal = '#modal_wallet';
				$fa_icone = 'fa-wallet';
				$fa_color = 'text-success';
				if (in_array(16, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				if ($user_revisor == true) {
					$artefato_id = 'review';
					$artefato_subtitulo = $pagina_translated['review'];
					$artefato_link = 'revisoes.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-highlighter';
					$fa_color = 'text-warning';
					if (in_array(17, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}
				}

				$artefato_id = 'bfranklin';
				$artefato_subtitulo = $pagina_translated['metodo bfranklin'];
				$artefato_link = 'bfranklin.php';
				$artefato_badge = 'fa-external-link';
				$fa_icone = 'fa-pen-nib';
				$fa_color = 'text-secondary';
				if (in_array(18, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

				$artefato_id = 'reading_planner';
				$artefato_subtitulo = $pagina_translated['Study Planner'];
				$artefato_modal = '#modal_planner';
				$fa_icone = 'fa-calendar-check';
				$fa_color = 'text-info';
				if (in_array(19, $icons_to_show)) {
					$template_conteudo .= include 'templates/artefato_item.php';
				} else {
					$template_conteudo_hidden .= include 'templates/artefato_item.php';
				}

                if ($user_tipo == 'admin') {
					$artefato_id = 'simulados';
					$artefato_subtitulo = $pagina_translated['Simulados'];
					$artefato_modal = '#modal_simulados';
					$fa_icone = 'fa-ballot-check';
					$fa_color = 'text-default';
					if (in_array(20, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}
				}

				if ($user_id == 1) {

					$artefato_id = 'homepage';
					$artefato_subtitulo = $pagina_translated['Homepage'];
					$artefato_link = 'nexus.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-house-user';
					$fa_color = 'text-danger';
					$artefato_icone_background = return_background($fa_color);
					if (in_array(21, $icons_to_show)) {
						$template_conteudo .= include 'templates/artefato_item.php';
					} else {
						$template_conteudo_hidden .= include 'templates/artefato_item.php';
					}

					$artefato_id = 'memory_planner';
					$artefato_subtitulo = $pagina_translated['memory palace planner'];
					$artefato_link = 'mpalace.php';
					$artefato_badge = 'fa-external-link';
					$fa_icone = 'fa-landmark-alt';
					$fa_color = 'text-warning';
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
								$fa_color = 'text-default';
								$artefato_icone_background = 'teal lighten-5';
								$template_conteudo_hidden .= include 'templates/artefato_item.php';*/

				include 'templates/page_element.php';

				$template_id = 'escritorio_segunda_janela';
				$template_titulo = false;
				$template_classes = 'hidden';
				$template_conteudo_class = 'justify-content-start';
				$template_botoes = "<a id='hide_hidden_icons' class='text-success' title='{$pagina_translated['Hidden icons']}'><i class='fad fa-exchange fa-fw fa-lg'></i></a>";
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
		<div class='row justify-content-center'>
			<a href='pagina.php?user_id=$user_id' class='{$_SESSION['user_avatar_cor']}'><i class='fad {$_SESSION['user_avatar_icone']} fa-3x fa-fw'></i></a>
		</div>
		<p>{$pagina_translated['Alterar']}:</p>
		<select name='selecionar_avatar' class='$select_classes'>
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
		<select name='selecionar_cor' class='$select_classes'>
			<option disabled selected value=''>{$pagina_translated['Cor do seu avatar']}</option>
			<option value='text-primary'>{$pagina_translated['Azul']}</option>
			<option value='text-danger'>{$pagina_translated['Vermelho']}</option>
			<option value='text-success'>{$pagina_translated['Verde']}</option>
			<option value='text-warning'>{$pagina_translated['Amarelo']}</option>
			<option value='text-secondary'>{$pagina_translated['Roxo']}</option>
			<option value='text-info'>{$pagina_translated['Azul-claro']}</option>
			<option value='text-default'>{$pagina_translated['Verde-azulado']}</option>
			<option value='text-dark'>{$pagina_translated['Preto']}</option>
		</select>
		<h3 class='mt-3'>{$pagina_translated['Perfil']}</h3>
        <p>{$pagina_translated['Você é identificado exclusivamente por seu apelido em todas as suas atividades públicas.']}</p>
        <div class='md-form'><input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required>
            <label data-error='inválido' data-successd='válido' for='novo_apelido' required>{$pagina_translated['Apelido']}</label>
        </div>
        <p>{$pagina_translated['Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.']}</p>
        <div class='md-form'>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>

            <label data-error='inválido' data-successd='válido'
                   for='novo_nome'>{$pagina_translated['Nome']}</label>
        </div>
        <div class='md-form'>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required>
            <label data-error='inválido' data-successd='válido' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' required>{$pagina_translated['Sobrenome']}</label>
        </div>
        <h3>{$pagina_translated['options']}</h3>
        <div class='md-form'>
            <input type='checkbox' class='form-check-input' id='hide_navbar_option' name='hide_navbar_option' $hide_navbar_checked>
            <label for='hide_navbar_option' class='form-check-label'>{$pagina_translated['Hide title and navbar']}</label>
        </div>
        <div class='md-form'>
        	<input type='checkbox' class='form-check-input' id='opcao_texto_justificado' name='opcao_texto_justificado' $texto_justificado_checked>
        	<label class='form-check-label' for='opcao_texto_justificado'>{$pagina_translated['Mostrar verbetes com texto justificado']}</label>
		</div>
    ";
	$template_modal_body_conteudo .= "
        <h3>{$pagina_translated['Dados de cadastro']}</h3>
        <ul class='list-group'>
            <li class='list-group-item'><strong>{$pagina_translated['Conta criada em']}:</strong> $user_criacao</li>
            <li class='list-group-item'><strong>{$pagina_translated['Email']}:</strong> $user_email</li>
        </ul>
	";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_estudos_recentes';
	$template_modal_titulo = $pagina_translated['recent_visits'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_biblioteca_particular';
	$template_modal_titulo = $pagina_translated['your collection'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_grupos_estudo';
	$template_modal_titulo = $pagina_translated['your study groups'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_comments';
	$template_modal_titulo = $pagina_translated['Suas participações no fórum'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_bookmarks';
	$template_modal_titulo = $pagina_translated['bookmarks'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_cursos';
	$template_modal_titulo = $pagina_translated['Seus cursos'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_notificacoes';
	$template_modal_titulo = $pagina_translated['notifications'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_referencias';
	$template_modal_titulo = $pagina_translated['sent references'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_areas_interesse';
	$template_modal_titulo = $pagina_translated['Gerenciar etiquetas'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_contribuicoes';
	$template_modal_titulo = $pagina_translated['Verbetes em que contribuiu'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_simulados';
	$template_modal_titulo = $pagina_translated['Simulados'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_planner';
	$template_modal_titulo = $pagina_translated['Study Planner'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_wallet';
	$template_modal_titulo = $pagina_translated['sua carteira'];
	$template_modal_body_conteudo = false;
	//$template_modal_body_conteudo .= "<p>{$pagina_translated['creditos visite']} <a href='https://www.grupoubique
	//.com.br' target='_blank'>www.grupoubique.com.br</a></p>";
	$template_modal_body_conteudo .= "<p>Formas de comprar créditos Ubwiki:</p>";
	$template_modal_body_conteudo .= "
        <form id='formulario_codigo' method='post' class='border rounded p-3 mb-2 hidden'>
            <div class='md-form'>
                <input type='text' class='form-control' id='adicionar_credito_codigo' name='adicionar_credito_codigo'>
                <label for='adicionar_credito_codigo'>{$pagina_translated['adicionar credito codigo']}</label>
                <div class='row d-flex justify-content-center'>
                <button class='$button_classes'>{$pagina_translated['send']}</button>
                </div>
            </div>
        </form>
	";
	$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
	$template_modal_body_conteudo .= put_together_list_item('link_button', 'mostrar_formulario_codigo', 'text-info', 'fad fa-gift-card', 'Adicionar crédito por código', false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N9U85AQL7RBF8";
	$oferta_texto = "Comprar 100 Créditos Ubwiki por R$ 100";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'text-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=43NNZHEDD6278";
	$oferta_texto = "Comprar 300 Créditos Ubwiki por R$ 285";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'text-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$oferta_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AN49TH77ETDY6";
	$oferta_texto = "Comprar 600 Créditos Ubwiki for R$ 550";
	$template_modal_body_conteudo .= put_together_list_item('link_blank', $oferta_link, 'text-primary', 'fad fa-external-link', $oferta_texto, false, false, false);

	$carteira_texto = "<strong>{$pagina_translated['Ubwiki credit current']} </strong>{$user_wallet}";
	$template_modal_body_conteudo .= put_together_list_item('inactive', false, false, 'fad fa-usd-circle', $carteira_texto, false, false, 'mt-2 lime lighten-5 b-0 teal-text font-italic d-flex justify-content-around');

	$template_modal_show_buttons = false;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_paginas_textos';
	$template_modal_titulo = $pagina_translated['Suas páginas e documentos de texto'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<div class='row d-flex justify-content-around border rounded m-1'>";

	$artefato_id = 'nova_pagina';
	$artefato_titulo = $pagina_translated['new_private_page'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?pagina_id=new';
	$fa_icone = 'fa-columns';
	$fa_color = 'text-info';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';

	$artefato_id = 'novo_documento_texto';
	$artefato_titulo = $pagina_translated['Nova anotação privada'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?texto_id=new';
	$fa_icone = 'fa-file-alt fa-swap-opacity';
	$fa_color = 'text-primary';
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
	$template_modal_show_buttons = false;

	$template_modal_body_conteudo .= "
							<form method='post'>
								<div class='md-form mb-2'>
									<input type='text' name='novo_grupo_titulo' id='novo_grupo_titulo' class='form-control validate mb-1' required>
									<label data-error='inválido' data-success='válido' for='novo_grupo_titulo'>{$pagina_translated['Nome do novo grupo de estudos']}</label>
								</div>
								<div class='row justify-content-center'>
									<button name='trigger_novo_grupo' class='$button_classes'>{$pagina_translated['Criar grupo de estudos']}</button>
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
			$template_modal_body_conteudo .= "<p><strong>{$pagina_translated['study groups need for nickname']} <span class='text-info'><i class='fad fa-user-cog'></i></span></strong></p>";
		} else {
			if ($convites_ativos->num_rows > 0) {
				$template_modal_body_conteudo .= "
                                <h2>{$pagina_translated['Você recebeu convite para participar de grupos de estudos:']}</h2>
                                <form method='post'>
                                    <div class='md-form mb-2'>
                                        <select class='$select_classes' name='responder_convite_grupo_id' id='responder_convite_grupo_id'>
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
                                    <button name='trigger_aceitar_convite' class='$button_classes'>{$pagina_translated['Aceitar convite']}</button>
                                    <button name='trigger_rejeitar_convite' class='$button_classes_red'>{$pagina_translated['Rejeitar convite']}</button>
                                </div>
                                </form>
                            ";
			}
		}

		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}
	$template_modal_div_id = 'modal_gerenciar_etiquetas';
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
    $(document).on('click', '#artefato_typewriter', function () {
        $.post('engine.php', {
            'list_user_pages': true
        }, function (data) {
            if (data != 0) {
                $('#user_pages_hide').removeClass('hidden');
                $('#user_pages').empty();
                $('#user_pages').append(data);
            } else {
                $('#user_pages_hide').addClass('hidden');
            }
        });
        $.post('engine.php', {
            'list_user_texts': true
        }, function (data) {
            if (data != 0) {
                $('#user_texts_hide').removeClass('hidden');
                $('#user_texts').empty();
                $('#user_texts').append(data);
            } else {
                $('#user_texts_hide').addClass('hidden');
            }
        });
    });
    $(document).on('click', '#hide_hidden_icons', function () {
        $('#escritorio_segunda_janela').addClass('hidden')
        $('#escritorio_primeira_janela').removeClass('hidden')
    })
    $(document).on('click', '#show_hidden_icons', function () {
        $('#escritorio_primeira_janela').addClass('hidden')
        $('#escritorio_segunda_janela').removeClass('hidden')
    })
</script>

<?php

	include 'templates/footer.html';
	$sistema_etiquetas_elementos = true;
	$sistema_etiquetas_topicos = true;
	$mdb_select = true;
	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>