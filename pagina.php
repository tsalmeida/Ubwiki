<?php
	
	include 'engine.php';
	$modal_novo_curso = false;
	$nao_contar = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_materia = false;
	$carregar_adicionar_topico = false;
	$carregar_adicionar_subtopico = false;
	$carregar_questoes_topico = false;
	$carregar_modal_wikipedia = false;
	$carregar_quill_anotacoes = false;
	$carregar_modal_destruir_pagina = false;
	$carregar_toggle_acervo = false;
	$carregar_convite = false;
	$item_no_acervo = false;
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
		} elseif (isset($_GET['questao_id'])) {
			$pagina_questao_id = $_GET['questao_id'];
			$pagina_id = return_pagina_id($pagina_questao_id, 'questao');
		} elseif (isset($_GET['texto_apoio_id'])) {
			$pagina_texto_apoio_id = $_GET['texto_apoio_id'];
			$pagina_id = return_pagina_id($pagina_texto_apoio_id, 'texto_apoio');
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
	
	if ($pagina_id == 1) {
		header('Location:ubwiki.php');
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
		$pagina_publicacao = $pagina_info[9];
		$pagina_colaboracao = $pagina_info[10];
	} else {
		header('Location:pagina.php?pagina_id=4');
		exit();
	}
	
	if (isset($_POST['trigger_apagar_pagina'])) {
		$conn->query("DELETE FROM Paginas WHERE id = $pagina_id");
		header('Location:pagina.php?pagina_id=6');
	}
	
	if (isset($_GET['wiki_id'])) {
		$wiki_id = (int)$_GET['wiki_id'];
	} else {
		$wiki_id = false;
	}
	
	$privilegio_edicao = return_privilegio_edicao($pagina_id, $user_id);
	
	if ($pagina_subtipo == 'Plano de estudos') {
		$pagina_materia_familia = return_familia($pagina_item_id);
		$pagina_curso_pagina_id = $pagina_materia_familia[1];
		$pagina_curso_pagina_info = return_pagina_info($pagina_curso_pagina_id);
		$pagina_compartilhamento = $pagina_curso_pagina_info[4];
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
	} elseif ($pagina_tipo == 'questao') {
		$pagina_questao_info = return_questao_info($pagina_item_id);
		$pagina_questao_origem = $pagina_questao_info[0];
		$pagina_questao_curso_id = $pagina_questao_info[1];
		$pagina_questao_edicao_ano = $pagina_questao_info[2];
		$pagina_questao_etapa_id = $pagina_questao_info[3];
		$pagina_questao_etapa_info = return_etapa_edicao_ano_e_titulo($pagina_questao_etapa_id);
		$pagina_questao_etapa_ano = $pagina_questao_etapa_info[0];
		$pagina_questao_etapa_titulo = $pagina_questao_etapa_info[1];
		$pagina_questao_texto_apoio = $pagina_questao_info[4];
		$pagina_questao_texto_apoio_id = $pagina_questao_info[5];
		if ($pagina_questao_texto_apoio_id != false) {
			$pagina_questao_texto_apoio_info = return_texto_apoio_info($pagina_questao_texto_apoio_id);
			$pagina_questao_texto_apoio_pagina_id = $pagina_questao_texto_apoio_info[1];
			$pagina_questao_texto_apoio_enunciado_html = $pagina_questao_texto_apoio_info[6];
			$pagina_questao_texto_apoio_html = $pagina_questao_texto_apoio_info[9];
			$pagina_questao_texto_apoio_titulo = $pagina_questao_texto_apoio_info[5];
		} else {
			$pagina_questao_texto_apoio_pagina_id = false;
			$pagina_questao_texto_apoio_html = false;
			$pagina_questao_texto_apoio_titulo = false;
		}
		$pagina_questao_prova_id = $pagina_questao_info[6];
		$pagina_questao_prova_info = return_info_prova_id($pagina_questao_prova_id);
		$pagina_questao_prova_titulo = $pagina_questao_prova_info[0];
		$pagina_questao_numero = $pagina_questao_info[7];
		$pagina_questao_materia = $pagina_questao_info[8];
		$pagina_questao_tipo = $pagina_questao_info[9];
		$pagina_questao_enunciado_html = $pagina_questao_info[10];
		$pagina_questao_enunciado_content = $pagina_questao_info[12];
		$pagina_questao_item1_html = $pagina_questao_info[13];
		$pagina_questao_item2_html = $pagina_questao_info[14];
		$pagina_questao_item3_html = $pagina_questao_info[15];
		$pagina_questao_item4_html = $pagina_questao_info[16];
		$pagina_questao_item5_html = $pagina_questao_info[17];
		$pagina_questao_item1_content = $pagina_questao_info[23];
		$pagina_questao_item2_content = $pagina_questao_info[24];
		$pagina_questao_item3_content = $pagina_questao_info[25];
		$pagina_questao_item4_content = $pagina_questao_info[26];
		$pagina_questao_item5_content = $pagina_questao_info[27];
		$pagina_questao_item1_gabarito = $pagina_questao_info[28];
		$pagina_questao_item2_gabarito = $pagina_questao_info[29];
		$pagina_questao_item3_gabarito = $pagina_questao_info[30];
		$pagina_questao_item4_gabarito = $pagina_questao_info[31];
		$pagina_questao_item5_gabarito = $pagina_questao_info[32];
	} elseif ($pagina_tipo == 'texto_apoio') {
		$pagina_texto_apoio_info = return_texto_apoio_info($pagina_item_id);
		$pagina_texto_apoio_origem = $pagina_texto_apoio_info[2];
		$pagina_texto_apoio_curso_id = $pagina_texto_apoio_info[3];
		$pagina_texto_apoio_curso_titulo = return_curso_titulo_id($pagina_texto_apoio_curso_id);
		$pagina_texto_apoio_prova_id = $pagina_texto_apoio_info[4];
		$pagina_texto_apoio_prova_info = return_info_prova_id($pagina_texto_apoio_prova_id);
		$pagina_texto_apoio_edicao_ano = $pagina_texto_apoio_prova_info[2];
		$pagina_texto_apoio_etapa_titulo = $pagina_texto_apoio_prova_info[6];
		$pagina_texto_apoio_prova_titulo = $pagina_texto_apoio_prova_info[0];
		$pagina_texto_apoio_titulo = $pagina_texto_apoio_info[5];
		$pagina_texto_apoio_enunciado_html = $pagina_texto_apoio_info[6];
		$pagina_texto_apoio_enunciado_content = $pagina_texto_apoio_info[8];
		$pagina_texto_apoio_html = $pagina_texto_apoio_info[9];
		$pagina_texto_apoio_content = $pagina_texto_apoio_info[11];
	} elseif (($pagina_tipo == 'materia') || ($pagina_tipo == 'curso')) {
		$familia_info = return_familia($pagina_id);
		$pagina_curso_pagina_id = (int)$familia_info[1];
		$pagina_curso_info = return_pagina_info($pagina_curso_pagina_id);
		$pagina_curso_id = (int)$pagina_curso_info[1];
		$pagina_curso_user_id = (int)$pagina_curso_info[5];
		$pagina_curso_compartilhamento = $pagina_curso_info[4];
		$pagina_curso_titulo = $pagina_curso_info[6];
	} elseif ($pagina_subtipo == 'produto') {
		$produto_preco = false;
		$produto_autor = false;
		$produto_info = return_produto_info($pagina_id);
		$produto_preco = $produto_info[2];
		$produto_autor = $produto_info[3];
	} elseif ($pagina_subtipo == 'etiqueta') {
		$pagina_etiqueta_id = $pagina_item_id;
	}
	
	if ($pagina_tipo == 'curso') {
		$conn->query("UPDATE Opcoes SET opcao = $pagina_curso_id WHERE user_id = $user_id AND opcao_tipo = 'curso_ativo'");
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
			if (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia') || ($pagina_subtipo == 'Plano de estudos')) {
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
			$conn->query("DELETE FROM Textos WHERE id = $pagina_texto_id");
			$conn->query("DELETE FROM Paginas WHERE id = $pagina_id");
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
	if (($pagina_tipo == 'questao') || ($pagina_tipo == 'texto_apoio')) {
		$html_head_template_quill_sim = true;
	}
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
		$conn->query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('acesso', $user_id, $pagina_id, '$pagina_tipo', 'grupo', $compartilhar_grupo_id)");
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
	if ($pagina_tipo == 'grupo') {
		$membros = $conn->query("SELECT DISTINCT membro_user_id, estado FROM Membros WHERE grupo_id = $grupo_id AND (estado = 1 OR estado IS NULL)");
	}
	
	include 'pagina/queries_notificacoes.php';

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
							if ($privilegio_edicao == true) {
								echo "<a href='javascript:void(0)' class='text-info mr-1' id='add_elements' title='Adicionar elementos' data-toggle='modal' data-target='#modal_add_elementos'><i class='fad fa-2x fa-plus-circle fa-fw'></i></a>";
							}
						}
						if ($pagina_tipo == 'elemento') {
							echo "
                            <a href='javascript:void(0);' data-toggle='modal' data-target='#modal_dados_elemento' class='text-info' id='elemento_dados' class='mr-1' title='Editar dados'><i class='fad fa-info-circle fa-fw fa-2x'></i></a>
                            <a href='javascript:void(0);' data-toggle='modal' data-target='#modal_elemento_subtipo' class='text-info' id='elemento_subtipo' class='mr-1' title='Determinar subcategoria'><i class='fad fa-sort-circle fa-fw fa-2x'></i></a>
                            ";
						}
						$modal_pagina_dados = false;
						if (
							(($pagina_tipo == 'sistema') && ($user_tipo == 'admin')) ||
							(($pagina_tipo == 'pagina') && ($pagina_user_id == $user_id)) ||
							((($pagina_tipo == 'curso') || ($pagina_tipo == 'materia') || ($pagina_tipo == 'topico')) && ($pagina_curso_user_id == $user_id)) ||
							(($pagina_tipo == 'texto') && ($pagina_user_id == $user_id) && ($texto_page_id == 0)) ||
							(($pagina_tipo == 'resposta') && ($pagina_user_id == $user_id)) ||
							(($pagina_tipo == 'secao') && (($pagina_user_id == $user_id) || $pagina_original_compartilhamento == false))
						) {
							$modal_pagina_dados = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_pagina_dados' class='text-success mr-1' id='pagina_dados' title='Editar dados'><i class='fad fa-info-circle fa-fw fa-2x'></i></a>";
							$carregar_produto_setup = false;
							if ($pagina_subtipo == 'produto') {
								$carregar_produto_setup = true;
								if (isset($imagem_opcoes)) {
									echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_produto_nova_imagem' class='text-danger mr-1' id='produto_imagem' title='Imagem do produto'><i class='fad fa-image-polaroid fa-fw fa-2x'></i></a>";
								}
								echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_produto_preco' class='text-warning mr-1' id='produto_preco' title='Preço do produto'><i class='fad fa-usd-circle fa-fw fa-2x'></i></a>";
							}
						}
						if ($pagina_tipo == 'texto') {
							if ($respostas->num_rows > 0) {
								echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_reply' class='text-success mr-1' id='add_reply' title='Adicionar resposta'><i class='fad fa-comment-alt-edit fa-fw fa-2x'></i></a>";
							} else {
								echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_reply' class='text-muted mr-1' id='add_reply' title='Adicionar resposta'><i class='fad fa-comment-alt-edit fa-fw fa-2x'></i></a>";
							}
						}
						if (($pagina_tipo == 'curso') && ($curso_user_id == $user_id)) {
							$carregar_adicionar_materia = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_materia' class='text-success mr-1' id='add_materia' title='Adicionar matéria'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a>";
						}
						if (($pagina_tipo == 'materia') && ($pagina_user_id == $user_id)) {
							$carregar_adicionar_topico = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_topico' class='text-success mr-1' id='add_topico' title='Adicionar tópico'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a>";
						}
						if (($pagina_tipo == 'topico') && ($pagina_user_id == $user_id) && ($topico_nivel < 5)) {
							$carregar_adicionar_subtopico = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_subtopico' class='text-success mr-1' id='add_subtopico' title='Adicionar subtópico'><i class='fad fa-plus-circle fa-2x fa-fw'></i></a>";
						}
						if ($pagina_tipo == 'questao') {
							echo "<a href='javascript:void(0);' class='mr-1 text-secondary' title='Dados da questão' data-toggle='modal' data-target='#modal_questao_dados'><i class='fad fa-check-circle fa-fw fa-2x'></i></a>";
						} elseif ($pagina_tipo == 'texto_apoio') {
							echo "<a href='javascript:void(0)' data-toggle='modal' data-target='#modal_texto_apoio_dados' class='text-secondary mr-1' title='Dados do texto de apoio'><i class='fad fa-check-circle fa-fw fa-2x'></i></a>";
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
								echo "<a href='$topico_anterior_link' id='verbete_anterior' class='mx-1' title='Verbete anterior'><i class='fad fa-arrow-left fa-fw'></i></a>";
							}
							echo "<a href='javascript:void(0);' id='verbetes_relacionados' class='text-muted mx-1' title='Navegação' data-toggle='modal' data-target='#modal_verbetes_relacionados'><i class='fad fa-location-circle fa-2x fa-fw'></i></a>";
							if ($topico_proximo != false) {
								$topico_proximo_link = "pagina.php?topico_id=$topico_proximo";
								echo "<a href='$topico_proximo_link' id='verbete_proximo' class='mx-1' title='Próximo verbete'><i class='fad fa-arrow-right fa-fw'></i></a>";
							}
						} elseif ($pagina_tipo == 'secao') {
							echo "<a href='javascript:void(0);' id='secoes' class='mx-1 text-muted' title='Página e seções' data-toggle='modal' data-target='#modal_paginas_relacionadas'><i class='fad fa-map-signs fa-2x fa-fw'></i></a>";
						}
						if ($pagina_subtipo == 'produto') {
							if ($produto_no_carrinho == false) {
								echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_adicionar_carrinho' id='adicionar_carrinho' class='text-success mx-1' title='Adicionar este produto a seu carrinho'><i class='fad fa-cart-plus fa-fw fa-2x'></i></a>";
							}
						}
					?>
        </div>
        <div class='py-2 text-right col-md-4 col-sm-12'>
					<?php
						if ($pagina_tipo == 'elemento') {
							$carregar_toggle_acervo = true;
							$elemento_no_acervo = $conn->query("SELECT id FROM Paginas_elementos WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND elemento_id = $pagina_item_id AND estado = 1");
							if ($elemento_no_acervo->num_rows > 0) {
								$item_no_acervo = true;
							}
							echo "
								  <a id='remover_acervo' href='javascript:void(0);' class='ml-1 text-success' title='Remover do seu acervo'>
									  <i class='fad fa-lamp-desk fa-fw'></i>
								  </a>
								  <a id='adicionar_acervo' href='javascript:void(0);' class='ml-1 text-muted' title='Adicionar a seu acervo'>
									  <i class='fad fa-lamp-desk fa-fw'></i>
								  </a>
						        ";
						}
						if ($pagina_subtipo == 'etiqueta') {
							$carregar_toggle_paginas_livres = true;
							$area_interesse = $conn->query("SELECT id FROM Paginas_elementos WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND tipo = 'topico' AND extra = $pagina_item_id AND estado = 1");
							if ($area_interesse->num_rows > 0) {
								$area_interesse_ativa = true;
							}
							echo "
						      <a id='remover_area_interesse' href='javascript:void(0);' class='ml-1 text-warning' title='Remover como áreas de interesse'>
						      	<i class='fad fa-lamp-desk fa-fw'></i>
							  </a>
						      <a id='adicionar_area_interesse' href='javascript:void(0);' class='ml-1 text-muted' title='Adicionar como áreas de interesse'>
						      	<i class='fad fa-lamp-desk fa-fw'></i>
							  </a>
						    ";
						}
						if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {
							$carregar_modal_destruir_pagina = true;
							echo "
                            <a href='javascript:void(0);' class='text-default ml-1' id='compartilhar_anotacao' title='Colaboração e publicação' data-toggle='modal' data-target='#modal_compartilhar_pagina'>
                                <i class='fad fa-user-friends fa-fw'></i>
                            </a>
                            <a href='javascript:void(0);' class='text-danger ml-1' id='destruir_pagina' title='Destruir esta página' data-toggle='modal' data-target='#modal_destruir_pagina'>
                                <i class='fad fa-shredder fa-fw'></i>
                            </a>
	                        ";
						}
						$vinculos_wikipedia = $conn->query("SELECT elemento_id, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'wikipedia'");
						if ($vinculos_wikipedia->num_rows > 0) {
							$carregar_modal_wikipedia = true;
							echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_vinculos_wikipedia' class='text-dark ml-1'><i class='fab fa-wikipedia-w fa-fw'></i></a>";
						}
						echo "<a href='javascript:void(0);' class='$notificacao_cor ml-1' data-toggle='modal' data-target='#modal_notificacoes'><i class='fad $notificacao_icone fa-fw'></i></a>";
						if (($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
							$comments = $conn->query("SELECT timestamp, comentario_text, user_id FROM Forum WHERE pagina_id = $pagina_id");
							if ($comments->num_rows == 0) {
								echo "
									<a href='forum.php?pagina_id=$pagina_id' title='Fórum' class='text-muted ml-1'>
                                    <i class='fad fa-comments-alt fa-fw'></i>
                                    </a>
                            	";
							} else {
								echo "
									<a href='forum.php?pagina_id=$pagina_id' title='Fórum' class='text-secondary ml-1'>
                                    <i class='fad fa-comments-alt fa-fw'></i>
                                    </a>
		                    	";
							}
						}
						if (($user_id != false) && ($pagina_tipo != 'sistema') && ($pagina_compartilhamento != 'escritorio')) {
							if ($etiquetados->num_rows > 0) {
								echo "
                                  <a href='javascript:void(0);' id='adicionar_etiqueta' class='ml-1 text-warning' title='Adicionar etiqueta' data-toggle='modal' data-target='#modal_secao_etiquetas'>
                                          <i class='fad fa-tags fa-fw'></i>
                                  </a>
                                ";
							} else {
								echo "
                                  <a href='javascript:void(0);' id='adicionar_etiqueta' class='ml-1 text-muted' title='Adicionar etiqueta' data-toggle='modal' data-target='#modal_gerenciar_etiquetas'>
                                          <i class='fad fa-tags fa-fw'></i>
                                  </a>
                                ";
							}
							if ($pagina_tipo == 'topico') {
								if ($estado_estudo == true) {
									$marcar_completo = 'collapse';
									$marcar_incompleto = false;
								} else {
									$marcar_completo = false;
									$marcar_incompleto = 'collapse';
								}
								echo "
                              <a id='add_completed' href='javascript:void(0);' class='text-muted ml-1 $marcar_completo' title='Estudo completo' value='$pagina_id'><i class='fad fa-check-circle fa-fw'></i></a>
                              <a id='remove_completed' href='javascript:void(0);' class='ml-1 $marcar_incompleto text-success' title='Desmarcar como completo' value='$pagina_id'><i class='fad fa-check-circle fa-fw'></i></a>
                            ";
							}
							if ($pagina_bookmark == true) {
								$marcar_bookmark = 'collapse';
								$desmarcar_bookmark = false;
							} else {
								$marcar_bookmark = false;
								$desmarcar_bookmark = 'collapse';
							}
							echo "
                              <a href='javascript:void(0);' id='add_bookmark' class='text-muted ml-1 $marcar_bookmark' title='Marcar para leitura' value='$pagina_id'><i class='fad fa-bookmark fa-fw'></i></a>
                              <a href='javascript:void(0);' id='remove_bookmark' class='text-danger ml-1 $desmarcar_bookmark' title='Remover da lista de leitura' value='$pagina_id'><i class='fad fa-bookmark fa-fw'></i></a>
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
                                <a href='javascript:void(0);' id='change_estado_pagina' class='ml-1 $estado_cor' title='Estado da página' data-toggle='modal' data-target='#modal_estado'><i class='$estado_icone fa-fw'></i></a>
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
			$template_subtitulo = "Matéria / <a href='pagina.php?pagina_id=$pagina_curso_id'>$pagina_curso_titulo</a>";
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
				$pagina_user_avatar = return_avatar($pagina_user_id);
				$pagina_user_avatar_icone = $pagina_user_avatar[0];
				$pagina_user_avatar_cor = $pagina_user_avatar[1];
				$template_subtitulo = "Escritório de <span class='$pagina_user_avatar_cor'><i class='fad $pagina_user_avatar_icone fa-fw'></i></span> $pagina_user_apelido";
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
		} elseif ($pagina_tipo == 'questao') {
			$template_titulo = "Questão $pagina_questao_numero";
			$pagina_questao_curso_titulo = return_curso_titulo_id($pagina_questao_curso_id);
			$pagina_questao_materia_titulo = return_pagina_titulo($pagina_questao_materia);
			$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_questao_materia'>$pagina_questao_materia_titulo</a> / <a href='pagina.php?curso_id=$pagina_questao_curso_id'>$pagina_questao_curso_titulo</a> / Edição de $pagina_questao_edicao_ano";
		} elseif ($pagina_tipo == 'texto_apoio') {
			$template_titulo = $pagina_texto_apoio_titulo;
			$template_subtitulo = "Texto de apoio / <a href='pagina.php?curso_id=$pagina_texto_apoio_curso_id'>$pagina_texto_apoio_curso_titulo</a>";
		}
		if ($wiki_id != false) {
			$template_subtitulo = "<a href='pagina.php?pagina_id=$pagina_id'>Retornar ao verbete da página</a>";
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
						$template_col_classes = 'd-flex justify-content-center';
						$template_botoes = false;
						$template_conteudo = "<a href='../imagens/verbetes/$elemento_arquivo' ><img class='imagem_pagina border' src='../imagens/verbetes/$elemento_arquivo'></img></a>";
						include 'templates/page_element.php';
					} elseif (($elemento_tipo == 'video') && ($elemento_iframe != false)) {
						$template_id = 'video_div';
						$template_titulo = false;
						$template_col_classes = 'd-flex justify-content-center';
						$template_botoes = false;
						$template_conteudo = $elemento_iframe;
						include 'templates/page_element.php';
					} elseif (($elemento_tipo == 'referencia') && ($elemento_link != false)) {
						$template_id = 'referencia_link';
						$template_titulo = false;
						$template_col_classes = 'd-flex justify-content-center';
						$template_conteudo = false;
						$template_conteudo_no_col = true;
						$template_conteudo .= "
					      <a href='$elemento_link' target='_blank' class='fontstack-mono'><i class='fad fa-external-link fa-fw'></i> $elemento_link</a>
					    ";
						include 'templates/page_element.php';
					}
				} elseif ($pagina_tipo == 'texto') {
					if ($privilegio_edicao == false) {
						$template_id = 'texto_privado';
						$template_titulo = $pagina_titulo;
						$template_conteudo = return_verbete_html($pagina_item_id);
						include 'templates/page_element.php';
					} else {
						$template_id = $texto_tipo;
						$template_titulo = false;
						//$template_conteudo_no_col = true;
						//$template_no_spacer = true;
						//$template_p_limit = false;
						$template_quill_initial_state = 'edicao';
						$template_quill_page_id = $texto_page_id;
						$template_quill_pagina_id = $pagina_id;
						//$template_quill_pagina_de_edicao = true;
						$template_quill_botoes = false;
						//$template_background = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					}
				}
				
				if (($pagina_tipo != 'texto') && ($pagina_tipo != 'materia') && ($pagina_tipo != 'questao') && ($pagina_tipo != 'texto_apoio')) {
					$template_id = 'verbete';
					if ($wiki_id == false) {
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
						if (
							(($pagina_compartilhamento == 'privado') && ($user_id != $pagina_user_id)) &&
							($check_compartilhamento == false)
						) {
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
					} else {
						$template_id = 'verbete_wiki';
						$template_titulo = 'Artigo da Wikipédia';
						$wiki_info = return_elemento_info($wiki_id);
						$wiki_url = $wiki_info[9];
						$wiki_conteudo = extract_wikipedia($wiki_url);
						$template_conteudo = false;
						$template_conteudo .= "<span class='strip_wikipedia'>$wiki_conteudo</span>";
						include 'templates/page_element.php';
					}
					
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
					
				}
				
				include 'pagina/etiquetas.php';
				
				if ($pagina_tipo == 'topico') {
					$list_pagina_questoes = $conn->query("SELECT elemento_id, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'questao'");
					if ($list_pagina_questoes->num_rows > 0) {
						$template_id = 'pagina_questoes';
						$template_titulo = 'Questões sobre este tópico';
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group list-group-flush'>";
						while ($list_pagina_questao = $list_pagina_questoes->fetch_assoc()) {
							$list_pagina_questao_id = $list_pagina_questao['elemento_id'];
							$list_pagina_questao_info = return_questao_info($list_pagina_questao_id);
							if ($list_pagina_questao_info == false) {
								continue;
							}
							$list_pagina_questao_origem = $list_pagina_questao_info[0];
							$list_pagina_questao_edicao_ano = $list_pagina_questao_info[2];
							$list_pagina_questao_prova_id = $list_pagina_questao_info[6];
							$list_pagina_questao_numero = $list_pagina_questao_info[7];
							$list_pagina_questao_tipo = $list_pagina_questao_info[9];
							$list_pagina_questao_prova_info = return_info_prova_id($list_pagina_questao_prova_id);
							$list_pagina_questao_prova_titulo = $list_pagina_questao_prova_info[0];
							
							$template_conteudo .= "<a href='pagina.php?questao_id=$list_pagina_questao_id' class='mt-1'><li class='list-group-item list-group-item-action border-top'>$list_pagina_questao_edicao_ano: Prova \"$list_pagina_questao_prova_titulo\", questão $list_pagina_questao_numero.</li></a>";
						}
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
					}
				}
				
				if ($pagina_tipo == 'texto_apoio') {
					$template_id = 'conteudo_texto_apoio';
					$template_titulo = false;
					$template_conteudo = false;
					if ($pagina_texto_apoio_html != false) {
						$template_conteudo .= "
							<h2 class='h2-responsive d-flex justify-content-center'>$pagina_texto_apoio_titulo</h2>
                            <div class='special-li'>
                              $pagina_texto_apoio_enunciado_html
                              $pagina_texto_apoio_html
                            </div>
			            ";
					} else {
						$template_conteudo .= "<p class='text-muted'><em>O conteúdo deste texto de apoio ainda não foi adicionado.</em></p>";
					}
					include 'templates/page_element.php';
				}
				
				if (($pagina_tipo == 'questao') && ($pagina_questao_enunciado_html != false)) {
					
					if ($pagina_questao_texto_apoio == 1) {
						$template_id = 'questao_texto_apoio';
						$template_titulo = "Texto de apoio";
						if ($pagina_questao_texto_apoio_pagina_id != false) {
							$template_botoes = "
							<a href='pagina.php?pagina_id=$pagina_questao_texto_apoio_pagina_id' title='Página deste texto de apoio' class='text-secondary'><li class='fad fa-external-link-square fa-fw'></li></a>
							";
						}
						$template_conteudo = false;
						$template_conteudo .= "<h3 class='h3-responsive'>$pagina_questao_texto_apoio_titulo</h3>";
						if ($pagina_questao_texto_apoio_id == false) {
							$template_conteudo .= "<p class='text-muted'><em>Esta questão depende de texto de apoio, mas seu texto de apoio ainda não foi identificado ou criado. Você poderá fazê-lo nas configurações da questão (canto superior esquerdo desta página).</em></p>";
						} else {
							if ($pagina_questao_texto_apoio_html != false) {
								$template_conteudo .= "
									<div class='special-li'>
									$pagina_questao_texto_apoio_enunciado_html
									$pagina_questao_texto_apoio_html
									</div>
								";
							} else {
								$template_conteudo .= "<p class='text-muted'><em>O conteúdo deste texto de apoio ainda não foi adicionado. Você poderá fazê-lo na <a href='pagina.php?pagina_id=$pagina_questao_texto_apoio_pagina_id'>na página deste texto de apoio</a>.</em></p>";
							}
						}
						include 'templates/page_element.php';
					}
					
					$template_id = 'gabarito_questao';
					if (($pagina_questao_tipo == 1) || ($pagina_questao_tipo == 2)) {
						$gabarito = true;
						$template_botoes = "
                                <span id='mostrar_gabarito' title='Mostrar gabarito'>
                                    <a href='javascript:void(0);' class='text-primary'><i class='fad fa-eye fa-fw'></i></a>
                                </span>
                            ";
						$template_titulo = 'Itens e gabarito';
					} else {
						$template_botoes = false;
						$template_titulo = 'Enunciado';
					}
					$template_conteudo = false;
					$template_conteudo .= "<div id='special_li'>$pagina_questao_enunciado_html</div>";
					$template_conteudo .= "<ul class='list-group'>";
					$mask_cor = 'list-group-item-light';
					if ($pagina_questao_item1_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item1_gabarito);
						$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>Item 1</strong></li>
							";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $pagina_questao_item1_html
                                </li>";
					}
					if ($pagina_questao_item2_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item2_gabarito);
						$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>Item 2</strong></li>
							";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $pagina_questao_item2_html
                                </li>";
					}
					if ($pagina_questao_item3_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item3_gabarito);
						$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>Item 3</strong></li>
							";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $pagina_questao_item3_html
                                </li>";
					}
					if ($pagina_questao_item4_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item4_gabarito);
						$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>Item 4</strong></li>
							";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $pagina_questao_item4_html
                                </li>";
					}
					if ($pagina_questao_item5_html != false) {
						$gabarito_cor = convert_gabarito_cor($pagina_questao_item5_gabarito);
						$template_conteudo .= "
							    <li class='list-group-item list-group-item-secondary py-1 mt-2 mb-1 d-flex justify-content-center'><strong>Item 5</strong></li>
							";
						$template_conteudo .= "<li class='list-group-item $gabarito_cor $mask_cor'>
                                    $pagina_questao_item5_html
                                </li>";
					}
					
					$template_conteudo .= "</ul>";
					include 'templates/page_element.php';
				}
				
				echo "</div>";
			?>
			<?php
				if (($pagina_tipo != 'sistema') && ($pagina_tipo != 'texto') && ($pagina_compartilhamento != 'escritorio') && ($pagina_tipo != 'resposta') && ($pagina_tipo != 'materia') && ($pagina_tipo != 'curso')) {
					include 'pagina/coluna_direita_anotacoes.php';
					$carregar_quill_anotacoes = true;
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
	echo "<a id='mostrar_coluna_direita' class='text-light rgba-black-strong rounded m-1 p-1' tabindex='-1' title='Notas privadas'><i class='fas fa-pen-alt fa-fw'></i></a>";
?>
</div>

<?php
	if ($pagina_tipo == 'topico') {
		$template_modal_div_id = 'modal_verbetes_relacionados';
		$template_modal_titulo = 'Navegação';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = $breadcrumbs;
		include 'templates/modal.php';
	}
	
	$carregar_controle_estado = true;
	$template_modal_div_id = 'modal_estado';
	$template_modal_titulo = 'Qualidade da página';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = "
        <p>Qual das categorias abaixo melhor descreve o estado atual desta página?</p>
        <input type='hidden' value='$pagina_estado' name='novo_estado_pagina' id='novo_estado_pagina'>
        <div class='row justify-content-around'>";
	
	$artefato_tipo = 'estado_rascunho';
	$artefato_titulo = 'Rascunho';
	$fa_icone = 'fa-acorn';
	$fa_color = 'text-info';
	if ($pagina_estado == 1) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-cyan-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'estado_aceitavel';
	$artefato_titulo = 'Aceitável';
	$fa_icone = 'fa-seedling';
	$fa_color = 'text-danger';
	if ($pagina_estado == 2) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-red-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'estado_desejavel';
	$artefato_titulo = 'Desejável';
	$fa_icone = 'fa-leaf';
	$fa_color = 'text-success';
	if ($pagina_estado == 3) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-green-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'estado_excepcional';
	$artefato_titulo = 'Excepcional';
	$fa_icone = 'fa-spa';
	$fa_color = 'text-warning';
	if ($pagina_estado == 4) {
		$fa_color = 'text-white';
		$artefato_icone_background = 'rgba-orange-strong';
	}
	$artefato_class = 'artefato_opcao_estado';
	$artefato_col_limit = 'col-3';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "
		</div>
    ";
	include 'templates/modal.php';
	
	include 'pagina/youtube.php';
	
	if ($pagina_tipo == 'elemento') {
		include 'pagina/modals_elemento.php';
	}
	
	if (($carregar_secoes == true) && ($privilegio_edicao == true)) {
		$template_modal_div_id = 'modal_partes_form';
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$template_modal_titulo = 'Adicionar episódio';
			} elseif ($elemento_subtipo == 'livro') {
				$template_modal_titulo = 'Adicionar capítulo';
			}
		}
		if (!isset($template_modal_titulo)) {
			$template_modal_titulo = 'Adicionar seção';
		}
		$template_modal_submit_name = 'trigger_nova_secao';
		$modal_scrollable = true;
		$template_modal_body_conteudo = false;
		$secoes_sem_texto = true;
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$template_modal_body_conteudo .= "
					<p>Adicione episódios abaixo de acordo com seu número.</p>
				";
				$secoes_sem_texto = false;
			} else {
				$template_modal_body_conteudo .= "
		        <p>Por favor, tome cuidado para que não haja duplicidade. Se possível, é preferencial que todas as seções sejam acrescentadas na ordem em que aparecem na edição de que você dispõe. Ao inserir a ordem da nova seção, por favor considere Introdução, Prefácio etc. Se houver mais de um prefácio, considere a possibilidade de consolidá-los em apenas uma seção, por exemplo, no caso de prefácios a edições que somente mencionam adições ou correções realizadas. Seções de agradecimento, caso nada incluam de especialmente relevante, podem ser ignoradas, assim como tabelas de referência, listas de anexos, glossários e seções afim.</p>
		        <p>Exemplos de seções adequadas: \"Capítulo 1\", \"Capítulo 2: Título do Capítulo\", \"Parte 1: As Origens\", \"Introdução\".</p>
		        <p>É possível determinar a ordem como \"0\". É preferível usar essa opção para elementos anteriores ao primeiro capítulo, como Introdução e Prefácio, pois dessa forma o primeiro capítulo terá a ordem \"1\", o segundo a ordem \"2\" etc.</p>
		        $secoes_sem_texto = false;
	          ";
			}
		} else {
			$secoes_sem_texto = true;
		}
		if ($secoes_sem_texto == true) {
			$template_modal_body_conteudo .= "
				<p>Você pode criar seções de sua página, mas recomenda-se cuidado para que não haja duplicidade. Preferencialmente, as seções devem ser adicionadas na ordem final de sua preferência. Nesse caso, é possível ignorar o campo 'ordem' completamente.</p>
			";
		}
		if ($pagina_tipo == 'elemento') {
			if ($elemento_subtipo == 'podcast') {
				$nova_secao_titulo = 'Título do episódio';
				$nova_secao_numero = 'Número do episódio';
			} elseif ($elemento_subtipo == 'livro') {
				$nova_secao_titulo = 'Título do capítulo';
				$nova_secao_numero = 'Ordem do capítulo';
			}
		}
		if (!isset($nova_secao_titulo)) {
			$nova_secao_titulo = 'Título da nova seção';
			$nova_secao_numero = 'Posição da nova seção';
		}
		$template_modal_body_conteudo .= "
          <div class='md-form mb-2'>
              <input type='text' id='elemento_nova_secao' name='elemento_nova_secao' class='form-control'>
              <label for='elemento_nova_secao'>$nova_secao_titulo</label>
          </div>
          <div class='md-form mb-2'>
              <input type='number' id='elemento_nova_secao_ordem' name='elemento_nova_secao_ordem' class='form-control'>
              <label for='elemento_nova_secao_ordem'>$nova_secao_numero</label>
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
	
	if ($privilegio_edicao == true) {
		include 'pagina/modal_add_elemento.php';
		include 'pagina/modal_adicionar_imagem.php';
		if ($pagina_tipo == 'topico') {
			include 'pagina/modals_questoes.php';
		}
	}
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
				$template_modal_body_conteudo .= "
		        <span data-toggle='modal' data-target='#modal_pagina_dados'>
                    <div class='row justify-content-center'>
                        <button type='button' class='$button_classes btn-info' data-toggle='modal' data-target='#modal_novo_curso'>Transformar em página inicial de curso</button>
                    </div>
                </span>
		    ";
			}
		}
		include 'templates/modal.php';
	}
	if ($privilegio_edicao == true) {
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
	}
	if (($pagina_subtipo == 'produto') && ($carregar_produto_setup == true) && ($privilegio_edicao == true) && (isset($imagem_opcoes))) {
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
		$template_modal_body_conteudo .= "<ul class='list-group list-group-flush'>";
		$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$pagina_item_id'><li class='list-group-item list-group-item-action list-group-item-primary'>$pagina_original_titulo</li></a>";
		$parentes = $conn->query("SELECT id FROM Paginas WHERE tipo = 'secao' AND item_id = $pagina_item_id");
		if ($parentes->num_rows > 0) {
			while ($parente = $parentes->fetch_assoc()) {
				$parente_id = $parente['id'];
				$parente_titulo = return_pagina_titulo($parente_id);
				$template_modal_body_conteudo .= "<a href='pagina.php?pagina_id=$parente_id' class='mt-1'><li class='list-group-item list-group-item-action border-top'>$parente_titulo</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}
	
	if ($pagina_tipo == 'texto') {
		include 'pagina/modals_texto.php';
	}
	
	if (($pagina_compartilhamento == 'privado') && ($pagina_user_id == $user_id)) {
		
		$template_modal_div_id = 'modal_compartilhar_pagina';
		$template_modal_titulo = 'Colaboração e acesso';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<form method='post' id='form_modal_compartilhar_pagina'>
			<h3>Acesso</h3>
	    ";
		if (isset($_POST['radio_publicar_opcao'])) {
			$radio_publicar_opcao = $_POST['radio_publicar_opcao'];
			$query_cmd = "INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('publicacao', $user_id, $pagina_id, '$pagina_tipo', '$radio_publicar_opcao', NULL)";
			$conn->query($query_cmd);
			$pagina_publicacao = $radio_publicar_opcao;
		}
		
		$radio_privado = false;
		$radio_ubwiki = false;
		$radio_internet = false;
		$radio_active = 'checked';
		if ($pagina_publicacao == 'privado') {
			$radio_privado = $radio_active;
		} elseif ($pagina_publicacao == 'ubwiki') {
			$radio_ubwiki = $radio_active;
			$esconder_botao_determinar_acesso = true;
		} elseif ($pagina_publicacao == 'internet') {
			$radio_internet = $radio_active;
		}
		
		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input radio_publicar_opcao' name='radio_publicar_opcao' id='checkbox_publicar_ubwiki' value='ubwiki' $radio_ubwiki>
				<label class='form-check-label' for='checkbox_publicar_ubwiki'>Acesso livre a usuários da Ubwiki.</label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input radio_publicar_opcao' name='radio_publicar_opcao' id='checkbox_publicar_privado' value='privado' $radio_privado>
				<label class='form-check-label' for='checkbox_publicar_privado'>Seletivo. <span class='text-muted'><em>Você determina quem tem acesso</em></span>.</label>
			</div>
			<div id='botao_determinar_acesso' class='row d-flex justify-content-center botao_determinar_acesso'>
				<span data-toggle='modal' data-target='#modal_compartilhar_pagina'><a data-toggle='modal' data-target='#modal_outorgar_acesso'><button class='$button_classes botao_determinar_acesso btn-info' type='button'>Outorgar acesso</button></a></span>
			</div>
			<!--<div class='form-check'>
				<input type='radio' class='form-check-input' name='radio_publicar_opcao' id='checkbox_publicar_geral' value='internet' $radio_internet>
				<label class='form-check-label' for='checkbox_publicar_geral'>Publicado na Internet.</label>
			</div>-->
			";
		$template_modal_body_conteudo .= "<h3 class='mt-3'>Colaboração</h3>";
		if (isset($_POST['colaboracao_opcao'])) {
			$colaboracao_opcao = $_POST['colaboracao_opcao'];
			$conn->query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento) VALUES ('colaboracao', $user_id, $pagina_id, '$pagina_tipo', '$colaboracao_opcao')");
			$pagina_colaboracao = $colaboracao_opcao;
		}
		
		$radio_colaboracao_exclusiva = false;
		$radio_colaboracao_aberta = false;
		$radio_colaboracao_selecionada = false;
		$radio_colaboracao_active = 'checked';
		if ($pagina_colaboracao == 'exclusiva') {
			$radio_colaboracao_exclusiva = $radio_colaboracao_active;
		} elseif ($pagina_colaboracao == 'aberta') {
			$radio_colaboracao_aberta = $radio_colaboracao_active;
		} elseif ($pagina_colaboracao == 'selecionada') {
			$radio_colaboracao_selecionada = $radio_colaboracao_active;
		}
		
		$template_modal_body_conteudo .= "
			<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_aberta' value='aberta' $radio_colaboracao_aberta>
				<label class='form-check-label' for='colaboracao_aberta'>Livre. <span class='text-muted'><em>Todos os grupos e indivíduos com acesso a esta página poderão editá-la.</em></span></label>
			</div>
			<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_exclusiva' value='exclusiva' $radio_colaboracao_exclusiva>
				<label class='form-check-label' for='colaboracao_exclusiva'>Autoral. <span class='text-muted'><em>Apenas você poderá editar o conteúdo desta página.</em></span></label>
			</div>
			<!--<div class='form-check'>
				<input type='radio' class='form-check-input colaboracao_opcao' name='colaboracao_opcao' id='colaboracao_selecionada' value='selecionada' $radio_colaboracao_selecionada>
				<label class='form-check-label' for='colaboracao_selecionada'>Seletiva. <span class='text-muted'><em>Apenas grupos e indivíduos selecionados poderão editar o conteúdo desta página.</em></span></label>
			</div>
			<div class='row d-flex justify-content-center botao_determinar_colaboracao'>
				<span data-toggle='modal' data-target='#modal_compartilhar_pagina'><a data-toggle='modal' data-target='#modal_determinar_colaboracao'><button class='$button_classes botao_determinar_colaboracao btn-info'>Adicionar colaboradores</button></a></span>
			</div>-->
		";
		
		$template_modal_body_conteudo .= "</form>";
		
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_outorgar_acesso';
		$template_modal_titulo = 'Outorgar acesso';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		
		$template_modal_body_conteudo .= "
        <p class='detalhes_acesso'>Adicione pessoas e grupos de estudo abaixo para que tenham acesso à sua página. Apenas você, como criador original desta página, poderá alterar suas opções de compartilhamento.</p>
        <span id='esconder_modal_compartilhar_pagina' data-toggle='modal' data-target='#modal_outorgar_acesso' class='row justify-content-center detalhes_acesso'>";
		
		$artefato_tipo = 'compartilhar_grupo';
		$artefato_titulo = 'Grupo de estudos';
		$artefato_link = false;
		$artefato_criacao = false;
		$fa_size = 'fa-3x';
		$artefato_col_limit = 'col-lg-4 col-md-5';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_tipo = 'compartilhar_usuario';
		$artefato_titulo = 'Colega';
		$artefato_link = false;
		$artefato_criacao = false;
		$fa_size = 'fa-3x';
		$artefato_col_limit = 'col-lg-4 col-md-5';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$template_modal_body_conteudo .= "</span>";
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_compartilhar_grupo';
		$template_modal_titulo = 'Outorgar acesso a grupos de estudos';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			  <h3>Grupos de estudos de que você faz parte</h3>
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
                        <button class='$button_classes mt-3' name='trigger_compartilhar_grupo'>Compartilhar com grupo</button>
                    </div>
                  </form>
                ";
		} else {
			$template_modal_body_conteudo .= "<p class='text-muted'><em>Você não faz parte de nenhum grupo de estudos. Visite seu escritório para participar.</em></p>";
		}
		$comp_grupos = $conn->query("SELECT recipiente_id FROM Compartilhamento WHERE item_id = $pagina_id AND compartilhamento = 'grupo'");
		if ($comp_grupos->num_rows > 0) {
			$template_modal_body_conteudo .= "<h3 class='mt-5'>Grupos de estudos com acesso:</h3>";
			$template_modal_body_conteudo .= "<ul class='list-group'>";
			while ($comp_grupo = $comp_grupos->fetch_assoc()) {
				$comp_grupo_id = $comp_grupo['recipiente_id'];
				$comp_grupo_info = return_grupo_info($comp_grupo_id);
				$comp_grupo_titulo = $comp_grupo_info[1];
				$comp_grupo_pagina_id = $comp_grupo_info[3];
				$template_modal_body_conteudo .= "<a href='javascript:void(0)' class='mt-1 remover_acesso_grupo' value='$comp_grupo_id'><li class='list-group-item list-group-item-info list-group-item-action'>$comp_grupo_titulo</li></a>";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
		include 'templates/modal.php';
		
		if (isset($_POST['compartilhar_usuario'])) {
			$compartilhar_usuario = $_POST['compartilhar_usuario'];
		}
		
		$bottom_compartilhar_usuario = true;
		
		$template_modal_div_id = 'modal_compartilhar_usuario';
		$template_modal_titulo = 'Colaborar com usuário da Ubwiki';
		$modal_scrollable = true;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p><strong>Colaboradores adicionados abaixo poderão alterar o conteúdo de sua página.</strong></p>
		";
		$template_modal_body_conteudo .= "
			<p>Pesquise o convidado por seu apelido:</p>
	        <div class='md-form'>
	        	<input type='text' class='form-control' id='apelido_convidado_compartilhamento' novo='apelido_convidado_compartilhamento'>
	        	<label for='apelido_convidado_compartilhamento'>Apelido</label>
	        </div>
	        <div class='md-form my-1'>
	            <button type='button' id='trigger_buscar_convidado_compartilhamento' name='trigger_buscar_convidado_compartilhamento' class='$button_classes btn-sm m-0'>Buscar</button>
            </div>
            <div id='convite_resultados_compartilhamento' class='row border p-2 rounded mt-4'>
			</div>
		";
		$template_modal_body_conteudo .= "
		    <p class='mt-3'>Atualmente compartilhado com os usuários listados abaixo:</p>
		    <p><strong>Pressione para remover.</strong></p>
		    <ul class='list-group list-group-flush'>
		";
		$usuarios_comp = $conn->query("SELECT recipiente_id FROM Compartilhamento WHERE estado = 1 AND item_id = $pagina_id AND compartilhamento = 'usuario'");
		if ($usuarios_comp->num_rows > 0) {
			while ($usuario_comp = $usuarios_comp->fetch_assoc()) {
				$usuario_comp_id = $usuario_comp['recipiente_id'];
				$usuario_comp_avatar_info = return_avatar($usuario_comp_id);
				$usuario_comp_avatar_icone = $usuario_comp_avatar_info[0];
				$usuario_comp_avatar_cor = $usuario_comp_avatar_info[1];
				$usuario_comp_apelido = return_apelido_user_id($usuario_comp_id);
				$template_modal_body_conteudo .= "<a href='javascript:void(0)' class='remover_compartilhamento_usuario' value='$usuario_comp_id'><li class='list-group-item list-group-item-action mt-1 border-top'><span class='$usuario_comp_avatar_cor'><i class='fad $usuario_comp_avatar_icone fa-fw'></i></span> $usuario_comp_apelido</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
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
		
		$template_modal_div_id = 'modal_convidar_ou_remover';
		$template_modal_titulo = 'Gerenciar membros';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		
		$template_modal_body_conteudo .= "<span class='row d-flex justify-content-around' data-target='#modal_convidar_ou_remover' data-toggle='modal'>";
		
		$artefato_titulo = 'Convidar novos membros';
		$fa_color = 'text-success';
		$fa_icone = 'fa-user-plus';
		$artefato_modal = '#modal_novo_membro';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$artefato_titulo = 'Remover membros';
		$fa_color = 'text-danger';
		$fa_icone = 'fa-user-minus';
		$artefato_modal = '#modal_remover_membro';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		
		$template_modal_body_conteudo .= "</span>";
		
		include 'templates/modal.php';
		
		$template_modal_div_id = 'modal_novo_membro';
		$template_modal_titulo = 'Convidar novo membro';
		$template_modal_show_buttons = false;
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
		
		$carregar_remover_usuarios = true;
		$template_modal_div_id = 'modal_remover_membro';
		$template_modal_titulo = 'Remover membro deste grupo';
		$modal_scrollable = true;
		$template_modal_body_conteudo = false;
		$template_modal_show_buttons = false;
		if ($membros->num_rows > 1) {
			$template_modal_body_conteudo .= "
				<p class='mt-3'>Selecione um membro abaixo para removê-lo do grupo ou cancelar seu convite.</p>
				<ul class='list-group list-group-flush'>
				<input type='hidden' id='remover_membro_grupo_id' value='$pagina_item_id'>
			";
			mysqli_data_seek($membros, 0);
			while ($membro = $membros->fetch_assoc()) {
				$membro_user_id = $membro['membro_user_id'];
				if ($membro_user_id == $user_id) {
					continue;
				}
				$membro_estado = $membro['estado'];
				$membro_user_apelido = return_apelido_user_id($membro_user_id);
				$avatar_info = return_avatar($membro_user_id);
				$fa_icone = $avatar_info[0];
				$fa_color = $avatar_info[1];
				$template_modal_body_conteudo .= "
					<a href='javascript:void(0);' class='remover_membro_grupo' value='$membro_user_id'><li class='list-group-item list-group-item-action border-0 border-top'><span class='$fa_color'><i class='fad $fa_icone'></i></span> $membro_user_apelido</li></a>
				";
			}
			$template_modal_body_conteudo .= "</ul>";
		}
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
            <div id='searchDiv' class='mt-5'>
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
		$template_modal_body_conteudo .= "
            <form id='searchform' action='' method='post'>
            <div id='searchDiv_geral' class='mt-5'>
                <input id='barra_busca_geral' type='text' class='barra_busca w-100' name='searchBar_geral' rows='1' autocomplete='off' spellcheck='false' placeholder='Busca geral' required>";
		$template_modal_body_conteudo .= "<input id='trigger_busca_geral' name='trigger_busca' value='$curso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
		$template_modal_body_conteudo .= "
        	</div>
        </form>";
		include 'templates/modal.php';
	}
	
	if ($carregar_modal_wikipedia == true) {
		$template_modal_div_id = 'modal_vinculos_wikipedia';
		$template_modal_titulo = 'Verbetes da Wikipédia relacionados';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
		<p>Pressione para carregar artigo da Wikipédia:</p>
		<ul class='list-group list-group-flush'>
	";
		while ($vinculo_wikipedia = $vinculos_wikipedia->fetch_assoc()) {
			$vinculo_wikipedia_elemento_id = $vinculo_wikipedia['elemento_id'];
			$vinculo_wikipedia_titulo = $vinculo_wikipedia['extra'];
			$template_modal_body_conteudo .= "<a class='list-group-item list-group-item-action mt-1 border-top' href='pagina.php?pagina_id=$pagina_id&wiki_id=$vinculo_wikipedia_elemento_id'>$vinculo_wikipedia_titulo</a>";
		}
		$template_modal_body_conteudo .= "</ul>";
		include 'templates/modal.php';
	}
	
	if ($carregar_modal_destruir_pagina == true) {
		$template_modal_div_id = 'modal_destruir_pagina';
		$template_modal_titulo = 'Destruir esta página';
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<p>Pressione para destruir esta página. Esse ato não pode ser desfeito.</p>
			<form method='post'>
			  <div class='md-form d-flex justify-content-center'>
				  <button class='$button_classes_red' name='trigger_apagar_pagina' id='trigger_apagar_pagina'>Apagar página</button>
			  </div>
			</form>
		";
		include 'templates/modal.php';
	}
	
	if ($pagina_tipo == 'texto_apoio') {
		$template_modal_div_id = 'modal_texto_apoio_dados';
		$template_modal_titulo = 'Dados deste texto de apoio';
		$template_modal_form_id = 'form_texto_apoio_alterar_dados';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
			<ul class='list-grou mb-3 w-responsive m-auto pb-3'>
				<li class='list-group-item list-group-item-info d-flex justify-content-center'>Dados fixos</li>
				<li class='list-group-item list-group-item-light'><strong>Concurso:</strong> $pagina_texto_apoio_curso_titulo</li>
				<li class='list-group-item list-group-item-light'><strong>Edição:</strong> $pagina_texto_apoio_edicao_ano</li>
				<li class='list-group-item list-group-item-light'><strong>Etapa:</strong> $pagina_texto_apoio_etapa_titulo</li>
				<li class='list-group-item list-group-item-light'><strong>Prova:</strong> $pagina_texto_apoio_prova_titulo</li>
		";
		if ($pagina_texto_apoio_origem == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Origem:</strong> Oficial do concurso.</li>";
		} elseif ($pagina_texto_apoio_origem == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Origem:</strong> Não-oficial.</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
		$template_modal_body_conteudo .= "
							<h3>Título</h3>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' value='$pagina_texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
		
		$template_modal_form_id = 'form_novo_texto_apoio';
		$template_modal_body_conteudo .= "<h3 class='text-center'>Enunciado:</h3>";
		$sim_quill_id = 'texto_apoio_enunciado';
		$sim_quill_form = include('templates/sim_quill.php');
		$template_modal_body_conteudo .= $sim_quill_form;
		
		$template_modal_body_conteudo .= "
			<h3 class='text-center mt-3'>Texto</h3>
			<p>Textos com linhas numeradas devem ser adicionados linha por linha, em uma lista numerada.</p>
	    ";
		$sim_quill_id = 'texto_apoio';
		$sim_quill_form = include('templates/sim_quill.php');
		$template_modal_body_conteudo .= $sim_quill_form;
		
		$template_modal_submit_name = 'novo_texto_apoio_trigger';
		include 'templates/modal.php';
	}
	
	if ($pagina_tipo == 'questao') {
		$template_modal_div_id = 'modal_questao_dados';
		$template_modal_titulo = 'Dados desta questão';
		$template_modal_form_id = 'form_questao_alterar_dados';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <ul class='list-group mb-3 w-responsive m-auto pb-3'>
                <li class='list-group-item list-group-item-info d-flex justify-content-center'>Dados fixos</li>
                <li class='list-group-item list-group-item-light'><strong>Concurso:</strong> $pagina_questao_curso_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>Edição:</strong> $pagina_questao_edicao_ano.</li>
                <li class='list-group-item list-group-item-light'><strong>Etapa:</strong> $pagina_questao_etapa_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>Prova:</strong> $pagina_questao_prova_titulo.</li>
                <li class='list-group-item list-group-item-light'><strong>Matéria:</strong> <a href='pagina.php?pagina_id=$pagina_questao_materia'>$pagina_questao_materia_titulo</a></li>
        ";
		
		$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Número:</strong> $pagina_questao_numero.</li>";
		if ($pagina_questao_origem == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Origem:</strong> Oficial do concurso.</li>";
		} elseif ($pagina_questao_origem == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Tipo:</strong> Não-oficial.</li>";
		}
		if ($pagina_questao_texto_apoio == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Texto de apoio:</strong> sim.</li>";
		} elseif ($pagina_questao_texto_apoio == 0) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Texto de apoio:</strong> não.</li>";
		}
		if ($pagina_questao_tipo == 1) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Tipo:</strong> \"certo ou errado\"</li>";
		} elseif ($pagina_questao_tipo == 2) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Tipo:</strong> múltipla escolha</li>";
		} elseif ($pagina_questao_tipo == 3) {
			$template_modal_body_conteudo .= "<li class='list-group-item list-group-item-light'><strong>Tipo:</strong> dissertativa.</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
		
		if ($pagina_questao_texto_apoio_id == false) {
			$selected = 'selected';
		} else {
			$selected = false;
		}
		
		if ($pagina_questao_texto_apoio == 1) {
			$template_modal_body_conteudo .= "
					<h3>Texto de apoio</h3>
					<select class='$select_classes' name='nova_questao_texto_de_apoio_id'>
					<option value='' disabled $selected>Selecione o texto de apoio:</option>";
			if ($pagina_questao_texto_apoio_id == false) {
				$template_modal_body_conteudo .= "
					<option value='novo'>Texto de apoio não-registrado</option>
				";
			}
			$textos_apoio = $conn->query("SELECT id, origem, titulo FROM sim_textos_apoio WHERE prova_id = $pagina_questao_prova_id");
			if ($textos_apoio->num_rows > 0) {
				$selected = false;
				while ($texto_apoio = $textos_apoio->fetch_assoc()) {
					$texto_apoio_id = $texto_apoio['id'];
					$texto_apoio_origem = $texto_apoio['origem'];
					if ($texto_apoio_origem == 1) {
						$texto_apoio_origem_string = '(oficial)';
					} elseif ($texto_apoio_origem == 0) {
						$texto_apoio_origem_string = '(não-oficial)';
					}
					if ($texto_apoio_id == $pagina_questao_texto_apoio_id) {
						$selected = 'selected';
					} else {
						false;
					}
					$texto_apoio_titulo = $texto_apoio['titulo'];
					$template_modal_body_conteudo .= "<option value='$texto_apoio_id' $selected>$texto_apoio_titulo $texto_apoio_origem_string</option>";
				}
			}
		}
		$template_modal_body_conteudo .= "</select>";
		
		$template_modal_body_conteudo .= "<h3 class='mt-3'>Enunciado</h3>";
		$sim_quill_id = 'questao_enunciado';
		$sim_quill_form = include 'templates/sim_quill.php';
		$template_modal_body_conteudo .= $sim_quill_form;
		if (($pagina_questao_tipo == 1) || ($pagina_questao_tipo == 2)) {
			
			$item1_certo = false;
			$item1_errado = false;
			$item1_anulado = false;
			$item1_none = false;
			$item2_certo = false;
			$item2_errado = false;
			$item2_anulado = false;
			$item2_none = false;
			$item3_certo = false;
			$item3_errado = false;
			$item3_anulado = false;
			$item3_none = false;
			$item4_certo = false;
			$item4_errado = false;
			$item4_anulado = false;
			$item4_none = false;
			$item5_certo = false;
			$item5_errado = false;
			$item5_anulado = false;
			$item5_none = false;
			
			if ($pagina_questao_item1_gabarito == 1) {
				$item1_certo = 'selected';
			} elseif ($pagina_questao_item1_gabarito == 2) {
				$item1_errado = 'selected';
			} elseif ($pagina_questao_item1_gabarito == 3) {
				$item1_anulado = 'selected';
			} else {
				$item1_none = 'selected';
			}
			if ($pagina_questao_item2_gabarito == 1) {
				$item2_certo = 'selected';
			} elseif ($pagina_questao_item2_gabarito == 2) {
				$item2_errado = 'selected';
			} elseif ($pagina_questao_item2_gabarito == 3) {
				$item2_anulado = 'selected';
			} else {
				$item2_none = 'selected';
			}
			if ($pagina_questao_item3_gabarito == 1) {
				$item3_certo = 'selected';
			} elseif ($pagina_questao_item3_gabarito == 2) {
				$item3_errado = 'selected';
			} elseif ($pagina_questao_item3_gabarito == 3) {
				$item3_anulado = 'selected';
			} else {
				$item3_none = 'selected';
			}
			if ($pagina_questao_item4_gabarito == 1) {
				$item4_certo = 'selected';
			} elseif ($pagina_questao_item4_gabarito == 2) {
				$item4_errado = 'selected';
			} elseif ($pagina_questao_item4_gabarito == 3) {
				$item4_anulado = 'selected';
			} else {
				$item4_none = 'selected';
			}
			if ($pagina_questao_item5_gabarito == 1) {
				$item5_certo = 'selected';
			} elseif ($pagina_questao_item5_gabarito == 2) {
				$item5_errado = 'selected';
			} elseif ($pagina_questao_item5_gabarito == 3) {
				$item5_anulado = 'selected';
			} else {
				$item5_none = 'selected';
			}
			
			$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 1</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled $item1_none>Selecione o gabarito do primeiro item</option>
                                <option value='1' $item1_certo>Certo</option>
                                <option value='2' $item1_errado>Errado</option>
                                <option value='3' $item1_anulado>Anulado</option>
                            </select>
						";
			$sim_quill_id = 'questao_item1';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 2</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled $item2_none>Selecione o gabarito do segundo item</option>
                                <option value='1' $item2_certo>Certo</option>
                                <option value='2' $item2_errado>Errado</option>
                                <option value='3' $item2_anulado>Anulado</option>
                            </select>
						";
			$sim_quill_id = 'questao_item2';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 3</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled $item3_none>Selecione o gabarito do terceiro item</option>
                                <option value='1' $item3_certo>Certo</option>
                                <option value='2' $item3_errado>Errado</option>
                                <option value='3' $item3_anulado>Anulado</option>
                            </select>
						";
			$sim_quill_id = 'questao_item3';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 4</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled $item4_none>Selecione o gabarito do quarto item</option>
                                <option value='1' $item4_certo>Certo</option>
                                <option value='2' $item4_errado>Errado</option>
                                <option value='3' $item4_anulado>Anulado</option>
                            </select>
						";
			$sim_quill_id = 'questao_item4';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
			$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 5</h3>";
			$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled $item5_none>Selecione o gabarito do quinto item</option>
                                <option value='1' $item5_certo>Certo</option>
                                <option value='2' $item5_errado>Errado</option>
                                <option value='3' $item5_anulado>Anulado</option>
                            </select>
						";
			$sim_quill_id = 'questao_item5';
			$sim_quill_form = include('templates/sim_quill.php');
			$template_modal_body_conteudo .= $sim_quill_form;
		}
		include 'templates/modal.php';
	}
	
	include 'pagina/modal_notificacoes.php';

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
	
	if ($pagina_tipo == 'questao') {
		echo "
        <script type='text/javascript'>
            quill_questao_enunciado.setContents($pagina_questao_enunciado_content);
            quill_questao_item1.setContents($pagina_questao_item1_content);
            quill_questao_item2.setContents($pagina_questao_item2_content);
            quill_questao_item3.setContents($pagina_questao_item3_content);
            quill_questao_item4.setContents($pagina_questao_item4_content);
            quill_questao_item5.setContents($pagina_questao_item5_content);
        </script>
    ";
	} elseif ($pagina_tipo == 'texto_apoio') {
		echo "
        <script type='text/javascript'>
            quill_texto_apoio_enunciado.setContents($pagina_texto_apoio_enunciado_content);
            quill_texto_apoio.setContents($pagina_texto_apoio_content);
        </script>
    	";
	}
	
	include 'templates/esconder_anotacoes.php';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
?>

</html>