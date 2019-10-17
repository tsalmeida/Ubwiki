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

if (isset($_POST['reconstruir_concurso'])) {
  $concurso = $_POST['reconstruir_concurso'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $ordem = 0;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $conn->query("DELETE FROM Searchbar WHERE concurso = '$concurso'");
  $result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      $materia = $row["materia"];
      $ordem++;
      $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', '$sigla', '$materia', 'materia')");
    }
  }
  $result = $conn->query("SELECT nivel1, nivel2, nivel3, nivel4, nivel5, id FROM Temas WHERE concurso = '$concurso' ORDER BY id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      $nivel4 = $row["nivel4"];
      $nivel5 = $row["nivel5"];
      $ordem++;
      if ($nivel5 != false) {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel5', 'tema')");
      }
      elseif ($nivel4 != false) {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel4', 'tema')");
      }
      elseif ($nivel3 != false) {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel3', 'tema')");
      }
      elseif ($nivel2 != false) {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel2', 'tema')");
      }
      else {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel1', 'tema')");
      }
    }
  }
}

function readSearchOptions($concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $chave = $row['chave'];
      echo "<option>$chave</option>";
    }
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

if (isset($_POST['metalinguagem_concurso'])) {
  $metalinguagem_concurso = $_POST['metalinguagem_concurso'];
  header("Location:edicao_temas.php?concurso=$metalinguagem_concurso");
}

function carregar_edicao_temas($concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT id, sigla_materia, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND ciclo_revisao = 0 ORDER BY id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $active1 = false;
      $active2 = false;
      $active3 = false;
      $active4 = false;
      $active5 = false;
      $id = $row['id'];
      $sigla_materia = $row['sigla_materia'];
      $nivel1 = $row['nivel1'];
      $nivel2 = $row['nivel2'];
      $nivel3 = $row['nivel3'];
      $nivel4 = $row['nivel4'];
      $nivel5 = $row['nivel5'];
      if ($nivel5 != false) { $active5 = 'active'; }
      elseif ($nivel4 != false) { $active4 = 'active'; }
      elseif ($nivel3 != false) { $active3 = 'active'; }
      elseif ($nivel2 != false) { $active2 = 'active'; }
      else { $active1 = 'active'; }
      echo "
        <ul class='list-group text-left'>
          <li class='list-group-item'><strong>MATERIA: </strong>$sigla_materia</li>
          <li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>
          <li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>
          <li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>
          <li class='list-group-item $active4'><strong>Nível 2: </strong>$nivel4</li>
          <li class='list-group-item $active5'><strong>Nível 3: </strong>$nivel5</li>
          <li class='list-group-item'><strong>ID: </strong>$id</li>
        </ul>";
      echo "
        <form class='text-center border border-light px-2 my-2' method='post'>
          <p class='h4 my-4'>Re-iniciar ciclo de revisão</p>
          <p class='text-left'>Ao pressionar o botão abaixo, todas as questões deste concurso serão colocadas no ciclo de revisão.</p>
          <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
        </form>

      ";
      return;
    }
  }
}

if (isset($_POST['reiniciar_ciclo'])) {
  $concurso = $_POST['reiniciar_ciclo'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
}

?>
