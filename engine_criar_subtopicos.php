<?php

error_log("$nivel $sigla_materia");

if ((isset($_POST['topico_subalterno1'])) && ($_POST['topico_subalterno1'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno1'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno2'])) && ($_POST['topico_subalterno2'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno2'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno3'])) && ($_POST['topico_subalterno3'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno3'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno4'])) && ($_POST['topico_subalterno4'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno4'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno5'])) && ($_POST['topico_subalterno5'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno5'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno6'])) && ($_POST['topico_subalterno6'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno6'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno7'])) && ($_POST['topico_subalterno7'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno7'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno8'])) && ($_POST['topico_subalterno8'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno8'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno9'])) && ($_POST['topico_subalterno9'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno9'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno10'])) && ($_POST['topico_subalterno10'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno10'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno11'])) && ($_POST['topico_subalterno11'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno11'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno12'])) && ($_POST['topico_subalterno12'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno12'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno13'])) && ($_POST['topico_subalterno13'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno13'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno14'])) && ($_POST['topico_subalterno14'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno14'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno15'])) && ($_POST['topico_subalterno15'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno15'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno16'])) && ($_POST['topico_subalterno16'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno16'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno17'])) && ($_POST['topico_subalterno17'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno17'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno18'])) && ($_POST['topico_subalterno18'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno18'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno19'])) && ($_POST['topico_subalterno19'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno19'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno20'])) && ($_POST['topico_subalterno20'] != "")) {
  $novo_subtopico = $_POST['topico_subalterno20'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas_testes (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

?>
