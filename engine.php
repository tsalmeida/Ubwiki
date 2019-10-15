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
  <script type="text/javascript" charset="UTF-8" src="engine.js"></script>
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
  $result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      $nivel4 = $row["nivel4"];
      $nivel5 = $row["nivel5"];
      if ($nivel5 != false) {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-5'>$nivel5</span></a></em>";
      }
      elseif ($nivel4 != false) {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-4'>$nivel4</span></em></a>";
      }
      elseif ($nivel3 != false) {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-2'>$nivel3</span></em></a>";
      }
      elseif ($nivel2 != false) {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><span class='ml-1'>$nivel2</span></a>";
      }
      elseif ($nivel1 != false) {
        echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><strong>$nivel1</strong></a>";
      }
    }
  }
  $conn->close();
  echo "</ul>";
}

function carregar_edicao_verbete($id_tema, $concurso) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND sigla = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $tema = $row['chave'];
    }
  }
  echo "<h1 class='mb-5'>$tema</h1>";
}

function carregar_verbete($id_tema, $concurso){
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND sigla = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $tema = $row['chave'];
    }
  }
  $found = false;
  $id_verbete = false;
  $verbete = false;
  $imagens = false;
  $verbetes = false;
  $bibliografia = false;
  $videos = false;
  $links = false;
  $discussao = false;
  $result = $conn->query("SELECT id, verbete, verbetes, bibliografia, videos, links, discussao FROM Verbetes WHERE id_tema = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $found = true;
      $id_verbete = $row["id"];
      $verbete = $row["verbete"];
      $imagens = $row["imagens"];
      $verbetes = $row["verbetes"];
      $bibliografia = $row["bibliografia"];
      $videos = $row["videos"];
      $links = $row["links"];
      $discussao = $row["discussao"];
    }
  }
  $conn->close();

  echo "<h1 class='mb-5'>$tema</h1>";
  echo"
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-12'><h2>Índice</h2></div>
    </div>
    <div class='row mt-2'>
      <div class='col-lg-12'>
        <ul class='list-group'>
          <a class='list-group-item list-group-item-action' href='#verbete'>Verbete consolidado</a>
          <a class='list-group-item list-group-item-action' href='#imagens'>Imagens de apoio</a>
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
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='verbete'>Verbete consolidado</h2></div>
      <div class='col-lg-1 h2 float-right'><a href='editar_verbete.php?concurso=$concurso&tema=$id_tema'><i class='fal fa-edit'></i></a></div>
    </div>
    <div class='row'>";
      if ($verbete == false) {
        echo "<div class='col-lg-12'><p>Ainda não há verbete consolidado para este tema.</p></div>";
      }
      else {
        echo $verbete;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='imagens'>Imagens de apoio</h2></div>
      <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
    </div>
    <div class='row'>";
      if ($imagens == false) {
        echo "<div class='col-lg-12'><p>Ainda não foram acrescentadas imagens de apoio sobre este tema.</p></div>";
      }
      else {
        echo $imagens;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='verbetes'>Verbetes relacionados</h2></div>
    </div>
    <div class='row'>";
      if ($verbetes == false) {
        echo "<div class='col-lg-12'><p>Ainda não foram identificados verbetes relacionados para este tema.</p></div>";
      }
      else {
        echo $verbetes;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='bibliografia'>Bibliografia pertinente</h2></div>
      <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
    </div>
    <div class='row'>";
      if ($bibliografia == false) {
        echo "<div class='col-lg-12'><p>Ainda não foram identificados recursos bibliográficos sobre este tema.</p></div>";
      }
      else {
        echo $bibliografia;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='videos'>Vídeos e aulas relacionados</h2></div>
      <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
    </div>
    <div class='row'>";
      if ($videos == false) {
        echo "<div class='col-lg-12'><p>Ainda não foram acrescentados links para vídeos e aulas sobre este tema.</p></div>";
      }
      else {
        echo $videos;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='links'>Links externos</h2></div>
      <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
    </div>
    <div class='row'>";
      if ($links == false) {
        echo "<div class='col-lg-12'><p>Ainda não foram acrescentados links externos sobre este tema.</p></div>";
      }
      else {
        echo $links;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-11'><h2 id='anotacoes'>Minhas anotações</h2></div>
      <div class='col-lg-1 h2 float-right'><a><i class='fal fa-edit'></i></a></div>
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='questoes'>Questões de provas passadas</h2></div>
    </div>
    <div class='row'>";
      $questoes = false;
      if ($questoes == false) {
        echo "<div class='col-lg-12'><p>Não há registro de questão em provas passadas sobre este tema.</p></div>";
      }
      else {
        echo $questoes;
      }
    echo "
    </div>
  </div>
  <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
    <div class='row'>
      <div class='col-lg-12'><h2 id='discussao'>Debate</h2></div>
    </div>
    <div class='row'>";
      if ($discussao == false) {
        echo "<div class='col-lg-12'><p>Não há debate sobre este tema. Deixe aqui sua opinião!</p></div>";
      }
      else {
        echo $discussao;
      }
    echo "
    </div>
  </div>
  ";
}

if (isset($_POST['reconstruir_concurso'])) {
  $concurso = $_POST['reconstruir_concurso'];
  reconstruir_searchbar($concurso);
}

function reconstruir_searchbar($concurso) {
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
  $result = $conn->query("SELECT nivel1, nivel2, nivel3, id FROM Temas WHERE concurso = '$concurso' ORDER BY id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row["id"];
      $nivel1 = $row["nivel1"];
      $nivel2 = $row["nivel2"];
      $nivel3 = $row["nivel3"];
      $ordem++;
      if ($nivel3 != false) {
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel3', 'tema')");
      }
      else {
        if ($nivel2 != false) {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel2', 'tema')");
        }
        else {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel1', 'tema')");
        }
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
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $chave = $row['chave'];
      echo "<option>$chave</option>";
    }
  }
  $conn->close();
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
        $conn->close();
        return;
      }
      elseif ($tipo == "tema") {
        echo "foundfoundfoundfverbete.php?concurso=$concurso&tema=$sigla";
        $conn->close();
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
        $conn->close();
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
      $conn->close();
      return;
    }
  }
  echo "nada foi encontrado";
  $conn->close();
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
  $result = $conn->query("SELECT id, sigla_materia, metaid, nivel1, nivel2, nivel3 FROM Temas WHERE concurso = '$concurso' AND ciclo_revisao = 0 ORDER BY id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $active1 = false;
      $active2 = false;
      $active3 = false;
      $id = $row['id'];
      $sigla_materia = $row['sigla_materia'];
      $metaid = $row['metaid'];
      $nivel1 = $row['nivel1'];
      $nivel2 = $row['nivel2'];
      $nivel3 = $row['nivel3'];
      if ($nivel3 != false) {
        $active3 = 'active';
      }
      else {
        if ($nivel2 != false) {
          $active2 = 'active';
        }
        else {
          $active1 = 'active';
        }
      }
      echo "
        <ul class='list-group text-left'>
          <li class='list-group-item'><strong>MATERIA:</strong> $sigla_materia</li>
          <li class='list-group-item $active1'><strong>Nível 1:</strong> $nivel1</li>
          <li class='list-group-item $active2'><strong>Nível 2:</strong> $nivel2</li>
          <li class='list-group-item $active3'><strong>Nível 3:</strong> $nivel3</li>
          <li class='list-group-item'><strong>ID:</strong> $id</li>
          <li class='list-group-item'><strong>Metatema:</strong> $metaid</li>
        </ul>";
      echo "
        <form class='text-center border border-light px-2 my-2' method='post'>
          <p class='h4 my-4'>Acrescentar meta-tema</p>
          <p class='text-left'>Para que o sistema seja imune a mudanças de edital, é necessário que cada tema seja internamente identificado por um meta-tema.</p>
          <fieldset class='form-group text-left'>
            <label for='registrarmeta'>Novo meta-tema para este assunto:</label>
            <input name='novo_metatema' id='registrarmeta' type='text'></input>
          </fieldset>
          <div class='custom-control custom-checkbox'>
              <input type='checkbox' class='custom-control-input' id='remover_ciclo' name='remover_ciclo' checked>
              <label class='custom-control-label' for='remover_ciclo'>Remover do ciclo de revisão</label>
          </div>
          <button name='novo_metatema_id' type='submit' class='btn btn-primary' value='$id'>Registrar novo meta-tema</button>
        </form>
        <form class='text-center border border-light px-2 my-2' method='post'>
          <p class='h4 my-4'>Re-iniciar ciclo de revisão</p>
          <p class='text-left'>Ao pressionar o botão abaixo, todas as questões deste concurso serão colocadas no ciclo de revisão.</p>
          <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
        </form>
        <form class='text-center border border-light px-2 my-2' method='post'>
          <p class='h4 my-4'>Metatemas automáticos</p>
          <p class='text-left'>Ao pressionar o botão abaixo, todos os metatemas não-registrados serão automaticamente criados, baseados no título do tema.</p>
          <button name='metatemas_automaticos' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
        </form>
      ";
      return;
    }
  }
}

if (isset($_POST['novo_metatema_id'])) {
  $novo_metatema = $_POST['novo_metatema'];
  $novo_metatema_id = $_POST['novo_metatema_id'];
  $remover_ciclo = $_POST['remover_ciclo'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("UPDATE Temas SET metaid = '$novo_metatema' WHERE id = '$novo_metatema_id'");
  if ($remover_ciclo = true) {
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE id = '$novo_metatema_id'");
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

if (isset($_POST['metatemas_automaticos'])) {
  $concurso = $_POST['metatemas_automaticos'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT id, nivel1, nivel2, nivel3 FROM Temas WHERE concurso = '$concurso' AND metaid IS NULL");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $nivel1 = $row['nivel1'];
      $nivel2 = $row['nivel2'];
      $nivel3 = $row['nivel3'];
      $novo_metaid = strtolower($novo_metaid);
      $novo_metaid = str_replace(" e ", "-", $novo_metaid);
      $novo_metaid = str_replace(" a ", "-", $novo_metaid);
      $novo_metaid = str_replace(" o ", "-", $novo_metaid);
      $novo_metaid = str_replace(" as ", "-", $novo_metaid);
      $novo_metaid = str_replace(" os ", "-", $novo_metaid);
      $novo_metaid = str_replace(" de ", "-", $novo_metaid);
      $novo_metaid = str_replace(" do ", "-", $novo_metaid);
      $novo_metaid = str_replace(" da ", "-", $novo_metaid);
      $novo_metaid = str_replace(" dos ", "-", $novo_metaid);
      $novo_metaid = str_replace(" das ", "-", $novo_metaid);
      $novo_metaid = str_replace(" no ", "-", $novo_metaid);
      $novo_metaid = str_replace(" na ", "-", $novo_metaid);
      $novo_metaid = str_replace(" nos ", "-", $novo_metaid);
      $novo_metaid = str_replace(" nas ", "-", $novo_metaid);
      $novo_metaid = str_replace(" em ", "-", $novo_metaid);
      $novo_metaid = str_replace(" para ", "-", $novo_metaid);
      $novo_metaid = str_replace(" ", "-", $novo_metaid);
      $novo_metaid = str_replace("---", "-", $novo_metaid);
      $novo_metaid = str_replace("--", "-", $novo_metaid);
      $novo_metaid = str_replace(":", "", $novo_metaid);
      $novo_metaid = str_replace(".", "", $novo_metaid);
      $novo_metaid = str_replace(",", "", $novo_metaid);
      $novo_metaid = str_replace("ç", "c", $novo_metaid);
      $novo_metaid = str_replace("ã", "a", $novo_metaid);
      $novo_metaid = str_replace("á", "a", $novo_metaid);
      $novo_metaid = str_replace("â", "a", $novo_metaid);
      $novo_metaid = str_replace("í", "i", $novo_metaid);
      $novo_metaid = str_replace("ê", "e", $novo_metaid);
      $novo_metaid = str_replace("é", "e", $novo_metaid);
      $novo_metaid = str_replace("à", "a", $novo_metaid);
      $novo_metaid = str_replace("(", "", $novo_metaid);
      $novo_metaid = str_replace(")", "", $novo_metaid);
      $novo_metaid = str_replace("ó", "o", $novo_metaid);
      $novo_metaid = str_replace("ô", "o", $novo_metaid);
      $novo_metaid = utf8_encode($novo_metaid);
      $novo_metaid = substr($novo_metaid, 0, 25);
      $update = $conn->query("UPDATE Temas SET metaid = '$novo_metaid' WHERE id = '$id'");
    }
  }
}
?>
