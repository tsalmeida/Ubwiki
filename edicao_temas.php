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
    $result = $conn->query("UPDATE Temas_testes SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['finalizar_ciclo'])) {
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas_testes SET ciclo_revisao = 1 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['ciclo_materia_adicionar'])) {
    $materia_revisao = $_POST['ciclo_materia'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas_testes SET ciclo_revisao = 0 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
  }

  if (isset($_POST['ciclo_materia_remover'])) {
    $materia_revisao = $_POST['ciclo_materia'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas_testes SET ciclo_revisao = 1 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
  }

  if ((isset($_POST['remover_ciclo'])) && (isset($_POST['tema_id']))) {
    $remover_ciclo = $_POST['remover_ciclo'];
    $tema_id = $_POST['tema_id'];
    if ($remover_ciclo == true) {
      $servername = "localhost";
      $username = "grupoubique";
      $password = "ubique patriae memor";
      $dbname = "Ubique";
      $conn = new mysqli($servername, $username, $password, $dbname);
      mysqli_set_charset($conn,"utf8");
      $result = $conn->query("UPDATE Temas_testes SET ciclo_revisao = 1 WHERE id = '$tema_id'");
    }
  }

  if ((isset($_POST['tema_novo_titulo'])) && ($_POST['tema_novo_titulo'] != "")) {
    $tema_novo_titulo = $_POST['tema_novo_titulo'];
    $tema_id = $_POST['tema_id'];
    $servername = "localhost";
    $username = "grupoubique";
    $password = "ubique patriae memor";
    $dbname = "Ubique";
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("SELECT nivel FROM Temas_testes WHERE id = $tema_id");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $nivel_relevante = $row['nivel'];
      }
    }
    $coluna_nivel = 'nivel';
    $coluna_nivel .= $nivel_relevante;
    error_log("$tema_novo_titulo $tema_id $nivel_relevante $coluna_nivel");
    $result = $conn->query("UPDATE Temas_testes SET $coluna_nivel = '$tema_novo_titulo' WHERE id = $tema_id");
  }

  ?>
  <body>
    <?php
    carregar_navbar();
    standard_jumbotron("Alterar tópicos", false);
    sub_jumbotron($concurso,false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-12">
          <?php
            echo "
            <form class='border boder-light p-4 my-2' method='post'>
            <h2 class='text-center'>Edição de tópicos</h2>";
            $result = $conn->query("SELECT id, sigla_materia, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas_testes WHERE concurso = '$concurso' AND ciclo_revisao = 0 ORDER BY id");
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $active1 = false; $active2 = false; $active3 = false; $active4 = false; $active5 = false;
                $id = $row['id'];
                $sigla_materia = $row['sigla_materia'];
                $nivel1 = $row['nivel1']; $nivel2 = $row['nivel2']; $nivel3 = $row['nivel3']; $nivel4 = $row['nivel4']; $nivel5 = $row['nivel5'];
                if ($nivel5 != false) { $active5 = 'active'; } elseif ($nivel4 != false) { $active4 = 'active'; } elseif ($nivel3 != false) { $active3 = 'active'; } elseif ($nivel2 != false) { $active2 = 'active'; } else { $active1 = 'active'; }
                echo "
                  <ul class='list-group p-4'>
                    <li class='list-group-item'><strong>MATERIA: </strong>$sigla_materia</li>
                    <li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>";
                    if ($nivel2 != false) { echo "<li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>"; }
                    if ($nivel3 != false) { echo "<li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>"; }
                    if ($nivel4 != false) { echo "<li class='list-group-item $active4'><strong>Nível 4: </strong>$nivel4</li>"; }
                    if ($nivel5 != false) { echo "<li class='list-group-item $active5'><strong>Nível 5: </strong>$nivel5</li>"; }
                    echo "
                  </ul>
                    <div class='custom-control custom-checkbox'>
                        <input type='checkbox' class='custom-control-input my-2' id='remover_ciclo' name='remover_ciclo' value='$id'>
                        <label class='custom-control-label my-2' for='remover_ciclo'>Remover do ciclo de revisão</label>
                    </div>
                    <h4 class='text-center'>Alterar título</h4>
                    <input class='form-control' type='text' name='tema_novo_titulo' placeholder='novo título para este tópico'></input>
                    <h4 class='text-center mt-3'>Criar subtópicos</h4>
                    <p>Os novos subtópicos serão criados um nível abaixo do atual tópico, compartilhando seus tópicos superiores.</p>
                    <input class='form-control mt-2' type='text' id='novosub1' name='topico_subalterno1' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub2' name='topico_subalterno2' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub3' name='topico_subalterno3' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub4' name='topico_subalterno4' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub5' name='topico_subalterno5' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub6' name='topico_subalterno6' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub7' name='topico_subalterno7' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub8' name='topico_subalterno8' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub9' name='topico_subalterno9' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub10' name='topico_subalterno10' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub11' name='topico_subalterno11' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub12' name='topico_subalterno12' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub13' name='topico_subalterno13' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub14' name='topico_subalterno14' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub15' name='topico_subalterno15' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub16' name='topico_subalterno16' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub17' name='topico_subalterno17' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub18' name='topico_subalterno18' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub19' name='topico_subalterno19' placeholder='título do novo tópico'></input>
                    <input class='form-control mt-2 novosub' type='text' id='novosub20' name='topico_subalterno20' placeholder='título do novo tópico'></input>
                    <div class='row justify-content-center mt-3'>
                      <button name='tema_id' type='submit' class='btn btn-primary' value='$id'>Registrar mudanças</button>
                    </div>
                  ";
                break;
              }
            }
            else {
              echo "
                <h5 class='text-center'>Não há tópicos marcados para revisão.</h5>
              ";
            }
            echo "
              </form>
              <form class='border border-light p-4 mb-2 mt-5' method='post'>
                <h2 class='text-center'>Ciclo de revisão</h2>
                  <h4 class='text-center'>Todos os tópicos</h4>
                    <p>Ao pressionar 'reiniciar o ciclo de revisão', todos os tópicos serão marcadas para revisão. Ao pressionar 'finalizar o ciclo de revisão', todos serão removidas do ciclo de revisão.</p>
                    <div class='row justify-content-center'>
                      <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
                      <button name='finalizar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Finalizar ciclo de revisão</button>
                    </div>
              </form>
              <form class='border border-light p-4 my-2' method='post'>
                <h2 class='text-center'>Ciclo de revisão</h2>
                  <h4 class='text-center'>Por matéria</h4>
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
  bottom_page("edicao_temas");
?>
?>
