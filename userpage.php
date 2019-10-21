<?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
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
        <div class="col-lg-4 col-sm-12">
          <?php
            echo "
              <h2 class='text-center'>Dados da sua conta:</h2>
              <ul class='list-group'>
                <li class='list-group-item'><strong>Conta criada em:</strong> $criacao</li>
                <li class='list-group-item'><a data-toggle='modal' data-target='#modal_editar_apelido' href=''><i class='fal fa-edit'></i></a> <strong>Apelido:</strong> $apelido</li>
                <li class='list-group-item'><a href=''><i class='fal fa-edit'></i></a> <strong>Nome:</strong> $nome</li>
                <li class='list-group-item'><a href=''><i class='fal fa-edit'></i></a> <strong>Sobrenome:</strong> $sobrenome</li>
                <li class='list-group-item'><strong>Email:</strong> $user</li>
              </ul>
            ";
          ?>
        </div>
      </div>
    </div>
    <div id='modal_editar_apelido' class='modal fade' role='dialog' tabindex='-1'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <form method='post'>
            <div class='modal-header text-center'>
              <h4 class='modal-title w-100 font-weight-bold'>Alterar apelido</h4>
              <div class='row'>
                <p>Seu apelido é sua identificação nos fórums de debate na página de cada verbete.</p>
              </div>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="fal fa-times-circle"></i>
              </button>
            </div>
            <div class='modal-body mx-3'>
              <div class='md-form md-2'>
                <input type='text' name='novo_apelido' class='form-control validate'></input>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_link_link' required>Novo apelido</label>
              </div>
            </div>
            <div class='modal-footer d-flex justify-content-center'>
              <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
              <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
