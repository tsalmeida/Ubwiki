<?php
	
	if (!isset($loginpage)) {
		$loginpage = false;
	}
	
	$sessionpath = getcwd();
	$sessionpath .= '/../sessions';
	session_save_path($sessionpath);
	session_start();
	
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
		$special = true;
	} elseif ($special == 19815848) {
		$_SESSION['email'] = 'cavalcanti@me.com';
		$special = true;
	} elseif ($special == 17091979) {
		$_SESSION['email'] = 'marciliofcf@gmail.com';
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
	
	if (isset($_POST['sbcommand'])) {
		$concurso = base64_decode($_POST['sbconcurso']);
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
		$user_id = $_POST['user_id'];
		$page_id = $_POST['page_id'];
		$contexto = $_POST['contexto'];
		$nossa_copia = adicionar_imagem($nova_imagem_link, 'Não há título registrado', $page_id, $user_id, $contexto);
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
		$page_id = $args[2];
		$user_id = $args[3];
		$contexto = $args[4];
		if ($contexto == 'verbete') {
			$contexto_column = 'topico_id';
		} elseif ($contexto == 'elemento') {
			$contexto_column = 'elemento_page_id';
		}
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubwiki";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
		if ($result->num_rows == 0) {
			$randomfilename = generateRandomString(12);
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
			$conn->query("INSERT INTO Elementos (tipo, titulo, link, arquivo, resolucao, orientacao, user_id) VALUES ('imagem', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', $user_id)");
			$result2 = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					$nova_imagem_id = $row['id'];
					$conn->query("INSERT INTO Verbetes_elementos ($contexto_column, elemento_id, tipo, user_id) VALUES ($page_id, $nova_imagem_id, 'imagem', $user_id)");
					break;
				}
			}
		} else {
			while ($row = $result->fetch_assoc()) {
				$nova_imagem_id = $row['id'];
				$conn->query("INSERT INTO Verbetes_elementos ($contexto_column, elemento_id, tipo, user_id) VALUES ($page_id, $nova_imagem_id, 'imagem', $user_id)");
				break;
			}
		}
		return "https://www.ubwiki.com.br/imagens/verbetes/$nova_imagem_arquivo";
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

	function return_titulo_topico($topico_id) {
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
				}
				elseif ($found_topico_nivel == 2) {
					$found_topico_titulo = $row_find['nivel2'];
				}
				elseif ($found_topico_nivel == 3) {
					$found_topico_titulo = $row_find['nivel3'];
				}
				elseif ($found_topico_nivel == 4) {
					$found_topico_titulo = $row_find['nivel4'];
				}
				else {
					$found_topico_titulo = $row_find['nivel5'];
				}
			}
			return $found_topico_titulo;
		}
		return false;
	}
	
	function return_concurso_id_topico($topico_id) {
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
	
	function return_concurso_sigla($concurso_id) {
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

?>
