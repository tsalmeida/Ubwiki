<?php
include 'engine.php';
top_page("quill");
$concurso = "CACD";
?>
  <body>
    <?php carregar_navbar(); ?>
    <div class="container-fluid bg-white">
      <div class="row justify-content-center">
          <div class="col-lg-2 col-sm-5">
              <img class="img-fluid logo" src="imagens/ubiquelogo.png"></img>
          </div>
      </div>
      <div class="row text-center py-5">
        <div class="col-lg-12 col-sm-12">
          <?php echo "<p class='lead'>Bem-vindo à Ubwiki, o sistema mais inteligente de preparação para o $concurso.</p>"; ?>
        </div>
      </div>
      <div class="row justify-content-center py-5">
        <div class="col-lg-6 col-sm-12">
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
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-12">
          <?php
              $row_items = 4;
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
                  if ($count == 0) { echo "<div class='row justify-content-around'>"; }
                  $count++;
                  $sigla = $row["sigla"];
                  $materia = $row["materia"];
                  echo "
                    <div href='materia.php?sigla=$sigla&concurso=$concurso' class='col-lg-2 col-sm-12 bg-lighter rounded bdark cardmateria text-break text-center align-middle my-2'>
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
<?php
    load_footer();
?>
  </body>
<?php
  bottom_page();
?>
