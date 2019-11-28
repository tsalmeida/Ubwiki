<?php
	
	include 'engine.php';
	
	if ($concurso_id == false) {
		header('Location:cursos.php');
	}
	
	include 'templates/html_head.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, tipo_pagina) VALUES ($user_id, 'index')");

?>
<body>
<div class='container-fluid onepage'>
	<?php
		$template_navbar_mode = 'light';
		include 'templates/navbar.php';
	?>
    <div class="row justify-content-center">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mt-5">
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
        <div class='col-11'>
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
							?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row justify-content-around">
        <div id='coluna_esquerda' class='<?php echo $coluna_maior_classes; ?>'>
					<?php
						$template_id = 'paginas_recentes';
						$template_titulo = 'Verbetes recentemente modificados';
						$template_botoes = false;
						$template_conteudo = false;
						
						$paginas = $conn->query("SELECT DISTINCT page_id FROM (SELECT id, page_id FROM Textos_arquivo WHERE tipo = 'verbete' GROUP BY id ORDER BY id DESC) t");
						if ($paginas->num_rows > 0) {
							$template_conteudo .= "<ol class='list-group'>";
							$count = 0;
							while ($pagina = $paginas->fetch_assoc()) {
								$topico_id = $pagina['page_id'];
								$topico_materia_id = return_materia_id_topico($topico_id);
								$topico_materia_titulo = return_materia_titulo_id($topico_materia_id);
								$topico_titulo = return_titulo_topico($topico_id);
								$topicos = $conn->query("SELECT estado_pagina FROM Topicos WHERE id = $topico_id AND concurso_id = $concurso_id");
								if ($count == 10) {
									break;
								}
								if ($topicos->num_rows > 0) {
									while ($topico = $topicos->fetch_assoc()) {
										$topico_estado_pagina = $topico['estado_pagina'];
										$icone_estado = return_estado_icone($topico_estado_pagina, 'materia');
										if ($topico_estado_pagina > 3) {
											$estado_cor = 'text-warning';
										} else {
											$estado_cor = 'text-dark';
										}
										$cor_badge = 'grey lighten-3';
										$icone_badge = "
                                            <span class='badge $cor_badge $estado_cor badge-pill z-depth-0'>
                                                <i class='fa $icone_estado fa-fw'></i>
                                            </span>
										";
										$template_conteudo .= "
                                            <a href='verbete.php?topico_id=$topico_id'>
                                                <li class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                                                    <span>
                                                        <strong>$topico_materia_titulo: </strong>
                                                        $topico_titulo
                                                    </span>
                                                    $icone_badge
                                                </li>
                                            </a>";
										$count++;
										break;
									}
								}
							}
							$template_conteudo .= "</ol>";
						}
						
						include 'templates/page_element.php';
					
					
					?>
        </div>
    </div>

</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/searchbar.html';
	include 'templates/html_bottom.php';
	$conn->close();
?>
</html>
