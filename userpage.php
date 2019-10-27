<?php

  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  top_page(false);

  $result = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user_email'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $user_id = $row['id'];
      $user_tipo = $row['tipo'];
      $user_criacao = $row['criacao'];
      $user_apelido = $row['apelido'];
      $user_nome = $row['nome'];
      $user_sobrenome = $row['sobrenome'];
    }
  }

  if (isset($_POST['novo_nome'])) {
    $novo_user_nome = $_POST['novo_nome'];
    $novo_user_sobrenome = $_POST['novo_sobrenome'];
    $novo_user_apelido = $_POST['novo_apelido'];
    $result = $conn->query("UPDATE Usuarios SET nome = '$novo_user_nome', sobrenome = '$novo_user_sobrenome', apelido = '$novo_user_apelido' WHERE id = $user_id");
  }

?>
  <body>
    <?php
      carregar_navbar('dark');
      standard_jumbotron("Sua página", false);
      if ($user_tipo == 'admin') {
        sub_jumbotron("Administrador", 'admin.php');
      }
      else {
        $tipo_capitalizado = ucfirst($user_tipo);
        sub_jumbotron($tipo_capitalizado, false);
      }
    ?>
    <div class="container-fluid my-5">
      <div class="row d-flex justify-content-around">
        <div class="col-lg-5 col-sm-12">
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h4>Dados da sua conta</h4>
              <h4><a data-toggle='modal' data-target='#modal_editar_dados' href=''><i class='fal fa-edit'></i></a></h4>
            </div>
          </div>
          <ul class='list-group'>
<?php
            echo "
              <li class='list-group-item'><strong>Conta criada em:</strong> $user_criacao</li>
              <li class='list-group-item'><strong>Apelido:</strong> $user_apelido</li>
              <li class='list-group-item'><strong>Nome:</strong> $user_nome</li>
              <li class='list-group-item'><strong>Sobrenome:</strong> $user_sobrenome</li>
              <li class='list-group-item'><strong>Email:</strong> $user_email</li>
            ";
?>
          </ul>
        </div>
        <div class='col-lg-5 col-sm-12'>
          <h4>Lista de leitura</h4>
          <ul class='list-group'>
<?php
            $result = $conn->query("SELECT tema_id FROM Bookmarks WHERE user_id = $user_id");
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $tema_id = $row['tema_id'];
                $info_temas = $conn->query("SELECT concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 WHERE id = $tema_id");
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    $concurso = $row['concurso'];
                    $sigla_materia = $row['sigla_materia'];
                    $nivel = $row['nivel'];
                    $nivel1 = $row['nivel1'];
                    $nivel2 = $row['nivel2'];
                    $nivel3 = $row['nivel3'];
                    $nivel4 = $row['nivel4'];
                    $nivel5 = $row['nivel5'];
                    if ($nivel == 1) { $titulo = $nivel1; }
                    elseif ($nivel == 2) { $titulo = $nivel2; }
                    elseif ($nivel == 3) { $titulo = $nivel3; }
                    elseif ($nivel == 4) { $titulo = $nivel4; }
                    else { $titulo = $nivel5; }
                    echo "<li class='list-group-item'>$titulo</li>";
                  }
                }
              }
            }
?>
          </ul>
        </div>
      </div>
    </div>
    <div id='modal_editar_dados' class='modal fade' role='dialog' tabindex='-1'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
          <form method='post'>
            <div class='modal-header text-center'>
              <h4 class='modal-title w-100 font-weight-bold'>Alterar dados</h4>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="fal fa-times-circle"></i>
              </button>
            </div>
            <div class='modal-body mx-3 pb-0'>
              <p>Você é identificado por seu apelido em todas as circunstâncias da página em que sua participação ou contribuição sejam tornadas públicas.</p>
              <div class='md-form md-2'>
<?php
                echo "<input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' required></input>";
?>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_apelido' required>Apelido</label>
              </div>
            </div>
            <div class='modal-body mx-3 pb-0'>
              <p>Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.</p>
              <div class='md-form md-2'>
<?php
                echo "<input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' required></input>";
?>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_nome'>Nome</label>
              </div>
            </div>
            <div class='modal-body mx-3 pb-0'>
              <div class='md-form md-2'>
<?php
                echo "<input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required></input>";
?>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_sobrenome' required>Sobrenome</label>
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
