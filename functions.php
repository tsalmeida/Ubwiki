<?php

	function return_wallet_value($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT endstate FROM Transactions WHERE user_id = $user_id ORDER BY id DESC");
		$wallet_contents = $conn->query($query);
		if ($wallet_contents->num_rows > 0) {
			while ($wallet_content = $wallet_contents->fetch_assoc()) {
				$wallet_content_endstate = $wallet_content['endstate'];
				return $wallet_content_endstate;
			}
		} else {
			return false;
		}
	}

	function prepare_query()
	{
		$args = func_get_args();
		$query = $args[0];
		//$args[1] = 'log';
		//$args[1] = 'print';
		if (isset($args[1])) {
			$extra = $args[1];
			switch ($extra) {
				case 'print':
					print "<p class='text-danger'>$query</p>";
					break;
				case 'log':
					error_log($query);
					break;
				default:
					error_log("$extra: $query");
			}
		}
		return $query;
	}

	function extract_gdoc($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$position = strpos($output, "<body");
		$body = substr($output, $position);
		$body = str_replace("</html>", "", $body);
		curl_close($ch);
		return $body;
	}

	function standard_jumbotron($titulo, $link)
	{
		echo "
		<div class='container-fluid p-0 m-0 text-center'>
		  <div class='jumbotron col-12 mb-0 elegant-color text-white'>
			";
		if ($link == false) {
			echo "<h1 class='h1-responsive logo-jumbotron d-sm-inline d-md-none'>$titulo</h1>";
			echo "<span class='display-2 logo-jumbotron d-none d-md-inline'>$titulo</span>";
		} else {
			echo "<a href='$link'><h1 class='h1-responsive logo-jumbotron d-sm-inline d-md-none'>$titulo</h1></a>";
			echo "<a href='$link'><span class='display-2 logo-jumbotron d-none d-md-inline'>$titulo</span></a>";
		}
		echo "
		  </div>
		</div>
		";
	}

	function generateRandomString()
	{
		//options: 'integers', 'letters'; default is 'mixed'
		$args = func_get_args();
		$length = $args[0];
		if (isset($args[1])) {
			$type = $args[1];
		} else {
			$type = 'mixed';
		}
		if (!isset($length)) {
			$length = 8;
		}
		if ($type == 'integers') {
			return substr(str_shuffle("0123456789"), 0, $length);
		} elseif ($type == 'letters') {
			return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		} elseif ($type == 'capsintegers') {
			return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789'), 0, $length);
		} else {
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
	}


	function make_thumb()
	{
		$filename = func_get_args();
		$filename = $filename[0];
		$check = substr($filename, -4);
		$check = strtolower($check);

		/* read the source image */
		$original = "../imagens/verbetes/$filename";
		if (($check == ".jpg") || ($check == "jpeg")) {
			$source_image = imagecreatefromjpeg($original);
		} elseif ($check == ".png") {
			$source_image = imagecreatefrompng($original);
		} elseif ($check == ".gif") {
			$source_image = imagecreatefromgif($original);
		} else {
			return false;
		}

		$width = imagesx($source_image);
		$height = imagesy($source_image);

		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = 300;
		$desired_width = floor($desired_height * ($width / $height));

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

		if (($check == ".png") || ($check = ".gif")) {
			imagecolortransparent($virtual_image, imagecolorallocatealpha($virtual_image, 0, 0, 0, 127));
			imagealphablending($virtual_image, false);
			imagesavealpha($virtual_image, true);
		}

		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

		/* create the physical thumbnail image to its destination */
		$prefix = "../imagens/verbetes/thumbnails/";
		$destination = "$prefix$filename";
		if (($check == ".jpg") || ($check == "jpeg")) {
			imagejpeg($virtual_image, "$destination");
		} elseif ($check == ".png") {
			imagepng($virtual_image, "$destination");
		} elseif ($check == ".gif") {
			imagegif($virtual_image, "$destination");
		}
		$x = 'x';
		if ($width > $height) {
			$orientacao = 'paisagem';
		} else {
			$orientacao = 'retrato';
		}
		$resolucao_original = "$width$x$height";
		$dados_da_imagem = array($resolucao_original, $orientacao);
		return $dados_da_imagem;
	}

	function adicionar_thumbnail_youtube($youtube_thumbnail_original)
	{
		$randomfilename = generateRandomString(12);
		$randomfilename .= '.jpg';
		$nova_imagem_arquivo = $randomfilename;
		$nova_imagem_diretorio = "../imagens/youthumb/$nova_imagem_arquivo";
		file_put_contents($nova_imagem_diretorio, fopen($youtube_thumbnail_original, 'r'));
		return $nova_imagem_arquivo;
	}

	function adicionar_imagem()
	{
		$args = func_get_args();
		$nova_imagem_link = $args[0];
		$nova_imagem_link = base64_decode($nova_imagem_link);
		$nova_imagem_titulo = $args[1];
		$pagina_id = $args[2];
		$user_id = $args[3];
		$pagina_tipo = $args[4];
		$origem = $args[5];
		$nova_imagem_subtipo = $args[6];

		include 'templates/criar_conn.php';

		$imagem_preexistente_id = false;
		$imagem_criada = false;
		$query = prepare_query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link' AND compartilhamento IS NULL");
		$check_imagem_existe = $conn->query($query);
		if ($check_imagem_existe->num_rows > 0) {
			while ($imagem_preexistente = $check_imagem_existe->fetch_assoc()) {
				$imagem_preexistente_id = $imagem_preexistente['id'];
				$nova_imagem_etiqueta_id = return_elemento_etiqueta_id($imagem_preexistente_id);
			}
		}

		if (($imagem_preexistente_id == false) || ($pagina_tipo == 'escritorio')) {
			$imagem_criada = true;
			$randomfilename = generateRandomString(16);
			$ultimo_ponto = strripos($nova_imagem_link, ".");
			$extensao = substr($nova_imagem_link, $ultimo_ponto);
			$nova_imagem_arquivo = "$randomfilename$extensao";
			$nova_imagem_diretorio = "../imagens/verbetes/$nova_imagem_arquivo";
			file_put_contents($nova_imagem_diretorio, fopen($nova_imagem_link, 'r'));
			$dados_da_imagem = make_thumb($nova_imagem_arquivo);
			if ($dados_da_imagem == false) {
				return false;
			} else {
				$nova_imagem_resolucao_original = $dados_da_imagem[0];
				$nova_imagem_orientacao = $dados_da_imagem[1];
				if ($origem == 'upload') {
					$nova_imagem_link = "https://ubwiki.com.br/imagens/verbetes/$nova_imagem_arquivo";
				}
			}
		}
		if ($pagina_tipo == 'escritorio') {
			$nova_imagem_compartilhamento = "'privado'";
			$nova_imagem_etiqueta_tipo = 'imagem_privada';
		} else {
			$nova_imagem_compartilhamento = "NULL";
			$nova_imagem_etiqueta_tipo = 'imagem';
		}
		if ($imagem_criada == true) {
			$nova_etiqueta = criar_etiqueta($nova_imagem_titulo, false, $nova_imagem_etiqueta_tipo, $user_id, false, false, $nova_imagem_subtipo);
			$nova_imagem_etiqueta_id = $nova_etiqueta[0];
			$query = prepare_query("INSERT INTO Elementos (etiqueta_id, compartilhamento, tipo, subtipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ($nova_imagem_etiqueta_id, $nova_imagem_compartilhamento, 'imagem', '$nova_imagem_subtipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
			$conn->query($query);
			$nova_imagem_id = $conn->insert_id;
		} else {
			$nova_imagem_id = $imagem_preexistente_id;
		}
		//antes de fazer isso, checar se o elemento já existe na página. Não é tão importante por que há
		//um check de duplicatas na página, mas talvez não exista no escritório.
		$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, subtipo, extra, user_id) VALUES ($pagina_id, '$pagina_tipo', $nova_imagem_id, 'imagem', '$nova_imagem_subtipo', $nova_imagem_etiqueta_id, $user_id)");
		$conn->query($query);
		if (isset($nova_imagem_arquivo)) {
			return "https://ubwiki.com.br/imagens/verbetes/$nova_imagem_arquivo";
		} else {
			return false;
		}
	}

	function get_youtube($url)
	{
		$youtube = "http://www.youtube.com/oembed?url=" . $url . "&format=json";

		$curl = curl_init($youtube);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($curl);
		curl_close($curl);
		return json_decode($return, true);
	}

	function escape_quotes($string)
	{
		$output = str_replace('"', '\"', $string);
		$output = str_replace("'", "\'", $output);
		return $output;
	}

	function return_titulo_elemento($elemento_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT titulo FROM Elementos WHERE id = $elemento_id");
		$elementos = $conn->query($query);
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_titulo = $elemento['titulo'];
				return $elemento_titulo;
			}
		}
		return false;
	}

	function return_pagina_item_id($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT item_id FROM Paginas WHERE id = $pagina_id");
		$paginas = $conn->query($query);
		if ($paginas->num_rows > 0) {
			while ($pagina = $paginas->fetch_assoc()) {
				$pagina_item_id = $pagina['item_id'];
				return $pagina_item_id;
			}
		}
		return false;
	}

	function return_gerado_info($find_simulado_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT criacao, tipo, curso_id FROM sim_gerados WHERE id = $find_simulado_id");
		$find_simulados = $conn->query($query);
		if ($find_simulados->num_rows > 0) {
			while ($find_simulado = $find_simulados->fetch_assoc()) {
				$find_simulado_criacao = $find_simulado['criacao'];
				$find_simulado_tipo = $find_simulado['tipo'];
				$find_simulado_curso_id = $find_simulado['curso_id'];
				$find_simulado_result = array($find_simulado_criacao, $find_simulado_tipo, $find_simulado_curso_id);
				return $find_simulado_result;
			}
		}
		return false;
	}

	function return_apelido_user_id($find_user_id)
	{
		include 'templates/criar_conn.php';
		if ($find_user_id == false) {
			return false;
		}
		$query = prepare_query("SELECT apelido FROM Usuarios WHERE id = $find_user_id");
		$result_find_apelido = $conn->query($query);
		if ($result_find_apelido->num_rows > 0) {
			while ($row_find_apelido = $result_find_apelido->fetch_assoc()) {
				$found_apelido = $row_find_apelido['apelido'];
			}
			if ($found_apelido == false) {
				$found_apelido = 'anônimo';
			}
			return $found_apelido;
		}
		return false;
	}

	function return_etapa_titulo_id($etapa_id)
	{
		if ($etapa_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT titulo FROM sim_etapas WHERE id = $etapa_id");
		$result_find_titulo = $conn->query($query);
		if ($result_find_titulo->num_rows > 0) {
			while ($row_find_titulo = $result_find_titulo->fetch_assoc()) {
				$found_titulo = $row_find_titulo['titulo'];
			}
			return $found_titulo;
		}
		return false;
	}

	function return_etapa_edicao_ano_e_titulo($etapa_id)
	{
		if ($etapa_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT edicao_id FROM sim_etapas WHERE id = $etapa_id");
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$edicao_id = $row['edicao_id'];
				$query = prepare_query("SELECT ano, titulo FROM sim_edicoes WHERE id = $edicao_id");
				$result2 = $conn->query($query);
				if ($result2->num_rows > 0) {
					while ($row2 = $result2->fetch_assoc()) {
						$edicao_ano = $row2['ano'];
						$edicao_titulo = $row2['titulo'];
						$edicao_ano_e_titulo = array($edicao_ano, $edicao_titulo);
						return $edicao_ano_e_titulo;
					}
				}
			}
		}
		return false;
	}

	function return_info_prova_id($prova_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT etapa_id, titulo, tipo FROM sim_provas WHERE id = $prova_id");
		$provas = $conn->query($query);
		if ($provas->num_rows > 0) {
			while ($prova = $provas->fetch_assoc()) {
				$prova_titulo = $prova['titulo']; // 0
				$prova_tipo = $prova['tipo']; // 1
				$prova_etapa_id = $prova['etapa_id']; // 4
				$prova_etapa_info = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
				$prova_etapa_ano = $prova_etapa_info[0]; // 5
				$prova_etapa_titulo = $prova_etapa_info[1]; // 6
				$edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
				$edicao_ano = $edicao_ano_e_titulo[0]; // 2
				$edicao_titulo = $edicao_ano_e_titulo[1]; // 3
				$result = array($prova_titulo, $prova_tipo, $edicao_ano, $edicao_titulo, $prova_etapa_id, $prova_etapa_ano, $prova_etapa_titulo);
				return $result;
			}
		}
		return false;
	}

	function return_texto_apoio_prova_id($texto_apoio_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT prova_id FROM sim_textos_apoio WHERE id = $texto_apoio_id");
		$textos_apoio = $conn->query($query);
		if ($textos_apoio->num_rows > 0) {
			while ($texto_apoio = $textos_apoio->fetch_assoc()) {
				$texto_apoio_prova_id = $texto_apoio['prova_id'];
				return $texto_apoio_prova_id;
			}
		}
		return false;
	}

	function convert_prova_tipo($prova_tipo)
	{
		if ($prova_tipo == 1) {
			return 'objetiva';
		} elseif ($prova_tipo == 2) {
			return 'dissertativa';
		} elseif ($prova_tipo == 3) {
			return 'oral';
		} elseif ($prova_tipo == 4) {
			return 'física';
		}
		return false;
	}

	function return_estado_icone($pagina_estado)
	{
		switch ($pagina_estado) {
			case 1:
				$icone = 'fad fa-acorn fa-fw';
				$color = 'text-info';
				break;
			case 2:
				$icone = 'fad fa-seedling fa-fw';
				$color = 'text-danger';
				break;
			case 3:
				$icone = 'fad fa-leaf fa-fw';
				$color = 'text-success';
				break;
			case 4:
				$icone = 'fad fa-spa fa-fw';
				$color = 'text-warning';
				break;
			case 0:
			default:
				$icone = 'fal fa-acorn fa-fw';
				$color = 'text-muted';
				break;
		}
		return array($icone, $color);
	}

	function convert_gabarito_cor($gabarito)
	{
		if ($gabarito == 3) {
			return 'list-group-item-warning';
		} elseif ($gabarito == 1) {
			return 'list-group-item-success';
		} elseif ($gabarito == 2) {
			return 'list-group-item-danger';
		} else {
			return false;
		}
	}

	function fix_link($link)
	{
		include 'templates/criar_conn.php';
		if ($ret = parse_url($link)) {

			if (!isset($ret["scheme"])) {
				$link = "http://{$link}";
			}
		}
		$link = mysqli_real_escape_string($conn, $link);
		return $link;
	}

	function criar_etiqueta()
		//criar_etiqueta($titulo, $autor, $tipo, $user_id, $criar_elemento, $link, $subtipo);
	{
		$args = func_get_args();
		$titulo = $args[0];
		$autor = $args[1];
		$tipo = $args[2];
		$user_id = $args[3];
		$criar_elemento = $args[4];
		$link = false;
		$subtipo = false;
		if (isset($args[5])) {
			$link = $args[5];
		}
		if (isset($args[6])) {
			$subtipo = $args[6];
		}
		if ($link == false) {
			$link = "NULL";
		} else {
			$link = fix_link($link);
			$link = "'$link'";
		}
		include 'templates/criar_conn.php';
		$nova_etiqueta_id = false;
		$nova_etiqueta_autor_id = false;
		$nova_etiqueta_criada = false;
		$novo_elemento_criado = false;
		$novo_elemento_id = false;
		if ($autor != false) {
			$autor = mysqli_real_escape_string($conn, $autor);
			$query = prepare_query("SELECT id FROM Etiquetas WHERE titulo = '$autor' AND tipo = 'autor'");
			$etiquetas = $conn->query($query);
			if ($etiquetas->num_rows == 0) {
				$query = prepare_query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$autor', 'autor', $user_id)");
				$conn->query($query);
				$nova_etiqueta_autor_id = $conn->insert_id;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$nova_etiqueta_autor_id = $etiqueta['id'];
				}
			}
		}
		if (($autor != false) && ($titulo != false)) {
			$nova_etiqueta = "$titulo / $autor";
			$nova_etiqueta = stripslashes($nova_etiqueta);
			$nova_etiqueta = mysqli_real_escape_string($conn, $nova_etiqueta);
			$query = prepare_query("SELECT id FROM Etiquetas WHERE titulo = '$nova_etiqueta' AND tipo = '$tipo'");
			$etiquetas = $conn->query($query);
			if ($etiquetas->num_rows == 0) {
				$query = prepare_query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$nova_etiqueta', '$tipo', $user_id)");
				$conn->query($query);
				$nova_etiqueta_id = $conn->insert_id;
				$nova_etiqueta_criada = true;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$nova_etiqueta_id = $etiqueta['id'];
				}
			}
		} elseif (($autor == false) || ($titulo != false)) {
			$titulo = mysqli_real_escape_string($conn, $titulo);
			$query = prepare_query("SELECT id FROM Etiquetas WHERE titulo = '$titulo' AND tipo = '$tipo'");
			$etiquetas = $conn->query($query);
			if ($etiquetas->num_rows == 0) {
				$query = prepare_query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$titulo', '$tipo', $user_id)");
				$conn->query($query);
				$nova_etiqueta_id = $conn->insert_id;
				$nova_etiqueta_criada = true;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$nova_etiqueta_id = $etiqueta['id'];
				}
			}
		}
		if ($criar_elemento == true) {
			if ($nova_etiqueta_criada == true) {
				if ($tipo == false) {
					$tipo = 'referencia';
				}
				if ($nova_etiqueta_autor_id == false) {
					$nova_etiqueta_autor_id = "NULL";
				}
				$query = prepare_query("INSERT INTO Elementos (etiqueta_id, tipo, subtipo, titulo, autor, autor_etiqueta_id, user_id, link) VALUES ($nova_etiqueta_id, '$tipo', '$subtipo', '$titulo', '$autor', $nova_etiqueta_autor_id, $user_id, $link)");
				$conn->query($query);
				$novo_elemento_id = $conn->insert_id;
				$novo_elemento_criado = true;
			} else {
				$novo_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
			}
		}
		return array($nova_etiqueta_id, $nova_etiqueta_autor_id, $novo_elemento_id, $nova_etiqueta_criada, $novo_elemento_criado);
	}

	function return_etiqueta_elemento_id($etiqueta_id)
	{
		if ($etiqueta_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT id FROM Elementos WHERE etiqueta_id = $etiqueta_id");
		$elementos = $conn->query($query);
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$found_elemento_id = $elemento['id'];
				return $found_elemento_id;
			}
		}
		return false;
	}

	function return_etiqueta_topico_id($etiqueta_id)
	{
		if ($etiqueta_id == false) {
			return false;
		}
		/*include 'templates/criar_conn.php';
		$query = prepare_query("SELECT id FROM Topicos WHERE etiqueta_id = $etiqueta_id";
		$topicos = $conn->query($query);
		if ($topicos->num_rows > 0) {
			while ($topico = $topicos->fetch_assoc()) {
				$topico_id = $topico['id'];
				return $topico_id;
			}
		}*/
		return false;
	}

	function return_etiqueta_info($etiqueta_id)
	{
		if ($etiqueta_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT criacao, tipo, titulo, user_id, pagina_id FROM Etiquetas WHERE id = $etiqueta_id");
		$etiquetas = $conn->query($query);
		if ($etiquetas->num_rows > 0) {
			while ($etiqueta = $etiquetas->fetch_assoc()) {
				$etiqueta_criacao = $etiqueta['criacao']; // 0
				$etiqueta_tipo = $etiqueta['tipo']; // 1
				$etiqueta_titulo = $etiqueta['titulo']; // 2
				$etiqueta_user_id = $etiqueta['user_id']; // 3
				$etiqueta_pagina_id = $etiqueta['pagina_id']; // 4
				if (is_null($etiqueta_pagina_id)) {
					$etiqueta_pagina_id = return_pagina_id($etiqueta_id, 'etiqueta');
				}
				$etiqueta_info = array($etiqueta_criacao, $etiqueta_tipo, $etiqueta_titulo, $etiqueta_user_id, $etiqueta_pagina_id);
				return $etiqueta_info;
			}
		}
	}

	function return_etiqueta_cor_icone($etiqueta_tipo)
	{
		if ($etiqueta_tipo == 'curso') {
			$etiqueta_icone = 'fa-books';
			$etiqueta_cor = 'blue-grey';
		} elseif ($etiqueta_tipo == 'anotacao_publica') {
			$etiqueta_icone = 'fa-file';
			$etiqueta_cor = 'blue';
		} elseif ($etiqueta_tipo == 'topico') {
			$etiqueta_icone = 'fa-tag';
			$etiqueta_cor = 'amber';
		} elseif ($etiqueta_tipo == 'imagem') {
			$etiqueta_icone = 'fa-image-polaroid';
			$etiqueta_cor = 'red';
		} elseif ($etiqueta_tipo == 'referencia') {
			$etiqueta_icone = 'fa-book';
			$etiqueta_cor = 'green';
		} elseif ($etiqueta_tipo == 'autor') {
			$etiqueta_icone = 'fa-user';
			$etiqueta_cor = 'light-green';
		} elseif ($etiqueta_tipo == 'video') {
			$etiqueta_icone = 'fa-film';
			$etiqueta_cor = 'lime';
		} elseif ($etiqueta_tipo == 'album_musica') {
			$etiqueta_icone = 'fa-microphone';
			$etiqueta_cor = 'teal';
		} else {
			return false;
		}
		return array($etiqueta_cor, $etiqueta_icone);
	}

	function return_elemento_etiqueta_id($elemento_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT etiqueta_id FROM Elementos WHERE id = $elemento_id");
		$etiquetas = $conn->query($query);
		if ($etiquetas->num_rows > 0) {
			while ($etiqueta = $etiquetas->fetch_assoc()) {
				$etiqueta_id = $etiqueta['etiqueta_id'];
				return $etiqueta_id;
			}
		}
		return false;
	}

	function converter_respostas($tipo, $resposta)
	{
		if ($resposta == 1) {
			return 'certo';
		} elseif ($resposta == 2) {
			return 'errado';
		} elseif ($resposta == 0) {
			if ($tipo == 'resposta') {
				return 'em branco';
			} elseif ($tipo == 'gabarito') {
				return 'anulado';
			}
		} else {
			return false;
		}
	}

	function converter_simulado_tipo($simulado_tipo)
	{
		if ($simulado_tipo == 'todas_objetivas_oficiais') {
			return 'Todas as questões objetivas oficiais';
		} elseif ($simulado_tipo == 'todas_dissertativas_oficiais') {
			return 'Todas as questões dissertativas oficiais';
		}
	}

	function update_etiqueta_elemento($elemento_id, $user_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT etiqueta_id, autor_etiqueta_id, tipo, titulo, autor FROM Elementos WHERE id = $elemento_id");
		$elementos = $conn->query($query);
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_etiqueta_id = $elemento['etiqueta_id'];
				$elemento_autor_etiqueta_id = $elemento['autor_etiqueta_id'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				if ($elemento_autor != false) {
					$elemento_etiqueta_novo_titulo = "$elemento_titulo / $elemento_autor";
					if ($elemento_autor_etiqueta_id != false) {
						$query = prepare_query("SELECT id FROM Etiquetas WHERE id = $elemento_autor_etiqueta_id AND titulo = '$elemento_autor'");
						$autores = $conn->query($query);
						if ($autores->num_rows == 0) {
							if ($conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('autor', '$elemento_autor', $user_id)") === true) {
								$novo_autor_id = $conn->insert_id;
								$conn->query("UPDATE Elementos SET autor_etiqueta_id = $novo_autor_id WHERE id = $elemento_id");
							}
						}
					} else {
						if ($conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('autor', '$elemento_autor', $user_id)") === true) {
							$novo_autor_id = $conn->insert_id;
							$conn->query("UPDATE Elementos SET autor_etiqueta_id = $novo_autor_id WHERE id = $elemento_id");
						}
					}
				} else {
					$elemento_etiqueta_novo_titulo = $elemento_titulo;
				}
				$conn->query("UPDATE Etiquetas SET titulo = '$elemento_etiqueta_novo_titulo' WHERE id = $elemento_etiqueta_id");
			}
		}
	}

	function return_elemento_info($elemento_id)
	{
		if ($elemento_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT * FROM Elementos WHERE id = $elemento_id");
		$elementos = $conn->query($query);
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_estado = $elemento['estado']; // 0
				$elemento_etiqueta_id = $elemento['etiqueta_id']; // 1
				$elemento_criacao = $elemento['criacao']; // 2
				$elemento_tipo = $elemento['tipo']; // 3
				$elemento_titulo = $elemento['titulo']; // 4
				$elemento_autor = $elemento['autor']; // 5
				$elemento_autor_etiqueta_id = $elemento['autor_etiqueta_id']; // 6
				$elemento_capitulo = $elemento['capitulo']; // 7
				$elemento_ano = $elemento['ano']; // 8
				$elemento_link = $elemento['link']; // 9
				$elemento_iframe = $elemento['iframe']; // 10
				$elemento_arquivo = $elemento['arquivo']; // 11
				$elemento_resolucao = $elemento['resolucao']; // 12
				$elemento_orientacao = $elemento['orientacao']; // 13
				$elemento_comentario = $elemento['comentario']; // 14
				$elemento_trecho = $elemento['trecho']; // 15
				$elemento_user_id = $elemento['user_id']; // 16
				$elemento_compartilhamento = $elemento['compartilhamento']; // 17
				$elemento_subtipo = $elemento['subtipo']; // 18
				$elemento_pagina_id = $elemento['pagina_id']; // 19
				if ($elemento_pagina_id == false) {
					$elemento_pagina_id = return_pagina_id($elemento_id, 'elemento');
				}
				$result = array($elemento_estado, $elemento_etiqueta_id, $elemento_criacao, $elemento_tipo, $elemento_titulo, $elemento_autor, $elemento_autor_etiqueta_id, $elemento_capitulo, $elemento_ano, $elemento_link, $elemento_iframe, $elemento_arquivo, $elemento_resolucao, $elemento_orientacao, $elemento_comentario, $elemento_trecho, $elemento_user_id, $elemento_compartilhamento, $elemento_subtipo, $elemento_pagina_id);
				return $result;
			}
		}
		return false;
	}

	function return_texto_info($texto_id)
	{
		include 'templates/criar_conn.php';
		if ($texto_id == false) {
			return false;
		}
		$query = prepare_query("SELECT curso_id, tipo, titulo, page_id, criacao, verbete_html, verbete_text, verbete_content, user_id, pagina_id, pagina_tipo, compartilhamento, texto_pagina_id FROM Textos WHERE id = $texto_id");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_curso_id = $texto['curso_id']; // 0
				$texto_tipo = $texto['tipo']; // 1
				$texto_titulo = $texto['titulo']; // 2
				$texto_page_id = $texto['page_id']; // 3
				$texto_criacao = $texto['criacao']; // 4
				$texto_verbete_html = $texto['verbete_html']; // 5
				$texto_verbete_text = $texto['verbete_text']; // 6
				$texto_verbete_content = $texto['verbete_content']; // 7
				$texto_user_id = $texto['user_id']; // 8
				$texto_pagina_id = $texto['pagina_id']; // 9
				if ($texto_titulo == false) {
					$texto_titulo = return_pagina_titulo($texto_pagina_id);
				}
				$texto_pagina_tipo = $texto['pagina_tipo']; // 10
				$texto_compartilhamento = $texto['compartilhamento']; // 11
				if ($texto_tipo == 'anotacoes') {
					$texto_compartilhamento = 'privado';
				}
				$texto_texto_pagina_id = $texto['texto_pagina_id']; // 12
				if ($texto_texto_pagina_id == false) {
					$texto_texto_pagina_id = return_pagina_id($texto_id, 'texto');
				}
				$texto_results = array($texto_curso_id, $texto_tipo, $texto_titulo, $texto_page_id, $texto_criacao, $texto_verbete_html, $texto_verbete_text, $texto_verbete_content, $texto_user_id, $texto_pagina_id, $texto_pagina_tipo, $texto_compartilhamento, $texto_texto_pagina_id);
				return $texto_results;
			}
		}
		return false;
	}

	function return_grupo_titulo_id($grupo_id)
	{
		include 'templates/criar_conn.php';
		$grupos = $conn->query("SELECT titulo FROM Grupos WHERE id = $grupo_id");
		if ($grupos->num_rows > 0) {
			while ($grupo = $grupos->fetch_assoc()) {
				$grupo_titulo = $grupo['titulo'];
				return $grupo_titulo;
			}
		}
		return false;
	}

	function return_grupo_info($grupo_id)
	{
		if ($grupo_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$grupos = $conn->query("SELECT criacao, titulo, estado, pagina_id, user_id FROM Grupos WHERE id = $grupo_id");
		if ($grupos->num_rows > 0) {
			while ($grupo = $grupos->fetch_assoc()) {
				$grupo_criacao = $grupo['criacao']; // 0
				$grupo_titulo = $grupo['titulo']; // 1
				$grupo_estado = $grupo['estado']; // 2
				$grupo_pagina_id = $grupo['pagina_id']; // 3
				$grupo_user_id = $grupo['user_id']; // 4
				$result = array($grupo_criacao, $grupo_titulo, $grupo_estado, $grupo_pagina_id, $grupo_user_id);
				return $result;
			}
		}
		return false;
	}

	function return_artefato_subtitulo($artefato_tipo)
	{
		if ($artefato_tipo == 'anotacoes_user') {
			$artefato_subtitulo = 'Página do usuário';
		} elseif ($artefato_tipo == 'anotacao_privada') {
			$artefato_subtitulo = 'Texto sem título';
		} elseif ($artefato_tipo == 'anotacoes_elemento') {
			$artefato_subtitulo = 'Nota de referência';
		} elseif ($artefato_tipo == 'anotacoes_prova') {
			$artefato_subtitulo = 'Nota de prova';
		} elseif ($artefato_tipo == 'anotacoes_texto_apoio') {
			$artefato_subtitulo = 'Nota de texto de apoio';
		} elseif ($artefato_tipo == 'anotacoes_questao') {
			$artefato_subtitulo = 'Nota de questão';
		} elseif ($artefato_tipo == 'anotacoes_materia') {
			$artefato_subtitulo = 'Nota de estudos';
		} elseif ($artefato_tipo == 'anotacoes_topico
		') {
			$artefato_subtitulo = 'Nota de estudos';
		} elseif ($artefato_tipo == 'anotacoes_admin') {
			$artefato_subtitulo = 'Notas dos administradores';
		} else {
			$artefato_subtitulo = $artefato_tipo;
		}
		return $artefato_subtitulo;
	}

	function check_membro_grupo($user_id, $grupo_id)
	{
		include 'templates/criar_conn.php';
		if (($user_id == false) || ($grupo_id == false)) {
			return false;
		} else {
			$grupos = $conn->query("SELECT * FROM Membros WHERE grupo_id = $grupo_id AND membro_user_id = $user_id AND estado = 1");
			if ($grupos->num_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
	}


	function return_avatar($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		$user_opcoes = return_user_opcoes($user_id);
		if (isset($user_opcoes['avatar'])) {
			$avatar = $user_opcoes['avatar'][1];
		} else {
			$avatar = 'fad fa-user';
		}
		if (isset($user_opcoes['avatar_cor'])) {
			$avatar_cor = $user_opcoes['avatar_cor'][1];
		} else {
			$avatar_cor = 'text-primary';
		}
		return array($avatar, $avatar_cor);
	}

	function return_quill_initial_state($template_id)
	{
		if ($template_id == 'verbete') {
			return 'leitura';
		} elseif ($template_id == 'anotacoes') {
			return 'edicao';
		}
	}

	function return_pagina_id($item_id, $tipo)
	{
		if ($item_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		if ($tipo == 'elemento') {
			$elemento_info = return_elemento_info($item_id);
			if ($elemento_info == false) {
				return false;
			}
			$elementos = $conn->query("SELECT pagina_id FROM Elementos WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($elementos->num_rows > 0) {
				while ($elemento = $elementos->fetch_assoc()) {
					$elemento_pagina_id = $elemento['pagina_id'];
					return $elemento_pagina_id;
				}
			} else {
				$elementos = $conn->query("SELECT compartilhamento, user_id FROM Elementos WHERE id = $item_id");
				if ($elementos->num_rows > 0) {
					while ($elemento = $elementos->fetch_assoc()) {
						$elemento_compartilhamento = $elemento['compartilhamento'];
						$elemento_user_id = $elemento['user_id'];
					}
				}
				if (is_null($elemento_compartilhamento)) {
					$elemento_compartilhamento = "'$elemento_compartilhamento'";
				} else {
					$elemento_compartilhamento = "NULL";
				}
				$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($item_id, 'elemento', $elemento_compartilhamento, $elemento_user_id)");
				$elemento_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Elementos SET pagina_id = $elemento_pagina_id WHERE id= $item_id");
				return $elemento_pagina_id;
			}
		} elseif ($tipo == 'curso') {
			$curso_sigla = return_curso_sigla($item_id);
			if ($curso_sigla == false) {
				return false;
			}
			$cursos = $conn->query("SELECT pagina_id FROM Cursos WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($cursos->num_rows > 0) {
				while ($curso = $cursos->fetch_assoc()) {
					$curso_pagina_id = $curso['pagina_id'];
					return $curso_pagina_id;
				}
			} else {
				$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'curso')");
				$curso_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Cursos SET pagina_id = $curso_pagina_id WHERE id = $item_id");
				return $curso_pagina_id;
			}
		} elseif ($tipo == 'grupo') {
			$grupo_titulo = return_grupo_titulo_id($item_id);
			if ($grupo_titulo == false) {
				return false;
			}
			$query = prepare_query("SELECT pagina_id FROM Grupos WHERE id = $item_id AND pagina_id IS NOT NULL");
			$grupos = $conn->query($query);
			if ($grupos->num_rows > 0) {
				while ($grupo = $grupos->fetch_assoc()) {
					$grupo_pagina_id = $grupo['pagina_id'];
					return $grupo_pagina_id;
				}
			} else {
				$query = prepare_query("INSERT INTO Paginas (item_id, tipo, compartilhamento) VALUES ($item_id, 'grupo', 'grupo')");
				$conn->query($query);
				$grupo_pagina_id = $conn->insert_id;
				$query = prepare_query("UPDATE Grupos SET pagina_id = $grupo_pagina_id WHERE id = $item_id");
				$conn->query($query);
				return $grupo_pagina_id;
			}
		} elseif ($tipo == 'texto') {
			$texto_info = return_texto_info($item_id);
			if ($texto_info == false) {
				return false;
			} else {
				$texto_compartilhamento = $texto_info[11];
				if (is_null($texto_compartilhamento)) {
					$texto_compartilhamento = "NULL";
				} else {
					$texto_compartilhamento = "'$texto_compartilhamento'";
				}
			}
			$query = prepare_query("SELECT texto_pagina_id FROM Textos WHERE id = $item_id AND texto_pagina_id IS NOT NULL");
			$textos = $conn->query($query);
			if ($textos->num_rows > 0) {
				while ($texto = $textos->fetch_assoc()) {
					$texto_pagina_id = $texto['texto_pagina_id'];
					return $texto_pagina_id;
				}
			} else {
				$texto_user_id = $texto_info[8];
				$query = prepare_query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($item_id, 'texto', $texto_compartilhamento, $texto_user_id)");
				$conn->query($query);
				$texto_pagina_id = $conn->insert_id;
				$query = prepare_query("UPDATE Textos SET texto_pagina_id = $texto_pagina_id WHERE id = $item_id");
				$conn->query($query);
				return $texto_pagina_id;
			}
		} elseif ($tipo == 'etiqueta') {
			$query = prepare_query("SELECT titulo, pagina_id FROM Etiquetas WHERE id = $item_id");
			$etiquetas = $conn->query($query);
			if ($etiquetas->num_rows == 0) {
				return false;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$etiqueta_titulo = $etiqueta['titulo'];
					$etiqueta_pagina_id = $etiqueta['pagina_id'];
					if (is_null($etiqueta_pagina_id)) {
						$query = prepare_query("INSERT INTO Paginas (item_id, tipo, subtipo) VALUES ($item_id, 'pagina', 'etiqueta')");
						$conn->query($query);
						$etiqueta_pagina_id = $conn->insert_id;
						$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($etiqueta_pagina_id, 'pagina', 'titulo', '$etiqueta_titulo')");
						$conn->query($query);
						$query = prepare_query("UPDATE Etiquetas SET pagina_id = $etiqueta_pagina_id WHERE id = $item_id");
						$conn->query($query);
					}
					return $etiqueta_pagina_id;
				}
			}
		} elseif ($tipo == 'escritorio') {
			$query = prepare_query("SELECT pagina_id FROM Usuarios WHERE id = $item_id AND pagina_id IS NOT NULL");
			$usuarios = $conn->query($query);
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_pagina_id = $usuario['pagina_id'];
				}
			} else {
				$query = prepare_query("INSERT INTO Paginas (item_id, tipo, compartilhamento) VALUES ($item_id, 'escritorio', 'privado')");
				$conn->query($query);
				$usuario_pagina_id = $conn->insert_id;
				$query = prepare_query("UPDATE Usuarios SET pagina_id = $usuario_pagina_id WHERE id = $item_id");
				$conn->query($query);
			}
			return $usuario_pagina_id;
		} elseif ($tipo == 'questao') {
			$query = prepare_query("SELECT pagina_id FROM sim_questoes WHERE id = $item_id");
			$questoes = $conn->query($query);
			if ($questoes->num_rows > 0) {
				while ($questao = $questoes->fetch_assoc()) {
					$questao_pagina_id = $questao['pagina_id'];
					if ($questao_pagina_id == false) {
						$query = prepare_query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'questao')");
						$conn->query($query);
						$questao_pagina_id = $conn->insert_id;
						$query = prepare_query("UPDATE sim_questoes SET pagina_id = $questao_pagina_id WHERE id = $item_id");
						$conn->query($query);
					}
					return $questao_pagina_id;
				}
			}
		} elseif ($tipo == 'texto_apoio') {
			$query = prepare_query("SELECT pagina_id FROM sim_textos_apoio WHERE id = $item_id");
			$textos_apoio = $conn->query($query);
			if ($textos_apoio->num_rows > 0) {
				while ($texto_apoio = $textos_apoio->fetch_assoc()) {
					$texto_apoio_pagina_id = $texto_apoio['pagina_id'];
					if ($texto_apoio_pagina_id == false) {
						$query = prepare_query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'texto_apoio')");
						$conn->query($query);
						$texto_apoio_pagina_id = $conn->insert_id;
						$query = prepare_query("UPDATE sim_textos_apoio SET pagina_id = $texto_apoio_pagina_id WHERE id = $item_id");
						$conn->query($query);
					}
					return $texto_apoio_pagina_id;
				}
			}
		} elseif ($tipo == 'nexus') {
			$query = prepare_query("SELECT pagina_id FROM Nexus WHERE user_id = $item_id");
			$nexus = $conn->query($query);
			if ($nexus->num_rows > 0) {
				while ($nexo = $nexus->fetch_assoc()) {
					$nexo_pagina_id = $nexo['pagina_id'];
				}
			} else {
				$query = prepare_query("INSERT INTO Paginas (user_id, item_id, tipo) values ($item_id, $item_id, 'nexus')");
				$conn->query($query);
				$nexo_pagina_id = $conn->insert_id;
				$query = prepare_query("INSERT INTO Nexus (user_id, pagina_id) VALUES ($item_id, $nexo_pagina_id)");
				$conn->query($query);
			}
			return $nexo_pagina_id;
		} elseif ($tipo == 'plano') {
			$query = prepare_query("SELECT pagina_id FROM Planos WHERE id = $item_id");
			$planos = $conn->query($query);
			if ($planos->num_rows > 0) {
				while ($plano = $planos->fetch_assoc()) {
					$plano_pagina_id = $plano['pagina_id'];
					return $plano_pagina_id;
				}
			} else {
				return false;
			}
		} elseif ($tipo == 'simulado') {
			$query = prepare_query("SELECT pagina_id FROM Simulados WHERE id = $item_id");
			$simulados = $conn->query($query);
			if ($simulados->num_rows > 0) {
				while ($simulado = $simulados->fetch_assoc()) {
					$simulado_pagina_id = $simulado['pagina_id'];
					return $simulado_pagina_id;
				}
			} else {
				return false;
			}
		}
		return false;
	}

	function return_elemento_id_pagina_id($pagina_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT id FROM Elementos WHERE pagina_id = $pagina_id");
		$elementos = $conn->query($query);
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				return $elemento_id;
			}
		}
	}

	function return_pagina_titulo($pagina_id)
	{
		$args = func_get_args();
		$pagina_id = $args[0];
		$pagina_tipo = false;
		$pagina_subtipo = false;
		$pagina_item_id = false;
		$pagina_titulo = false;
		if (isset($args[1])) {
			$pagina_tipo = $args[1];
		}
		if (isset($args[2])) {
			$pagina_subtipo = $args[2];
		}
		if (isset($args[3])) {
			$pagina_item_id = $args[3];
		}
		if ($pagina_id == false) {
			return false;
		}
		if ($pagina_tipo == false) {
			$pagina_info = return_pagina_info($pagina_id, false, false, false);
			$pagina_tipo = $pagina_info[2];
			$pagina_subtipo = $pagina_info[8];
			$pagina_item_id = $pagina_info[1];
		}
		$buscar_pagina = false;
		if ($pagina_subtipo == 'Plano de estudos') {
			$parent_pagina_id = $pagina_item_id;
			$parent_pagina_titulo = return_pagina_titulo($parent_pagina_id);
			$pagina_titulo = $parent_pagina_titulo;
		} else {
			switch ($pagina_tipo) {
				case 'topico':
				case 'materia':
				case 'curso':
					$buscar_pagina = true;
					break;
				case 'etiqueta':
					$pagina_titulo = false;
					break;
				case 'grupo':
					$pagina_titulo = return_grupo_titulo_id($pagina_item_id);
					break;
				case 'texto':
					$pagina_texto_info = return_texto_info($pagina_item_id);
					$pagina_titulo = $pagina_texto_info[2];
					break;
				case 'elemento':
					$pagina_titulo = return_titulo_elemento($pagina_item_id);
					break;
				case 'questao':
					$pagina_titulo = return_questao_titulo($pagina_item_id);
					break;
				case 'escritorio':
					$pagina_titulo = return_apelido_user_id($pagina_item_id);
					break;
				case 'nexus':
					$pagina_titulo = 'Nexus';
					break;
				default:
					$buscar_pagina = true;
			}
		}
		if ($buscar_pagina == true) {
			include 'templates/criar_conn.php';
			$query = prepare_query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'titulo' ORDER BY id DESC");
			$paginas_elementos = $conn->query($query);
			if ($paginas_elementos->num_rows > 0) {
				while ($pagina_elemento = $paginas_elementos->fetch_assoc()) {
					$pagina_titulo = $pagina_elemento['extra'];
					break;
				}
			}
		}
		return $pagina_titulo;
	}

	// retorna-se um array com: (tipo, curso_id, materia_id, nivel)
	function return_familia($pagina_id)
	{
		$result = false;
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$nivel1_id = $pagina_id;
		$nivel1_info = return_pagina_info($nivel1_id);
		$nivel1_tipo = $nivel1_info[2];
		$nivel2_id = $nivel1_info[1];
		if ($nivel1_tipo == 'curso') {
			$result = array('curso', $nivel1_id, false, false, false, false, false, false);
		} elseif ($nivel1_tipo == 'materia') {
			$result = array('materia', $nivel2_id, $nivel1_id, false, false, false, false, false);
		} elseif ($nivel1_tipo == 'topico') {
			// sabe-se que a página é um tópico, agora é necessário saber seu nível.
			$nivel2_info = return_pagina_info($nivel2_id);
			$nivel2_tipo = $nivel2_info[2];
			$nivel3_id = $nivel2_info[1];
			if ($nivel2_tipo == 'materia') {
				$result = array(1, $nivel3_id, $nivel2_id, $nivel1_id, false, false, false, false);
			} elseif ($nivel2_tipo == 'topico') {
				//sabe-se que é um subtópico, a questão agora é, de que nível?
				$nivel3_info = return_pagina_info($nivel3_id);
				$nivel3_tipo = $nivel3_info[2];
				$nivel4_id = $nivel3_info[1];
				if ($nivel3_tipo == 'materia') {
					$result = array(2, $nivel4_id, $nivel3_id, $nivel2_id, false, false, false, false);
				} else {
					// sabe-se que o item é subtópico de outro subtópico.
					$nivel4_info = return_pagina_info($nivel4_id);
					$nivel4_tipo = $nivel4_info[2];
					$nivel5_id = $nivel4_info[1];
					if ($nivel4_tipo == 'materia') {
						$result = array(3, $nivel5_id, $nivel4_id, $nivel3_id, $nivel2_id, $nivel1_id, false, false);
					} else {
						// o item é subtópico de outro subtópico de outro subtópico
						$nivel5_info = return_pagina_info($nivel5_id);
						$nivel5_tipo = $nivel5_info[2];
						$nivel6_id = $nivel5_info[1];
						if ($nivel5_tipo == 'materia') {
							// subtópico nível 4
							$result = array(4, $nivel6_id, $nivel5_id, $nivel4_id, $nivel3_id, $nivel2_id, $nivel1_id, false);
						} else {
							// subtópico nível 5
							$nivel6_info = return_pagina_info($nivel6_id);
							$nivel7_id = $nivel6_info[1];
							$result = array(5, $nivel7_id, $nivel6_id, $nivel5_id, $nivel4_id, $nivel3_id, $nivel2_id, $nivel1_id);
						}
					}
				}
			}
		} elseif ($nivel1_tipo == 'secao') {
			$pagina_secao_parent = $nivel1_info[1];
			$result = array('secao', $pagina_secao_parent, false, false, false, false, false, false);
		} else {
			$result = array($nivel1_tipo, $pagina_id, false, false, false, false, false, false);
		}
		return $result;
	}

	function return_pagina_info()
	{
		$args = func_get_args();
		$pagina_id = $args[0];
		$find_titulo = false;
		$find_publicacao = false;
		$find_colaboracao = false;
		if (isset($args[1])) {
			$find_titulo = $args[1];
		}
		if (isset($args[2])) {
			$find_publicacao = $args[2];
		}
		if (isset($args[3])) {
			$find_colaboracao = $args[3];
		}
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT * FROM Paginas WHERE id = $pagina_id");
		$paginas = $conn->query($query);
		if ($paginas->num_rows > 0) {
			while ($pagina = $paginas->fetch_assoc()) {
				$pagina_criacao = $pagina['criacao']; // 0
				$pagina_item_id = $pagina['item_id']; // 1
				$pagina_tipo = $pagina['tipo']; // 2
				$pagina_estado = $pagina['estado']; // 3
				if ($pagina_estado == 0) {
					if ($pagina_tipo == 'materia') {
						$pagina_estado = 1;
					}
				}
				$pagina_compartilhamento = $pagina['compartilhamento']; // 4
				$pagina_user_id = $pagina['user_id']; // 5
				if ($find_titulo == true) {
					$pagina_titulo = return_pagina_titulo($pagina_id, $pagina_tipo, $pagina['subtipo'], $pagina_item_id); // 6
				} else {
					$pagina_titulo = false;
				}
				$pagina_etiqueta_id = $pagina['etiqueta_id']; // 7
				$pagina_subtipo = $pagina['subtipo']; // 8
				if ($find_publicacao == true) {
					$pagina_publicacao = return_publicacao($pagina_id); // 9
				} else {
					$pagina_publicacao = false;
				}
				if ($find_colaboracao == true) {
					$pagina_colaboracao = return_colaboracao($pagina_id); // 10
				} else {
					$pagina_colaboracao = false;
				}
				$pagina_link = $pagina['link'];
				$result = array($pagina_criacao, $pagina_item_id, $pagina_tipo, $pagina_estado, $pagina_compartilhamento, $pagina_user_id, $pagina_titulo, $pagina_etiqueta_id, $pagina_subtipo, $pagina_publicacao, $pagina_colaboracao, $pagina_link);
				return $result;
			}
		}
		return false;
	}

	function return_list_color_page_type($pagina_tipo)
	{
		if ($pagina_tipo == 'elemento') {
			return 'list-group-item-success';
		} elseif (($pagina_tipo == 'topico') || ($pagina_tipo == 'materia') || ($pagina_tipo == 'curso')) {
			return 'list-group-item-warning';
		} elseif ($pagina_tipo == 'grupo') {
			return 'list-group-item-info';
		} elseif ($pagina_tipo == 'pagina') {
			return 'list-group-item-info';
		} elseif ($pagina_tipo == 'texto') {
			return 'list-group-item-primary';
		} elseif ($pagina_tipo == 'secao') {
			return 'list-group-item-success';
		} elseif ($pagina_tipo == 'sistema') {
			return 'list-group-item-secondary';
		} elseif ($pagina_tipo == 'resposta') {
			return 'list-group-item-danger';
		}
	}

	function return_texto_id($pagina_tipo, $template_id, $pagina_id, $user_id)
	{
		if (($pagina_tipo == false) || ($template_id == false) || ($pagina_id == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		if ($pagina_tipo == 'texto') {
			$query = prepare_query("SELECT id FROM Textos WHERE pagina_tipo IS NULL AND texto_pagina_id = $pagina_id AND tipo = '$template_id'");
			$textos = $conn->query($query);
		} else {
			if ($template_id == 'anotacoes') {
				$query_extra = " AND user_id = $user_id";
			} else {
				$query_extra = false;
			}
			$query = prepare_query("SELECT id FROM Textos WHERE pagina_tipo = '$pagina_tipo' AND pagina_id = $pagina_id AND tipo = '$template_id'$query_extra");
			$textos = $conn->query($query);
		}
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				return $texto_id;
			}
		} else {
			return false;
		}
		return false;
	}

	function return_privilegio_edicao()
	{
		$args = func_get_args();
		$item_id = $args[0];
		$user_id = $args[1];
		if (isset($args[2])) {
			$user_editor_paginas_id = $args[2];
		} else {
			$user_editor_paginas_id = array();
		}
		if (in_array($item_id, $user_editor_paginas_id)) {
			return true;
		}
		if (($item_id == false) || ($user_id == false)) {
			return false;
		}
		$pagina_colaboracao = return_colaboracao($item_id);
		if ($pagina_colaboracao == 'aberta') {
			return true;
		} else {
			if (($pagina_colaboracao == 'exclusiva') || ($pagina_colaboracao == 'selecionada')) {
				$pagina_info = return_pagina_info($item_id);
				$pagina_user_id = $pagina_info[5];
				if ($user_id == $pagina_user_id) {
					return true;
				} else {
					return false;
				}
			}
			if ($pagina_colaboracao == 'selecionada') {
				include 'templates/criar_conn.php';
				$query = prepare_query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
				$membros = $conn->query($query);
				if ($membros->num_rows > 0) {
					while ($membro = $membros->fetch_assoc()) {
						$membro_grupo_id = $membro['grupo_id'];
						$query = prepare_query("SELECT id FROM Compartilhamentos WHERE tipo = 'colaborador' AND recipiente_id = $membro_grupo_id AND estado = 1");
						$compartilhamentos = $conn->query($query);
						if ($compartilhamentos->num_rows > 0) {
							return true;
						}
					}
				}
				$query = prepare_query("SELECT id FROM Compartilhamentos WHERE tipo = 'colaborador' AND recipiente_id = $user_id AND estado = 1");
				$compartilhamentos = $conn->query($query);
				if ($compartilhamentos->num_rows > 0) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

	function return_compartilhamento()
	{
		$args = func_get_args();
		$item_id = $args[0];
		$user_id = $args[1];

		if ($item_id == false) {
			return false;
		}
		$check_publicacao = return_publicacao($item_id);
		if (($check_publicacao == 'internet') || ($check_publicacao == 'ubwiki')) {
			return true;
		}
		$item_pagina_info = return_pagina_info($item_id, false, false, false);
		if ($item_pagina_info == false) {
			return false;
		}
		$item_pagina_user_id = $item_pagina_info[5];
		$item_pagina_compartilhamento = $item_pagina_info[4];
		$item_pagina_tipo = $item_pagina_info[2];
		if ($item_pagina_tipo == 'texto') {
			$item_pagina_revisao = check_review_state($item_id);
			if ($item_pagina_revisao == true) {
				$user_info = return_usuario_info($user_id);
				$user_tipo = $user_info[0];
				$user_revisor = check_revisor($user_tipo);
				if ($user_revisor == true) {
					return true;
				}
			}
		}
		if ($item_pagina_compartilhamento != 'privado') {
			return true;
		}
		if ($user_id == $item_pagina_user_id) {
			return true;
		}
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
		$membros = $conn->query($query);
		if ($membros->num_rows > 0) {
			while ($membro = $membros->fetch_assoc()) {
				$membro_grupo_id = $membro['grupo_id'];
				$query = prepare_query("SELECT id FROM Compartilhamento WHERE tipo = 'acesso' AND item_id = $item_id AND recipiente_id = $membro_grupo_id AND estado = 1");
				$compartilhamentos = $conn->query($query);
				if ($compartilhamentos->num_rows > 0) {
					return true;
				}
			}
		}
		$query = prepare_query("SELECT id FROM Compartilhamento WHERE tipo = 'acesso' AND item_id = $item_id AND recipiente_id = $user_id AND estado = 1 AND compartilhamento = 'usuario'");
		$compartilhamentos = $conn->query($query);
		if ($compartilhamentos->num_rows > 0) {
			return true;
		}
		return false;
	}

	function return_publicacao($item_id)
	{
		if ($item_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT compartilhamento FROM Compartilhamento WHERE item_id = $item_id AND tipo = 'publicacao' AND estado = 1 ORDER BY id DESC");
		$publicacao = $conn->query($query);
		if ($publicacao->num_rows > 0) {
			while ($publicacao_tipo = $publicacao->fetch_assoc()) {
				$publicacao_ativa = $publicacao_tipo['compartilhamento'];
				return $publicacao_ativa;
			}
		} else {
			return 'privado';
		}
		return 'privado';
	}

	function return_colaboracao($item_id)
	{
		if ($item_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT compartilhamento FROM Compartilhamento WHERE tipo = 'colaboracao' AND estado = 1 AND item_id = $item_id ORDER BY id DESC");
		$colaboracao = $conn->query($query);
		if ($colaboracao->num_rows > 0) {
			while ($colaboracao_tipo = $colaboracao->fetch_assoc()) {
				$colaboracao_ativa = $colaboracao_tipo['compartilhamento'];
				return $colaboracao_ativa;
			}
		} else {
			return 'aberta';
		}
		return 'aberta';
	}


	function return_lounge_id($usuario_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT escritorio_id FROM Usuarios WHERE id = $usuario_id AND escritorio_id IS NOT NULL");
		$usuarios = $conn->query($query);
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$usuario_escritorio_id = $usuario['escritorio_id'];
				return $usuario_escritorio_id;
			}
		} else {
			$query = prepare_query("INSERT INTO Paginas (item_id, tipo, subtipo, compartilhamento, user_id) VALUES ($usuario_id, 'pagina', 'escritorio', 'escritorio', $usuario_id)");
			$conn->query($query);
			$usuario_escritorio_id = $conn->insert_id;
			$query = prepare_query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($usuario_escritorio_id, 'pagina', 'titulo', 'Sala de Visitas', $usuario_id)");
			$conn->query($query);
			$query = prepare_query("UPDATE Usuarios SET escritorio_id = $usuario_escritorio_id WHERE id = $usuario_id");
			$conn->query($query);
			return $usuario_escritorio_id;
		}
		return false;
	}

	function return_curso_sigla($curso_id)
	{
		$curso_info = return_curso_info($curso_id);
		return $curso_info[2];
	}

	function return_curso_titulo_id($find_curso_id)
	{
		$curso_info = return_curso_info($find_curso_id);
		return $curso_info[3];
	}

	function return_curso_info($curso_id)
	{
		if ($curso_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT pagina_id, estado, sigla, titulo, user_id, criacao FROM Cursos WHERE id = $curso_id");
		$cursos = $conn->query($query);
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$curso_pagina_id = $curso['pagina_id']; // 0
				$curso_estado = $curso['estado']; // 1
				$curso_sigla = $curso['sigla']; // 2
				$curso_titulo = return_pagina_titulo($curso_pagina_id); // 3
				$curso_user_id = $curso['user_id']; // 4
				$curso_criacao = $curso['criacao']; // 5
				$curso_result = array($curso_pagina_id, $curso_estado, $curso_sigla, $curso_titulo, $curso_user_id, $curso_criacao);
				return $curso_result;
			}
		} else {
			return false;
		}
	}

	function return_curso_paginas($curso_id, $tipo)
	{
		include 'templates/criar_conn.php';
		if ($curso_id == false) {
			return false;
		}
		$curso_info = return_curso_info($curso_id);
		$curso_pagina_id = $curso_info[0];
		$result_all = array();
		$result_materias = array();
		$result_topicos = array();
		$result_subtopicos = array();
		$result_subsubtopicos = array();
		$result_subsubsubtopicos = array();
		$result_subsubsubsubtopicos = array();
		$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $curso_pagina_id AND tipo = 'materia'");
		$materias = $conn->query($query);
		if ($materias->num_rows > 0) {
			while ($materia = $materias->fetch_assoc()) {
				$materia_pagina_id = $materia['elemento_id'];
				array_push($result_materias, $materia_pagina_id);
				array_push($result_all, $materia_pagina_id);
				$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $materia_pagina_id AND tipo = 'topico'");
				$topicos = $conn->query($query);
				if ($topicos->num_rows > 0) {
					while ($topico = $topicos->fetch_assoc()) {
						$topico_pagina_id = $topico['elemento_id'];
						array_push($result_topicos, $topico_pagina_id);
						array_push($result_all, $topico_pagina_id);
						if ($topico_pagina_id == false) {
							continue;
						}
						$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $topico_pagina_id AND tipo = 'subtopico'");
						$subtopicos = $conn->query($query);
						if ($subtopicos->num_rows > 0) {
							while ($subtopico = $subtopicos->fetch_assoc()) {
								$subtopico_pagina_id = $subtopico['elemento_id'];
								array_push($result_subtopicos, $subtopico_pagina_id);
								array_push($result_all, $subtopico_pagina_id);
								$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subtopico_pagina_id AND tipo = 'subtopico'");
								$subsubtopicos = $conn->query($query);
								if ($subsubtopicos->num_rows > 0) {
									while ($subsubtopico = $subsubtopicos->fetch_assoc()) {
										$subsubtopico_pagina_id = $subsubtopico['elemento_id'];
										array_push($result_subsubtopicos, $subsubtopico_pagina_id);
										array_push($result_all, $subsubtopico_pagina_id);
										$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubtopico_pagina_id AND tipo = 'subtopico'");
										$subsubsubtopicos = $conn->query($query);
										if ($subsubsubtopicos->num_rows > 0) {
											while ($subsubsubtopico = $subsubsubtopicos->fetch_assoc()) {
												$subsubsubtopico_pagina_id = $subsubsubtopico['elemento_id'];
												array_push($result_subsubsubtopicos, $subsubsubtopico_pagina_id);
												array_push($result_all, $subsubsubtopico_pagina_id);
												$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubsubtopico_pagina_id AND tipo = 'subtopico'");
												$subsubsubsubtopicos = $conn->query($query);
												if ($subsubsubsubtopicos->num_rows > 0) {
													while ($subsubsubsubtopico = $subsubsubsubtopicos->fetch_assoc()) {
														$subsubsubsubtopico_pagina_id = $subsubsubsubtopico['elemento_id'];
														array_push($result_subsubsubsubtopicos, $subsubsubsubtopico_pagina_id);
														array_push($result_all, $subsubsubsubtopico_pagina_id);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if ($tipo == 'all') {
			return $result_all;
		} else {
			$result = array();
			array_push($result, $result_materias, $result_topicos, $result_subtopicos, $result_subsubtopicos, $result_subsubsubtopicos, $result_subsubsubsubtopicos);
			return $result;
		}
	}

	function reconstruir_busca($curso_id)
	{
		include 'templates/criar_conn.php';
		if ($curso_id == false) {
			return false;
		}
		$query = prepare_query("DELETE FROM Searchbar WHERE curso_id = $curso_id");
		$conn->query($query);
		$curso_paginas = return_curso_paginas($curso_id, 'all');
		$ordem = 0;
		foreach ($curso_paginas as $curso_pagina) {
			$ordem++;
			$curso_pagina_titulo = return_pagina_titulo($curso_pagina);
			if ($curso_pagina_titulo != false) {
				$query = prepare_query("INSERT INTO Searchbar (ordem, curso_id, pagina_id, chave) VALUES ($ordem, $curso_id, $curso_pagina, '$curso_pagina_titulo')");
				$conn->query($query);
			}
		}
	}


	function crop_text($text, $ch_limit)
	{
		if ($text == false) {
			return false;
		}
		$text_length = strlen($text);
		if ($text_length > $ch_limit) {
			$first_section = substr($text, 0, $ch_limit);
			$last_space_position = strrpos($first_section, ' ');
			$cropped = substr($first_section, 0, $last_space_position);
			$cropped .= '...';
			return $cropped;
		} else {
			return $text;
		}
	}

	function return_produto_info($pagina_id)
	{
		include 'templates/criar_conn.php';
		if ($pagina_id == false) {
			return false;
		}
		$verbete_texto = false;
		$pagina_titulo = return_pagina_titulo($pagina_id);
		$query = prepare_query("SELECT verbete_text FROM Textos WHERE pagina_id = $pagina_id");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$verbete_texto = $texto['verbete_text'];
				break;
			}
		}
		$produto_preco = false;
		$query = prepare_query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'preco' ORDER BY id DESC");
		$precos = $conn->query($query);
		if ($precos->num_rows > 0) {
			while ($preco = $precos->fetch_assoc()) {
				$produto_preco = $preco['extra'];
				break;
			}
		}
		$produto_autor = false;
		$query = prepare_query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'autor' ORDER BY id DESC");
		$autores = $conn->query($query);
		if ($autores->num_rows > 0) {
			while ($autor = $autores->fetch_assoc()) {
				$produto_autor = $autor['extra'];
				break;
			}
		}
		if ($pagina_titulo != false) {
			return array($pagina_titulo, $verbete_texto, $produto_preco, $produto_autor);
		} else {
			return false;
		}
	}

	function return_produto_imagem($pagina_id)
	{
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'imagem' ORDER BY id DESC");
		$imagens = $conn->query($query);
		if ($imagens->num_rows > 0) {
			while ($imagem = $imagens->fetch_assoc()) {
				$imagem_elemento_id = $imagem['elemento_id'];
				return $imagem_elemento_id;
			}
		}
		return false;
	}

	function return_imagem_arquivo($elemento_id)
	{
		$elemento_info = return_elemento_info($elemento_id);
		if ($elemento_info == false) {
			return false;
		} else {
			$elemento_arquivo = $elemento_info[11];
			return $elemento_arquivo;
		}
		return false;
	}

	function return_verbete_html($texto_id)
	{
		if ($texto_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT verbete_html FROM Textos WHERE id = $texto_id");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_verbete_html = $texto['verbete_html'];
				return $texto_verbete_html;
			}
		}
		return false;
	}

	function return_verbete_text($texto_id)
	{
		if ($texto_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT verbete_text FROM Textos WHERE id = $texto_id");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_verbete_text = $texto['verbete_text'];
				return $texto_verbete_text;
			}
		}
		return false;
	}

	function return_texto_historico_html($texto_id)
	{
		if ($texto_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT verbete_html FROM Textos_arquivo WHERE id = $texto_id");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_verbete_html = $texto['verbete_html'];
				return $texto_verbete_html;
			}
		}
		return false;
	}


	function return_forum_topico_titulo($topico_id)
	{
		if ($topico_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT comentario_text FROM Forum WHERE id = $topico_id AND tipo = 'topico'");
		$topicos = $conn->query($query);
		if ($topicos->num_rows > 0) {
			while ($topico = $topicos->fetch_assoc()) {
				$topico_titulo = $topico['comentario_text'];
				return $topico_titulo;
			}
		}
		return false;
	}

	function return_usuario_cursos($user_id)
	{
		$usuario_cursos_disponiveis = array();
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT pagina_id FROM Cursos");
		$cursos = $conn->query($query);
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$list_curso_pagina_id = $curso['pagina_id'];
				$curso_compartilhamento = return_compartilhamento($list_curso_pagina_id, $user_id);
				if ($curso_compartilhamento == true) {
					array_push($usuario_cursos_disponiveis, $list_curso_pagina_id);
				}
			}
		}
		return $usuario_cursos_disponiveis;
	}

	function return_usuario_cursos_inscrito($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT DISTINCT opcao FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'curso' ORDER BY id DESC");
		$usuario_cursos = $conn->query($query);
		$list_usuario_cursos = array();
		if ($usuario_cursos->num_rows > 0) {
			while ($usuario_curso = $usuario_cursos->fetch_assoc()) {
				$usuario_curso_opcao = $usuario_curso['opcao'];
				$usuario_curso_pagina_id = return_pagina_id($usuario_curso_opcao, 'curso');
				array_push($list_usuario_cursos, $usuario_curso_pagina_id);
			}
		}
		return $list_usuario_cursos;
	}

	//TODO: This function should get the title automatically, so the user doesn't have to.
	function extract_wikipedia($url)
	{
		$ch = curl_init();
		$url_host = parse_url($url);
		$url_host = $url_host['host'];
		curl_setopt($ch, CURLOPT_URL, "$url?printable=yes");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$position = strpos($output, "<body");
		$body = substr($output, $position);
		$body = str_replace("</html>", "", $body);
		curl_close($ch);
		//$body = strip_tags($body, '<p><ul><li><h1><h2><h3><table><img>');
		$body = str_replace('/wiki/', "https://$url_host/wiki/", $body);
		return $body;
	}

	function convert_questao_tipo($questao_tipo)
	{
		if ($questao_tipo == false) {
			return false;
		}
		if ($questao_tipo == 1) {
			return 'certo ou errado';
		} elseif ($questao_tipo == 2) {
			return 'múltipla escolha';
		} elseif ($questao_tipo == 3) {
			return 'dissertativa';
		} else {
			return false;
		}
	}

	function return_questao_info($questao_id)
	{
		if ($questao_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$results = false;
		$query = prepare_query("SELECT * FROM sim_questoes WHERE id = $questao_id");
		$questoes = $conn->query($query);
		if ($questoes->num_rows > 0) {
			while ($questao = $questoes->fetch_assoc()) {
				$questao_origem = $questao['origem']; // 0
				$questao_curso_id = $questao['curso_id']; // 1
				$questao_edicao_ano = $questao['edicao_ano']; // 2
				$questao_etapa_id = $questao['etapa_id']; // 3
				$questao_texto_apoio = $questao['texto_apoio']; // 4
				$questao_texto_apoio_id = $questao['texto_apoio_id']; // 5
				$questao_prova_id = $questao['prova_id']; // 6
				$questao_numero = $questao['numero']; // 7
				$questao_materia = $questao['materia']; // 8
				$questao_tipo = $questao['tipo']; // 9
				$questao_enunciado_html = $questao['enunciado_html']; // 10
				$questao_enunciado_text = $questao['enunciado_text']; // 11
				$questao_enunciado_content = $questao['enunciado_content']; // 12
				$questao_item1_text = $questao['item1_text']; // 13
				$questao_item2_text = $questao['item2_text']; // 14
				$questao_item3_text = $questao['item3_text']; // 15
				$questao_item4_text = $questao['item4_text']; // 16
				$questao_item5_text = $questao['item5_text']; // 17
				$questao_item1_html = $questao['item1_html']; // 18
				$questao_item2_html = $questao['item2_html']; // 19
				$questao_item3_html = $questao['item3_html']; // 20
				$questao_item4_html = $questao['item4_html']; // 21
				$questao_item5_html = $questao['item5_html']; // 22
				$questao_item1_content = $questao['item1_content']; // 23
				$questao_item2_content = $questao['item2_content']; // 24
				$questao_item3_content = $questao['item3_content']; // 25
				$questao_item4_content = $questao['item4_content']; // 26
				$questao_item5_content = $questao['item5_content']; // 27
				$questao_item1_gabarito = $questao['item1_gabarito']; // 28
				$questao_item2_gabarito = $questao['item2_gabarito']; // 29
				$questao_item3_gabarito = $questao['item3_gabarito']; // 30
				$questao_item4_gabarito = $questao['item4_gabarito']; // 31
				$questao_item5_gabarito = $questao['item5_gabarito']; // 32
				$questao_user_id = $questao['user_id']; // 33
				$questao_pagina_id = $questao['pagina_id']; // 34
				$results = array($questao_origem, $questao_curso_id, $questao_edicao_ano, $questao_etapa_id, $questao_texto_apoio, $questao_texto_apoio_id, $questao_prova_id, $questao_numero, $questao_materia, $questao_tipo, $questao_enunciado_html, $questao_enunciado_text, $questao_enunciado_content, $questao_item1_text, $questao_item2_text, $questao_item3_text, $questao_item4_text, $questao_item5_text, $questao_item1_html, $questao_item2_html, $questao_item3_html, $questao_item4_html, $questao_item5_html, $questao_item1_content, $questao_item2_content, $questao_item3_content, $questao_item4_content, $questao_item5_content, $questao_item1_gabarito, $questao_item2_gabarito, $questao_item3_gabarito, $questao_item4_gabarito, $questao_item5_gabarito, $questao_user_id, $questao_pagina_id);
			}
		}
		return $results;
	}

	function return_texto_apoio_info($texto_apoio_id)
	{
		if ($texto_apoio_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT criacao, pagina_id, origem, curso_id, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id FROM sim_textos_apoio WHERE id = $texto_apoio_id");
		$textos_apoio = $conn->query($query);
		$results = false;
		if ($textos_apoio->num_rows > 0) {
			while ($texto_apoio = $textos_apoio->fetch_assoc()) {
				$texto_apoio_criacao = $texto_apoio['criacao']; // 0
				$texto_apoio_pagina_id = $texto_apoio['pagina_id']; // 1
				$texto_apoio_origem = $texto_apoio['origem']; // 2
				$texto_apoio_curso_id = $texto_apoio['curso_id']; // 3
				$texto_apoio_prova_id = $texto_apoio['prova_id']; // 4
				$texto_apoio_titulo = $texto_apoio['titulo']; // 5
				$texto_apoio_enunciado_html = $texto_apoio['enunciado_html']; // 6
				$texto_apoio_enunciado_text = $texto_apoio['enunciado_text']; // 7
				$texto_apoio_enunciado_content = $texto_apoio['enunciado_content']; // 8
				$texto_apoio_texto_apoio_html = $texto_apoio['texto_apoio_html']; // 9
				$texto_apoio_texto_apoio_text = $texto_apoio['texto_apoio_text']; // 10
				$texto_apoio_texto_apoio_content = $texto_apoio['texto_apoio_content']; // 11
				$texto_apoio_user_id = $texto_apoio['user_id']; // 12
				$results = array($texto_apoio_criacao, $texto_apoio_pagina_id, $texto_apoio_origem, $texto_apoio_curso_id, $texto_apoio_prova_id, $texto_apoio_titulo, $texto_apoio_enunciado_html, $texto_apoio_enunciado_text, $texto_apoio_enunciado_content, $texto_apoio_texto_apoio_html, $texto_apoio_texto_apoio_text, $texto_apoio_texto_apoio_content, $texto_apoio_user_id);
			}
		}
		return $results;
	}

	function return_notificacao($pagina_id, $user_id)
	{
		if (($pagina_id == false) || ($user_id == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT tipo FROM Notificacoes WHERE user_id = $user_id AND pagina_id = $pagina_id AND estado = 1");
		$notificacoes = $conn->query($query);
		if ($notificacoes->num_rows > 0) {
			while ($notificacao = $notificacoes->fetch_assoc()) {
				$notificacao_tipo = $notificacao['tipo'];
				if ($notificacao_tipo == 'normal') {
					return array(1, 0);
				} elseif ($notificacao_tipo == 'email') {
					return array(1, 1);
				}
			}
		} else {
			return array(0, 0);
		}
	}


	function return_alteracao_recente($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$recente_criacao = false;
		$comentario_timestamp = false;
		$comentario_user_id = false;
		$recente_user_id = false;
		$query = prepare_query("SELECT criacao, user_id FROM Textos_arquivo WHERE pagina_id = $pagina_id ORDER BY id DESC");
		$recentes = $conn->query($query);
		if ($recentes->num_rows > 0) {
			while ($recente = $recentes->fetch_assoc()) {
				$recente_criacao = $recente['criacao'];
				$recente_user_id = $recente['user_id'];
				break;
			}
		}
		$query = prepare_query("SELECT timestamp, user_id FROM Forum WHERE pagina_id = $pagina_id ORDER BY id DESC");
		$comentarios = $conn->query($query);
		if ($comentarios->num_rows > 0) {
			while ($comentario = $comentarios->fetch_assoc()) {
				$comentario_timestamp = $comentario['timestamp'];
				$comentario_user_id = $comentario['user_id'];
				break;
			}
		}
		if (($recente_criacao == false) && ($comentario_timestamp == false)) {
			return false;
		} else {
			if ($recente_criacao > $comentario_timestamp) {
				return array($recente_criacao, $recente_user_id, 'verbete', $comentario_timestamp, $comentario_user_id, 'forum');
			} else {
				return array($comentario_timestamp, $comentario_user_id, 'forum', $recente_criacao, $recente_user_id, 'verbete');
			}
		}
		return false;
	}

	function format_data($data)
	{
		if ($data == false) {
			return false;
		}
		$data = DateTime::createFromFormat('Y-m-d H:i:s', $data);
		$data = $data->format('Y/m/d');
		return $data;
	}

	function convert_language($browser_lang)
	{
		switch ($browser_lang) {
			case 'pt':
				return 'Português';
				break;
			case 'en':
				return 'English';
				break;
			case 'es':
				return 'Español';
				break;
			case 'fr':
				return 'Français';
				break;
			default:
				return false;
				break;
		}
	}

	function send_nova_senha($email, $confirmacao, $user_language)
	{
		//error_log('this happened');
		if ($user_language == 'en') {
			$msg = "Your password at Ubwiki has been changed.\nIf you do not have an Ubwiki account, one has been created for your e-mail address.\nTo activate it, please follow this link:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		} elseif ($user_language == 'es') {
			$msg = "Su contraseña en Ubwiki ha sido cambiada.\nSi no tiene una cuenta de Ubwiki, se ha creado una para su dirección de correo electrónico.\nPara activarlo, siga este enlace:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		} else {
			$msg = "Sua senha na Ubwiki foi alterada.\nCaso você não tenha conta na Ubwiki, uma nova conta terá sido criada para seu endereço de email.\nPara ativá-la, siga este link:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		}
		$header = 'From: Ubwiki <webmaster@ubwiki.com.br>';
		$check = mail($email, 'Nova senha na Ubwiki', $msg, $header);
		return $check;
	}


	function return_list_item()
	{
		// ($pagina_id, $lista_tipo, $item_classes, $no_icon, $no_estado, $override_pagina_estado_cor, $override_pagina_titulo, $link_classes, $lista_texto_classes)
		$args = func_get_args();
		$pagina_id = $args[0];
		if (isset($args[1])) {
			$lista_tipo = $args[1];
		} else {
			$lista_tipo = false;
		}
		if (isset($args[2])) {
			$item_classes = $args[2];
		} else {
			$item_classes = false;
		}
		if (isset($args[3])) {
			$no_icon = $args[3];
		} else {
			$no_icon = false;
		}
		if (isset($args[4])) {
			$no_estado = $args[4];
		} else {
			$no_estado = false;
		}
		if (isset($args[5])) {
			$override_pagina_estado_cor = $args[5];
		} else {
			$override_pagina_estado_cor = false;
		}

		$parent_title = false;
		if (isset($args[6])) {
			$override_pagina_titulo = $args[6];
			if ($override_pagina_titulo == true) {
				$parent_title = true;
				$override_pagina_titulo = false;
			}
		} else {
			$override_pagina_titulo = false;
		}

		if (isset($args[7])) {
			$link_classes = $args[7];
		} else {
			$link_classes = false;
		}

		if (isset($args[8])) {
			$lista_texto_classes = $args[8];
		} else {
			$lista_texto_classes = false;
		}

		if (isset($args[9])) {
			$show_only_public_pages = $args[9];
		} else {
			$show_only_public_pages = false;
		}

		if ($pagina_id == false) {
			return false;
		} else {
			$pagina_info = return_pagina_info($pagina_id, true);
			if ($pagina_info == false) {
				return false;
			}
			$pagina_compartilhamento = $pagina_info[4];
			if ($pagina_compartilhamento != false) {
				if ($show_only_public_pages == true) {
					return false;
				}
			}
			$pagina_tipo = $pagina_info[2];
			$pagina_subtipo = $pagina_info[8];
			$pagina_estado = $pagina_info[3];
			$pagina_titulo = $pagina_info[6];
			$pagina_item_id = $pagina_info[1];
			$paginas_secao = array('secao', 'topico', 'materia');
			if (in_array($pagina_tipo, $paginas_secao)) {
				if ($parent_title == true) {
					$pagina_parent_title = return_pagina_titulo($pagina_item_id);
					$pagina_titulo = "<span>$pagina_titulo</span><small class='ml-3 text-muted font-italic'>$pagina_parent_title</small>";
				}
			}
			if ($pagina_tipo == 'elemento') {
				if ($parent_title == true) {
					$pagina_item_info = return_elemento_info($pagina_item_id);
					$pagina_item_autor_id = $pagina_item_info[6];
					if ($pagina_item_autor_id != false) {
						$pagina_item_autor_info = return_etiqueta_info($pagina_item_autor_id);
						$pagina_item_autor_titulo = $pagina_item_autor_info[2];
						$pagina_titulo = "<span>$pagina_titulo</span><small class='ml-3 text-muted font-italic'>$pagina_item_autor_titulo</small>";
					}
				}
			}
			$pagina_estado_icone_info = return_estado_icone($pagina_estado);
			$pagina_estado_icone = $pagina_estado_icone_info[0];
			$pagina_estado_cor = $pagina_estado_icone_info[1];
		}
		$pagina_icone = return_pagina_icone($pagina_tipo, $pagina_subtipo, $pagina_item_id);
		$icone_principal = $pagina_icone[0];
		$cor_icone_principal = $pagina_icone[1];
		if ($pagina_tipo == 'texto') {
			$lista_tipo = 'texto';
		}
		if ($lista_tipo == 'texto') {

			$pagina_estado_cor = $cor_icone_principal;
			$pagina_estado_icone = $icone_principal;
			$icone_principal = 'fad fa-file-alt fa-swap-opacity fa-fw';

			switch ($pagina_tipo) {
				case 'elemento':
					$cor_icone_principal = 'text-success';
					break;
				case 'topico':
					$cor_icone_principal = 'text-warning';
					break;
				case 'pagina':
					switch ($pagina_subtipo) {
						case 'modelo':
							$cor_icone_principal = 'text-secondary';
							break;
						case 'Plano de estudos':
							$cor_icone_principal = 'text-default';
							break;
						case 'etiqueta':
							$cor_icone_principal = 'text-warning';
							break;
						default:
							$cor_icone_principal = 'text-info';
					}
					break;
				case 'texto':
					$cor_icone_principal = 'text-primary';
					$pagina_estado_icone = false;
					$pagina_estado_cor = false;
					break;
				case 'secao':
					$cor_icone_principal = 'text-info';
					break;
				default:
					$cor_icone_principal = 'text-light';
			}
		}
		if ($lista_tipo == 'forum') {
			$link = "forum.php?pagina_id=$pagina_id";
		} elseif ($lista_tipo == 'inactive') {
			$link = false;
		} else {
			$link = "pagina.php?pagina_id=$pagina_id";
		}

		if ($no_icon == true) {
			$cor_icone_principal = false;
			$icone_principal = false;
		}
		if ($no_estado == true) {
			$pagina_estado_cor = false;
			$pagina_estado_icone = false;
		}

		if ($override_pagina_estado_cor != false) {
			$pagina_estado_cor = $override_pagina_estado_cor;
		}

		if ($override_pagina_titulo != false) {
			$pagina_titulo = $override_pagina_titulo;
		}

		return put_together_list_item('link', $link, $cor_icone_principal, $icone_principal, $pagina_titulo, $pagina_estado_cor, $pagina_estado_icone, $item_classes, $link_classes, $lista_texto_classes);
	}

	function put_together_list_item()
	{
		/*
		put_together_list_item('link', $link, $cor_icone_principal, $icone_principal, $pagina_titulo, $pagina_estado_cor, $pagina_estado_icone, $item_classes, $link_classes, $lista_texto_classes);
		*/

		$args = func_get_args();
		$type = $args[0];
		$link = $args[1];
		if ($link == false) {
			$type = 'inactive';
		}
		$cor_icone_principal = $args[2];
		$icone_principal = $args[3];
		$pagina_titulo = $args[4];
		$pagina_estado_cor = $args[5];
		$pagina_estado_icone = $args[6];
		if (($pagina_estado_icone == false) && ($icone_principal == false)) {
			$dflex = false;
		} else {
			$dflex = 'd-flex justify-content-between';
		}
		if (isset($args[7])) {
			$item_classes = $args[7];
		} else {
			$item_classes = false;
		}
		if (isset($args[8])) {
			$link_classes = $args[8];
		} else {
			$link_classes = false;
		}
		if (isset($args[9])) {
			$lista_texto_classes = $args[9];
		} else {
			$lista_texto_classes = false;
		}
		if ($type == 'link_blank') {
			$type = 'link';
			$target = "target='_blank'";
		} else {
			$target = false;
		}
		if ($icone_principal != false) {
			$fix_things = 'd-flex justify-content-center';
		} else {
			$fix_things = false;
		}
		if ($type == 'link') {
			return "
			<a href='$link' $target class='$link_classes'>
				<li class='list-group-item list-group-item-action $item_classes border-top p-1 py-2 $dflex'>
					<span class='$fix_things'>
						<span class='$cor_icone_principal align-center icone-lista'>
							<i class='$icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto $lista_texto_classes'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-center ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			</a>";
		} elseif ($type == 'modal') {
			return "
			<a data-toggle='modal' data-target='$link' class='$link_classes'>
				<li class='list-group-item list-group-item-action $item_classes border-top p-1 py-2 $dflex'>
					<span class='$fix_things'>
						<span class='$cor_icone_principal align-center icone-lista'>
							<i class='$icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto $lista_texto_classes'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-center ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			</a>";
		} elseif ($type == 'inactive') {
			return "
				<li class='list-group-item $item_classes border-top p-1 py-2 $dflex'>
					<span class='$fix_things'>
						<span class='$cor_icone_principal align-center icone-lista'>
							<i class='$icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto $lista_texto_classes'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-center ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			";
		} elseif ($type == 'link_button') {
			return "
			<a href='javascript:void(0);' id='$link' name='$link' value='$link' class='$link $link_classes'>
				<li class='list-group-item list-group-item-action $item_classes border-top p-1 py-2 $dflex'>
					<span class='$fix_things'>
						<span class='$cor_icone_principal align-center icone-lista'>
							<i class='$icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto $lista_texto_classes'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-center ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			</a>";
		}
	}

	// Esta função retorna páginas ou elementos, enviando as variáveis para uma entre as duas funções que seguem abaixo.
	function return_icone()
	{
		$args = func_get_args();
		$categoria = $args[0];
		$tipo = $args[1];
		if (!isset($args[2])) {
			$args[2] = false;
		}
		if (!isset($args[3])) {
			$args[3] = false;
		}
		$subtipo = $args[2];
		$extra = $args[3];
		if ($categoria == 'pagina') {
			$result = return_pagina_icone($tipo, $subtipo, $extra);
		} elseif ($categoria == 'elemento') {
			$result = return_icone_subtipo($tipo, $subtipo);
		}
		return $result;
	}

	function return_icone_subtipo($tipo, $subtipo) // Esta função retorna os ícones dos elementos
	{
		if (($tipo == false) && ($subtipo == false)) {
			return array(false, false, false);
		}
		switch ($subtipo) {
			case 'livro':
				return array('fad fa-book', 'text-success', 'rgba-green-strong');
				break;
			case 'pagina':
				return array('fad fa-browser', 'text-primary', 'rgba-blue-strong');
				break;
			case 'artigo':
				return array('fad fa-newspaper', 'text-muted', 'rgba-black-strong');
				break;
			case 'wikipedia':
				return array('fab fa-wikipedia-w', 'text-dark', 'rgba-black-strong');
				break;
			case 'musica':
				return array('fad fa-record-vinyl', 'text-dark', 'rgba-black-strong');
				break;
			case 'podcast':
				return array('fad fa-podcast', 'text-secondary', 'rgba-purple-strong');
				break;
			case 'audiobook':
				return array('fab fa-audible', 'text-warning', 'rgba-amber-strong');
				break;
			case 'mapasatelite':
				return array('fad fa-globe-americas', 'text-default', 'rgba-teal-strong');
				break;
			case 'retrato':
				return array('fad fa-portrait', 'text-danger', 'rgba-red-strong');
				break;
			case 'arte':
				return array('fad fa-paint-brush', 'text-secondary', 'rgba-purple-strong');
				break;
			case 'grafico':
				return array('fad fa-chart-pie', 'text-warning', 'rgba-orange-strong');
				break;
			case 'paisagem':
				return array('fad fa-mountain', 'text-info', 'rgba-cyan-strong');
				break;
			case 'objeto':
				return array('fad fa-cube', 'text-success', 'rgba-green-strong');
				break;
			case 'arquitetura':
				return array('fad fa-university', 'text-warning', 'rgba-orange-strong');
				break;
			case 'planta':
				return array('fad fa-flower-daffodil', 'text-danger', 'rgba-red-strong');
				break;
			case 'animais':
				return array('fad fa-rabbit', 'text-info', 'rgba-cyan-strong');
				break;
			case 'outras':
				return array('fad fa-camera-alt', 'text-dark', 'rgba-black-strong');
				break;
			case 'youtube':
				return array('fab fa-youtube', 'text-danger', 'rgba-red-strong');
				break;
			case 'filme':
				return array('fad fa-film', 'text-info', 'rgba-cyan-strong');
				break;
			case 'aula':
				return array('fad fa-chalkboard-teacher', 'text-default', 'rgba-teal-strong');
				break;
			case 'equacao':
				return array('fad fa-greater-than-equal', 'text-info', 'rgba-cyan-strong');
				break;
			case 'etiqueta':
				return array('fad fa-tag', 'text-warning', 'rgba-orange-strong');
				break;
			default:
				switch ($tipo) {
					case 'imagem':
						if ($subtipo == 'generico') {
							return array('fad fa-image', 'text-danger', 'rgba-red-strong');
						} else {
							return array('fad fa-file-image', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'video':
						if ($subtipo == 'generico') {
							return array('fad fa-play-circle', 'text-info', 'rgba-blue-strong');
						} else {
							return array('fad fa-file-video', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'album_musica':
						if ($subtipo == 'generico') {
							return array('fad fa-volume-up', 'text-warning', 'rgba-orange-strong');
						} else {
							return array('fad fa-file-audio', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'referencia':
						if ($subtipo == 'generico') {
							return array('fad fa-glasses-alt', 'text-success', 'rgba-green-strong');
						} else {
							return array('fad fa-file-alt', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'questao':
						return array('fad fa-ballot-check', 'text-secondary', 'rgba-purple-strong');
						break;
					case 'secao':
						return array('fad fa-list-ol', 'text-info', 'rgba-cyan-strong');
						break;
					case 'resposta':
						return array('fad fa-comment-alt-edit', 'text-default', 'rgba-cyan-strong');
						break;
					case 'wikipedia':
						return array('fab fa-wikipedia-w', 'text-dark', 'rgba-grey-strong');
						break;
					default:
						return array('fad fa-circle-notch fa-spin', 'text-danger', 'rgba-red-strong');
				}
		}
	}

	function return_pagina_icone($pagina_tipo, $pagina_subtipo, $pagina_item_id) // esta função retorna os ícones das páginas
	{
		if ($pagina_tipo == false) {
			return false;
		}
		switch ($pagina_tipo) {
			case 'pagina':
				switch ($pagina_subtipo) {
					case 'escritorio':
						return array('fad fa-mug-tea', 'text-info');
						break;
					case 'etiqueta':
						return array('fad fa-tag', 'text-warning');
						break;
					case 'modelo':
						return array('fad fa-pen-nib', 'text-secondary');
						break;
					case 'Plano de estudos':
						return array('fad fa-ruler-triangle', 'text-default');
						break;
					case 'plano':
						return array('fad fa-calendar-check', 'text-info');
						break;
					case 'simulado':
						return array('fad fa-ballot-check', 'text-default');
						break;
					default:
						return array('fad fa-columns', 'text-info');
						break;
				}
			case 'secao':
				return array('fad fa-bookmark', 'text-default');
				break;
			case 'curso':
				return array('fad fa-graduation-cap', 'text-default');
				break;
			case 'elemento':
				$elemento_info = return_elemento_info($pagina_item_id);
				$elemento_tipo = $elemento_info[3];
				$elemento_subtipo = $elemento_info[18];
				$elemento_subtipo_icone = return_icone_subtipo($elemento_tipo, $elemento_subtipo);
				if ($elemento_subtipo_icone != false) {
					return array($elemento_subtipo_icone[0], $elemento_subtipo_icone[1]);
				} else {
					return array('fad fa-circle-notch', 'text-success');
				}
				break;
			case 'topico':
				return array('fad fa-columns', 'text-warning');
				break;
			case 'sistema':
				return array('fad fa-columns', 'text-default');
				break;
			case 'texto':
				return array('fad fa-file-alt fa-swap-opacity', 'text-primary');
				break;
			case 'grupo':
				return array('fad fa-users', 'text-default');
				break;
			case 'materia':
				return array('fad fa-th-list', 'text-default');
				break;
			case 'resposta':
				return array('fad fa-reply', 'text-default');
				break;
			case 'questao':
				return array('fad fa-ballot-check', 'text-secondary');
				break;
			case 'texto_apoio':
				return array('fad fa-quote-left', 'text-secondary');
				break;
			case 'escritorio':
				return array('fad fa-lamp-desk', 'text-danger');
				break;
			default:
				return array('fad fa-circle-notch', 'text-light');
		}
	}


	function return_link_compartilhamento($pagina_id)
	{
		if ($pagina_id == false) {
			return array(false, false);
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT subtipo, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'linkshare' AND estado = 1");
		$dados = $conn->query($query);
		if ($dados->num_rows > 0) {
			while ($dado = $dados->fetch_assoc()) {
				$link_compartilhamento_tipo = $dado['subtipo'];
				$link_compartilhamento_codigo = $dado['extra'];
				return array($link_compartilhamento_tipo, $link_compartilhamento_codigo);
			}
		} else {
			return array(false, false);
		}
	}

	function adicionar_chave_traducao()
	{
		$args = func_get_args();
		$nova_chave_titulo = $args[0];
		if (!isset($args[1])) {
			$user_id = 1;
		} else {
			$user_id = $args[1];
		}
		if ($nova_chave_titulo == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$chave_id = false;
		$nova_chave_titulo = mysqli_real_escape_string($conn, $nova_chave_titulo);
		$query = prepare_query("SELECT id FROM Translation_chaves WHERE chave = '$nova_chave_titulo'");
		$chaves = $conn->query($query);
		if ($chaves->num_rows == 0) {
			$query = prepare_query("INSERT INTO Translation_chaves (user_id, chave) VALUES ($user_id, '$nova_chave_titulo')");
			$conn->query($query);
			$chave_id = $conn->insert_id;
		} else {
			while ($chave = $chaves->fetch_assoc()) {
				$chave_id = $chave['id'];
			}
		}
		return $chave_id;
	}

	function adicionar_traducao()
	{
		$args = func_get_args();
		$chave_id = $args[0];
		$lingua = $args[1];
		$conteudo = $args[2];
		if (isset($args[3])) {
			$user_id = $args[3];
		} else {
			$user_id = 1;
		}
		if (($chave_id == false) || ($lingua == false) || ($conteudo == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$conteudo = mysqli_real_escape_string($conn, $conteudo);
		$check_exists = $conn->query("SELECT id FROM Chaves_traduzidas WHERE chave_id = $chave_id AND lingua = '$lingua'");
		if ($check_exists->num_rows > 0) {
			$query = prepare_query("UPDATE Chaves_traduzidas SET traducao = '$conteudo' WHERE chave_id = $chave_id AND lingua = '$lingua'");
			$check = $conn->query($query);
		} else {
			$query = prepare_query("INSERT INTO Chaves_traduzidas (user_id, chave_id, lingua, traducao) VALUES ($user_id, $chave_id, '$lingua', '$conteudo')");
			$check = $conn->query($query);
		}
		return $check;
	}

	function adicionar_chave_e_traducoes() // ('nova chave', 'em ingles', 'em portugues', 'em espanhol', 'em frances')
	{
		$args = func_get_args();
		$nova_chave = $args[0];
		if (isset($args[1])) {
			$en = $args[1];
		} else {
			$en = $nova_chave;
		}
		if (isset($args[2])) {
			$pt = $args[2];
		} else {
			$pt = $nova_chave;
		}
		if (isset($args[3])) {
			$es = $args[3];
		} else {
			$es = $en;
		}
		if (isset($args[4])) {
			$fr = $args[4];
		} else {
			$fr = $en;
		}
		$nova_chave_id = adicionar_chave_traducao($nova_chave);
		adicionar_traducao($nova_chave_id, 'en', $en);
		adicionar_traducao($nova_chave_id, 'pt', $pt);
		adicionar_traducao($nova_chave_id, 'es', $es);
		adicionar_traducao($nova_chave_id, 'fr', $fr);
	}

	function return_curso_card()
	{
		$args = func_get_args();
		$curso_pagina_id = $args[0];
		$card_mode = $args[1];
		if ($curso_pagina_id == false) {
			return false;
		}
		$curso_titulo = return_pagina_titulo($curso_pagina_id);
		if ($curso_titulo == false) {
			return false;
		}
		$curso_texto_id = return_texto_id('curso', 'verbete', $curso_pagina_id, false);
		$curso_verbete = return_verbete_html($curso_texto_id);
		$curso_verbete = strip_tags($curso_verbete);
		$curso_verbete = crop_text($curso_verbete, 400);

		$template_id = "curso_$curso_pagina_id";
		$template_titulo = "<a href='pagina.php?pagina_id=$curso_pagina_id'>$curso_titulo</a>";
		if ($card_mode == 'inscrito') {
			$template_botoes = "<span class='text-success'><i class='fad fa-lamp-desk fa-fw'></i></span>";
		} elseif ($card_mode == 'disponivel') {
			$template_botoes = "<span class='text-primary'><i class='fad fa-lamp-desk fa-fw'></i></span>";
		}
		$template_conteudo = false;
		if ($curso_verbete != false) {
			$template_conteudo .= $curso_verbete;
		}

		$curso_card = include 'templates/page_element.php';

		return $curso_card;

	}

	function return_usuario_info($usuario_id)
	{
		if ($usuario_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT * FROM Usuarios WHERE id = $usuario_id");
		$usuarios = $conn->query($query);
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$usuario_tipo = $usuario['tipo']; // 01
				$usuario_criacao = $usuario['criacao']; // 02
				$usuario_origem = $usuario['origem']; // 03
				$usuario_language = $usuario['language']; // 04
				$usuario_email = $usuario['email']; // 05
				$usuario_apelido = $usuario['apelido']; // 06
				$usuario_pagina_id = $usuario['pagina_id']; // 07
				if ($usuario_pagina_id == false) {
					$usuario_pagina_id = return_pagina_id($usuario_id, 'escritorio');
				}
				$usuario_escritorio_id = $usuario['escritorio_id']; // 08
				$usuario_nome = $usuario['nome']; // 09
				$usuario_sobrenome = $usuario['sobrenome']; // 10
				$usuario_concursos = $usuario['concursos']; // 11
				$usuario_special = $usuario['special']; // 12
				$result = array($usuario_tipo, $usuario_criacao, $usuario_origem, $usuario_language, $usuario_email, $usuario_apelido, $usuario_pagina_id, $usuario_escritorio_id, $usuario_nome, $usuario_sobrenome, $usuario_concursos, $usuario_special);
				return $result;
			}
		}
		return false;
	}

	function check_revisor($user_tipo)
	{
		switch ($user_tipo) {
			case 'admin':
				return true;
				break;
			case 'revisor':
				return true;
				break;
			case 'diplomata':
				return true;
				break;
			default:
				return false;
		}
	}

	function return_pagina_texto_id($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		$pagina_info = return_pagina_info($pagina_id);
		$pagina_tipo = $pagina_info[2];
		if ($pagina_tipo == 'texto') {
			$pagina_texto_id = $pagina_info[1];
		} else {
			$pagina_texto_id = return_texto_id($pagina_tipo, 'verbete', $pagina_id, false);
		}
		return $pagina_texto_id;
	}

	function return_modelo_estado($modelo_pagina_id, $user_id)
	{
		if (($modelo_pagina_id == false) || ($user_id == false)) {
			return false;
		}
		$user_escritorio_pagina_id = return_pagina_id($user_id, 'escritorio');
		include 'templates/criar_conn.php';
		$modelos_do_usuario = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $user_escritorio_pagina_id AND elemento_id = $modelo_pagina_id AND estado = 1");
		if ($modelos_do_usuario->num_rows > 0) {
			while ($modelo_do_usuario = $modelos_do_usuario->fetch_assoc()) {
				$modelo_do_usuario_extra = $modelo_do_usuario['extra'];
				if ($modelo_do_usuario_extra == false) {
					return 'added';
				} else {
					return $modelo_do_usuario_extra;
				}
			}
		} else {
			return false;
		}
	}

	function return_questao_titulo($questao_id)
	{
		$questao_info = return_questao_info($questao_id);
		$questao_origem = $questao_info[0];
		if ($questao_origem == 0) {
			$questao_origem_string = "(não-oficial)";
		} else {
			$questao_origem_string = false;
		}
		$questao_edicao_ano = $questao_info[2];
		$questao_prova_id = $questao_info[6];
		$questao_numero = $questao_info[7];
		//$questao_tipo = $questao_info[9];
		$questao_prova_info = return_info_prova_id($questao_prova_id);
		$questao_prova_titulo = $questao_prova_info[0];
		return "{$questao_edicao_ano}, Prova “{$questao_prova_titulo}” — Questão {$questao_numero} {$questao_origem_string}";
	}

	function return_plan_row()
	{
		$args = func_get_args();
		$item_planejamento_elemento_id = $args[0];
		$item_planejamento_estado = $args[1];
		$item_planejamento_classificacao = $args[2];
		$item_planejamento_comments = $args[3];
		$item_planejamento_categoria = $args[4];
		$item_planejamento_entrada_id = $args[5];

		if ($item_planejamento_categoria == 'elemento') {
			$item_planejamento_info = return_elemento_info($item_planejamento_elemento_id);
			$item_planejamento_pagina_id = $item_planejamento_info[19];
			$item_planejamento_titulo = $item_planejamento_info[4];
			$item_planejamento_autor = $item_planejamento_info[5];
			$item_planejamento_tipo = $item_planejamento_info[3];
			$item_planejamento_subtipo = $item_planejamento_info[18];
			$item_planejamento_ano = $item_planejamento_info[8];
		} elseif ($item_planejamento_categoria == 'pagina') {
			$item_planejamento_info = return_pagina_info($item_planejamento_elemento_id, true);
			$item_planejamento_pagina_id = $item_planejamento_elemento_id;
			$item_planejamento_titulo = $item_planejamento_info[6];
			$item_planejamento_autor = false;
			$item_planejamento_tipo = $item_planejamento_info[2];
			$item_planejamento_subtipo = $item_planejamento_info[8];
			$item_planejamento_ano = false;
		}

		$plan_item = return_plan_item($item_planejamento_pagina_id, $item_planejamento_titulo, $item_planejamento_autor, $item_planejamento_tipo, $item_planejamento_subtipo, $item_planejamento_ano, $item_planejamento_estado, $item_planejamento_classificacao, $item_planejamento_comments, $item_planejamento_elemento_id, $item_planejamento_categoria, $item_planejamento_entrada_id);
		return $plan_item;
	}

	function return_plan_item()
	{
		$args = func_get_args();
		$pagina_id = $args[0];
		$titulo = $args[1];
		$autor = $args[2];
		$tipo = $args[3];
		$subtipo = $args[4];
		$ano = $args[5];
		if ($ano != false) {
			$titulo .= " ($ano)";
		}
		$estado = $args[6];
		$classificacao = $args[7];
		$comments = $args[8];
		$elemento_id = $args[9];
		$categoria = $args[10];
		$entrada_id = $args[11];

		$plan_icon = return_plan_icon($estado);

		$icone = return_icone($categoria, $tipo, $subtipo, $elemento_id);

		$all_cell_classes = 'rounded text-wrap border p-1';

		$result = false;
		$result .= "<div class='row grey lighten-5 mt-1'>";
		$result .= "
				<div class='col-1 $all_cell_classes {$plan_icon[0]} ml-0 text-center align-center d-flex justify-content-center'>
					<a value='$entrada_id' href='javascript:void(0);' data-toggle='modal' data-target='#modal_set_state' class='align-self-center {$plan_icon[1]} p-1 rounded set_state_entrada_value' title='{$plan_icon[3]}'><i class='{$plan_icon[2]} fa-fw fa-lg'></i></a>
				</div>
					";
		$icone_background = return_background($icone[1]);
		$result .= "
			<div class='col-1 $all_cell_classes ml-1 text-center align-center d-flex justify-content-center $icone_background'>
				<span class='{$icone[1]} ml-1 align-self-center rounded p-1'><i class='{$icone[0]} fa-fw fa-lg'></i></span>
			</div>
					";
		$result .= "
				<div class='col $all_cell_classes bg-white ml-1'>
					<div class='row'>
						<div class='col'>
							<a href='pagina.php?pagina_id=$pagina_id' class='text-primary'>$titulo</a>
						</div>
					</div>
					<div class='row'>
						<div class='col'>
							<span class='text-muted font-italic'>$autor</span>
						</div>
					</div>
				</div>
					";
		$result .= "
				<div class='col $all_cell_classes bg-white line-height-1 ml-1'>
					<a class='text-dark set_comment_elemento_id font-italic' href='javascript:void(0);' data-toggle='modal' data-target='#modal_add_comment' value='$elemento_id'><small>$comments</small></a>
				</div>
					";
		if ($classificacao == false) {
			$result .= "
				<div class='col-1 $all_cell_classes rgba-orange-slight ml-0 text-center align-center d-flex justify-content-center ml-1'>
					<a value='$elemento_id' href='javascript:void(0);' data-toggle='modal' data-target='#modal_set_tag' class='align-self-center p-1 text-warning rounded set_tag_elemento_value'><i class='fad fa-tag fa-fw fa-lg'></i></a>
				</div>
						";
		} else {
			$result .= "
				<div class='col-1 $all_cell_classes rounded text-break ml-0 rgba-orange-slight d-flex justify-content-center line-height-1 ml-1'>
					<a value='$elemento_id' class='text-muted set_tag_elemento_value align-self-center text-center' data-toggle='modal' data-target='#modal_set_tag'><small>$classificacao</small></a>
				</div>
						";
		}
		$result .= "</div>";

		return $result;
	}

	function return_plan_icon($estado)
	{
		$meaning = "interest level $estado";
		switch ($estado) {
			case 0:
				$background = 'rgba-amber-light';
				$icon_color = 'deep-orange-darker-hover';
				$icon = 'fad fa-question-circle';
				$meaning = 'not set';
				break;
			case 1:
				$background = 'rgba-grey-slight';
				$icon_color = 'text-muted';
				$icon = 'fad fa-circle';
				break;
			case 2:
				$background = 'rgba-grey-slight';
				$icon_color = 'deep-orange-darker-hover';
				$icon = 'fad fa-circle';
				break;
			case 3:
				$background = 'rgba-red-slight';
				$icon_color = 'deep-orange-darker-hover';
				$icon = 'fad fa-circle';
				break;
			case 4:
				$background = 'rgba-red-slight';
				$icon_color = 'text-warning';
				$icon = 'fad fa-circle';
				break;
			case 5:
				$background = 'rgba-orange-slight';
				$icon_color = 'text-warning';
				$icon = 'fad fa-circle';
				break;
			case 6:
				$background = 'rgba-orange-slight';
				$icon_color = 'text-success';
				$icon = 'fad fa-circle';
				break;
			case 7:
				$background = 'rgba-green-slight';
				$icon_color = 'text-success';
				$icon = 'fad fa-circle';
				break;
			case 8:
				$background = 'rgba-green-slight';
				$icon_color = 'text-default';
				$icon = 'fad fa-exclamation-circle';
				break;
			case 9:
				$background = 'rgba-cyan-slight';
				$icon_color = 'text-default';
				$icon = 'fad fa-alarm-exclamation';
				break;
			case 10:
				$background = 'rgba-cyan-slight';
				$icon_color = 'text-info';
				$icon = 'fad fa-book-reader';
				break;
			case 11: // full study in process or interrupted
				$background = 'rgba-blue-slight';
				$icon_color = 'text-info';
				$icon = 'fad fa-pen-alt';
				$meaning = 'full study in process';
				break;
			case 12:// fully read, not planning to re-read
				$background = 'rgba-blue-slight';
				$icon_color = 'text-info';
				$icon = 'fad fa-check';
				$meaning = 'incomplete study';
				break;
			case 13:// fully read, not planning to re-read
				$background = 'rgba-blue-slight';
				$icon_color = 'text-primary';
				$icon = 'fas fa-check';
				$meaning = 'fully read';
				break;
			case 14: // full study completed.
				$background = 'rgba-blue-slight';
				$icon_color = 'text-primary';
				$icon = 'fad fa-check-double';
				$meaning = 'full study completed';
				break;
			case 15: // re-read notes.
				$background = 'rgba-blue-slight';
				$icon_color = 'text-secondary';
				$icon = 'fad fa-repeat';
				$meaning = 're-read notes';
				break;
			case 16: // content completely absorbed.
				$background = 'rgba-blue-slight';
				$icon_color = 'text-secondary';
				$icon = 'fad fa-head-side-brain';
				$meaning = 'full assimilation';
				break;
			case 17: // content completely absorbed.
				$background = 'rgba-purple-slight';
				$icon_color = 'text-secondary';
				$icon = 'fad fa-certificate';
				$meaning = 'full assimilation with notes';
				break;
			default:
				return false;
		}
		return array($background, $icon_color, $icon, $meaning);
	}

	function return_plano_id_pagina_id($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		$plano_id = false;
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT id FROM Planos WHERE pagina_id = $pagina_id");
		$planos = $conn->query($query);
		if ($planos->num_rows > 0) {
			while ($plano = $planos->fetch_assoc()) {
				$plano_id = $plano['id'];
			}
		}
		return $plano_id;
	}

	function return_background($fa_color)
	{
		switch ($fa_color) {
			case 'text-info':
				return 'cyan lighten-5';
				break;
			case 'text-primary':
				return 'blue lighten-5';
				break;
			case 'text-warning':
				return 'orange lighten-5';
				break;
			case 'text-danger':
				return 'red lighten-5';
				break;
			case 'text-success':
				return 'green lighten-5';
				break;
			case 'text-secondary':
				return 'purple lighten-5';
				break;
			case 'text-default':
				return 'teal lighten-5';
				break;
			case 'text-dark':
			case 'text-muted':
				return 'grey lighten-4';
				break;
			default:
				return 'bg-white';
		}
	}

	function return_user_bookmarks($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT DISTINCT pagina_id FROM Bookmarks WHERE user_id = $user_id AND bookmark = 1 AND active = 1 ORDER BY id DESC");
		$usuario_bookmarks = $conn->query($query);
		$result_bookmarks = array();
		if ($usuario_bookmarks->num_rows > 0) {
			while ($usuario_bookmark = $usuario_bookmarks->fetch_assoc()) {
				$usuario_bookmark_pagina_id = $usuario_bookmark['pagina_id'];
				array_push($result_bookmarks, $usuario_bookmark_pagina_id);
			}
		}
		return $result_bookmarks;
	}

	function return_user_completed($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT DISTINCT pagina_id FROM Completed WHERE user_id = $user_id AND estado = 1 AND active = 1 ORDER BY id DESC");
		$usuario_completos = $conn->query($query);
		$result_completos = array();
		if ($usuario_completos->num_rows > 0) {
			while ($usuario_completo = $usuario_completos->fetch_assoc()) {
				$usuario_completo_pagina_id = $usuario_completo['pagina_id'];
				array_push($result_completos, $usuario_completo_pagina_id);
			}
		}
		return $result_completos;
	}

	function return_user_areas_interesse($user_escritorio)
	{
		if ($user_escritorio == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $user_escritorio AND tipo = 'topico' AND estado = 1 ORDER BY id DESC");
		$user_areas_interesse = array();
		$areas_interesse = $conn->query($query);
		if ($areas_interesse->num_rows > 0) {
			while ($area_interesse = $areas_interesse->fetch_assoc()) {
				$area_interesse_etiqueta_id = $area_interesse['extra'];
				$area_interesse_info = return_etiqueta_info($area_interesse_etiqueta_id);
				$area_interesse_pagina_id = $area_interesse_info[4];
				array_push($user_areas_interesse, $area_interesse_pagina_id);
			}
		}
		return $user_areas_interesse;
	}

	function list_wrap($content)
	{
		if ($content == false) {
			return false;
		}
		return "
			<ul class='list-group list-group-flush'>
				$content
			</ul>
		";
	}

	function wrapp($content)
	{
		if ($content == false) {
			return false;
		}
		return "<p>$content</p>";
	}

	function return_user_opcoes($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("SELECT opcao, opcao_tipo, opcao_string FROM Opcoes WHERE user_id = $user_id ORDER BY id DESC");
		$user_opcoes = $conn->query($query);
		$resultado = array();
		if ($user_opcoes->num_rows > 0) {
			$opcoes_registradas = array();
			while ($user_opcao = $user_opcoes->fetch_assoc()) {
				$opcao_tipo = $user_opcao['opcao_tipo'];
				$opcao_value = $user_opcao['opcao'];
				$opcao_string = $user_opcao['opcao_string'];
				if (in_array($opcao_tipo, $opcoes_registradas)) {
					continue;
				}
				array_push($opcoes_registradas, $opcao_tipo);
				$dados_opcao = array($opcao_value, $opcao_string);
				$resultado[$opcao_tipo] = $dados_opcao;
			}
		}
		//error_log(serialize($resultado));
		return $resultado;
	}


	function return_simulado_info($simulado_id)
	{
		if ($simulado_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$simulados = $conn->query("SELECT * FROM Simulados WHERE id = $simulado_id");
		if ($simulados->num_rows > 0) {
			while ($simulado = $simulados->fetch_assoc()) {
				$simulado_pagina_id = $simulado['pagina_id'];
				$simulado_curso_id = $simulado['curso_id'];
				$simulado_contexto_pagina_id = $simulado['contexto_pagina_id'];
				$simulado_user_id = $simulado['user_id'];
				return array($simulado_pagina_id, $simulado_curso_id, $simulado_contexto_pagina_id, $simulado_user_id);
			}
		} else {
			return false;
		}
	}

	function return_admin_status($pagina_id, $pagina_tipo, $pagina_subtipo, $user_id, $user_tipo, $pagina_user_id, $pagina_compartilhamento, $pagina_curso_user_id, $texto_page_id, $pagina_original_compartilhamento)
	{
		$paginas_de_curso = array('curso', 'materia', 'topico');
		if ($pagina_tipo == 'sistema') {
			if ($user_tipo == 'admin') {
				return true;
			}
		} elseif ($pagina_tipo == 'pagina') {
			if ($pagina_user_id == $user_id) {
				return true;
			}
			if ($pagina_subtipo == 'modelo') {
				if ($pagina_compartilhamento != 'privado') {
					return false;
				}
			} elseif ($pagina_subtipo == 'etiqueta') {
				if ($user_tipo == 'admin') {
					return true;
				}
			}
		} elseif (in_array($pagina_tipo, $paginas_de_curso)) {
			if ($pagina_curso_user_id == $user_id) {
				return true;
			}
			if ($user_tipo == 'admin') {
				return true;
			}
		} elseif ($pagina_tipo == 'texto') {
			if ($texto_page_id == 0) {
				if ($pagina_user_id == $user_id) {
					return true;
				}
			}
		} elseif ($pagina_tipo == 'resposta') {
			if ($pagina_user_id == $user_id) {
				return true;
			}
		} elseif ($pagina_tipo == 'secao') {
			if ($pagina_user_id == $user_id) {
				return true;
			} elseif ($pagina_original_compartilhamento == false) {
				return true;
			}
		} elseif ($pagina_tipo == 'elemento') {
			if ($user_tipo == 'admin') {
				return true;
			}
		}
		return false;
	}

	function return_anotacao_score()
	{
		$args = func_get_args();
		$item_id = $args[0];
		$item_tipo = $args[1];
		$user_id = $args[2];
		$pagina_id = $args[3];

		include 'templates/criar_conn.php';

		$voto_usuario_value = false;
		$votos = $conn->query("SELECT user_id FROM Votos WHERE pagina_id = $pagina_id AND tipo = '$item_tipo' AND objeto = $item_id AND valor = 1");
		$votos_count = $votos->num_rows;
		if ($votos_count > 0) {
			$votos_usuario = $conn->query("SELECT valor FROM Votos WHERE pagina_id = $pagina_id AND tipo = '$item_tipo' AND objeto = $item_id AND user_id = $user_id");
			if ($votos_usuario->num_rows > 0) {
				while ($voto_usuario = $votos_usuario->fetch_assoc()) {
					$voto_usuario_value = $voto_usuario['valor'];
					break;
				}
			}
		}

		return array($votos_count, $voto_usuario_value);
	}

	function delete_by_id($table_name, $id)
	{
		if (($table_name == false) || ($id == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$query = prepare_query("DELETE FROM $table_name WHERE id = $id");
		$query = mysqli_real_escape_string($conn, $query);
		$check = $conn->query($query);
		return $check;
	}