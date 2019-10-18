<?php
  include 'engine.php';
  $concurso = 'CACD';

top_page("onepage");

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
  error_log("this happened");
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
  <body>
    <div class='container-fluid px-0 onepage'>
      <?php carregar_navbar(); ?>
      <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-sm-5 px-3">
                <img class="img-fluid logo" src="imagens/ubiquelogo.png"></img>
            </div>
        </div>
        <div class="row text-center">
          <div class="col-lg-12 col-sm-12 mb-5">
            <?php echo "<p class='lead'>Bem-vindo à Ubwiki, o sistema mais inteligente de preparação para o $concurso.</p>"; ?>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-lg-6 col-sm-12 mb-5">
            <form id="searchform" action="" method="post">
              <div id="searchDiv">
                <input id="searchBar" list="searchlist" type="text" class="searchBar" name="searchBar" rows="1" autocomplete="off" spellcheck="false" placeholder="O que você vai estudar hoje?" required></input>
                <datalist id="searchlist">
                  <?php
                    readSearchOptions($concurso);
                  ?>
                </datalist>
                <?php
                  echo "<input id='searchBarGo' name='searchBarGo' value='$concurso' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
                ?>
              </div>
            </form>
          </div>
        </div>
        <div class="row mt-5 justify-content-center">
          <div class='col-lg-8 col-sm-12'>
            <div class='row'>
              <?php
                  $row_items = 2;
                  $result = $conn->query("SELECT sigla, materia, ordem  FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
                  if ($result->num_rows > 0) {
                    $count = 0;
                    while($row = $result->fetch_assoc()) {
                      if ($count == 0) { echo "<div class='col-lg-3 col-sm-12'>"; }
                      $count++;
                      $sigla = $row["sigla"];
                      $materia = $row["materia"];
                      echo "
                        <div href='materia.php?sigla=$sigla&concurso=$concurso' class='bg-lighter rounded bdark cardmateria text-break text-center align-middle my-3'>
                          <small class='text-muted text-uppercase smaller'>$materia</small>
                        </div>
                      ";
                      if ($count == $row_items) {
                        echo "</div>";
                        $count = 0;
                      }
                    }
                  }
                  $conn->close();
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
<?php
  bottom_page();
?>
