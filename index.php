<?php
include 'engine.php';
top_page();
$concurso = "CACD";
?>
  <body>
    <nav class="navbar navbar-expand-lg bg-white height10vh py-0">
      <a class="navbar-brand align-top" href="index.php"><h2>Ubwiki</h2></a>
      <ul class="nav navbar-nav ml-auto">
        <li><a class="navlink float-right h5 align-top" href="userpage.php">Minha conta</a></li>
      </ul>
    </nav>
    <div class="container-fluid text-center bg-white justify-content-center height50vh">
      <div class="row justify-content-center">
          <div class="col-sm-2">
              <img class="img-fluid logo" src="imagens/ubiquelogo.png"></img>
          </div>
      </div>
      <div class="row justify-concent-center">
        <div class="col-sm-12">
          <?php echo "<p class='lead'>Bem-vindo à Ubwiki, o sistema mais inteligente de preparação para o $concurso.</p>"; ?>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <form id="searchform" action="" method="post">
            <div id="searchDiv">
              <input id="searchbar" list="searchlist" type="text" class="searchbar" name="searchbar" rows="1" autocomplete="off" spellcheck="false" placeholder="o que você vai estudar hoje?" required></input>
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
    </div>
    <div class="container-fluid text-center bg-white height30vh pt-5">
      <div class="row justify-content-center">
        <div class="col-lg-10">
            <?php
                ler_cartoes($concurso, 4);
            ?>
        </div>
      </div>
    </div>

    <!-- <div class="container my-5 bg-lighter py-5 col-sm-12">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 bg-white py-3">
          <form method="GET" action="https://creator.zoho.com/api/json/ubique/view/ETIQUETAS_RPT">
            <div class="form-group">
              <label>authtoken</label>
              <input class="form-control mb-4" name ="authtoken" value="10abe2d1aae94281cff517bb019aea2b" type="text"></input>
            </div>
            <div class="form-group">
              <label>zc_ownername</label>
              <input class="form-control mb-4" name ="zc_ownername" value="marciliofilho" type="text"></input>
            </div>
            <div class="form-group">
              <label>criteria</label>
              <input class="form-control mb-4" name="criteria" value='(materia.materia == "Geografia")' type="text"></input>
            </div>
            <div class="form-group">
              <label>scope</label>
              <input class="form-control mb-4" name ="scope" id="scope" value="creatorapi" type="text"></input>
            </div>
            <button type="submit">Enviar</button>
          </form>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div> -->

    <!-- <div class="container my-5 bg-lighter py-5 col-sm-12">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 bg-white py-3"> -->
          <?php
            // $result = extract_zoho("https://creator.zoho.com/api/xml/ubique/view/ETIQUETAS_RPT", "10abe2d1aae94281cff517bb019aea2b", "marciliofilho", "Geografia", "creatorapi");
            // echo "<p>$result</p>";
           ?>
        <!-- </div>
        <div class="col-sm-2"></div>
      </div>
    </div> -->

    <!-- <div class="container bg-lighter py-5 col-sm-12" id="verbetes">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="list-group col-sm-8 pr-0"> -->
          <?php
              // ler_edital("Cultura Geral");
           ?>
        <!-- </div>
        <div class="col-sm-2"></div>
      </div>
    </div> -->

    <footer class="container-fluid text-center bg-lighter text-dark py-2 height5vh align-bottom">
      <p class="mb-0">A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Clique <a href="termos.php" target="_blank">aqui</a> para rever os termos e condições de uso da página.</p>
    </footer>
  </body>

<?php
  bottom_page();
?>
