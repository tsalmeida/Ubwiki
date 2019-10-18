<?php

$servername = "localhost";
$username = "grupoubique";
$password = "ubique patriae memor";
$dbname = "Ubique";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");

function carregar_navbar() {
  $breadcrumbs = func_get_args();
  echo "<nav class='navbar navbar-expand-lg'>
    <a class='navbar-brand text-muted2 playfair' href='index.php'>Ubwiki</a>
    <div class='mr-auto'>
      <nav>
        <ol class='breadcrumb d-inline-flex pl-0 pt-0 text-dark'>
          <li class='breadcrumb-item text-muted2'>Matéria</li>
          <li class='breadcrumb-item text-muted2'>Nível 1</li>
          <li class='breadcrumb-item text-muted2'>Nível 2</li>
          <li class='breadcrumb-item text-muted2'>Nível 3</li>
          <li class='breadcrumb-item text-muted2'>Nível 4</li>
          <li class='breadcrumb-item text-muted2'>Nível 5</li>
        </ol>
      </nav>
    </div>
    <ul class='nav navbar-nav ml-auto nav-flex-icons'>
      <li class='nav-item'>
        <a class='navlink waves-effect waves-light text-muted2' href='userpage.php'>
          <i class='fas fa-user-tie fa-2x'></i>
        </a>
      </li>
    </ul>
  </nav>";
}

function top_page() {
	$args = func_get_args();
  echo '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b8e073920a.js" crossorigin="anonymous"></script>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <link type="image/vnd.microsoft.icon" rel="icon" href="imagens/favicon.ico"/>
    <title>Ubwiki</title>';

    if ($args != false) {
      $array = 0;
      while (isset($args[$array])) {
        if ($args[$array] == "quill") {
        }
        elseif ($args[$array] == "onepage") {
          echo "
            <style>
              html, body, .onepage {
                height: 100vh;
                overflow-y: auto;
              }
            </style>
          ";
        }
        $array++;
      }
    }
  echo '</head>';
}

function bottom_page() {

  $args = func_get_args();

  echo '
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript" charset="UTF-8" src="engine.js"></script>
  ';

  if ($args != false) {
    $array = 0;
    while (isset($args[$array])) {
      if ($args[$array] == "quill") {
      }
      $array++;
    }
  }

  echo "</html>";

}

function load_footer() {
  echo "
    <footer class='footer-copyright bg-lighter text-dark text-center font-small mt-2'>
      <p class='mb-0'>A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Siga <a href='termos.php' target='_blank'>este</a> link para rever os termos e condições de uso da página.</p>
    </footer>
  ";
}

function extract_gdoc($url) {
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

function extract_zoho($linkplanilha, $authtoken, $ownername, $materia, $scope) {
  $ch = curl_init();
  $linkplanilha = "$linkplanilha?authtoken=$authtoken&zc_ownername=$ownername&materia=$materia&scope=$scope";
  curl_setopt($ch, CURLOPT_URL, $linkplanilha);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);
  $xml = simplexml_load_string($output, "SimpleXMLElement", LIBXML_NOCDATA);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  $output = serialize($array);
  return $output;
}

function standard_jumbotron($titulo, $link) {
  if ($link == false) {
    echo "
    <div class='container-fluid p-0 m-0 text-center'>
      <div class='jumbotron col-12 mb-0'>
        <h1 class='display-4 logo-jumbotron'>$titulo</h1>
      </div>
    </div>
    ";
  }
  else {
    echo "
    <div class='container-fluid p-0 m-0 text-center'>
      <div class='jumbotron col-12 mb-0'>
        <a href='$link'><h1 class='display-4 logo-jumbotron'>$titulo</h1></a>
      </div>
    </div>
    ";
  }
}

function sub_jumbotron($titulo, $link) {
  if ($link == false) {
    echo "
    <div class='container-fluid py-3 col-lg-12 bg-lighter text-center mb-3'>
      <h1>$titulo</h1>
    </div>
    ";
  }
}

if (isset($_POST['sbcommand'])) {
  $concurso = base64_decode($_POST['sbconcurso']);
  $command = base64_decode($_POST['sbcommand']);
  $command = utf8_encode($command);
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla, tipo FROM Searchbar WHERE concurso = '$concurso' AND chave = '$command' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      $tipo = $row["tipo"];
      if ($tipo == "materia") {
        echo "foundfoundfoundfmateria.php?sigla=$sigla&concurso=$concurso";
        return;
      }
      elseif ($tipo == "tema") {
        echo "foundfoundfoundfverbete.php?concurso=$concurso&tema=$sigla";
        return;
      }
    }
  }
  $index = 500;
  $winner = 0;
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $chave = $row["chave"];
      $chavelow = mb_strtolower($chave);
      $commandlow = mb_strtolower($command);
      $check = levenshtein($chavelow, $commandlow, 1, 1, 1);
			if (strpos($chavelow, $commandlow) !== false) {
        echo "notfoundnotfound$chave";
				return;
			}
      elseif ($check < $index) {
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
  echo "nada foi encontrado";
  return;
}

?>
