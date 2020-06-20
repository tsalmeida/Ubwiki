<?php

    //TODO: Re-criar a plataforma de simulados

	include 'engine.php';
	
	if ($user_tipo != 'admin') {
	    header('Location:pagina.php?pagina_id=3');
    }
	
	$pagina_tipo = 'simulados';
	
	if (isset($_GET['curso_id'])) {
		$pagina_id = $_GET['curso_id'];
		$pagina_curso_info = return_curso_info($pagina_id);
		$pagina_curso_id = $pagina_id;
		$pagina_curso_sigla = $pagina_curso_info[2];
		$pagina_curso_titulo = $pagina_curso_info[3];
		$pagina_curso_pagina_id = $pagina_curso_info[0];
		$pagina_curso_user_id = $pagina_curso_info[4];
	} else {
		header('Location:pagina.php?pagina_id=3');
	}
	
	if (isset($_POST['nova_edicao_trigger'])) {
		if (isset($_POST['nova_edicao_ano'])) {
			$nova_edicao_ano = $_POST['nova_edicao_ano'];
		}
		if (isset($_POST['nova_edicao_titulo'])) {
			$nova_edicao_titulo = $_POST['nova_edicao_titulo'];
			$nova_edicao_titulo = mysqli_real_escape_string($conn, $nova_edicao_titulo);
		}
		if (($nova_edicao_ano != false) && ($nova_edicao_titulo != false)) {
			$conn->query("INSERT INTO sim_edicoes (curso_id, ano, titulo, user_id) VALUES ($curso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
			$conn->query("INSERT INTO sim_edicoes_arquivo (curso_id, ano, titulo, user_id) VALUES ($curso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
		}
	}
	
	if (isset($_POST['nova_etapa_trigger'])) {
		if (isset($_POST['nova_etapa_titulo'])) {
			$nova_etapa_titulo = $_POST['nova_etapa_titulo'];
			$nova_etapa_titulo = mysqli_real_escape_string($conn, $nova_etapa_titulo);
		}
		if (isset($_POST['nova_etapa_edicao'])) {
			$nova_etapa_edicao = $_POST['nova_etapa_edicao'];
		}
		if (($nova_etapa_titulo != false) && ($nova_etapa_edicao != false)) {
			$conn->query("INSERT INTO sim_etapas (curso_id, edicao_id, titulo, user_id) VALUES ($curso_id, $nova_etapa_edicao, '$nova_etapa_titulo', $user_id)");
			$conn->query("INSERT INTO sim_etapas_arquivo (curso_id, edicao_id, titulo, user_id) VALUES ($curso_id, $nova_etapa_edicao, '$nova_etapa_titulo', $user_id)");
		}
	}
	
	if (isset($_POST['nova_prova_trigger'])) {
		if (isset($_POST['nova_prova_etapa'])) {
			$nova_prova_etapa = $_POST['nova_prova_etapa'];
		}
		if (isset($_POST['nova_prova_titulo'])) {
			$nova_prova_titulo = $_POST['nova_prova_titulo'];
			$nova_prova_titulo = mysqli_real_escape_string($conn, $nova_prova_titulo);
		}
		if (isset($_POST['nova_prova_tipo'])) {
			$nova_prova_tipo = $_POST['nova_prova_tipo'];
		}
		if (($nova_prova_etapa != false) && ($nova_prova_titulo != false) && ($nova_prova_tipo != false)) {
			$conn->query("INSERT INTO sim_provas (curso_id, etapa_id, titulo, tipo, user_id) VALUES ($curso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
			$conn->query("INSERT INTO sim_provas_arquivo (curso_id, etapa_id, titulo, tipo, user_id) VALUES ($curso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
		}
	}
	
	if (isset($_POST['novo_texto_apoio_trigger'])) {
		if (isset($_POST['novo_texto_apoio_origem'])) {
			$novo_texto_apoio_origem = true;
		} else {
			$novo_texto_apoio_origem = false;
		}
		if (isset($_POST['novo_texto_apoio_prova'])) {
			$novo_texto_apoio_prova = $_POST['novo_texto_apoio_prova'];
		}
		if (isset($_POST['novo_texto_apoio_titulo'])) {
			$novo_texto_apoio_titulo = $_POST['novo_texto_apoio_titulo'];
			$novo_texto_apoio_prova = mysqli_real_escape_string($conn, $novo_texto_apoio_prova);
		}
		$quill_novo_texto_apoio_enunciado_html = false;
		$quill_novo_texto_apoio_enunciado_text = false;
		$quill_novo_texto_apoio_enunciado_content = false;
		$quill_novo_texto_apoio_html = false;
		$quill_novo_texto_apoio_text = false;
		$quill_novo_texto_apoio_content = false;
		
		if (isset($_POST['quill_novo_texto_apoio_enunciado_html'])) {
			$novo_texto_apoio_enunciado_html = $_POST['quill_novo_texto_apoio_enunciado_html'];
			$novo_texto_apoio_enunciado_html = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_html);
			$novo_texto_apoio_enunciado_text = $_POST['quill_novo_texto_apoio_enunciado_text'];
			$novo_texto_apoio_enunciado_text = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_text);
			$novo_texto_apoio_enunciado_content = $_POST['quill_novo_texto_apoio_enunciado_content'];
			$novo_texto_apoio_enunciado_content = mysqli_real_escape_string($conn, $novo_texto_apoio_enunciado_content);
		}
		if (isset($_POST['quill_novo_texto_apoio_html'])) {
			$novo_texto_apoio_html = $_POST['quill_novo_texto_apoio_html'];
			$novo_texto_apoio_html = mysqli_real_escape_string($conn, $novo_texto_apoio_html);
			$novo_texto_apoio_text = $_POST['quill_novo_texto_apoio_text'];
			$novo_texto_apoio_text = mysqli_real_escape_string($conn, $novo_texto_apoio_text);
			$novo_texto_apoio_content = $_POST['quill_novo_texto_apoio_content'];
			$novo_texto_apoio_content = mysqli_real_escape_string($conn, $novo_texto_apoio_content);
		}
		
		if (($novo_texto_apoio_origem != false) && ($novo_texto_apoio_prova != false) && ($novo_texto_apoio_titulo != false) && ($novo_texto_apoio_enunciado_html != false) && ($novo_texto_apoio_html != false)) {
			$conn->query("INSERT INTO sim_textos_apoio (curso_id, origem, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($curso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado_html', '$novo_texto_apoio_enunciado_text', '$novo_texto_apoio_enunciado_content', '$novo_texto_apoio_html', '$novo_texto_apoio_text', '$novo_texto_apoio_content', $user_id)");
			$conn->query("INSERT INTO sim_textos_apoio_arquivo (curso_id, origem, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($curso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado_html', '$novo_texto_apoio_enunciado_text', '$novo_texto_apoio_enunciado_content', '$novo_texto_apoio_html', '$novo_texto_apoio_text', '$novo_texto_apoio_content', $user_id)");
		}
	}
	
	if (isset($_POST['nova_questao_trigger'])) {
		if (isset($_POST['nova_questao_origem'])) {
			$nova_questao_origem = true;
		} else {
			$nova_questao_origem = (int)false;
		}
		if (isset($_POST['nova_questao_texto_apoio'])) {
			$nova_questao_texto_apoio = $_POST['nova_questao_texto_apoio'];
		} else {
			$nova_questao_texto_apoio = false;
		}
		if (isset($_POST['nova_questao_prova'])) {
			$nova_questao_prova = $_POST['nova_questao_prova'];
		} else {
			if ($nova_questao_texto_apoio != false) {
				$nova_questao_prova = return_texto_apoio_prova_id($nova_questao_texto_apoio);
			} else {
				$nova_questao_prova = false;
			}
		}
		$nova_questao_prova_info = return_info_prova_id($nova_questao_prova);
		$nova_questao_edicao_ano = $nova_questao_prova_info[2];
		if ($nova_questao_edicao_ano == false) {
			$nova_questao_edicao_ano = "NULL";
		}
		$nova_questao_etapa_id = (int)$nova_questao_prova_info[4];
		
		if (isset($_POST['nova_questao_numero'])) {
			$nova_questao_numero = $_POST['nova_questao_numero'];
		} else {
			$nova_questao_numero = false;
		}
		if (isset($_POST['nova_questao_materia'])) {
			$nova_questao_materia = $_POST['nova_questao_materia'];
		} else {
			$nova_questao_materia = false;
		}
		if (isset($_POST['nova_questao_tipo'])) {
			$nova_questao_tipo = $_POST['nova_questao_tipo'];
		} else {
			$nova_questao_tipo = false;
		}
		// ENUNCIADO
		if (isset($_POST['quill_novo_questao_enunciado_html'])) {
			$quill_novo_questao_enunciado_html = $_POST['quill_novo_questao_enunciado_html'];
			$quill_novo_questao_enunciado_html = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_html);
		} else {
			$quill_novo_questao_enunciado_html = false;
		}
		if (isset($_POST['quill_novo_questao_enunciado_text'])) {
			$quill_novo_questao_enunciado_text = $_POST['quill_novo_questao_enunciado_text'];
			$quill_novo_questao_enunciado_text = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_text);
		} else {
			$quill_novo_questao_enunciado_text = false;
		}
		if (isset($_POST['quill_novo_questao_enunciado_content'])) {
			$quill_novo_questao_enunciado_content = $_POST['quill_novo_questao_enunciado_content'];
			$quill_novo_questao_enunciado_content = mysqli_real_escape_string($conn, $quill_novo_questao_enunciado_content);
		} else {
			$quill_novo_questao_enunciado_content = false;
		}
		// ITEM 1
		$quill_novo_questao_item1_html = false;
		$quill_novo_questao_item1_text = false;
		$quill_novo_questao_item1_content = false;
		$quill_novo_questao_item2_html = false;
		$quill_novo_questao_item2_text = false;
		$quill_novo_questao_item2_content = false;
		$quill_novo_questao_item3_html = false;
		$quill_novo_questao_item3_text = false;
		$quill_novo_questao_item3_content = false;
		$quill_novo_questao_item4_html = false;
		$quill_novo_questao_item4_text = false;
		$quill_novo_questao_item4_content = false;
		$quill_novo_questao_item5_html = false;
		$quill_novo_questao_item5_text = false;
		$quill_novo_questao_item5_content = false;
		$nova_questao_item1_gabarito = "NULL";
		$nova_questao_item2_gabarito = "NULL";
		$nova_questao_item3_gabarito = "NULL";
		$nova_questao_item4_gabarito = "NULL";
		$nova_questao_item5_gabarito = "NULL";
		
		// ITEM 1
		if (isset($_POST['nova_questao_item1_gabarito'])) {
			$nova_questao_item1_gabarito = $_POST['nova_questao_item1_gabarito'];
			if (isset($_POST['quill_novo_questao_item1_html'])) {
				$quill_novo_questao_item1_html = $_POST['quill_novo_questao_item1_html'];
				$quill_novo_questao_item1_html = mysqli_real_escape_string($conn, $quill_novo_questao_item1_html);
				$quill_novo_questao_item1_html = "'$quill_novo_questao_item1_html'";
				$quill_novo_questao_item1_text = $_POST['quill_novo_questao_item1_text'];
				$quill_novo_questao_item1_text = mysqli_real_escape_string($conn, $quill_novo_questao_item1_text);
				$quill_novo_questao_item1_text = "'$quill_novo_questao_item1_text'";
				$quill_novo_questao_item1_content = $_POST['quill_novo_questao_item1_content'];
				$quill_novo_questao_item1_content = mysqli_real_escape_string($conn, $quill_novo_questao_item1_content);
				$quill_novo_questao_item1_content = "'$quill_novo_questao_item1_content'";
			}
		}
		if ($quill_novo_questao_item1_html == false) {
			$quill_novo_questao_item1_html = "NULL";
			$quill_novo_questao_item1_text = "NULL";
			$quill_novo_questao_item1_content = "NULL";
		}
		// ITEM 2
		if (isset($_POST['nova_questao_item2_gabarito'])) {
			$nova_questao_item2_gabarito = $_POST['nova_questao_item2_gabarito'];
			if (isset($_POST['quill_novo_questao_item2_html'])) {
				$quill_novo_questao_item2_html = $_POST['quill_novo_questao_item2_html'];
				$quill_novo_questao_item2_html = mysqli_real_escape_string($conn, $quill_novo_questao_item2_html);
				$quill_novo_questao_item2_html = "'$quill_novo_questao_item2_html'";
				$quill_novo_questao_item2_text = $_POST['quill_novo_questao_item2_text'];
				$quill_novo_questao_item2_text = mysqli_real_escape_string($conn, $quill_novo_questao_item2_text);
				$quill_novo_questao_item2_text = "'$quill_novo_questao_item2_text'";
				$quill_novo_questao_item2_content = $_POST['quill_novo_questao_item2_content'];
				$quill_novo_questao_item2_content = mysqli_real_escape_string($conn, $quill_novo_questao_item2_content);
				$quill_novo_questao_item2_content = "'$quill_novo_questao_item2_content'";
			}
		}
		if ($quill_novo_questao_item2_html == false) {
			$quill_novo_questao_item2_html = "NULL";
			$quill_novo_questao_item2_text = "NULL";
			$quill_novo_questao_item2_content = "NULL";
		}
		// ITEM 3
		if (isset($_POST['nova_questao_item3_gabarito'])) {
			$nova_questao_item3_gabarito = $_POST['nova_questao_item3_gabarito'];
			if (isset($_POST['quill_novo_questao_item3_html'])) {
				$quill_novo_questao_item3_html = $_POST['quill_novo_questao_item3_html'];
				$quill_novo_questao_item3_html = mysqli_real_escape_string($conn, $quill_novo_questao_item3_html);
				$quill_novo_questao_item3_html = "'$quill_novo_questao_item3_html'";
				$quill_novo_questao_item3_text = $_POST['quill_novo_questao_item3_text'];
				$quill_novo_questao_item3_text = mysqli_real_escape_string($conn, $quill_novo_questao_item3_text);
				$quill_novo_questao_item3_text = "'$quill_novo_questao_item3_text'";
				$quill_novo_questao_item3_content = $_POST['quill_novo_questao_item3_content'];
				$quill_novo_questao_item3_content = mysqli_real_escape_string($conn, $quill_novo_questao_item3_content);
				$quill_novo_questao_item3_content = "'$quill_novo_questao_item3_content'";
			}
		}
		if ($quill_novo_questao_item3_html == false) {
			$quill_novo_questao_item3_html = "NULL";
			$quill_novo_questao_item3_text = "NULL";
			$quill_novo_questao_item3_content = "NULL";
		}
		// ITEM 4
		if (isset($_POST['nova_questao_item4_gabarito'])) {
			$nova_questao_item4_gabarito = $_POST['nova_questao_item4_gabarito'];
			if (isset($_POST['quill_novo_questao_item4_html'])) {
				$quill_novo_questao_item4_html = $_POST['quill_novo_questao_item4_html'];
				$quill_novo_questao_item4_html = mysqli_real_escape_string($conn, $quill_novo_questao_item4_html);
				$quill_novo_questao_item4_html = "'$quill_novo_questao_item4_html'";
				$quill_novo_questao_item4_text = $_POST['quill_novo_questao_item4_text'];
				$quill_novo_questao_item4_text = mysqli_real_escape_string($conn, $quill_novo_questao_item4_text);
				$quill_novo_questao_item4_text = "'$quill_novo_questao_item4_text'";
				$quill_novo_questao_item4_content = $_POST['quill_novo_questao_item4_content'];
				$quill_novo_questao_item4_content = mysqli_real_escape_string($conn, $quill_novo_questao_item4_content);
				$quill_novo_questao_item4_content = "'$quill_novo_questao_item4_content'";
			}
		}
		if ($quill_novo_questao_item4_html == false) {
			$quill_novo_questao_item4_html = "NULL";
			$quill_novo_questao_item4_text = "NULL";
			$quill_novo_questao_item4_content = "NULL";
		}
		// ITEM 5
		if (isset($_POST['nova_questao_item5_gabarito'])) {
			$nova_questao_item5_gabarito = $_POST['nova_questao_item5_gabarito'];
			if (isset($_POST['quill_novo_questao_item5_html'])) {
				$quill_novo_questao_item5_html = $_POST['quill_novo_questao_item5_html'];
				$quill_novo_questao_item5_html = mysqli_real_escape_string($conn, $quill_novo_questao_item5_html);
				$quill_novo_questao_item5_html = "'$quill_novo_questao_item5_html'";
				$quill_novo_questao_item5_text = $_POST['quill_novo_questao_item5_text'];
				$quill_novo_questao_item5_text = mysqli_real_escape_string($conn, $quill_novo_questao_item5_text);
				$quill_novo_questao_item5_text = "'$quill_novo_questao_item5_text'";
				$quill_novo_questao_item5_content = $_POST['quill_novo_questao_item5_content'];
				$quill_novo_questao_item5_content = mysqli_real_escape_string($conn, $quill_novo_questao_item5_content);
				$quill_novo_questao_item5_content = "'$quill_novo_questao_item5_content'";
			}
		}
		if ($quill_novo_questao_item5_html == false) {
			$quill_novo_questao_item5_html = "NULL";
			$quill_novo_questao_item5_text = "NULL";
			$quill_novo_questao_item5_content = "NULL";
		}
		
		// GABARITOS
		if (isset($_POST['nova_questao_item2_gabarito'])) {
			$nova_questao_item2_gabarito = $_POST['nova_questao_item2_gabarito'];
		} else {
			$nova_questao_item2_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item3_gabarito'])) {
			$nova_questao_item3_gabarito = $_POST['nova_questao_item3_gabarito'];
		} else {
			$nova_questao_item3_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item4_gabarito'])) {
			$nova_questao_item4_gabarito = $_POST['nova_questao_item4_gabarito'];
		} else {
			$nova_questao_item4_gabarito = "NULL";
		}
		if (isset($_POST['nova_questao_item5_gabarito'])) {
			$nova_questao_item5_gabarito = $_POST['nova_questao_item5_gabarito'];
		} else {
			$nova_questao_item5_gabarito = "NULL";
		}
		$conn->query("INSERT INTO sim_questoes (origem, curso_id, edicao_ano, texto_apoio_id, etapa_id, prova_id, enunciado_html, enunciado_text, enunciado_content, numero, materia, tipo, item1_html, item1_text, item1_content, item2_html, item2_text, item2_content, item3_html, item3_text, item3_content, item4_html, item4_text, item4_content, item5_html, item5_text, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $curso_id, $nova_questao_edicao_ano, $nova_questao_texto_apoio, $nova_questao_etapa_id, $nova_questao_prova, '$quill_novo_questao_enunciado_html', '$quill_novo_questao_enunciado_text', '$quill_novo_questao_enunciado_content', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $quill_novo_questao_item1_html, $quill_novo_questao_item1_text, $quill_novo_questao_item1_content, $quill_novo_questao_item2_html, $quill_novo_questao_item2_text, $quill_novo_questao_item2_content, $quill_novo_questao_item3_html, $quill_novo_questao_item3_text, $quill_novo_questao_item3_content, $quill_novo_questao_item4_html, $quill_novo_questao_item4_text, $quill_novo_questao_item4_content, $quill_novo_questao_item5_html, $quill_novo_questao_item5_text, $quill_novo_questao_item5_content, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
		$conn->query("INSERT INTO sim_questoes_arquivo (origem, curso_id, edicao_ano, texto_apoio_id, etapa_id, prova_id, enunciado_html, enunciado_text, enunciado_content, numero, materia, tipo, item1_html, item1_text, item1_content, item2_html, item2_text, item2_content, item3_html, item3_text, item3_content, item4_html, item4_text, item4_content, item5_html, item5_text, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $curso_id, $nova_questao_edicao_ano, $nova_questao_texto_apoio, $nova_questao_etapa_id, $nova_questao_prova, '$quill_novo_questao_enunciado_html', '$quill_novo_questao_enunciado_text', '$quill_novo_questao_enunciado_content', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $quill_novo_questao_item1_html, $quill_novo_questao_item1_text, $quill_novo_questao_item1_content, $quill_novo_questao_item2_html, $quill_novo_questao_item2_text, $quill_novo_questao_item2_content, $quill_novo_questao_item3_html, $quill_novo_questao_item3_text, $quill_novo_questao_item3_content, $quill_novo_questao_item4_html, $quill_novo_questao_item4_text, $quill_novo_questao_item4_content, $quill_novo_questao_item5_html, $quill_novo_questao_item5_text, $quill_novo_questao_item5_content, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
	}
	
	if (isset($_POST['novo_simulado_trigger'])) {
		$novo_simulado_tipo = $_POST['novo_simulado_tipo'];
		header("Location:simulado.php?simulado_tipo=$novo_simulado_tipo");
	}
	$query = prepare_query("SELECT * FROM sim_edicoes WHERE curso_id = $pagina_id ORDER BY id DESC");
	$edicoes = $conn->query($query);
	$query = prepare_query("SELECT id, edicao_id, titulo FROM sim_etapas WHERE curso_id = $pagina_id ORDER BY id DESC");
	$etapas = $conn->query($query);
	$query = prepare_query("SELECT id, etapa_id, titulo, tipo FROM sim_provas WHERE curso_id = $pagina_id ORDER BY id DESC");
	$provas = $conn->query($query);
	$query = prepare_query("SELECT id, origem, prova_id, titulo FROM sim_textos_apoio WHERE curso_id = $pagina_id ORDER BY id DESC");
	$textos_apoio = $conn->query($query);
	$query = prepare_query("SELECT id FROM Paginas WHERE tipo = 'materia' AND item_id = $pagina_curso_pagina_id");
	$materias = $conn->query($query);
	
	$html_head_template_quill_sim = true;
	include 'templates/html_head.php';

?>

<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid">
    <div class="row justify-content-between px-2">

    </div>
</div>

<div class="container">
	<?php
		$template_titulo = 'Plataforma de simulados';
		$template_subtitulo = return_curso_titulo_id($pagina_id);
		$template_titulo_context = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						$template_id = 'sessao_simulados';
						$template_titulo = 'Plataforma de simulados';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Adicionar dados de provas e questões';
						$artefato_criacao = 'Pressione para lançar dados de simulados';
						$artefato_tipo = 'adicionar_simulado';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Criar simulado';
						$artefato_criacao = 'Pressione para criar um simulados';
						$artefato_tipo = 'criar_simulado';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Fazer simulado';
						$artefato_criacao = 'Pressione para fazer um simulado';
						$artefato_tipo = 'novo_simulado';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
<?php
	include 'pagina/modals_provas.php';
?>
</body>
<?php
  include 'templates/footer.html';
  $mdb_select = true;
  include 'templates/html_bottom.php';
?>