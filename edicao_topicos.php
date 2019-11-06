<?php

	include 'engine.php';

	if (isset($_SESSION['email'])) {
		$user = $_SESSION['email'];
	} else {
		header('Location:login.php');
	}

	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}

	$result = $conn->query("SELECT estado FROM Concursos WHERE sigla = '$concurso'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$estado = $row['estado'];
		}
	} else {
		header('Location:index.php');
	}

	if (isset($_POST['ativar_materia_id'])) {
		$ativar_materia_id = $_POST['ativar_materia_id'];
		$update = $conn->query("UPDATE Materias SET estado = 1 WHERE id = $ativar_materia_id");
	}

	if ((isset($_POST['nova_materia_titulo'])) && isset($_POST['nova_materia_sigla'])) {
		$nova_materia_titulo = $_POST['nova_materia_titulo'];
		$nova_materia_sigla = $_POST['nova_materia_sigla'];
		$conn->query("INSERT INTO Materias (materia, sigla, concurso) VALUES ('$nova_materia_titulo', '$nova_materia_sigla', '$concurso')");
	}

	if (isset($_POST['nivel_1_materia'])) {
		$nivel_1_materia = $_POST['nivel_1_materia'];
	}

	if (!isset($_POST['primeiro_nivel_1'])) {
		$_POST['primeiro_nivel_1'] = false;
	}
	if (!isset($_POST['primeiro_nivel_2'])) {
		$_POST['primeiro_nivel_2'] = false;
	}
	if (!isset($_POST['primeiro_nivel_3'])) {
		$_POST['primeiro_nivel_3'] = false;
	}
	if (!isset($_POST['primeiro_nivel_4'])) {
		$_POST['primeiro_nivel_4'] = false;
	}
	if (!isset($_POST['primeiro_nivel_5'])) {
		$_POST['primeiro_nivel_5'] = false;
	}

	if ($_POST['primeiro_nivel_1'] != '') {
		$primeiro_nivel_1 = $_POST['primeiro_nivel_1'];
		$novo_tema = $conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1) VALUES (0, '$concurso', '$nivel_1_materia', 1, '$primeiro_nivel_1')");
	}

	if ($_POST['primeiro_nivel_2'] != '') {
		$primeiro_nivel_2 = $_POST['primeiro_nivel_2'];
		$conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1) VALUES (0, '$concurso', '$nivel_1_materia', 1, '$primeiro_nivel_2')");
	}

	if ($_POST['primeiro_nivel_3'] != '') {
		$primeiro_nivel_3 = $_POST['primeiro_nivel_3'];
		$conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1) VALUES (0, '$concurso', '$nivel_1_materia', 1, '$primeiro_nivel_3')");
	}

	if ($_POST['primeiro_nivel_4'] != '') {
		$primeiro_nivel_4 = $_POST['primeiro_nivel_4'];
		$conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1) VALUES (0, '$concurso', '$nivel_1_materia', 1, '$primeiro_nivel_4')");
	}

	if ($_POST['primeiro_nivel_5'] != '') {
		$primeiro_nivel_5 = $_POST['primeiro_nivel_5'];
		$conn->query("INSERT INTO Temas (ciclo_revisao, concurso, sigla_materia, nivel, nivel1) VALUES (0, '$concurso', '$nivel_1_materia', 1, '$primeiro_nivel_5')");
	}

	if (isset($_POST['apagar_tema_id'])) {
		$apagar_tema_id = $_POST['apagar_tema_id'];
		$result = $conn->query("SELECT nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $apagar_tema_id");
		while ($row = $result->fetch_assoc()) {
			$apagar_nivel = $row['nivel'];
			if ($apagar_nivel == 1) {
				$apagar_nivel1 = $row['nivel1'];
				$apagar = $conn->query("DELETE FROM Temas WHERE nivel1 = '$apagar_nivel1'");
			} elseif ($apagar_nivel == 2) {
				$apagar_nivel2 = $row['nivel2'];
				$apagar = $conn->query("DELETE FROM Temas WHERE nivel2 = '$apagar_nivel2'");

			} elseif ($apagar_nivel == 3) {
				$apagar_nivel3 = $row['nivel3'];
				$apagar = $conn->query("DELETE FROM Temas WHERE nivel3 = '$apagar_nivel3'");

			} elseif ($apagar_nivel == 4) {
				$apagar_nivel4 = $row['nivel4'];
				$apagar = $conn->query("DELETE FROM Temas WHERE nivel4 = '$apagar_nivel4'");

			} else {
				$apagar_nivel5 = $row['nivel5'];
				$apagar = $conn->query("DELETE FROM Temas WHERE nivel5 = '$apagar_nivel5'");

			}
		}
	}

	if (isset($_POST['reiniciar_ciclo'])) {
		$result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso'");
	}

	if (isset($_POST['finalizar_ciclo'])) {
		$result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE concurso = '$concurso'");
	}

	if (isset($_POST['ciclo_materia_adicionar'])) {
		$materia_revisao = $_POST['ciclo_materia'];
		$result = $conn->query("UPDATE Temas SET ciclo_revisao = 0 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
	}

	if (isset($_POST['ciclo_materia_remover'])) {
		$materia_revisao = $_POST['ciclo_materia'];
		$result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE concurso = '$concurso' AND sigla_materia = '$materia_revisao'");
	}

	if ((isset($_POST['remover_ciclo'])) && (isset($_POST['form_tema_id']))) {
		$remover_ciclo = $_POST['remover_ciclo'];
		$form_tema_id = $_POST['form_tema_id'];
		if ($remover_ciclo == true) {
			$result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE id = '$form_tema_id'");
		}
	}

	$tema_novo_titulo = false;

	if (isset($_POST['form_tema_id'])) {
		$form_tema_id = $_POST['form_tema_id'];
		$result = $conn->query("SELECT nivel, sigla_materia, concurso, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $form_tema_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$nivel_relevante = $row['nivel'];
				$novo_titulo_sigla_materia = $row['sigla_materia'];
				$novo_titulo_concurso = $row['concurso'];
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

	if ((isset($_POST['tema_novo_titulo'])) && ($_POST['tema_novo_titulo'] != "")) {
		$tema_novo_titulo = $_POST['tema_novo_titulo'];
		$update = $conn->query("UPDATE Temas SET $coluna_nivel = '$tema_novo_titulo' WHERE $coluna_nivel = '$antigo_titulo' AND concurso = '$novo_titulo_concurso' AND sigla_materia = '$novo_titulo_sigla_materia'");
	}

	include 'engine_criar_subtopicos.php';

	$revisao = false;

	$result = $conn->query("SELECT id, sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND ciclo_revisao = 0 ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$active1 = false;
			$active2 = false;
			$active3 = false;
			$active4 = false;
			$active5 = false;
			$tema_id = $row['id'];
			$sigla_materia = $row['sigla_materia'];
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
		$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND sigla = '$sigla_materia'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$nome_materia = $row["materia"];
			}
		}
	} else {
		$revisao = false;
	}

	top_page(false);

?>
<body>
<?php
	carregar_navbar('dark');
	standard_jumbotron("Alterar tópicos", false);
	sub_jumbotron($concurso, false);
?>
<div class="container-fluid my-5">
    <div class="row justify-content-around" id="ferramenta">
        <div class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'edicao_topicos';
						$template_titulo = 'Edição de tópicos';
						$template_conteudo = false;
						if ($revisao != false) {
							$template_conteudo .= "
            <form method='post' action='edicao_topicos.php?concurso=$concurso#ferramenta'>
              <input type='hidden' name='form_nivel' value='$nivel'>
              <input type='hidden' name='form_ordem' value='$ordem'>
              <input type='hidden' name='form_nivel1' value='$nivel1'>
              <input type='hidden' name='form_nivel2' value='$nivel2'>
              <input type='hidden' name='form_nivel3' value='$nivel3'>
              <input type='hidden' name='form_nivel4' value='$nivel4'>
              <input type='hidden' name='form_nivel5' value='$nivel5'>
              <input type='hidden' name='form_sigla_materia' value='$sigla_materia'>
              <ul class='list-group'>
                <li class='list-group-item'><strong>MATÉRIA: </strong>$nome_materia</li>
                <li class='list-group-item $active1'><strong>Nível 1: </strong>$nivel1</li>
            ";

							if ($nivel2 != false) {
								$template_conteudo .= "<li class='list-group-item $active2'><strong>Nível 2: </strong>$nivel2</li>";
							}
							if ($nivel3 != false) {
								$template_conteudo .= "<li class='list-group-item $active3'><strong>Nível 3: </strong>$nivel3</li>";
							}
							if ($nivel4 != false) {
								$template_conteudo .= "<li class='list-group-item $active4'><strong>Nível 4: </strong>$nivel4</li>";
							}
							if ($nivel5 != false) {
								$template_conteudo .= "<li class='list-group-item $active5'><strong>Nível 5: </strong>$nivel5</li>";
							}
							$template_conteudo .= "
              </ul>
              <div class='custom-control custom-checkbox'>
                  <input type='checkbox' class='custom-control-input my-2' id='remover_ciclo' name='remover_ciclo' value='$tema_id' checked>
                  <label class='custom-control-label my-2' for='remover_ciclo'>Remover do ciclo de revisão</label>
              </div>
              <h3>Alterar título</h3>
              <input class='form-control' type='text' name='tema_novo_titulo' placeholder='novo título para este tópico'></input>
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
              <input class='form-control mt-2 novosub' type='text' id='novosub20' name='topico_subalterno20' placeholder='título do novo tópico'></input>
              <div class='row justify-content-center'>
                <button name='form_tema_id' type='submit' class='btn btn-primary' value='$tema_id'>Registrar mudanças</button>";
							if ($estado == 0) {
								$template_conteudo .= "<button name='apagar_tema_id' type='submit' class='btn btn-danger' value='$tema_id'>Apagar tópico e subtópicos</button>";
							}
							$template_conteudo .= '</div>';
						} else {
							$template_conteudo .= "
              <h5 class='text-center'>Não há tópicos marcados para revisão.</h5>
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
						$template_conteudo .= "<button name='reiniciar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Reiniciar ciclo de revisão</button>";
						$template_conteudo .= "<button name='finalizar_ciclo' type='submit' class='btn btn-primary' value='$concurso'>Finalizar ciclo de revisão</button>";
						$template_conteudo .= "</div>";
						include 'templates/page_element.php';

						$template_id = 'ciclo_revisao_materia';
						$template_titulo = 'Ciclo de revisão: por matéria';
						$template_conteudo = false;
						$template_conteudo .= "<p>Escolha abaixo uma matéria para acrescentar ao ciclo de revisão.</p>";

						$result = $conn->query("SELECT materia, sigla, estado FROM Materias WHERE concurso = '$concurso'");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$sigla = $row['sigla'];
								$materia_pick = $row['materia'];
								$estado = $row['estado'];
								if ($estado == false) {
									$estado = "(matéria desativada)";
								} else {
									$estado = false;
								}
								$item_id = "ciclo_materia_";
								$item_id .= $sigla;
								$template_conteudo .= "
                                    <div class='form-check my-1'>
                                      <input class='form-check-input' type='radio' name='ciclo_materia' id='$item_id' value='$sigla'>
                                      <label class='form-check-label' for='$item_id'>$materia_pick $estado</label>
                                    </div>
                                  ";
							}
						}
						$template_conteudo .= "
                            <div class='row justify-content-center'>
                              <button name='ciclo_materia_adicionar' type='submit' class='btn btn-primary' value='$concurso'>Marcar para revisão</button>
                              <button name='ciclo_materia_remover' type='submit' class='btn btn-primary' value='$concurso'>Remover do ciclo de revisão</button>
                            </div>
                        </form>
                        ";
						include 'templates/page_element.php';

						$template_id = 'acrescentar_materia';
						$template_titulo = 'Acrescentar matéria';
						$template_conteudo = false;
						$template_conteudo .= "
                        <form method='post'>
                <p>Para acrescentar uma matéria, é necessário criar um sigla. A sigla é necessariamente única em todo o
                    sistema Ubwiki e, portanto, é melhor que inclua a sigla do próprio concurso. Por exemplo: 'GEOCACD'
                    é a sigla para a matéria 'Geografia' do concurso 'CACD'.</p>
                <p>A ordem em que as matérias forem acrscentadas será a ordem em que serão apresentadas na página. Por
                    favor, tire um instante para pensar nas conexões naturais entre as matérias, assim como em sua
                    progressão natural, seja de importância ou de complexidade, para que sua ordem de apresentação seja
                    minimamente significativa.</p>
                    <div class='row'>
                        <input type='text' id='nova_materia_titulo' name='nova_materia_titulo'
                               class='form-control validate' required>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='nova_materia_titulo'>Título da matéria</label>
                    </div>
                    <div class='row'>
                        <input type='text' id='nova_materia_sigla' name='nova_materia_sigla'
                               class='form-control validate' required>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='nova_materia_sigla'>Sigla da matéria</label>
                    </div>
                <div class='row justify-content-center'>
                    <button type='submit' class='btn btn-primary' name='nova_materia_concurso'>Incluir matéria</button>
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

						$result = $conn->query("SELECT materia, sigla, estado FROM Materias WHERE concurso = '$concurso'");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$sigla = $row['sigla'];
								$materia_pick = $row['materia'];
								$estado = $row['estado'];
								if ($estado == false) {
									$estado = "(matéria desativada)";
								} else {
									$estado = false;
								}
								$item_id = "novo_nivel_1_";
								$item_id .= $sigla;
								$template_conteudo .= "
                                  <div class='form-check my-1'>
                                    <input class='form-check-input' type='radio' name='nivel_1_materia' id='$item_id' value='$sigla'>
                                    <label class='form-check-label' for='$item_id'>$materia_pick $estado</label>
                                  </div>
                                ";
							}
						}
						$template_conteudo .= "<fieldset class='form-group'>
                    <div class='row'>
                        <input type='text' id='primeiro_nivel_1' name='primeiro_nivel_1' class='form-control validate'
                               required>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='primeiro_nivel_1'>Tópico de primeiro nível</label>
                    </div>
                    <div class='row'>
                        <input type='text' id='primeiro_nivel_2' name='primeiro_nivel_2' class='form-control validate'>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='primeiro_nivel_2'>Tópico de primeiro nível</label>
                    </div>
                    <div class='row'>
                        <input type='text' id='primeiro_nivel_3' name='primeiro_nivel_3' class='form-control validate'>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='primeiro_nivel_3'>Tópico de primeiro nível</label>
                    </div>
                    <div class='row'>
                        <input type='text' id='primeiro_nivel_4' name='primeiro_nivel_4' class='form-control validate'>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='primeiro_nivel_4'>Tópico de primeiro nível</label>
                    </div>
                    <div class='row'>
                        <input type='text' id='primeiro_nivel_5' name='primeiro_nivel_5' class='form-control validate'>
                        <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                               for='primeiro_nivel_5'>Tópico de primeiro nível</label>
                </fieldset>

                <div class='row justify-content-center'>
                    <button type='submit' class='btn btn-primary' name='nova_materia_concurso'>Incluir matéria</button>
                </div>";

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

						$result = $conn->query("SELECT id, materia, sigla, estado FROM Materias WHERE concurso = '$concurso' AND estado = 0");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$sigla = $row['sigla'];
								$materia_pick = $row['materia'];
								$materia_id = $row['id'];
								$input_id = "ativar_materia_";
								$input_id .= $sigla;
								$template_conteudo .= "
                        <div class='form-check my-1'>
                          <input class='form-check-input' type='radio' name='ativar_materia_id' id='$input_id' value='$materia_id'>
                          <label class='form-check-label' for='$input_id'>$materia_pick</label>
                        </div>
                      ";
							}
						} else {
							$template .= "<p>Este concurso não possui matérias desativadas.</p>";
						}
						$template_conteudo .= "<div class='row justify-content-center'>";
						$template_conteudo .= "
                  <button type='submit' class='btn btn-primary'>Ativar materia</button>
                ";
						$template_conteudo .= "</div>
            </form>";
						include 'templates/page_element.php';


						echo "</div>";

						if ($revisao != false) {
							echo "<div class='col-lg-5 col-sm-12'>";

							$template_id = 'lista_topicos';
							$template_titulo = "Tópicos de $nome_materia";
							$template_conteudo = false;
							$template_conteudo .= "
							    <ul class='list-group'>
							";

                            $result = $conn->query("SELECT id, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $active1 = false;
                                    $active2 = false;
                                    $active3 = false;
                                    $active4 = false;
                                    $active5 = false;
                                    $id_lista = $row['id'];
                                    if ($id_lista == $tema_id) {
                                        $color = "list-group-item-primary";
                                    } else {
                                        $color = false;
                                    }
                                    $sigla_materia = $row['sigla_materia'];
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
	load_footer();
	bottom_page("edicao_topicos");
?>
</html>
