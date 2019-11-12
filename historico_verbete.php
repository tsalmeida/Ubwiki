<?php

	include 'engine.php';

	if (isset($_GET['tema'])) {
		$tema_id = $_GET['tema'];
	}

	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}

	$result = $conn->query("SELECT sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND id = $tema_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$sigla_materia = $row['sigla_materia'];
			$nivel = $row['nivel'];
			$ordem = $row['ordem'];
			$nivel1 = $row['nivel1'];
			$nivel2 = $row['nivel2'];
			$nivel3 = $row['nivel3'];
			$nivel4 = $row['nivel4'];
			$nivel5 = $row['nivel5'];
		}
	}

	$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia = $row["materia"];
		}
	}

	include 'templates/html_head.php';
?>
<body>
<?php
	include 'templates/navbar.php';
?>
<h1>Em construção</h1>
<p>Em breve.</p>
</body>
</html>