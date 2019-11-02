  <?php
  $sessionpath = realpath(dirname($_SERVER['DOCUMENT_ROOT']));
  $sessionpath .= '/htdocs/sessions';
  session_save_path($sessionpath);
  session_start();
  if (isset($_SESSION['email'])) {
    header('Location:index.php');
  }

  include 'engine.php';
  top_page(false);
  ?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Ubwiki", false);
    ?>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-sm-12">
          <h1 class="text-center">Login necessário</h1>
          <p>Você não está logado. Para fazê-lo gratuitamente, por favor crie uma conta na <a href='https://www.grupoubique.com.br'>página do Grupo Ubique</a> e siga o link 'Ubwiki' no topo da página ou na sua lista de cursos.</p>
            <?php phpinfo(); ?>
        </div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
</html>
