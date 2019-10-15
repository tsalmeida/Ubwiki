<?php
include 'engine.php';
top_page();

if (isset($_GET['sigla'])) {
  $sigla = $_GET['sigla'];
}

if (isset($_GET['concurso'])) {
  $concurso = $_GET['concurso'];
}
$materia = false;
$servername = "localhost";
$username = "grupoubique";
$password = "ubique patriae memor";
$dbname = "Ubique";
$found = false;
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");
$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla' ORDER BY ordem");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $materia = $row["materia"];
  }
}


?>
<body>
  <?php
  carregar_navbar();
  standard_jumbotron($materia);
  ?>
  <div class="container my-5">
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
<?php
        $servername = "localhost";
        $username = "grupoubique";
        $password = "ubique patriae memor";
        $dbname = "Ubique";
        $found = false;
        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn,"utf8");
        $result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla' ORDER BY ordem");
        echo "<h1 class='mb-5'>Índice</h1>";
        if ($materia == false) {
          echo "<h4>Página não-encontrada</h4>
          <p>Clique <a href='index.php'>aqui</a> para retornar.</p>
          ";
          return;
        }
        echo "<h2>Verbetes</h2>
        <ul class='list-group'>";
        $result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla'");
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $nivel1 = $row["nivel1"];
            $nivel2 = $row["nivel2"];
            $nivel3 = $row["nivel3"];
            $nivel4 = $row["nivel4"];
            $nivel5 = $row["nivel5"];
            if ($nivel5 != false) {
              echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-5'>$nivel5</span></a></em>";
            }
            elseif ($nivel4 != false) {
              echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-4'>$nivel4</span></em></a>";
            }
            elseif ($nivel3 != false) {
              echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><em><span class='ml-2'>$nivel3</span></em></a>";
            }
            elseif ($nivel2 != false) {
              echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><span class='ml-1'>$nivel2</span></a>";
            }
            elseif ($nivel1 != false) {
              echo "<a class='list-group-item list-group-item-action' href='verbete.php?concurso=$concurso&tema=$id'><strong>$nivel1</strong></a>";
            }
          }
        }
        $conn->close();
        echo "</ul>";
?>
      </div>
      <div class="col-sm-2"></div>
    </div>
  </div>
</body>
<?php
  load_footer();
  bottom_page();
?>
