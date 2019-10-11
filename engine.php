<?php
function top_page() {
  echo '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <link type="image/vnd.microsoft.icon" rel="icon" href="../nxst/favicons/simple.scss.ico"/>
    <title>Ubwiki</title>
  </head>
  ';
}

function bottom_page() {
  echo '
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  </html>
  ';
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

function ler_cartoes($concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla, materia, ordem  FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
  if ($result->num_rows > 0) {
    $count = 1;
    while($row = $result->fetch_assoc()) {
      if ($count == 1) { echo "<div class='row justify-content-center'>"; }
      $count++;
      $sigla = $row["sigla"];
      $materia = $row["materia"];
      echo "
      <a href='#verbetes'>
        <div class='col-lg-2 bg-lighter mx-3 my-3 py-0 px-1 rounded bdark'>
          <a href='materia.php?sigla=$sigla&concurso=$concurso'><small class='text-muted text-uppercase smaller'>$materia</small></a>
        </div>
      </a>
      ";
      if ($count == 5) {
        echo "</div>";
        $count = 1;
      }
    }
  }
  $conn->close();
}

function ler_edital($materia) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $sql = "SELECT etiqueta, materia, superior FROM Etiquetas";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $check = $row['materia'];
        if ($materia == $check) {
          $superior = $row["superior"];
          $etiqueta = $row["etiqueta"];
          echo "<a href='#!' class='list-group-item list-group-item-action'>$etiqueta</a>";
      }
    }
  }
  $conn->close();
}

function readSearchOptions($concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT materia, ordem FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $materia = $row["materia"];
      echo "<option>$materia</option>";
    }
  }
  $conn->close();
}

function standard_jumbotron() {
  echo "
  <div class='container-fluid px-1 py-1 mb-3 text-center'>
    <div class='jumbotron col-sm-12 mb-0'>
      <a href='index.php'><h1 class='display-4 logo-jumbotron'>Ubwiki</h1></a>
    </div>
  </div>
  ";
}

if (isset($_POST['searchBarGo'])) {
  $concurso = $_POST['searchBarGo'];
  $command = $_POST['searchbar'];
  error_log("this happened: concurso: $concurso and command: $command");
  header("Location:materia.php?sigla=PICACD&concurso=CACD");
  // $servername = "localhost";
  // $username = "grupoubique";
  // $password = "ubique patriae memor";
  // $dbname = "Ubique";
  // $conn = new mysqli($servername, $username, $password, $dbname);
  // mysqli_set_charset($conn,"utf8");
  // $result = $conn->query("SELECT sigla FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND materia = '$command' ORDER BY ordem");
  // if ($result->num_rows > 0) {
  //   while($row = $result->fetch_assoc()) {
  //     $sigla = $row["sigla"];
  //     header("materias.php?sigla=$sigla&concurso=$concurso");
  //   }
  // }
  // $conn->close();
}

function carregar_pagina($sigla, $concurso) {
  echo "<h1>Página de $sigla</h1>";
  echo "<p>Concurso: $concurso</p>";
}
?>
