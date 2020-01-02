<?php
	
	include 'engine.php';
	
	if (isset($_GET['materia_id'])) {
		$materia_id = $_GET['materia_id'];
	} else {
		header('Location:index.php');
	}
	$materia_titulo = false;
	$found = false;
	$result = $conn->query("SELECT titulo FROM Materias WHERE estado = 1 AND id = '$materia_id' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia_titulo = $row["titulo"];
		}
	}
	$html_head_template_conteudo = false;
	$background_image = false;
	if (file_exists("../imagens/materias/$materia_id.jpg")) {
		$background_image = "background-image: url('../imagens/materias/$materia_id.jpg');";
	}
	if ($background_image != false) {
		$html_head_template_conteudo .= "
            <style>
                #materia_background {
                    $background_image
                    background-size: cover;
                    background-position: center center;
                    min-height: 40vh;
                }
            </style>
        ";
	}
	
	$html_head_template_quill = true;
	include 'templates/html_head.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $materia_id, 'materia')");
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
								$template_titulo = $materia_titulo;
								include 'templates/titulo.php';
							?>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row justify-content-around">
        <div class="<?php echo $coluna_maior_classes; ?>">
					<?php
						$template_id = 'lista_topicos';
						$template_titulo = 'Tópicos';
						$template_conteudo = false;
						$template_conteudo_no_col = true;
						if ($materia_titulo == false) {
							$template_conteudo .= "
					          <h4>Página não-encontrada</h4>
                              <p>Clique <a href='index.php'>aqui</a> para retornar.</p>
                            ";
							return;
						}
						$template_conteudo .= "<ul class='list-group'>";
						
						$result = $conn->query("SELECT DISTINCT nivel FROM Topicos WHERE materia_id = '$materia_id'");
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
							$cor_nivel2 = 'grey lighten-3';
							$cor_nivel3 = 'grey lighten-5';
						} elseif ($nivel_count == 2) {
							$cor_nivel1 = 'grey lighten-3';
							$cor_nivel2 = 'grey lighten-5';
						}
						
						$spacing1 = "style=''";
						$spacing2 = "style='padding-left: 5ch'";
						$spacing3 = "style='padding-left: 10ch'";
						$spacing4 = "style='padding-left: 15ch'";
						$spacing5 = "style='padding-left: 20ch'";
						
						$result = $conn->query("SELECT id, estado_pagina, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = '$materia_id' ORDER BY ordem");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$topico_id = $row["id"];
								$estado_pagina = $row['estado_pagina'];
								$nivel1 = $row["nivel1"];
								$nivel2 = $row["nivel2"];
								$nivel3 = $row["nivel3"];
								$nivel4 = $row["nivel4"];
								$nivel5 = $row["nivel5"];
								
								if ($estado_pagina != 0) {
									$estado_cor = false;
									$icone_estado = return_estado_icone($estado_pagina, 'materia');
									if ($estado_pagina > 3) {
										$estado_cor = 'text-warning';
									} else {
										$estado_cor = 'text-dark';
									}
									$cor_badge = 'bg-white';
									$icone_badge = "
				                        <span class='ml-3 badge $cor_badge $estado_cor badge-pill z-depth-0'>
                                            <i class='fa $icone_estado'></i>
                                        </span>
				                    ";
								} else {
									$icone_badge = false;
								}
								
								if ($nivel5 != false) {
									$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel5 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing5>
                                            $nivel5
                                            $icone_badge
                                        </a>
                                    ";
								} elseif ($nivel4 != false) {
									$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel4 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing4>
                                            $nivel4
                                            $icone_badge
                                        </a>
                                    ";
								} elseif ($nivel3 != false) {
									$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel3 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing3>
                                            $nivel3
                                            $icone_badge
                                        </a>
                                    ";
								} elseif ($nivel2 != false) {
									$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel2 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing2>
                                            $nivel2
                                            $icone_badge
                                        </a>
                                    ";
								} elseif ($nivel1 != false) {
									$template_conteudo .= "
                                        <a class='list-group-item list-group-item-action $cor_nivel1 d-flex justify-content-between align-items-center' href='pagina.php?topico_id=$topico_id' $spacing1>
                                            $nivel1
                                            $icone_badge
                                        </a>
                                    ";
								}
							}
							unset($topico_id);
						}
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <div class="row justify-content-around mt-5">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'verbete_materia';
						$template_titulo = 'Introdução';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					?>
        </div>
        <div id="coluna_direita" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'anotacoes_materia';
						$template_titulo = 'Anotações privadas';
						$template_quill_empty_content = "<p id='verbete_vazio_{$template_id}'>Você ainda não fez anotações sobre este curso.</p>";
						$template_botoes = false;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i class='fas fa-pen-alt fa-fw'></i></button>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_materia';
	include 'templates/esconder_anotacoes.php';
	$conn->close();
?>
</html>