<?php

	include 'engine.php';

	top_page(false);

	if (isset($_GET['sigla'])) {
		$sigla = $_GET['sigla'];
	}

	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}
	$materia = false;
	$found = false;
	$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia = $row["materia"];
		}
	}
?>

<body>
<?php
	carregar_navbar('dark');
	standard_jumbotron($materia, false);
	//	sub_jumbotron("Tópicos", false);
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-sm-12">
					<?php
						if ($materia == false) {
							echo "<h4>Página não-encontrada</h4>
          <p>Clique <a href='index.php'>aqui</a> para retornar.</p>
          ";
							return;
						}
						echo "<ul class='list-group my-3'>";
						$result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla' ORDER BY ordem");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$id = $row["id"];
								$nivel1 = $row["nivel1"];
								$nivel2 = $row["nivel2"];
								$nivel3 = $row["nivel3"];
								$nivel4 = $row["nivel4"];
								$nivel5 = $row["nivel5"];
								if ($nivel5 != false) {
									echo "<a class='list-group-item list-group-item-action grey-lighten-5' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 16ch; display: inline-grid;' class='bg-light'></span><em>$nivel5</em></a>";
								} elseif ($nivel4 != false) {
									echo "<a class='list-group-item list-group-item-action grey lighten-4' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 12ch; display: inline-grid;' class='bg-light'></span><em>$nivel4</em></a>";
								} elseif ($nivel3 != false) {
									echo "<a class='list-group-item list-group-item-action grey lighten-3' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 8ch; display: inline-grid;' class='bg-light'></span>$nivel3</a>";
								} elseif ($nivel2 != false) {
									echo "<a class='list-group-item list-group-item-action grey lighten-2' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 4ch; display: inline-grid;' class=''></span>$nivel2</a>";
								} elseif ($nivel1 != false) {
									echo "<a class='list-group-item list-group-item-action grey lighten-1' href='verbete.php?concurso=$concurso&tema=$id'><strong><span style='width: 0ch; display: inline-grid;' class=''></span>$nivel1</strong></a>";
								}
							}
						}
						$conn->close();
						echo "</ul>";
					?>
        </div>
    </div>
</div>
</body>
<?php
	load_footer();
	bottom_page();
?>
</html>
