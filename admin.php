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

  if (isset($_POST['otimizar_temas_concurso'])) {
    $concurso = $_POST['otimizar_temas_concurso'];
    $ordem = 0;
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso'");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $nivel1 = $row["nivel1"];
        $nivel2 = $row["nivel2"];
        $nivel3 = $row["nivel3"];
        $nivel4 = $row["nivel4"];
        $nivel5 = $row["nivel5"];
        if ($nivel5 != false) {
          $conn->query("UPDATE Temas SET nivel = 5 WHERE id = '$id'");
        }
        elseif ($nivel4 != false) {
          $conn->query("UPDATE Temas SET nivel = 4 WHERE id = '$id'");
        }
        elseif ($nivel3 != false) {
          $conn->query("UPDATE Temas SET nivel = 3 WHERE id = '$id'");
        }
        elseif ($nivel2 != false) {
          $conn->query("UPDATE Temas SET nivel = 2 WHERE id = '$id'");
        }
        else {
          $conn->query("UPDATE Temas SET nivel = 1 WHERE id = '$id'");
        }
      }
    }
  }

  if (isset($_POST['reconstruir_concurso'])) {
    $concurso = $_POST['reconstruir_concurso'];
    $ordem = 0;
    $conn->query("DELETE FROM Searchbar WHERE concurso = '$concurso'");
    $result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $sigla = $row["sigla"];
        $materia = $row["materia"];
        $ordem++;
        $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', '$sigla', '$materia', 'materia')");
      }
    }
    $result = $conn->query("SELECT nivel1, nivel2, nivel3, nivel4, nivel5, id FROM Temas WHERE concurso = '$concurso' ORDER BY id");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $nivel1 = $row["nivel1"];
        $nivel2 = $row["nivel2"];
        $nivel3 = $row["nivel3"];
        $nivel4 = $row["nivel4"];
        $nivel5 = $row["nivel5"];
        $ordem++;
        if ($nivel5 != false) {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel5', 'tema')");
        }
        elseif ($nivel4 != false) {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel4', 'tema')");
        }
        elseif ($nivel3 != false) {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel3', 'tema')");
        }
        elseif ($nivel2 != false) {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel2', 'tema')");
        }
        else {
          $conn->query("INSERT INTO Searchbar (ordem, concurso, sigla, chave, tipo) VALUES ('$ordem', '$concurso', $id, '$nivel1', 'tema')");
        }
      }
    }
  }

  if (isset($_POST['metalinguagem_concurso'])) {
    $metalinguagem_concurso = $_POST['metalinguagem_concurso'];
    header("Location:edicao_temas.php?concurso=$metalinguagem_concurso");
  }

  $admin_mensagens = false;

  if (isset($_POST['quill_nova_mensagem_html'])) {
    $nova_mensagem = $_POST['quill_nova_mensagem_html'];
    $conn->query("INSERT INTO Admin_data (tipo, conteudo) VALUES ('notas', '$nova_mensagem')");
    $admin_mensagens = $nova_mensagem;
  }
  else {
    $result = $conn->query("SELECT conteudo FROM Admin_data WHERE tipo = 'notas' ORDER BY id DESC");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $admin_mensagens = $row['conteudo'];
        break;
      }
    }
  }

  if (isset($_POST['novo_concurso_titulo'])) {
    $novo_concurso_titulo = $_POST['novo_concurso_titulo'];
    $novo_concurso_sigla = $_POST['novo_concurso_sigla'];
    $result = $conn->query("SELECT titulo, sigla FROM Concursos WHERE sigla = '$novo_concurso_sigla' AND titulo = '$novo_concurso_titulo'");
    if ($result->num_rows == 0) {
      $conn->query("INSERT INTO Concursos (titulo, sigla) VALUES ('$novo_concurso_titulo', '$novo_concurso_sigla')");
    }
    else {
      return false;
    }
  }
  $lista_concursos = array();
  $result = $conn->query("SELECT titulo, sigla, estado FROM Concursos");
  while ($row = $result->fetch_assoc()) {
    $sigla = $row['sigla'];
    $titulo = $row['titulo'];
    $estado = $row['estado'];
    $instance = array($sigla,$titulo,$estado);
    array_push($lista_concursos, $instance);
    $check = serialize($lista_concursos);
  }

  top_page("quill_admin");
  ?>
  <body>
    <?php
      carregar_navbar('dark');
      standard_jumbotron("Página dos Administradores", false);
      echo "<p>$check</p>";
    ?>
    <div class="container-fluid my-5 px-3">
      <div class="row">
        <div class="col-lg-6 col-sm-12">
          <form class='text-center border border-light p-5 my-2' method='post' formaction='edicao_temas.php'>
              <p class="h4 mb-4">Editar tópicos</p>
              <p class='text-left'>Com esta ferramenta, o administrador pode alterar a tabela de tópicos de um concurso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-2 pt-0">Concurso</legend>
                  <div class="col-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="metalinguagem_concurso" value="CACD" checked>
                      <label class="form-check-label" for="gridRadios1">
                        CACD
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>
            <button class='btn btn-primary btn-block my-4' type='submit'>Acessar ferramenta</button>
          </form>
          <form class='text-center border border-light p-5 my-2' method='post' formaction='edicao_temas.php'>
              <p class="h4 mb-4">Acrescentar concurso</p>
              <p class='text-left'>Cada concurso tem um título completo e uma sigla. Este é o primeiro passo no processo de inclusão de novos concursos.</p>
              <fieldset class="form-group">
                <div class="row">
                  <input type='text' id='novo_concurso_titulo' name='novo_concurso_titulo' class='form-control validate' required>
                  <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_concurso_titulo'>Título do concurso</label>
                </div>
                <div class="row">
                  <input type='text' id='novo_concurso_sigla' name='novo_concurso_sigla' class='form-control validate' required>
                  <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_concurso_sigla'>Sigla do concurso</label>
                </div>
              </fieldset>
            <button class='btn btn-primary btn-block my-4' type='submit'>Acrescentar concurso</button>
          </form>
          <form class='text-center border border-light p-5 my-2' method='post'>
              <p class="h4 mb-4">Barra de busca</p>
              <p>Reconstruir tabela de opções da barra de busca.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-2 pt-0">Concurso</legend>
                  <div class="col-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="reconstruir_concurso" value="CACD" checked>
                      <label class="form-check-label" for="gridRadios1">
                        CACD
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>
            <button class='btn btn-primary btn-block my-4' type='submit'>Reconstruir</button>
          </form>
          <form class='text-center border border-light p-5 my-2' method='post'>
              <p class="h4 mb-4">Otimizar tabela de tópicos</p>
              <p>Essa ferramenta determina o nível relevante de cada entrada na tabela de tópicos, de 1 a 5.</p>
              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-2 pt-0">Concurso</legend>
                  <div class="col-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="otimizar_temas_concurso" value="CACD" checked>
                      <label class="form-check-label" for="gridRadios1">
                        CACD
                      </label>
                    </div>
                  </div>
                </div>
              </fieldset>
            <button class='btn btn-primary btn-block my-4' type='submit'>Otimizar</button>
          </form>
        </div>
        <div class='col-lg-6 col-sm-12'>
          <div class='text-center border border-light p-5 my-2'>
            <p class="h4 mb-4">Notas dos administradores</p>
            <p class='text-left'>Estas anotações são compartilhadas entre todos os administradores, por exemplo, para registrar idéia de melhorias futuras para a página.</p>
            <div class='row'>
              <div class='col-12 border text-left p-3' style='height: 40rem; overflow-y: auto;'>
                <?php echo "$admin_mensagens"; ?>
              </div>
            </div>
            <button class='btn btn-primary btn-block my-4' data-toggle='modal' data-target='#modal_notas_admin'>Editar notas</button>
          </div>
        </div>
      </div>
    </div>

    <div class='modal fade' id='modal_notas_admin' role='dialog' tabindex='-1'>
      <div class='modal-dialog modal-lg quill_modal' role='document'>
        <div class='modal-content'>
          <form id='quill_admin_form' method='post'>
            <input name='quill_nova_mensagem_html' type='hidden'>
            <div class='modal-header text-center'>
              <h4 class='modal-title w-100 font-weight-bold'>Notas dos administradores</h4>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="fal fa-times-circle"></i>
              </button>
            </div>
            <div class='modal-body'>
              <div class='row justify-content-center'>
                <div class='container col-12 justify-content-center'>
                  <?php
                    echo "
                      <div id='quill_container_admin' class='quill_container_modal'>
                        <div id='quill_editor_admin'>
                          $admin_mensagens
                        </div>
                      </div>
                    ";
                  ?>
                </div>
              </div>
            </div>
            <div class='modal-footer d-flex justify-content-center mt-5'>
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
  bottom_page("quill_admin");
?>
