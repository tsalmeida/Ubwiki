<?php
	
	if (isset($_POST['nova_imagem_titulo'])) {
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_subtipo = $_POST['nova_imagem_subtipo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		
		if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
			$nova_imagem_link = $_POST['nova_imagem_link'];
			$nova_imagem_link = base64_encode($nova_imagem_link);
			adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'link', $nova_imagem_subtipo);
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
					adicionar_imagem($target_file, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'upload', false);
				}
			}
		}
		$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_imagem')");
		$conn->query($query);
		$nao_contar = true;
	}
	
	if (isset($_POST['novo_video_link'])) {
		$novo_video_link = $_POST['novo_video_link'];
		$novo_video_data = get_youtube($novo_video_link);
		if ($novo_video_data != false) {
			$novo_video_titulo = $novo_video_data['title'];
			$novo_video_autor = $novo_video_data['author_name'];
			$novo_video_thumbnail = $novo_video_data['thumbnail_url'];
			$novo_video_iframe = $novo_video_data['html'];
			$query = prepare_query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
			$videos = $conn->query($query);
			if ($videos->num_rows > 0) {
				while ($video = $videos->fetch_assoc()) {
					$video_id = $video['id'];
					$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $video_id, 'video', $user_id)");
					$conn->query($query);
					$video_etiqueta_id = return_elemento_etiqueta_id($video_id);
				}
			} else {
				$novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
				$novo_video_etiqueta = criar_etiqueta($novo_video_titulo, $novo_video_autor, 'video', $user_id, false, $novo_video_link, 'youtube');
				$novo_video_titulo = mysqli_real_escape_string($conn, $novo_video_titulo);
				$novo_video_autor = mysqli_real_escape_string($conn, $novo_video_autor);
				$novo_video_iframe = mysqli_real_escape_string($conn, $novo_video_iframe);
				$novo_video_etiqueta_id = $novo_video_etiqueta[0];
				$query = prepare_query("INSERT INTO Elementos (etiqueta_id, tipo, subtipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ($novo_video_etiqueta_id, 'video', 'youtube', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
				$conn->query($query);
				$novo_video_elemento_id = $conn->insert_id;
				$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $novo_video_elemento_id, 'video', $user_id)");
				$conn->query($query);
			}
		}
		$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_video')");
		$conn->query($query);
		$nao_contar = true;
	}
	
	if (isset($_POST['pagina_novo_titulo'])) {
		$pagina_novo_titulo = $_POST['pagina_novo_titulo'];
		$pagina_novo_titulo = mysqli_real_escape_string($conn, $pagina_novo_titulo);
		if ($pagina_tipo == 'texto') {
			$query = prepare_query("UPDATE Textos SET titulo = '$pagina_novo_titulo' WHERE id = $pagina_texto_id");
			$conn->query($query);
		} else {
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($pagina_id, '$pagina_tipo', 'titulo', '$pagina_novo_titulo', $user_id)");
			$conn->query($query);
		}
		$pagina_titulo = $pagina_novo_titulo;
		$texto_titulo = $pagina_novo_titulo;
		$nao_contar = true;
	}
	
	if (isset($_POST['wikipedia_url'])) {
		$novo_wikipedia_url = $_POST['wikipedia_url'];
		$parse_url = parse_url($novo_wikipedia_url);
		$parse_url_domain = $parse_url['host'];
		$novo_wikipedia_titulo = $_POST['wikipedia_titulo'];
		$novo_wikipedia_titulo = mysqli_real_escape_string($conn, $novo_wikipedia_titulo);
		$novo_wikipedia_url = mysqli_real_escape_string($conn, $novo_wikipedia_url);
		$query = prepare_query("INSERT INTO Elementos (tipo, titulo, autor, autor_etiqueta_id, link, user_id) VALUES ('wikipedia', '$novo_wikipedia_titulo', 'Wikipedia', 807, '$novo_wikipedia_url', $user_id)");
		$conn->query($query);
		$novo_wiki_id = $conn->insert_id;
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($pagina_id, '$pagina_tipo', $novo_wiki_id, 'wikipedia', '$novo_wikipedia_titulo', $user_id)");
		$conn->query($query);
	}

	$query = prepare_query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id");
	$grupos_do_usuario = $conn->query($query);
	
	if ($pagina_tipo == 'questao') {
		// ENUNCIADO
		
		if (isset($_POST['quill_novo_questao_enunciado_html'])) {
			$quill_novo_questao_enunciado_html = $_POST['quill_novo_questao_enunciado_html'];
			$pagina_questao_enunciado_html = $_POST['quill_novo_questao_enunciado_html'];
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
			$pagina_questao_enunciado_content = $_POST['quill_novo_questao_enunciado_content'];
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
			$pagina_questao_item1_gabarito = $nova_questao_item1_gabarito;
			if (isset($_POST['quill_novo_questao_item1_html'])) {
				$quill_novo_questao_item1_html = $_POST['quill_novo_questao_item1_html'];
				$quill_novo_questao_item1_html = mysqli_real_escape_string($conn, $quill_novo_questao_item1_html);
				$quill_novo_questao_item1_html = "'$quill_novo_questao_item1_html'";
				$pagina_questao_item1_html = $_POST['quill_novo_questao_item1_html'];
				$quill_novo_questao_item1_text = $_POST['quill_novo_questao_item1_text'];
				$quill_novo_questao_item1_text = mysqli_real_escape_string($conn, $quill_novo_questao_item1_text);
				$quill_novo_questao_item1_text = "'$quill_novo_questao_item1_text'";
				$quill_novo_questao_item1_content = $_POST['quill_novo_questao_item1_content'];
				$quill_novo_questao_item1_content = mysqli_real_escape_string($conn, $quill_novo_questao_item1_content);
				$quill_novo_questao_item1_content = "'$quill_novo_questao_item1_content'";
				$pagina_questao_item1_content = $_POST['quill_novo_questao_item1_content'];
			}
		} else {
			$nova_questao_item1_gabarito = "NULL";
		}
		if ($quill_novo_questao_item1_html == false) {
			$quill_novo_questao_item1_html = "NULL";
			$quill_novo_questao_item1_text = "NULL";
			$quill_novo_questao_item1_content = "NULL";
		}
		// ITEM 2
		if (isset($_POST['nova_questao_item2_gabarito'])) {
			$nova_questao_item2_gabarito = $_POST['nova_questao_item2_gabarito'];
			$pagina_questao_item2_gabarito = $nova_questao_item2_gabarito;
			if (isset($_POST['quill_novo_questao_item2_html'])) {
				$quill_novo_questao_item2_html = $_POST['quill_novo_questao_item2_html'];
				$quill_novo_questao_item2_html = mysqli_real_escape_string($conn, $quill_novo_questao_item2_html);
				$quill_novo_questao_item2_html = "'$quill_novo_questao_item2_html'";
				$pagina_questao_item2_html = $_POST['quill_novo_questao_item2_html'];
				$quill_novo_questao_item2_text = $_POST['quill_novo_questao_item2_text'];
				$quill_novo_questao_item2_text = mysqli_real_escape_string($conn, $quill_novo_questao_item2_text);
				$quill_novo_questao_item2_text = "'$quill_novo_questao_item2_text'";
				$quill_novo_questao_item2_content = $_POST['quill_novo_questao_item2_content'];
				$quill_novo_questao_item2_content = mysqli_real_escape_string($conn, $quill_novo_questao_item2_content);
				$quill_novo_questao_item2_content = "'$quill_novo_questao_item2_content'";
				$pagina_questao_item2_content = $_POST['quill_novo_questao_item2_content'];
			}
		} else {
			$nova_questao_item2_gabarito = "NULL";
		}
		if ($quill_novo_questao_item2_html == false) {
			$quill_novo_questao_item2_html = "NULL";
			$quill_novo_questao_item2_text = "NULL";
			$quill_novo_questao_item2_content = "NULL";
		}
		// ITEM 3
		if (isset($_POST['nova_questao_item3_gabarito'])) {
			$nova_questao_item3_gabarito = $_POST['nova_questao_item3_gabarito'];
			$pagina_questao_item3_gabarito = $nova_questao_item3_gabarito;
			if (isset($_POST['quill_novo_questao_item3_html'])) {
				$quill_novo_questao_item3_html = $_POST['quill_novo_questao_item3_html'];
				$quill_novo_questao_item3_html = mysqli_real_escape_string($conn, $quill_novo_questao_item3_html);
				$quill_novo_questao_item3_html = "'$quill_novo_questao_item3_html'";
				$pagina_questao_item3_html = $_POST['quill_novo_questao_item3_html'];
				$quill_novo_questao_item3_text = $_POST['quill_novo_questao_item3_text'];
				$quill_novo_questao_item3_text = mysqli_real_escape_string($conn, $quill_novo_questao_item3_text);
				$quill_novo_questao_item3_text = "'$quill_novo_questao_item3_text'";
				$quill_novo_questao_item3_content = $_POST['quill_novo_questao_item3_content'];
				$quill_novo_questao_item3_content = mysqli_real_escape_string($conn, $quill_novo_questao_item3_content);
				$quill_novo_questao_item3_content = "'$quill_novo_questao_item3_content'";
				$pagina_questao_item3_content = $_POST['quill_novo_questao_item3_content'];
			}
		} else {
			$nova_questao_item3_gabarito = "NULL";
		}
		if ($quill_novo_questao_item3_html == false) {
			$quill_novo_questao_item3_html = "NULL";
			$quill_novo_questao_item3_text = "NULL";
			$quill_novo_questao_item3_content = "NULL";
		}
		// ITEM 4
		if (isset($_POST['nova_questao_item4_gabarito'])) {
			$nova_questao_item4_gabarito = $_POST['nova_questao_item4_gabarito'];
			$pagina_questao_item4_gabarito = $nova_questao_item4_gabarito;
			if (isset($_POST['quill_novo_questao_item4_html'])) {
				$quill_novo_questao_item4_html = $_POST['quill_novo_questao_item4_html'];
				$quill_novo_questao_item4_html = mysqli_real_escape_string($conn, $quill_novo_questao_item4_html);
				$quill_novo_questao_item4_html = "'$quill_novo_questao_item4_html'";
				$pagina_questao_item4_html = $_POST['quill_novo_questao_item4_html'];
				$quill_novo_questao_item4_text = $_POST['quill_novo_questao_item4_text'];
				$quill_novo_questao_item4_text = mysqli_real_escape_string($conn, $quill_novo_questao_item4_text);
				$quill_novo_questao_item4_text = "'$quill_novo_questao_item4_text'";
				$quill_novo_questao_item4_content = $_POST['quill_novo_questao_item4_content'];
				$quill_novo_questao_item4_content = mysqli_real_escape_string($conn, $quill_novo_questao_item4_content);
				$quill_novo_questao_item4_content = "'$quill_novo_questao_item4_content'";
				$pagina_questao_item4_content = $_POST['quill_novo_questao_item4_content'];
			}
		} else {
			$nova_questao_item4_gabarito = "NULL";
		}
		if ($quill_novo_questao_item4_html == false) {
			$quill_novo_questao_item4_html = "NULL";
			$quill_novo_questao_item4_text = "NULL";
			$quill_novo_questao_item4_content = "NULL";
		}
		// ITEM 5
		if (isset($_POST['nova_questao_item5_gabarito'])) {
			$nova_questao_item5_gabarito = $_POST['nova_questao_item5_gabarito'];
			$pagina_questao_item5_gabarito = $nova_questao_item5_gabarito;
			if (isset($_POST['quill_novo_questao_item5_html'])) {
				$quill_novo_questao_item5_html = $_POST['quill_novo_questao_item5_html'];
				$quill_novo_questao_item5_html = mysqli_real_escape_string($conn, $quill_novo_questao_item5_html);
				$quill_novo_questao_item5_html = "'$quill_novo_questao_item5_html'";
				$pagina_questao_item5_html = $_POST['quill_novo_questao_item5_html'];
				$quill_novo_questao_item5_text = $_POST['quill_novo_questao_item5_text'];
				$quill_novo_questao_item5_text = mysqli_real_escape_string($conn, $quill_novo_questao_item5_text);
				$quill_novo_questao_item5_text = "'$quill_novo_questao_item5_text'";
				$quill_novo_questao_item5_content = $_POST['quill_novo_questao_item5_content'];
				$quill_novo_questao_item5_content = mysqli_real_escape_string($conn, $quill_novo_questao_item5_content);
				$quill_novo_questao_item5_content = "'$quill_novo_questao_item5_content'";
				$pagina_questao_item5_content = $_POST['quill_novo_questao_item5_content'];
			}
		} else {
			$nova_questao_item5_gabarito = "NULL";
		}
		if ($quill_novo_questao_item5_html == false) {
			$quill_novo_questao_item5_html = "NULL";
			$quill_novo_questao_item5_text = "NULL";
			$quill_novo_questao_item5_content = "NULL";
		}
		
		// GABARITOS
		/*if (isset($_POST['nova_questao_item2_gabarito'])) {
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
		}*/
		
		if (isset($_POST['trigger_modal_questao_dados'])) {
			$query = prepare_query("UPDATE sim_questoes SET enunciado_html = '$quill_novo_questao_enunciado_html', enunciado_text = '$quill_novo_questao_enunciado_text', enunciado_content = '$quill_novo_questao_enunciado_content', item1_html = $quill_novo_questao_item1_html, item1_text = $quill_novo_questao_item1_text, item1_content = $quill_novo_questao_item1_content, item2_html = $quill_novo_questao_item2_html, item2_text = $quill_novo_questao_item2_text, item2_content = $quill_novo_questao_item2_content, item3_html = $quill_novo_questao_item3_html, item3_text = $quill_novo_questao_item3_text, item3_content = $quill_novo_questao_item3_content, item4_html = $quill_novo_questao_item4_html, item4_text = $quill_novo_questao_item4_text, item4_content = $quill_novo_questao_item4_content, item5_html = $quill_novo_questao_item5_html, item5_text = $quill_novo_questao_item5_text, item5_content = $quill_novo_questao_item5_content, item1_gabarito = $nova_questao_item1_gabarito, item2_gabarito = $nova_questao_item2_gabarito, item3_gabarito = $nova_questao_item3_gabarito, item4_gabarito = $nova_questao_item4_gabarito, item5_gabarito = $nova_questao_item5_gabarito WHERE id = $pagina_item_id");
			$conn->query($query);
		}
		
		if (isset($_POST['nova_questao_texto_de_apoio_id'])) {
			$nova_questao_texto_de_apoio_id = $_POST['nova_questao_texto_de_apoio_id'];
			if ($nova_questao_texto_de_apoio_id == 'novo') {
				$novo_texto_apoio_titulo = "Texto de apoio da questão $pagina_questao_numero";
				$query = prepare_query("INSERT INTO sim_textos_apoio (origem, curso_id, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id) VALUES ($pagina_questao_origem, $pagina_questao_curso_id, $pagina_questao_prova_id, '$novo_texto_apoio_titulo', false, false, false, false, false, false, $user_id)");
				$conn->query($query);
				$novo_texto_apoio_id = $conn->insert_id;
				$query = prepare_query("UPDATE sim_questoes SET texto_apoio_id = $novo_texto_apoio_id WHERE id = $pagina_item_id");
				$conn->query($query);
				$query = prepare_query("INSERT INTO Paginas (tipo, item_id) VALUES ('texto_apoio', $novo_texto_apoio_id)");
				$conn->query($query);
				$novo_texto_apoio_pagina_id = $conn->insert_id;
				$query = prepare_query("UPDATE sim_textos_apoio SET pagina_id = $novo_texto_apoio_pagina_id WHERE id = $novo_texto_apoio_id");
				$conn->query($query);
				$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_texto_apoio_pagina_id, 'texto_apoio', 'titulo', '$novo_texto_apoio_titulo', $user_id)");
				$conn->query($query);
				
				$pagina_questao_texto_apoio_id = $novo_texto_apoio_id;
				$pagina_questao_texto_apoio_pagina_id = $novo_texto_apoio_pagina_id;
			}
		}
	}
	
	if ($pagina_tipo == 'texto_apoio') {
		
		$novo_texto_apoio_titulo = $pagina_texto_apoio_titulo;
		
		if (isset($_POST['novo_texto_apoio_titulo'])) {
			$novo_texto_apoio_titulo = $_POST['novo_texto_apoio_titulo'];
			$pagina_texto_apoio_titulo = $novo_texto_apoio_titulo;
			
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
				$pagina_texto_apoio_enunciado_content = $_POST['quill_novo_texto_apoio_enunciado_content'];
				$pagina_texto_apoio_enunciado_html = $_POST['quill_novo_texto_apoio_enunciado_html'];
			}
			if (isset($_POST['quill_novo_texto_apoio_html'])) {
				$novo_texto_apoio_html = $_POST['quill_novo_texto_apoio_html'];
				$novo_texto_apoio_html = mysqli_real_escape_string($conn, $novo_texto_apoio_html);
				$novo_texto_apoio_text = $_POST['quill_novo_texto_apoio_text'];
				$novo_texto_apoio_text = mysqli_real_escape_string($conn, $novo_texto_apoio_text);
				$novo_texto_apoio_content = $_POST['quill_novo_texto_apoio_content'];
				$novo_texto_apoio_content = mysqli_real_escape_string($conn, $novo_texto_apoio_content);
				$pagina_texto_apoio_content = $_POST['quill_novo_texto_apoio_content'];
				$pagina_texto_apoio_html = $_POST['quill_novo_texto_apoio_html'];
			}

			$query = prepare_query("UPDATE sim_textos_apoio SET titulo = '$novo_texto_apoio_titulo', enunciado_html = '$novo_texto_apoio_enunciado_html', enunciado_text = '$novo_texto_apoio_enunciado_text', enunciado_content = '$novo_texto_apoio_enunciado_content', texto_apoio_html = '$novo_texto_apoio_html', texto_apoio_text = '$novo_texto_apoio_text', texto_apoio_content = '$novo_texto_apoio_content' WHERE id = $pagina_item_id");
			$conn->query($query);
			
		}
	}
	
	if ($pagina_tipo == 'escritorio') {
		if (isset($_POST['aderir_novo_curso'])) {
			$aderir_novo_curso = $_POST['aderir_novo_curso'];
			$query = prepare_query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'curso', $aderir_novo_curso)");
			$conn->query($query);
		}
		
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
	}

?>