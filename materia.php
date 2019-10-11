  <?php
  include 'engine.php';
  top_page();

// else {
//   $materia = "geral";
//   header("materias.php?materia=$materia&concurso=$concurso");
// }


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
            if ($materia == "geral") {
              echo "<h1>Matérias do Edital</h1>";
            }
            else {
              echo "<h1>Página de $materia</h1>";
            }
          ?>
          <p>Nesta página, links para todos os verbetes, destaques etc.</p>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
