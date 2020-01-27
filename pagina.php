<?php
	
	include 'engine.php';
	$modal_novo_curso = false;
	$nao_contar = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_topico = false;
	$carregar_adicionar_subtopico = false;
	$carregar_questoes_topico = false;
	$carregar_convite = false;
	if (!isset($_GET['pagina_id'])) {
		if (isset($_GET['topico_id'])) {
			$topico_id = (int)$_GET['topico_id'];
			$pagina_id = return_pagina_id($topico_id, 'topico');
		} elseif (isset($_GET['elemento_id'])) {
			$elemento_id = (int)$_GET['elemento_id'];
			$pagina_id = return_pagina_id($elemento_id, 'elemento');
		} elseif (isset($_GET['curso_id'])) {
			$curso_id = (int)$_GET['curso_id'];
			$pagina_id = return_pagina_id($curso_id, 'curso');
		} elseif (isset($_GET['materia_id'])) {
			$materia_id = (int)$_GET['materia_id'];
			$pagina_id = return_pagina_id($materia_id, 'materia');
		} elseif (isset($_GET['texto_id'])) {
			$texto_anotacao = false;
			$pagina_texto_id = (int)$_GET['texto_id'];
			if ($pagina_texto_id == 'new') {
				$conn->query("INSERT INTO Textos (tipo, compartilhamento, page_id, user_id, verbete_html, verbete_text, verbete_content) VALUES ('anotacoes', 'privado', 0, $user_id, FALSE, FALSE, FALSE)");
				$pagina_texto_id = $conn->insert_id;
				header("Location:pagina.php?texto_id=$pagina_texto_id");
				exit();
			}
			$pagina_id = return_pagina_id($pagina_texto_id, 'texto');
			$pagina_tipo = 'texto';
		} elseif (isset($_GET['grupo_id'])) {
			$grupo_id = (int)$_GET['grupo_id'];
			$pagina_id = return_pagina_id($grupo_id, 'grupo');
		} elseif (isset($_GET['user_id'])) {
			$escritorio_user_id = (int)$_GET['user_id'];
			$escritorio_user_apelido = return_apelido_user_id($escritorio_user_id);
			if ($escritorio_user_apelido == false) {
				header("Location:pagina.php?pagina_id=6");
				exit();
			}
			$escritorio_id = return_escritorio_id($escritorio_user_id);
			$pagina_id = $escritorio_id;
			header("Location:pagina.php?pagina_id=$escritorio_id");
			exit();
		} elseif (isset($_GET['original_id'])) {
			$original_id = (int)$_GET['original_id'];
			if (isset($_GET['resposta_id'])) {
				if (isset($_GET['resposta_id'])) {
					$resposta_id = (int)$_GET['resposta_id'];
					if ($resposta_id == 'new') {
						$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($original_id, 'resposta', 'igual à página original', $user_id)");
						$nova_resposta_id = $conn->insert_id;
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($original_id, 'texto', $nova_resposta_id, 'resposta', $user_id)");
						header("Location:pagina.php?pagina_id=$nova_resposta_id");
						exit();
					}
				}
			}
		} elseif (isset($_GET['etiqueta_id'])) {
			$pagina_etiqueta_id = $_GET['etiqueta_id'];
			$pagina_id = return_pagina_id($pagina_etiqueta_id, 'etiqueta');
		} else {
			header('Location:pagina.php?pagina_id=4');
			exit();
		}
	} else {
		$pagina_id = $_GET['pagina_id'];
		if ($pagina_id == 'new') {
			$conn->query("INSERT INTO Paginas (tipo, compartilhamento, user_id) VALUES ('pagina', 'privado', $user_id)");
			$pagina_id = $conn->insert_id;
			header("Location:pagina.php?pagina_id=$pagina_id");
			exit();
		}
	}
	
	$pagina_info = return_pagina_info($pagina_id);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = (int)$pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = (int)$pagina_info[5];
		$pagina_titulo = $pagina_info[6];
		$pagina_etiqueta_id = (int)$pagina_info[7];
		$pagina_subtipo = $pagina_info[8];
	} else {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}
	
	if ($pagina_tipo == 'topico') {
		$familia_info = return_familia($pagina_id);
		$topico_nivel = $familia_info[0];
		$topico_curso_pagina_id = (int)$familia_info[1];
		$topico_curso_pagina_info = return_pagina_info($topico_curso_pagina_id);
		$topico_curso_titulo = $topico_curso_pagina_info[6];
		$pagina_curso_id = $topico_curso_pagina_info[1];
		$pagina_curso_pagina_id = (int)$topico_curso_pagina_id;
		$pagina_curso_user_id = (int)$topico_curso_pagina_info[5];
		$pagina_curso_compartilhamento = $topico_curso_pagina_info[4];
		$topico_materia_pagina_id = (int)$familia_info[2];
		$topico_materia_titulo = return_pagina_titulo($topico_materia_pagina_id);
	} elseif (($pagina_tipo == 'materia') || ($pagina_tipo == 'curso')) {
		$familia_info = return_familia($pagina_id);
		$pagina_curso_pagina_id = (int)$familia_info[1];
		$pagina_curso_info = return_pagina_info($pagina_curso_pagina_id);
		$pagina_curso_id = (int)$pagina_curso_info[1];
		$pagina_curso_user_id = (int)$pagina_curso_info[5];
		$pagina_curso_compartilhamento = $pagina_curso_info[4];
	} elseif ($pagina_subtipo == 'produto') {
		$produto_preco = false;
		$produto_autor = false;
		$produto_info = return_produto_info($pagina_id);
		$produto_preco = $produto_info[2];
		$produto_autor = $produto_info[3];
	} elseif ($pagina_subtipo == 'etiqueta') {
		$pagina_etiqueta_id = $pagina_item_id;
	}
	
	if (isset($_POST['novo_curso'])) {
		$novo_curso_sigla = $_POST['novo_curso_sigla'];
		$conn->query("INSERT INTO Cursos (pagina_id, titulo, sigla, user_id) VALUES ($pagina_id, '$pagina_titulo', '$novo_curso_sigla', $user_id)");
		$novo_curso_id = $conn->insert_id;
		$conn->query("UPDATE Paginas SET item_id = $novo_curso_id, tipo = 'curso' WHERE id = $pagina_id");
		header("Location:pagina.php?curso_id=$novo_curso_id");
		exit();
	}
	
	if (($pagina_tipo == 'topico') || $pagina_tipo == 'materia') {
		$pagina_compartilhamento = $pagina_curso_compartilhamento;
		$pagina_user_id = $pagina_curso_user_id;
	}
	
	if ($pagina_compartilhamento == 'privado') {
		if ($pagina_user_id != $user_id) {
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia')) {
				$check_compartilhamento = return_compartilhamento($pagina_curso_pagina_id, $user_id);
			} else {
				$check_compartilhamento = return_compartilhamento($pagina_id, $user_id);
			}
			if ($check_compartilhamento == false) {
				header('Location:pagina.php?pagina_id=4');
				exit();
			}
		}
	}
	
	if ($pagina_tipo == 'curso') {
		$curso_info = return_curso_info($curso_id);
		$curso_sigla = $curso_info[2];
		$curso_titulo = $curso_info[3];
		$curso_user_id = $curso_info[4];
	} elseif ($pagina_tipo == 'materia') {
		$materia_id = $pagina_item_id;
		$materia_curso_id = false;
		$materia_curso_sigla = return_curso_sigla($materia_curso_id);
		$materia_titulo = $pagina_titulo;
	} elseif ($pagina_tipo == 'topico') {
		include 'templates/verbetes_relacionados.php';
	} elseif ($pagina_tipo == 'elemento') {
		$elemento_id = $pagina_item_id;
	} elseif ($pagina_tipo == 'grupo') {
		$grupo_id = $pagina_item_id;
		$check_membro = check_membro_grupo($user_id, $grupo_id);
		if ($check_membro == false) {
			header('Location:pagina.php?pagina_id=3');
			exit();
		}
	} elseif ($pagina_tipo == 'texto') {
		$pagina_texto_id = $pagina_item_id;
		$texto_info = return_texto_info($pagina_texto_id);
		$texto_curso_id = $texto_info[0];
		$texto_tipo = $texto_info[1];
		$texto_titulo = $texto_info[2];
		$texto_page_id = $texto_info[3];
		$texto_criacao = $texto_info[4];
		$texto_verbete_html = $texto_info[5];
		$texto_user_id = $texto_info[8];
		$texto_pagina_id = $texto_info[9];
		$texto_compartilhamento = $texto_info[11];
		$texto_texto_pagina_id = $texto_info[12];
		$pagina_id = $texto_texto_pagina_id;
		if (isset($_POST['destruir_anotacao'])) {
			$conn->query("DELETE FROM Textos WHERE id = $texto_id");
			header('Location:pagina.php?pagina_id=5');
			exit();
		}
		if ($texto_page_id === 0) {
			$texto_editar_titulo = true;
		}
	} elseif ($pagina_tipo == 'secao') {
		$pagina_original_info = return_pagina_info($pagina_item_id);
		$pagina_original_compartilhamento = $pagina_original_info[4];
		$pagina_compartilhamento = $pagina_original_compartilhamento;
		$pagina_original_user_id = $pagina_original_info[5];
		if (($pagina_original_user_id != $user_id) && ($pagina_original_compartilhamento == 'privado')) {
			$check_compartilhamento = return_compartilhamento($pagina_item_id, $user_id);
			if ($check_compartilhamento == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
		}
	} elseif ($pagina_tipo == 'resposta') {
		$resposta_id = $pagina_id;
		$resposta_info = return_pagina_info($pagina_id);
		$original_id = $resposta_info[1];
		$original_info = return_pagina_info($original_id);
		$original_user_id = $original_info[5];
		if ($original_user_id != $user_id) {
			$check_compartilhamento = return_compartilhamento($original_id, $user_id);
			if ($check_compartilhamento == false) {
				header("Location:pagina.php?pagina_id=3");
				exit();
			}
		}
		$original_titulo = $original_info[6];
		$original_texto_id = return_texto_id('texto', 'anotacoes', $original_id, $user_id);
		$original_texto_info = return_texto_info($original_texto_id);
		$original_texto_html = $original_texto_info[5];
	}
	
	if ($pagina_tipo == 'elemento') {
		include 'pagina/isset_elemento.php';
		include 'pagina/queries_elemento.php';
	}
	
	include 'pagina/shared_issets.php';
	
	if (($pagina_tipo == 'elemento') || ($pagina_tipo == 'pagina') || ($pagina_tipo == 'grupo')) {
		if (isset($_POST['trigger_nova_secao'])) {
			$nova_secao_titulo = $_POST['elemento_nova_secao'];
			$nova_secao_titulo = mysqli_real_escape_string($conn, $nova_secao_titulo);
			$nova_secao_ordem = (int)$_POST['elemento_nova_secao_ordem'];
			$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($pagina_id, 'secao', 'igual à página original', $user_id)");
			$nova_pagina_id = $conn->insert_id;
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_pagina_id, 'secao', 'titulo', '$nova_secao_titulo', $user_id)");
			$conn->query("INSERT INTO Secoes (ordem, user_id, pagina_id, secao_pagina_id) VALUES ($nova_secao_ordem, $user_id, $pagina_id, $nova_pagina_id)");
			if ($pagina_tipo == 'elemento') {
				$nova_etiqueta_titulo = "$elemento_titulo // $nova_secao_titulo";
				$nova_etiqueta_titulo = mysqli_real_escape_string($conn, $nova_etiqueta_titulo);
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$nova_etiqueta_titulo', $user_id)");
				$nova_etiqueta_id = $conn->insert_id;
				$conn->query("UPDATE Paginas SET etiqueta_id = $nova_etiqueta_id WHERE id = $nova_pagina_id");
			}
			$nao_contar = true;
		}
	}
	
	if ($pagina_tipo != 'sistema') {
		$pagina_bookmark = false;
		$bookmarks = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND pagina_id = $pagina_id AND active = 1 ORDER BY id DESC");
		if ($bookmarks->num_rows > 0) {
			while ($bookmark = $bookmarks->fetch_assoc()) {
				$pagina_bookmark = $bookmark['bookmark'];
				break;
			}
		}
		
		$estado_estudo = false;
		$estudos = $conn->query("SELECT estado FROM Completed WHERE user_id = $user_id AND pagina_id = $pagina_id AND active = 1 ORDER BY id DESC");
		if ($estudos->num_rows > 0) {
			while ($estado = $estudos->fetch_assoc()) {
				$estado_estudo = $estado['estado'];
				break;
			}
		}
	} else {
		$pagina_bookmark = false;
		$estado_estudo = false;
	}
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	if ($user_id != false) {
		if ($nao_contar == false) {
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia')) {
				$visualizacao_extra = $curso_id;
			} elseif ($pagina_tipo == 'elemento') {
				$visualizacao_extra = $elemento_tipo;
			} elseif ($pagina_tipo == 'texto') {
				$visualizacao_extra = $pagina_texto_id;
			} else {
				$visualizacao_extra = "NULL";
			}
			$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra, extra2) VALUES ($user_id, $pagina_id, '$pagina_tipo', '$visualizacao_extra', 'pagina')");
		}
	}
	
	if (isset($_POST['compartilhar_grupo_id'])) {
		$compartilhar_grupo_id = $_POST['compartilhar_grupo_id'];
		$conn->query("INSERT INTO Compartilhamento (user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'grupo', $compartilhar_grupo_id)");
	}
	
	if (isset($_POST['produto_nova_imagem'])) {
		$produto_nova_imagem_elemento_id = $_POST['produto_nova_imagem'];
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($pagina_id, 'produto', $produto_nova_imagem_elemento_id, 'imagem', $user_id)");
	}
	
	if (isset($_POST['novo_produto_preco'])) {
		$novo_produto_preco = $_POST['novo_produto_preco'];
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($pagina_id, 'produto', 'preco', $novo_produto_preco, $user_id)");
		$produto_preco = $novo_produto_preco;
	}
	
	if ($pagina_subtipo == 'produto') {
		$carrinho = $conn->query("SELECT id FROM Carrinho WHERE user_id = $user_id AND produto_pagina_id = $pagina_id AND estado = 1");
		if ($carrinho->num_rows > 0) {
			$produto_no_carrinho = true;
		} else {
			$produto_no_carrinho = false;
		}
	}
	
	if (isset($_POST['adicionar_produto_pagina_id'])) {
		$adicionar_produto_pagina_id = $_POST['adicionar_produto_pagina_id'];
		$conn->query("INSERT INTO Carrinho (user_id, produto_pagina_id, estado) VALUES ($user_id, $pagina_id, 1)");
		$produto_no_carrinho = true;
	}
	
	if (($pagina_tipo == 'elemento') || ($pagina_tipo == 'grupo') || (($pagina_tipo == 'pagina') && (($pagina_compartilhamento != 'escritorio') && ($pagina_subtipo != 'produto')))) {
		$carregar_secoes = true;
	} else {
		$carregar_secoes = false;
	}
	
	if ($carregar_secoes == true) {
		$secoes = $conn->query("SELECT secao_pagina_id FROM Secoes WHERE pagina_id = $pagina_id ORDER BY ordem");
	}
	$etiquetados = $conn->query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1 AND extra IS NOT NULL");
	if ($pagina_tipo == 'texto') {
		$respostas = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'resposta'");
	}

?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row justify-content-between">
        <div class='py-2 text-left col-md-4 col-sm-12'>
					<?php
						if (($pagina_tipo != 'sistema') && ($pagina_tipo != 'texto') && ($pagina_compartilhamento != 'escritorio') && ($pagina_tipo != 'materia')) {
							echo "<span id='add_elements' class='mx-1' title='Adicionar elementos' data-toggle='modal' data-target='#modal_add_elementos'><a href='javascript:void(0)' class='text-info'><i class='fad fa-2x fa-plus-circle fa-fw'></i></a></span>";
						}
						if ($pagina_tipo == 'elemento') {
							echo "<span id='elemento_dados' class='mx-1' title='Editar dados'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_dados_elemento' class='text-info'><i class='fad fa-info-circle fa-fw fa-2x'></i></a></span>";
						}
						$modal_pagina_dados = false;
						if ((($pagina_tipo == 'sistema') && ($user_tipo == 'admin')) || (($pagina_tipo == 'pagina') || ($pagina_user_id == $user_id)) || ((($pagina_tipo == 'curso') || ($pagina_tipo == 'materia') || ($pagina_tipo == 'topico')) && ($pagina_curso_user_id == $user_id)) || (($pagina_tipo == 'texto') && ($pagina_user_id = $user_id) && ($texto_page_id == 0)) || (($pagina_tipo == 'resposta') && ($pagina_user_id == $user_id)) || (($pagina_tipo == 'secao') && ($pagina_user_id == $user_id))) {
							$modal_pagina_dados = true;
							echo "<span id='pagina_dados' class='mx-1' title='Editar dados'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_pagina_dados' class='text-success'><i class='fad fa-info-circle fa-fw fa-2x'></i></a></span>";
							$carregar_produto_setup = false;
							if ($pagina_subtipo == 'produto') {
								$carregar_produto_setup = true;
								echo "<span id='produto_imagem' class='mx-1' title='Imagem do produto'><a href='javascript:void(0)' data-toggle='modal' data-target='#modal_produto_nova_imagem' class='text-danger'><i class='fad fa-image-polaroid fa-fw fa-2x'></i></a></span>";
								echo "<span id='produto_preco' class='mx-1' title='Preço do produto'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_produto_preco' class='text-warning'><i class='fad fa-usd-circle fa-fw fa-2x'></i></a></span>";
							}
						}
						if ($pagina_tipo == 'texto') {
							if ($respostas->num_rows > 0) {
								echo "<span id='add_reply' class='mx-1' title='Adicionar resposta'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_reply' class='text-success'><i class='fad fa-comment-alt-edit fa-fw fa-2x'></i></a></span>";
							} else {
								echo "<span id='add_reply' class='mx-1' title='Adicionar resposta'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_reply' class='text-muted'><i class='fad fa-comment-alt-edit fa-fw fa-2x'></i></a></span>";
							}
						}
						if (($pagina_tipo == 'curso') && ($curso_user_id == $user_id)) {
							$carregar_adicionar_materia = true;
							echo "<span id='add_materia' class='mx-1' title='Adicionar matéria'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_materia' class='text-success'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a></span>";
						}
						if (($pagina_tipo == 'materia') && ($pagina_user_id == $user_id)) {
							$carregar_adicionar_topico = true;
							echo "<span id='add_topico' class='mx-1' title='Adicionar tópico'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_topico' class='text-success'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a></span>";
						}
						if (($pagina_tipo == 'topico') && ($pagina_user_id == $user_id) && ($topico_nivel < 5)) {
							$carregar_adicionar_subtopico = true;
							echo "<span id='add_subtopico' class='mx-1' title='Adicionar subtópico'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_subtopico' class='text-success'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a></span>";
						}
					?>
        </div>
        <div class="py-2 text-center col-md-4 col-sm-12">
					<?php
						if ($pagina_tipo == 'curso') {
							echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_busca' class='text-dark' title='Busca'><i class='fad fa-search fa-fw'></i></a>";
						}
						if ($pagina_tipo == 'topico') {
							if ($topico_anterior != false) {
								$topico_anterior_link = "pagina.php?topico_id=$topico_anterior";
								echo "<span id='verbete_anterior' class='mx-1' title='Verbete anterior'><a href='$topico_anterior_link'><i class='fad fa-arrow-left fa-fw'></i></a></span>";
							}
							echo "<span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='modal' data-target='#modal_verbetes_relacionados'><a href='javascript:void(0);' class='text-dark'><i class='fad fa-project-diagram fa-fw'></i></a></span>";
							if ($topico_proximo != false) {
								$topico_proximo_link = "pagina.php?topico_id=$topico_proximo";
								echo "<span id='verbete_proximo' class='mx-1' title='Próximo verbete'><a href='$topico_proximo_link'><i class='fad fa-arrow-right fa-fw'></i></a></span>";
							}
						} elseif ($pagina_tipo == 'secao') {
							echo "<span id='secoes' class='mx-1' title='Página e seções' data-toggle='modal' data-target='#modal_paginas_relacionadas'><a href='javascript:void(0);' class='text-dark'><i class='fad fa-project-diagram fa-fw'></i></a></span>";
						}
						if ($pagina_subtipo == 'produto') {
							if ($produto_no_carrinho == false) {
								echo "<span id='adicionar_carrinho' class='mx-1' title='Adicionar este produto a seu carrinho'><a href='javascript:void(0);' data-toggle='modal' data-target='#modal_adicionar_carrinho' class='text-success'><i class='fad fa-cart-plus fa-fw fa-2x'></i></a></span>";
							}
						}
					?>
        </div>
        <div class='py-2 text-right col-md-4 col-sm-12'>
					<?php
						if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {
							echo "
	                          <span id='compartilhar_anotacao' class='ml-1' title='Editar compartilhamento desta anotação'
                                 data-toggle='modal' data-target='#modal_compartilhar_anotacao'>
                                <a href='javascript:void(0);' class='text-default'>
                                    <i class='fad fa-users fa-fw'></i>
                                </a>
                              </span>
	                        ";
						}
					?>
            <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'>
                    <?php
	                    if (($user_id != false) && ($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
		                    $comments = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE pagina_id = $pagina_id");
		                    if ($comments->num_rows == 0) {
			                    echo "
                                <a href='javascript:void(0);' class='text-muted'>
                                    <i class='fad fa-comments-alt fa-fw'></i>
                                </a>
                            ";
		                    } else {
			                    echo "
                                <a href='javascript:void(0);' class='text-secondary'>
                                        <i class='fad fa-comments-alt fa-fw'></i>
                                </a>
		                    ";
		                    }
	                    }
                    ?>
                </span>
					<?php
						if (($user_id != false) && ($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
							if ($etiquetados->num_rows > 0) {
								echo "
                                  <span id='adicionar_etiqueta' class='ml-1' title='Adicionar etiqueta' data-toggle='modal'
                                        data-target='#modal_gerenciar_etiquetas'>
                                      <a href='javascript:void(0);' class='text-warning'>
                                          <i class='fad fa-tags fa-fw'></i>
                                      </a>
                                  </span>
                                ";
							} else {
								echo "
                                  <span id='adicionar_etiqueta' class='ml-1' title='Adicionar etiqueta' data-toggle='modal'
                                        data-target='#modal_gerenciar_etiquetas'>
                                      <a href='javascript:void(0);' class='text-muted'>
                                          <i class='fad fa-tags fa-fw'></i>
                                      </a>
                                  </span>
                                ";
							}
							if ($estado_estudo == true) {
								$marcar_completo = 'collapse';
								$marcar_incompleto = false;
							} else {
								$marcar_completo = false;
								$marcar_incompleto = 'collapse';
							}
							echo "
                              <span id='add_completed' class='ml-1 $marcar_completo' title='Estudo completo' value='$pagina_id'><a href='javascript:void(0);' class='text-muted'><i class='fad fa-check-circle fa-fw'></i></a></span>
                              <span id='remove_completed' class='ml-1 $marcar_incompleto' title='Desmarcar como completo' value='$pagina_id'><a href='javascript:void(0);'><span class='text-success'><i class='fad fa-check-circle fa-fw'></i></span></span></a></span>
                            ";
							if ($pagina_bookmark == true) {
								$marcar_bookmark = 'collapse';
								$desmarcar_bookmark = false;
							} else {
								$marcar_bookmark = false;
								$desmarcar_bookmark = 'collapse';
							}
							echo "
                              <span id='add_bookmark' class='ml-1 $marcar_bookmark' title='Marcar para leitura' value='$pagina_id'><a href='javascript:void(0);' class='text-muted'><i class='fad fa-bookmark fa-fw'></i></a></span>
                              <span id='remove_bookmark' class='ml-1 $desmarcar_bookmark' title='Remover da lista de leitura' value='$pagina_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fad fa-bookmark fa-fw'></i></span></span></a></span>
                            ";
							
							$estado_cor = false;
							$estado_icone = return_estado_icone($pagina_estado, 'pagina');
							if ($pagina_estado == 4) {
								$estado_cor = 'text-warning';
							} else {
								$estado_cor = 'text-info';
							}
							if ($pagina_estado != 0) {
								echo "
                                <span id='change_estado_pagina' class='ml-1' title='Estado da página' data-toggle='modal' data-target='#modal_estado'><a href='javascript:void(0);'><span class='$estado_cor'><i class='$estado_icone fa-fw'></i></span></a></span>
                                ";
							}
						}
					?>
        </div>
    </div>
</div>
<div class="container">
	<?php
		$template_titulo_context = true;
		if ($pagina_tipo == 'topico') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = "<a href='pagina.php?pagina_id=$topico_materia_pagina_id' title='Matéria'>$topico_materia_titulo</a> / <a href='pagina.php?pagina_id=$topico_curso_pagina_id' title='Curso'>$topico_curso_titulo</a>";
		} elseif ($pagina_tipo == 'elemento') {
			$template_titulo = $elemento_titulo;
			$template_subtitulo = $elemento_autor;
		} elseif ($pagina_tipo == 'curso') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = 'Curso';
		} elseif ($pagina_tipo == 'materia') {
			$template_titulo = $pagina_titulo;
			$template_subtitulo = "Matéria / <a href='pagina.php?pagina_id=$pagina_item_id'>$curso_titulo</a>";
		} elseif ($pagina_tipo == 'texto') {
			if ($texto_page_id != false) {
				$template_titulo = return_pagina_titulo($texto_pagina_id);
			}
			$template_subtitulo = false;
			if ($texto_page_id == 0) {
				if ($texto_titulo != false) {
					$template_titulo = $texto_titulo;
				} else {
					$template_titulo = 'Texto sem título';
				}
				$template_subtitulo = 'Texto privado';
			}
			$template_titulo_no_nav = false;
		} elseif ($pagina_tipo == 'sistema') {
			$template_titulo = $pagina_titulo;
		} elseif ($pagina_tipo == 'pagina') {
			if ($pagina_titulo == false) {
				$template_titulo = 'Página sem título';
			} else {
				$template_titulo = $pagina_titulo;
			}
			if ($pagina_compartilhamento == 'privado') {
				$template_subtitulo = 'Página privada';
			} elseif ($pagina_compartilhamento == 'publico') {
				$template_subtitulo = 'Página pública';
			} elseif ($pagina_compartilhamento == 'escritorio') {
				$pagina_user_apelido = return_apelido_user_id($pagina_user_id);
				$template_subtitulo = "Escritório de $pagina_user_apelido";
			} else {
				$template_subtitulo = $pagina_compartilhamento;
			}
			if ($pagina_subtipo == 'Plano de estudos') {
				$template_titulo = return_pagina_titulo($pagina_id);
				$pagina_original_info = return_pagina_info($pagina_item_id);
				$pagina_original_titulo = $pagina_original_info[6];
				$pagina_original_concurso_pagina_id = $pagina_original_info[1];
				$pagina_original_concurso_titulo = return_pagina_titulo($pagina_original_concurso_pagina_id);
				$template_titulo = "Plano de estudos: $pagina_original_titulo";
				$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_item_id'>$pagina_original_titulo</a> / <a href='pagina.php?pagina_id=$pagina_original_concurso_pagina_id'>$pagina_original_concurso_titulo</a>";
			} elseif ($pagina_subtipo == 'etiqueta') {
				$template_subtitulo = 'Página livre';
			}
		} elseif ($pagina_tipo == 'secao') {
			$template_titulo = $pagina_titulo;
			$paginal_original_info = return_pagina_info($pagina_item_id);
			$pagina_original_titulo = $pagina_original_info[6];
			$pagina_original_compartilhamento = $pagina_original_info[4];
			$template_subtitulo = "Seção de \"$pagina_original_titulo\"";
			if ($pagina_original_compartilhamento == 'privado') {
				$template_subtitulo = "$template_subtitulo (Página e seções privadas)";
			}
		} elseif ($pagina_tipo == 'grupo') {
			$template_titulo = return_grupo_titulo_id($grupo_id);
			$template_subtitulo = 'Grupo de estudos';
		} elseif ($pagina_tipo == 'resposta') {
			if ($pagina_titulo == false) {
				$template_titulo = 'Resposta sem título';
			} else {
				$template_titulo = $pagina_titulo;
			}
			if ($original_titulo != false) {
				$template_subtitulo = "Referente ao texto \"$original_titulo\"";
			} else {
				$template_subtitulo = 'Referente a texto sem título';
			}
		}
		include 'templates/titulo.php';
	?>
</div>
<div class="container-fluid">
    <div class="row justify-content-around">
        <div id="coluna_unica" class="col-lg-10 col-md-12 pagina_coluna">
					<?php
						if ($pagina_tipo == 'grupo') {
							include 'pagina/grupo.php';
						} elseif ($pagina_tipo == 'materia') {
							include 'pagina/materia.php';
						}
					?>
        </div>
			<?php
				
				if ($pagina_tipo == 'resposta') {
					echo "
						<div id='coluna_original' class='$coluna_classes pagina_coluna'>";
					$template_div = 'texto_original';
					if ($original_titulo != false) {
						$template_titulo = $original_titulo;
					} else {
						$template_titulo = 'Texto original';
					}
					$template_conteudo = false;
					$template_conteudo = '<p class="mt-4"></p>';
					$template_conteudo .= $original_texto_html;
					include 'templates/page_element.php';
					echo "</div>";
				}
				
				echo "<div id='coluna_esquerda' class='$coluna_classes pagina_coluna'>";
				if ($pagina_tipo == 'elemento') {
					if ($elemento_tipo == 'imagem') {
						$template_id = 'imagem_div';
						$template_titulo = false;
						$template_botoes = false;
						$template_conteudo = "<a href='../imagens/verbetes/$elemento_arquivo' ><img class='imagem_pagina border' src='../imagens/verbetes/$elemento_arquivo'></img></a>";
						include 'templates/page_element.php';
					} elseif (($elemento_tipo == 'video') && ($elemento_iframe != false)) {
						$template_div = 'video_div';
						$template_titulo = false;
						$template_botoes = false;
						$template_conteudo = $elemento_iframe;
						$template_conteudo_class = 'text-center';
						include 'templates/page_element.php';
					}
				} elseif ($pagina_tipo == 'texto') {
					$template_id = $texto_tipo;
					$template_titulo = false;
					$template_conteudo_no_col = true;
					$template_no_spacer = true;
					$template_p_limit = false;
					$template_quill_initial_state = 'edicao';
					$template_quill_page_id = $texto_page_id;
					$template_quill_pagina_id = $pagina_id;
					$template_quill_pagina_de_edicao = true;
					$template_quill_botoes = false;
					$template_background = 'grey lighten-3';
					$template_conteudo = include 'templates/template_quill.php';
					include 'templates/page_element.php';
				}
				
				if (($pagina_tipo != 'texto') && ($pagina_tipo != 'materia')) {
					$template_id = 'verbete';
					if ($pagina_tipo == 'curso') {
						$template_titulo = 'Apresentação';
					} elseif ($pagina_tipo == 'sistema') {
						$template_titulo = 'Aviso';
					} else {
						$template_titulo = 'Verbete';
					}
					if (($pagina_tipo == 'sistema') && ($user_tipo != 'admin')) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if (($pagina_compartilhamento == 'escritorio') && ($user_id != $pagina_user_id)) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if (($pagina_compartilhamento == 'privado') && ($user_id != $pagina_user_id)) {
						$template_quill_botoes = false;
						$template_quill_initial_state = 'leitura';
					}
					if ($pagina_tipo == 'resposta') {
						$template_titulo = 'Resposta';
						$template_classes = 'sticky-top';
						$template_quill_vazio = 'Escreva aqui sua resposta.';
					}
					$template_conteudo = include 'templates/template_quill.php';
					include 'templates/page_element.php';
					
					if ($carregar_secoes == true) {
						include 'pagina/secoes_pagina.php';
					}
					
					include 'pagina/leiamais.php';
					
					include 'pagina/videos.php';
					
					include 'pagina/imagens.php';
					
					include 'pagina/audio.php';
					
					if ($pagina_subtipo == 'etiqueta') {
						include 'pagina/paginas_etiqueta.php';
					}
					
					include 'pagina/usos_etiqueta.php';
					
					include 'pagina/etiquetas.php';
					
				}
				
				echo "</div>";
			?>
			<?php
				if (($pagina_tipo != 'sistema') && ($pagina_tipo != 'texto') && ($pagina_compartilhamento != 'escritorio') && ($pagina_tipo != 'resposta') && ($pagina_tipo != 'materia') && ($pagina_tipo != 'curso')) {
					include 'pagina/coluna_direita_anotacoes.php';
				} elseif ($pagina_tipo == 'curso') {
					
					echo "<div id='coluna_direita' class='$coluna_classes pagina_coluna'>";
					
					$template_id = 'modulos';
					$template_titulo = 'Módulos';
					$template_conteudo_no_col = true;
					$template_botoes = false;
					$template_conteudo = false;
					$template_conteudo_class = 'justify-content-start';
					
					$row_items = 2;
					$materias = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE tipo = 'materia' AND pagina_id = $pagina_id");
					
					$rowcount = mysqli_num_rows($materias);
					if ($materias->num_rows > 0) {
						while ($materia = $materias->fetch_assoc()) {
							$materia_pagina_id = $materia['elemento_id'];
							$materia_pagina_titulo = return_pagina_titulo($materia_pagina_id);
							$template_conteudo .= "
	                            <span class='col-lg-6 col-md-12'><a href='pagina.php?pagina_id=$materia_pagina_id'><button type='button' class='btn btn-light fontstack-subtitle col-12 grey lighten-3 text-muted rounded materia_hover mx-0 px-0'>$materia_pagina_titulo</button></a></span>
                            ";
						}
						unset($materia_id);
					}
					
					include 'templates/page_element.php';
					
					include 'pagina/curso.php';
					
					echo "</div>";
					
				}
			
			?>
    </div>
</div>
<?php
	echo "<button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
            class='fas fa-pen-alt fa-fw'></i></button>";
?>
</div>

<?php
	if ($pagina_tipo == 'topico') {
		$template_modal_div_id = 'modal_verbetes_relacionados';
		$template_modal_titulo = 'Verbetes relacionados';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = $breadcrumbs;
		include 'templates/modal.php';
	}
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	if (isset($comments)) {
		if ($comments->num_rows > 0) {
			$template_modal_body_conteudo .= "<ul class='list-group'>";
			while ($comment = $comments->fetch_assoc()) {
				$timestamp_comentario = $comment['timestamp'];
				$texto_comentario = $comment['comentario'];
				$autor_comentario_id = $comment['user_id'];
				$autor_comentario_apelido = return_apelido_user_id($autor_comentario_id);
				$autor_comentario_avatar_info = return_avatar($autor_comentario_id);
				$autor_comentario_avatar = $autor_comentario_avatar_info[0];
				$autor_comentario_cor = $autor_comentario_avatar_info[1];
				
				$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p></span><strong><a href='pagina.php?user_id=$autor_comentario_id' ><span class='$autor_comentario_cor'><i class='fad $autor_comentario_avatar fa-fw fa-2x'></i></span>$autor_comentario_apelido</a></strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
			}
			$template_modal_body_conteudo .= "</ul>";
		} else {
			$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
		}
	}
	
	if ($user_apelido != false) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <textarea id='novo_comentario' name='novo_comentario' class='md-textarea form-control' rows='3' required></textarea>
                    <label for='novo_comentario'>Escreva seu comentário:</label>
                </div>
            ";
	} else {
		$template_modal_body_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em <a href='escritorio.php'>seu escritório</a>.</strong></p>";
	}
	include 'templates/modal.php';
	
	
	$active1 = false;
	$active2 = false;
	$active3 = false;
	$active4 = false;
	$inactive = false;
	if ($pagina_estado == 1) {
		$active1 = 'selected';
	} elseif ($pagina_estado == 2) {
		$active2 = 'selected';
	} elseif ($pagina_estado == 3) {
		$active3 = 'selected';
	} elseif ($pagina_estado == 4) {
		$active4 = 'selected';
	} else {
		$inactive = 'selected';
	}
	$template_modal_div_id = 'modal_estado';
	$template_modal_titulo = 'Qualidade da página';
	$template_modal_body_conteudo = "
        <p>Por favor, determine abaixo sua avaliação sobre o estado atual desta página:</p>
        <div class='md-form mb-2'>
            <span>Estado</span>
            <select class='mdb-select' name='novo_estado_pagina'>
                <option value='' disabled $inactive>Escolha uma opção</option>
                <option value='1' $active1>Rascunho</option>
                <option value='2' $active2>Aceitável</option>
                <option value='3' $active3>Desejável</option>
                <option value='4' $active4>Excepcional</option>
            </select>
        </div>
    ";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_youtube';
	$template_modal_titulo = 'Adicionar vídeo do YouTube';
	$template_modal_body_conteudo = "
                    <div class='md-form mb-2'>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate'
                               required>
                        <label data-error='inválido' data-success='válido'
                               for='novo_video_link'>Link para o vídeo (Youtube)</label>
                    </div>
	";
	
	include 'templates/modal.php';
	
	if ($pagina_tipo == 'elemento') {
		include 'pagina/modals_elemento.php';
	}
	
	if ($carregar_secoes == true) {
		$template_modal_div_id = 'modal_partes_form';
		$template_modal_titulo = 'Adicionar seção';
		$template_modal_submit_name = 'trigger_nova_secao';
		$template_modal_body_conteudo = false;
		if ($pagina_tipo == 'elemento') {
			$template_modal_body_conteudo .= "
		        <p>Por favor, tome cuidado para que não haja duplicidade. Se possível, é preferencial que todas as seções sejam acrescentadas na ordem em que aparecem na edição de que você dispõe. Ao inserir a ordem da nova seção, por favor considere Introdução, Prefácio etc. Se houver mais de um prefácio, considere a possibilidade de consolidá-los em apenas uma seção, por exemplo, no caso de prefácios a edições que somente mencionam adições ou correções realizadas. Seções de agradecimento, caso nada incluam de especialmente relevante, podem ser ignoradas, assim como tabelas de referência, listas de anexos, glossários e seções afim.</p>
		        <p>Exemplos de seções adequadas: \"Capítulo 1\", \"Capítulo 2: Título do Capítulo\", \"Parte 1: As Origens\", \"Introdução\".</p>
		        <p>É possível determinar a ordem como \"0\". É preferível usar essa opção para elementos anteriores ao primeiro capítulo, como Introdução e Prefácio, pois dessa forma o primeiro capítulo terá a ordem \"1\", o segundo a ordem \"2\" etc.</p>
	          ";
		} else {
			$template_modal_body_conteudo .= "
				<p>Você pode criar seções de sua página, mas recomenda-se cuidado para que não haja duplicidade. Preferencialmente, as seções devem ser adicionadas na ordem final de sua preferência. Nesse caso, é possível ignorar o campo 'ordem' completamente.</p>
			";
		}
		$template_modal_body_conteudo .= "
          <div class='md-form mb-2'>
              <input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
              <label for='elemento_nova_secao'>Título da nova seção</label>
          </div>
          <div class='md-form mb-2'>
              <input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
              <label for='elemento_nova_secao_ordem'>Posição da nova seção</label>
          </div>
        ";
		
		$secoes = $conn->query("SELECT secao_pagina_id, ordem FROM Secoes WHERE pagina_id = $pagina_id");
		if ($secoes->num_rows > 0) {
			$template_modal_body_conteudo .= "
		<h3>Seções registradas desta página:</h3>
		<ul class='list-group'>
    ";
			while ($secao = $secoes->fetch_assoc()) {
				$secao_ordem = $secao['ordem'];
				$secao_pagina_id = $secao['secao_pagina_id'];
				$secao_info = return_pagina_info($secao_pagina_id);
				$secao_titulo = $secao_info[6];
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$secao_pagina_id'><li class='list-group-item list-group-item-action'>$secao_ordem: $secao_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
	}
	
	include 'pagina/modal_add_elemento.php';
	
	include 'pagina/modal_adicionar_imagem.php';
	
	if ($modal_pagina_dados == true) {
		if (($pagina_tipo == 'pagina') || ($pagina_tipo == 'sistema')) {
			$mudar_titulo_texto = 'desta página';
		} elseif (($pagina_tipo == 'texto') || ($pagina_tipo == 'resposta')) {
			$mudar_titulo_texto = 'deste texto';
		} elseif ($pagina_tipo == 'curso') {
			$mudar_titulo_texto = 'deste curso';
		} elseif ($pagina_tipo == 'materia') {
			$mudar_titulo_texto = 'desta matéria';
		} elseif ($pagina_tipo == 'topico') {
			$mudar_titulo_texto = 'deste tópico';
		} else {
			$mudar_titulo_texto = 'deste documento';
		}
		$template_modal_div_id = 'modal_pagina_dados';
		$template_modal_titulo = "Alterar dados $mudar_titulo_texto";
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
        <div class='md-form mb-2'>
            <input type='text' id='pagina_novo_titulo' name='pagina_novo_titulo'
                   class='form-control validate' value='$pagina_titulo' required>
            <label data-error='inválido' data-success='válido'
                   for='pagina_novo_titulo'>Novo título</label>
        </div>
        ";
		
		if (isset($secoes)) {
			if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id) && ($secoes->num_rows == 0) && ($pagina_tipo == 'pagina') && ($pagina_titulo != false) && ($pagina_subtipo != 'produto')) {
				$modal_novo_curso = true;
				$template_modal_show_buttons = false;
				$template_modal_body_conteudo .= "
		        <span data-toggle='modal' data-target='#modal_pagina_dados'>
                    <div class='row justify-content-center'>
                        <button type='button' class='$button_classes' data-toggle='modal' data-target='#modal_novo_curso'>Transformar em página inicial de curso</button>
                    </div>
                </span>
		    ";
			}
		}
		include 'templates/modal.php';
	}
	
	if ($modal_novo_curso == true) {
		$template_modal_div_id = 'modal_novo_curso';
		$template_modal_titulo = 'Transformar esta página em um curso';
		$template_modal_submit_name = 'novo_curso';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
	        <p>Ao pressionar o botão de confirmação abaixo, esta página será transformada na página inicial de um curso. Em seguida, será possível acrescentar matérias e, às páginas das matérias, tópicos.</p>
	        <div class='md-form mb-2'>
	            <input type='text' id='novo_curso_sigla' name='novo_curso_sigla' class='form-control validade' required>
	            <label data-error='inválido' data-success='válido' for='pagina_novo_titulo'>Novo título</label>
            </div>
	    ";
		include 'templates/modal.php';
	}
	
	if (($pagina_subtipo == 'produto') && ($carregar_produto_setup == true)) {
		$template_modal_div_id = 'modal_produto_nova_imagem';
		$template_modal_titulo = 'Determinar imagem para o cartão do produto';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <div class='md-form mb-2'>
				<p>Selecione uma entre as imagens desta página para fazer parte do cartão de oferta deste produto na Loja Virtual:</p>
            	<select class='$select_classes' name='produto_nova_imagem'>
            	<option value='' disabled selected>Selecione uma imagem:</option>
           ";
		foreach ($imagem_opcoes as $imagem_opcao) {
			$imagem_opcao_id = $imagem_opcao[0];
			$imagem_opcao_titulo = $imagem_opcao[1];
			$template_modal_body_conteudo .= "<option value='$imagem_opcao_id'>$imagem_opcao_titulo</option>";
		}
		$template_modal_body_conteudo .= "
            </select>
            </div>
           ";
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_produto_preco';
		$template_modal_titulo = 'Determinar preço deste produto';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<div class='md-form mb-2'>
				<p>Escreva abaixo o preço deste produto em Reais brasileiros (BRL).</p>
				<input type='number' name='novo_produto_preco' id='novo_produto_preco' value='$produto_preco'>
			</div>
		";
		include 'templates/modal.php';
		
	}
	
	include 'templates/etiquetas_modal.php';
	
	if ($pagina_tipo == 'secao') {
		$template_modal_div_id = 'modal_paginas_relacionadas';
		$template_modal_titulo = 'Página e seções';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$pagina_item_id'><li class='list-group-item list-group-item-action list-group-item-primary'>$pagina_original_titulo</li></a>";
		$parentes = $conn->query("SELECT id FROM Paginas WHERE tipo = 'secao' AND item_id = $pagina_item_id");
		if ($parentes->num_rows > 0) {
			while ($parente = $parentes->fetch_assoc()) {
				$parente_id = $parente['id'];
				$parente_titulo = return_pagina_titulo($parente_id);
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$parente_id'><li class='list-group-item list-group-item-action'>$parente_titulo</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}
	
	if ($pagina_tipo == 'texto') {
		include 'pagina/modals_texto.php';
	}
	
	if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {
		
		$template_modal_div_id = 'modal_compartilhar_anotacao';
		$template_modal_titulo = 'Compartilhamento';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <p>Apenas você, como criador original desta anotação, poderá alterar suas opções de compartilhamento. Por favor, analise cuidadosamente as opções abaixo. Versões anteriores do documento estarão sempre disponíveis no histórico (para todos os que tenham acesso à sua versão atual). Todo usuário com acesso à anotação poderá alterar suas etiquetas.</p>
			  <h3>Compartilhar com grupo de estudos</h3>
			  ";
		$grupos_do_usuario = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
		if ($grupos_do_usuario->num_rows > 0) {
			$template_modal_body_conteudo .= "
                  <form method='post'>
                    <select name='compartilhar_grupo_id' class='$select_classes'>
                        <option value='' disabled selected>Selecione o grupo de estudos</option>
                ";
			while ($grupo_do_usuario = $grupos_do_usuario->fetch_assoc()) {
				$grupo_do_usuario_id = $grupo_do_usuario['grupo_id'];
				$grupo_do_usuario_titulo = return_grupo_titulo_id($grupo_do_usuario_id);
				$template_modal_body_conteudo .= "<option value='$grupo_do_usuario_id'>$grupo_do_usuario_titulo</option>";
			}
			$template_modal_body_conteudo .= "
                    </select>
                    <div class='row justify-content-center'>
                        <button class='$button_classes' name='trigger_compartilhar_grupo'>Compartilhar com grupo</button>
                    </div>
                  </form>
                ";
		} else {
			$template_modal_body_conteudo .= "<p class='text-muted'><em>Você não faz parte de nenhum grupo de estudos. Visite seu escritório para participar.</em></p>";
		}
		$comp_grupos = $conn->query("SELECT recipiente_id FROM Compartilhamento WHERE item_id = $pagina_id");
		if ($comp_grupos->num_rows > 0) {
			$template_modal_body_conteudo .= "<h3>Compartilhado com:</h3>";
			$template_modal_body_conteudo .= "<ul class='list-group'>";
			while ($comp_grupo = $comp_grupos->fetch_assoc()) {
				$comp_grupo_id = $comp_grupo['recipiente_id'];
				$comp_grupo_info = return_grupo_info($comp_grupo_id);
				$comp_grupo_titulo = $comp_grupo_info[1];
				$comp_grupo_pagina_id = $comp_grupo_info[3];
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$comp_grupo_pagina_id' class='mt-1'><li class='list-group-item list-group-item-info list-group-item-action'>$comp_grupo_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		
		//TODO: CONTINUAR AQUI, LISTA DE COMPARTILHAMENTO DESTA PÁGINA
		
		/*$template_modal_body_conteudo .= "
			<form>
			<h3>Compartilhar com outro usuário</h3>
				<select name='compartilhar_usuario' class='$select_classes'>
						<option value='' disabled selected>Selecione o usuário</option>
				</select>
				<div class='row justify-content-center'>
						<button class='$button_classes' name='trigger_compartilhar_usuario'>Compartilhar com usuário</button>
				</div>
			</form>
			<h3>Tornar anotação pública.</h3>
			<p>Todo usuário da Ubwiki poderá ler sua anotação, mas não poderá editá-la.</p>
			<h3>Tornar pública e aberta.</h3>
			<p>Todo usuário da Ubwiki poderá ler e editar sua anotação.</p>
			";*/
		
		include 'templates/modal.php';
		
	}
	if ($pagina_tipo == 'texto') {
		$template_modal_div_id = 'modal_add_reply';
		$template_modal_titulo = 'Escrever texto em resposta';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p>Pressione o botão abaixo para escrever um texto em resposta a este. Seu texto será visível a todos que possam acessar este texto.</p>
			<div class='row justify-content-center'>
				<a href='pagina.php?original_id=$pagina_id&resposta_id=new'><button class='$button_classes'>Escrever resposta</button></a>
			</div>
		";
		$template_modal_body_conteudo .= "<h2>Respostas a este texto</h2>";
		if ($respostas->num_rows > 0) {
			while ($resposta = $respostas->fetch_assoc()) {
				$resposta_pagina_id = $resposta['elemento_id'];
				$resposta_pagina_info = return_pagina_info($resposta_pagina_id);
				$resposta_pagina_titulo = $resposta_pagina_info[6];
				$resposta_user_id = $resposta_pagina_info[5];
				$resposta_avatar_info = return_avatar($resposta_user_id);
				$resposta_avatar_icone = $resposta_avatar_info[0];
				$resposta_avatar_cor = $resposta_avatar_info[1];
				$resposta_user_apelido = return_apelido_user_id($resposta_user_id);
				if ($resposta_pagina_titulo != false) {
					$template_modal_body_conteudo .= "<li class='list-group-item d-flex justify-content-between'><a href='pagina.php?user_id=$resposta_user_id' class='$resposta_avatar_cor'><i class='fa $resposta_avatar_icone fa-fw fa-2x'></i> <span class='text-dark'>$resposta_user_apelido</span></a> <a href='pagina.php?pagina_id=$resposta_pagina_id'>$resposta_pagina_titulo</a></li>";
				}
			}
		} else {
			$template_modal_body_conteudo .= "<p><span class='text-muted'>Não há respostas a este texto.</span></p>";
		}
		include 'templates/modal.php';
	}
	
	if ($carregar_adicionar_materia == true) {
		$template_modal_div_id = 'modal_add_materia';
		$template_modal_titulo = 'Adicionar matéria';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>Pesquise a nova matéria abaixo</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_materias' id='buscar_materias' required>
				<label for='buscar_materias'>Buscar matéria</label>
                <button type='button' class='$button_classes' id='trigger_buscar_materias'>Buscar</button>
			</div>
			<div class='row border p-2' id='materias_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}
	
	if ($carregar_adicionar_topico == true) {
		$template_modal_div_id = 'modal_add_topico';
		$template_modal_titulo = 'Adicionar tópico';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>Pesquise o novo tópico abaixo</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_topicos' id='buscar_topicos' required>
				<label for='buscar_topicos'>Buscar tópico</label>
                <button type='button' class='$button_classes' id='trigger_buscar_topicos'>Buscar</button>
			</div>
			<div class='row border p-2' id='topicos_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}
	
	if ($carregar_adicionar_subtopico == true) {
		$template_modal_div_id = 'modal_add_subtopico';
		$template_modal_titulo = 'Adicionar subtópico';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>Pesquise o novo subtópico abaixo</p>";
		$template_modal_body_conteudo .= "
			<div class='md-form'>
				<input type='text' class='form-control' name='buscar_subtopicos' id='buscar_subtopicos' required>
				<label for='buscar_topicos'>Buscar subtópico</label>
				<button type='button' class='$button_classes' id='trigger_buscar_subtopicos'>Buscar</button>
			</div>
			<div class='row border p-2' id='subtopicos_disponiveis'></div>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';
	}
	
	if ($carregar_convite == true) {
		$template_modal_div_id = 'modal_novo_membro';
		$template_modal_titulo = 'Convidar novo membro';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <p>Pesquise o convidado por seu apelido:</p>
	        <div class='md-form'>
	        	<input type='text' class='form-control' id='apelido_convidado' novo='apelido_convidado'>
	        	<label for='apelido_convidado'>Apelido</label>
	        </div>
	        <div class='md-form my-1'>
	            <button type='button' id='trigger_buscar_convidado' name='trigger_buscar_convidado' class='$button_classes btn-sm m-0'>Buscar</button>
            </div>
            <div id='convite_resultados' class='row border p-2'>
			</div>
	    ";
		include 'templates/modal.php';
	}
	
	if ($pagina_subtipo == 'produto') {
		$template_modal_div_id = 'modal_adicionar_carrinho';
		$template_modal_titulo = 'Adicionar este produto a seu carrinho';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <input type='hidden' value='$pagina_id' name='adicionar_produto_pagina_id'>
			<p>Confirma adicionar este produto a seu carrinho?</p>
			<p>Produto: $pagina_titulo</p>
			<p>Autor: $produto_autor</p>
			<p>Preço: R$ $produto_preco</p>
		";
		include 'templates/modal.php';
	}
	
	if ($pagina_tipo == 'curso') {
		$template_modal_div_id = 'modal_busca';
		$template_modal_titulo = 'O que você vai aprender hoje?';
		$template_modal_body_conteudo = false;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo .= "
            <form id='searchform' action='' method='post'>
            <div id='searchDiv'>
                <input id='barra_busca' list='searchlist' type='text' class='barra_busca w-100'
                       name='searchBar' rows='1' autocomplete='off' spellcheck='false'
                       placeholder='Busca de páginas deste curso' required>
                <datalist id='searchlist'>
        ";
		$result = $conn->query("SELECT chave FROM Searchbar WHERE curso_id = '$curso_id' ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row['chave'];
				$template_modal_body_conteudo .= "<option>$chave</option>";
			}
		}
		$template_modal_body_conteudo .= "
                            </datalist>";
		$template_modal_body_conteudo .= "<input id='trigger_busca' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
		$template_modal_body_conteudo .= "
        </div>
        </form>";
		include 'templates/modal.php';
	}


?>

</body>

<?php
	
	$mdb_select = true;
	if ($pagina_tipo == 'curso') {
		include 'templates/searchbar.html';
	}
	if ($pagina_tipo == 'texto') {
		$sticky_toolbar = true;
		$quill_extra_buttons = false;
		if ($texto_user_id == $user_id) {
			if ($pagina_compartilhamento == 'privado') {
				$quill_extra_buttons .= "<a id='apagar_anotacao' class='mx-2 text-danger' title='Destruir anotação' data-toggle='modal' data-target='#modal_apagar_anotacao' href='javascript:void(0);'><i class='fad fa-shredder fa-fw'></i></a>";
			}
		}
		$quill_extra_buttons = mysqli_real_escape_string($conn, $quill_extra_buttons);
	}
	if ($pagina_tipo != 'texto') {
		$sistema_etiquetas_elementos = true;
	}
	$sistema_etiquetas_topicos = true;
	$sticky_toolbar = true;
	include 'templates/html_bottom.php';
	if ($pagina_tipo != 'texto') {
		include 'templates/footer.html';
	}
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>