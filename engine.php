<?php
	
	if (!isset($pagina_tipo)) {
		$pagina_tipo = false;
	}
	
	if (session_status() == PHP_SESSION_NONE) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
	}
	
	if (!isset($user_email)) {
		$user_email = false;
	}
	
	$user_logged_out = false;
	
	if (!isset($_SESSION['user_email'])) {
		$user_email = false;
		if ((!isset($_POST['login_email'])) && (!isset($_POST['thinkific_login']))) {
			if (($user_email == false) && ($pagina_tipo != 'logout') && ($pagina_tipo != 'login') && ($pagina_tipo != 'index')) {
				$user_logged_out = true;
				$user_id = false;
				$user_tipo = 'visitante';
				$user_email = false;
				if (!isset($_SESSION['curso_id'])) {
					$_SESSION['curso_id'] = 1;
				}
//				header('Location:logout.php');
//				exit();
			}
		}
	} else {
		$user_email = $_SESSION['user_email'];
	}
	include 'templates/criar_conn.php';
	
	if (isset($_POST['thinkific_login'])) {
		$thinkific_login = $_POST['thinkific_login'];
		$thinkific_senha = $_POST['thinkific_senha'];
		$encrypted = password_hash($thinkific_senha, PASSWORD_DEFAULT);
		$set_password = $conn->query("UPDATE Usuarios SET senha = '$encrypted' WHERE email = '$thinkific_login'");
		if ($set_password == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['login_email'])) {
		$login_email = $_POST['login_email'];
		$login_senha = $_POST['login_senha'];
		$login_senha2 = false;
		if (isset($_POST['login_senha2'])) {
			$login_senha2 = $_POST['login_senha2'];
		}
		if ($login_senha2 == false) {
			$login_origem = $_POST['login_origem'];
			$hashes = $conn->query("SELECT senha, origem FROM Usuarios WHERE email = '$login_email'");
			if ($hashes->num_rows > 0) {
				while ($hash = $hashes->fetch_assoc()) {
					$hash_senha = $hash['senha'];
					$hash_origem = $hash['origem'];
					$check = password_verify($login_senha, $hash_senha);
					if ($check == true) {
						if (($hash_origem == false) || ($hash_origem == 'confirmado') || ($hash_origem == 'thinkific')) {
							$_SESSION['user_email'] = $login_email;
							echo true;
						} else {
							echo 'confirmacao';
						}
					} elseif (($hash_origem == 'thinkific') && (is_null($hash_senha))) {
						echo 'thinkific';
					} else {
						echo false;
					}
				}
			} else {
				echo 'novo_usuario';
			}
		} else {
			$encrypted = password_hash($login_senha, PASSWORD_DEFAULT);
			$check = $conn->query("INSERT INTO Usuarios (tipo, email, senha) VALUES ('estudante', '$login_email', '$encrypted')");
			if ($check == true) {
				$_SESSION['user_email'] = $login_email;
				echo true;
			} else {
				echo false;
			}
		}
	}
	
	if ($user_email != false) {
		$usuarios = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome, language FROM Usuarios WHERE email = '$user_email'");
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$user_id = $usuario['id'];
				$user_tipo = $usuario['tipo'];
				$user_criacao = $usuario['criacao'];
				$user_apelido = $usuario['apelido'];
				$user_nome = $usuario['nome'];
				$user_sobrenome = $usuario['sobrenome'];
				$user_language = $usuario['language'];
				$user_wallet = (int)return_wallet_value($user_id);
				if ($user_language != false) {
					$_SESSION['lg'] = $user_language;
				}
			}
		}
	} else {
		$user_id = false;
		$user_tipo = false;
		$user_criacao = false;
		$user_apelido = false;
		$user_sobrenome = false;
		$user_wallet = false;
	}
	
	
	function return_wallet_value($user_id)
	{
		if ($user_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$wallet_contents = $conn->query("SELECT endstate FROM Transactions WHERE user_id = $user_id ORDER BY id DESC");
		if ($wallet_contents->num_rows > 0) {
			while ($wallet_content = $wallet_contents->fetch_assoc()) {
				$wallet_content_endstate = $wallet_content['endstate'];
				return $wallet_content_endstate;
			}
		} else {
			return false;
		}
	}
	
	include 'money_engine.php';
	
	if ($user_id != false) {
		$produtos = $conn->query("SELECT id FROM Carrinho WHERE user_id = $user_id AND estado = 1");
		if ($produtos->num_rows > 0) {
			$carregar_carrinho = true;
		} else {
			$carregar_carrinho = false;
		}
	}
	
	include 'templates/translation.php';
	$pagina_translated = translate_pagina($user_language);
	
	if (isset($_SESSION['curso_id'])) {
		$curso_id = $_SESSION['curso_id'];
	}
	if (isset($_GET['curso_id'])) {
		if ($user_id != false) {
			$curso_id = $_GET['curso_id'];
			$_SESSION['curso_id'] = $curso_id;
			$cursos_ativos = $conn->query("SELECT opcao FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'curso_ativo'");
			if ($cursos_ativos->num_rows > 0) {
				$conn->query("UPDATE Opcoes SET opcao = $curso_id WHERE user_id = $user_id AND opcao_tipo = 'curso_ativo'");
			} else {
				$conn->query("INSERT INTO Opcoes (opcao, opcao_tipo, user_id) VALUES ($curso_id, 'curso_ativo', $user_id)");
			}
		}
	} else {
		if ($user_id != false) {
			$cursos_ativos = $conn->query("SELECT opcao FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'curso_ativo'");
			if ($cursos_ativos->num_rows > 0) {
				while ($curso_ativo = $cursos_ativos->fetch_assoc()) {
					$curso_id = $curso_ativo['opcao'];
					$_SESSION['curso_id'] = $curso_id;
					break;
				}
			} else {
				$cursos = $conn->query("SELECT opcao FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'curso' ORDER BY id DESC");
				if ($cursos->num_rows > 0) {
					while ($curso = $cursos->fetch_assoc()) {
						$curso_id = $curso['opcao'];
						$_SESSION['curso_id'] = $curso_id;
						$conn->query("INSERT INTO Opcoes (opcao, opcao_tipo, user_id) VALUES ($curso_id, 'curso_ativo', $user_id)");
						break;
					}
				} else {
					$conn->query("INSERT INTO Opcoes (opcao, opcao_tipo, user_id) VALUES (1, 'curso', $user_id)");
					$conn->query("INSERT INTO Opcoes (opcao, opcao_tipo, user_id) VALUES (1, 'curso_ativo', $user_id)");
					$_SESSION['curso_id'] = 1;
					$curso_id = 1;
				}
			}
		}
	}
	if (isset($curso_id)) {
		$curso_sigla = return_curso_sigla($curso_id);
		$curso_titulo = return_curso_titulo_id($curso_id);
	}
	
	$all_buttons_classes = "btn rounded btn-md text-center";
	$button_classes = "$all_buttons_classes btn-primary";
	$button_small = 'brn rounded btn-sm text-center';
	$button_classes_light = "$all_buttons_classes btn-light";
	$button_classes_info = "$all_buttons_classes btn-info";
	$button_classes_red = "$all_buttons_classes btn-danger";
	$select_classes = "browser-default custom-select mt-2";
	$coluna_todas = false;
	$coluna_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$coluna_maior_classes = "col-lg-9 col-md-10 col-sm-11 $coluna_todas";
	$coluna_media_classes = "col-lg-7 col-md-10 col-sm-11 $coluna_todas";
	$coluna_pouco_maior_classes = "col-lg-6 col-md-10 col-sm-11 $coluna_todas";
	$row_classes = "pt-3 pb-5";
	
	$tag_ativa_classes = 'text-dark m-1 p-2 lighten-4 rounded remover_tag';
	$tag_inativa_classes = 'text-dark m-1 p-2 lighten-4 rounded adicionar_tag';
	$tag_neutra_classes = 'text-dark m-1 p-2 lighten-4 rounded';
	$tag_inativa_classes2 = 'text-dark m-1 p-2 lighten-4 rounded adicionar_tag2';
	
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
	
	if (isset($_POST['bookmark_change'])) {
		$bookmark_change = $_POST['bookmark_change'];
		$bookmark_pagina_id = $_POST['bookmark_pagina_id'];
		$bookmarks = $conn->query("SELECT id FROM Bookmarks WHERE user_id = $user_id AND pagina_id = $bookmark_pagina_id AND active = 1");
		if ($bookmarks->num_rows > 0) {
			while ($bookmark = $bookmarks->fetch_assoc()) {
				$bookmark_id = $bookmark['id'];
				$conn->query("UPDATE Bookmarks SET active = 0 WHERE id = $bookmark_id");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $bookmark_pagina_id, 'bookmark', $bookmark_change)");
		$conn->query("INSERT INTO Bookmarks (user_id, pagina_id, bookmark, active) VALUES ($user_id, $bookmark_pagina_id, $bookmark_change, 1)");
	}
	
	if (isset($_POST['completed_change'])) {
		$completed_change = $_POST['completed_change'];
		$completed_pagina_id = $_POST['completed_pagina_id'];
		$completos = $conn->query("SELECT id FROM Completed WHERE user_id = $user_id AND pagina_id = $completed_pagina_id AND active = 1");
		if ($completos->num_rows > 0) {
			while ($completo = $completos->fetch_assoc()) {
				$completed_id = $completo['id'];
				$conn->query("UPDATE Completed SET active = 0 WHERE id = $completed_id");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $completed_pagina_id, 'completed', $completed_change)");
		$conn->query("INSERT INTO Completed (user_id, pagina_id, estado, active) VALUES ($user_id, $completed_pagina_id, $completed_change, 1)");
	}
	
	$opcao_texto_justificado_value = false;
	if ($user_id != false) {
		$opcoes = $conn->query("SELECT opcao FROM Opcoes WHERE opcao_tipo = 'texto_justificado' AND user_id = $user_id ORDER BY id DESC");
		if ($opcoes->num_rows > 0) {
			while ($opcao = $opcoes->fetch_assoc()) {
				$opcao_texto_justificado_value = $opcao['opcao'];
				break;
			}
		}
	}
	
	if (isset($_POST['sbcommand'])) {
		$busca_curso_id = base64_decode($_POST['sbcurso']);
		$command = base64_decode($_POST['sbcommand']);
		$command = utf8_encode($command);
		$found = false;
		$result = $conn->query("SELECT pagina_id FROM Searchbar WHERE curso_id = $busca_curso_id AND chave = '$command' ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$pagina_id = $row["pagina_id"];
				echo "foundfoundfoundfpagina.php?pagina_id=$pagina_id";
				return;
			}
		}
		$index = 500;
		$winner = 0;
		$result = $conn->query("SELECT chave FROM Searchbar WHERE curso_id = $busca_curso_id AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
		$commandlow = mb_strtolower($command);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row["chave"];
				$chavelow = mb_strtolower($chave);
				$check = levenshtein($chavelow, $commandlow, 1, 1, 1);
				if (strpos($chavelow, $commandlow) !== false) {
					echo "notfoundnotfound$chave";
					return;
				} elseif ($check < $index) {
					$index = $check;
					$winner = $chave;
				}
			}
			$length = strlen($winner);
			$length = $length / 3;
			if ($index < $length) {
				echo "notfoundnotfound$winner";
				return;
			} else {
				echo "foundfoundfoundfbusca.php?busca=$command";
			}
		}
		return;
	}
	
	if ((isset($_POST['novo_texto_titulo'])) && (isset($_POST['novo_texto_titulo_id']))) {
		include 'templates/criar_conn.php';
		
		$novo_texto_titulo = $_POST['novo_texto_titulo'];
		$novo_texto_titulo = mysqli_real_escape_string($conn, $novo_texto_titulo);
		$novo_texto_titulo_id = $_POST['novo_texto_titulo_id'];
		
		$conn->query("UPDATE Textos SET titulo = '$novo_texto_titulo' WHERE id = $novo_texto_titulo_id");
		echo true;
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
	
	if (isset($_POST['nova_imagem'])) {
		error_log('does this ever happen?');
		$nova_imagem_link = $_POST['nova_imagem'];
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		$nova_imagem_subtipo = $_POST['nova_imagem_subtipo'];
		if ($nova_imagem_titulo == false) {
			$nova_imagem_titulo = 'Não há título registrado';
		}
		$user_id = $_POST['user_id'];
		$page_id = $_POST['page_id'];
		$pagina_tipo = $_POST['contexto'];
		$nossa_copia = adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $page_id, $user_id, $pagina_tipo, $curso_id, false);
		echo $nossa_copia;
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
		$check_imagem_existe = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link' AND compartilhamento IS NULL");
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
			$conn->query("INSERT INTO Elementos (etiqueta_id, compartilhamento, tipo, subtipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ($nova_imagem_etiqueta_id, $nova_imagem_compartilhamento, 'imagem', '$nova_imagem_subtipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
			$nova_imagem_id = $conn->insert_id;
		} else {
			$nova_imagem_id = $imagem_preexistente_id;
		}
		//antes de fazer isso, checar se o elemento já existe na página. Não é tão importante por que há
		//um check de duplicatas na página, mas talvez não exista no escritório.
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, subtipo, extra, user_id) VALUES ($pagina_id, '$pagina_tipo', $nova_imagem_id, 'imagem', '$nova_imagem_subtipo', $nova_imagem_etiqueta_id, $user_id)");
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
	
	function return_titulo_topico($topico_id)
	{
		return false;
	}
	
	function return_titulo_elemento($elemento_id)
	{
		include 'templates/criar_conn.php';
		$elementos = $conn->query("SELECT titulo FROM Elementos WHERE id = $elemento_id");
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
		$paginas = $conn->query("SELECT item_id FROM Paginas WHERE id = $pagina_id");
		if ($paginas->num_rows > 0) {
			while ($pagina = $paginas->fetch_assoc()) {
				$pagina_item_id = $pagina['item_id'];
				return $pagina_item_id;
			}
		}
		return false;
	}
	
	function return_curso_id_topico($topico_id)
	{
		/*include 'templates/criar_conn.php';
		if ($topico_id == false) {
			return false;
		}
		$result_find_curso = $conn->query("SELECT curso_id FROM Topicos WHERE id = $topico_id");
		if ($result_find_curso->num_rows > 0) {
			while ($row_find_curso = $result_find_curso->fetch_assoc()) {
				$found_curso_id = $row_find_curso['curso_id'];
			}
			return $found_curso_id;
		}*/
		return false;
	}
	
	function return_materia_id_topico($topico_id)
	{
		return false;
	}
	
	
	function return_simulado_info($find_simulado_id)
	{
		include 'templates/criar_conn.php';
		$find_simulados = $conn->query("SELECT criacao, tipo, curso_id FROM sim_gerados WHERE id = $find_simulado_id");
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
		$result_find_apelido = $conn->query("SELECT apelido FROM Usuarios WHERE id = $find_user_id");
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
		$result_find_titulo = $conn->query("SELECT titulo FROM sim_etapas WHERE id = $etapa_id");
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
		$result = $conn->query("SELECT edicao_id FROM sim_etapas WHERE id = $etapa_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$edicao_id = $row['edicao_id'];
				$result2 = $conn->query("SELECT ano, titulo FROM sim_edicoes WHERE id = $edicao_id");
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
		$provas = $conn->query("SELECT etapa_id, titulo, tipo FROM sim_provas WHERE id = $prova_id");
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
		$textos_apoio = $conn->query("SELECT prova_id FROM sim_textos_apoio WHERE id = $texto_apoio_id");
		if ($textos_apoio->num_rows > 0) {
			while ($texto_apoio = $textos_apoio->fetch_assoc()) {
				$texto_apoio_prova_id = $texto_apoio['prova_id'];
				return $texto_apoio_prova_id;
			}
		}
		return false;
	}
	
	function return_materia_titulo_id($materia_id)
	{
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
	
	function return_estado_icone($estado_pagina, $contexto)
	{
		$icone0 = 'fad fa-empty-set fa-fw';
		$icone1 = 'fad fa-acorn fa-fw';
		$icone2 = 'fad fa-seedling fa-fw';
		$icone3 = 'fad fa-leaf fa-fw';
		$icone4 = 'fad fa-spa fa-fw';
		/*
		if ($contexto == 'pagina') {
			$icone0 = 'fal fa-empty-set fa-fw';
			$icone1 = 'fas fa-acorn fa-fw';
			$icone2 = 'fas fa-seedling fa-fw';
		}
		*/
		if ($estado_pagina == 0) {
			return $icone0;
		} elseif ($estado_pagina == 1) {
			return $icone1;
		} elseif ($estado_pagina == 2) {
			return $icone2;
		} elseif ($estado_pagina == 3) {
			return $icone3;
		} elseif ($estado_pagina == 4) {
			return $icone4;
		}
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
	
	if (isset($_POST['questao_id'])) {
		$questao_tipo = $_POST['questao_tipo'];
		$simulado_id = $_POST['simulado_id'];
		
		$user_id = $_POST['user_id'];
		$questao_curso_id = $_POST['curso_id'];
		$questao_id = $_POST['questao_id'];
		$questao_numero = $_POST['questao_numero'];
		
		if ($questao_tipo == 1) {
			$item1_resposta = $_POST['item1'];
			$item2_resposta = $_POST['item2'];
			$item3_resposta = $_POST['item3'];
			$item4_resposta = $_POST['item4'];
			$item5_resposta = $_POST['item5'];
			$conn->query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, item1, item2, item3, item4, item5) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $item1_resposta, $item2_resposta, $item3_resposta, $item4_resposta, $item5_resposta)");
			echo true;
		} elseif ($questao_tipo == 2) {
			$resposta = $_POST['resposta'];
			$conn->query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, multipla) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $resposta)");
			echo true;
		} elseif ($questao_tipo == 3) {
			$redacao_html = $_POST['redacao_html'];
			$redacao_text = $_POST['redacao_text'];
			$redacao_content = $_POST['redacao_content'];
			$redacao_html = mysqli_real_escape_string($conn, $redacao_html);
			$redacao_text = mysqli_real_escape_string($conn, $redacao_text);
			$redacao_content = mysqli_real_escape_string($conn, $redacao_content);
			$conn->query("INSERT INTO sim_respostas (user_id, curso_id, simulado_id, questao_id, questao_tipo, questao_numero, redacao_html, redacao_text, redacao_content) VALUES ($user_id, $questao_curso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, '$redacao_html', '$redacao_text', '$redacao_content')");
			echo true;
		}
		echo false;
	}
	
	if (isset($_POST['busca_referencias'])) {
		$busca_referencias = $_POST['busca_referencias'];
		if (isset($_POST['busca_referencias_tipo'])) {
			$busca_referencias_tipo = $_POST['busca_referencias_tipo'];
		} else {
			$busca_referencias_tipo = false;
		}
		$busca_referencias = mysqli_real_escape_string($conn, $busca_referencias);
		$busca_resultados = false;
		if ($busca_referencias_tipo == false) {
			$referencia_exata = $conn->query("SELECT titulo FROM Elementos WHERE titulo = '$busca_referencias' AND (tipo = 'referencia' OR tipo = 'video' OR tipo = 'album_musica')");
		} else {
			$referencia_exata = $conn->query("SELECT titulo FROM Elementos WHERE titulo = '$busca_referencias' AND tipo = '$busca_referencias_tipo'");
		}
		if ($referencia_exata->num_rows == 0) {
			$busca_resultados .= "<div class='col-12 pl-0'><button type='button' id='criar_referencia' name='criar_referencia' class='btn rounded btn-md text-center btn-info btn-sm mb-2' value='$busca_referencias'>{$pagina_translated['Referência não encontrada, criar nova?']}</button></div>";
		}
		if ($busca_referencias_tipo == false) {
			$elementos = $conn->query("SELECT id, etiqueta_id, compartilhamento, titulo, autor, tipo, user_id FROM Elementos WHERE titulo LIKE '%{$busca_referencias}%'");
		} else {
			$elementos = $conn->query("SELECT id, etiqueta_id, compartilhamento, titulo, autor, tipo, user_id FROM Elementos WHERE titulo LIKE '%{$busca_referencias}%' AND tipo = '$busca_referencias_tipo'");
		}
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				$elemento_etiqueta_id = $elemento['etiqueta_id'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				$elemento_tipo = $elemento['tipo'];
				$elemento_user_id = $elemento['user_id'];
				$elemento_compartilhamento = $elemento['compartilhamento'];
				if ($elemento_compartilhamento == 'privado') {
					if ($elemento_user_id != $user_id) {
						continue;
					}
				}
				$elemento_cor_icone = return_etiqueta_cor_icone($elemento_tipo);
				$elemento_cor = $elemento_cor_icone[0];
				$elemento_icone = $elemento_cor_icone[1];
				$busca_resultados .= "<a href='javascript:void(0)' class='$tag_inativa_classes $elemento_cor lighten-4 acrescentar_referencia_bibliografia' value='$elemento_etiqueta_id'><i class='far $elemento_icone fa-fw'></i> $elemento_titulo</a>";
			}
		}
		echo $busca_resultados;
	}
	
	if (isset($_POST['adicionar_referencia_titulo'])) {
		$adicionar_referencia_titulo = $_POST['adicionar_referencia_titulo'];
		$adicionar_referencia_titulo = mysqli_real_escape_string($conn, $adicionar_referencia_titulo);
		$adicionar_referencia_autor = $_POST['adicionar_referencia_autor'];
		$adicionar_referencia_autor = mysqli_real_escape_string($conn, $adicionar_referencia_autor);
		$adicionar_referencia_link = $_POST['adicionar_referencia_link'];
		$adicionar_referencia_link = mysqli_real_escape_string($conn, $adicionar_referencia_link);
		$adicionar_referencia_tipo = $_POST['adicionar_referencia_tipo'];
		$adicionar_referencia_subtipo = $_POST['adicionar_referencia_subtipo'];
		$adicionar_referencia_contexto = $_POST['adicionar_referencia_contexto'];
		$adicionar_referencia_pagina_id = $_POST['adicionar_referencia_pagina_id'];
		
		$nova_etiqueta = criar_etiqueta($adicionar_referencia_titulo, $adicionar_referencia_autor, $adicionar_referencia_tipo, $user_id, true, $adicionar_referencia_link, $adicionar_referencia_subtipo);
		$nova_etiqueta_id = $nova_etiqueta[0];
		$nova_etiqueta_autor_id = $nova_etiqueta[1];
		$nova_etiqueta_elemento_id = $nova_etiqueta[2];
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($adicionar_referencia_pagina_id, '$adicionar_referencia_contexto', $nova_etiqueta_elemento_id, '$adicionar_referencia_tipo', '$adicionar_referencia_subtipo', $user_id)");
		echo true;
	}
	
	if (isset($_POST['pagina_nova_etiqueta_id'])) {
		$nova_etiqueta_id = $_POST['pagina_nova_etiqueta_id'];
		$nova_etiqueta_pagina_id = $_POST['nova_etiqueta_pagina_id'];
		$novo_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
		$novo_elemento_info = return_elemento_info($novo_elemento_id);
		$novo_elemento_tipo = $novo_elemento_info[3];
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, elemento_id, tipo, user_id) VALUES ($nova_etiqueta_pagina_id, $novo_elemento_id, '$novo_elemento_tipo', $user_id)");
		echo true;
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
			$etiquetas = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$autor' AND tipo = 'autor'");
			if ($etiquetas->num_rows == 0) {
				$conn->query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$autor', 'autor', $user_id)");
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
			$etiquetas = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$nova_etiqueta' AND tipo = '$tipo'");
			if ($etiquetas->num_rows == 0) {
				$conn->query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$nova_etiqueta', '$tipo', $user_id)");
				$nova_etiqueta_id = $conn->insert_id;
				$nova_etiqueta_criada = true;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$nova_etiqueta_id = $etiqueta['id'];
				}
			}
		} elseif (($autor == false) || ($titulo != false)) {
			$titulo = mysqli_real_escape_string($conn, $titulo);
			$etiquetas = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$titulo' AND tipo = '$tipo'");
			if ($etiquetas->num_rows == 0) {
				$conn->query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$titulo', '$tipo', $user_id)");
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
				$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, subtipo, titulo, autor, autor_etiqueta_id, user_id, link) VALUES ($nova_etiqueta_id, '$tipo', '$subtipo', '$titulo', '$autor', $nova_etiqueta_autor_id, $user_id, $link)");
				$novo_elemento_id = $conn->insert_id;
				$novo_elemento_criado = true;
			} else {
				$novo_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
			}
		}
		return array($nova_etiqueta_id, $nova_etiqueta_autor_id, $novo_elemento_id, $nova_etiqueta_criada, $novo_elemento_criado);
	}
	
	/*	if (isset($_POST['acrescentar_referencia_id'])) {
			$acrescentar_referencia_id = $_POST['acrescentar_referencia_id'];
			$acrescentar_referencia_info = return_etiqueta_info($acrescentar_referencia_id);
			$acrescentar_referencia_tipo = $acrescentar_referencia_info[1];
			$acrescentar_referencia_elemento_id = return_etiqueta_elemento_id($acrescentar_referencia_id);
			if ($conn->query("INSERT INTO Acervo (user_id, etiqueta_id, etiqueta_tipo, elemento_id) VALUES ($user_id, $acrescentar_referencia_id, '$acrescentar_referencia_tipo', $acrescentar_referencia_elemento_id)") === true) {
				echo true;
			} else {
				echo false;
			}
		}*/
	
	function return_etiqueta_elemento_id($etiqueta_id)
	{
		if ($etiqueta_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$elementos = $conn->query("SELECT id FROM Elementos WHERE etiqueta_id = $etiqueta_id");
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
		$topicos = $conn->query("SELECT id FROM Topicos WHERE etiqueta_id = $etiqueta_id");
		if ($topicos->num_rows > 0) {
			while ($topico = $topicos->fetch_assoc()) {
				$topico_id = $topico['id'];
				return $topico_id;
			}
		}*/
		return false;
	}
	
	if (isset($_POST['busca_autores'])) {
		$busca_autores = $_POST['busca_autores'];
		$busca_resultados = false;
		$autores = $conn->query("SELECT DISTINCT titulo FROM Etiquetas WHERE tipo = 'autor' AND titulo LIKE '%{$busca_autores}%'");
		if ($autores->num_rows > 0) {
			while ($autor = $autores->fetch_assoc()) {
				$busca_autor = $autor['titulo'];
				$busca_resultados .= "<a href='javascript:void(0);' class='$tag_inativa_classes2 blue-grey adicionar_autor' value='$busca_autor'>$busca_autor</a>";
			}
		} else {
			return false;
		}
		echo $busca_resultados;
	}
	
	if (isset($_POST['curso_novo_topico_id'])) {
		if (isset($_POST['curso_novo_topico_pagina_id'])) {
			$curso_novo_topico_id = $_POST['curso_novo_topico_id'];
			$curso_novo_topico_pagina_id = $_POST['curso_novo_topico_pagina_id'];
			$curso_novo_topico_user_id = $_POST['curso_novo_topico_user_id'];
			$conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $curso_novo_topico_pagina_id, $curso_novo_topico_id, $curso_novo_topico_user_id)");
			$novo_topico_pagina_id = $conn->insert_id;
			$novo_topico_etiqueta_info = return_etiqueta_info($curso_novo_topico_id);
			$novo_topico_pagina_titulo = $novo_topico_etiqueta_info[2];
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_novo_topico_pagina_id, 'materia', $novo_topico_pagina_id, 'topico', $curso_novo_topico_user_id)");
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_topico_pagina_id, 'pagina', 'titulo', '$novo_topico_pagina_titulo', $curso_novo_topico_user_id)");
			echo true;
		} else {
			echo false;
		}
	}
	if (isset($_POST['curso_novo_subtopico_id'])) {
		if (isset($_POST['curso_novo_subtopico_pagina_id'])) {
			$curso_novo_subtopico_id = $_POST['curso_novo_subtopico_id'];
			$curso_novo_subtopico_pagina_id = $_POST['curso_novo_subtopico_pagina_id'];
			$curso_novo_subtopico_user_id = $_POST['curso_novo_subtopico_user_id'];
			$conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $curso_novo_subtopico_pagina_id, $curso_novo_subtopico_id, $curso_novo_subtopico_user_id)");
			$novo_subtopico_pagina_id = $conn->insert_id;
			$novo_subtopico_etiqueta_info = return_etiqueta_info($curso_novo_subtopico_id);
			$novo_subtopico_pagina_titulo = $novo_subtopico_etiqueta_info[2];
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_novo_subtopico_pagina_id, 'topico', $novo_subtopico_pagina_id, 'subtopico', $curso_novo_subtopico_user_id)");
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_subtopico_pagina_id, 'pagina', 'titulo', '$novo_subtopico_pagina_titulo', $curso_novo_subtopico_user_id)");
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['curso_nova_materia_id'])) {
		if (isset($_POST['curso_nova_materia_pagina_id'])) {
			$curso_nova_materia_id = $_POST['curso_nova_materia_id'];
			$curso_nova_materia_pagina_id = $_POST['curso_nova_materia_pagina_id'];
			$curso_nova_materia_user_id = $_POST['curso_nova_materia_user_id'];
			$check = $conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('materia', $curso_nova_materia_pagina_id, $curso_nova_materia_id, $curso_nova_materia_user_id)");
			$nova_materia_pagina_id = $conn->insert_id;
			$nova_materia_etiqueta_info = return_etiqueta_info($curso_nova_materia_id);
			$nova_materia_pagina_titulo = $nova_materia_etiqueta_info[2];
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($curso_nova_materia_pagina_id, 'curso', $nova_materia_pagina_id, 'materia', $curso_nova_materia_user_id)");
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_materia_pagina_id, 'pagina', 'titulo', '$nova_materia_pagina_titulo', $curso_nova_materia_user_id)");
			
			echo $check;
		}
	}
	
	if (isset($_POST['criar_topico_titulo'])) {
		$criar_topico_titulo = $_POST['criar_topico_titulo'];
		$criar_topico_page_id = $_POST['criar_topico_page_id'];
		$criar_topico_page_tipo = $_POST['criar_topico_page_tipo'];
		
		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];
		
		$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_topico_titulo', $user_id)");
		$nova_etiqueta_id = $conn->insert_id;
		
		$check = $conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $criar_topico_page_id, $nova_etiqueta_id, $user_id)");
		$novo_topico_pagina_id = $conn->insert_id;
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_topico_page_id, '$criar_topico_page_tipo', 'topico', $novo_topico_pagina_id, $user_id)");
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_topico_pagina_id, 'pagina', 'titulo', '$criar_topico_titulo', $user_id)");
		
		echo $check;
		
	}
	if (isset($_POST['criar_subtopico_titulo'])) {
		$criar_subtopico_titulo = $_POST['criar_subtopico_titulo'];
		$criar_subtopico_page_id = $_POST['criar_subtopico_page_id'];
		$criar_subtopico_page_tipo = $_POST['criar_subtopico_page_tipo'];
		
		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];
		
		$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_subtopico_titulo', $user_id)");
		$nova_etiqueta_id = $conn->insert_id;
		
		$check = $conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('topico', $criar_subtopico_page_id, $nova_etiqueta_id, $user_id)");
		$novo_subtopico_pagina_id = $conn->insert_id;
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_subtopico_page_id, '$criar_subtopico_page_tipo', 'subtopico', $novo_subtopico_pagina_id, $user_id)");
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_subtopico_pagina_id, 'pagina', 'titulo', '$criar_subtopico_titulo', $user_id)");
		
		echo $check;
		
	}
	
	if (isset($_POST['criar_materia_titulo'])) {
		$criar_materia_titulo = $_POST['criar_materia_titulo'];
		$criar_materia_page_id = $_POST['criar_materia_page_id'];
		$criar_materia_page_id = $_POST['criar_materia_page_id'];
		$criar_materia_page_tipo = $_POST['criar_materia_page_tipo'];
		
		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];
		
		$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_materia_titulo', $user_id)");
		$nova_etiqueta_id = $conn->insert_id;
		
		$check = $conn->query("INSERT INTO Paginas (tipo, item_id, etiqueta_id, user_id) VALUES ('materia', $criar_materia_page_id, $nova_etiqueta_id, $user_id)");
		$nova_materia_pagina_id = $conn->insert_id;
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, elemento_id, user_id) VALUES ($criar_materia_page_id, '$criar_materia_page_tipo', 'materia', $nova_materia_pagina_id, $user_id)");
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($nova_materia_pagina_id, 'pagina', 'titulo', '$criar_materia_titulo', $user_id)");
		
		echo $check;
		
	}
	
	if (isset($_POST['busca_etiquetas'])) {
		if (isset($_POST['busca_etiquetas_contexto'])) {
			$busca_etiquetas_contexto = $_POST['busca_etiquetas_contexto'];
		}
		if ($busca_etiquetas_contexto == 'curso') {
			$acao_etiqueta_criar = 'criar_materia';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_materia', $tag_inativa_classes);
		} elseif ($busca_etiquetas_contexto == 'materia') {
			$acao_etiqueta_criar = 'criar_topico';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_topico', $tag_inativa_classes);
		} elseif ($busca_etiquetas_contexto == 'topico') {
			$acao_etiqueta_criar = 'criar_subtopico';
			$tag_inativa_classes = str_replace('adicionar_tag', 'adicionar_subtopico', $tag_inativa_classes);
		} else {
			$acao_etiqueta_criar = 'criar_etiqueta';
		}
		/*else {
			$tag_ativa_classes = str_replace('remover_tag', 'remover_materia', $tag_ativa_classes);
			$acao_etiqueta_criar = 'criar_etiqueta';
		}*/
		if (isset($_POST['busca_etiquetas_sem_link'])) {
			$busca_etiquetas_sem_link = $_POST['busca_etiquetas_sem_link'];
		} else {
			$busca_etiquetas_sem_link = false;
		}
		if (isset($_POST['busca_etiquetas_tipo'])) {
			$busca_etiquetas_tipo = $_POST['busca_etiquetas_tipo'];
		} else {
			$busca_etiquetas_tipo = 'all';
		}
		$busca_etiquetas = $_POST['busca_etiquetas'];
		$busca_etiquetas = mysqli_real_escape_string($conn, $busca_etiquetas);
		$busca_resultados = false;
		if ($busca_etiquetas_tipo == 'all') {
			$etiqueta_exata = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas'");
		} else {
			$etiqueta_exata = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas' AND tipo = '$busca_etiquetas_tipo'");
		}
		if ($etiqueta_exata->num_rows == 0) {
			$busca_resultados .= "<div class='col-12'><div class='row d-flex justify-content-center'><button type='button' id='$acao_etiqueta_criar' name='$acao_etiqueta_criar' class='btn rounded btn-md text-center btn-success w-50 btn-sm m-0 mb-2' value='$busca_etiquetas'>{$pagina_translated['Criar etiqueta']} \"$busca_etiquetas\"</button></div></div>";
		}
		if ($busca_etiquetas_tipo == 'all') {
			$etiquetas = $conn->query("SELECT id, tipo, titulo FROM Etiquetas WHERE titulo LIKE '%{$busca_etiquetas}%'");
		} else {
			$etiquetas = $conn->query("SELECT id, tipo, titulo FROM Etiquetas WHERE titulo LIKE '%{$busca_etiquetas}%' AND tipo = '$busca_etiquetas_tipo'");
		}
		if ($etiquetas->num_rows > 0) {
			while ($etiqueta = $etiquetas->fetch_assoc()) {
				$etiqueta_id = $etiqueta['id'];
				$etiqueta_tipo = $etiqueta['tipo'];
				$etiqueta_titulo = $etiqueta['titulo'];
				
				$etiqueta_cor_icone = return_etiqueta_cor_icone($etiqueta_tipo);
				$etiqueta_cor = $etiqueta_cor_icone[0];
				$etiqueta_icone = $etiqueta_cor_icone[1];
				
				if ($etiqueta_cor != false) {
					if ($busca_etiquetas_sem_link == true) {
						$busca_resultados .= "<span href='javascript:void(0);' class='$tag_neutra_classes $etiqueta_cor'><i class='far $etiqueta_icone fa-fw'></i> $etiqueta_titulo</span>";
					} else {
						$busca_resultados .= "<a href='javascript:void(0);' class='$tag_inativa_classes $etiqueta_cor' value='$etiqueta_id'><i class='far $etiqueta_icone fa-fw'></i> $etiqueta_titulo</a>";
					}
				}
			}
		} else {
			$busca_resultados .= "<p><em>Nenhuma etiqueta encontrada.</em></p>";
		}
		echo $busca_resultados;
	}
	
	// Este mecanismo precisa ser dinâmico o suficiente para que funcione tanto para elementos quanto
	// para tópicos. Ele é compartilhado por ambos os sistemas, como se vê no condicional dos sitemas
	// no arquivo html_bottom
	if (isset($_POST['nova_etiqueta_id'])) {
		$nova_etiqueta_id = $_POST['nova_etiqueta_id'];
		$nova_etiqueta_page_id = $_POST['nova_etiqueta_page_id'];
		$nova_etiqueta_page_tipo = $_POST['nova_etiqueta_page_tipo'];
		$nova_etiqueta_info = return_etiqueta_info($nova_etiqueta_id);
		$nova_etiqueta_tipo = $nova_etiqueta_info[1];
		$nova_etiqueta_titulo = $nova_etiqueta_info[2];
		$nova_etiqueta_elemento_id = false;
		$halt = false;
		if ($nova_etiqueta_tipo == 'topico') {
			$nova_etiqueta_elemento_id = return_etiqueta_topico_id($nova_etiqueta_id);
		} else {
			$nova_etiqueta_elemento_id = return_etiqueta_elemento_id($nova_etiqueta_id);
			$nova_etiqueta_elemento_info = return_elemento_info($nova_etiqueta_elemento_id);
			$nova_etiqueta_elemento_user_id = $nova_etiqueta_elemento_info[16];
			$nova_etiqueta_elemento_compartilhamento = $nova_etiqueta_elemento_info[17];
			if ($nova_etiqueta_elemento_compartilhamento == 'privado') {
				if ($user_id != $nova_etiqueta_elemento_user_id) {
					$halt = true;
				} else {
					if ($nova_etiqueta_tipo == 'imagem_privada') {
						$nova_etiqueta_tipo = 'imagem';
					}
				}
			}
		}
		if ($nova_etiqueta_elemento_id == false) {
			$nova_etiqueta_elemento_id = "NULL";
		}
		if ($halt == false) {
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($nova_etiqueta_page_id, '$nova_etiqueta_page_tipo', $nova_etiqueta_elemento_id, '$nova_etiqueta_tipo', $nova_etiqueta_id, $user_id)");
			$nova_etiqueta_cor_icone = return_etiqueta_cor_icone($nova_etiqueta_tipo);
			$nova_etiqueta_cor = $nova_etiqueta_cor_icone[0];
			$nova_etiqueta_icone = $nova_etiqueta_cor_icone[1];
			if ($nova_etiqueta_tipo == 'topico') {
				echo "<a href='javascript:void(0);' class='$tag_ativa_classes $nova_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $nova_etiqueta_icone fa-fw'></i> $nova_etiqueta_titulo</a>";
			} else {
				echo true;
			}
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['criar_etiqueta_titulo'])) {
		$criar_etiqueta_titulo = $_POST['criar_etiqueta_titulo'];
		$criar_etiqueta_page_id = $_POST['criar_etiqueta_page_id'];
		$criar_etiqueta_page_tipo = $_POST['criar_etiqueta_page_tipo'];
		
		$criar_etiqueta_cor_icone = return_etiqueta_cor_icone('topico');
		$criar_etiqueta_cor = $criar_etiqueta_cor_icone[0];
		$criar_etiqueta_icone = $criar_etiqueta_cor_icone[1];
		
		$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_etiqueta_titulo', $user_id)");
		$nova_etiqueta_id = $conn->insert_id;
		
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($criar_etiqueta_page_id, '$criar_etiqueta_page_tipo', NULL, 'topico', $nova_etiqueta_id, $user_id)");
		
		echo "<a href='javascript:void(0);' class='$tag_ativa_classes $criar_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</a>";
	}
	
	if (isset($_POST['remover_etiqueta_id'])) {
		$remover_etiqueta_id = $_POST['remover_etiqueta_id'];
		$remover_etiqueta_page_id = $_POST['remover_etiqueta_page_id'];
		$remover_etiqueta_page_tipo = $_POST['remover_etiqueta_page_tipo'];
		$conn->query("UPDATE Paginas_elementos SET estado = FALSE WHERE extra IN ('$remover_etiqueta_id') AND pagina_id = $remover_etiqueta_page_id");
	}
	
	function return_etiqueta_info($etiqueta_id)
	{
		if ($etiqueta_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$etiquetas = $conn->query("SELECT criacao, tipo, titulo, user_id, pagina_id FROM Etiquetas WHERE id = $etiqueta_id");
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
		$etiquetas = $conn->query("SELECT etiqueta_id FROM Elementos WHERE id = $elemento_id");
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
	
	$fa_secondary_color_anotacao = '#2196f3';
	$fa_icone_anotacao = 'fa-file-alt';
	$fa_secondary_color_imagem = "#ff5722";
	$fa_primary_color_imagem = "#ffab91";
	$fa_icone_imagem = 'fa-file-image';
	
	function update_etiqueta_elemento($elemento_id, $user_id)
	{
		include 'templates/criar_conn.php';
		$elementos = $conn->query("SELECT etiqueta_id, autor_etiqueta_id, tipo, titulo, autor FROM Elementos WHERE id = $elemento_id");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_etiqueta_id = $elemento['etiqueta_id'];
				$elemento_autor_etiqueta_id = $elemento['autor_etiqueta_id'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				if ($elemento_autor != false) {
					$elemento_etiqueta_novo_titulo = "$elemento_titulo / $elemento_autor";
					if ($elemento_autor_etiqueta_id != false) {
						$autores = $conn->query("SELECT id FROM Etiquetas WHERE id = $elemento_autor_etiqueta_id AND titulo = '$elemento_autor'");
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
		include 'templates/criar_conn.php';
		if ($elemento_id == false) {
			return false;
		}
		$elementos = $conn->query("SELECT estado, etiqueta_id, criacao, tipo, subtipo, titulo, autor, autor_etiqueta_id, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id, compartilhamento FROM Elementos WHERE id = $elemento_id");
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
				$result = array($elemento_estado, $elemento_etiqueta_id, $elemento_criacao, $elemento_tipo, $elemento_titulo, $elemento_autor, $elemento_autor_etiqueta_id, $elemento_capitulo, $elemento_ano, $elemento_link, $elemento_iframe, $elemento_arquivo, $elemento_resolucao, $elemento_orientacao, $elemento_comentario, $elemento_trecho, $elemento_user_id, $elemento_compartilhamento, $elemento_subtipo);
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
		$textos = $conn->query("SELECT curso_id, tipo, titulo, page_id, criacao, verbete_html, verbete_text, verbete_content, user_id, pagina_id, pagina_tipo, compartilhamento, texto_pagina_id FROM Textos WHERE id = $texto_id");
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
		
		include 'templates/criar_conn.php';
		
		$usuario_avatar = 'fa-user-tie';
		$usuario_avatar_cor = false;
		
		$opcoes_avatar = $conn->query("SELECT opcao_string FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar' ORDER BY id DESC");
		if ($opcoes_avatar->num_rows > 0) {
			while ($opcao_avatar = $opcoes_avatar->fetch_assoc()) {
				$usuario_avatar = $opcao_avatar['opcao_string'];
				break;
			}
		}
		
		$opcoes_cor = $conn->query("SELECT opcao_string FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'avatar_cor' ORDER BY id DESC");
		if ($opcoes_cor->num_rows > 0) {
			while ($opcao_cor = $opcoes_cor->fetch_assoc()) {
				$usuario_avatar_cor = $opcao_cor['opcao_string'];
				break;
			}
		}
		
		return array($usuario_avatar, $usuario_avatar_cor);
		
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
		if ($tipo == 'topico') {
			$topico_titulo = return_titulo_topico($item_id);
			if ($topico_titulo == false) {
				return false;
			}
			/*$topicos = $conn->query("SELECT pagina_id FROM Topicos WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($topicos->num_rows > 0) {
				while ($topico = $topicos->fetch_assoc()) {
					$topico_pagina_id = $topico['pagina_id'];
					return $topico_pagina_id;
				}
			} else {
				$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'topico')");
				$topico_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Topicos SET pagina_id = $topico_pagina_id WHERE id = $item_id");
				return $topico_pagina_id;
			}*/
		} elseif ($tipo == 'elemento') {
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
		} elseif ($tipo == 'materia') {
			$materia_titulo = return_materia_titulo_id($item_id);
			if ($materia_titulo == false) {
				return false;
			}
			/*$materias = $conn->query("SELECT pagina_id FROM Materias WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($materias->num_rows > 0) {
				while ($materia = $materias->fetch_assoc()) {
					$materia_pagina_id = $materia['pagina_id'];
					return $materia_pagina_id;
				}
			} else {
				$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'materia')");
				$materia_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Materias SET pagina_id = $materia_pagina_id WHERE id = $item_id");
				return $materia_pagina_id;
			}*/
		} elseif ($tipo == 'grupo') {
			$grupo_titulo = return_grupo_titulo_id($item_id);
			if ($grupo_titulo == false) {
				return false;
			}
			$grupos = $conn->query("SELECT pagina_id FROM Grupos WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($grupos->num_rows > 0) {
				while ($grupo = $grupos->fetch_assoc()) {
					$grupo_pagina_id = $grupo['pagina_id'];
					return $grupo_pagina_id;
				}
			} else {
				$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento) VALUES ($item_id, 'grupo', 'grupo')");
				$grupo_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Grupos SET pagina_id = $grupo_pagina_id WHERE id = $item_id");
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
			$textos = $conn->query("SELECT texto_pagina_id FROM Textos WHERE id = $item_id AND texto_pagina_id IS NOT NULL");
			if ($textos->num_rows > 0) {
				while ($texto = $textos->fetch_assoc()) {
					$texto_pagina_id = $texto['texto_pagina_id'];
					return $texto_pagina_id;
				}
			} else {
				$texto_user_id = $texto_info[8];
				$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, user_id) VALUES ($item_id, 'texto', $texto_compartilhamento, $texto_user_id)");
				$texto_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Textos SET texto_pagina_id = $texto_pagina_id WHERE id = $item_id");
				return $texto_pagina_id;
			}
		} elseif ($tipo == 'etiqueta') {
			$etiquetas = $conn->query("SELECT titulo, pagina_id FROM Etiquetas WHERE id = $item_id");
			if ($etiquetas->num_rows == 0) {
				return false;
			} else {
				while ($etiqueta = $etiquetas->fetch_assoc()) {
					$etiqueta_titulo = $etiqueta['titulo'];
					$etiqueta_pagina_id = $etiqueta['pagina_id'];
					if (is_null($etiqueta_pagina_id)) {
						$conn->query("INSERT INTO Paginas (item_id, tipo, subtipo) VALUES ($item_id, 'pagina', 'etiqueta')");
						$etiqueta_pagina_id = $conn->insert_id;
						$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra) VALUES ($etiqueta_pagina_id, 'pagina', 'titulo', '$etiqueta_titulo')");
						$conn->query("UPDATE Etiquetas SET pagina_id = $etiqueta_pagina_id WHERE id = $item_id");
					}
					return $etiqueta_pagina_id;
				}
			}
		} elseif ($tipo == 'escritorio') {
			$usuarios = $conn->query("SELECT pagina_id FROM Usuarios WHERE id = $item_id AND pagina_id IS NOT NULL");
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_pagina_id = $usuario['pagina_id'];
				}
			} else {
				$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'escritorio')");
				$usuario_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Usuarios SET pagina_id = $usuario_pagina_id WHERE id = $item_id");
			}
			return $usuario_pagina_id;
		} elseif ($tipo == 'questao') {
			$questoes = $conn->query("SELECT pagina_id FROM sim_questoes WHERE id = $item_id");
			if ($questoes->num_rows > 0) {
				while ($questao = $questoes->fetch_assoc()) {
					$questao_pagina_id = $questao['pagina_id'];
					if ($questao_pagina_id == false) {
						$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'questao')");
						$questao_pagina_id = $conn->insert_id;
					}
					return $questao_pagina_id;
				}
			}
		} elseif ($tipo == 'texto_apoio') {
			$textos_apoio = $conn->query("SELECT pagina_id FROM sim_textos_apoio WHERE id = $item_id");
			if ($textos_apoio->num_rows > 0) {
				while ($texto_apoio = $textos_apoio->fetch_assoc()) {
					$texto_apoio_pagina_id = $texto_apoio['pagina_id'];
					if ($texto_apoio_pagina_id == false) {
						$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($item_id, 'texto_apoio')");
						$texto_apoio_pagina_id = $conn->insert_id;
					}
					return $texto_apoio_pagina_id;
				}
			}
		}
		return false;
	}
	
	function return_topico_id_pagina_id($pagina_id)
	{
		return false;
	}
	
	function return_curso_id_materia($materia_id)
	{
		return false;
	}
	
	function return_elemento_id_pagina_id($pagina_id)
	{
		include 'templates/criar_conn.php';
		$elementos = $conn->query("SELECT id FROM Elementos WHERE pagina_id = $pagina_id");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				return $elemento_id;
			}
		}
	}
	
	function return_pagina_titulo($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$pagina_titulo = false;
		$buscar_pagina = false;
		$paginas = $conn->query("SELECT tipo, subtipo, item_id FROM Paginas WHERE id = $pagina_id");
		if ($paginas->num_rows > 0) {
			while ($pagina = $paginas->fetch_assoc()) {
				$pagina_tipo = $pagina['tipo'];
				$pagina_item_id = $pagina['item_id'];
				$pagina_subtipo = $pagina['subtipo'];
				if ($pagina_subtipo == 'Plano de estudos') {
					$parent_pagina_id = $pagina_item_id;
					$parent_pagina_titulo = return_pagina_titulo($parent_pagina_id);
					$pagina_titulo = "Plano de estudos: $parent_pagina_titulo";
					break;
				}
				if ($pagina_tipo == 'topico') {
					$buscar_pagina = true;
				} elseif ($pagina_tipo == 'elemento') {
					$pagina_titulo = return_titulo_elemento($pagina_item_id);
				} elseif ($pagina_tipo == 'materia') {
					$buscar_pagina = true;
				} elseif ($pagina_tipo == 'curso') {
					$buscar_pagina = true;
				} elseif ($pagina_tipo == 'grupo') {
					$pagina_titulo = return_grupo_titulo_id($pagina_item_id);
				} elseif ($pagina_tipo == 'texto') {
					$pagina_texto_info = return_texto_info($pagina_item_id);
					$pagina_titulo = $pagina_texto_info[2];
					break;
				} else {
					$buscar_pagina = true;
				}
			}
		}
		if ($buscar_pagina == true) {
			$paginas_elementos = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'titulo' ORDER BY id DESC");
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
	
	function return_pagina_info($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		
		include 'templates/criar_conn.php';
		$paginas = $conn->query("SELECT criacao, item_id, tipo, estado, compartilhamento, user_id, etiqueta_id, subtipo FROM Paginas WHERE id = $pagina_id");
		if ($paginas->num_rows > 0) {
			while ($pagina = $paginas->fetch_assoc()) {
				$pagina_criacao = $pagina['criacao']; // 0
				$pagina_item_id = $pagina['item_id']; // 1
				$pagina_tipo = $pagina['tipo']; // 2
				$pagina_estado = $pagina['estado']; // 3
				if ((($pagina_estado == 1) || ($pagina_estado == 0)) && ($pagina_tipo != 'materia')) {
					$pagina_texto_id = return_texto_id($pagina_tipo, 'verbete', $pagina_id, false);
					if ($pagina_texto_id != false) {
						$pagina_verbete = return_verbete_html($pagina_texto_id);
						if ($pagina_verbete == false) {
							$conn->query("UPDATE Paginas SET estado = 0 WHERE id = $pagina_id");
							$pagina_estado = 0;
						}
					} else {
						$conn->query("UPDATE Paginas SET estado = 0 WHERE id = $pagina_id");
						$pagina_estado = 0;
					}
				}
				$pagina_compartilhamento = $pagina['compartilhamento']; // 4
				$pagina_user_id = $pagina['user_id']; // 5
				$pagina_titulo = return_pagina_titulo($pagina_id); // 6
				$pagina_etiqueta_id = $pagina['etiqueta_id']; // 7
				$pagina_subtipo = $pagina['subtipo']; // 8
				$pagina_publicacao = return_publicacao($pagina_id); // 9
				$pagina_colaboracao = return_colaboracao($pagina_id); // 10
				return array($pagina_criacao, $pagina_item_id, $pagina_tipo, $pagina_estado, $pagina_compartilhamento, $pagina_user_id, $pagina_titulo, $pagina_etiqueta_id, $pagina_subtipo, $pagina_publicacao, $pagina_colaboracao);
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
			$query = "SELECT id FROM Textos WHERE pagina_tipo IS NULL AND texto_pagina_id = $pagina_id AND tipo = '$template_id'";
			$textos = $conn->query($query);
		} else {
			if ($template_id == 'anotacoes') {
				$query_extra = " AND user_id = $user_id";
			} else {
				$query_extra = false;
			}
			$query = "SELECT id FROM Textos WHERE pagina_tipo = '$pagina_tipo' AND pagina_id = $pagina_id AND tipo = '$template_id'$query_extra";
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
	
	function return_privilegio_edicao($item_id, $user_id)
	{
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
				$membros = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
				if ($membros->num_rows > 0) {
					while ($membro = $membros->fetch_assoc()) {
						$membro_grupo_id = $membro['grupo_id'];
						$compartilhamentos = $conn->query("SELECT id FROM Compartilhamentos WHERE tipo = 'colaborador' AND recipiente_id = $membro_grupo_id AND estado = 1");
						if ($compartilhamentos->num_rows > 0) {
							return true;
						}
					}
				}
				$compartilhamentos = $conn->query("SELECT id FROM Compartilhamentos WHERE tipo = 'colaborador' AND recipiente_id = $user_id AND estado = 1");
				if ($compartilhamentos->num_rows > 0) {
					return true;
				} else {
					return false;
				}
			}
		}
	}
	
	function return_compartilhamento($item_id, $user_id)
	{
		if ($item_id == false) {
			return false;
		}
		$check_publicacao = return_publicacao($item_id);
		if (($check_publicacao == 'internet') || ($check_publicacao == 'ubwiki')) {
			return true;
		}
		$item_pagina_info = return_pagina_info($item_id);
		$item_pagina_user_id = $item_pagina_info[5];
		$item_pagina_compartilhamento = $item_pagina_info[4];
		$item_pagina_tipo = $item_pagina_info[2];
		if ($item_pagina_tipo == 'texto') {
			$item_pagina_revisao = check_review_state($item_id);
			if ($item_pagina_revisao == true) {
				$user_info = return_usuario_info($user_id);
				$user_tipo = $user_info[0];
				if (($user_tipo == 'admin') || ($user_tipo == 'revisor')) {
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
		$membros = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
		if ($membros->num_rows > 0) {
			while ($membro = $membros->fetch_assoc()) {
				$membro_grupo_id = $membro['grupo_id'];
				$compartilhamentos = $conn->query("SELECT id FROM Compartilhamento WHERE tipo = 'acesso' AND item_id = $item_id AND recipiente_id = $membro_grupo_id AND estado = 1");
				if ($compartilhamentos->num_rows > 0) {
					return true;
				}
			}
		}
		
		$compartilhamentos = $conn->query("SELECT id FROM Compartilhamento WHERE tipo = 'acesso' AND item_id = $item_id AND recipiente_id = $user_id AND estado = 1 AND compartilhamento = 'usuario'");
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
		$publicacao = $conn->query("SELECT compartilhamento FROM Compartilhamento WHERE item_id = $item_id AND tipo = 'publicacao' AND estado = 1 ORDER BY id DESC");
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
		$colaboracao = $conn->query("SELECT compartilhamento FROM Compartilhamento WHERE tipo = 'colaboracao' AND estado = 1 ORDER BY id DESC");
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
	
	
	function return_escritorio_id($usuario_id)
	{
		include 'templates/criar_conn.php';
		$usuarios = $conn->query("SELECT escritorio_id FROM Usuarios WHERE id = $usuario_id AND escritorio_id IS NOT NULL");
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$usuario_escritorio_id = $usuario['escritorio_id'];
				return $usuario_escritorio_id;
			}
		} else {
			$conn->query("INSERT INTO Paginas (item_id, tipo, subtipo, compartilhamento, user_id) VALUES ($usuario_id, 'pagina', 'escritorio', 'escritorio', $usuario_id)");
			$usuario_escritorio_id = $conn->insert_id;
			$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($usuario_escritorio_id, 'pagina', 'titulo', 'Sala de Visitas', $usuario_id)");
			$conn->query("UPDATE Usuarios SET escritorio_id = $usuario_escritorio_id WHERE id = $usuario_id");
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
		$cursos = $conn->query("SELECT pagina_id, estado, sigla, titulo, user_id, criacao FROM Cursos WHERE id = $curso_id");
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
		$materias = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $curso_pagina_id AND tipo = 'materia'");
		if ($materias->num_rows > 0) {
			while ($materia = $materias->fetch_assoc()) {
				$materia_pagina_id = $materia['elemento_id'];
				array_push($result_materias, $materia_pagina_id);
				array_push($result_all, $materia_pagina_id);
				$topicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $materia_pagina_id AND tipo = 'topico'");
				if ($topicos->num_rows > 0) {
					while ($topico = $topicos->fetch_assoc()) {
						$topico_pagina_id = $topico['elemento_id'];
						array_push($result_topicos, $topico_pagina_id);
						array_push($result_all, $topico_pagina_id);
						$subtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $topico_pagina_id AND tipo = 'subtopico'");
						if ($subtopicos->num_rows > 0) {
							while ($subtopico = $subtopicos->fetch_assoc()) {
								$subtopico_pagina_id = $subtopico['elemento_id'];
								array_push($result_subtopicos, $subtopico_pagina_id);
								array_push($result_all, $subtopico_pagina_id);
								$subsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subtopico_pagina_id AND tipo = 'subtopico'");
								if ($subsubtopicos->num_rows > 0) {
									while ($subsubtopico = $subsubtopicos->fetch_assoc()) {
										$subsubtopico_pagina_id = $subsubtopico['elemento_id'];
										array_push($result_subsubtopicos, $subsubtopico_pagina_id);
										array_push($result_all, $subsubtopico_pagina_id);
										$subsubsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubtopico_pagina_id AND tipo = 'subtopico'");
										if ($subsubsubtopicos->num_rows > 0) {
											while ($subsubsubtopico = $subsubsubtopicos->fetch_assoc()) {
												$subsubsubtopico_pagina_id = $subsubsubtopico['elemento_id'];
												array_push($result_subsubsubtopicos, $subsubsubtopico_pagina_id);
												array_push($result_all, $subsubsubtopico_pagina_id);
												$subsubsubsubtopicos = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $subsubsubtopico_pagina_id AND tipo = 'subtopico'");
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
		$conn->query("DELETE FROM Searchbar WHERE curso_id = $curso_id");
		$curso_paginas = return_curso_paginas($curso_id, 'all');
		$ordem = 0;
		foreach ($curso_paginas as $curso_pagina) {
			$ordem++;
			$curso_pagina_titulo = return_pagina_titulo($curso_pagina);
			if ($curso_pagina_titulo != false) {
				$conn->query("INSERT INTO Searchbar (ordem, curso_id, pagina_id, chave) VALUES ($ordem, $curso_id, $curso_pagina, '$curso_pagina_titulo')");
			}
		}
	}
	
	if (isset($_POST['quill_novo_verbete_html'])) {
		$quill_novo_verbete_html = $_POST['quill_novo_verbete_html'];
		$quill_novo_verbete_html = mysqli_real_escape_string($conn, $quill_novo_verbete_html);
		$quill_novo_verbete_html = strip_tags($quill_novo_verbete_html, '<strong><p><li><ul><ol><h2><h3><blockquote><em><sup><img><u><b><a><s>');
		$quill_novo_verbete_text = $_POST['quill_novo_verbete_text'];
		$quill_novo_verbete_text = mysqli_real_escape_string($conn, $quill_novo_verbete_text);
		$quill_novo_verbete_content = $_POST['quill_novo_verbete_content'];
		$quill_novo_verbete_content = mysqli_real_escape_string($conn, $quill_novo_verbete_content);
		$quill_pagina_id = $_POST['quill_pagina_id'];
		$quill_texto_tipo = $_POST['quill_texto_tipo'];
		$quill_texto_id = $_POST['quill_texto_id'];
		$quill_texto_page_id = $_POST['quill_texto_page_id'];
		$quill_pagina_tipo = $_POST['quill_pagina_tipo'];
		$quill_pagina_subtipo = $_POST['quill_pagina_subtipo'];
		$quill_pagina_estado = $_POST['quill_pagina_estado'];
		$quill_curso_id = $_POST['quill_curso_id'];
		
		$check = $conn->query("UPDATE Textos SET verbete_html = '$quill_novo_verbete_html', verbete_text = '$quill_novo_verbete_text', verbete_content = '$quill_novo_verbete_content' WHERE id = $quill_texto_id");
		$check2 = $conn->query("INSERT INTO Textos_arquivo (texto_id, curso_id, tipo, page_id, pagina_id, pagina_tipo, pagina_subtipo, estado_texto, verbete_html, verbete_text, verbete_content, user_id) VALUES ($quill_texto_id, $quill_curso_id, '$quill_texto_tipo', $quill_texto_page_id, $quill_pagina_id, '$quill_pagina_tipo', '$quill_pagina_subtipo', 1, '$quill_novo_verbete_html', FALSE, '$quill_novo_verbete_content', $user_id)");
		
		if (($quill_pagina_estado == false) && ($quill_novo_verbete_text != false)) {
			$conn->query("UPDATE Paginas SET estado = 1 WHERE id = $quill_pagina_id");
		}
		if (($check == false) || ($check2 == false)) {
			echo false;
		} else {
			echo true;
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
		$textos = $conn->query("SELECT verbete_text FROM Textos WHERE pagina_id = $pagina_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$verbete_texto = $texto['verbete_text'];
				break;
			}
		}
		$produto_preco = false;
		$precos = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'preco' ORDER BY id DESC");
		if ($precos->num_rows > 0) {
			while ($preco = $precos->fetch_assoc()) {
				$produto_preco = $preco['extra'];
				break;
			}
		}
		$produto_autor = false;
		$autores = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'autor' ORDER BY id DESC");
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
		$imagens = $conn->query("SELECT elemento_id FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'imagem' ORDER BY id DESC");
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
		$textos = $conn->query("SELECT verbete_html FROM Textos WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_verbete_html = $texto['verbete_html'];
				return $texto_verbete_html;
			}
		}
		return false;
	}
	
	function return_verbete_text($texto_id) {
		if ($texto_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$textos = $conn->query("SELECT verbete_text FROM Textos WHERE id = $texto_id");
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
		$textos = $conn->query("SELECT verbete_html FROM Textos_arquivo WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_verbete_html = $texto['verbete_html'];
				return $texto_verbete_html;
			}
		}
		return false;
	}
	
	if (isset($_POST['busca_apelido'])) {
		$busca_apelido = $_POST['busca_apelido'];
		$busca_apelido_continuar = false;
		$busca_grupo_id = false;
		$busca_pagina_id = false;
		if (isset($_POST['busca_grupo_id'])) {
			$busca_apelido_continuar = true;
			$busca_grupo_id = $_POST['busca_grupo_id'];
			$convite_tipo = 'convite_usuario';
		} elseif (isset($_POST['busca_pagina_id'])) {
			$busca_apelido_continuar = true;
			$busca_pagina_id = $_POST['busca_pagina_id'];
			$convite_tipo = 'compartilhar_usuario';
		}
		if ($busca_apelido_continuar == true) {
			$usuarios = $conn->query("SELECT apelido, id FROM Usuarios WHERE apelido LIKE '%$busca_apelido%' ORDER BY apelido");
			if ($usuarios->num_rows > 0) {
				while ($usuario = $usuarios->fetch_assoc()) {
					$usuario_apelido = $usuario['apelido'];
					$usuario_id = $usuario['id'];
					$usuario_avatar = return_avatar($usuario_id);
					$usuario_avatar_icone = $usuario_avatar[0];
					$usuario_avatar_cor = $usuario_avatar[1];
					echo "<a value='$usuario_id' class='border p-1 mr-2 mb-2 rounded $convite_tipo'><span class='$usuario_avatar_cor'><i class='fad $usuario_avatar_icone fa-fw fa-2x'></i></span> $usuario_apelido</a></a>";
				}
			} else {
				echo "<span class='text-muted'>{$pagina_translated['Nenhum usuário encontrado.']}</span>";
			}
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['compartilhar_usuario_id'])) {
		$compartilhar_usuario_id = $_POST['compartilhar_usuario_id'];
		$compartilhar_pagina_id = $_POST['compartilhar_pagina_id'];
		$conn->query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('acesso', $user_id, $compartilhar_pagina_id, 'pagina', 'usuario', $compartilhar_usuario_id)");
	}
	
	if (isset($_POST['convidar_usuario_id'])) {
		$convidar_usuario_id = $_POST['convidar_usuario_id'];
		$convidar_grupo_id = $_POST['convidar_grupo_id'];
		$check = $conn->query("INSERT INTO Membros (grupo_id, membro_user_id, user_id) VALUES ($convidar_grupo_id, $convidar_usuario_id, $user_id)");
		if ($check == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['remover_carrinho_pagina_id'])) {
		$remover_carrinho_pagina_id = $_POST['remover_carrinho_pagina_id'];
		$check = $conn->query("UPDATE Carrinho SET estado = 0 WHERE produto_pagina_id = $remover_carrinho_pagina_id AND user_id = $user_id");
		return $check;
	}
	
	if (isset($_POST['remover_compartilhamento_usuario'])) {
		$remover_compartilhamento_usuario = $_POST['remover_compartilhamento_usuario'];
		$remover_compartilhamento_usuario_pagina = $_POST['remover_compartilhamento_usuario_pagina'];
		$check_remocao_compartilhamento = $conn->query("UPDATE Compartilhamento SET estado = 0 WHERE tipo = 'acesso' AND item_id = $remover_compartilhamento_usuario_pagina AND recipiente_id = $remover_compartilhamento_usuario AND compartilhamento = 'usuario'");
		if ($check_remocao_compartilhamento == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['remover_acesso_grupo'])) {
		$remover_acesso_grupo = $_POST['remover_acesso_grupo'];
		$remover_acesso_grupo_pagina_id = $_POST['remover_acesso_grupo_pagina_id'];
		$check_remocao_acesso_grupo = $conn->query("UPDATE Compartilhamento SET estado = 0 WHERE tipo = 'acesso' AND item_id = $remover_acesso_grupo_pagina_id AND recipiente_id = $remover_acesso_grupo AND compartilhamento = 'grupo'");
		if ($check_remocao_compartilhamento == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	function return_forum_topico_titulo($topico_id)
	{
		if ($topico_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$topicos = $conn->query("SELECT comentario_text FROM Forum WHERE id = $topico_id AND tipo = 'topico'");
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
		$cursos = $conn->query("SELECT pagina_id FROM Cursos");
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
		$usuario_cursos = $conn->query("SELECT DISTINCT opcao FROM Opcoes WHERE user_id = $user_id AND opcao_tipo = 'curso' ORDER BY id DESC");
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
	
	if (isset($_POST['adicionar_item_acervo'])) {
		$adicionar_item_acervo = $_POST['adicionar_item_acervo'];
		$user_escritorio_pagina_id = return_pagina_id($user_id, 'escritorio');
		$adicionar_item_pagina_id = return_pagina_id($adicionar_item_acervo, 'elemento');
		$check_acervo = $conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($user_escritorio_pagina_id, 'escritorio', $adicionar_item_acervo, 'referencia', $adicionar_item_pagina_id, $user_id)");
		if ($check_acervo == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['remover_item_acervo'])) {
		$remover_item_acervo = $_POST['remover_item_acervo'];
		$check_acervo = $conn->query("UPDATE Paginas_elementos SET estado = 0 WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND elemento_id = $remover_item_acervo");
		if ($check_acervo == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['adicionar_area_interesse'])) {
		$adicionar_area_interesse = $_POST['adicionar_area_interesse'];
		$user_escritorio_pagina_id = return_pagina_id($user_id, 'escritorio');
		$check_adicionar = $conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($user_escritorio_pagina_id, 'escritorio', 'topico', $adicionar_area_interesse, $user_id)");
		if ($check_adicionar == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['remover_area_interesse'])) {
		$remover_area_interesse = $_POST['remover_area_interesse'];
		$check_remover = $conn->query("UPDATE Paginas_elementos SET estado = 0 WHERE pagina_tipo = 'escritorio' AND user_id = $user_id AND tipo = 'topico' AND extra = $remover_area_interesse");
		if ($check_remover == true) {
			echo true;
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['remover_membro_grupo_id'])) {
		$remover_membro_grupo_id = $_POST['remover_membro_grupo_id'];
		$remover_membro_user_id = $_POST['remover_membro_user_id'];
		$remover_membro_check = $conn->query("UPDATE Membros SET estado = 0 WHERE grupo_id = $remover_membro_grupo_id AND membro_user_id = $remover_membro_user_id");
		if ($remover_membro_check == true) {
			echo true;
		}
	}
	
	if (isset($_POST['carregar_edicao'])) {
		$carregar_edicao = $_POST['carregar_edicao'];
		$etapas = $conn->query("SELECT id, titulo FROM sim_etapas WHERE edicao_id = $carregar_edicao");
		$etapas_resultado = false;
		if ($etapas->num_rows > 0) {
			$etapas_resultado .= "<ul class='list-group list-group-flush'><span data-toggle='modal' data-target='#modal_vazio_edicoes'><span data-toggle='modal' data-target='#modal_vazio_provas'>";
			while ($etapa = $etapas->fetch_assoc()) {
				$etapa_id = $etapa['id'];
				$etapa_titulo = $etapa['titulo'];
				$etapas_resultado .= "<a href='javascript:void(0);' class='mt-1 carregar_etapa' value='$etapa_id'><li class='list-group-item list-group-item-action border-top'>$etapa_titulo</li></a>";
			}
			$etapas_resultado .= "</span></span></ul>";
		}
		echo $etapas_resultado;
	}
	
	if (isset($_POST['nova_etapa_edicao_id'])) {
		$nova_etapa_edicao_id = $_POST['nova_etapa_edicao_id'];
		$nova_etapa_curso_id = $_POST['nova_etapa_curso_id'];
		$nova_etapa_titulo = $_POST['nova_etapa_titulo'];
		$conn->query("INSERT INTO sim_etapas (curso_id, edicao_id, titulo, user_id) VALUES ($nova_etapa_curso_id, $nova_etapa_edicao_id, '$nova_etapa_titulo', $user_id)");
	}
	
	if (isset($_POST['carregar_etapa'])) {
		$carregar_etapa = $_POST['carregar_etapa'];
		$provas = $conn->query("SELECT id, titulo, tipo FROM sim_provas WHERE etapa_id = $carregar_etapa");
		$provas_resultado = false;
		if ($provas->num_rows > 0) {
			$provas_resultado .= "<ul class='list-group list-group-flush'><span data-toggle='modal' data-target='#modal_vazio_provas'><span data-toggle='modal' data-target='#modal_vazio_questoes'>";
			while ($prova = $provas->fetch_assoc()) {
				$prova_id = $prova['id'];
				$prova_titulo = $prova['titulo'];
				$prova_tipo = $prova['tipo'];
				$prova_tipo_string = convert_prova_tipo($prova_tipo);
				$provas_resultado .= "<a href='javascript:void(0);' class='mt-1 carregar_prova' value='$prova_id'><li class='list-group-item list-group-item-action border-top'>$prova_titulo <strong>($prova_tipo_string)</strong></li></a>";
			}
			$provas_resultado .= "</span></span></ul>";
		}
		echo $provas_resultado;
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
	
	if (isset($_POST['carregar_prova'])) {
		$carregar_prova = $_POST['carregar_prova'];
		$list_questoes = $conn->query("SELECT id, pagina_id, origem, numero, tipo FROM sim_questoes WHERE prova_id = $carregar_prova ORDER BY numero");
		$list_questoes_resultado = false;
		if ($list_questoes->num_rows > 0) {
			while ($list_questao = $list_questoes->fetch_assoc()) {
				$list_questao_id = $list_questao['id'];
				$list_questao_origem = $list_questao['origem'];
				if ($list_questao_origem == 1) {
					$list_questao_origem_string = 'oficial';
				} elseif ($list_questao_origem == 0) {
					$list_questao_origem_string = 'não-oficial';
				}
				$list_questao_numero = $list_questao['numero'];
				$list_questao_tipo = $list_questao['tipo'];
				$list_questao_tipo_string = convert_questao_tipo($list_questao_tipo);
				$list_questoes_resultado .= "<a href='javascript:void(0);' class='mt-1 adicionar_questao' value='$list_questao_id'><li class='list-group-item list-group-item-action border-top'>Questão $list_questao_numero ($list_questao_tipo_string, $list_questao_origem_string)</li></a>";
			}
		}
		echo $list_questoes_resultado;
	}
	
	if (isset($_POST['nova_prova_etapa_id'])) {
		$nova_prova_etapa_id = $_POST['nova_prova_etapa_id'];
		$nova_prova_curso_id = $_POST['nova_prova_curso_id'];
		$nova_prova_tipo = $_POST['nova_prova_tipo'];
		$nova_prova_titulo = $_POST['nova_prova_titulo'];
		$conn->query("INSERT INTO sim_provas (curso_id, etapa_id, titulo, tipo) VALUES ($nova_prova_curso_id, $nova_prova_etapa_id, '$nova_prova_titulo', $nova_prova_tipo)");
	}
	
	if (isset($_POST['trigger_nova_questao'])) {
		$nova_questao_curso_id = $_POST['nova_questao_curso_id'];
		$nova_questao_pagina_id = $_POST['nova_questao_pagina_id'];
		$nova_questao_etapa_id = $_POST['nova_questao_etapa_id'];
		$nova_questao_prova_id = $_POST['nova_questao_prova_id'];
		$nova_questao_edicao_id = $_POST['nova_questao_edicao_id'];
		$nova_questao_origem = $_POST['nova_questao_origem'];
		$nova_questao_texto_apoio = $_POST['nova_questao_texto_apoio'];
		$nova_questao_tipo = $_POST['nova_questao_tipo'];
		$nova_questao_numero = $_POST['nova_questao_numero'];
		
		$nova_questao_etapa_info = return_etapa_edicao_ano_e_titulo($nova_questao_etapa_id);
		$nova_questao_edicao_ano = $nova_questao_etapa_info[0];
		
		$nova_questao_pagina_familia = return_familia($nova_questao_pagina_id);
		$nova_questao_materia_id = $nova_questao_pagina_familia[2];
		
		$conn->query("INSERT INTO sim_questoes (origem, curso_id, edicao_ano, etapa_id, texto_apoio, prova_id, numero, materia, tipo, enunciado_html, enunciado_text, enunciado_content, user_id) VALUES ($nova_questao_origem, $nova_questao_curso_id, $nova_questao_edicao_ano, $nova_questao_etapa_id, $nova_questao_texto_apoio, $nova_questao_prova_id, $nova_questao_numero, $nova_questao_materia_id, $nova_questao_tipo, false, false, false, $user_id)");
		$nova_questao_id = $conn->insert_id;
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($nova_questao_pagina_id, 'topico', $nova_questao_id, 'questao', $nova_questao_origem, $user_id)");
	}
	
	function return_questao_info($questao_id)
	{
		if ($questao_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$results = false;
		$questoes = $conn->query("SELECT * FROM sim_questoes WHERE id = $questao_id");
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
				$results = array($questao_origem, $questao_curso_id, $questao_edicao_ano, $questao_etapa_id, $questao_texto_apoio, $questao_texto_apoio_id, $questao_prova_id, $questao_numero, $questao_materia, $questao_tipo, $questao_enunciado_html, $questao_enunciado_text, $questao_enunciado_content, $questao_item1_text, $questao_item2_text, $questao_item3_text, $questao_item4_text, $questao_item5_text, $questao_item1_html, $questao_item2_html, $questao_item3_html, $questao_item4_html, $questao_item5_html, $questao_item1_content, $questao_item2_content, $questao_item3_content, $questao_item4_content, $questao_item5_content, $questao_item1_gabarito, $questao_item2_gabarito, $questao_item3_gabarito, $questao_item4_gabarito, $questao_item5_gabarito, $questao_user_id);
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
		$textos_apoio = $conn->query("SELECT criacao, pagina_id, origem, curso_id, prova_id, titulo, enunciado_html, enunciado_text, enunciado_content, texto_apoio_html, texto_apoio_text, texto_apoio_content, user_id FROM sim_textos_apoio WHERE id = $texto_apoio_id");
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
	
	if (isset($_POST['adicionar_questao_id'])) {
		$adicionar_questao_id = $_POST['adicionar_questao_id'];
		$adicionar_questao_info = return_questao_info($adicionar_questao_id);
		$adicionar_questao_origem = $adicionar_questao_info[0];
		$adicionar_questao_pagina_id = $_POST['adicionar_questao_pagina_id'];
		$adicionar_questao_check = $conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, extra, user_id) VALUES ($adicionar_questao_pagina_id, 'topico', $adicionar_questao_id, 'questao', $adicionar_questao_origem, $user_id)");
		echo $adicionar_questao_check;
	}
	
	function return_notificacao($pagina_id, $user_id)
	{
		if (($pagina_id == false) || ($user_id == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$notificacoes = $conn->query("SELECT tipo FROM Notificacoes WHERE user_id = $user_id AND pagina_id = $pagina_id AND estado = 1");
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
	
	if (isset($_POST['notificacao_pagina_id'])) {
		$notificacao_pagina_id = $_POST['notificacao_pagina_id'];
		$notificacao_ativa = $_POST['notificacao_ativa'];
		$notificacao_email = $_POST['notificacao_email'];
		$notificacao_tipo = false;
		if ($notificacao_email == true) {
			$notificacao_tipo = 'email';
		} elseif ($notificacao_ativa == true) {
			$notificacao_tipo = 'normal';
		}
		$conn->query("UPDATE Notificacoes SET estado = 0 WHERE user_id = $user_id AND pagina_id = $notificacao_pagina_id");
		if ($notificacao_tipo != false) {
			$conn->query("INSERT INTO Notificacoes (user_id, pagina_id, tipo) VALUES ($user_id, $notificacao_pagina_id, '$notificacao_tipo')");
		}
	}
	
	if (isset($_POST['novo_estado_pagina'])) {
		$novo_estado_pagina = $_POST['novo_estado_pagina'];
		$novo_estado_pagina_id = $_POST['novo_estado_pagina_id'];
		$conn->query("UPDATE Paginas SET estado = $novo_estado_pagina WHERE id = $novo_estado_pagina_id");
		$pagina_estado = $novo_estado_pagina;
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
		$recentes = $conn->query("SELECT criacao, user_id FROM Textos_arquivo WHERE pagina_id = $pagina_id ORDER BY id DESC");
		if ($recentes->num_rows > 0) {
			while ($recente = $recentes->fetch_assoc()) {
				$recente_criacao = $recente['criacao'];
				$recente_user_id = $recente['user_id'];
				break;
			}
		}
		$comentarios = $conn->query("SELECT timestamp, user_id FROM Forum WHERE pagina_id = $pagina_id ORDER BY id DESC");
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
	
	function return_icone_subtipo($tipo, $subtipo)
	{
		if (($tipo == false) && ($subtipo == false)) {
			return array(false, false, false);
		}
		switch ($subtipo) {
			case 'livro':
				return array('fa-book', 'text-success', 'rgba-green-strong');
				break;
			case 'pagina':
				return array('fa-browser', 'text-primary', 'rgba-blue-strong');
				break;
			case 'artigo':
				return array('fa-newspaper', 'text-muted', 'rgba-black-strong');
				break;
			case 'wikipedia':
				return array('fa-wikipedia-w', 'text-dark', 'rgba-black-strong');
				break;
			case 'musica':
				return array('fa-record-vinyl', 'text-dark', 'rgba-black-strong');
				break;
			case 'podcast':
				return array('fa-podcast', 'text-secondary', 'rgba-purple-strong');
				break;
			case 'audiobook':
				return array('fa-book-reader', 'text-success', 'rgba-green-strong');
				break;
			case 'mapasatelite':
				return array('fa-globe-americas', 'text-default', 'rgba-teal-strong');
				break;
			case 'retrato':
				return array('fa-portrait', 'text-danger', 'rgba-red-strong');
				break;
			case 'arte':
				return array('fa-paint-brush', 'text-secondary', 'rgba-purple-strong');
				break;
			case 'grafico':
				return array('fa-chart-pie', 'text-warning', 'rgba-orange-strong');
				break;
			case 'paisagem':
				return array('fa-mountain', 'text-info', 'rgba-cyan-strong');
				break;
			case 'objeto':
				return array('fa-cube', 'text-success', 'rgba-green-strong');
				break;
			case 'arquitetura':
				return array('fa-university', 'text-warning', 'rgba-orange-strong');
				break;
			case 'planta':
				return array('fa-flower-daffodil', 'text-danger', 'rgba-red-strong');
				break;
			case 'animais':
				return array('fa-rabbit', 'text-info', 'rgba-cyan-strong');
				break;
			case 'outras':
				return array('fa-camera-alt', 'text-dark', 'rgba-black-strong');
				break;
			case 'youtube':
				return array('fa-youtube', 'text-danger', 'rgba-red-strong');
				break;
			case 'filme':
				return array('fa-film', 'text-info', 'rgba-cyan-strong');
				break;
			case 'aula':
				return array('fa-chalkboard-teacher', 'text-default', 'rgba-teal-strong');
				break;
			case 'equacao':
				return array('fa-greater-than-equal', 'text-info', 'rgba-cyan-strong');
				break;
			case 'etiqueta':
				return array('fa-tag', 'text-warning', 'rgba-orange-strong');
				break;
			default:
				switch ($tipo) {
					case 'imagem':
						if ($subtipo == 'generico') {
							return array('fa-image', 'text-danger', 'rgba-red-strong');
						} else {
							return array('fa-file-image', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'video':
						if ($subtipo == 'generico') {
							return array('fa-play-circle', 'text-info', 'rgba-blue-strong');
						} else {
							return array('fa-file-video', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'album_musica':
						if ($subtipo == 'generico') {
							return array('fa-volume-up', 'text-warning', 'rgba-orange-strong');
						} else {
							return array('fa-file-audio', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'referencia':
						if ($subtipo == 'generico') {
							return array('fa-book-alt', 'text-success', 'rgba-green-strong');
						} else {
							return array('fa-file-alt', 'text-muted', 'rgba-grey-strong');
						}
						break;
					case 'questao':
						return array('fa-ballot-check', 'text-secondary', 'rgba-purple-strong');
						break;
					case 'secao':
						return array('fa-list-ol', 'text-info', 'rgba-cyan-strong');
						break;
					case 'resposta':
						return array('fa-comment-alt-edit', 'text-default', 'rgba-cyan-strong');
						break;
					case 'wikipedia':
						return array('fa-wikipedia-w', 'text-dark', 'rgba-grey-strong');
						break;
					default:
						return array('fa-circle-notch fa-spin', 'text-danger', 'rgba-red-strong');
				}
		}
	}
	
	function return_pagina_icone_cor($anotacao_pagina_tipo, $anotacao_pagina_subtipo)
	{
		switch ($anotacao_pagina_subtipo) {
			case 'etiqueta':
				return array('fa-tag', 'text-warning', 'rgba-orange-strong');
				break;
			default:
				switch ($anotacao_pagina_tipo) {
					case 'elemento':
						return array('fa-columns', 'text-success', 'rgba-green-strong');
						break;
					case 'topico':
						return array('fa-columns', 'text-warning', 'rgba-orange-strong');
						break;
					case 'curso':
						return array('fa-graduation-cap', 'text-default', 'rgba-teal-strong');
						break;
					case 'pagina':
						return array('fa-columns', 'text-info', 'rgba-cyan-strong');
						break;
					case 'texto':
						return array('fa-file-alt', 'text-primary', 'rgba-blue-strong');
						break;
					case false:
						return array('fa-columns', 'text-muted', 'rgba-grey-strong');
						break;
					default:
						return array('fa-circle-notch fa-spin', 'text-muted', 'rgba-grey-strong');
				}
		}
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
	
	if (isset($_POST['delete_this_edit'])) {
		$delete_this_edit = (int)$_POST['delete_this_edit'];
		$delete_edit_check = $conn->query("DELETE FROM Textos_arquivo WHERE id = $delete_this_edit AND user_id = $user_id");
		echo $delete_edit_check;
	}
	
	if (isset($_POST['popular_traducao_chave_pt'])) {
		$popular_traducao_chave_pt = $_POST['popular_traducao_chave_pt'];
		$chaves_pt = $conn->query("SELECT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_pt AND lingua = 'pt'");
		if ($chaves_pt->num_rows > 0) {
			while ($chave_pt = $chaves_pt->fetch_assoc()) {
				$chave_pt_string = $chave_pt['traducao'];
				echo "<span><strong>Português</strong>: <em>$chave_pt_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	if (isset($_POST['popular_traducao_chave_en'])) {
		$popular_traducao_chave_en = $_POST['popular_traducao_chave_en'];
		$chaves_en = $conn->query("SELECT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_en AND lingua = 'en'");
		if ($chaves_en->num_rows > 0) {
			while ($chave_en = $chaves_en->fetch_assoc()) {
				$chave_en_string = $chave_en['traducao'];
				echo "<span><strong>English</strong>: <em>$chave_en_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	if (isset($_POST['popular_traducao_chave_es'])) {
		$popular_traducao_chave_es = $_POST['popular_traducao_chave_es'];
		$chaves_es = $conn->query("SELECT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_es AND lingua = 'es'");
		if ($chaves_es->num_rows > 0) {
			while ($chave_es = $chaves_es->fetch_assoc()) {
				$chave_es_string = $chave_es['traducao'];
				echo "<span><strong>Español</strong>: <em>$chave_es_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	if (isset($_POST['popular_traducao_chave_fr'])) {
		$popular_traducao_chave_fr = $_POST['popular_traducao_chave_fr'];
		$chaves_fr = $conn->query("SELECT traducao FROM Chaves_traduzidas WHERE chave_id = $popular_traducao_chave_fr AND lingua = 'fr'");
		if ($chaves_fr->num_rows > 0) {
			while ($chave_fr = $chaves_fr->fetch_assoc()) {
				$chave_fr_string = $chave_fr['traducao'];
				echo "<span><strong>Français</strong>: <em>$chave_fr_string</em></span>";
			}
		} else {
			echo false;
		}
	}
	
	if (isset($_POST['return_chave_codigo'])) {
		$return_chave_codigo = $_POST['return_chave_codigo'];
		$codigos = $conn->query("SELECT chave FROM Translation_chaves WHERE id = $return_chave_codigo");
		if ($codigos->num_rows > 0) {
			while ($codigo = $codigos->fetch_assoc()) {
				$codigo_chave = $codigo['chave'];
				echo "<span>$codigo_chave</span>";
			}
		} else {
			echo false;
		}
	}
	
	if (isset($_GET['confirmacao'])) {
		$confirmacao = $_GET['confirmacao'];
		$check = $conn->query("UPDATE Usuarios SET origem = 'confirmado' WHERE origem = '$confirmacao'");
	}
	
	function send_nova_senha($email, $confirmacao, $user_language)
	{
		if ($user_language == 'en') {
			$msg = "Your password at Ubwiki has been changed.\nIf you do not have an Ubwiki account, one has been created for your e-mail address.\nTo activate it, please follow this link:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		} elseif ($user_language == 'es') {
			$msg = "Su contraseña en Ubwiki ha sido cambiada.\nSi no tiene una cuenta de Ubwiki, se ha creado una para su dirección de correo electrónico.\nPara activarlo, siga este enlace:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		} else {
			$msg = "Sua senha na Ubwiki foi alterada.\nCaso você não tenha conta na Ubwiki, uma nova conta terá sido criada para seu endereço de email.\nPara ativá-la, siga este link:\nhttps://www.ubwiki.com.br/ubwiki/ubwiki.php?confirmacao=$confirmacao";
		}
		$check = mail($email, 'Nova senha na Ubwiki', $msg, null, '-fwebmaster@ubwiki.com.br');
		return $check;
	}
	
	if (isset($_POST['nova_senha'])) {
		$nova_senha = $_POST['nova_senha'];
		$nova_senha_email = $_POST['nova_senha_email'];
		$nova_senha_encrypted = password_hash($nova_senha, PASSWORD_DEFAULT);
		$confirmacao = generateRandomString(12);
		$check = send_nova_senha($nova_senha_email, $confirmacao, $user_language);
		$usuarios = $conn->query("SELECT id FROM Usuarios WHERE email = '$nova_senha_email'");
		if ($usuarios->num_rows > 0) {
			while ($usuario = $usuarios->fetch_assoc()) {
				$usuario_id = $usuario['id'];
				$conn->query("UPDATE Usuarios SET senha = '$nova_senha_encrypted', origem = '$confirmacao' WHERE id = $usuario_id");
			}
		} else {
			$conn->query("INSERT INTO Usuarios (email, origem, senha) VALUES ('$nova_senha_email', '$confirmacao', '$nova_senha_encrypted')");
		}
	}
	
	if (isset($_POST['list_areas_interesse'])) {
		$user_escritorio_pagina_id = return_pagina_id($user_id, 'escritorio');
		$areas_interesse_result = false;
		$areas_interesse_result .= "<ul class='list-group list-group-flush'>";
		
		$areas_interesse_result .= "<span data-toggle='modal' data-target='#modal_areas_interesse'>";
		
		$areas_interesse_result .= put_together_list_item('modal', '#modal_gerenciar_etiquetas', 'text-info', 'fad', 'fa-plus-circle', $pagina_translated['Gerenciar etiquetas'], 'text-muted', 'fad fa-tags');
		
		$areas_interesse_result .= "</span>";
		
		$areas_interesse = $conn->query("SELECT extra FROM Paginas_elementos WHERE pagina_id = $user_escritorio_pagina_id AND tipo = 'topico' AND estado = 1 ORDER BY id DESC");
		if ($areas_interesse->num_rows > 0) {
			while ($area_interesse = $areas_interesse->fetch_assoc()) {
				$area_interesse_etiqueta_id = $area_interesse['extra'];
				$area_interesse_info = return_etiqueta_info($area_interesse_etiqueta_id);
				$area_interesse_pagina_id = $area_interesse_info[4];
				$areas_interesse_result .= return_list_item($area_interesse_pagina_id);
			}
		}
		$areas_interesse_result .= '</ul>';
		echo $areas_interesse_result;
	}
	
	if (isset($_POST['list_biblioteca_particular'])) {
		$user_escritorio_pagina_id = return_pagina_id($user_id, 'escritorio');
		$list_biblioteca_particular = false;
		$list_biblioteca_particular .= "<ul class='list-group list-group-flush'>";
		$list_biblioteca_particular .= "<div data-toggle='modal' data-target='#modal_biblioteca_particular'>";
		$list_biblioteca_particular .= put_together_list_item('modal', '#modal_add_elementos', 'text-info', 'fad', 'fa-plus-circle', $pagina_translated['press add collection'], 'text-muted', 'fad fa-cog');
		$list_biblioteca_particular .= "</div>";
		$biblioteca_particular = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE pagina_id = $user_escritorio_pagina_id AND estado = 1 AND elemento_id IS NOT NULL ORDER BY id DESC");
		if ($biblioteca_particular->num_rows > 0) {
			while ($item_biblioteca = $biblioteca_particular->fetch_assoc()) {
				$item_biblioteca_elemento_id = $item_biblioteca['elemento_id'];
				$item_biblioteca_pagina_id = return_pagina_id($item_biblioteca_elemento_id, 'elemento');
				$list_biblioteca_particular .= return_list_item($item_biblioteca_pagina_id);
			}
		}
		$list_biblioteca_particular .= '</ul>';
		echo $list_biblioteca_particular;
	}
	
	if (isset($_POST['list_cursos'])) {
		$list_cursos = false;
		$list_cursos .= '<ul class="list-group list-group-flush">';
		$list_cursos .= put_together_list_item('link', 'cursos.php', 'text-success', 'fad', 'fa-portal-enter', $pagina_translated['available courses'], 'text-primary', 'fad fa-external-link');
		$usuario_cursos = return_usuario_cursos_inscrito($user_id);
		foreach ($usuario_cursos as $usuario_curso) {
			$list_cursos .= return_list_item($usuario_curso);
		}
		$list_cursos .= '</ul>';
		echo $list_cursos;
	}
	
	if (isset($_POST['list_referencias'])) {
		$referencias_usuario = $conn->query("SELECT DISTINCT elemento_id FROM Paginas_elementos WHERE user_id = $user_id ORDER BY id DESC");
		$list_referencias = false;
		if ($referencias_usuario->num_rows > 0) {
			$list_referencias .= '<ul class="list-group list-group-flush">';
			while ($referencia_usuario = $referencias_usuario->fetch_assoc()) {
				$referencia_usuario_elemento_id = $referencia_usuario['elemento_id'];
				$referencia_usuario_pagina_id = return_pagina_id($referencia_usuario_elemento_id, 'elemento');
				$list_referencias .= return_list_item($referencia_usuario_pagina_id);
			}
			$list_referencias .= '</ul>';
		}
		echo $list_referencias;
	}
	
	if (isset($_POST['list_grupos_estudo'])) {
		$grupos_estudo_usuario = $conn->query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado = 1");
		$convites_ativos = $conn->query("SELECT DISTINCT grupo_id FROM Membros WHERE membro_user_id = $user_id AND estado IS NULL");
		$list_grupos_estudo = false;
		$list_grupos_estudo .= "<ul class='list-group list-group-flush'>";
		$list_grupos_estudo .= "<span data-toggle='modal' data-target='#modal_grupos_estudo'>";
		if ($convites_ativos->num_rows > 0) {
			$list_grupos_estudo .= put_together_list_item('modal', '#modal_reagir_convite', 'text-warning', 'fad', 'fa-exclamation-triangle', $pagina_translated['Você recebeu convite para participar de grupos de estudos:'], 'text-muted', 'fad fa-users');
		}
		$list_grupos_estudo .= put_together_list_item('modal', '#modal_criar_grupo', 'text-info', 'fad', 'fa-plus-circle', $pagina_translated['Criar grupo de estudos'], 'text-muted', 'fad fa-users');
		$list_grupos_estudo .= "</span>";
		
		if ($grupos_estudo_usuario->num_rows > 0) {
			while ($grupo_estudo_usuario = $grupos_estudo_usuario->fetch_assoc()) {
				$grupo_estudo_id = $grupo_estudo_usuario['grupo_id'];
				$grupo_estudo_pagina_id = return_pagina_id($grupo_estudo_id, 'grupo');
				$list_grupos_estudo .= return_list_item($grupo_estudo_pagina_id);
			}
		} else {
		
		}
		$list_grupos_estudo .= '</ul>';
		echo $list_grupos_estudo;
	}
	
	if (isset($_POST['list_bookmarks'])) {
		$usuario_bookmarks = $conn->query("SELECT pagina_id FROM Bookmarks WHERE user_id = $user_id AND bookmark = 1 AND active = 1 ORDER BY id DESC");
		$list_bookmarks = false;
		$counted_bookmarks = array();
		if ($usuario_bookmarks->num_rows > 0) {
			$list_bookmarks .= "<ul class='list-group list-group-flush'>";
			while ($usuario_bookmark = $usuario_bookmarks->fetch_assoc()) {
				$usuario_bookmark_pagina_id = $usuario_bookmark['pagina_id'];
				if (in_array($usuario_bookmark_pagina_id, $counted_bookmarks)) {
					continue;
				} else {
					array_push($counted_bookmarks, $usuario_bookmark_pagina_id);
				}
				$list_bookmarks .= return_list_item($usuario_bookmark_pagina_id);
			}
			$list_bookmarks .= '</ul>';
		}
		echo $list_bookmarks;
	}
	
	if (isset($_POST['list_comments'])) {
		$usuario_comments = $conn->query("SELECT DISTINCT pagina_id FROM Forum WHERE user_id = $user_id");
		$list_comments = false;
		if ($usuario_comments->num_rows > 0) {
			$list_comments .= "<ul class='list-group list-group-flush'>";
			while ($usuario_comment = $usuario_comments->fetch_assoc()) {
				$usuario_comment_pagina_id = $usuario_comment['pagina_id'];
				$list_comments .= return_list_item($usuario_comment_pagina_id, 'forum');
			}
			$list_comments .= "</ul>";
		}
		echo $list_comments;
	}
	
	if (isset($_POST['list_contribuicoes'])) {
		$usuario_contribuicoes = $conn->query("SELECT DISTINCT pagina_id FROM Textos_arquivo WHERE user_id = $user_id AND tipo = 'verbete' ORDER BY id DESC");
		$list_contribuicoes = false;
		if ($usuario_contribuicoes->num_rows > 0) {
			$list_contribuicoes .= "<ul class='list-group list-group-flush'>";
			//$count_contribuicoes = 0;
			while ($usuario_contribuicao = $usuario_contribuicoes->fetch_assoc()) {
				/*$count_contribuicoes++;
				if ($count_contribuicoes == 21) {
					break;
				}*/
				$usuario_contribuicao_pagina_id = $usuario_contribuicao['pagina_id'];
				$list_contribuicoes .= return_list_item($usuario_contribuicao_pagina_id);
			}
			$list_contribuicoes .= "</ul>";
		}
		echo $list_contribuicoes;
	}
	
	/*
	if (isset($_POST['list_notificacoes'])) {
		$usuario_notificacoes = $conn->query("SELECT DISTINCT pagina_id FROM Notificacoes WHERE user_id = $user_id AND estado = 1 ORDER BY id DESC");
		$list_notificacoes = false;
		if ($usuario_notificacoes->num_rows > 0) {
			$list_notificacoes .= '<ul class="list-group list-group-flush">';
			while ($usuario_notificacao = $usuario_notificacoes->fetch_assoc()) {
				$usuario_notificacao_pagina_id = $usuario_notificacao['pagina_id'];
				$list_notificacoes .= return_list_item($usuario_notificacao_pagina_id);
			}
			$list_notificacoes .= '</ul>';
		}
		echo $list_notificacoes;
	}
	*/
	if (isset($_POST['list_notificacoes'])) {
		$template_modal_body_conteudo = false;
		$notificacoes = $conn->query("SELECT DISTINCT pagina_id FROM Notificacoes WHERE user_id = $user_id AND estado = 1 ORDER BY id DESC");
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
		echo $template_modal_body_conteudo;
	}
	
	if (isset($_POST['list_estudos_recentes'])) {
		$list_estudos_recentes = "<ul class='list-group list-group-flush'>";
		$usuario_estudos_recentes = $conn->query("SELECT page_id, tipo_pagina, extra, extra2 FROM Visualizacoes WHERE user_id = $user_id ORDER BY id DESC");
		if ($usuario_estudos_recentes->num_rows > 0) {
			$count = 0;
			$listados = array();
			while ($usuario_estudo_recente = $usuario_estudos_recentes->fetch_assoc()) {
				if ($count > 20) {
					break;
				}
				$usuario_estudo_recente_page_id = $usuario_estudo_recente['page_id'];
				if ($usuario_estudo_recente_page_id < 12) {
					continue;
				}
				if (in_array($usuario_estudo_recente_page_id, $listados)) {
					continue;
				} else {
					$count++;
					array_push($listados, $usuario_estudo_recente_page_id);
				}
				$usuario_estudo_recente_tipo_pagina = $usuario_estudo_recente['tipo_pagina'];
				$usuario_estudo_recente_extra = $usuario_estudo_recente['extra'];
				$usuario_estudo_recente_extra2 = $usuario_estudo_recente['extra2'];
				$usuario_estudo_recente_pagina_titulo = return_pagina_titulo($usuario_estudo_recente_page_id);
				if ($usuario_estudo_recente_pagina_titulo == false) {
					continue;
				}
				switch ($usuario_estudo_recente_tipo_pagina) {
					case 'elemento':
						$list_item_color = 'list-group-item-success';
						$list_item_icon = 'fa-books';
						break;
					case 'topico':
						$list_item_color = 'list-group-item-info';
						$list_item_icon = 'fa-columns';
						break;
					case 'curso':
						$list_item_color = 'list-group-item-primary';
						$list_item_icon = 'fa-graduation-cap';
						break;
					case 'pagina':
						$pagina_subtipo_info = return_pagina_info($usuario_estudo_recente_page_id);
						$pagina_subtipo = $pagina_subtipo_info[8];
						switch ($pagina_subtipo) {
							case 'etiqueta':
								$list_item_color = 'list-group-item-warning';
								$list_item_icon = 'fa-tag';
								break;
							case 'escritorio':
								$list_item_color = 'list-group-item-danger';
								$list_item_icon = 'fa-user';
								break;
							default:
								$list_item_color = 'list-group-item-secondary';
								$list_item_icon = 'fa-circle-notch';
						}
						break;
					default:
						$list_item_color = false;
						$list_item_icon = 'fa-external-link';
				}
				$list_estudos_recentes .= return_list_item($usuario_estudo_recente_page_id);
				//$list_estudos_recentes .= "<a href='pagina.php?pagina_id=$usuario_estudo_recente_page_id' class='mt-1'><li class='list-group-item list-group-item-action $list_item_color'><i class='fad $list_item_icon fa-fw'></i> $usuario_estudo_recente_pagina_titulo</li></a>";
			}
		}
		$list_estudos_recentes .= "</ul>";
		echo $list_estudos_recentes;
	}
	
	function return_list_item()
	{
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
		if ($pagina_id == false) {
			return false;
		} else {
			$pagina_info = return_pagina_info($pagina_id);
			if ($pagina_info == false) {
				return false;
			}
			$pagina_tipo = $pagina_info[2];
			$pagina_subtipo = $pagina_info[8];
			$pagina_estado = $pagina_info[3];
			$pagina_titulo = $pagina_info[6];
			$pagina_item_id = $pagina_info[1];
			$pagina_estado_icone = return_estado_icone($pagina_estado, false);
		}
		$pagina_icone = return_pagina_icone($pagina_tipo, $pagina_subtipo, $pagina_item_id);
		$icone_principal = $pagina_icone[0];
		$cor_icone_principal = $pagina_icone[1];
		if ($pagina_tipo == 'texto') {
			$lista_tipo = 'texto';
		}
		if ($lista_tipo == 'texto') {
			
			$pagina_estado_cor = $cor_icone_principal;
			$fa_prefixo = 'fad';
			if ($icone_principal == 'fa-youtube') {
				$fa_prefixo = 'fab';
			}
			$pagina_estado_icone = "$fa_prefixo $icone_principal";
			$icone_principal = 'fad fa-file-alt fa-swap-opacity fa-fw';
			
			switch ($pagina_tipo) {
				case 'elemento':
					$cor_icone_principal = 'text-success';
					break;
				case 'topico':
					$cor_icone_principal = 'text-warning';
					break;
				case 'pagina':
					$cor_icone_principal = 'text-info';
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
		} else {
			$pagina_estado_cor = 'text-muted';
		}
		if ($lista_tipo == 'forum') {
			$link = "forum.php?pagina_id=$pagina_id";
		} elseif ($lista_tipo == 'inactive') {
			$link = false;
		} else {
			$link = "pagina.php?pagina_id=$pagina_id";
		}
		if (($icone_principal == 'fa-youtube') || ($icone_principal == 'fa-wikipedia-w')) {
			$icone_prefixo = 'fab';
		} else {
			$icone_prefixo = 'fad';
		}
		return put_together_list_item('link', $link, $cor_icone_principal, $icone_prefixo, $icone_principal, $pagina_titulo, $pagina_estado_cor, $pagina_estado_icone, $item_classes);
	}
	
	function put_together_list_item()
	{
		$args = func_get_args();
		$type = $args[0];
		$link = $args[1];
		if ($link == false) {
			$type = 'inactive';
		}
		$cor_icone_principal = $args[2];
		$icone_prefixo = $args[3];
		$icone_principal = $args[4];
		$pagina_titulo = $args[5];
		$pagina_estado_cor = $args[6];
		$pagina_estado_icone = $args[7];
		if (isset($args[8])) {
			$item_classes = $args[8];
		} else {
			$item_classes = false;
		}
		if ($type == 'link') {
			return "
			<a href='$link'>
				<li class='list-group-item list-group-item-action $item_classes border-top p-1 py-2 d-flex justify-content-between'>
					<span>
						<span class='$cor_icone_principal align-middle icone-lista'>
							<i class='$icone_prefixo $icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-middle ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			</a>";
		} elseif ($type == 'modal') {
			return "
			<a data-toggle='modal' data-target='$link'>
				<li class='list-group-item list-group-item-action $item_classes border-top p-1 py-2 d-flex justify-content-between'>
					<span>
						<span class='$cor_icone_principal align-middle icone-lista'>
							<i class='$icone_prefixo $icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-middle ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			</a>";
		} elseif ($type == 'inactive') {
			return "
				<li class='list-group-item $item_classes border-top p-1 py-2 d-flex justify-content-between'>
					<span>
						<span class='$cor_icone_principal align-middle icone-lista'>
							<i class='$icone_prefixo $icone_principal fa-fw fa-lg'></i>
						</span>
						<span class='lista-texto'>
							$pagina_titulo
						</span>
					</span>
					<span class='align-middle ml-2 icone-estado $pagina_estado_cor'>
						<i class='$pagina_estado_icone fa-fw fa-sm'></i>
					</span>
				</li>
			";
		}
	}
	
	function return_pagina_icone($pagina_tipo, $pagina_subtipo, $pagina_item_id)
	{
		if ($pagina_tipo == false) {
			return false;
		}
		switch ($pagina_tipo) {
			case 'pagina':
				switch ($pagina_subtipo) {
					case 'escritorio':
						return array('fa-lamp-desk', 'text-info');
						break;
					case 'etiqueta':
						return array('fa-tag', 'text-warning');
						break;
					default:
						return array('fa-columns', 'text-info');
						break;
				}
			case 'secao':
				return array('fa-bookmark', 'text-danger');
				break;
			case 'curso':
				return array('fa-graduation-cap', 'text-default');
				break;
			case 'elemento':
				$elemento_info = return_elemento_info($pagina_item_id);
				$elemento_tipo = $elemento_info[3];
				$elemento_subtipo = $elemento_info[18];
				$elemento_subtipo_icone = return_icone_subtipo($elemento_tipo, $elemento_subtipo);
				if ($elemento_subtipo_icone != false) {
					return array($elemento_subtipo_icone[0], $elemento_subtipo_icone[1]);
				} else {
					return array('fa-circle-notch', 'text-success');
				}
				break;
			case 'topico':
				return array('fa-columns', 'text-warning');
				break;
			case 'sistema':
				return array('fa-exclamation-triangle', 'text-warning');
				break;
			case 'texto':
				return array('fa-file-alt fa-swap-opacity', 'text-primary');
				break;
			case 'grupo':
				return array('fa-users', 'text-default');
				break;
			case 'materia':
				return array('fa-th-list', 'text-default');
				break;
			case 'resposta':
				return array('fa-reply', 'text-default');
				break;
			default:
				return array('fa-circle-notch', 'text-light');
				break;
		}
	}
	
	if (isset($_POST['list_user_texts'])) {
		$list_user_texts = false;
		$query = "SELECT id, pagina_id FROM Textos WHERE tipo = 'anotacoes' AND user_id = $user_id AND verbete_text != '0' AND verbete_text != '' AND verbete_text != '\\n' ORDER BY id DESC";
		$user_textos = $conn->query($query);
		if ($user_textos->num_rows > 0) {
			while ($user_texto = $user_textos->fetch_assoc()) {
				$user_texto_id = $user_texto['id'];
				$user_texto_pagina_id = $user_texto['pagina_id'];
				if ($user_texto_pagina_id == false) {
					$user_texto_pagina_id = return_pagina_id($user_texto_id, 'texto');
				}
				$list_user_texts .= return_list_item($user_texto_pagina_id, 'texto');
			}
		}
		echo $list_user_texts;
	}
	
	if (isset($_POST['list_user_pages'])) {
		$user_pages = $conn->query("SELECT id FROM Paginas WHERE tipo = 'pagina' AND user_id = $user_id ORDER BY id DESC");
		$list_user_pages = false;
		if ($user_pages->num_rows > 0) {
			while ($user_page = $user_pages->fetch_assoc()) {
				$user_page_id = $user_page['id'];
				$user_page_titulo = return_pagina_titulo($user_page_id);
				$list_user_pages .= return_list_item($user_page_id);
				
				//$list_user_pages .= "<a href='pagina.php?pagina_id=$user_page_id'><li class='list-group-item list-group-item-action'><span class='text-info mr-2 align-middle'><i class='fad fa-columns fa-2x fa-fw'></i></span> $user_page_titulo</li></a>";
			}
		}
		echo $list_user_pages;
	}
	
	function return_link_compartilhamento($pagina_id)
	{
		if ($pagina_id == false) {
			return array(false, false);
		}
		include 'templates/criar_conn.php';
		$dados = $conn->query("SELECT subtipo, extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'linkshare' AND estado = 1");
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
	
	function adicionar_chave_traducao($nova_chave_titulo, $user_id)
	{
		if (($nova_chave_titulo == false) || ($user_id == false)) {
			return false;
		}
		include 'templates/criar_conn.php';
		$nova_chave_titulo = mysqli_real_escape_string($conn, $nova_chave_titulo);
		$chaves = $conn->query("SELECT id FROM Translation_chaves WHERE chave = '$nova_chave_titulo'");
		if ($chaves->num_rows == 0) {
			$conn->query("INSERT INTO Translation_chaves (user_id, chave) VALUES ($user_id, '$nova_chave_titulo')");
		}
	}
	
	if (isset($_POST['listar_usuarios_emails'])) {
		$usuarios_emails_results = false;
		if ($user_tipo == 'admin') {
			$usuarios_emails = $conn->query("SELECT email FROM Usuarios");
			if ($usuarios_emails->num_rows > 0) {
				while ($usuario_email = $usuarios_emails->fetch_assoc()) {
					$usuario_email_one = $usuario_email['email'];
					$usuarios_emails_results .= "<li class='list-group-item'>$usuario_email_one</li>";
				}
			}
		}
		echo $usuarios_emails_results;
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
		$curso_texto_id = return_texto_id('curso', 'verbete', $curso_pagina_id, false);
		$curso_verbete = return_verbete_html($curso_texto_id);
		$curso_verbete = crop_text($curso_verbete, 400);
		
		$template_id = "curso_$curso_pagina_id";
		$template_titulo = "<a href='pagina.php?pagina_id=$curso_pagina_id'>$curso_titulo</a>";
		if ($card_mode == 'inscrito') {
			$template_botoes = "<span class='text-success'><i class='fad fa-check-circle fa-fw'></i></span>";
		} elseif ($card_mode == 'disponivel') {
			$template_botoes = "<span class='text-info'><i class='fad fa-circle fa-fw'></i></span>";
		}
		$template_conteudo = false;
		if ($curso_verbete != false) {
			$template_conteudo .= $curso_verbete;
		}
		
		$curso_card = include 'templates/page_element.php';
		
		return $curso_card;
		
	}
	
	if (isset($_POST['finalizar_correcao'])) {
		$finalizar_correcao_pagina_id = $_POST['finalizar_correcao'];
		$conn->query("UPDATE Orders SET estado = 0, corretor_user_id = $user_id, data_finalizado = NOW() WHERE tipo = 'review' AND pagina_id = $finalizar_correcao_pagina_id AND estado = 1");
	}

	function return_usuario_info($usuario_id) {
		if ($usuario_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$usuarios = $conn->query("SELECT * FROM Usuarios WHERE id = $usuario_id");
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
				$result = array($usuario_tipo, $usuario_criacao, $usuario_origem, $usuario_language, $usuario_email,
					$usuario_apelido, $usuario_pagina_id, $usuario_escritorio_id, $usuario_nome, $usuario_sobrenome,
					$usuario_concursos, $usuario_special);
				return $result;
			}
		}
		return false;
	}
	
?>
