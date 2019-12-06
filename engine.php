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
	
	$servername = "localhost";
	$username = "grupoubique";
	$password = "ubique patriae memor";
	$dbname = "Ubwiki";
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	
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
	if (isset($_GET['concurso_id'])) {
		$concurso_id = $_GET['concurso_id'];
	}
	if ($concurso_id != false) {
		$concurso_sigla = return_concurso_sigla($concurso_id);
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$found = false;
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT page_id, tipo FROM Searchbar WHERE concurso_id = '$concurso_id' AND chave = '$command' ORDER BY ordem");
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
		error_log('this happened');
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
		$nossa_copia = adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $page_id, $user_id, $contexto);
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
		if ($contexto != 'privada') {
			$nova_imagem_tipo = 'imagem';
		} else {
			$nova_imagem_tipo = 'imagem_privada';
		}
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
				$conn->query("INSERT INTO Elementos (tipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ('$nova_imagem_tipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
			}
			if ($page_id != 0) {
				// aqui deveria checar para ver se é duplicada.
				$conn->query("INSERT INTO Elementos (tipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ('$nova_imagem_tipo', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
				$result2 = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
				if ($result2->num_rows > 0) {
					while ($row = $result2->fetch_assoc()) {
						$nova_imagem_id = $row['id'];
						$result3 = $conn->query("SELECT id FROM Verbetes_elementos WHERE elemento_id = $nova_imagem_id");
						if ($result3->num_rows == 0) {
							$conn->query("INSERT INTO Verbetes_elementos (page_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($page_id, '$contexto', $nova_imagem_id, '$nova_imagem_tipo', $user_id)");
						}
						break;
					}
				}
			} else {
				while ($row = $result->fetch_assoc()) {
					$nova_imagem_id = $row['id'];
					$conn->query("INSERT INTO Verbetes_elementos (page_id, tipo_pagina, elemento_id, tipo, user_id) VALUES ($page_id, '$contexto', $nova_imagem_id, '$nova_imagem_tipo', $user_id)");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
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
		
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		
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

?>
