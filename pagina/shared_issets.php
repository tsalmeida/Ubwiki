<?php

	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		$conn->query("UPDATE Paginas SET estado = $novo_estado_pagina WHERE id = $pagina_id");
		$pagina_estado = $novo_estado_pagina;
		$nao_contar = true;
	}
	
	if (isset($_POST['nova_imagem_titulo'])) {
		
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_titulo = mysqli_real_escape_string($conn, $nova_imagem_titulo);
		
		if ((isset($_POST['nova_imagem_link'])) && ($_POST['nova_imagem_link'] != false)) {
			$nova_imagem_link = $_POST['nova_imagem_link'];
			$nova_imagem_link = base64_encode($nova_imagem_link);
			adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'link', $curso_id);
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
					adicionar_imagem($target_file, $nova_imagem_titulo, $pagina_id, $user_id, $pagina_tipo, 'upload', $curso_id);
				}
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_imagem')");
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
			$videos = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
			if ($videos->num_rows > 0) {
				while ($video = $videos->fetch_assoc()) {
					$video_id = $video['id'];
					$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $video_id, 'video', $user_id)");
					$video_etiqueta_id = return_elemento_etiqueta_id($video_id);
				}
			} else {
				$novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
				$novo_video_etiqueta = criar_etiqueta($novo_video_titulo, $novo_video_autor, 'video', $user_id, false);
				$novo_video_titulo = mysqli_real_escape_string($conn, $novo_video_titulo);
				$novo_video_autor = mysqli_real_escape_string($conn, $novo_video_autor);
				$novo_video_iframe = mysqli_real_escape_string($conn, $novo_video_iframe);
				$novo_video_etiqueta_id = $novo_video_etiqueta[0];
				$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ($novo_video_etiqueta_id, 'video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
				$novo_video_elemento_id = $conn->insert_id;
				$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($pagina_id, $novo_video_elemento_id, 'video', $user_id)");
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $pagina_id, '$pagina_tipo', 'adicionar_video')");
		$nao_contar = true;
	}
	
	if (isset($_POST['pagina_novo_titulo'])) {
		$pagina_novo_titulo = $_POST['pagina_novo_titulo'];
		$pagina_novo_titulo = mysqli_real_escape_string($conn, $pagina_novo_titulo);
		if ($pagina_tipo == 'texto') {
			$conn->query("UPDATE Textos SET titulo = '$pagina_novo_titulo' WHERE id = $pagina_texto_id");
		} else {
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($pagina_id, '$pagina_tipo', 'titulo', '$pagina_novo_titulo', $user_id)");
		}
		$pagina_titulo = $pagina_novo_titulo;
		$texto_titulo = $pagina_novo_titulo;
		$nao_contar = true;
	}
	
	if (isset($_POST['wikipedia_url'])) {
		$novo_wikipedia_url = $_POST['wikipedia_url'];
		$novo_wikipedia_titulo = $_POST['wikipedia_titulo'];
		$conn->query("INSERT INTO Elementos (tipo, titulo, autor, autor_etiqueta_id) VALUES ('wikipedia', '$novo_wikipedia_titulo', 'wikipedia', 807)");
		$novo_wiki_id = $conn->insert_id;
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra) VALUES ($pagina_id, '$pagina_tipo', $novo_wiki_id, 'wikipedia', '$novo_wikipedia_titulo')");
	}
	
	$grupos_do_usuario = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id");

?>