  <?php
  include 'engine.php';
  top_page();

// else {
//   $materia = "geral";
//   header("materias.php?materia=$materia&concurso=$concurso");
// }

if (isset($_GET['sigla'])) {
  $sigla = $_GET['sigla'];
}

if (isset($_GET['concurso'])) {
  $concurso = $_GET['concurso'];
}

  ?>
  <body>
    <?php
    carregar_navbar();
    standard_jumbotron($sigla);
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <?php carregar_pagina($sigla, $concurso); ?>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div>
  </body>
  <?php
    load_footer();
    bottom_page();
  ?>
