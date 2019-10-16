<?php
include 'engine.php';
top_page("quill");
$concurso = "CACD";
?>
  <body>
    <?php carregar_navbar(); ?>
    <div class="container text-center bg-white justify-content-center">
      <div class="row justify-content-center">
          <div class="col-lg-2 col-sm-5">
              <img class="img-fluid logo" src="imagens/ubiquelogo.png"></img>
          </div>
      </div>
      <div class="row justify-concent-center">
        <div class="col-lg-12 col-sm-12">
          <?php echo "<p class='lead'>Bem-vindo à Ubwiki, o sistema mais inteligente de preparação para o $concurso.</p>"; ?>
        </div>
      </div>
      <div class="row justify-content-center">
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
        <div class="col-lg-10 col-sm-12">
          <?php
              ler_cartoes($concurso, 4);
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
