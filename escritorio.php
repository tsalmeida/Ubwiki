<?php
	
	$pagina_tipo = 'escritorio';
	include 'engine.php';
	$pagina_id = return_pagina_id($user_id, $pagina_tipo);

	if (!isset($user_email)) {
		header('Location:login.php');
	}
	
	if (isset($_POST['novo_nome'])) {
		$user_nome = $_POST['novo_nome'];
		$user_sobrenome = $_POST['novo_sobrenome'];
		$apelidos = $conn->query("SELECT id FROM Usuarios WHERE apelido = '$user_apelido'");
		if ($apelidos->num_rows == 0) {
			$user_apelido = $_POST['novo_apelido'];
			$conn->query("UPDATE Usuarios SET nome = '$user_nome', sobrenome = '$user_sobrenome', apelido = '$user_apelido' WHERE id = $user_id");
			if (isset($_POST['opcao_texto_justificado'])) {
				$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 1)");
				$opcao_texto_justificado_value = true;
			} else {
				$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 0)");
				$opcao_texto_justificado_value = false;
			}
		}
	}
	
	include 'pagina/shared_issets.php';
	
	if (isset($_POST['aderir_novo_curso'])) {
		$aderir_novo_curso = $_POST['aderir_novo_curso'];
		$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'curso', $aderir_novo_curso)");
	}
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	
	include 'templates/html_head.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, tipo_pagina) VALUES ($user_id, 'userpage')");
	
	if (isset($_POST['novo_grupo_titulo'])) {
		$novo_grupo_titulo = $_POST['novo_grupo_titulo'];
		$novo_grupo_titulo = mysqli_real_escape_string($conn, $novo_grupo_titulo);
		if ($conn->query("INSERT INTO Grupos (titulo, user_id) VALUES ('$novo_grupo_titulo', $user_id)") === true) {
			$novo_grupo_id = $conn->insert_id;
			$conn->query("INSERT INTO Membros (membro_user_id, grupo_id, estado, user_id) VALUES ($user_id, $novo_grupo_id, 1, $user_id)");
			$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($novo_grupo_id, 'grupo', 'grupo', $user_id)");
			$novo_grupo_pagina_id = $conn->insert_id;
			$conn->query("UPDATE Grupos SET pagina_id = $novo_grupo_pagina_id WHERE id = $novo_grupo_id");
		}
	}
	
	if (isset($_POST['responder_convite_grupo_id'])) {
		$responder_convite_grupo_id = $_POST['responder_convite_grupo_id'];
		$resposta_convite = false;
		if (isset($_POST['trigger_aceitar_convite'])) {
			$resposta_convite = 1;
		}
		if (isset($_POST['trigger_rejeitar_convite'])) {
			$resposta_convite = (int)0;
		}
		$conn->query("UPDATE Membros SET estado = $resposta_convite WHERE grupo_id = $responder_convite_grupo_id AND membro_user_id = $user_id");
	}
	
	$grupos_do_usuario = $conn->query("SELECT id, titulo FROM Grupos WHERE user_id = $user_id AND estado = 1");
	$convites_do_usuario = $conn->query("SELECT criacao, grupo_id, membro_user_id FROM Membros WHERE user_id = $user_id AND estado IS NULL");
	$convites_ativos = $conn->query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado IS NULL");
	$materias = false;
	//$materias = $conn->query("SELECT id, titulo FROM Materias WHERE curso_id = $curso_id ORDER BY ordem");
	$bookmarks = $conn->query("SELECT pagina_id FROM Bookmarks WHERE user_id = $user_id AND bookmark = 1 AND active = 1 ORDER BY id DESC");
	$comentarios = $conn->query("SELECT DISTINCT pagina_id, pagina_tipo FROM Forum WHERE user_id = $user_id");
	$completados = $conn->query("SELECT pagina_id FROM Completed WHERE user_id = $user_id AND estado = 1 AND active = 1");
	$verbetes_escritos = $conn->query("SELECT DISTINCT pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND user_id = $user_id ORDER BY id DESC");
	$etiquetados = $conn->query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
	$notificacoes = $conn->query("SELECT pagina_id FROM Notificacoes WHERE user_id = $user_id AND estado = 1 ORDER BY id DESC");

?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>


<div class='container-fluid'>
    <div class='row justify-content-between px-2'>
			<?php
				echo "<div class='col-md-3 col-sm-12'><div class='row justify-content-start'>";
				echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_opcoes' class='p-2 text-info rounded artefato'><i class='fad fa-user-cog fa-2x fa-fw'></i></a>";
				echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_apresentacao' class='p-2 text-info rounded artefato' title='{$pagina_translated['edit lounge']}'><i class='fad fa-mug-tea fa-2x fa-fw'></i></a>";
				echo "</div></div>";
				echo "<div class='col-md-6 col-sm-12'><div class='row justify-content-center'>";
				echo "
                      <a id='escritorio_home' href='javascript:void(0);' class='p-2 rounded text-muted artefato' title='{$pagina_translated['Retornar à página inicial']}'><i class='fad fa-lamp-desk fa-2x fa-fw'></i></a>
                      <a id='mostrar_textos' href='javascript:void(0);' class='p-2 rounded text-primary artefato' title='{$pagina_translated['Pressione para ver seus textos e notas privadas']}'><i class='fad fa-typewriter fa-2x fa-fw'></i></a>
                      <a id='mostrar_tags' href='javascript:void(0);' class='p-2 rounded text-warning artefato' title='{$pagina_translated['Pressione para ver suas páginas livres']}'><i class='fad fa-tags fa-2x fa-fw'></i></a>
                      <a id='mostrar_acervo' href='javascript:void(0);' class='p-2 rounded text-success artefato' title='{$pagina_translated['Pressione para ver seu acervo virtual']}'><i class='fad fa-books fa-2x fa-fw'></i></a>
                      <a id='mostrar_grupos' href='javascript:void(0);' class='p-2 rounded text-default artefato' title='{$pagina_translated['Pressione para ver seus grupos de estudos']}'><i class='fad fa-users fa-2x fa-fw'></i></a>
                      <a id='mostrar_imagens' href='javascript:void(0);' class='p-2 rounded text-danger artefato' title='{$pagina_translated['Pressione para ver suas imagens públicas e privadas']}'><i class='fad fa-images fa-2x fa-fw'></i></a>
                      ";
				if ($user_tipo == 'admin') {
					echo "<a id='icone_simulados' href='javascript:void(0);' class='p-2 rounded text-secondary artefato' title='{$pagina_translated['Pressione para ver seus simulados']}'><i class='fad fa-clipboard-list-check fa-2x fa-fw'></i></a>
                  ";
				}
				
				echo "</div></div>";
				echo "<div class='col-md-3 col-sm-12'><div class='row justify-content-end'>";
				if ($notificacoes->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_notificacoes' class='p-2 text-default rounded artefato'><i class='fad fa-bell fa-swap-opacity fa-2x fa-fw'></i></a>";
				}
				if ($comentarios->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_forum' class='p-2 text-secondary rounded artefato'><i class='fad fa-comments-alt fa-2x fa-fw'></i></a>";
				}
				if ($completados->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_completados' class='p-2 text-success rounded artefato'><i class='fad fa-check-circle fa-2x fa-fw'></i></a>";
				}
				if ($bookmarks->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_bookmarks' class='p-2 text-danger rounded artefato'><i class='fad fa-bookmark fa-2x fa-fw'></i></a>";
				}
				if ($verbetes_escritos->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_verbetes' class='p-2 text-warning rounded artefato'><i class='fad fa-spa fa-2x fa-fw'></i></a>";
				}
				echo "</div></div>";
			?>
    </div>
</div>


<div class="container">
	<?php
		if ($user_apelido != false) {
			$template_titulo = $user_apelido;
			$template_titulo_above = $pagina_translated['user_office'];
		} else {
			$template_titulo = $pagina_translated['user_office'];
		}
		$template_titulo_context = true;
		include 'templates/titulo.php'
	
	?>

    <div class="row d-flex justify-content-center">

        <div id="coluna_unica" class="col">
					<?php
						
						$visualizacoes = $conn->query("SELECT page_id, tipo_pagina FROM Visualizacoes WHERE user_id = $user_id AND extra2 = 'pagina' ORDER BY id DESC");
						if ($visualizacoes->num_rows > 0) {
							$template_id = 'ultimas_visualizacoes';
							$template_titulo = $pagina_translated['recent_visits'];
							$template_classes = 'mostrar_sessao esconder_sessao';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							$count = 0;
							$resultados = array();
							
							$artefato_titulo = $curso_titulo;
							$artefato_subtitulo = 'Curso ativo';
							$artefato_link = "pagina.php?curso_id=$curso_id";
							$artefato_tipo = 'curso';
							$fa_icone = 'fa-book-reader';
							$fa_color = 'text-white';
							$artefato_icone_background = 'rgba-teal-strong';
							$artefato_background = 'grey lighten-5';
							$artefato_criacao = false;
							$template_conteudo .= include 'templates/artefato_item.php';
							
							while ($visualizacao = $visualizacoes->fetch_assoc()) {
								$visualizacao_page_id = $visualizacao['page_id'];
								if (array_search($visualizacao_page_id, $resultados) !== false) {
									continue;
								} else {
									array_push($resultados, $visualizacao_page_id);
								}
								$visualizacao_tipo_pagina = $visualizacao['tipo_pagina'];
								if ($visualizacao_tipo_pagina == 'elemento') {
									$visualizacao_elemento_id = return_elemento_id_pagina_id($visualizacao_page_id);
									$visualizacao_elemento_info = return_elemento_info($visualizacao_elemento_id);
									$visualizacao_elemento_subtipo = $visualizacao_elemento_info[18];
									$visualizacao_elemento_tipo = $visualizacao_elemento_info[3];
									$elemento_info = return_icone_subtipo($visualizacao_elemento_tipo, $visualizacao_elemento_subtipo);
									$fa_icone = $elemento_info[0];
									$fa_color = $elemento_info[1];
									$artefato_titulo = $visualizacao_elemento_info[4];
									$artefato_subtitulo = $visualizacao_elemento_info[5];
									$artefato_link = "pagina.php?pagina_id=$visualizacao_page_id";
									$artefato_tipo = $visualizacao_elemento_info[3];
								} elseif ($visualizacao_tipo_pagina == 'topico') {
									$fa_icone = 'fa-columns';
									$fa_color = 'text-warning';
									$visualizacao_pagina_info = return_familia($visualizacao_page_id);
									$artefato_titulo = return_pagina_titulo($visualizacao_page_id);
									$artefato_curso_pagina_id = $visualizacao_pagina_info[1];
									$artefato_curso_id = return_pagina_item_id($artefato_curso_pagina_id);
									$artefato_curso_sigla = return_curso_sigla($artefato_curso_id);
									$artefato_materia_pagina_id = $visualizacao_pagina_info[2];
									$artefato_subtitulo_materia_titulo = return_pagina_titulo($artefato_materia_pagina_id);
									$artefato_subtitulo = "$artefato_curso_sigla / $artefato_subtitulo_materia_titulo";
									$artefato_link = "pagina.php?pagina_id=$visualizacao_page_id";
									$artefato_tipo = 'verbete';
									/*} elseif ($visualizacao_tipo_pagina == 'texto') {
									
									}*/
								} elseif ($visualizacao_tipo_pagina == 'pagina') {
									$artefato_titulo = return_pagina_titulo($visualizacao_page_id);
									$pagina_info = return_pagina_info($visualizacao_page_id);
									$fa_icone = 'fa-columns';
									$pagina_compartilhamento = $pagina_info[4];
									if ($pagina_compartilhamento == 'privado') {
										$artefato_subtitulo = $pagina_translated['private page'];
										$fa_color = 'text-info';
									} elseif ($pagina_compartilhamento == 'publico') {
										$artefato_subtitulo = $pagina_translated['public page'];
										$fa_color = 'text-primary';
									} else {
										$artefato_subtitulo = $pagina_translated['free page'];
										$fa_icone = 'fa-tag';
										$fa_color = 'text-warning';
									}
									$artefato_tipo = 'pagina';
									$artefato_link = "pagina.php?pagina_id=$visualizacao_page_id";
								} elseif ($visualizacao_tipo_pagina == 'grupo') {
									$artefato_titulo = return_pagina_titulo($visualizacao_page_id);
									$artefato_subtitulo = $pagina_translated['study group'];
									$fa_icone = 'fa-users';
									$fa_color = 'text-default';
									$artefato_tipo = 'pagina_grupo';
									$artefato_link = "pagina.php?pagina_id=$visualizacao_page_id";
								} else {
									continue;
								}
								$artefato_id = 0;
								$artefato_page_id = false;
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								$count++;
								if ($count == 11) {
									break;
								}
							}
							include 'templates/page_element.php';
						}
						
						$template_id = 'grupos_estudos';
						$template_titulo = $pagina_translated['study groups'];
						$template_botoes = false;
						$template_classes = 'esconder_sessao justify-content-center';
						//$template_col_value = 'col-lg-8 col-md-10 col-sm-12';
						$template_conteudo = false;
						$template_conteudo .= "<p>{$pagina_translated['joining study group explanation']}</p>";
						if ($user_apelido == false) {
							$template_conteudo .= "<p><strong>{$pagina_translated['study groups need for nickname']} <span class='text-info'><i class='fad fa-user-cog'></i></span></strong></p>";
						} else {
							if ($convites_ativos->num_rows > 0) {
								$template_conteudo .= "
                                    <h2>{$pagina_translated['Você recebeu convite para participar de grupos de estudos:']}</h2>
                                    <form method='post'>
                                        <div class='md-form mb-2'>
                                            <select class='$select_classes' name='responder_convite_grupo_id' id='responder_convite_grupo_id'>
                                                <option value='' disabled selected>{$pagina_translated['Selecione o grupo de estudos']}:</option>
                                ";
								while ($convite_ativo = $convites_ativos->fetch_assoc()) {
									$convite_ativo_grupo_id = $convite_ativo['grupo_id'];
									$convite_ativo_grupo_titulo = return_grupo_titulo_id($convite_ativo_grupo_id);
									$template_conteudo .= "<option value='$convite_ativo_grupo_id'>$convite_ativo_grupo_titulo</option>";
								}
								$template_conteudo .= "
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
						include 'templates/page_element.php';
						
						if ($user_apelido != false) {
							$grupos_usuario_membro = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
							if ($grupos_usuario_membro->num_rows > 0) {
								$template_id = 'grupos_usuario_membro';
								$template_titulo = $pagina_translated['your study groups'];
								$template_botoes = false;
								$template_classes = 'esconder_sessao justify-content-center';
								//$template_col_value = 'col-lg-8 col-md-10 col-sm-12';
								$template_conteudo = false;
								$template_conteudo .= "<ul class='list-group list-group-flush'>";
								while ($grupo_usuario_membro = $grupos_usuario_membro->fetch_assoc()) {
									$grupo_usuario_membro_grupo_id = $grupo_usuario_membro['grupo_id'];
									$grupo_usuario_membro_grupo_titulo = return_grupo_titulo_id($grupo_usuario_membro_grupo_id);
									$template_conteudo .= "<a href='pagina.php?grupo_id=$grupo_usuario_membro_grupo_id' ><li class='list-group-item list-group-item-action border-top'>$grupo_usuario_membro_grupo_titulo</li></a>";
								}
								$template_conteudo .= "</ul>";
								include 'templates/page_element.php';
							}
							
							$template_id = 'criar_grupo';
							$template_titulo = $pagina_translated['create new study group'];
							$template_botoes = false;
							$template_classes = 'esconder_sessao justify-content-center';
							//$template_col_value = 'col-lg-8 col-md-10 col-sm-12';
							$template_conteudo = false;
							$template_conteudo .= "
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
							include 'templates/page_element.php';
						}
						
						$template_id = 'escolha_cursos';
						$template_titulo = $pagina_translated['courses'];
						$template_classes = 'mostrar_sessao esconder_sessao justify-content-center';
						$template_conteudo = false;
						$template_conteudo .= "<p>{$pagina_translated['most effective']}</p>";
						$usuario_cursos = $conn->query("SELECT DISTINCT opcao FROM Opcoes WHERE opcao_tipo = 'curso' AND user_id = $user_id");
						$cursos_inscrito = array();
						if ($usuario_cursos->num_rows > 0) {
							$template_conteudo .= "<h2>{$pagina_translated['your courses']}</h2>";
							$template_conteudo .= "<ul class='list-group list-group-flush' class='grey lighten-5'>";
							while ($usuario_curso = $usuario_cursos->fetch_assoc()) {
								$usuario_curso_id = $usuario_curso['opcao'];
								array_push($cursos_inscrito, $usuario_curso_id);
								$usuario_curso_titulo = return_curso_titulo_id($usuario_curso_id);
								$template_conteudo .= "<a href='pagina.php?curso_id=$usuario_curso_id'><li class='list-group-item list-group-item-action mt-1 border-top border-bottom-0 text-center'>$usuario_curso_titulo</li></a>";
							}
							$template_conteudo .= "</ul>";
						} else {
							$template_conteudo .= "<p><strong>{$pagina_translated['Você ainda não aderiu a nenhum curso.']}</strong></p>";
						}
						$template_conteudo .= "<h2 class='mt-3'>{$pagina_translated['available courses']}</h2>";
						$template_conteudo .= "
							<form method='post'>
                                <select class='$select_classes' name='aderir_novo_curso' id='aderir_novo_curso' required>
                                      <option value='' disabled selected>{$pagina_translated['Selecione um curso']}</option>
                        ";
						$cursos_disponiveis = $conn->query("SELECT id, titulo FROM Cursos WHERE estado = 1");
						while ($curso_disponivel = $cursos_disponiveis->fetch_assoc()) {
							$curso_disponivel_id = $curso_disponivel['id'];
							$curso_disponivel_titulo = $curso_disponivel['titulo'];
							$template_conteudo .= "<option value='$curso_disponivel_id'>$curso_disponivel_titulo</option>";
						}
						$cursos_do_usuario = $conn->query("SELECT id, titulo FROM Cursos WHERE estado = 0 AND user_id = $user_id");
						if ($cursos_do_usuario->num_rows > 0) {
							$template_conteudo .= "<option disabled>{$pagina_translated['Seus cursos']}:</option>";
							while ($curso_do_usuario = $cursos_do_usuario->fetch_assoc()) {
								$curso_do_usuario_id = $curso_do_usuario['id'];
								$curso_do_usuario_titulo = $curso_do_usuario['titulo'];
								$template_conteudo .= "<option value='$curso_do_usuario_id'>$curso_do_usuario_titulo</option>";
							}
						}
						$template_conteudo .= "</select>";
						$template_conteudo .= "
							<div class='row justify-content-center'>
								<button name='trigger_adicionar_curso' class='$button_classes'>{$pagina_translated['Aderir']}</button>
							</div>
							</form>
						";
						
						include 'templates/page_element.php';
						
						$template_id = 'topicos_interesse';
						$template_titulo = $pagina_translated['freepages'];
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Incluir tópico';
						$artefato_criacao = 'Pressione para adicionar uma área de interesse';
						$fa_icone = 'fa-plus-circle';
						$fa_color = 'text-info';
						$artefato_tipo = 'novo_topico';
						$artefato_modal = '#modal_gerenciar_etiquetas';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$acervo = $conn->query("SELECT criacao, elemento_id, tipo, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND estado = 1 ORDER BY id DESC");
						while ($item_acervo = $acervo->fetch_assoc()) {
							$topico_acervo_etiqueta_id = $item_acervo['extra'];
							$topico_acervo_etiqueta_tipo = $item_acervo['tipo'];
							if ($topico_acervo_etiqueta_tipo != 'topico') {
								continue;
							}
							$topico_acervo_etiqueta_info = return_etiqueta_info($topico_acervo_etiqueta_id);
							$artefato_criacao = $topico_acervo_etiqueta_info[0];
							$topico_acervo_pagina_id = $topico_acervo_etiqueta_info[4];
							$topico_acervo_pagina_info = return_pagina_info($topico_acervo_pagina_id);
							$topico_acervo_pagina_estado = $topico_acervo_pagina_info[3];
							$topico_acervo_pagina_estado_icone = return_estado_icone($topico_acervo_pagina_estado, 'curso');
							$fa_icone = 'fa-tag';
							if ($topico_acervo_pagina_estado == 0) {
								$fa_color = 'text-light';
								unset($artefato_badge);
							} else {
								$fa_color = 'text-warning';
								$artefato_badge = $topico_acervo_pagina_estado_icone;
							}
							$topico_acervo_titulo = $topico_acervo_etiqueta_info[2];
							$artefato_titulo = "$topico_acervo_titulo";
							$artefato_link = "pagina.php?etiqueta_id=$topico_acervo_etiqueta_id";
							$artefato_tipo = 'topico_interesse';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$respostas = $conn->query("SELECT DISTINCT simulado_id, questao_tipo FROM sim_respostas WHERE user_id = $user_id ORDER BY id DESC");
						if ($respostas->num_rows > 0) {
							$template_id = 'respostas_usuario';
							$template_titulo = $pagina_translated['Simulados feitos'];
							$template_classes = 'esconder_sessao';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							while ($resposta = $respostas->fetch_assoc()) {
								$artefato_id = $resposta['simulado_id'];
								$artefato_questao_tipo = $resposta['questao_tipo'];
								$simulado_info = return_simulado_info($artefato_id);
								$simulado_criacao = $simulado_info[0];
								$simulado_tipo = $simulado_info[1];
								$simulado_tipo_string = converter_simulado_tipo($simulado_tipo);
								$simulado_curso_id = $simulado_info[2];
								$simulado_curso_sigla = return_curso_sigla($simulado_curso_id);
								$artefato_criacao = $simulado_criacao;
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = "$simulado_curso_sigla: $simulado_tipo_string";
								$artefato_link = "resultados.php?simulado_id=$artefato_id";
								$artefato_tipo = 'simulado';
								if ($artefato_questao_tipo == 3) {
									$artefato_icone = 'fa-file-edit fa-swap-opacity';
								}
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						/*
						$artefato_id = 0;
                        $artefato_page_id = false;
                        $artefato_titulo = 'Novo curso';
                        $artefato_criacao = 'Pressione para criar um novo curso';
                        $artefato_tipo = 'novo_curso';
                        $artefato_link = false;
                        $template_conteudo .= include 'templates/artefato_item.php';
						*/
						
						$template_id = 'acervo_virtual';
						$template_titulo = $pagina_translated['your collection'];
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_titulo = 'Adicionar item';
						$artefato_criacao = $pagina_translated['press add collection'];
						$artefato_tipo = 'nova_referencia';
						$fa_icone = 'fa-plus-circle';
						$fa_color = 'text-info';
						$artefato_modal = '#modal_add_elementos';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						mysqli_data_seek($acervo, 0);
						while ($acervo_item = $acervo->fetch_assoc()) {
							$acervo_item_criacao = $acervo_item['criacao'];
							$acervo_item_elemento_id = $acervo_item['elemento_id'];
							$acervo_item_info = return_elemento_info($acervo_item_elemento_id);
							$acervo_item_subtipo = $acervo_item_info[18];
							$acervo_item_tipo = $acervo_item_info[3];
							$acervo_info = return_icone_subtipo($acervo_item_tipo, $acervo_item_subtipo);
							$fa_icone = $acervo_info[0];
							$fa_color = $acervo_info[1];
							$acervo_item_etiqueta_tipo = $acervo_item['tipo'];
							$acervo_item_etiqueta_id = $acervo_item['extra'];
							if ($acervo_item_etiqueta_tipo == 'topico') {
								continue;
							}
							$acervo_item_elemento_info = return_elemento_info($acervo_item_elemento_id);
							if ($acervo_item_elemento_info == false) {
								continue;
							}
							$acervo_item_elemento_titulo = $acervo_item_elemento_info[4];
							$acervo_item_elemento_autor = $acervo_item_elemento_info[5];
							$artefato_id = $acervo_item_etiqueta_id;
							$artefato_page_id = $acervo_item_elemento_id;
							$artefato_titulo = $acervo_item_elemento_titulo;
							$artefato_subtitulo = $acervo_item_elemento_autor;
							$artefato_criacao = $acervo_item_criacao;
							$artefato_criacao = "Adicionado em $artefato_criacao";
							$artefato_tipo = $acervo_item_etiqueta_tipo;
							$artefato_link = "pagina.php?elemento_id=$acervo_item_elemento_id";
							
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$template_id = 'paginas_usuario';
						$template_titulo = $pagina_translated['your_pages'];
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = $pagina_translated['new_private_page'];
						$artefato_criacao = $pagina_translated['Pressione para criar uma página privada'];
						$fa_icone = 'fa-plus-circle';
						$fa_color = 'text-info';
						$artefato_tipo = 'nova_pagina';
						$artefato_link = 'pagina.php?pagina_id=new';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$paginas_usuario = $conn->query("SELECT id FROM Paginas WHERE tipo = 'pagina' AND user_id = $user_id");
						if ($paginas_usuario->num_rows > 0) {
							while ($pagina_usuario = $paginas_usuario->fetch_assoc()) {
								$pagina_usuario_id = $pagina_usuario['id'];
								$pagina_usuario_info = return_pagina_info($pagina_usuario_id);
								$pagina_usuario_criacao = $pagina_usuario_info[0];
								$pagina_usuario_compartilhamento = $pagina_usuario_info[4];
								$pagina_usuario_titulo = $pagina_usuario_info[6];
								
								$artefato_id = $pagina_usuario_id;
								$artefato_titulo = $pagina_usuario_titulo;
								if ($pagina_usuario_compartilhamento == 'privado') {
									$artefato_subtitulo = $pagina_translated['private page'];
								} elseif ($pagina_usuario_compartilhamento == 'publico') {
									$artefato_subtitulo = $pagina_translated['public page'];
								} else {
									$artefato_subtitulo = $pagina_translated['Página'];
								}
								$artefato_tipo = 'pagina_usuario';
								$fa_icone = 'fa-columns';
								$fa_color = 'text-info';
								$artefato_link = "pagina.php?pagina_id=$pagina_usuario_id";
								$artefato_criacao = $pagina_usuario_criacao;
								
								$template_conteudo .= include 'templates/artefato_item.php';
							}
						}
						
						include 'templates/page_element.php';
						
						$anotacoes = $conn->query("SELECT id, page_id, pagina_id, pagina_tipo, pagina_subtipo, titulo, criacao, tipo, verbete_content FROM Textos WHERE tipo LIKE '%anotac%' AND user_id = $user_id ORDER BY id DESC");
						$template_id = 'anotacoes_privadas';
						$template_titulo = $pagina_translated['texts and study notes'];
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = $pagina_translated['Nova anotação privada'];
						$artefato_criacao = $pagina_translated['Pressione para criar uma anotação privada'];
						$artefato_tipo = 'nova_anotacao';
						$fa_icone = 'fa-plus-circle';
						$fa_color = 'text-info';
						$artefato_link = 'pagina.php?texto_id=new';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						while ($anotacao = $anotacoes->fetch_assoc()) {
							$anotacao_content = $anotacao['verbete_content'];
							if ($anotacao_content == false) {
								continue;
							}
							$anotacao_id = $anotacao['id'];
							$anotacao_page_id = $anotacao['page_id'];
							$anotacao_pagina_id = $anotacao['pagina_id'];
							if ($anotacao_pagina_id == false) {
								$anotacao_pagina_id = return_pagina_id($anotacao_id, 'texto');
							}
							$anotacao_pagina_tipo = $anotacao['pagina_tipo'];
							$anotacao_titulo = $anotacao['titulo'];
							$anotacao_criacao = $anotacao['criacao'];
							$anotacao_tipo = $anotacao['tipo'];
							$anotacao_subtipo = $anotacao['pagina_subtipo'];
							$anotacao_info = return_pagina_icone_cor($anotacao_pagina_tipo, $anotacao_subtipo);
							$fa_icone = 'fa-file-alt fa-swap-opacity';
	                        $fa_color = $anotacao_info[1];
							
							$artefato_tipo = $anotacao_tipo;
							if ($anotacao_pagina_tipo != false) {
								$artefato_tipo = "{$anotacao_tipo}_{$anotacao_pagina_tipo}";
							}
							if ($anotacao_titulo == false) {
								$artefato_titulo = false;
							} else {
								$artefato_titulo = $anotacao_titulo;
							}
							if ($anotacao_pagina_tipo == 'topico') {
								$artefato_titulo = return_pagina_titulo($anotacao_pagina_id);
								$artefato_familia = return_familia($anotacao_pagina_id);
								$artefato_curso_pagina_id = $artefato_familia[1];
								$artefato_materia_pagina_id = $artefato_familia[2];
								$artefato_materia_titulo = return_pagina_titulo($artefato_materia_pagina_id);
								$artefato_curso_id = return_pagina_item_id($artefato_curso_pagina_id);
								$artefato_curso_sigla = return_curso_sigla($artefato_curso_id);
								$artefato_subtitulo = "$artefato_materia_titulo / $artefato_curso_sigla";
							} elseif ($anotacao_pagina_tipo == 'pagina') {
								$artefato_titulo = return_pagina_titulo($anotacao_pagina_id);
								$artefato_subtitulo = 'Nota / página';
							} elseif ($anotacao_pagina_tipo == 'elemento') {
								$artefato_titulo = return_titulo_elemento($anotacao_page_id);
								$artefato_subtitulo = 'Nota / elemento';
							} elseif ($anotacao_pagina_tipo == 'curso') {
								$artefato_titulo = return_curso_titulo_id($anotacao_page_id);
								$artefato_subtitulo = 'Nota / curso';
							} elseif ($anotacao_pagina_tipo == 'materia') {
								$artefato_titulo = return_pagina_titulo($anotacao_pagina_id);
								$artefato_info = return_familia($anotacao_pagina_id);
								$artefato_curso_pagina_id = $artefato_info[1];
								$artefato_curso_id = return_pagina_item_id($artefato_curso_pagina_id);
								$artefato_curso_sigla = return_curso_sigla($artefato_curso_id);
								$artefato_subtitulo = "Matéria / $artefato_curso_sigla";
							} elseif ($anotacao_pagina_tipo == 'grupo') {
								$artefato_titulo = return_grupo_titulo_id($anotacao_page_id);
								$artefato_subtitulo = 'Nota / Grupo de estudos';
							} elseif ($anotacao_pagina_tipo == 'secao') {
								$artefato_titulo = return_pagina_titulo($anotacao_pagina_id);
								$artefato_parent_titulo = return_pagina_titulo($anotacao_page_id);
								$artefato_subtitulo = "Seção de $artefato_parent_titulo";
							}
							if ($anotacao_pagina_tipo == false) {
								$artefato_link = "pagina.php?texto_id=$anotacao_id";
								$fa_color = 'text-primary';
								$artefato_subtitulo = 'Anotação privada';
							} else {
								$artefato_link = "pagina.php?pagina_id=$anotacao_pagina_id";
							}
							if (!isset($artefato_subtitulo)) {
								$artefato_subtitulo = false;
							}
							$artefato_criacao = false;
							if ($artefato_titulo == false) {
								$artefato_titulo = return_artefato_subtitulo($artefato_tipo);
							}
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$imagens_privadas = $conn->query("SELECT subtipo, id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' AND user_id = $user_id AND compartilhamento = 'privado' ORDER BY id DESC");
						$template_id = 'imagens_privadas';
						$template_titulo = $pagina_translated['private images'];
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = $pagina_translated['Nova imagem privada'];
						$artefato_criacao = $pagina_translated['Pressione para adicionar uma imagem privada'];
						$artefato_tipo = 'adicionar_imagem_privada';
						$fa_icone = 'fa-plus-circle';
						$fa_color = 'text-info';
						$artefato_modal = '#modal_adicionar_imagem';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						while ($imagem_privada = $imagens_privadas->fetch_assoc()) {
							$artefato_id = $imagem_privada['id'];
							$artefato_criacao = $imagem_privada['criacao'];
							$imagem_subtipo = $imagem_privada['subtipo'];
							$imagem_info = return_icone_subtipo('imagem', $imagem_subtipo);
							$fa_icone = $imagem_info[0];
							$fa_color = $imagem_info[1];
							$artefato_criacao = "Criado em $artefato_criacao";
							$artefato_titulo = $imagem_privada['titulo'];
							$artefato_imagem_arquivo = $imagem_privada['arquivo'];
							$artefato_estado = $imagem_privada['estado'];
							$artefato_link = "pagina.php?elemento_id=$artefato_id";
							$artefato_tipo = 'imagem_publica';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$imagens_publicas = $conn->query("SELECT subtipo, id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' AND compartilhamento IS NULL ORDER BY id DESC");
						if ($imagens_publicas->num_rows > 0) {
							$template_id = 'imagens_publicas';
							$template_titulo = $pagina_translated['public images'];
							$template_classes = 'esconder_sessao';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($imagem_publica = $imagens_publicas->fetch_assoc()) {
								$artefato_id = $imagem_publica['id'];
								$imagem_publica_subtipo = $imagem_publica['subtipo'];
								$imagem_publica_info = return_icone_subtipo('imagem', $imagem_publica_subtipo);
								$fa_icone = $imagem_publica_info[0];
								$fa_color = $imagem_publica_info[1];
								$artefato_criacao = $imagem_publica['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = $imagem_publica['titulo'];
								$artefato_imagem_arquivo = $imagem_publica['arquivo'];
								$artefato_estado = $imagem_publica['estado'];
								$artefato_link = "pagina.php?elemento_id=$artefato_id";
								$artefato_tipo = 'imagem_publica';
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
					
					?>
        </div>
    </div>
</div>

<?php
	
	$template_modal_div_id = 'modal_verbetes';
	$template_modal_titulo = $pagina_translated['Verbetes em que contribuiu'];
	$template_modal_body_conteudo = false;
	if ($verbetes_escritos->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($verbete_escrito = $verbetes_escritos->fetch_assoc()) {
			$escrito_pagina_id = $verbete_escrito['pagina_id'];
			$escrito_pagina_info = return_pagina_info($escrito_pagina_id);
			$escrito_pagina_titulo = $escrito_pagina_info[6];
			if ($escrito_pagina_titulo == false) {
				$escrito_pagina_titulo = $pagina_translated['Não há título registrado'];
			}
			$escrito_pagina_tipo = $escrito_pagina_info[2];
			$list_color = return_list_color_page_type($escrito_pagina_tipo);
			if ($escrito_pagina_tipo == 'texto') {
				continue;
			}
			$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$escrito_pagina_id' title='$escrito_pagina_tipo' class='mt-1'><li class='list-group-item list-group-item-action $list_color'>$escrito_pagina_titulo</li></a>";
			
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_completados';
	$template_modal_titulo = $pagina_translated['Assuntos estudados'];
	$template_modal_body_conteudo = false;
	if ($completados->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($row = $completados->fetch_assoc()) {
			$completed_pagina_id = $row['pagina_id'];
			$completed_pagina_info = return_pagina_info($completed_pagina_id);
			$completed_pagina_titulo = $completed_pagina_info[6];
			$completed_pagina_tipo = $completed_pagina_info[2];
			$list_color = return_list_color_page_type($completed_pagina_tipo);
			$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$completed_pagina_id' title='$completed_pagina_tipo' class='mt-1'><li class='list-group-item list-group-item-action $list_color'>$completed_pagina_titulo</li></a>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = $pagina_translated['Suas participações no fórum'];
	$template_modal_body_conteudo = false;
	if ($comentarios->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($row = $comentarios->fetch_assoc()) {
			$forum_pagina_id = $row['pagina_id'];
			$forum_pagina_tipo = $row['pagina_tipo'];
			$forum_pagina_info = return_pagina_info($forum_pagina_id);
			$forum_pagina_titulo = $forum_pagina_info[6];
			$forum_pagina_tipo = $forum_pagina_info[2];
			$list_color = return_list_color_page_type($forum_pagina_tipo);
			$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$forum_pagina_id' title='$forum_pagina_tipo' class='mt-1'><li class='list-group-item list-group-item-action $list_color'>$forum_pagina_titulo</li></a>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	if ($bookmarks->num_rows > 0) {
		$template_modal_div_id = 'modal_bookmarks';
		$template_modal_titulo = $pagina_translated['bookmarks'];
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		while ($bookmark = $bookmarks->fetch_assoc()) {
			$bookmark_pagina_id = $bookmark['pagina_id'];
			$bookmark_info = return_pagina_info($bookmark_pagina_id);
			$bookmark_titulo = $bookmark_info[6];
			$bookmark_tipo = $bookmark_info[2];
			$list_color = return_list_color_page_type($bookmark_tipo);
			$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$bookmark_pagina_id' title='$bookmark_tipo' class='mt-1'><li class='list-group-item list-group-item-action $list_color'>$bookmark_titulo</li></a>";
		}
		$template_modal_body_conteudo .= "</ul>";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}
	
	$template_modal_div_id = 'modal_apresentacao';
	$template_modal_titulo = $pagina_translated['your office lounge'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<p>{$pagina_translated['lounge room explanation 1']}</p>";
	$template_modal_body_conteudo .= "<p>{$pagina_translated['lounge room explanation 2']}</p>";
	
	$perfil_publico_id = false;
	$perfis_publicos = $conn->query("SELECT id FROM Textos WHERE tipo = 'verbete_user' AND user_id = $user_id");
	if ($perfis_publicos->num_rows > 0) {
		while ($perfil_publico = $perfis_publicos->fetch_assoc()) {
			$perfil_publico_id = $perfil_publico['id'];
		}
	} else {
		if ($conn->query("INSERT INTO Textos (tipo, user_id, titulo, verbete_html, verbete_text, verbete_content) VALUES ('verbete_user', $user_id, 'Perfil público', 0, 0, 0)") === true) {
			$perfil_publico_id = $conn->insert_id;
		}
	}
	if ($perfil_publico_id != false) {
		$template_modal_body_conteudo .= "
		  <div class='row justify-content-center'>
			  <a href='pagina.php?user_id=$user_id'><button type='button' class='$button_classes'>{$pagina_translated['edit lounge']}</button></a>
		  </div>
	  ";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	if (isset($_POST['selecionar_avatar'])) {
		$novo_avatar = $_POST['selecionar_avatar'];
		$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar', '$novo_avatar')");
	}
	
	if (isset($_POST['selecionar_cor'])) {
		$nova_cor = $_POST['selecionar_cor'];
		$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar_cor', '$nova_cor')");
	}
	
	$usuario_avatar_info = return_avatar($user_id);
	$usuario_avatar = $usuario_avatar_info[0];
	$usuario_avatar_cor = $usuario_avatar_info[1];
	
	$template_modal_div_id = 'modal_opcoes';
	$template_modal_titulo = $pagina_translated['user settings'];
	if ($opcao_texto_justificado_value == true) {
		$texto_justificado_checked = 'checked';
	} else {
		$texto_justificado_checked = false;
	}
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<h3>Avatar</h3>
		<div class='row justify-content-center'>
			<a href='pagina.php?user_id=$user_id' class='$usuario_avatar_cor'><i class='fad $usuario_avatar fa-3x fa-fw'></i></a>
		</div>
		<p>Alterar:</p>
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
        <div class='md-form md-2'><input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required>
            <label data-error='inválido' data-successd='válido' for='novo_apelido' required>{$pagina_translated['Apelido']}</label>
        </div>
        <p>{$pagina_translated['Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.']}</p>
        <div class='md-form md-2'>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>

            <label data-error='inválido' data-successd='válido'
                   for='novo_nome'>{$pagina_translated['Nome']}</label>
        </div>
        <div class='md-form md-2'>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required>

            <label data-error='inválido' data-successd='válido' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' required>{$pagina_translated['Sobrenome']}</label>
        </div>
        <h3>Opções</h3>
        <div class='md-form md-2'>
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
	
	$template_modal_div_id = 'modal_notificacoes';
	$template_modal_titulo = $pagina_translated['notifications'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	if ($notificacoes->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		if ($user_tipo == 'admin') {
			//$template_modal_body_conteudo .= "<a href='notificacoes.php' class='mt-1'><li class='list-group-item list-group-item-action list-group-item-info d-flex justify-content-center'>Página de notificações</li></a>";
		}
		while ($notificacao = $notificacoes->fetch_assoc()) {
			$notificacao_pagina_id = $notificacao['pagina_id'];
			$notificacao_pagina_titulo = return_pagina_titulo($notificacao_pagina_id);
			$alteracao_recente = return_alteracao_recente($notificacao_pagina_id);
			$alteracao_recente_data = $alteracao_recente[0];
			$alteracao_recente_data = format_data($alteracao_recente_data);
			$alteracao_recente_usuario = $alteracao_recente[1];
			$alteracao_recente_usuario_apelido = return_apelido_user_id($alteracao_recente_usuario);
			$alteracao_recente_tipo = $alteracao_recente[2];
			if ($alteracao_recente_tipo == 'verbete') {
				$alteracao_recente_tipo_icone = 'fa-edit';
			} elseif ($alteracao_recente_tipo == 'forum') {
				$alteracao_recente_tipo_icone = 'fa-comments-alt';
			}
			$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$notificacao_pagina_id' class='mt-1'><li class='list-group-item list-group-item-action border-top d-flex justify-content-between'><span><i class='fad $alteracao_recente_tipo_icone fa-fw'></i> $notificacao_pagina_titulo</span><span class='text-muted'><em>($alteracao_recente_usuario_apelido) $alteracao_recente_data</em></span></li></a>";
			$segunda_alteracao_recente_data = $alteracao_recente[3];
			if ($segunda_alteracao_recente_data != false) {
				$segunda_alteracao_recente_data = format_data($segunda_alteracao_recente_data);
				$segunda_alteracao_recente_tipo = $alteracao_recente[5];
				if ($segunda_alteracao_recente_tipo == 'verbete') {
					$segunda_alteracao_recente_tipo_icone = 'fa-edit';
				} elseif ($segunda_alteracao_recente_tipo == 'forum') {
					$segunda_alteracao_recente_tipo_icone = 'fa-comments-alt';
				}
				$segunda_alteracao_recente_usuario = $alteracao_recente[4];
				$segunda_alteracao_recente_usuario_apelido = return_apelido_user_id($segunda_alteracao_recente_usuario);
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$notificacao_pagina_id' class='mt-1'><li class='list-group-item list-group-item-action border-top d-flex justify-content-between'><span class='ml-3'><i class='fad $segunda_alteracao_recente_tipo_icone fa-fw'></i> $notificacao_pagina_titulo</span><span class='text-muted'><em>($segunda_alteracao_recente_usuario_apelido) $segunda_alteracao_recente_data</em></span></li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	include 'templates/modal.php';
	
	include 'pagina/modal_adicionar_imagem.php';
	
	include 'pagina/modal_add_elemento.php';
	
	$template_modal_div_id = 'modal_gerenciar_etiquetas';
	$template_modal_titulo = $pagina_translated['Incluir área de interesse'];
	include 'templates/etiquetas_modal.php';
	
	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
	include 'pagina/modal_languages.php';

?>

</body>
<?php
	
	include 'templates/footer.html';
	$sistema_etiquetas_elementos = true;
	$sistema_etiquetas_topicos = true;
	$mdb_select = true;
	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>
