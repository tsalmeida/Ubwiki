<?php
	
	include 'engine.php';
	
	$dados_usuario = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user_email'");
	if ($dados_usuario->num_rows > 0) {
		while ($row = $dados_usuario->fetch_assoc()) {
			$user_id = $row['id'];
			$user_tipo = $row['tipo'];
			$user_criacao = $row['criacao'];
			$user_apelido = $row['apelido'];
			$user_nome = $row['nome'];
			$user_sobrenome = $row['sobrenome'];
		}
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
			$conn->query("INSERT INTO sim_edicoes (concurso_id, ano, titulo, user_id) VALUES ($concurso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
			$conn->query("INSERT INTO sim_edicoes_arquivo (concurso_id, ano, titulo, user_id) VALUES ($concurso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
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
			$conn->query("INSERT INTO sim_etapas (concurso_id, edicao_id, titulo, user_id) VALUES ($concurso_id, $nova_etapa_edicao, '$nova_etapa_titulo', $user_id)");
			$conn->query("INSERT INTO sim_etapas_arquivo (concurso_id, edicao_id, titulo, user_id) VALUES ($concurso_id, $nova_etapa_edicao, '$nova_etapa_titulo', $user_id)");
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
			$conn->query("INSERT INTO sim_provas (concurso_id, etapa_id, titulo, tipo, user_id) VALUES ($concurso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
			$conn->query("INSERT INTO sim_provas_arquivo (concurso_id, etapa_id, titulo, tipo, user_id) VALUES ($concurso_id, $nova_prova_etapa, '$nova_prova_titulo', $nova_prova_tipo, $user_id)");
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
			$conn->query("INSERT INTO sim_textos_apoio (concurso_id, origem, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($concurso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado_html', '$novo_texto_apoio_enunciado_text', '$novo_texto_apoio_enunciado_content', '$novo_texto_apoio_html', '$novo_texto_apoio_text', '$novo_texto_apoio_content', $user_id)");
			$conn->query("INSERT INTO sim_textos_apoio_arquivo (concurso_id, origem, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($concurso_id, $novo_texto_apoio_origem, $novo_texto_apoio_prova, '$novo_texto_apoio_titulo', '$novo_texto_apoio_enunciado_html', '$novo_texto_apoio_enunciado_text', '$novo_texto_apoio_enunciado_content', '$novo_texto_apoio_html', '$novo_texto_apoio_text', '$novo_texto_apoio_content', $user_id)");
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
		$conn->query("INSERT INTO sim_questoes (origem, concurso_id, edicao_ano, texto_apoio_id, etapa_id, prova_id, enunciado_html, enunciado_text, enunciado_content, numero, materia, tipo, item1_html, item1_text, item1_content, item2_html, item2_text, item2_content, item3_html, item3_text, item3_content, item4_html, item4_text, item4_content, item5_html, item5_text, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $concurso_id, $nova_questao_edicao_ano, $nova_questao_texto_apoio, $nova_questao_etapa_id, $nova_questao_prova, '$quill_novo_questao_enunciado_html', '$quill_novo_questao_enunciado_text', '$quill_novo_questao_enunciado_content', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $quill_novo_questao_item1_html, $quill_novo_questao_item1_text, $quill_novo_questao_item1_content, $quill_novo_questao_item2_html, $quill_novo_questao_item2_text, $quill_novo_questao_item2_content, $quill_novo_questao_item3_html, $quill_novo_questao_item3_text, $quill_novo_questao_item3_content, $quill_novo_questao_item4_html, $quill_novo_questao_item4_text, $quill_novo_questao_item4_content, $quill_novo_questao_item5_html, $quill_novo_questao_item5_text, $quill_novo_questao_item5_content, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
		$conn->query("INSERT INTO sim_questoes_arquivo (origem, concurso_id, edicao_ano, texto_apoio_id, etapa_id, prova_id, enunciado_html, enunciado_text, enunciado_content, numero, materia, tipo, item1_html, item1_text, item1_content, item2_html, item2_text, item2_content, item3_html, item3_text, item3_content, item4_html, item4_text, item4_content, item5_html, item5_text, item5_content, item1_gabarito, item2_gabarito, item3_gabarito, item4_gabarito, item5_gabarito, user_id) VALUES ($nova_questao_origem, $concurso_id, $nova_questao_edicao_ano, $nova_questao_texto_apoio, $nova_questao_etapa_id, $nova_questao_prova, '$quill_novo_questao_enunciado_html', '$quill_novo_questao_enunciado_text', '$quill_novo_questao_enunciado_content', $nova_questao_numero, $nova_questao_materia, $nova_questao_tipo, $quill_novo_questao_item1_html, $quill_novo_questao_item1_text, $quill_novo_questao_item1_content, $quill_novo_questao_item2_html, $quill_novo_questao_item2_text, $quill_novo_questao_item2_content, $quill_novo_questao_item3_html, $quill_novo_questao_item3_text, $quill_novo_questao_item3_content, $quill_novo_questao_item4_html, $quill_novo_questao_item4_text, $quill_novo_questao_item4_content, $quill_novo_questao_item5_html, $quill_novo_questao_item5_text, $quill_novo_questao_item5_content, $nova_questao_item1_gabarito, $nova_questao_item2_gabarito, $nova_questao_item3_gabarito, $nova_questao_item4_gabarito, $nova_questao_item5_gabarito, $user_id)");
	}
	
	if (isset($_POST['novo_simulado_trigger'])) {
		$novo_simulado_tipo = $_POST['novo_simulado_tipo'];
		header("Location:simulado.php?simulado_tipo=$novo_simulado_tipo");
	}
	
	if (isset($_POST['novo_nome'])) {
		$user_nome = $_POST['novo_nome'];
		$user_sobrenome = $_POST['novo_sobrenome'];
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
	
	if (isset($_POST['nova_imagem_titulo'])) {
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, 0, 'nova_imagem_privada')");
	}
	
	if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
		$nova_imagem_link = $_POST['nova_imagem_link'];
		$nova_imagem_link = base64_encode($nova_imagem_link);
		adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, 0, $user_id, 'privada', 'link', 0);
	} else {
		$upload_ok = false;
		if (isset($_FILES['nova_imagem_upload'])) {
			$nova_imagem_upload = $_FILES['nova_imagem_upload'];
			$target_dir = '../imagens/uploads/';
			$target_file = $target_dir . basename($_FILES['nova_imagem_upload']['name']);
			$image_filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			// check if file is actually an image
			$check = getimagesize($_FILES['nova_imagem_upload']['tmp_name']);
			if ($check !== false) {
				$upload_ok = true;
			}
			if ($upload_ok == true) {
				if ($image_filetype != 'jpg' && $image_filetype != 'png' && $image_filetype != 'jpeg'
					&& $image_filetype != 'gif') {
					$upload_ok = false;
				}
			}
			if ($upload_ok != false) {
				move_uploaded_file($_FILES['nova_imagem_upload']['tmp_name'], $target_file);
				$target_file = base64_encode($target_file);
				adicionar_imagem($target_file, $nova_imagem_titulo, 0, $user_id, 'privada', 'upload', 0);
			}
		}
	}
	
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
	
	$html_head_template_quill_sim = true;
	include 'templates/html_head.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, tipo_pagina) VALUES ($user_id, 'userpage')");
	
	$edicoes = $conn->query("SELECT id, ano, titulo FROM sim_edicoes WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$etapas = $conn->query("SELECT id, edicao_id, titulo FROM sim_etapas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$provas = $conn->query("SELECT id, etapa_id, titulo, tipo FROM sim_provas WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$textos_apoio = $conn->query("SELECT id, origem, prova_id, titulo FROM sim_textos_apoio WHERE concurso_id = $concurso_id ORDER BY id DESC");
	$materias = $conn->query("SELECT id, titulo FROM Materias WHERE concurso_id = $concurso_id ORDER BY ordem");
	$bookmarks = $conn->query("SELECT DISTINCT topico_id FROM Bookmarks WHERE user_id = $user_id AND topico_id IS NOT NULL AND bookmark = 1 AND active = 1 ORDER BY id DESC");
	$bookmarks_elementos = $conn->query("SELECT elemento_id FROM Bookmarks WHERE user_id = $user_id AND bookmark = 1 AND elemento_id IS NOT NULL AND active = 1 ORDER BY id DESC");
	$comentarios = $conn->query("SELECT DISTINCT page_id, page_tipo FROM Forum WHERE user_id = $user_id");
	$completados = $conn->query("SELECT topico_id FROM Completed WHERE user_id = $user_id AND estado = 1 AND active = 1");
	$verbetes_escritos = $conn->query("SELECT DISTINCT page_id, tipo FROM Textos_arquivo WHERE user_id = $user_id AND (tipo = 'verbete' OR tipo = 'verbete_elemento') ORDER BY id DESC");
	$visualizacoes = $conn->query("SELECT DISTINCT page_id, criacao, tipo_pagina, extra FROM Visualizacoes WHERE (tipo_pagina <> 'userpage' AND tipo_pagina <> 'index' AND tipo_pagina <> 'verbete_mudanca') AND user_id = $user_id ORDER BY id DESC");

?>
<body>
<?php
	include 'templates/navbar.php';
?>


<div class='container-fluid'>
    <div class='row justify-content-between px-2'>
			<?php
				echo "<div class='col-3 mt-3'><div class='row justify-content-start'>";
				echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_opcoes' class='mr-1 text-info'><i class='fad fa-user-cog fa-2x fa-fw'></i></a>";
				echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_apresentacao' class='ml-1 text-info'><i class='fad fa-file-signature fa-swap-opacity fa-2x fa-fw'></i></a>";
				if ($user_tipo == 'admin') {
					echo "<a href='admin.php' class='mr-1 text-info'><i class='fad fa-user-crown fa-2x fa-fw'></i></a>";
				}
				echo "</div></div>";
				echo "<div class='col-6'><div class='row justify-content-center'>";
				
				echo "
                    <div class='row' class='justify-content-center'>
                      <a id='escritorio_home' href='javascript:void(0);' class='p-2 rounded text-muted artefato' title='Retornar à página inicial'><i class='fad fa-lamp-desk fa-3x fa-fw'></i></a>
                      <a id='mostrar_textos' href='javascript:void(0);' class='p-2 rounded text-primary artefato' title='Pressione para ver seus textos e notas privadas'><i class='fad fa-typewriter fa-3x fa-fw'></i></a>
                      <a id='mostrar_imagens' href='javascript:void(0);' class='p-2 rounded text-danger artefato' title='Pressione para ver suas imagens privadas e públicas'><i class='fad fa-images fa-3x fa-fw'></i></a>
                      <a id='mostrar_acervo' href='javascript:void(0);' class='p-2 rounded text-success artefato' title='Pressione para ver seu acervo virtual'><i class='fad fa-books fa-3x fa-fw'></i></a>
                      <a id='mostrar_tags' href='javascript:void(0);' class='p-2 rounded text-warning artefato' title='Pressione para ver suas áreas de interesse'><i class='fad fa-tags fa-3x fa-fw'></i></a>";
				if ($user_tipo == 'admin') {
					echo "<a id='icone_simulados' href='javascript:void(0);' class='p-2 rounded text-secondary artefato' title='Pressione para ver seus simulados'><i class='fad fa-clipboard-list-check fa-3x fa-fw'></i></a>
                  ";
				}
				
				echo "</div></div></div>";
				echo "<div class='col-3 mt-3'><div class='row justify-content-end'>";
				if ($comentarios->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_forum' class='ml-1 text-secondary'><i class='fad fa-comments-alt fa-2x fa-fw'></i></a>";
				}
				if ($completados->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_completados' class='ml-1 text-success'><i class='fad fa-check-circle fa-2x fa-fw'></i></a>";
				}
				if ($bookmarks->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_bookmarks' class='ml-1 text-danger'><i class='fad fa-bookmark fa-2x fa-fw'></i></a>";
				}
				if ($verbetes_escritos->num_rows > 0) {
					echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_verbetes' class='ml-1 text-warning'><i class='fad fa-seedling fa-2x fa-fw'></i></a>";
				}
				echo "</div></div>";
			?>
    </div>
</div>


<div class="container">
	<?php
		if ($user_apelido != false) {
			$template_titulo = $user_apelido;
			$template_titulo_escritorio = true;
		} else {
			$template_titulo = "Seu escritório";
		}
		$template_titulo_context = true;
		include 'templates/titulo.php'
	
	?>
    <div class="row d-flex justify-content-center">

        <div id="coluna_unica" class="col">
					<?php
						
						if ($visualizacoes->num_rows > 0) {
							$template_id = 'ultimas_visualizacoes';
							$template_titulo = 'Estudos recentes';
							$template_classes = 'mostrar_sessao esconder_sessao';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							$count = 0;
							$resultados = array();
							while ($visualizacao = $visualizacoes->fetch_assoc()) {
								$visualizacao_page_id = $visualizacao['page_id'];
								if (array_search($visualizacao_page_id, $resultados) !== false) {
									continue;
								} else {
									array_push($resultados, $visualizacao_page_id);
								}
								$visualizacao_extra = $visualizacao['extra'];
								$visualizacao_tipo_pagina = $visualizacao['tipo_pagina'];
								if ($visualizacao_extra == 'secao_elemento') {
									continue;
								} elseif ($visualizacao_extra == 'verbete') {
									continue;
								} elseif ($visualizacao_extra == 'verbete_elemento') {
									continue;
								}
								if ($visualizacao_tipo_pagina == 'elemento') {
									$visualizacao_elemento_info = return_elemento_info($visualizacao_page_id);
									$artefato_titulo = $visualizacao_elemento_info[4];
									$artefato_subtitulo = $visualizacao_elemento_info[5];
									$artefato_link = "elemento.php?id=$visualizacao_page_id";
									$artefato_tipo = $visualizacao_elemento_info[3];
								} elseif (($visualizacao_tipo_pagina == 'topico') || ($visualizacao_tipo_pagina == 'verbete')) {
									$artefato_titulo = return_titulo_topico($visualizacao_page_id);
									$artefato_subtitulo_concurso_id = return_concurso_id_topico($visualizacao_page_id);
									$artefato_subtitulo_materia_id = return_materia_id_topico($visualizacao_page_id);
									$artefato_subtitulo_concurso_titulo = return_concurso_sigla($artefato_subtitulo_concurso_id);
									$artefato_subtitulo_materia_titulo = return_materia_titulo_id($artefato_subtitulo_materia_id);
									$artefato_subtitulo = "$artefato_subtitulo_concurso_titulo / $artefato_subtitulo_materia_titulo";
									$artefato_link = "verbete.php?topico_id=$visualizacao_page_id";
									$artefato_tipo = 'verbete';
								} elseif ($visualizacao_tipo_pagina == 'texto') {
									$artefato_texto_info = return_texto_info($visualizacao_page_id);
									$artefato_titulo = $artefato_texto_info[2];
									$artefato_tipo = $artefato_texto_info[1];
									$artefato_link = "edicao_textos.php?texto_id=$visualizacao_page_id";
									if ($artefato_titulo == false) {
										$artefato_subtitulo = return_artefato_subtitulo($artefato_tipo);
									}
								} else {
									continue;
								}
								$artefato_id = 0;
								$artefato_page_id = false;
								$artefato_criacao = false;
								$template_conteudo .= include 'templates/artefato_item.php';
								$count++;
								if ($count == 12) {
									break;
								}
							}
							include 'templates/page_element.php';
						}
						
						$template_id = 'pagina_usuario_informacoes';
						$template_titulo = 'Seu escritório';
						$template_classes = 'mostrar_sessao esconder_sessao justify-content-center';
						$template_col_value = 'col-lg-8 col-md-10 col-sm-12';
						$template_conteudo = false;
						$template_conteudo .= "
			                <p>No seu escritório, você encontrará seus artefatos de estudo, organizados de acordo com seus interesses e objetivos. Quanto mais artefatos você criar, sejam itens em seu acervo virtual, anotações, imagens, indicações de progresso ou outras atividades desempenhadas, mais completos serão seus estudos, mais você se aproximará de seus objetivos.</p>
			            ";
						include 'templates/page_element.php';
						
						$template_id = 'escolha_cursos';
						$template_titulo = 'Seus cursos';
						$template_classes = 'mostrar_sessao esconder_sessao justify-content-center';
						$template_col_value = 'col-lg-8 col-md-10 col-sm-12';
						$template_conteudo = false;
						$template_conteudo .= "<p>Você pode usar a Ubwiki como uma plataforma de estudos geral para registros de suas leituras pessoais, mas torna-se ainda mais efetiva quando você participa de comunidades em torno de seus interesses. Essa é a função dos cursos listados abaixo.</p>";
						$usuario_cursos = $conn->query("SELECT opcao FROM Opcoes WHERE opcao_tipo = 'curso' AND user_id = $user_id");
						if ($usuario_cursos->num_rows > 0) {
							$template_conteudo .= "<h2>Cursos em que você está inscrito</h2>";
							$template_conteudo .= "<ul class='list-group'>";
							while ($usuario_curso = $usuario_cursos->fetch_assoc()) {
								$usuario_curso_id = $usuario_curso['opcao'];
								$usuario_curso_titulo = return_concurso_titulo_id($usuario_curso_id);
								$template_conteudo .= "<a href='index.php?curso_id=$usuario_curso_id'><li class='list-group-item list-group-item-action'>$usuario_curso_titulo</li></a>";
							}
							$template_conteudo .= "</ul>";
						} else {
							$template_conteudo .= "<p><strong>Você ainda não aderiu a nenhum curso.</strong></p>";
						}
						$template_conteudo .= "<h2 class='mt-3'>Cursos disponíveis</h2>";
						$template_conteudo .= "
							<form method='post'>
                                <select class='$select_classes' name='aderir_novo_curso' id='aderir_novo_curso' required>
                                      <option value='' disabled selected>Selecione um curso</option>
                        ";
						$cursos_disponiveis = $conn->query("SELECT id, titulo FROM Concursos WHERE estado = 1");
						while ($curso_disponivel = $cursos_disponiveis->fetch_assoc()) {
							$curso_disponivel_id = $curso_disponivel['id'];
							$curso_disponivel_titulo = $curso_disponivel['titulo'];
							$template_conteudo .= "<option value='$curso_disponivel_id'>$curso_disponivel_titulo</option>";
						}
						$template_conteudo .= "</select>";
						$template_conteudo .= "
							<div class='row justify-content-center'>
								<button name='trigger_adicionar_curso' class='$button_classes'>Aderir</button>
							</div>
							</form>
						";
						
						include 'templates/page_element.php';
						
						$topicos_acervo = $conn->query("SELECT DISTINCT etiqueta_id FROM Acervo WHERE user_id = $user_id AND etiqueta_tipo = 'topico' ORDER BY id DESC");
						$template_id = 'topicos_interesse';
						$template_titulo = 'Áreas de interesse';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Incluir área de interesse';
						$artefato_criacao = 'Pressione para adicionar uma área de interesse';
						$artefato_tipo = 'novo_topico';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						while ($topico_acervo = $topicos_acervo->fetch_assoc()) {
							$topico_acervo_etiqueta_id = $topico_acervo['etiqueta_id'];
							$topico_acervo_etiqueta_info = return_etiqueta_info($topico_acervo_etiqueta_id);
							$artefato_criacao = $topico_acervo_etiqueta_info[0];
							$artefato_titulo = $topico_acervo_etiqueta_info[2];
							$artefato_link = false;
							$artefato_tipo = 'topico_interesse';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$template_id = 'sessao_simulados';
						$template_titulo = 'Simulados';
						$template_classes = 'esconder_sessao';
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
						
						$template_id = 'sessao_plataforma_simulados';
						$template_titulo = 'Plataforma de simulados';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'p-limit';
						$template_conteudo = false;
						
						$template_conteudo .= "
                            <p>A plataforma de simulados é populada por questões oficiais de concursos e por questões criadas pelos usuários da página.</p>
                            <p>Para acrescentar questões de edições passadas do concurso, é necessário registrar algumas informações prévias:</p>
                            <ol>
                                <li>A edição do concurso</li>
                                <li>A etapa da edição</li>
                                <li>A prova da etapa</li>
                            </ol>
                            <p>Caso a questão possua um texto de apoio, será também necessário registrá-lo anteriormente.</p>
                            <p>É importante prestar atenção para que todos as questões não-oficiais sejam devidamente identificadas como tal. É possível criar textos de apoio não oficiais, mas também criar questões não-oficiais com os textos de apoio de edições passadas do concurso.</p>
                            <p>Selecione a opção abaixo para acessar os sistemas que permitem acrescentar provas anteriores do concurso:</p>
                        ";
						$template_conteudo .= "
                            <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_edicao'>Adicionar edição do concurso</button>
                        ";
						if ($edicoes->num_rows > 0) {
							$template_conteudo .= "
                                <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_etapa'>Adicionar etapa da edição</button>
                            ";
						}
						if ($etapas->num_rows > 0) {
							$template_conteudo .= "
                                <button type='button' class='$button_classes_info btn-block' data-toggle='modal' data-target='#modal_adicionar_prova'>Adicionar prova da etapa</button>
                            ";
						}
						$template_conteudo .= "
                            <button type='button' class='$button_classes btn-block' data-toggle='modal' data-target='#modal_adicionar_texto_apoio'>Adicionar texto de apoio</button>
                        ";
						$template_conteudo .= "
                            <button type='button' class='$button_classes btn-block' data-toggle='modal' data-target='#modal_adicionar_questao'>Adicionar questão</button>
                        ";
						
						include 'templates/page_element.php';
						
						$respostas = $conn->query("SELECT DISTINCT simulado_id, questao_tipo FROM sim_respostas WHERE user_id = $user_id ORDER BY id DESC");
						if ($respostas->num_rows > 0) {
							$template_id = 'respostas_usuario';
							$template_titulo = 'Simulados feitos';
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
								$simulado_concurso_id = $simulado_info[2];
								$simulado_concurso_sigla = return_concurso_sigla($simulado_concurso_id);
								$artefato_criacao = $simulado_criacao;
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = "$simulado_concurso_sigla: $simulado_tipo_string";
								$artefato_link = "resultados.php?simulado_id=$artefato_id";
								$artefato_tipo = 'simulado';
								if ($artefato_questao_tipo == 3) {
									$artefato_icone = 'fa-file-edit fa-swap-opacity';
								}
								$template_conteudo .= include 'templates/artefato_item.php';
							}
							include 'templates/page_element.php';
						}
						
						
						/*						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Novo curso';
						$artefato_criacao = 'Pressione para criar um novo curso';
						$artefato_tipo = 'novo_curso';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';*/
						
						$template_id = 'acervo_virtual';
						$template_titulo = 'Acervo';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Adicionar item';
						$artefato_criacao = 'Pressione para adicionar um item a seu acervo';
						$artefato_tipo = 'nova_referencia';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$acervo = $conn->query("SELECT criacao, etiqueta_id, etiqueta_tipo, elemento_id FROM Acervo WHERE user_id = $user_id AND estado = 1 AND etiqueta_tipo NOT IN ('topico') ORDER BY id DESC");
						while ($acervo_item = $acervo->fetch_assoc()) {
							$acervo_item_criacao = $acervo_item['criacao'];
							$acervo_item_etiqueta_id = $acervo_item['etiqueta_id'];
							$acervo_item_etiqueta_tipo = $acervo_item['etiqueta_tipo'];
							$acervo_item_elemento_id = $acervo_item['elemento_id'];
							if ($acervo_item_elemento_id == false) {
								$acervo_item_elemento_id = return_etiqueta_elemento_id($acervo_item_etiqueta_id);
							}
							$acervo_item_elemento_info = return_elemento_info($acervo_item_elemento_id);
							$acervo_item_elemento_titulo = $acervo_item_elemento_info[4];
							$acervo_item_elemento_autor = $acervo_item_elemento_info[5];
							
							$artefato_id = $acervo_item_etiqueta_id;
							$artefato_page_id = $acervo_item_elemento_id;
							$artefato_titulo = $acervo_item_elemento_titulo;
							$artefato_subtitulo = $acervo_item_elemento_autor;
							$artefato_criacao = $acervo_item_criacao;
							$artefato_criacao = "Adicionado em $artefato_criacao";
							$artefato_tipo = $acervo_item_etiqueta_tipo;
							$artefato_link = "elemento.php?id=$acervo_item_elemento_id";
							
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						
						$anotacoes = $conn->query("SELECT id, page_id, titulo, criacao, tipo FROM Textos WHERE tipo LIKE '%anotac%' AND user_id = $user_id ORDER BY id DESC");
						$template_id = 'anotacoes_privadas';
						$template_titulo = 'Textos e notas de estudo';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Novo texto privado';
						$artefato_criacao = 'Pressione para criar texto privado';
						$artefato_tipo = 'nova_anotacao';
						$artefato_link = 'edicao_textos.php?texto_id=new';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						while ($anotacao = $anotacoes->fetch_assoc()) {
							$artefato_id = $anotacao['id'];
							$artefato_page_id = $anotacao['page_id'];
							$artefato_titulo = $anotacao['titulo'];
							$artefato_criacao = $anotacao['criacao'];
							$artefato_tipo = $anotacao['tipo'];
							if ($artefato_tipo == 'anotacoes_elemento') {
								$artefato_elemento_info = return_elemento_info($artefato_page_id);
								$artefato_elemento_titulo = $artefato_elemento_info[4];
								$artefato_elemento_autor = $artefato_elemento_info[5];
								$artefato_elemento_tipo = $artefato_elemento_info[3];
								$artefato_titulo = $artefato_elemento_titulo;
								if ($artefato_elemento_autor != false) {
									$artefato_subtitulo = $artefato_elemento_autor;
								} else {
									$artefato_subtitulo = $artefato_elemento_tipo;
								}
								$artefato_subtipo = $artefato_elemento_tipo;
								$artefato_link = "elemento.php?id=$artefato_page_id";
							} elseif ($artefato_tipo == 'anotacoes_curso') {
								//TODO: Colocar no update título automático de anotações_curso;
								$artefato_titulo = return_concurso_titulo_id($artefato_page_id);
							} elseif ($artefato_tipo == 'anotacoes') {
								$artefato_titulo = return_titulo_topico($artefato_page_id);
								$artefato_topico_concurso_id = return_concurso_id_topico($artefato_page_id);
								$artefato_topico_concurso_sigla = return_concurso_sigla($artefato_topico_concurso_id);
								$artefato_subtitulo = $artefato_topico_concurso_sigla;
								$artefato_link = "verbete.php?topico_id=$artefato_page_id";
							} elseif ($artefato_tipo == 'anotacoes_materia') {
								$artefato_materia_titulo = return_materia_titulo_id($artefato_page_id);
								$artefato_materia_concurso_id = return_concurso_id_materia($artefato_page_id);
								$artefato_materia_concurso_sigla = return_concurso_sigla($artefato_materia_concurso_id);
								$artefato_titulo = $artefato_materia_titulo;
								$artefato_subtitulo = $artefato_materia_concurso_sigla;
							}
							if (!isset($artefato_link)) {
								$artefato_link = "edicao_textos.php?texto_id=$artefato_id";
							}
							if ($artefato_titulo == false) {
								$artefato_subtitulo = return_artefato_subtitulo($artefato_tipo);
							}
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$imagens_privadas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem_privada' AND user_id = $user_id ORDER BY id DESC");
						$template_id = 'imagens_privadas';
						$template_titulo = 'Imagens privadas';
						$template_classes = 'esconder_sessao';
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						$template_conteudo = false;
						
						$artefato_id = 0;
						$artefato_page_id = false;
						$artefato_titulo = 'Nova imagem privada';
						$artefato_criacao = 'Pressione para adicionar uma imagem privada';
						$artefato_tipo = 'nova_imagem';
						$artefato_link = false;
						$template_conteudo .= include 'templates/artefato_item.php';
						
						while ($imagem_privada = $imagens_privadas->fetch_assoc()) {
							$artefato_id = $imagem_privada['id'];
							$artefato_criacao = $imagem_privada['criacao'];
							$artefato_criacao = "Criado em $artefato_criacao";
							$artefato_titulo = $imagem_privada['titulo'];
							$artefato_imagem_arquivo = $imagem_privada['arquivo'];
							$artefato_estado = $imagem_privada['estado'];
							$artefato_link = "elemento.php?id=$artefato_id";
							$artefato_tipo = 'imagem_publica';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						include 'templates/page_element.php';
						
						$imagens_publicas = $conn->query("SELECT id, criacao, titulo, arquivo, estado FROM Elementos WHERE user_id = $user_id AND tipo = 'imagem' ORDER BY id DESC");
						if ($imagens_publicas->num_rows > 0) {
							$template_id = 'imagens_publicas';
							$template_titulo = 'Imagens públicas';
							$template_classes = 'esconder_sessao';
							$template_conteudo_class = 'justify-content-start';
							$template_conteudo_no_col = true;
							$template_conteudo = false;
							
							while ($imagem_publica = $imagens_publicas->fetch_assoc()) {
								$artefato_id = $imagem_publica['id'];
								$artefato_criacao = $imagem_publica['criacao'];
								$artefato_criacao = "Criado em $artefato_criacao";
								$artefato_titulo = $imagem_publica['titulo'];
								$artefato_imagem_arquivo = $imagem_publica['arquivo'];
								$artefato_estado = $imagem_publica['estado'];
								$artefato_link = "elemento.php?id=$artefato_id";
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
	$template_modal_titulo = 'Verbetes em que contribuiu';
	$template_modal_body_conteudo = false;
	if ($verbetes_escritos->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row_verbete = $verbetes_escritos->fetch_assoc()) {
			$page_id = $row_verbete['page_id'];
			$page_tipo = $row_verbete['tipo'];
			if ($page_tipo == 'verbete') {
				$topico_titulo = return_titulo_topico($page_id);
				$template_modal_body_conteudo .= "<a href='verbete.php?topico_id=$page_id' target='_blank'><li class='list-group-item list-group-item-action'>$topico_titulo</li></a>";
			} elseif ($page_tipo == 'verbete_elemento') {
				$topico_info = return_elemento_info($page_id);
				$topico_titulo = $topico_info[4];
				$template_modal_body_conteudo .= "<a href='elemento.php?id=$page_id' target='_blank'><li class='list-group-item list-group-item-action'>$topico_titulo</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_completados';
	$template_modal_titulo = 'Tópicos estudados';
	$template_modal_body_conteudo = false;
	if ($completados->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $completados->fetch_assoc()) {
			$topico_id = $row['topico_id'];
			$infotopicos = mysqli_query($conn, "SELECT id FROM Topicos WHERE id = $topico_id");
			while ($row = $infotopicos->fetch_assoc()) {
				$completed_topico_id = $row['id'];
				$completed_topico_titulo = return_titulo_topico($completed_topico_id);
				$template_modal_body_conteudo .= "<a href='verbete.php?topico_id=$completed_topico_id' target='_blank'><li class='list-group-item list-group-item-action'>$completed_topico_titulo</li></a>";
				break;
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Suas participações no fórum';
	$template_modal_body_conteudo = false;
	if ($comentarios->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $comentarios->fetch_assoc()) {
			$forum_page_id = $row['page_id'];
			$forum_page_tipo = $row['page_tipo'];
			if ($forum_page_tipo == 'topico') {
				$forum_topico_titulo = return_titulo_topico($forum_page_id);
				$template_modal_body_conteudo .= "<a href='verbete.php?topico_id=$forum_page_id' target='_blank'><li class='list-group-item list-group-item-action'><strong>Tópico:</strong> $forum_topico_titulo</li></a>";
			} elseif ($forum_page_tipo == 'elemento') {
				$forum_elemento_titulo = return_titulo_elemento($forum_page_id);
				$template_modal_body_conteudo .= "<a href='elemento.php?id=$forum_page_id' target='_blank'><li class='list-group-item list-group-item-action'><strong>Elemento:</strong> $forum_elemento_titulo</li></a>";
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	
	$template_modal_div_id = 'modal_bookmarks';
	$template_modal_titulo = 'Lista de leitura';
	$template_modal_body_conteudo = false;
	if ($bookmarks->num_rows > 0) {
		$template_modal_body_conteudo .= "<h3>Verbetes</h3>";
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $bookmarks->fetch_assoc()) {
			$bookmark_topico_id = $row['topico_id'];
			$infotopicos = mysqli_query($conn, "SELECT materia_id, id FROM Topicos WHERE id = $bookmark_topico_id");
			while ($row = $infotopicos->fetch_assoc()) {
				$bookmark_materia_id = $row['materia_id'];
				$bookmark_topico_id = $row['id'];
				$bookmark_titulo = return_titulo_topico($bookmark_topico_id);
				$template_modal_body_conteudo .= "<a href='verbete.php?topico_id=$bookmark_topico_id' target='_blank'><li class='list-group-item list-group-item-action'>$bookmark_titulo</li></a>";
				break;
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	if ($bookmarks_elementos->num_rows > 0) {
		$template_modal_body_conteudo .= "<h3 class='mt-3'>Elementos</h3>";
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $bookmarks_elementos->fetch_assoc()) {
			$elemento_id = $row['elemento_id'];
			$info_elementos = $conn->query("SELECT titulo FROM Elementos WHERE id = $elemento_id");
			if ($info_elementos->num_rows > 0) {
				while ($row = $info_elementos->fetch_assoc()) {
					$titulo_elemento = $row['titulo'];
					$template_modal_body_conteudo .= "<a href='elemento.php?id=$elemento_id' target='_blank'><li class='list-group-item list-group-item-action'>$titulo_elemento</li></a>";
				}
			}
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	
	$template_modal_div_id = 'modal_apresentacao';
	$template_modal_titulo = 'Apresentação';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<p>Sua apresentação é visível a outros usuários, que poderão visitar seu escritório ao clicar em seu apelido. Seu apelido somente é visível como identificação de suas atividades públicas na Ubwiki.";
	
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
			  <a href='edicao_textos.php?texto_id=$perfil_publico_id'><button type='button' class='$button_classes'>Editar sua apresentação</button></a>
		  </div>
	  ";
	}
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	
	$template_modal_div_id = 'modal_opcoes';
	$template_modal_titulo = 'Alterar dados e opções';
	if ($opcao_texto_justificado_value == true) {
		$texto_justificado_checked = 'checked';
	} else {
		$texto_justificado_checked = false;
	}
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
        <h3>Dados de cadastro</h3>
        <ul class='list-group'>
            <li class='list-group-item'><strong>Conta criada em:</strong> $user_criacao</li>
            <li class='list-group-item'><strong>Email:</strong> $user_email</li>
        </ul>
	";
	$template_modal_body_conteudo .= "
		<h3 class='mt-3'>Perfil</h3>
        <p>Você é identificado por seu apelido em todas as suas atividades públicas.</p>
        <div class='md-form md-2'><input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required></input>
            <label data-error='inválido' data-successd='válido' for='novo_apelido' required>Apelido</label>
        </div>
        <p>Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.</p>
        <div class='md-form md-2'>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>

            <label data-error='inválido' data-successd='válido'
                   for='novo_nome'>Nome</label>
        </div>
        <div class='md-form md-2'>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required></input>

            <label data-error='inválido' data-successd='válido' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' required>Sobrenome</label>
        </div>
        <h3>Opções</h3>
        <div class='md-form md-2'>
        	<input type='checkbox' class='form-check-input' id='opcao_texto_justificado' name='opcao_texto_justificado' $texto_justificado_checked>
        	<label class='form-check-label' for='opcao_texto_justificado'>Mostrar verbetes com texto justificado.</label>
		</div>
    ";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_nova_imagem';
	$template_modal_titulo = 'Nova imagem privada';
	$template_modal_enctype = "enctype='multipart/form-data'";
	$template_modal_body_conteudo = "
			<div class='md-form mb-2'>
            <input type='text' id='nova_imagem_titulo' name='nova_imagem_titulo'
                   class='form-control validate' required>
            <label data-error='inválido' data-success='válido'
                   for='nova_imagem_titulo'>Título da imagem</label>
            </div>
            <div class='md-form mb-2'>
            <input type='url' id='nova_imagem_link' name='nova_imagem_link'
                   class='form-control validate'>
            <label data-error='inválido' data-success='válido'
                   for='nova_imagem_link'>Link para a imagem</label>
            </div>
            <div class='md-form mb-2'>
                <div class='file-field'>
                    <div class='btn btn-primary btn-sm float-left'>
                        <span>Selecione o arquivo</span>
                        <input type='file' name='nova_imagem_upload'>
                    </div>
                    <div class='file-path-wrapper'>
                        <input class='file-path validate' type='text' placeholder='Faça upload da sua imagem'>
                    </div>
                </div>
            </div>
		";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_nova_referencia';
	$template_modal_titulo = 'Adicionar item ao acervo';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
	        <h4 class='mt-3'>Acervo virtual</h4>
	        <p>Acrescente a seu acervo virtual livros que você tem, quer ter, pretende emprestar de um amigo, assim como artigos que quer ler, revistas, até mesmo álbuns de música ou filmes.</p>
	        <p>Uma vez que seu item tenha sido adicionado, será possível marcar capítulos e escrever fichamentos específicos, assim como resenhas e resumos. Cada anotação será inicialmente privada, podendo ser tornada pública se você assim desejar.</p>
		    <div class='md-form'>
			    <input type='text' class='form-control' name='busca_referencias' id='busca_referencias' required>
			    <label for='busca_referencias'>Buscar item para adicionar à sua biblioteca virtual, por título ou autor.</label>
		    </div>
		    <div class='row' id='referencias_disponiveis'>
		    
		    </div>
		    <div class='row' id='criar_referencia_form'>
		        <div class='col-12'>
                    <div class='md-form'>
                        <input type='text' class='form-control' name='criar_referencia_titulo' id='criar_referencia_titulo' required>
                        <label for='criar_referencia_titulo'>Título da nova referência</label>
                    </div>
                    <div class='md-form'>
                        <input type='text' class='form-control' name='criar_referencia_autor' id='criar_referencia_autor' required>
                        <label for='criar_referencia_autor'>Autor da nova referência</label>
                    </div>
                    <div class='row' id='autores_disponiveis'>
                    
                    </div>
                    <select class='mdb-select md-form' name='criar_referencia_tipo' id='criar_referencia_tipo'>
                        <option value='' disabled selected>Tipo do novo item:</option>
                        <option value='referencia'>Materia de leitura: livros, artigos, páginas virtuais etc.</option>
                        <option value='video'>Material videográfico: vídeos virtuais, filmes etc.</option>
                        <option value='album_musica'>Material em áudio: álbuns de música, podcasts</option>
                    </select>
                    <div class='row justify-content-center'>
                    	<button type='button' class='$button_classes' id='trigger_adicionar_referencia'>Cadastrar esta referência e acrescentá-la a seu acervo</button>
					</div>
                </div>
            </div>
		";
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_novo_topico';
	$template_modal_titulo = 'Incluir área de interesse';
	$etiquetas_carregar_remover = false;
	include 'templates/etiquetas_modal.php';
	
	$template_modal_div_id = 'modal_novo_simulado';
	$template_modal_titulo = 'Gerar novo simulado';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
	    <p>Fazer simulados é muito importante, mas é necessário algum cuidado para que realmente ajude a trazer você mais próximo de seus objetivos. Fazer provas é uma habilidade que pode e deve ser desenvolvida pela prática, esse é seu objetivo principal ao fazer simulados, e não necessariamente o aprendizado do conteúdo.</p>
	    <p>Somente recomendamos que você comece a fazer simulados após haver estudado todo o conteúdo do seu concurso pelo menos uma vez, mesmo que em um primeiro nível introdutório e superficial.</p>
	";
	
	$template_modal_body_conteudo .= "
        <form method='post'>
        <select class='$select_classes' name='novo_simulado_tipo' required>
              <option value='' disabled selected>Tipo:</option>
              <option value='todas_objetivas_oficiais'>Todas as questões objetivas oficiais</option>
              <option value='todas_dissertativas_oficiais'>Todas as questões dissertativas oficiais</option>
              <option value='todas_dissertativas_nao_oficiais'>Todas as questões dissertativas não-oficiais</option>
              <option value='todas_objetivas_inoficiais' disabled>Todas as questões objetivas não-oficiais</option>
              <option value='inteligente' disabled>Criado por nosso algoritmo</option>
              <option value='questao_errou' disabled>Apenas questões em que você errou pelo menos um item</option>
              <option value='itens_errou' disabled>Apenas itens que você errou no passado</option>
              <option value='questoes_estudados' disabled>Apenas questões de tópicos que você marcou como estudados</option>
            </select>
    ";
	$template_modal_body_conteudo .= "
        <div class='row justify-content-center'>
            <button name='novo_simulado_trigger' class='$button_classes'>Gerar simulado</button>
        </div>
        </form>
    ";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_edicao';
	$template_modal_titulo = 'Adicionar edição do concurso';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_edicao_ano' id='nova_edicao_ano' required>
                              <label for='nova_edicao_ano'>Ano</label>
                            </div>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_edicao_titulo' id='nova_edicao_titulo' required>
                              <label for='nova_edicao_titulo'>Título</label>
                            </div>
						";
	if ($edicoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Edições registradas para o $concurso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
			$template_modal_body_conteudo .= "
				<li class='list-group-item'>$edicao_ano: $edicao_titulo</li>
			";
		}
		$template_modal_body_conteudo .= "
			</ul>
		";
	}
	$template_modal_submit_name = 'nova_edicao_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_etapa';
	$template_modal_titulo = 'Adicionar etapa da edição';
	$template_modal_body_conteudo = false;
	
	$template_modal_body_conteudo .= "
                                <select class='$select_classes' name='nova_etapa_edicao' required>
                                      <option value='' disabled selected>Edição do concurso:</option>
                                ";
	mysqli_data_seek($edicoes, 0);
	while ($edicao = $edicoes->fetch_assoc()) {
		$edicao_id = $edicao['id'];
		$edicao_ano = $edicao['ano'];
		$edicao_titulo = $edicao['titulo'];
		$template_modal_body_conteudo .= "<option value='$edicao_id'>$edicao_ano : $edicao_titulo</option>";
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                                <div class='md-form'>
                                  <input type='text' class='form-control' name='nova_etapa_titulo' id='nova_etapa_titulo' required>
                                  <label for='nova_etapa_titulo'>Título da etapa</label>
                                </div>
                            ";
	if ($etapas->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Etapas registradas para o $concurso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($etapa = $etapas->fetch_assoc()) {
			$etapa_titulo = $etapa['titulo'];
			$etapa_edicao_id = $etapa['edicao_id'];
			$edicoes = $conn->query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
			while ($edicao = $edicoes->fetch_assoc()) {
				$edicao_ano = $edicao['ano'];
				$edicao_titulo = $edicao['titulo'];
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>$edicao_ano: $edicao_titulo: $etapa_titulo</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_submit_name = 'nova_etapa_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_prova';
	$template_modal_titulo = 'Adicionar prova da etapa';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_etapa' required>
                                  <option value='' disabled selected>Etapa do concurso:</option>";
	mysqli_data_seek($etapas, 0);
	while ($etapa = $etapas->fetch_assoc()) {
		$etapa_id = $etapa['id'];
		$etapa_titulo = $etapa['titulo'];
		$etapa_edicao_id = $etapa['edicao_id'];
		mysqli_data_seek($edicoes, 0);
		$edicoes = $conn->query("SELECT ano, titulo FROM sim_edicoes WHERE id = $etapa_edicao_id");
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
		}
		$template_modal_body_conteudo .= "<option value='$etapa_id'>$edicao_ano: $edicao_titulo: $etapa_titulo</option>";
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_prova_tipo' required>
                              <option value='' disabled selected>Tipo de prova:</option>
                              <option value='1'>Objetiva</option>
                              <option value='2'>Dissertativa</option>
                              <option value='3'>Oral</option>
                              <option value='4'>Física</option>
                            </select>
                            <div class='md-form'>
                              <input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo' required>
                              <label for='nova_prova_titulo'>Título da prova</label>
                            </div>
                        ";
	if ($provas->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3>Provas registradas para o $concurso_sigla</h3>
			<ul class='list-group'>
		";
		while ($prova = $provas->fetch_assoc()) {
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo ($prova_tipo_string)</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	$template_modal_submit_name = 'nova_prova_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_texto_apoio';
	$template_modal_titulo = 'Adicionar texto de apoio';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                            <div class='form-check pl-0'>
                                <input id='novo_texto_apoio_origem' name='novo_texto_apoio_origem' type='checkbox' class='form-check-input' checked>
                                <label class='form-check-label' for='novo_texto_apoio_origem'>Texto de apoio oficial do concurso.</label>
                            </div>
						";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='novo_texto_apoio_prova' required>
                              <option value='' disabled selected>Selecione a prova:</option>
                              <option value='0'>Texto de apoio não é oficial</option>";
	if ($provas->num_rows > 0) {
		while ($prova = $provas->fetch_assoc()) {
			$prova_id = $prova['id'];
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<option value='$prova_id'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='text' class='form-control' name='novo_texto_apoio_titulo' id='novo_texto_apoio_titulo' required>
                              <label for='novo_texto_apoio_titulo'>Título do texto de apoio</label>
                            </div>
						";
	
	$template_modal_form_id = 'form_novo_texto_apoio';
	$template_modal_body_conteudo .= "<h3>Enunciado:</h3>";
	$sim_quill_id = 'texto_apoio_enunciado';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Texto de apoio:</h3>";
	$sim_quill_id = 'texto_apoio';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	if ($textos_apoio->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3 class='mt-3'>Textos de apoio registrados para o $concurso_sigla:</h3>
			<ul class='list-group'>
		";
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$texto_apoio_id = $texto_apoio['id'];
			$texto_apoio_origem = $texto_apoio['origem'];
			if ($texto_apoio_origem == 1) {
				$texto_apoio_origem_string = 'oficial';
			} else {
				$texto_apoio_origem_string = 'não-oficial';
			}
			$texto_apoio_prova_id = $texto_apoio['prova_id'];
			$texto_apoio_titulo = $texto_apoio['titulo'];
			$find_prova_info = return_info_prova_id($texto_apoio_prova_id);
			$prova_titulo = $find_prova_info[0];
			$prova_tipo = $find_prova_info[1];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_edicao_ano = $find_prova_info[2];
			$prova_edicao_titulo = $find_prova_info[3];
			$template_modal_body_conteudo .= "<li class='list-group-item'>$prova_edicao_ano: $prova_edicao_titulo: $texto_apoio_titulo</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	
	
	$template_modal_submit_name = 'novo_texto_apoio_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_adicionar_questao';
	$template_modal_titulo = 'Adicionar questão';
	$template_modal_form_id = 'form_nova_questao';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
                <h3>Dados gerais</h3>
            ";
	$template_modal_body_conteudo .= "
						<div class='form-check pl-0'>
                            <input id='nova_questao_origem' name='nova_questao_origem' type='checkbox' class='form-check-input' checked>
                            <label class='form-check-label' for='nova_questao_origem'>Questão oficial do concurso.</label>
                        </div>
						";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_texto_apoio'>
                              <option value='' disabled selected>Selecione o texto de apoio:</option>
                              <option value='0'>Questão não tem texto de apoio</option>
                              ";
	mysqli_data_seek($textos_apoio, 0);
	if ($textos_apoio->num_rows > 0) {
		while ($texto_apoio = $textos_apoio->fetch_assoc()) {
			$texto_apoio_id = $texto_apoio['id'];
			$texto_apoio_origem = $texto_apoio['origem'];
			if ($texto_apoio_origem == 1) {
				$texto_apoio_origem_string = 'oficial';
			} else {
				$texto_apoio_origem_string = 'não-oficial';
			}
			$texto_apoio_prova_id = $texto_apoio['prova_id'];
			$texto_apoio_titulo = $texto_apoio['titulo'];
			$find_prova_info = return_info_prova_id($texto_apoio_prova_id);
			$prova_titulo = $find_prova_info[0];
			$prova_tipo = $find_prova_info[1];
			$prova_tipo_string = convert_prova_tipo($prova_tipo);
			$prova_edicao_ano = $find_prova_info[2];
			$prova_edicao_titulo = $find_prova_info[3];
			$template_modal_body_conteudo .= "<option value='$texto_apoio_id'>$prova_edicao_ano: $texto_apoio_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "<p class='mt-2'>Se a questão não tem texto de apoio, é necessário indicar a prova de que faz parte:</p>";
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_prova'>
                                <option value='' disabled selected>Selecione a prova:</option>
                                <option value='0'>Questão livre e não-oficial, não faz parte de prova</option>
    ";
	mysqli_data_seek($provas, 0);
	if ($provas->num_rows > 0) {
		while ($prova = $provas->fetch_assoc()) {
			$prova_id = $prova['id'];
			$prova_etapa_id = $prova['etapa_id'];
			$prova_titulo = $prova['titulo'];
			$prova_tipo = $prova['tipo'];
			$prova_etapa_titulo = return_etapa_titulo_id($prova_etapa_id);
			$prova_etapa_edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
			if ($prova_etapa_edicao_ano_e_titulo != false) {
				$prova_etapa_edicao_ano = $prova_etapa_edicao_ano_e_titulo[0];
				$prova_etapa_edicao_titulo = $prova_etapa_edicao_ano_e_titulo[1];
			} else {
				break;
			}
			$template_modal_body_conteudo .= "<option value='$prova_id'>$prova_etapa_edicao_ano: $prova_etapa_edicao_titulo: $prova_etapa_titulo: $prova_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	
	$template_modal_body_conteudo .= "
                            <select class='$select_classes' name='nova_questao_materia' required>
                              <option value='' disabled selected>Selecione a matéria:</option>
                        ";
	if ($materias->num_rows > 0) {
		while ($materia = $materias->fetch_assoc()) {
			$materia_id = $materia['id'];
			$materia_titulo = $materia['titulo'];
			$template_modal_body_conteudo .= "<option value='$materia_id'>$materia_titulo</option>";
		}
	}
	$template_modal_body_conteudo .= "</select>";
	$template_modal_body_conteudo .= "
                            <div class='md-form'>
                              <input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero' required>
                              <label for='nova_questao_numero'>Número da questão</label>
                            </div>
						";
	$template_modal_body_conteudo .= "
                <select class='mdb-select md-form' name='nova_questao_tipo' required>
                  <option value='' disabled selected>Selecione o tipo da questão:</option>
                  <option value='1'>Certo e errado</option>
                  <option value='2'>Múltipla escolha</option>
                  <option value='3'>Dissertativa</option>
                </select>
            ";
	$template_modal_body_conteudo .= "<h3>Enunciado</h3>";
	$sim_quill_id = 'questao_enunciado';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 1</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item1_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do primeiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item1';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 2</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item2_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do segundo item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item2';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 3</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item3_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do terceiro item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item3';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 4</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item4_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quarto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item4';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	$template_modal_body_conteudo .= "<h3 class='mt-3'>Item 5</h3>";
	$template_modal_body_conteudo .= "
                            <select class='mdb-select md-form' name='nova_questao_item5_gabarito'>
                                <option value='' disabled selected>Selecione o gabarito do quinto item</option>
                                <option value='1'>Certo</option>
                                <option value='2'>Errado</option>
                                <option value='0'>Anulado</option>
                            </select>
						";
	$sim_quill_id = 'questao_item5';
	$sim_quill_form = include('templates/sim_quill.php');
	$template_modal_body_conteudo .= $sim_quill_form;
	
	$questoes = $conn->query("SELECT edicao_ano, numero, materia, tipo FROM sim_questoes WHERE concurso_id = $concurso_id AND origem = 1");
	if ($questoes->num_rows > 0) {
		$template_modal_body_conteudo .= "
			<h3 class='mt-3'>Questões registradas para o $concurso_sigla</h3>
			<ul class='list-group'>
		";
		while ($questao = $questoes->fetch_assoc()) {
			$questao_edicao_ano = $questao['edicao_ano'];
			$questao_numero = $questao['numero'];
			$questao_materia = $questao['materia'];
			$questao_materia_titulo = return_materia_titulo_id($questao_materia);
			$questao_tipo = $questao['tipo'];
			$template_modal_body_conteudo .= "<li>$questao_edicao_ano: $questao_materia_titulo: Questão $questao_numero</li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	}
	
	$template_modal_submit_name = 'nova_questao_trigger';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_criar_simulado';
	$template_modal_titulo = 'Criar simulado';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<p>Para criar um simulado é preciso determinar um título e as questões que farão parte dele. Você pode fazer mudanças em seu simulado até que decida publicá-lo. Após sua publicação, não será possível alterá-lo, nem desfazer sua publicação. Caso seu simulado inclua questões dissertativas, alunos poderão pagar uma taxa, por você determinada, para que você corrija suas respostas.</p>
	";
	include 'templates/modal.php';


?>

</body>
<?php
	
	include 'templates/footer.html';
	$texto_id = 0;
	$texto_tipo = 'acervo';
	$etiquetas_bottom_adicionar = true;
	$biblioteca_bottom_adicionar = true;
	$mdb_select = true;
	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>
