<?php

$servername = "localhost";
$username = "grupoubique";
$password = "ubique patriae memor";
$dbname = "Ubique";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");

function carregar_navbar() {
  echo "<nav class='navbar navbar-expand-lg bg-white'>
    <a class='navbar-brand align-top' href='index.php'><h2>Ubwiki</h2></a>
    <ul class='nav navbar-nav ml-auto'>
      <li><a class='navlink float-right h4 align-top' href='userpage.php'>Minha conta</a></li>
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

?>
