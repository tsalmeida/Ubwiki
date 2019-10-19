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
  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
  }

  if (isset($_POST['reiniciar_ciclo'])) {
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['finalizar_ciclo'])) {
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['ciclo_materia_adicionar'])) {
    $materia_revisao = $_POST['ciclo_materia'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
  }

  if (isset($_POST['ciclo_materia_remover'])) {
    $materia_revisao = $_POST['ciclo_materia'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
  }

  ?>
  <body>
    <?php
    standard_jumbotron("Edição de temas", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-12">
          <?php
            echo "<h1 class='text-center'>$concurso</h1>
                  <form class='border boder-light p-4 my-2' method='post'>
                  <h2 class='text-center'>Edição de temas</h2>";
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
                  <ul class='list-group p-4 my-2'>
                    <li class='list-group-item'><strong>MATERIA: </strong>$sigla_materia</li>
                    <li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>
                    <li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>
                    <li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>
                    <li class='list-group-item $active4'><strong>Nível 4: </strong>$nivel4</li>
                    <li class='list-group-item $active5'><strong>Nível 5: </strong>$nivel5</li>
                    <li class='list-group-item'><strong>ID: </strong>$id</li>
                  </ul>
                    <h4 class='text-center'>Alterar título</h4>
                    <input class='form-control' type='text' name='tema_novo_titulo' placeholder='novo título'></input>
                    <div class='custom-control custom-checkbox'>
                        <input type='checkbox' class='custom-control-input my-2' id='remover_ciclo' name='remover_ciclo' value='$id' checked>
                        <label class='custom-control-label my-2' for='remover_ciclo'>Remover do ciclo de revisão</label>
                    </div>
                    <div class='row justify-content-center'>
                      <button name='tema_novo_titulo_id' type='submit' class='btn btn-primary' value='$id'>Alterar título</button>
                    </div>
                  </form>
                  ";
                break;
              }
            }
            else {
              echo "<h4 class='text-center'>Não há temas marcados para revisão.</h4>";
            }
            echo "
              <form class='border border-light p-4 my-2' method='post'>
                <h2 class='text-center'>Ciclo de revisão</h2>
                  <h4 class='text-center'>Todos os temas</h4>
                    <p>Ao pressionar 'reiniciar o ciclo de revisão', todas as questões serão marcadas para revisão. Ao pressionar 'finalizar o ciclo de revisão', todas serão removidas do ciclo de revisão.</p>
                    <div class='row justify-content-center'>
                      <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
                      <button name='finalizar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Finalizar ciclo de revisão</button>
                    </div>
              </form>
              <form class='border border-light p-4 my-2' method='post'>
                <h2 class='text-center'>Ciclo de revisão</h2>
                  <h4 class='text-center'>Matéria específica</h4>
                  <p>Escolha abaixo uma matéria para acrescentar ao ciclo de revisão:</p>";
                    $result = $conn->query("SELECT materia, sigla, estado FROM Materias WHERE concurso = '$concurso'");
                    if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        $sigla = $row['sigla'];
                        $materia = $row['materia'];
                        $estado = $row['estado'];
                        if ($estado == false) { $estado = "(matéria desativada)"; }
                        else { $estado = false; }
                        $item_id = "ciclo_materia_";
                        $item_id .= $sigla;
                        echo "
                          <div class='form-check my-1'>
                            <input class='form-check-input' type='radio' name='ciclo_materia' id='$item_id' value='$sigla'>
                            <label class='form-check-label' for='$item_id'>$materia $estado</label>
                          </div>
                        ";
                      }
                    }
              echo "
                <div class='row justify-content-center'>
                  <button name='ciclo_materia_adicionar' type='submit' class='btn btn-primary' value='$concurso'>Marcar para revisão</button>
                  <button name='ciclo_materia_remover' type='submit' class='btn btn-primary' value='$concurso'>Remover do ciclo de revisão</button>
                </div>
              </form>
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
