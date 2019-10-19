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
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
  }

  if (isset($_POST['finalizar_ciclo'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE concurso = '$concurso'");
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
            echo "<h1>$concurso</h1>";
            echo "<p class='h4 mb-4 text-center'>Edição de temas</p>";
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
                return;
              }
            }
            else {
              echo "<h4>Não há temas marcados para revisão.</h4>";
            }
            echo "
              <form class='text-center border border-light px-2 my-2' method='post'>
                <p class='h4 my-4'>Re-iniciar ciclo de revisão</p>
                <p class='text-left'>Ao pressionar o botão abaixo, todas as questões deste concurso serão colocadas no ciclo de revisão.</p>
                <button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>
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
