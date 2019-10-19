<?php
  $concurso = 'CACD';
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
  else {
    $user = $_SESSION['email'];
  }

  $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $usuario_id = $row['id'];
    }
  }

  $nova_tabela = "usuario_id_";
  $nova_tabela .= $usuario_id;
  $nova_tabela_arquivo = $nova_tabela;
  $nova_tabela_arquivo .= "_arquivo";

  if ($newuser == true) {
    $create = $conn2->query("CREATE TABLE `Ubwiki_usuarios`.`$nova_tabela` ( `id` INT NOT NULL AUTO_INCREMENT , `tipo` VARCHAR(255) NOT NULL , `tipo2` VARCHAR(255) NOT NULL , `tipo3` VARCHAR(255) NOT NULL , `tipo_conteudo` VARCHAR(255) NOT NULL , `tipo_conteudo2` VARCHAR(255) NOT NULL , `tipo_conteudo3` VARCHAR(255) NOT NULL , `conteudo_varchar` VARCHAR(255) NOT NULL , `conteudo_varchar2` VARCHAR(255) NOT NULL , `conteudo_varchar3` VARCHAR(255) NOT NULL , `conteudo_texto` TEXT NOT NULL , `conteudo_boolean` BOOLEAN NOT NULL , `timestamp` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM COMMENT = 'tabela de $user';");
    $create = $conn2->query("CREATE TABLE `Ubwiki_usuarios`.`$nova_tabela_arquivo` ( `id` INT NOT NULL AUTO_INCREMENT , `tipo` VARCHAR(255) NOT NULL , `tipo2` VARCHAR(255) NOT NULL , `tipo3` VARCHAR(255) NOT NULL , `tipo_conteudo` VARCHAR(255) NOT NULL , `tipo_conteudo2` VARCHAR(255) NOT NULL , `tipo_conteudo3` VARCHAR(255) NOT NULL , `conteudo_varchar` VARCHAR(255) NOT NULL , `conteudo_varchar2` VARCHAR(255) NOT NULL , `conteudo_varchar3` VARCHAR(255) NOT NULL , `conteudo_texto` TEXT NOT NULL , `conteudo_boolean` BOOLEAN NOT NULL , `timestamp` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM COMMENT = 'arquivos da tabela de $user';");
    $insert = $conn2->query("INSERT INTO $nova_tabela (tipo, tipo_conteudo) VALUES ('criacao', 'timestamp')");
    $insert = $conn2->query("INSERT INTO $nova_tabela (tipo, tipo_conteudo, conteudo_varchar) VALUES ('email', 'varchar', '$user')");
    $insert = $conn2->query("INSERT INTO $nova_tabela (tipo, tipo_conteudo, conteudo_varchar) VALUES ('concurso', 'varchar', '$concurso')");
  }

  top_page("onepage");

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
                    $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' ORDER BY ordem");
                    if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        $chave = $row['chave'];
                        echo "<option>$chave</option>";
                      }
                    }
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
