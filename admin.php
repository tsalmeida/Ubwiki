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
              <p class="h4 mb-4">Funções</p>
              <p>Reconstruir tabela de opções de barra de busca.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Concurso</legend>
                  <div class="custom-control custom-radio mb-4">
                    <input type="radio" class="custom-control-input" name="reconstruir_concurso" value="CACD">
                    <label class="custom-control-label" for="radio">CACD</label>
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
