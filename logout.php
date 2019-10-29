  <?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (!isset($_SESSION['email'])) {
    header('Location:index.php');
  }
  else {
    session_unset();
    session_destroy();
  }

  include 'engine.php';
  top_page(false);
  ?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Ubwiki", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-sm-12">
          <h1>Logout realizado</h1>
          <p>Para retornar, por favor siga o link na <a href='https://www.grupoubique.com.br'>página do Grupo Ubique</a>.</p>
        </div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
</html>
