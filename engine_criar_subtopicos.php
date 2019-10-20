<?php

$refazer_ordem = false;

if (isset($_POST['form_sigla_materia'])) {
  $form_nivel1 = $_POST['form_nivel1']; $form_nivel2 = $_POST['form_nivel2']; $form_nivel3 = $_POST['form_nivel3']; $form_nivel4 = $_POST['form_nivel4']; $form_nivel5 = $_POST['form_nivel5']; $form_sigla_materia = $_POST['form_sigla_materia']; $form_nivel = $_POST['form_nivel'];
  error_log("$form_nivel1 $form_nivel2 $form_nivel3 $form_nivel4 $form_nivel5 $form_sigla_materia $form_nivel");
}

if ((isset($_POST['tema_novo_titulo'])) && ($_POST['tema_novo_titulo'] != "")) {
  if ($form_nivel == 1) { $form_nivel1 = $tema_novo_titulo; }
  elseif ($form_nivel == 2) { $form_nivel2 = $tema_novo_titulo; }
  elseif ($form_nivel == 3) { $form_nivel3 = $tema_novo_titulo; }
  elseif ($form_nivel == 4) { $form_nivel4 = $tema_novo_titulo; }
}

if ((isset($_POST['topico_subalterno1'])) && ($_POST['topico_subalterno1'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno1'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno2'])) && ($_POST['topico_subalterno2'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno2'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno3'])) && ($_POST['topico_subalterno3'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno3'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno4'])) && ($_POST['topico_subalterno4'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno4'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno5'])) && ($_POST['topico_subalterno5'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno5'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno6'])) && ($_POST['topico_subalterno6'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno6'];


  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno7'])) && ($_POST['topico_subalterno7'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno7'];
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
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if((isset($_POST['topico_subalterno9'])) && ($_POST['topico_subalterno9'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno9'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno10'])) && ($_POST['topico_subalterno10'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno10'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno11'])) && ($_POST['topico_subalterno11'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno11'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno12'])) && ($_POST['topico_subalterno12'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno12'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno13'])) && ($_POST['topico_subalterno13'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno13'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno14'])) && ($_POST['topico_subalterno14'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno14'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno15'])) && ($_POST['topico_subalterno15'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno15'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno16'])) && ($_POST['topico_subalterno16'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno16'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno17'])) && ($_POST['topico_subalterno17'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno17'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno18'])) && ($_POST['topico_subalterno18'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno18'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno19'])) && ($_POST['topico_subalterno19'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno19'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ((isset($_POST['topico_subalterno20'])) && ($_POST['topico_subalterno20'] != "")) {
  $refazer_ordem = true;
  $novo_subtopico = $_POST['topico_subalterno20'];
  if ($form_nivel == 1) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2) VALUES (0, '$concurso', '$form_sigla_materia', 2, '$form_nivel1', '$novo_subtopico') "); }
  elseif ($form_nivel == 2) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3) VALUES (0, '$concurso', '$form_sigla_materia', 3, '$form_nivel1', '$form_nivel2', '$novo_subtopico') "); }
  elseif ($form_nivel == 3) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, '$concurso', '$form_sigla_materia', 4, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$novo_subtopico') "); }
  elseif ($form_nivel == 4) { $insert = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, '$concurso', '$form_sigla_materia', 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_nivel4', '$novo_subtopico') "); }
  else { return false; }
}

if ($refazer_ordem == true) {
  $nova_ordem = 0;


  $ordem1 = $conn->query("SELECT nivel1, id FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 1 ORDER BY id");
  if ($ordem1->num_rows > 0) {
    while($row1 = $ordem1->fetch_assoc()) {
      $nova_ordem++;
      $tema_ciclo_tema1 = $row1['nivel1'];
      $id_ciclo_tema1 = $row1['id'];
      $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_ciclo_tema1");
      $ordem2 = $conn->query("SELECT nivel2, id FROM Temas WHERE nivel1 = '$tema_ciclo_tema1' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 2 ORDER BY id");
      if ($ordem2->num_rows > 0) {
        while($row2 = $ordem2->fetch_assoc()) {
          $nova_ordem++;
          $tema_ciclo_tema2 = $row2['nivel2'];
          $id_ciclo_tema2 = $row2['id'];
          $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_ciclo_tema2");
          $ordem3 = $conn->query("SELECT nivel3, id FROM Temas WHERE nivel2 = '$tema_ciclo_tema2' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 3 ORDER BY id");
          if ($ordem3->num_rows > 0) {
            while ($row3 = $ordem3->fetch_assoc()) {
              $nova_ordem++;
              $tema_ciclo_tema3 = $row3['nivel3'];
              $id_ciclo_tema3 = $row3['id'];
              $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_ciclo_tema3");
              $ordem4 = $conn->query("SELECT nivel4, id FROM Temas WHERE nivel3 = '$tema_ciclo_tema3' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 4 ORDER BY id");
              if ($ordem4->num_rows > 0) {
                while ($row4 = $ordem4->fetch_assoc()) {
                  $nova_ordem++;
                  $tema_ciclo_tema4 = $row4['nivel4'];
                  $id_ciclo_tema4 = $row4['id'];
                  $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_ciclo_tema4");
                  $ordem5 = $conn->query("SELECT id FROM Temas WHERE nivel4 = '$tema_ciclo_tema4' AND concurso = '$concurso' AND sigla_materia = '$form_sigla_materia' AND nivel = 5 ORDER BY id");
                  if ($ordem5->num_rows > 0) {
                    while ($row5 = $ordem5->fetch_assoc()) {
                      $nova_ordem++;
                      $id_ciclo_tema5 = $row5['id'];
                      $update = $conn->query("UPDATE Temas SET ordem = $nova_ordem WHERE id = $id_ciclo_tema5");
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
