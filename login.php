  <?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    header('Location:index.php');
  }

  include 'engine.php';
  top_page();
  ?>
  <body>
    <?php
    carregar_navbar();
    standard_jumbotron("Ubwiki", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-4 col-sm-12">
          <h1>Login necessário</h1>
          <p>Você não está logado. Para fazê-lo gratuitamente, por favor crie uma conta na <a href='https://www.grupoubique.com.br'>página do Grupo Ubique</a> e siga o link 'Ubwiki' no topo da página ou na sua lista de cursos.</p>
        </div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
