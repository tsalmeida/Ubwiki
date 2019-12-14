<?php

	if (!isset($loginpage)) {
		$loginpage = false;
	}

	if (!isset($_SESSION['email'])) {
		$sessionpath = getcwd();
		$sessionpath .= '/../sessions';
		session_save_path($sessionpath);
		session_start();
	}

	include 'templates/criar_conn.php';

	$user_email = false;
	$newuser = false;
	$special = false;

	if (isset($_GET['special'])) {
		$special = $_GET['special'];
	}
	if ($special == 14836) {
		$_SESSION['email'] = 'tsilvaalmeida@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 19815848) {
		$_SESSION['email'] = 'cavalcanti@me.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 17091979) {
		$_SESSION['email'] = 'marciliofcf@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 2951720) {
		$_SESSION['email'] = 'mariaelianebsb@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 15030) {
		$_SESSION['email'] = 'isabellecorrea@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 'joaodaniel') {
		$_SESSION['email'] = 'joaodaniel@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	} elseif ($special == 'maladiplomatica') {
		$_SESSION['email'] = 'maladiplomatica@gmail.com';
		$_POST['bora'] = 123456;
		$special = true;
	}
	if (!isset($_SESSION['email'])) {
		if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['bora'] = $_POST['bora'];
			$user_email = $_SESSION['email'];
			$bora = $_SESSION['bora'];
			$result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
			if ($result->num_rows == 0) {
				$newuser = true;
				$insert = $conn->query("INSERT INTO Usuarios (tipo, email) VALUES ('estudante', '$user_email')");
			}
		} else {
			if ($loginpage == false) {
				header('Location:login.php');
			}
		}
	} else {
		$user_email = $_SESSION['email'];
	}
	if ($user_email != false) {
		$result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
//        header("Location:index.php");
			}
		}
	} else {
		if ($special == true) {
//      header('Location:index.php');
		}
	}

	$result = $conn->query("SELECT id, apelido, nome FROM Usuarios WHERE email = '$user_email'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$user_id = $row['id'];
			$user_apelido = $row['apelido'];
			$user_nome = $row['nome'];
		}
	}

	if (!isset($_SESSION['concurso_id'])) {
		$_SESSION['concurso_id'] = false;
		header('Location:cursos.php');
	} else {
		$concurso_id = $_SESSION['concurso_id'];
	}
	if (isset($_GET['curso_id'])) {
		$concurso_id = $_GET['curso_id'];
		$_SESSION['concurso_id'] = $concurso_id;
	}
	if ($concurso_id != false) {
		$concurso_sigla = return_concurso_sigla($concurso_id);
	}

	$all_buttons_classes = "btn rounded btn-md mt-4 text-center";
	$button_classes = "$all_buttons_classes btn-primary";
	$button_classes_light = "$all_buttons_classes btn-light";
	$button_classes_info = "$all_buttons_classes btn-info";
	$button_classes_red = "$all_buttons_classes btn-danger";
	$select_classes = "browser-default custom-select mt-2";
	$coluna_classes = "col-lg-5 col-md-10 col-sm-11";
	$coluna_maior_classes = "col-lg-9 col-md-10 col-sm-11";
	$coluna_media_classes = "col-lg-7 col-md-10 col-sm-11";
	$coluna_pouco_maior_classes = "col-lg-6 col-md-10 col-sm-11";

	$tag_ativa_classes = 'text-dark m-1 p-2 lighten-4 rounded remover_tag';
	$tag_inativa_classes = 'text-dark m-1 p-2 lighten-4 rounded adicionar_tag';
	$tag_neutra_classes = 'text-dark m-1 p-2 lighten-4 rounded';

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
			echo "<h1 class='h1-responsive playfair400 logo-jumbotron d-sm-inline d-md-none'>$titulo</h1>";
			echo "<span class='display-2 playfair400 logo-jumbotron d-none d-md-inline'>$titulo</span>";
		} else {
			echo "<a href='$link'><h1 class='h1-responsive playfair400 logo-jumbotron d-sm-inline d-md-none'>$titulo</h1></a>";
			echo "<a href='$link'><span class='display-2 playfair400 logo-jumbotron d-none d-md-inline'>$titulo</span></a>";
		}
		echo "
      </div>
    </div>
		";
	}

	if (isset($_POST['bookmark_change'])) {
		$bookmark_change = $_POST['bookmark_change'];
		$bookmark_page_id = $_POST['bookmark_page_id'];
		$bookmark_user_id = $_POST['bookmark_user_id'];
		$bookmark_contexto = $_POST['bookmark_contexto'];
		if ($bookmark_contexto == 'verbete') {
			$coluna_relevante = 'topico_id';
		} elseif ($bookmark_contexto == 'elemento') {
			$coluna_relevante = 'elemento_id';
		}
		include 'templates/criar_conn.php';
		$result = $conn->query("SELECT id FROM Bookmarks WHERE user_id = $bookmark_user_id AND $coluna_relevante = $bookmark_page_id AND active = 1");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$bookmark_id = $row['id'];
				$conn->query("UPDATE Bookmarks SET active = 0 WHERE id = $bookmark_id");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($bookmark_user_id, $bookmark_page_id, 'bookmark', $bookmark_change)");
		$conn->query("INSERT INTO Bookmarks (user_id, $coluna_relevante, bookmark, active) VALUES ($bookmark_user_id, $bookmark_page_id, $bookmark_change, 1)");
	}

	if (isset($_POST['completed_change'])) {
		$completed_change = $_POST['completed_change'];
		$completed_page_id = $_POST['completed_page_id'];
		$completed_user_id = $_POST['completed_user_id'];
		include 'templates/criar_conn.php';
		$result = $conn->query("SELECT id FROM Completed WHERE user_id = $completed_user_id AND topico_id = $completed_page_id AND active = 1");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$completed_id = $row['id'];
				$conn->query("UPDATE Completed SET active = 0 WHERE id = $completed_id");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($completed_user_id, $completed_page_id, 'completed', $completed_change)");
		$conn->query("INSERT INTO Completed (user_id, topico_id, estado, active) VALUES ($completed_user_id, $completed_page_id, $completed_change, 1)");
	}

	$opcao_texto_justificado_value = false;
	$opcoes = $conn->query("SELECT opcao FROM Opcoes WHERE opcao_tipo = 'texto_justificado' AND user_id = $user_id ORDER BY id DESC");
	if ($opcoes->num_rows > 0) {
		while ($opcao = $opcoes->fetch_assoc()) {
			$opcao_texto_justificado_value = $opcao['opcao'];
			break;
		}
	}

	if (isset($_POST['sbcommand'])) {
		$concurso_id = base64_decode($_POST['sbconcurso']);
		$command = base64_decode($_POST['sbcommand']);
		$command = utf8_encode($command);
		$found = false;
		$result = $conn->query("SELECT page_id, tipo FROM Searchbar WHERE concurso_id = $concurso_id AND chave = '$command' ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$page_id = $row["page_id"];
				$tipo = $row["tipo"];
				if ($tipo == "materia") {
					echo "foundfoundfoundfmateria.php?materia_id=$page_id";
					return;
				} elseif ($tipo == "topico") {
					echo "foundfoundfoundfverbete.php?topico_id=$page_id";
					return;
				}
			}
		}
		$index = 500;
		$winner = 0;
		$result = $conn->query("SELECT chave FROM Searchbar WHERE concurso_id = '$concurso_id' AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row["chave"];
				$chavelow = mb_strtolower($chave);
				$commandlow = mb_strtolower($command);
				$check = levenshtein($chavelow, $commandlow, 1, 1, 1);
				if (strpos($chavelow, $commandlow) !== false) {
					echo "notfoundnotfound$chave";
					return;
				} elseif ($check < $index) {
					$index = $check;
					$winner = $chave;
				}
			}
			$length = strlen($command);
			if ($index < $length) {
				echo "notfoundnotfound$winner";
				return;
			}
		}
		echo "Nada foi encontrado";
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
		$length = func_get_args();
		if (!isset($length[0])) {
			$length[0] = 8;
		}
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length[0]);
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
		$nova_imagem_link = $_POST['nova_imagem'];
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		if ($nova_imagem_titulo == false) {
			$nova_imagem_titulo = 'Não há título registrado';
		}
		$user_id = $_POST['user_id'];
		$page_id = $_POST['page_id'];
		$contexto = $_POST['contexto'];
		$nossa_copia = adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $page_id, $user_id, $contexto, $concurso_id);
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
		$page_id = $args[2];
		$user_id = $args[3];
		$contexto = $args[4];
		$origem = $args[5];
		$concurso_id = $args[6];
		if ($contexto != 'privada') {
			$nova_imagem_tipo = 'imagem';
		} else {
			$nova_imagem_tipo = 'imagem_privada';
		}
		include 'templates/criar_conn.php';
		$result = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
		if (($result->num_rows == 0) || ($nova_imagem_tipo == 'imagem_privada')) {
			$randomfilename = generateRandomString(16);
			$ultimo_ponto = strripos($nova_imagem_link, ".");
			$extensao = substr($nova_imagem_link, $ultimo_ponto);
			$nova_imagem_arquivo = "$randomfilename$extensao";
			$nova_imagem_diretorio = "../imagens/verbetes/$randomfilename$extensao";
			file_put_contents($nova_imagem_diretorio, fopen($nova_imagem_link, 'r'));
			$dados_da_imagem = make_thumb($nova_imagem_arquivo);
			if ($dados_da_imagem == false) {
				return false;
			}
			$nova_imagem_resolucao_original = $dados_da_imagem[0];
			$nova_imagem_orientacao = $dados_da_imagem[1];
			if ($origem == 'upload') {
				$nova_imagem_link = "https://ubwiki.com.br/imagens/verbetes/$nova_imagem_arquivo";
			}
			if ($nova_imagem_tipo == 'imagem_privada') {
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('$nova_imagem_tipo', '$nova_imagem_titulo', $user_id)");
				$nova_imagem_etiqueta_id = $conn->insert_id;
				$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ($nova_imagem_etiqueta_id, '$nova_imagem_tipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
			}
			if ($page_id != 0) {
				//em algum momento antes deste, está checando para ver se é duplicada. Onde? Está tudo certo com isso?
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('$nova_imagem_tipo', '$nova_imagem_titulo', $user_id)");
				$nova_imagem_etiqueta_id = $conn->insert_id;
				$conn->query("INSERT INTO Elementos (etiqueta_id, tipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ($nova_imagem_etiqueta_id, '$nova_imagem_tipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
				$result2 = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
				if ($result2->num_rows > 0) {
					while ($row = $result2->fetch_assoc()) {
						$nova_imagem_id = $row['id'];
						$result3 = $conn->query("SELECT id FROM Verbetes_elementos WHERE elemento_id = $nova_imagem_id");
						if ($result3->num_rows == 0) {
							$conn->query("INSERT INTO Verbetes_elementos (page_id, concurso_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($page_id, $concurso_id, '$contexto', $nova_imagem_id, '$nova_imagem_tipo', $user_id)");
						}
						break;
					}
				}
			} else {
				while ($row = $result->fetch_assoc()) {
					$nova_imagem_id = $row['id'];
					$conn->query("INSERT INTO Verbetes_elementos (page_id, tipo_pagina, concurso_id, elemento_id, tipo, user_id) VALUES ($page_id, '$contexto', $concurso_id, $nova_imagem_id, '$nova_imagem_tipo', $user_id)");
					break;
				}
			}
		}
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
		include 'templates/criar_conn.php';
		$result_find_topico = $conn->query("SELECT nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE id = $topico_id");
		if ($result_find_topico->num_rows > 0) {
			while ($row_find = $result_find_topico->fetch_assoc()) {
				$found_topico_nivel = $row_find['nivel'];
				if ($found_topico_nivel == 1) {
					$found_topico_titulo = $row_find['nivel1'];
				} elseif ($found_topico_nivel == 2) {
					$found_topico_titulo = $row_find['nivel2'];
				} elseif ($found_topico_nivel == 3) {
					$found_topico_titulo = $row_find['nivel3'];
				} elseif ($found_topico_nivel == 4) {
					$found_topico_titulo = $row_find['nivel4'];
				} else {
					$found_topico_titulo = $row_find['nivel5'];
				}
			}
			return $found_topico_titulo;
		}
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

	function return_concurso_id_topico($topico_id)
	{
		include 'templates/criar_conn.php';
		$result_find_concurso = $conn->query("SELECT concurso_id FROM Topicos WHERE id = $topico_id");
		if ($result_find_concurso->num_rows > 0) {
			while ($row_find_concurso = $result_find_concurso->fetch_assoc()) {
				$found_concurso_id = $row_find_concurso['concurso_id'];
			}
			return $found_concurso_id;
		}
		return false;
	}

	function return_materia_id_topico($topico_id)
	{
		include 'templates/criar_conn.php';
		$materias = $conn->query("SELECT materia_id FROM Topicos WHERE id = $topico_id");
		if ($materias->num_rows > 0) {
			while ($materia = $materias->fetch_assoc()) {
				$found_materia_id = $materia['materia_id'];
			}
			return $found_materia_id;
		}
		return false;
	}

	function return_concurso_sigla($concurso_id)
	{
		include 'templates/criar_conn.php';
		$result_find_concurso_sigla = $conn->query("SELECT sigla FROM Concursos WHERE id = $concurso_id");
		if ($result_find_concurso_sigla->num_rows > 0) {
			while ($row_find_concurso_sigla = $result_find_concurso_sigla->fetch_assoc()) {
				$found_concurso_sigla = $row_find_concurso_sigla['sigla'];
			}
			return $found_concurso_sigla;
		}
		return false;
	}

	function return_concurso_titulo_id($find_concurso_id)
	{
		include 'templates/criar_conn.php';
		$find_concursos = $conn->query("SELECT titulo FROM Concursos WHERE id = $find_concurso_id");
		if ($find_concursos->num_rows > 0) {
			while ($find_concurso = $find_concursos->fetch_assoc()) {
				$find_concurso_titulo = $find_concurso['titulo'];
			}
			return $find_concurso_titulo;
		}
		return false;
	}

	function return_simulado_info($find_simulado_id)
	{
		include 'templates/criar_conn.php';
		$find_simulados = $conn->query("SELECT criacao, tipo, concurso_id FROM sim_gerados WHERE id = $find_simulado_id");
		if ($find_simulados->num_rows > 0) {
			while ($find_simulado = $find_simulados->fetch_assoc()) {
				$find_simulado_criacao = $find_simulado['criacao'];
				$find_simulado_tipo = $find_simulado['tipo'];
				$find_simulado_concurso_id = $find_simulado['concurso_id'];
				$find_simulado_result = array($find_simulado_criacao, $find_simulado_tipo, $find_simulado_concurso_id);
				return $find_simulado_result;
			}
		}
		return false;
	}

	function return_concurso_id_materia($materia_id)
	{
		include 'templates/criar_conn.php';
		$result_find_concurso_id = $conn->query("SELECT concurso_id FROM Materias WHERE id = $materia_id");
		if ($result_find_concurso_id->num_rows > 0) {
			while ($row_find_concurso_id = $result_find_concurso_id->fetch_assoc()) {
				$found_concurso_id = $row_find_concurso_id['concurso_id'];
			}
			return $found_concurso_id;
		}
		return false;
	}

	function return_apelido_user_id($find_user_id)
	{
		include 'templates/criar_conn.php';
		$result_find_apelido = $conn->query("SELECT apelido FROM Usuarios WHERE id = $find_user_id");
		if ($result_find_apelido->num_rows > 0) {
			while ($row_find_apelido = $result_find_apelido->fetch_assoc()) {
				$found_apelido = $row_find_apelido['apelido'];
			}
			return $found_apelido;
		}
		return false;
	}

	function return_etapa_titulo_id($etapa_id)
	{
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
				$prova_etapa_id = $prova['etapa_id'];
				$prova_titulo = $prova['titulo'];
				$prova_tipo = $prova['tipo'];
				$edicao_ano_e_titulo = return_etapa_edicao_ano_e_titulo($prova_etapa_id);
				$edicao_ano = $edicao_ano_e_titulo[0];
				$edicao_titulo = $edicao_ano_e_titulo[1];
				$result = array($prova_titulo, $prova_tipo, $edicao_ano, $edicao_titulo, $prova_etapa_id);
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
		include 'templates/criar_conn.php';
		$materias = $conn->query("SELECT titulo FROM Materias WHERE id = $materia_id");
		if ($materias->num_rows > 0) {
			while ($materia = $materias->fetch_assoc()) {
				$materia_titulo = $materia['titulo'];
				return $materia_titulo;
			}
		}
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
		$icone0 = 'fal fa-empty-set fa-fw';
		$icone1 = 'fal fa-acorn fa-fw';
		$icone2 = 'fal fa-seedling fa-fw';
		$icone3 = 'fas fa-leaf fa-fw';
		$icone4 = 'fas fa-leaf fa-fw';

		if ($contexto == 'verbete') {
			$icone0 = 'fas fa-empty-set fa-fw';
			$icone1 = 'fas fa-acorn fa-fw';
			$icone2 = 'fas fa-seedling fa-fw';
		}

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
		if ($gabarito == 0) {
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
		$concurso_id = $_POST['concurso_id'];
		$questao_id = $_POST['questao_id'];
		$questao_numero = $_POST['questao_numero'];

		if ($questao_tipo == 1) {
			$item1_resposta = $_POST['item1'];
			$item2_resposta = $_POST['item2'];
			$item3_resposta = $_POST['item3'];
			$item4_resposta = $_POST['item4'];
			$item5_resposta = $_POST['item5'];
			$conn->query("INSERT INTO sim_respostas (user_id, concurso_id, simulado_id, questao_id, questao_tipo, questao_numero, item1, item2, item3, item4, item5) VALUES ($user_id, $concurso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $item1_resposta, $item2_resposta, $item3_resposta, $item4_resposta, $item5_resposta)");
			echo true;
		} elseif ($questao_tipo == 2) {
			$resposta = $_POST['resposta'];
			$conn->query("INSERT INTO sim_respostas (user_id, concurso_id, simulado_id, questao_id, questao_tipo, questao_numero, multipla) VALUES ($user_id, $concurso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, $resposta)");
			echo true;
		} elseif ($questao_tipo == 3) {
			$redacao_html = $_POST['redacao_html'];
			$redacao_text = $_POST['redacao_text'];
			$redacao_content = $_POST['redacao_content'];
			$redacao_html = mysqli_real_escape_string($conn, $redacao_html);
			$redacao_text = mysqli_real_escape_string($conn, $redacao_text);
			$redacao_content = mysqli_real_escape_string($conn, $redacao_content);
			$conn->query("INSERT INTO sim_respostas (user_id, concurso_id, simulado_id, questao_id, questao_tipo, questao_numero, redacao_html, redacao_text, redacao_content) VALUES ($user_id, $concurso_id, $simulado_id, $questao_id, $questao_tipo, $questao_numero, '$redacao_html', '$redacao_text', '$redacao_content')");
			echo true;
		}
		echo false;
	}

	if (isset($_POST['busca_referencias'])) {
		$busca_referencias = $_POST['busca_referencias'];
		$busca_referencias = mysqli_real_escape_string($conn, $busca_referencias);
		$busca_resultados = false;
		$referencia_exata = $conn->query("SELECT titulo FROM Elementos WHERE titulo = '$busca_referencias' AND (tipo = 'referencia' OR tipo = 'video' OR tipo = 'album_musica')");
		if ($referencia_exata->num_rows == 0) {
			$busca_resultados .= "<div class='col-12'><button type='button' id='criar_referencia' name='criar_referencia' class='btn rounded btn-md text-center btn-primary btn-sm m-0 mb-2' value='$busca_referencias'>Referência não encontrada, criar nova?</button></div>";
		}
		$elementos = $conn->query("SELECT id, etiqueta_id, titulo, autor, tipo FROM Elementos WHERE titulo LIKE '%{$busca_referencias}%'");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				$elemento_etiqueta_id = $elemento['etiqueta_id'];
				$elemento_titulo = $elemento['titulo'];
				$elemento_autor = $elemento['autor'];
				$elemento_tipo = $elemento['tipo'];
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
		$adicionar_referencia_tipo = $_POST['adicionar_referencia_tipo'];
		$adicionar_referencia_etiqueta_titulo = "$adicionar_referencia_titulo / $adicionar_referencia_autor";
		$adicionar_referencia_etiqueta_titulo = mysqli_real_escape_string($conn, $adicionar_referencia_etiqueta_titulo);

		$autores = $conn->query("SELECT id FROM Etiquetas WHERE tipo = 'autor' AND titulo = '$adicionar_referencia_autor'");
		if ($autores->num_rows > 0) {
			while ($autor = $autores->fetch_assoc()) {
				$adicionar_referencia_autor_etiqueta_id = $autor['id'];
			}
		} else {
			if ($conn->query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$adicionar_referencia_autor', 'autor', $user_id)") === true) {
				$adicionar_referencia_autor_etiqueta_id = $conn->insert_id;
			}
		}
		if ($adicionar_referencia_autor_etiqueta_id != false) {
			if ($conn->query("INSERT INTO Etiquetas (titulo, tipo, user_id) VALUES ('$adicionar_referencia_etiqueta_titulo', '$adicionar_referencia_tipo', $user_id)") === true) {

				$adicionar_referencia_etiqueta_id = $conn->insert_id;

				if ($conn->query("INSERT INTO Elementos (etiqueta_id, titulo, autor_etiqueta_id, autor, tipo, user_id) VALUES ($adicionar_referencia_etiqueta_id, '$adicionar_referencia_titulo', $adicionar_referencia_autor_etiqueta_id, '$adicionar_referencia_autor', '$adicionar_referencia_tipo', $user_id)") === true) {

					$adicionar_referencia_elemento_id = $conn->insert_id;
				}

				$conn->query("INSERT INTO Acervo (user_id, etiqueta_id, etiqueta_tipo, elemento_id) VALUES ($user_id, $adicionar_referencia_etiqueta_id, '$adicionar_referencia_tipo', $adicionar_referencia_elemento_id)");

			}
		}
		echo true;
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
		include 'templates/criar_conn.php';
		$elementos = $conn->query("SELECT id FROM Elementos WHERE etiqueta_id = $etiqueta_id");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$found_elemento_id = $elemento['id'];
				return $found_elemento_id;
			}
		}
	}

	if (isset($_POST['busca_autores'])) {
		$busca_autores = $_POST['busca_autores'];
		$busca_resultados = false;
		$autores = $conn->query("SELECT DISTINCT titulo FROM Etiquetas WHERE tipo = 'autor' AND titulo LIKE '%{$busca_autores}%'");
		if ($autores->num_rows > 0) {
			while ($autor = $autores->fetch_assoc()) {
				$busca_autor = $autor['titulo'];
				$busca_resultados .= "<a href='javascript:void(0)' class='$tag_inativa_classes blue-grey adicionar_autor' value='$busca_autor'>$busca_autor</a>";
			}
		} else {
			return false;
		}
		echo $busca_resultados;
	}

	if (isset($_POST['busca_etiquetas'])) {
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
		$busca_resultados = false;
		if ($busca_etiquetas_tipo == 'all') {
			$etiqueta_exata = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas'");
		} else {
			$etiqueta_exata = $conn->query("SELECT id FROM Etiquetas WHERE titulo = '$busca_etiquetas' AND tipo = '$busca_etiquetas_tipo'");
		}
		if ($etiqueta_exata->num_rows == 0) {
			$busca_resultados .= "<div class='col-12'><button type='button' id='criar_etiqueta' name='criar_etiqueta' class='btn rounded btn-md text-center btn-primary btn-sm m-0 mb-2' value='$busca_etiquetas'>Criar etiqueta \"$busca_etiquetas\"</button></div>";
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

	if (isset($_POST['nova_etiqueta_id'])) {
		$nova_etiqueta_id = $_POST['nova_etiqueta_id'];
		$nova_etiqueta_page_id = $_POST['nova_etiqueta_page_id'];
		$nova_etiqueta_page_tipo = $_POST['nova_etiqueta_page_tipo'];
		if ($nova_etiqueta_page_tipo != 'acervo') {
			$conn->query("INSERT INTO Etiquetados (etiqueta_id, page_id, page_tipo, user_id) VALUES ($nova_etiqueta_id, $nova_etiqueta_page_id, '$nova_etiqueta_page_tipo', $user_id)");
		} else {
			$nova_etiqueta_info = return_etiqueta_info($nova_etiqueta_id);
			$nova_etiqueta_tipo = $nova_etiqueta_info[1];
			$conn->query("INSERT INTO Acervo (etiqueta_id, etiqueta_tipo, user_id) VALUES ($nova_etiqueta_id, '$nova_etiqueta_tipo', $user_id)");
		}

		$nova_etiqueta_info = return_etiqueta_info($nova_etiqueta_id);
		$nova_etiqueta_tipo = $nova_etiqueta_info[1];
		$nova_etiqueta_titulo = $nova_etiqueta_info[2];
		$nova_etiqueta_cor_icone = return_etiqueta_cor_icone($nova_etiqueta_tipo);
		$nova_etiqueta_cor = $nova_etiqueta_cor_icone[0];
		$nova_etiqueta_icone = $nova_etiqueta_cor_icone[1];


		echo "<a href='javascript:void(0);' class='$tag_ativa_classes $nova_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $nova_etiqueta_icone fa-fw'></i> $nova_etiqueta_titulo</a>";
	}

	if (isset($_POST['remover_etiqueta_id'])) {
		$remover_etiqueta_id = $_POST['remover_etiqueta_id'];
		$remover_etiqueta_page_id = $_POST['remover_etiqueta_page_id'];
		$remover_etiqueta_page_tipo = $_POST['remover_etiqueta_page_tipo'];

		if ($conn->query("UPDATE Etiquetados SET estado = 0 WHERE etiqueta_id = $remover_etiqueta_id AND page_id = $remover_etiqueta_page_id AND page_tipo = '$remover_etiqueta_page_tipo'")
			===
			true) {
			echo true;
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

		if ($conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('topico', '$criar_etiqueta_titulo', $user_id)") === true) {
			$nova_etiqueta_id = $conn->insert_id;
			$conn->query("INSERT INTO Acervo (user_id, etiqueta_id, etiqueta_tipo, elemento_id) VALUES ($user_id, $nova_etiqueta_id, 'topico', 0)");
			if ($criar_etiqueta_page_tipo != 'acervo') {
				$conn->query("INSERT INTO Etiquetados (etiqueta_id, page_id, page_tipo, user_id) VALUES ($nova_etiqueta_id, $criar_etiqueta_page_id, '$criar_etiqueta_page_tipo', $user_id)");
			}
			if (isset($criar_etiqueta_page_id)) {
				echo "<a href='javascript:void(0);' class='$tag_ativa_classes $criar_etiqueta_cor' value='$nova_etiqueta_id'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</a>";
			} else {
				echo "<span href='javascript:void(0);' class='$tag_neutra_classes $criar_etiqueta_cor'><i class='far $criar_etiqueta_icone fa-fw'></i> $criar_etiqueta_titulo</span>";
			}
		} else {
			echo false;
		}
	}

	function return_etiqueta_info($etiqueta_id)
	{
		include 'templates/criar_conn.php';
		$etiquetas = $conn->query("SELECT criacao, tipo, titulo, user_id FROM Etiquetas WHERE id = $etiqueta_id");
		if ($etiquetas->num_rows > 0) {
			while ($etiqueta = $etiquetas->fetch_assoc()) {
				$etiqueta_criacao = $etiqueta['criacao'];
				$etiqueta_tipo = $etiqueta['tipo'];
				$etiqueta_titulo = $etiqueta['titulo'];
				$etiqueta_user_id = $etiqueta['user_id'];
				$etiqueta_info = array($etiqueta_criacao, $etiqueta_tipo, $etiqueta_titulo, $etiqueta_user_id);
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

	function convert_artefato_icone_cores($artefato_tipo)
	{

		$fa_icone_anotacao = 'fa-file-alt fa-swap-opacity';
		$fa_icone_imagem = 'fa-file-image fa-swap-opacity';
		$fa_icone_plus = 'fa-plus-circle';

		$fa_icone = 'fa-circle-notch';
		$fa_primary_color = 'text-muted';

		if ($artefato_tipo == 'anotacoes') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'anotacoes_materia') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'anotacoes_elemento') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-success';
		} elseif ($artefato_tipo == 'anotacoes_user') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-info';
		} elseif ($artefato_tipo == 'anotacoes_curso') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-default';
		} elseif ($artefato_tipo == 'simulado') {
			$fa_icone = 'fa-file-check fa-swap-opacity';
			$fa_primary_color = 'text-secondary';
		} elseif (($artefato_tipo == 'anotacoes_prova') || ($artefato_tipo == 'anotacoes_texto_apoio') || ($artefato_tipo == 'anotacoes_questao')) {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-secondary';
		} elseif ($artefato_tipo == 'imagem_publica') {
			$fa_icone = $fa_icone_imagem;
			$fa_primary_color = 'text-danger';
		} elseif ($artefato_tipo == 'nova_anotacao') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-primary';
		} elseif ($artefato_tipo == 'nova_imagem') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-danger';
		} elseif ($artefato_tipo == 'anotacao_privada') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-primary';
		} elseif ($artefato_tipo == 'novo_topico') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'nova_referencia') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-success';
		} elseif ($artefato_tipo == 'nova_materia') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'novo_curso') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-default';
		} elseif ($artefato_tipo == 'novo_simulado') {
			$fa_icone = $fa_icone_plus;
			$fa_primary_color = 'text-secondary';
		} elseif ($artefato_tipo == 'adicionar_simulado') {
			$fa_icone = 'fa-plus-hexagon';
			$fa_primary_color = 'text-secondary';
		} elseif ($artefato_tipo == 'criar_simulado') {
			$fa_icone = 'fa-plus-octagon';
			$fa_primary_color = 'text-secondary';
		} elseif ($artefato_tipo == 'referencia') {
			$fa_icone = 'fa-book';
			$fa_primary_color = 'text-success';
		} elseif ($artefato_tipo == 'album_musica') {
			$fa_icone = 'fa-record-vinyl';
			$fa_primary_color = 'text-dark';
		} elseif ($artefato_tipo == 'video') {
			$fa_icone = 'fa-film';
			$fa_primary_color = 'text-info';
		} elseif (($artefato_tipo == 'imagem') || ($artefato_tipo == 'imagem_privada')) {
			$fa_icone = 'fa-image-polaroid';
			$fa_primary_color = 'text-danger';
		} elseif (($artefato_tipo == 'verbete') || ($artefato_tipo == 'topico')) {
			$fa_icone = 'fa-tag';
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'topico_interesse') {
			$fa_icone = 'fa-tag';
			$fa_primary_color = 'text-warning';
		} elseif ($artefato_tipo == 'anotacoes_admin') {
			$fa_icone = $fa_icone_anotacao;
			$fa_primary_color = 'text-light';
		}
		return array($fa_icone, $fa_primary_color);
	}

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
		if ($elemento_id == false) { return false; }
		$elementos = $conn->query("SELECT estado, etiqueta_id, criacao, tipo, titulo, autor, autor_etiqueta_id, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
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
				$result = array($elemento_estado, $elemento_etiqueta_id, $elemento_criacao, $elemento_tipo, $elemento_titulo, $elemento_autor, $elemento_autor_etiqueta_id, $elemento_capitulo, $elemento_ano, $elemento_ano, $elemento_link, $elemento_iframe, $elemento_arquivo, $elemento_resolucao, $elemento_orientacao, $elemento_comentario, $elemento_trecho, $elemento_user_id);
				return $result;
			}
		}
		return false;
	}
	
	function return_texto_info($texto_id) {
		include 'templates/criar_conn.php';
		if($texto_id == false) { return false; }
		$textos = $conn->query("SELECT concurso_id, tipo, titulo, page_id, criacao, verbete_html, verbete_text, verbete_content, user_id FROM Textos WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_concurso_id = $texto['concurso_id']; // 0
				$texto_tipo = $texto['tipo']; // 1
				$texto_titulo = $texto['titulo']; // 2
				$texto_page_id = $texto['page_id']; // 3
				$texto_criacao = $texto['criacao']; // 4
				$texto_verbete_html = $texto['verbete_html']; // 5
				$texto_verbete_text = $texto['verbete_text']; // 6
				$texto_verbete_content = $texto['verbete_content']; // 7
				$texto_user_id = $texto['user_id']; // 8
				$texto_results = array($texto_concurso_id, $texto_tipo, $texto_titulo, $texto_page_id, $texto_criacao, $texto_verbete_html, $texto_verbete_text, $texto_verbete_content, $texto_user_id);
				return $texto_results;
			}
		}
		return false;
	}
	
	function return_text_id($concurso_id, $topico_id) {
		include 'templates/criar_conn.php';
		$textos = $conn->query("SELECT id FROM Textos WHERE concurso_id = $concurso_id AND page_id = $topico_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				return $texto_id;
			}
		}
		return false;
	}

	function return_artefato_subtitulo($artefato_tipo) {
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
		} elseif ($artefato_tipo == 'anotacoes') {
			$artefato_subtitulo = 'Nota de estudos';
		} elseif ($artefato_tipo == 'anotacoes_admin') {
			$artefato_subtitulo = 'Notas dos administradores';
		} else {
			$artefato_subtitulo = $artefato_tipo;
		}
		return $artefato_subtitulo;
	}
	
	
?>
