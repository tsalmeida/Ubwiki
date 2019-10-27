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

  if (isset($_POST['reconstruir_busca'])) {
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

  if (isset($_POST['editar_topicos_concurso'])) {
    $metalinguagem_concurso = $_POST['editar_topicos_concurso'];
    header("Location:edicao_topicos.php?concurso=$metalinguagem_concurso");
  }

  $admin_mensagens = false;

  if (isset($_POST['quill_nova_mensagem_html'])) {
    $nova_mensagem = $_POST['quill_nova_mensagem_html'];
    $nova_mensagem = strip_tags($nova_mensagem, '<p><li><ul><ol><h2><blockquote><em><sup><s>');
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
    $um_concurso = array($sigla,$titulo,$estado);
    array_push($lista_concursos, $um_concurso);
  }

  top_page(false, "quill_admin");
  ?>
  <body>
    <?php
      carregar_navbar('dark');
      standard_jumbotron("Página dos Administradores", false);
    ?>
    <div class="container-fluid my-5 px-3">
      <div class="row justify-content-around">
        <div class="col-lg-5 col-sm-12">
          <form class='text-center border border-light p-5 my-2' method='post' formaction='edicao_topicos.php'>
              <h4 class='mb-4'>Editar tópicos</h4>
              <p class='text-left'>Com esta ferramenta, o administrador pode alterar a tabela de tópicos de um concurso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
              <div class="form-group">
                <label for="editar_topicos_concurso">Concurso</label>
                <select class="form-control" name='editar_topicos_concurso'>
<?php
                  foreach ($lista_concursos as $um_concurso) {
                    if ($um_concurso[2] == 0) { $estado = '(desativado)'; } else { $estado = '(ativado)'; }
                    echo "<option value='$um_concurso[0]'>$um_concurso[1] / $estado</option>";
                  }
?>
                </select>
              </div>
            <button class='btn btn-primary btn-block my-4' type='submit'>Acessar ferramenta</button>
          </form>

          <form class='text-center border border-light p-5 my-2' method='post' formaction='edicao_topicos.php'>
            <h4 class='mb-4'>Acrescentar concurso</h4>
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
              <h4 class='mb-4'>Barra de busca</h4>
              <p>Reconstruir tabela de opções da barra de busca.</p>
              <div class="form-group">
                <label for="editar_topicos_concurso">Concurso</label>
                <select class="form-control" name="reconstruir_concurso" id='reconstruir_concurso'>
<?php
                  foreach ($lista_concursos as $um_concurso) {
                    if ($um_concurso[2] == 0) { $estado = '(desativado)'; } else { $estado = '(ativado)'; }
                    echo "<option value='$um_concurso[0]'>$um_concurso[1] / $estado</option>";
                  }
?>
                </select>
              </div>
            <button class='btn btn-primary btn-block my-4' type='submit' name='reconstruir_busca'>Reconstruir</button>
          </form>
          <form class='text-center border border-light p-5 my-2' method='post'>
            <h4 class='mb-4'>Otimizar tabela de tópicos</h4>
            <p>Essa ferramenta determina o nível relevante de cada entrada na tabela de tópicos, de 1 a 5.</p>
            <div class="form-group">
              <label for="editar_topicos_concurso">Concurso</label>
              <select class="form-control" name="otimizar_temas_concurso">
<?php
                foreach ($lista_concursos as $um_concurso) {
                  if ($um_concurso[2] == 0) { $estado = '(desativado)'; } else { $estado = '(ativado)'; }
                  echo "<option value='$um_concurso[0]'>$um_concurso[1] $estado</option>";
                }
?>
              </select>
            </div>
            <button class='btn btn-primary btn-block my-4' type='submit'>Otimizar</button>
          </form>
        </div>
        <div class='col-lg-5 col-sm-12'>
          <h4>Notas dos administradores</h4>
          <p>Estas anotações são compartilhadas entre todos os administradores, por exemplo, para registrar idéia de melhorias futuras para a página.</p>
          <form id='quill_admin_form' method='post'>
            <input name='quill_nova_mensagem_html' type='hidden'>
              <div class='row d-flex justify-content-center'>
                <div class='container col-12 d-flex justify-content-center'>
                  <?php
                    echo "
                      <div id='quill_container_admin'>
                        <div id='quill_editor_admin'>
                          $admin_mensagens
                        </div>
                      </div>
                    ";
                  ?>
                </div>
              </div>
            <div class='row d-flex justify-content-center mt-5'>
              <button type='button' class='btn bg-lighter btn-lg'><i class="fal fa-times-circle"></i> Cancelar</button>
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
