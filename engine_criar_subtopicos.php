<?php

$refazer_ordem = false;

if ((isset($_POST['topico_subalterno1'])) && ($_POST['topico_subalterno1'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno1'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno2'])) && ($_POST['topico_subalterno2'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno2'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno3'])) && ($_POST['topico_subalterno3'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno3'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno4'])) && ($_POST['topico_subalterno4'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno4'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno5'])) && ($_POST['topico_subalterno5'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno5'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno6'])) && ($_POST['topico_subalterno6'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno6'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno7'])) && ($_POST['topico_subalterno7'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno7'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno8'])) && ($_POST['topico_subalterno8'] != "")) {
  $refazer_ordem = true;
    $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno8'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno9'])) && ($_POST['topico_subalterno9'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno9'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno10'])) && ($_POST['topico_subalterno10'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno10'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno11'])) && ($_POST['topico_subalterno11'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno11'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno12'])) && ($_POST['topico_subalterno12'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno12'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno13'])) && ($_POST['topico_subalterno13'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno13'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno14'])) && ($_POST['topico_subalterno14'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno14'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno15'])) && ($_POST['topico_subalterno15'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno15'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno16'])) && ($_POST['topico_subalterno16'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno16'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno17'])) && ($_POST['topico_subalterno17'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno17'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno18'])) && ($_POST['topico_subalterno18'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno18'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno19'])) && ($_POST['topico_subalterno19'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno19'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno20'])) && ($_POST['topico_subalterno20'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno20'];
  $tema_id = $_POST['tema_id'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2) VALUES (0, '$concurso', '$sigla_materia', 2, $tema_id, '$nivel1', '$novo_subtopico') "); }
  elseif ($nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3) VALUES (0, '$concurso', '$sigla_materia', 3, $tema_id, '$nivel1', '$nivel2', '$novo_subtopico') "); }
  elseif ($nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$sigla_materia', 4, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$novo_subtopico') "); }
  elseif ($nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, parent_id, nivel1 nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$sigla_materia', 5, $tema_id, '$nivel1', '$nivel2', '$nivel3', '$nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ($refazer_ordem == true) {

}

?>
