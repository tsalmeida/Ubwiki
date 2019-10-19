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

  $result = $conn->query("SELECT id, tipo, criacao, apelido, senha, nome, sobrenome, concursos FROM Usuarios WHERE email = '$user'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $tipo = $row['tipo'];
      $apelido = $row['apelido'];
      $nome = $row['nome'];
      $sobrenome = $row['sobrenome'];
      $concursos = $row['concursos'];
    }
  }

?>
  <body>
    <?php
      carregar_navbar();
      standard_jumbotron("Sua página", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row">
        <div class="col-8">

          <?php
            if ($tipo = 'admin') {
              echo "
                <h2>Administrador</h2>
                <p>Você tem acesso à <a href='admin.php'>página de administrador</a>.</p>
              ";
            }
            echo "
              <h2>Dados da sua conta:</h2>
              <ul class='list-group'>
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
