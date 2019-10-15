<?php
include 'engine.php';
top_page();
$concurso = "CACD";
?>
  <body>
    <?php carregar_navbar(); ?>
    <div class="container-fluid text-center bg-white justify-content-center home1">
      <div class="row justify-content-center">
          <div class="col-lg-2">
              <img class="img-fluid logo" src="imagens/ubiquelogo.png"></img>
          </div>
      </div>
      <div class="row justify-concent-center">
        <div class="col-lg-12">
          <?php echo "<p class='lead'>Bem-vindo à Ubwiki, o sistema mais inteligente de preparação para o $concurso.</p>"; ?>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-6">
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
    </div>
    <div class="container-fluid text-center bg-white home2 pt-5">
      <div class="row justify-content-center">
        <div class="col-lg-10">
            <?php
                ler_cartoes($concurso, 4);
            ?>
        </div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
