<?php
	
	$refazer_ordem = false;
	
	if (isset($_POST['form_materia_id'])) {
	  $form_topico_id = $_POST['form_topico_id'];
		$form_nivel1 = $_POST['form_nivel1'];
		$form_nivel2 = $_POST['form_nivel2'];
		$form_nivel3 = $_POST['form_nivel3'];
		$form_nivel4 = $_POST['form_nivel4'];
		$form_nivel5 = $_POST['form_nivel5'];
		$form_materia_id = $_POST['form_materia_id'];
		$form_nivel = $_POST['form_nivel'];
	}
	
	if ((isset($_POST['topico_novo_titulo'])) && ($_POST['topico_novo_titulo'] != "")) {
		if ($form_nivel == 1) {
			$form_nivel1 = $topico_novo_titulo;
		} elseif ($form_nivel == 2) {
			$form_nivel2 = $topico_novo_titulo;
		} elseif ($form_nivel == 3) {
			$form_nivel3 = $topico_novo_titulo;
		} elseif ($form_nivel == 4) {
			$form_nivel4 = $topico_novo_titulo;
		}
	}
	
	if ((isset($_POST['topico_subalterno1'])) && ($_POST['topico_subalterno1'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno1'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno2'])) && ($_POST['topico_subalterno2'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno2'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno3'])) && ($_POST['topico_subalterno3'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno3'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno4'])) && ($_POST['topico_subalterno4'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno4'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno5'])) && ($_POST['topico_subalterno5'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno5'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno6'])) && ($_POST['topico_subalterno6'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno6'];
		
		
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno7'])) && ($_POST['topico_subalterno7'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno7'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno8'])) && ($_POST['topico_subalterno8'] != "")) {
		$refazer_ordem = true;
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno8'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno9'])) && ($_POST['topico_subalterno9'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno9'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno10'])) && ($_POST['topico_subalterno10'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno10'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno11'])) && ($_POST['topico_subalterno11'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno11'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno12'])) && ($_POST['topico_subalterno12'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno12'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno13'])) && ($_POST['topico_subalterno13'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno13'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno14'])) && ($_POST['topico_subalterno14'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno14'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno15'])) && ($_POST['topico_subalterno15'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno15'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno16'])) && ($_POST['topico_subalterno16'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno16'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno17'])) && ($_POST['topico_subalterno17'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno17'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno18'])) && ($_POST['topico_subalterno18'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno18'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno19'])) && ($_POST['topico_subalterno19'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno19'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ((isset($_POST['topico_subalterno20'])) && ($_POST['topico_subalterno20'] != "")) {
		$refazer_ordem = true;
		$novo_subtopico = $_POST['topico_subalterno20'];
		if ($form_nivel == 1) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2) VALUES (0, $concurso_id, $form_materia_id, 2, '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 2) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3) VALUES (0, $concurso_id, $form_materia_id, 3, '$form_nivel1', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 3) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4) VALUES (0, $concurso_id, $form_materia_id, 4, '$form_nivel1', '$form_nivel2', '$form_topico_id', '$novo_subtopico') ");
		} elseif ($form_nivel == 4) {
			$insert = $conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5) VALUES (0, $concurso_id, $form_materia_id, 5, '$form_nivel1', '$form_nivel2', '$form_nivel3', '$form_topico_id', '$novo_subtopico') ");
		} else {
			return false;
		}
	}
	
	if ($refazer_ordem == true) {
		$nova_ordem = 0;
		
		$ordem1 = $conn->query("SELECT id FROM Topicos WHERE concurso_id = $concurso_id AND materia_id = $form_materia_id AND nivel = 1 ORDER BY ordem");
		if ($ordem1->num_rows > 0) {
			while ($row1 = $ordem1->fetch_assoc()) {
				$nova_ordem++;
				$id_ciclo_topico1 = $row1['id'];
				$update = $conn->query("UPDATE Topicos SET ordem = $nova_ordem WHERE id = $id_ciclo_topico1");
				$ordem2 = $conn->query("SELECT id FROM Topicos WHERE nivel1 = $id_ciclo_topico1 AND nivel = 2 ORDER BY ordem");
				if ($ordem2->num_rows > 0) {
					while ($row2 = $ordem2->fetch_assoc()) {
						$nova_ordem++;
						$id_ciclo_topico2 = $row2['id'];
						$update = $conn->query("UPDATE Topicos SET ordem = $nova_ordem WHERE id = $id_ciclo_topico2");
						$ordem3 = $conn->query("SELECT id FROM Topicos WHERE nivel2 = $id_ciclo_topico2 AND nivel = 3 ORDER BY ordem");
						if ($ordem3->num_rows > 0) {
							while ($row3 = $ordem3->fetch_assoc()) {
								$nova_ordem++;
								$id_ciclo_topico3 = $row3['id'];
								$update = $conn->query("UPDATE Topicos SET ordem = $nova_ordem WHERE id = $id_ciclo_topico3");
								$ordem4 = $conn->query("SELECT id FROM Topicos WHERE nivel3 = $id_ciclo_topico3 AND nivel = 4 ORDER BY ordem");
								if ($ordem4->num_rows > 0) {
									while ($row4 = $ordem4->fetch_assoc()) {
										$nova_ordem++;
										$id_ciclo_topico4 = $row4['id'];
										$update = $conn->query("UPDATE Topicos SET ordem = $nova_ordem WHERE id = $id_ciclo_topico4");
										$ordem5 = $conn->query("SELECT id FROM Topicos WHERE nivel4 = $id_ciclo_topico4 AND nivel = 5 ORDER BY ordem");
										if ($ordem5->num_rows > 0) {
											while ($row5 = $ordem5->fetch_assoc()) {
												$nova_ordem++;
												$id_ciclo_topico5 = $row5['id'];
												$update = $conn->query("UPDATE Topicos SET ordem = $nova_ordem WHERE id = $id_ciclo_topico5");
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
