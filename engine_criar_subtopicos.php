<?php

$refazer_ordem = false;

if (isset($_POST['form_sigla_materia'])) {
  $form_nivel1 = $_POST['form_nivel1']; $form_nivel2 = $_POST['form_nivel2']; $form_nivel3 = $_POST['form_nivel3']; $form_nivel4 = $_POST['form_nivel4']; $form_nivel5 = $_POST['form_nivel5']; $form_sigla_materia = $_POST['form_sigla_materia']; $form_nivel = $_POST['form_nivel'];

}

if ((isset($_POST['topico_subalterno1'])) && ($_POST['topico_subalterno1'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno1'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno2'])) && ($_POST['topico_subalterno2'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno2'];
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno3'])) && ($_POST['topico_subalterno3'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno3'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno4'])) && ($_POST['topico_subalterno4'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno4'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno5'])) && ($_POST['topico_subalterno5'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno5'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno6'])) && ($_POST['topico_subalterno6'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno6'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno7'])) && ($_POST['topico_subalterno7'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno7'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno8'])) && ($_POST['topico_subalterno8'] != "")) {
  $refazer_ordem = true;
    $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno8'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno9'])) && ($_POST['topico_subalterno9'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno9'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno10'])) && ($_POST['topico_subalterno10'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno10'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno11'])) && ($_POST['topico_subalterno11'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno11'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno12'])) && ($_POST['topico_subalterno12'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno12'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno13'])) && ($_POST['topico_subalterno13'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno13'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno14'])) && ($_POST['topico_subalterno14'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno14'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno15'])) && ($_POST['topico_subalterno15'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno15'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno16'])) && ($_POST['topico_subalterno16'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno16'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno17'])) && ($_POST['topico_subalterno17'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno17'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno18'])) && ($_POST['topico_subalterno18'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno18'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno19'])) && ($_POST['topico_subalterno19'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno19'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno20'])) && ($_POST['topico_subalterno20'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno20'];

  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ($refazer_ordem == true) {
  $nova_ordem = 0;
  $servername = "localhost"; $username = "grupoubique"; $password = "ubique patriae memor"; $dbname = "Ubique";
  $conn = new mysqli($servername, $username, $password, $dbname); mysqli_set_charset($conn,"utf8");
  $ordem1 = $conn->query("SELECT nivel1, id FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 1");
  if ($ordem1->num_rows > 0) {
    while($row1 = $ordem1->fetch_assoc()) {
      $nova_ordem++;
      $tema_nivel1 = $row1['nivel1'];
      $id_nivel1 = $row1['id'];
      $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_nivel1");
      $ordem2 = $conn->query("SELECT nivel2, id FROM Temas WHERE nivel1 = '$tema_nivel1' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 2");
      if ($ordem2->num_rows > 0) {
        while($row2 = $ordem2->fetch_assoc()) {
          $nova_ordem++;
          $tema_nivel2 = $row2['nivel2'];
          $id_nivel2 = $row2['id'];
          $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_nivel2");
          $ordem3 = $conn->query("SELECT nivel3, id FROM Temas WHERE nivel2 = '$tema_nivel2' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 3");
          if ($ordem3->num_rows > 0) {
            while ($row3 = $ordem3->fetch_assoc()) {
              $nova_ordem++;
              $tema_nivel3 = $row3['nivel3'];
              $id_nivel3 = $row3['id'];
              $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_nivel3");
              $ordem4 = $conn->query("SELECT nivel4, id FROM Temas WHERE nivel3 = '$tema_nivel3' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 4");
              if ($ordem4->num_rows > 0) {
                while ($row4 = $ordem4->fetch_assoc()) {
                  $nova_ordem++;
                  $tema_nivel4 = $row4['nivel4'];
                  $id_nivel4 = $row4['id'];
                  $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_nivel4");
                  $ordem5 = $conn->query("SELECT id FROM Temas WHERE nivel4 = '$tema_nivel4' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 5");
                  if ($ordem5->num_rows > 0) {
                    while ($row5 = $ordem5->fetch_assoc()) {
                      $nova_ordem++;
                      $id_nivel5 = $row5['id'];
                      $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_nivel5");
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

?>
