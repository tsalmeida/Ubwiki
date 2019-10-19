<?php

  session_start();
  if (isset($_SESSION['email'])) {
    $user = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  top_page();

  $result = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $tipo = $row['tipo'];
      $criacao = $row['criacao'];
      $apelido = $row['apelido'];
      $nome = $row['nome'];
      $sobrenome = $row['sobrenome'];
    }
  }

?>
  <body>
    <?php
      carregar_navbar();
      standard_jumbotron("Sua página", false);
      if ($tipo == 'admin') {
        sub_jumbotron("Administrador", 'admin.php');
      }
      else {
        $tipo_capitalizado = ucfirst($tipo);
        sub_jumbotron($tipo_capitalizado, false);
      }
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-12">

          <?php
            echo "
              <h2>Dados da sua conta:</h2>
              <ul class='list-group'>
                <li class='list-group-item'>Conta criada em: $criacao</li>
                <li class='list-group-item'>Apelido: $apelido</li>
                <li class='list-group-item'>Nome: $nome</li>
                <li class='list-group-item'>Sobrenome: $sobrenome</li>
              </ul>
            ";
          ?>

        </div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
