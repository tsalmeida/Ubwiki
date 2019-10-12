<?php

if (isset($_POST['sbcommand'])) {
  $concurso = base64_decode($_POST['sbconcurso']);
  $command = base64_decode($_POST['sbcommand']);
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND materia = '$command' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      echo "foundfoundfoundfmateria.php?sigla=$sigla&concurso=$concurso";
      $conn->close();
      $found = true;
    }
  }
  // aqui entrará a parte de busca por temas.
  if ($found == false) {
    // não havendo encontrado um match exato, o sistema busca por partial matches
    $index = 500;
    $winner = 0;
    $result = $conn->query("SELECT sigla, materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 ORDER BY ordem");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $sigla = $row["sigla"];
        $materia = $row["materia"];
        $materialow = mb_strtolower($materia);
        $commandlow = mb_strtolower($command);
        $check = levenshtein($materialow, $commandlow, 1, 1, 1);
  			if (strpos($materialow, $commandlow) !== false) {
  				echo "foundfoundfoundf$materia";
          $conn->close();
  				return;
  			}
        elseif ($check < $index) {
          $index = $check;
          $winner = $materia;
        }
      }
      $length = strlen($command);
      if ($index < $length) {
        echo "foundfoundfoundf$winner";
        $conn->close();
        return;
      }
    }
  }
  echo "notfoundnotfoundnada foi encontrado";
  $conn->close();
  return;
}

?>

<script>

$('#searchBarGo').click(function() {
  var command = $('#searchBar').val();
  var command = btoa(command);
  var concurso = $('#searchBarGo').val();
  var concurso = btoa(concurso);
  $.post('engine.php', {'sbcommand': command, 'sbconcurso': concurso}, function(data) {
    $("#searchBar").val('');
    if (data != 0) {
      var pw = data.substring(0, 16);
      var pw2 = data.substring(16);
      if (pw == 'notfoundnotfound') {
        $("#searchBar").val(pw2);
      }
      else if (pw = 'foundfoundfoundf') {
        $("#searchBar").val(pw2);
      }
    }
  });
  return false;
});

</script>
