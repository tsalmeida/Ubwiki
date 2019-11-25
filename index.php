<?php

	include 'engine.php';
	
	$html_head_template_one_page = true;
	include 'templates/html_head.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, tipo_pagina) VALUES ($user_id, 'index')");

?>
<body>
<div class='container-fluid px-0 onepage bg-white'>
	<?php
		$template_navbar_mode = 'light';
		include 'templates/navbar.php';
	?>
    <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-6 col-md-2 px-3 mt-5 pt-5">
                <img class="img-fluid logo" src="/../imagens/ubiquelogo.png"></img>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 col-sm-12 mb-5">
                <form id="searchform" action="" method="post">
                    <div id="searchDiv">
                        <input id="searchBar" list="searchlist" type="text" class="searchBar text-muted"
                               name="searchBar" rows="1" autocomplete="off" spellcheck="false"
                               placeholder="O que você vai aprender hoje?" required>
                        <datalist id="searchlist">
													<?php
														$result = $conn->query("SELECT chave FROM Searchbar WHERE concurso_id = '$concurso_id' ORDER BY ordem");
														if ($result->num_rows > 0) {
															while ($row = $result->fetch_assoc()) {
																$chave = $row['chave'];
																echo "<option>$chave</option>";
															}
														}
													?>
                        </datalist>
											<?php
												echo "<input id='searchBarGo' name='searchBarGo' value='$concurso_id' type='submit' style='position: absolute; left: -9999px; width: 1px; height: 1px;' tabindex='-1' />";
											?>
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-5 justify-content-center">
            <div class='col-lg-8 col-sm-12'>
                <div class='row'>
									<?php
										$row_items = 2;
										$result = $conn->query("SELECT titulo, id FROM Materias WHERE concurso_id = '$concurso_id' AND estado = 1 ORDER BY ordem");
										$rowcount = mysqli_num_rows($result);
										if ($rowcount > 8) {
											$row_items = 3;
										} elseif ($rowcount > 4) {
											$row_items = 2;
										} elseif ($rowcount < 5) {
											$row_items = 1;
										}
										if ($result->num_rows > 0) {
											$count = 0;
											while ($row = $result->fetch_assoc()) {
												if ($count == 0) {
													echo "<div class='col-xl col-lg-6 col-md-6 col-sm-12'>";
												}
												$count++;
												$materia_titulo = $row['titulo'];
												$materia_id = $row["id"];
												echo "
                      <div href='materia.php?materia_id=$materia_id' class='rounded cardmateria grey lighten-4 text-break text-center align-middle mb-3 py-2'>
                        <span class='text-muted text-uppercase'>$materia_titulo</span>
                      </div>
                    ";
												if ($count == $row_items) {
													echo "</div>";
													$count = 0;
												}
											}
										}
										$conn->close();
									?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/searchbar.html';
	include 'templates/html_bottom.php';
?>
</html>
