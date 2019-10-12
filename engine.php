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
    <script src="https://kit.fontawesome.com/b8e073920a.js" crossorigin="anonymous"></script>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <link type="image/vnd.microsoft.icon" rel="icon" href="imagens/favicon.ico"/>
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

function load_footer() {
  echo '
    <footer class="container-fluid text-center bg-lighter text-dark py-2 height5vh align-bottom">
      <p class="mb-0">A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Clique <a href="termos.php" target="_blank">aqui</a> para rever os termos e condições de uso da página.</p>
    </footer>
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

function standard_jumbotron() {
  echo "
  <div class='container-fluid px-1 py-1 mb-3 text-center'>
    <div class='jumbotron col-sm-12 mb-0'>
      <a href='index.php'><h1 class='display-4 logo-jumbotron'>Ubwiki</h1></a>
    </div>
  </div>
  ";
}


function readSearchOptions($concurso) {
  $searchBarValues = array();
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      $materia = $row["materia"];
      $match = array($materia,$sigla);
      array_push($searchBarValues,$match);
      echo "<option>$materia</option>";
    }
  }
  $result = $conn->query("SELECT nivel1, nivel2, nivel3, id FROM Temas WHERE concurso = '$concurso' ORDER BY sigla_materia, nivel1");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      $nivel1 = limpar_tema($nivel1);
      $nivel2 = limpar_tema($nivel2);
      $nivel3 = limpar_tema($nivel3);
      if ($nivel3 != false) {
        $match = array($nivel3,$id);
        array_push($searchBarValues,$match);
        echo "<option>$nivel3</option>";
      }
      else {
        $match = array($nivel2,$id);
        array_push($searchBarValues,$match);
        if ($nivel2 != false) {
          echo "<option>$nive2</option>";
        }
        else {
          $match = array($nivel1,$id);
          array_push($searchBarValues,$match);
          echo "<option>$nivel1</option>";
        }
      }
      $conn->close();
    }
  }
  $searchBarValues = serialize($searchBarValues);
  error_log($searchBarValues);
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
    echo "<h1 class='mb-5'>Portal de $materia</h1>";
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

  $tema = limpar_tema($tema);

  echo "<h1 class='mb-5'>$tema</h1>";
  echo"
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-12'><h2>Índice</h2></div>
    </div>
    <div class='row mt-2'>
      <div class='col-lg-12'>
        <ul class='list-group'>
          <a class='list-group-item list-group-item-action' href='#verbete'>Verbete consolidado</a>
          <a class='list-group-item list-group-item-action' href='#verbetes'>Verbetes relacionados</a>
          <a class='list-group-item list-group-item-action' href='#bibliografia'>Bibliografia pertinente</a>
          <a class='list-group-item list-group-item-action' href='#videos'>Vídeos e aulas relacionados</a>
          <a class='list-group-item list-group-item-action' href='#links'>Links externos</a>
          <a class='list-group-item list-group-item-action' href='#anotacoes'>Minhas anotações</a>
          <a class='list-group-item list-group-item-action' href='#questoes'>Questões de provas passadas</a>
          <a class='list-group-item list-group-item-action' href='#discussao'>Discussão</a>
        </ul>
      </div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row h2'>
      <div class='col-lg-11'><h2 id='verbete'>Verbete consolidado</h2></div>
      <div class='col-lg-1 float-right'><i class='fal fa-edit'></i></div>
    </div>
    <div class='row'>";
      if ($verbete == false) {
        echo "<div class='col-lg-12'><p>Ainda não há verbete consolidado para este tema do $concurso.</p></div>";
      }
      else {
        echo $verbete;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='verbetes'>Verbetes relacionados</h2></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='bibliografia'>Bibliografia pertinente</h2></div>
      <div class='col-lg-1 h2 float-right'><i class='fal fa-plus-square'></i></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='videos'>Vídeos e aulas relacionados</h2></div>
      <div class='col-lg-1 h2 float-right'><i class='fal fa-plus-square'></i></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='links'>Links externos</h2></div>
      <div class='col-lg-1 h2 float-right'><i class='fal fa-plus-square'></i></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='anotacoes'>Minhas anotações</h2></div>
      <div class='col-lg-1 h2 float-right'><i class='fal fa-edit'></i></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='questoes'>Questões de provas passadas</h2></div>
    </div>
  </div>
  <div class='container-fluid px-0 mb-5'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='discussao'>Discussão</h2></div>
    </div>
  </div>
  ";
  }
  $conn->close();
}

function limpar_tema($tema) {
  $trim = strpos($tema, " ");
  $tema = substr($tema, $trim);
  $tema = substr($tema, 0, -1);
  return $tema;
}

?>
