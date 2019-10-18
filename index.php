<?php

  include 'engine.php';

  $newuser = false;
  session_start();
  if (!isset($_SESSION['email'])) {
    if (isset($_GET['email'])) {
      $_SESSION['email'] = $_GET['email'];
      $user = $_SESSION['email'];
      $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user'");
      if ($result->num_rows == 0) {
        $newuser = true;
        $insert = $conn->query("INSERT INTO Usuarios (email) VALUES ('$user')");
      }
    }
    else {
      header('Location:login.php');
    }
  }

  $result = $conn->query("SELECT id, criacao FROM Usuarios WHERE email = '$user'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $usuario_id = $row['id'];
      $usuario_criacao = $row['criacao'];
    }
  }

  if ($newuser == true) {
    $create = $conn2->query("CREATE TABLE `Ubwiki_usuarios`.`$usuario_id` ( `id` INT NOT NULL AUTO_INCREMENT , `tipo` VARCHAR(255) NOT NULL , `tipo_conteudo` VARCHAR(255) NOT NULL , `conteudo_varchar` VARCHAR(255) NOT NULL , `conteudo_text` TEXT NOT NULL , `conteudo_timestamp` TIMESTAMP NOT NULL , `conteudo_boolean` BOOLEAN NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM COMMENT = 'tabela de $user';");
    error_log($usuario_criacao);
    $create = $conn2->query("INSERT INTO $usuario_id (tipo, tipo_conteudo, conteudo_timestamp) VALUES ('criacao', 'timestamp', '$usuario_criacao')")
    $create = $conn2->query("INSERT INTO $usuario_id (tipo, tipo_conteudo, conteudo_timestamp) VALUES ('email', 'varchar', '$user')")
    $create = $conn2->query("INSERT INTO 1 (tipo, tipo_conteudo, conteudo_varchar) VALUES ('teste', 'varchar', 'teste1')")
    $create = $conn2->query("INSERT INTO $usuario_id (tipo, tipo_conteudo, conteudo_texto) VALUES ('teste', 'texto', 'teste2')")
  }

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

?>
  <body>
    <div class='container-fluid px-0 onepage'>
      <?php
        carregar_navbar();
      ?>
      <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-sm-5 px-3 mt-5">
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
                      <div href='materia.php?sigla=$sigla&concurso=$concurso' class='bg-lighter rounded bdark cardmateria text-break text-center align-middle mb-3'>
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
