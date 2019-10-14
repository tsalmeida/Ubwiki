  <?php
  include 'engine.php';
  top_page();
  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
  }
  ?>
  <body>
    <?php
    standard_jumbotron();
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <?php
            echo "<h1>Edição de temas: $concurso</h1>";
          ?>
          <form class='text-center border border-light p-5' method='post'>
              <p class="h4 mb-4">Tabela de temas: metalinguagem e organização</p>
              <p class='text-left'>Utiliza-se esta ferramenta para alterar a estrutura de temas do edital dos concursos. Alguns temas do edital são muito vagos, exigindo que sejam criadas novas sub-entradas. Outros incluem vários temas ao mesmo tempo, precisando ser divididos em várias entradas. É necessário, ainda, determinar uma metalinguagem permanente, que seja independente do texto do edital, que será reconhecida pelo sistema como correspondente àquele tema.</p>
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
