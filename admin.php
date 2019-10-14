  <?php
  include 'engine.php';
  top_page();
  ?>
  <body>
    <?php
    standard_jumbotron();
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <h1>Administrador</h1>
          <form class='text-center border border-light p-5' method='post'>
              <p class="h4 mb-4">Tabela de temas: metalinguagem e organização</p>
              <p class='text-left'>Utiliza-se esta ferramenta para alterar a estrutura de temas do edital dos concursos. Alguns temas do edital são muito vagos, exigindo que sejam criadas novas sub-entradas. Outros incluem vários temas ao mesmo tempo, precisando ser divididos em várias entradas. É necessário, ainda, determinar uma metalinguagem permanente, que seja independente do texto do edital, que será reconhecida pelo sistema como correspondente àquele tema.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Concurso</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="reconstruir_concurso" value="CACD" checked>
                      <label class="form-check-label" for="gridRadios1">
                        CACD
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>
            <?php
            echo "<button class='btn btn-info btn-block my-4' type='submit'>Reconstruir</button>";
            ?>
          </form>
          <form class='text-center border border-light p-5' method='post'>
              <p class="h4 mb-4">Funções</p>
              <p>Reconstruir tabela de opções da barra de busca.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Concurso</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="reconstruir_concurso" value="CACD" checked>
                      <label class="form-check-label" for="gridRadios1">
                        CACD
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>
            <?php
            echo "<button class='btn btn-info btn-block my-4' type='submit'>Reconstruir</button>";
            ?>
          </form>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
