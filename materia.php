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
    standard_jumbotron();
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
  <footer class="container-fluid text-center bg-lighter text-dark py-2 height5vh align-bottom">
    <p class="mb-0">A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Clique <a href="termos.php" target="_blank">aqui</a> para rever os termos e condições de uso da página.</p>
  </footer>
  <?php
    bottom_page();
  ?>
