<?php

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
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $ordem = 0;
    $conn = new mysqli($servername, $username, $password, $dbname);
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
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $ordem = 0;
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
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

  function carregar_edicao_temas($concurso) {
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $found = false;
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("SELECT id, sigla_materia, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND ciclo_revisao = 0 ORDER BY id");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $active1 = false;
        $active2 = false;
        $active3 = false;
        $active4 = false;
        $active5 = false;
        $id = $row['id'];
        $sigla_materia = $row['sigla_materia'];
        $nivel1 = $row['nivel1'];
        $nivel2 = $row['nivel2'];
        $nivel3 = $row['nivel3'];
        $nivel4 = $row['nivel4'];
        $nivel5 = $row['nivel5'];
        if ($nivel5 != false) { $active5 = 'active'; }
        elseif ($nivel4 != false) { $active4 = 'active'; }
        elseif ($nivel3 != false) { $active3 = 'active'; }
        elseif ($nivel2 != false) { $active2 = 'active'; }
        else { $active1 = 'active'; }
        echo "
          <ul class='list-group text-left'>
            <li class='list-group-item'><strong>MATERIA: </strong>$sigla_materia</li>
            <li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>
            <li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>
            <li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>
            <li class='list-group-item $active4'><strong>Nível 2: </strong>$nivel4</li>
            <li class='list-group-item $active5'><strong>Nível 3: </strong>$nivel5</li>
            <li class='list-group-item'><strong>ID: </strong>$id</li>
          </ul>";
        echo "
          <form class='text-center border border-light px-2 my-2' method='post'>
            <p class='h4 my-4'>Re-iniciar ciclo de revisão</p>
            <p class='text-left'>Ao pressionar o botão abaixo, todas as questões deste concurso serão colocadas no ciclo de revisão.</p>
            <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
          </form>

        ";
        return;
      }
    }
  }

  if (isset($_POST['reiniciar_ciclo'])) {
    $concurso = $_POST['reiniciar_ciclo'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $found = false;
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['metalinguagem_concurso'])) {
    $metalinguagem_concurso = $_POST['metalinguagem_concurso'];
    header("Location:edicao_temas.php?concurso=$metalinguagem_concurso");
  }

  top_page();
  ?>
  <body>
    <?php
      carregar_navbar();
      standard_jumbotron("Página de Administrador", false);
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <form class='text-center border border-light p-5' method='post' formaction='edicao_temas.php'>
              <p class="h4 mb-4">Editar tabela de temas</p>
              <p class='text-left'>Com esta ferramenta, o administrador pode alterar a tabela de temas de um concurso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
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
            <button class='btn btn-info btn-block my-4' type='submit'>Acessar ferramenta</button>
          </form>
          <form class='text-center border border-light p-5' method='post'>
              <p class="h4 mb-4">Funções</p>
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
            <button class='btn btn-info btn-block my-4' type='submit'>Reconstruir</button>
          </form>
          <form class='text-center border border-light p-5' method='post'>
              <p class="h4 mb-4">Otimizar tabela de temas</p>
              <p>Essa ferramenta determina o nível relevante de cada entrada na tabela de temas, de 1 a 5.</p>
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
            <button class='btn btn-info btn-block my-4' type='submit'>Otimizar</button>
          </form>
        </div>
        <div class="col-2"></div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
