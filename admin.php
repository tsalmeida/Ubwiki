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
          <form class='text-center border border-light p-5'>
                <p class="h4 mb-4">Funções</p>
                <p>Reconstruir tabela de opções de barra de busca.</p>
                <button class="btn btn-info btn-block my-4" type='submit'>Reconstruir</button>
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
