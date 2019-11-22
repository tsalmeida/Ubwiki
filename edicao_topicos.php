<?php
	
	include 'engine.php';
	
	if (isset($_SESSION['email'])) {
		$user_email = $_SESSION['email'];
	} else {
		header('Location:login.php');
	}
	
	if (isset($_GET['concurso_id'])) {
		$concurso_id = $_GET['concurso_id'];
	}
	
	$result = $conn->query("SELECT sigla, estado, titulo FROM Concursos WHERE id = $concurso_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$concurso_sigla = $row['sigla'];
			$concurso_estado = $row['estado'];
			$concurso_titulo = $row['titulo'];
		}
	} else {
		header('Location:index.php');
	}
	
	if (isset($_POST['ativar_materia_id'])) {
		$ativar_materia_id = $_POST['ativar_materia_id'];
		$update = $conn->query("UPDATE Materias SET estado = 1 WHERE id = $ativar_materia_id");
	}
	
	if (isset($_POST['nova_materia_titulo'])) {
		$nova_materia_titulo = $_POST['nova_materia_titulo'];
		$conn->query("INSERT INTO Materias (titulo, concurso_id) VALUES ('$nova_materia_titulo', $concurso_id)");
	}
	
	$nivel_1_materia = false;
	$primeiro_nivel_1 = '';
	$primeiro_nivel_2 = '';
	$primeiro_nivel_3 = '';
	$primeiro_nivel_4 = '';
	$primeiro_nivel_5 = '';
	
	if (isset($_POST['nivel_1_materia'])) {
		$nivel_1_materia = $_POST['nivel_1_materia'];
	}
	if ($nivel_1_materia != false) {
		if (isset($_POST['primeiro_nivel_1'])) {
			$primeiro_nivel_1 = $_POST['primeiro_nivel_1'];
		}
		if (isset($_POST['primeiro_nivel_2'])) {
			$primeiro_nivel_2 = $_POST['primeiro_nivel_2'];
		}
		if (isset($_POST['primeiro_nivel_3'])) {
			$primeiro_nivel_3 = $_POST['primeiro_nivel_3'];
		}
		if (isset($_POST['primeiro_nivel_4'])) {
			$primeiro_nivel_4 = $_POST['primeiro_nivel_4'];
		}
		if (isset($_POST['primeiro_nivel_5'])) {
			$primeiro_nivel_5 = $_POST['primeiro_nivel_5'];
		}
		
		if ($primeiro_nivel_1 != '') {
			$conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1) values (0, $concurso_id, $nivel_1_materia, 1, '$primeiro_nivel_1')");
		}
		if ($primeiro_nivel_2 != '') {
			$conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1) values (0, $concurso_id, $nivel_1_materia, 1, '$primeiro_nivel_2')");
		}
		if ($primeiro_nivel_3 != '') {
			$conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1) values (0, $concurso_id, $nivel_1_materia, 1, '$primeiro_nivel_3')");
		}
		if ($primeiro_nivel_4 != '') {
			$conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1) values (0, $concurso_id, $nivel_1_materia, 1, '$primeiro_nivel_4')");
		}
		if ($primeiro_nivel_5 != '') {
			$conn->query("INSERT INTO Topicos (ciclo_revisao, concurso_id, materia_id, nivel, nivel1) values (0, $concurso_id, $nivel_1_materia, 1, '$primeiro_nivel_5')");
		}
	}
	
	
	if (isset($_POST['apagar_topico_id'])) {
		$apagar_topico_id = $_POST['apagar_topico_id'];
		$result = $conn->query("SELECT nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE id = $apagar_topico_id");
		while ($row = $result->fetch_assoc()) {
			$apagar_nivel = $row['nivel'];
			if ($apagar_nivel == 1) {
				$apagar_nivel1 = $row['nivel1'];
				$apagar = $conn->query("DELETE FROM Topicos WHERE nivel1 = '$apagar_nivel1'");
			} elseif ($apagar_nivel == 2) {
				$apagar_nivel2 = $row['nivel2'];
				$apagar = $conn->query("DELETE FROM Topicos WHERE nivel2 = '$apagar_nivel2'");
				
			} elseif ($apagar_nivel == 3) {
				$apagar_nivel3 = $row['nivel3'];
				$apagar = $conn->query("DELETE FROM Topicos WHERE nivel3 = '$apagar_nivel3'");
				
			} elseif ($apagar_nivel == 4) {
				$apagar_nivel4 = $row['nivel4'];
				$apagar = $conn->query("DELETE FROM Topicos WHERE nivel4 = '$apagar_nivel4'");
				
			} else {
				$apagar_nivel5 = $row['nivel5'];
				$apagar = $conn->query("DELETE FROM Topicos WHERE nivel5 = '$apagar_nivel5'");
				
			}
		}
	}
	
	if (isset($_POST['reiniciar_ciclo'])) {
		$result = $conn->query("UPDATE Topicos SET ciclo_revisao = 0 WHERE concurso_id = $concurso_id");
	}
	
	if (isset($_POST['finalizar_ciclo'])) {
		$result = $conn->query("UPDATE Topicos SET ciclo_revisao = 1 WHERE concurso_id = $concurso_id");
	}
	
	if (isset($_POST['ciclo_materia_adicionar'])) {
		$materia_revisao_id = $_POST['ciclo_materia'];
		$result = $conn->query("UPDATE Topicos SET ciclo_revisao = 0 WHERE concurso_id = $concurso_id AND materia_id = '$materia_revisao_id'");
	}
	
	if (isset($_POST['ciclo_materia_remover'])) {
		if (isset($_POST['ciclo_materia'])) {
			$materia_revisao_id = $_POST['ciclo_materia'];
			$result = $conn->query("UPDATE Topicos SET ciclo_revisao = 1 WHERE concurso_id = $concurso_id AND materia_id = '$materia_revisao_id'");
		}
	}
	
	if ((isset($_POST['remover_ciclo'])) && (isset($_POST['form_topico_id']))) {
		$remover_ciclo = $_POST['remover_ciclo'];
		$form_topico_id = $_POST['form_topico_id'];
		if ($remover_ciclo == true) {
			$result = $conn->query("UPDATE Topicos SET ciclo_revisao = 1 WHERE id = '$form_topico_id'");
		}
	}
	
	$topico_novo_titulo = false;
	
	if (isset($_POST['form_topico_id'])) {
		$form_topico_id = $_POST['form_topico_id'];
		$result = $conn->query("SELECT nivel, materia_id, concurso_id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE id = $form_topico_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$nivel_relevante = $row['nivel'];
				$novo_titulo_materia_id = $row['materia_id'];
				$novo_titulo_concurso = $row['concurso_id'];
				if ($nivel_relevante == 1) {
					$antigo_titulo = $row['nivel1'];
				} elseif ($nivel_relevante == 2) {
					$antigo_titulo = $row['nivel2'];
				} elseif ($nivel_relevante == 3) {
					$antigo_titulo = $row['nivel3'];
				} elseif ($nivel_relevante == 4) {
					$antigo_titulo = $row['nivel4'];
				} elseif ($nivel_relevante == 5) {
					$antigo_titulo = $row['nivel5'];
				}
			}
		}
		$coluna_nivel = 'nivel';
		$coluna_nivel .= $nivel_relevante;
	}
	
	if ((isset($_POST['topico_novo_titulo'])) && ($_POST['topico_novo_titulo'] != "")) {
		$topico_novo_titulo = $_POST['topico_novo_titulo'];
		$update = $conn->query("UPDATE Topicos SET $coluna_nivel = '$topico_novo_titulo' WHERE $coluna_nivel = '$antigo_titulo' AND concurso_id = '$novo_titulo_concurso' AND materia_id = '$novo_titulo_materia_id'");
	}
	
	include 'engine_criar_subtopicos.php';
	
	$revisao = false;
	
	$result = $conn->query("SELECT id, materia_id, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE concurso_id = $concurso_id AND ciclo_revisao = 0 ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$active1 = false;
			$active2 = false;
			$active3 = false;
			$active4 = false;
			$active5 = false;
			$topico_id = $row['id'];
			$materia_id = $row['materia_id'];
			$nivel = $row['nivel'];
			$ordem = $row['ordem'];
			$nivel1 = $row['nivel1'];
			$nivel2 = $row['nivel2'];
			$nivel3 = $row['nivel3'];
			$nivel4 = $row['nivel4'];
			$nivel5 = $row['nivel5'];
			if ($nivel5 != false) {
				$active5 = 'list-group-item-primary';
			} elseif ($nivel4 != false) {
				$active4 = 'list-group-item-primary';
			} elseif ($nivel3 != false) {
				$active3 = 'list-group-item-primary';
			} elseif ($nivel2 != false) {
				$active2 = 'list-group-item-primary';
			} else {
				$active1 = 'list-group-item-primary';
			}
			$revisao = true;
			break;
		}
		$result = $conn->query("SELECT titulo FROM Materias WHERE concurso_id = $concurso_id AND id = $materia_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$materia_titulo = $row["titulo"];
			}
		}
	} else {
		$revisao = false;
	}
	
	include 'templates/html_head.php'

?>
<body>
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class='row d-flex justify-content-center'>
        <div class='col-lg-10 col-sm-12 text-center py-5'>
					<?php
						$template_titulo = "Alterar tópicos: $concurso_sigla";
						include 'templates/titulo.php';
					?>
        </div>
    </div>
    <div class="row justify-content-around" id="ferramenta">
        <div class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'edicao_topicos';
						$template_titulo = 'Edição de tópicos';
						$template_conteudo = false;
						if ($revisao != false) {
							$template_conteudo .= "
            <form method='post' action='edicao_topicos.php?concurso_id=$concurso_id#ferramenta'>
              <input type='hidden' name='form_topico_id' value='$topico_id'>
              <input type='hidden' name='form_nivel' value='$nivel'>
              <input type='hidden' name='form_ordem' value='$ordem'>
              <input type='hidden' name='form_nivel1' value='$nivel1'>
              <input type='hidden' name='form_nivel2' value='$nivel2'>
              <input type='hidden' name='form_nivel3' value='$nivel3'>
              <input type='hidden' name='form_nivel4' value='$nivel4'>
              <input type='hidden' name='form_nivel5' value='$nivel5'>
              <input type='hidden' name='form_materia_id' value='$materia_id'>
              <ul class='list-group'>
                <li class='list-group-item'><strong>MATÉRIA: </strong>$materia_titulo</li>
            ";
							
							if ($nivel != 1) {
								if ($nivel1 != false) {
									$nivel1_titulo = return_titulo_topico($nivel1);
									$template_conteudo .= "<li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1_titulo</li>";
								}
							} else {
								$template_conteudo .= "<li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>";
							}
							if ($nivel != 2) {
								if ($nivel2 != false) {
									$nivel2_titulo = return_titulo_topico($nivel2);
									$template_conteudo .= "<li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2_titulo</li>";
								}
							} else {
								$template_conteudo .= "<li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>";
							}
							if ($nivel != 3) {
								if ($nivel3 != false) {
									$nivel3_titulo = return_titulo_topico($nivel3);
									$template_conteudo .= "<li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3_titulo</li>";
								}
							} else {
								$template_conteudo .= "<li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>";
							}
							if ($nivel != 4) {
								if ($nivel4 != false) {
									$nivel4_titulo = return_titulo_topico($nivel4);
									$template_conteudo .= "<li class='list-group-item $active4'><strong>Nível 4: </strong>$nivel4_titulo</li>";
								}
							} else {
								$template_conteudo .= "<li class='list-group-item $active4'><strong>Nível 4: </strong>$nivel4</li>";
							}
							if ($nivel != 5) {
								if ($nivel5 != false) {
									$nivel5_titulo = return_titulo_topico($nivel5);
									$template_conteudo .= "<li class='list-group-item $active5'><strong>Nível 5: </strong>$nivel5_titulo</li>";
								}
							} else {
								$template_conteudo .= "<li class='list-group-item $active5'><strong>Nível 5: </strong>$nivel5</li>";
							}
							
							
							$template_conteudo .= "
              </ul>
              <div class='custom-control custom-checkbox'>
                  <input type='checkbox' class='custom-control-input my-2' id='remover_ciclo' name='remover_ciclo' value='$topico_id' checked>
                  <label class='custom-control-label my-2' for='remover_ciclo'>Remover do ciclo de revisão</label>
              </div>
              <h3>Alterar título</h3>
              <input class='form-control' type='text' name='topico_novo_titulo' placeholder='novo título para este tópico'></input>";
							if ($nivel != 5) {
								$template_conteudo .= "
              <h3>Criar subtópicos</h3>
              <p>Os novos subtópicos serão criados um nível abaixo do atual tópico, compartilhando seus tópicos superiores.</p>
              <input class='form-control mt-2' type='text' id='novosub1' name='topico_subalterno1' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub2' name='topico_subalterno2' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub3' name='topico_subalterno3' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub4' name='topico_subalterno4' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub5' name='topico_subalterno5' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub6' name='topico_subalterno6' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub7' name='topico_subalterno7' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub8' name='topico_subalterno8' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub9' name='topico_subalterno9' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub10' name='topico_subalterno10' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub11' name='topico_subalterno11' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub12' name='topico_subalterno12' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub13' name='topico_subalterno13' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub14' name='topico_subalterno14' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub15' name='topico_subalterno15' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub16' name='topico_subalterno16' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub17' name='topico_subalterno17' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub18' name='topico_subalterno18' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub19' name='topico_subalterno19' placeholder='título do novo tópico'></input>
              <input class='form-control mt-2 novosub' type='text' id='novosub20' name='topico_subalterno20' placeholder='título do novo tópico'></input>";
							}
							$template_conteudo .= "
                <div class='row justify-content-center'>
                <button name='form_topico_id' type='submit' class='btn btn-primary' value='$topico_id'>Registrar mudanças</button>";
							if ($concurso_estado == 0) {
								$template_conteudo .= "<button name='apagar_topico_id' type='submit' class='$button_classes btn-danger' value='$topico_id'>Apagar tópico e subtópicos</button>";
							}
							$template_conteudo .= '</div>';
						} else {
							$template_conteudo .= "
              <p>Não há tópicos marcados para revisão.</p>
            ";
						}
						$template_conteudo .= "</form>";
						include 'templates/page_element.php';
						
						$template_id = 'ciclo_revisao';
						$template_titulo = 'Ciclo de revisão: todos os tópicos';
						$template_conteudo = false;
						$template_conteudo .= "<form method='post'>";
						$template_conteudo .= "<p>Ao pressionar 'reiniciar o ciclo de revisão', todos os tópicos serão marcadas para revisão. Ao pressionar 'finalizar o ciclo de revisão', todos serão removidos do ciclo de revisão.</p>";
						$template_conteudo .= "<div class='row justify-content-center'>";
						$template_conteudo .= "<button name='reiniciar_ciclo' type='submit' class='$button_classes' value='$concurso_id'>Reiniciar ciclo de revisão</button>";
						$template_conteudo .= "<button name='finalizar_ciclo' type='submit' class='$button_classes' value='$concurso_id'>Finalizar ciclo de revisão</button>";
						$template_conteudo .= "</div>";
						include 'templates/page_element.php';
						
						$template_id = 'ciclo_revisao_materia';
						$template_titulo = 'Ciclo de revisão: por matéria';
						$template_conteudo = false;
						$template_conteudo .= "<p>Escolha abaixo uma matéria para acrescentar ao ciclo de revisão.</p>";
						
						$result = $conn->query("SELECT titulo, id, estado FROM Materias WHERE concurso_id = $concurso_id");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$pick_materia_id = $row['id'];
								$pick_materia_titulo = $row['titulo'];
								$pick_materia_estado = $row['estado'];
								if ($pick_materia_estado == false) {
									$pick_materia_estado = "(matéria desativada)";
								} else {
									$pick_materia_estado = false;
								}
								$item_id = "ciclo_materia_";
								$item_id .= $pick_materia_id;
								$template_conteudo .= "
                                    <div class='form-check my-1'>
                                      <input class='form-check-input' type='radio' name='ciclo_materia' id='$item_id' value='$pick_materia_id'>
                                      <label class='form-check-label' for='$item_id'>$pick_materia_titulo $pick_materia_estado</label>
                                    </div>
                                  ";
							}
						}
						$template_conteudo .= "
                            <div class='row justify-content-center'>
                              <button name='ciclo_materia_adicionar' type='submit' class='btn btn-primary' value='$concurso_id'>Marcar para revisão</button>
                              <button name='ciclo_materia_remover' type='submit' class='btn btn-primary' value='$concurso_id'>Remover do ciclo de revisão</button>
                            </div>
                        </form>
                        ";
						include 'templates/page_element.php';
						
						$template_id = 'acrescentar_materia';
						$template_titulo = 'Acrescentar matéria';
						$template_conteudo = false;
						$template_conteudo .= "
                        <form method='post'>
                <p>A ordem em que as matérias forem acrescentadas será a ordem em que serão apresentadas na página. Por
                    favor, tire um instante para pensar nas conexões naturais entre as matérias, assim como em sua
                    progressão natural, seja de importância ou de complexidade, para que sua ordem de apresentação seja
                    minimamente significativa.</p>
                    <div class='row'>
                        <input type='text' id='nova_materia_titulo' name='nova_materia_titulo'
                               class='form-control validate' required>
                        <label data-error='inválido' data-successd='válido'
                               for='nova_materia_titulo'>Título da matéria</label>
                    </div>
                <div class='row justify-content-center'>
                    <button type='submit' class='$button_classes'>Incluir matéria</button>
                </div>
            </form>
                        
                        ";
						
						include 'templates/page_element.php';
						
						$template_id = 'acrestentar_topicos_primeiro_nivel';
						$template_titulo = 'Acrescentar tópicos de primeiro nível';
						$template_conteudo = false;
						$template_conteudo .= "
                        <form method='post'>
                        <p>Após a inclusão de uma nova matéria, o próximo passo é acrescentar todos os tópicos de primeiro nível
                    da nova matéria.</p>
                        
                        ";
						
						$result = $conn->query("SELECT id, titulo, estado FROM Materias WHERE concurso_id = $concurso_id ORDER BY ordem");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$pick_materia_id = $row['id'];
								$pick_materia_titulo = $row['titulo'];
								$pick_materia_estado = $row['estado'];
								if ($pick_materia_estado == false) {
									$pick_materia_estado = "(matéria desativada)";
								} else {
									$pick_materia_estado = false;
								}
								$item_id = "novo_nivel_1_";
								$item_id .= $pick_materia_id;
								$template_conteudo .= "
                                  <div class='form-check my-1'>
                                    <input class='form-check-input' type='radio' name='nivel_1_materia' id='$item_id' value='$pick_materia_id'>
                                    <label class='form-check-label' for='$item_id'>$pick_materia_titulo $pick_materia_estado</label>
                                  </div>
                                ";
							}
							
							$template_conteudo .= "<fieldset class='form-group'>
                                <div class='row'>
                                    <input type='text' id='primeiro_nivel_1' name='primeiro_nivel_1' class='form-control validate'
                                           required>
                                    <label data-error='inválido' data-successd='válido'
                                           for='primeiro_nivel_1'>Tópico de primeiro nível</label>
                                </div>
                                <div class='row'>
                                    <input type='text' id='primeiro_nivel_2' name='primeiro_nivel_2' class='form-control validate'>
                                    <label data-error='inválido' data-successd='válido'
                                           for='primeiro_nivel_2'>Tópico de primeiro nível</label>
                                </div>
                                <div class='row'>
                                    <input type='text' id='primeiro_nivel_3' name='primeiro_nivel_3' class='form-control validate'>
                                    <label data-error='inválido' data-successd='válido'
                                           for='primeiro_nivel_3'>Tópico de primeiro nível</label>
                                </div>
                                <div class='row'>
                                    <input type='text' id='primeiro_nivel_4' name='primeiro_nivel_4' class='form-control validate'>
                                    <label data-error='inválido' data-successd='válido'
                                           for='primeiro_nivel_4'>Tópico de primeiro nível</label>
                                </div>
                                <div class='row'>
                                    <input type='text' id='primeiro_nivel_5' name='primeiro_nivel_5' class='form-control validate'>
                                    <label data-error='inválido' data-successd='válido'
                                           for='primeiro_nivel_5'>Tópico de primeiro nível</label>
                            </fieldset>
            
                            <div class='row justify-content-center'>
                                <button type='submit' class='$button_classes' name='nova_materia_concurso'>Incluir tópicos</button>
                            </div>
                        </form>";
						} else {
							$template_conteudo .= "<p><strong>Este concurso não possui matérias.</strong></p>";
						}
						
						include 'templates/page_element.php';
						
						$template_id = 'ativar_materia';
						$template_titulo = 'Ativar matéria';
						$template_conteudo = false;
						$template_conteudo .= "
                            <form method='post'>
                            <p>Após compôr definitivamente a lista de tópicos de uma matéria, é necessário ativá-la para que os
                                usuários a vejam. Uma vez ativada, não será possível remover tópicos, embora ainda seja possível
                                acrescentar tópicos novos ou mudar o nome dos tópicos pre-existentes, inclusive tópicos de primeiro
                                nível.</p>
                            <p>Em suma, apenas ative uma matéria se tiver certeza que nenhum tópico precisará ser removido.</p>
                        ";
						
						$result = $conn->query("SELECT id, titulo, estado FROM Materias WHERE concurso_id = $concurso_id AND estado = 0");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$pick_materia_titulo = $row['titulo'];
								$pick_materia_id = $row['id'];
								$input_id = "ativar_materia_";
								$input_id .= $pick_materia_id;
								$template_conteudo .= "
                        <div class='form-check my-1'>
                          <input class='form-check-input' type='radio' name='ativar_materia_id' id='$input_id' value='$pick_materia_id'>
                          <label class='form-check-label' for='$input_id'>$pick_materia_titulo</label>
                        </div>
                      ";
							}
						} else {
							$template_conteudo .= "<p>Este concurso não possui matérias desativadas.</p>";
						}
						$template_conteudo .= "<div class='row justify-content-center'>";
						$template_conteudo .= "<button type='submit' class='$button_classes'>Ativar matéria</button>";
						$template_conteudo .= "</div>
                            </form>";
						include 'templates/page_element.php';
					
					?>

        </div>
			
			<?php
				
				if ($revisao != false) {
					echo "<div id='coluna_direita' class='col-lg-5 col-sm-12'>";
					
					$template_id = 'lista_topicos';
					$template_titulo = "Tópicos de $materia_titulo";
					$template_conteudo = false;
					$template_conteudo .= "
							    <ul class='list-group'>
							";
					
					$result = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE concurso_id = $concurso_id AND materia_id = $materia_id ORDER BY ordem");
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$active1 = false;
							$active2 = false;
							$active3 = false;
							$active4 = false;
							$active5 = false;
							$id_lista = $row['id'];
							if ($id_lista == $topico_id) {
								$color = "list-group-item-primary";
							} else {
								$color = false;
							}
							$nivel = $row['nivel'];
							$nivel1 = $row['nivel1'];
							$nivel2 = $row['nivel2'];
							$nivel3 = $row['nivel3'];
							$nivel4 = $row['nivel4'];
							$nivel5 = $row['nivel5'];
							if ($nivel5 != false) {
								$template_conteudo .= "<li class='list-group-item $color'><em><span style='margin-left: 13ch'><i class='fal fa-chevron-double-right'></i><i class='fal fa-chevron-double-right'></i> $nivel5</span></em></li>";
							} elseif ($nivel4 != false) {
								$template_conteudo .= "<li class='list-group-item $color'><em><span style='margin-left: 8ch'><i class='fal fa-chevron-double-right'></i><i class='fal fa-chevron-right'></i> $nivel4</span></em></li>";
							} elseif ($nivel3 != false) {
								$template_conteudo .= "<li class='list-group-item $color'><span style='margin-left: 5ch'><i class='fal fa-chevron-double-right'></i> $nivel3</span></li>";
							} elseif ($nivel2 != false) {
								$template_conteudo .= "<li class='list-group-item $color'><span style='margin-left: 3ch'><i class='fal fa-chevron-right'></i> $nivel2</span></li>";
							} elseif ($nivel1 != false) {
								$template_conteudo .= "<li class='list-group-item $color'><strong>$nivel1</strong></li>";
							}
						}
					}
					$template_conteudo .= "</ul>";
					$template_conteudo .= "</div>";
					include 'templates/page_element.php';
				}
			?>

    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
	include 'templates/acrescentar_subtopico.html';
?>
</html>
