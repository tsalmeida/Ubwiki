<?php

  if (isset($_SESSION['email'])) {
    session_start();
    $user = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  top_page();
  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
  }
  ?>
  <body>
    <?php
    standard_jumbotron("Edição de temas", false);
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <?php
            echo "<h1>$concurso</h1>";
          ?>
            <p class="h4 mb-4 text-center">Edição de temas</p>
            <?php
              carregar_edicao_temas($concurso);
             ?>
        </div>
        <div class="col-2"></div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
