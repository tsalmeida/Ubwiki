<?php

	include 'engine.php';

	if (isset($_GET['sigla'])) {
		$sigla_materia = $_GET['sigla'];
	}

	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}
	$materia = false;
	$found = false;
	$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia = $row["materia"];
		}
	}
	$html_head_template_conteudo = false;
	if (file_exists("../imagens/materias/$sigla_materia.jpg")) {
		$background_image = "background-image: url('../imagens/materias/$sigla_materia.jpg');";
	} else {
		$background_image = false;
	}
	$html_head_template_conteudo .= "
	    <style>
	        #materia_background {
                $background_image
	            background-size: cover;
	            background-position: center center;
            }
	    </style>
	";

	include 'templates/html_head.php';
?>

<body>
<div id='materia_background' class='elegant-color'>
	<?php
		$template_navbar_mode = 'transparent';
		include 'templates/navbar.php';
	?>

    <div class="container-fluid">
        <div class='row d-flex justify-content-center transparent'>
            <div class='col-lg-10 col-sm-12 text-center py-5 text-white'>
							<?php
								$template_titulo = $materia;
								include 'templates/titulo.php';
							?>
            </div>
        </div>
    </div>
</div>
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
						echo "<ul class='list-group my-5'>";

						$result = $conn->query("SELECT DISTINCT nivel FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
						$nivel_count = 0;
						while ($row = mysqli_fetch_array($result)) {
							$nivel_count++;
						}
						$cor_nivel1 = false;
						$cor_nivel2 = false;
						$cor_nivel3 = false;
						$cor_nivel4 = false;
						$cor_nivel5 = false;

						if ($nivel_count == 5) {
							$cor_nivel1 = 'grey lighten-1';
							$cor_nivel2 = 'grey lighten-2';
							$cor_nivel3 = 'grey lighten-3';
							$cor_nivel4 = 'grey lighten-4';
							$cor_nivel5 = 'grey lighten-5';
						} elseif ($nivel_count == 4) {
							$cor_nivel1 = 'grey lighten-2';
							$cor_nivel2 = 'grey lighten-3';
							$cor_nivel3 = 'grey lighten-4';
							$cor_nivel4 = 'grey lighten-5';
						} elseif ($nivel_count == 3) {
							$cor_nivel1 = 'grey lighten-2';
							$cor_nivel2 = 'grey lighten-4';
							$cor_nivel3 = 'grey lighten-5';
						} elseif ($nivel_count == 2) {
							$cor_nivel1 = 'grey lighten-4';
							$cor_nivel2 = 'grey lighten-5';
						}

						$result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$id = $row["id"];
								$nivel1 = $row["nivel1"];
								$nivel2 = $row["nivel2"];
								$nivel3 = $row["nivel3"];
								$nivel4 = $row["nivel4"];
								$nivel5 = $row["nivel5"];
								if ($nivel5 != false) {
									echo "<a class='list-group-item list-group-item-action $cor_nivel5' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 16ch; display: inline-grid;' class='bg-light'></span><em>$nivel5</em></a>";
								} elseif ($nivel4 != false) {
									echo "<a class='list-group-item list-group-item-action $cor_nivel4' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 12ch; display: inline-grid;' class='bg-light'></span><em>$nivel4</em></a>";
								} elseif ($nivel3 != false) {
									echo "<a class='list-group-item list-group-item-action $cor_nivel3' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 8ch; display: inline-grid;' class='bg-light'></span>$nivel3</a>";
								} elseif ($nivel2 != false) {
									echo "<a class='list-group-item list-group-item-action $cor_nivel2' href='verbete.php?concurso=$concurso&tema=$id'><span style='width: 4ch; display: inline-grid;' class=''></span>$nivel2</a>";
								} elseif ($nivel1 != false) {
									echo "<a class='list-group-item list-group-item-action $cor_nivel1' href='verbete.php?concurso=$concurso&tema=$id'><strong><span style='width: 0ch; display: inline-grid;' class=''></span>$nivel1</strong></a>";
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
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
</html>
