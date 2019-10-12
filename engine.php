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
  <script type="text/javascript" src="engine.js"></script>
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

function ler_cartoes($concurso, $row_itens) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla, materia, ordem  FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
  if ($result->num_rows > 0) {
    $count = 0;
    while($row = $result->fetch_assoc()) {
      if ($count == 0) { echo "<div class='row justify-content-center'>"; }
      $count++;
      $sigla = $row["sigla"];
      $materia = $row["materia"];
      echo "
        <div class='col-lg-2 bg-lighter mx-3 my-3 py-0 px-1 rounded bdark cardmateria' href='materia.php?sigla=$sigla&concurso=$concurso'>
          <small class='text-muted text-uppercase smaller'>$materia</small>
        </div>
      ";
      if ($count == $row_itens) {
        echo "</div>";
        $count = 0;
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

if (isset($_POST['sbcommand'])) {
  $concurso = base64_decode($_POST['sbconcurso']);
  $command = base64_decode($_POST['sbcommand']);
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND materia = '$command' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      echo "foundfoundfoundfmateria.php?sigla=$sigla&concurso=$concurso";
      $conn->close();
      $found = true;
    }
  }
  // aqui entrará a parte de busca por temas.
  if ($found == false) {
    // não havendo encontrado um match exato, o sistema busca por partial matches
    $index = 500;
    $winner = 0;
    $result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $sigla = $row["sigla"];
        $materia = $row["materia"];
        $materialow = mb_strtolower($materia);
        $commandlow = mb_strtolower($command);
        $check = levenshtein($materialow, $commandlow, 1, 1, 1);
  			if (strpos($materialow, $commandlow) !== false) {
  				echo "foundfoundfoundf$materia";
          $conn->close();
  				return;
  			}
        elseif ($check < $index) {
          $index = $check;
          $winner = $materia;
        }
      }
      $length = strlen($command);
      if ($index < $length) {
        echo "foundfoundfoundf$winner";
        $conn->close();
        return;
      }
    }
  }
  echo "notfoundnotfoundnada foi encontrado";
  $conn->close();
  return;
}

function carregar_pagina($sigla, $concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $materia = $row["materia"];
    }
    echo "<h1>Portal de $materia</h1>";
  }
  else {
    echo "<h1>Página não-encontrada</h1>
    <p>Clique <a href='index.php'>aqui</a> para retornar.</p>
    ";
    return;
  }
  echo "<h2>Verbetes</h2>
  <ul class='list-group'>";
  $result = $conn->query("SELECT id, nivel1, nivel2, nivel3 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      if ($nivel3 == false) {
        if ($nivel2 == false) {
          echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><strong>$nivel1</strong></a>";
        }
        else {
          echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><span class='ml-2'>$nivel2</span></a>";
        }
      }
      else {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-5'>$nivel3</span></em></a>";
      }
    }
  }
  $conn->close();
  echo "</ul>";
}

function carregar_verbete($tema, $concurso){
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT nivel1, nivel2, nivel3, verbete_consolidado FROM Temas WHERE concurso = '$concurso' AND id = $tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      $verbete = $row["verbete_consolidado"];
      if ($nivel3 == false) {
        if ($nivel2 == false) {
          $tema = $nivel1;
        }
        else {
          $tema = $nivel2;
        }
      }
      else {
        $tema = $nivel3;
      }
    }
  $trim = strpos($tema, " ");
  $tema = substr($tema, $trim);
  $tema = substr($tema, 0, -1);

  echo "<h1>$tema</h1>";
  echo"<h2>Índice</h2>
    <h2>Verbete consolidado</h2>";

    if ($verbete != false) {
      echo "<p>Ainda não há verbete consolidado para este tema do $concurso.</p>";
    }
    else {
      echo $verbete;
    }

  echo"<h2>Verbetes relacionados</h2>
    <h2>Bibliografia pertinente</h2>
    <h2>Vídeos e aulas relacionados</h2>
    <h2>Links externos</h2>
    <h2>Minhas anotações</h2>
    <h2>Questões de provas passadas sobre este tema</h2>
    <h2>Discussão</h2>
  ";
  }
  $conn->close();
}
?>
