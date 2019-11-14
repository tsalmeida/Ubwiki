<?php
	
	include 'engine.php';
	
	$result = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user_email'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$user_id = $row['id'];
			$user_tipo = $row['tipo'];
			$user_criacao = $row['criacao'];
			$user_apelido = $row['apelido'];
			$user_nome = $row['nome'];
			$user_sobrenome = $row['sobrenome'];
		}
	}
	
	if (isset($_POST['novo_nome'])) {
		$user_nome = $_POST['novo_nome'];
		$user_sobrenome = $_POST['novo_sobrenome'];
		$user_apelido = $_POST['novo_apelido'];
		$result = $conn->query("UPDATE Usuarios SET nome = '$user_nome', sobrenome = '$user_sobrenome', apelido = '$user_apelido' WHERE id = $user_id");
	}
	
	// ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO
	
	$result = $conn->query("SELECT anotacao_content FROM Anotacoes WHERE tipo = 'userpage' AND user_id = $user_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$anotacoes_content = $row['anotacao_content'];
		}
	} else {
		$anotacoes_content = '';
	}
	
	if (isset($_POST['quill_novo_anotacoes_html'])) {
		$novo_anotacoes_html = $_POST['quill_novo_anotacoes_html'];
		$novo_anotacoes_text = $_POST['quill_novo_anotacoes_text'];
		$novo_anotacoes_content = $_POST['quill_novo_anotacoes_content'];
		$novo_anotacoes_html = strip_tags($novo_anotacoes_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
		$result = $conn->query("SELECT id FROM Anotacoes WHERE tipo = 'userpage' AND user_id = $user_id");
		if ($result->num_rows > 0) {
			$result = $conn->query("UPDATE Anotacoes SET anotacao_html = '$novo_anotacoes_html', anotacao_text = '$novo_anotacoes_text', anotacao_content = '$novo_anotacoes_content', user_id = '$user_id' WHERE tipo = 'userpage'");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (tipo, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('userpage', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		} else {
			$result = $conn->query("INSERT INTO Anotacoes (tipo, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('userpage', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
			$result = $conn->query("INSERT INTO Anotacoes_arquivo (tipo, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('userpage', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		}
		$anotacoes_content = $novo_anotacoes_content;
	}
	
	$anotacoes_content = urldecode($anotacoes_content);
	
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	
	include 'templates/html_head.php';
	include 'templates/imagehandler.php';
	
	$conn->query("INSERT INTO Visualizacoes (user_id, tipo_pagina) VALUES ($user_id, 'userpage')");

?>
<body>
<?php
	include 'templates/navbar.php';
?>


<div class='container-fluid bg-white'>
    <div class='row'>
        <div class='col-lg-4 col-sm-12'>
        </div>
        <div class='col-lg-8 col-sm-12'>
            <div class='text-right py-2'>
							<?php
								if ($user_tipo == 'admin') {
									echo "<a href='admin.php' target='_blank'>Página de Administrador</a>";
								}
							?>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
	<?php
		if ($user_apelido != false) {
			$template_titulo = $user_apelido;
		} else {
			$template_titulo = "Sua Página";
		}
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php'
	
	?>
    <div class="row d-flex justify-content-around">
        <div id='coluna_esquerda' class="col-lg-5 col-sm-12">
					<?php
						$template_id = 'dados_conta';
						$template_titulo = 'Dados da sua conta';
						$template_botoes = "<a data-toggle='modal' data-target='#modal_editar_dados' href=''><i class='fal fa-edit'></i></a>";
						$template_conteudo = false;
						$template_conteudo .= "<ul class='list-group'>";
						$template_conteudo .= "<li class='list-group-item'><strong>Conta criada em:</strong> $user_criacao</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Apelido:</strong> $user_apelido</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Nome:</strong> $user_nome</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Sobrenome:</strong> $user_sobrenome</li>";
						$template_conteudo .= "<li class='list-group-item'><strong>Email:</strong> $user_email</li>";
						$template_conteudo .= "</ul>";
						
						include 'templates/page_element.php';
						
						$template_id = 'lista_leitura';
						$template_titulo = 'Lista de leitura';
						$template_botoes = false;
						$template_conteudo = false;
						
						$result = $conn->query("SELECT DISTINCT tema_id FROM Bookmarks WHERE user_id = $user_id AND tema_id IS NOT NULL AND bookmark = 1 AND active = 1 ORDER BY id DESC");
						if ($result->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							while ($row = $result->fetch_assoc()) {
								$bookmark_tema_id = $row['tema_id'];
								$infotemas = mysqli_query($conn, "SELECT concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $bookmark_tema_id");
								error_log(serialize($infotemas));
								while ($row = $infotemas->fetch_assoc()) {
									$concurso = $row['concurso'];
									$sigla_materia = $row['sigla_materia'];
									$nivel = $row['nivel'];
									$nivel1 = $row['nivel1'];
									$nivel2 = $row['nivel2'];
									$nivel3 = $row['nivel3'];
									$nivel4 = $row['nivel4'];
									$nivel5 = $row['nivel5'];
									if ($nivel == 1) {
										$titulo = $nivel1;
									} elseif ($nivel == 2) {
										$titulo = $nivel2;
									} elseif ($nivel == 3) {
										$titulo = $nivel3;
									} elseif ($nivel == 4) {
										$titulo = $nivel4;
									} else {
										$titulo = $nivel5;
									}
									$template_conteudo .= "<a href='verbete.php?concurso=$concurso&tema=$bookmark_tema_id' target='_blank'><li class='list-group-item list-group-item-action'>$titulo</li></a>";
									break;
								}
							}
							$template_conteudo .= "</ul>";
						}
						else {
						    $template_conteudo .= "<p>Você ainda não acrescentou tópicos à sua lista de leitura.</p>";
                        }
						
						include 'templates/page_element.php';
						
						$template_id = 'lista_leitura_elementos';
						$template_titulo = 'Lista de leitura: elementos';
						$template_botoes = false;
						$template_conteudo = false;
						
						$template_conteudo .= "<ul class='list-group'>";
						$result = $conn->query("SELECT elemento_id FROM Bookmarks WHERE user_id = $user_id AND bookmark = 1 AND elemento_id IS NOT NULL AND active = 1 ORDER BY id DESC");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$info_elementos = $conn->query("SELECT titulo FROM Elementos WHERE id = $elemento_id");
								if ($info_elementos->num_rows > 0) {
									while ($row = $info_elementos->fetch_assoc()) {
										$titulo_elemento = $row['titulo'];
										$template_conteudo .= "<a href='elemento.php?id=$elemento_id' target='_blank'><li class='list-group-item list-group-item-action'>$titulo_elemento</li></a>";
									}
								}
							}
						} else {
							$template_conteudo .= "<p>Você ainda não acrescentou nenhum elemento de página (imagens, vídeos, referências bibliográficas) à sua lista de leitura.</p>";
						}
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';
						
						$template_id = 'lista_comentarios';
						$template_titulo = 'Participações no fórum';
						$template_botoes = false;
						$template_conteudo = false;
						$result = $conn->query("SELECT DISTINCT tema_id FROM Forum WHERE user_id = $user_id AND tema_id IS NOT NULL");
						if ($result->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							while ($row = $result->fetch_assoc()) {
								$tema_id = $row['tema_id'];
								$result2 = $conn->query("SELECT concurso, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $tema_id");
								if ($result2->num_rows > 0) {
									while ($row2 = $result2->fetch_assoc()) {
										$concurso = $row2['concurso'];
										$nivel = $row2['nivel'];
										$nivel1 = $row2['nivel1'];
										$nivel2 = $row2['nivel2'];
										$nivel3 = $row2['nivel3'];
										$nivel4 = $row2['nivel4'];
										$nivel5 = $row2['nivel5'];
										if ($nivel == 1) {
											$nivel_relevante = $nivel1;
										} elseif ($nivel == 2) {
											$nivel_relevante = $nivel2;
										} elseif ($nivel == 3) {
											$nivel_relevante = $nivel3;
										} elseif ($nivel == 4) {
											$nivel_relevante = $nivel4;
										} else {
											$nivel_relevante = $nivel5;
										}
										$template_conteudo .= "<a href='verbete.php?concurso=$concurso&tema=$tema_id'><li class='list-group-item list-group-item-action'>$nivel_relevante</li></a>";
									}
								}
							}
							$template_conteudo .= "</ul>";
						} else {
							$template_conteudo .= "<p>Não há registro de participação sua em fórum de verbete.</p>";
						}
						include 'templates/page_element.php';
						
						$template_id = 'lista_completed';
						$template_titulo = 'Tópicos estudados';
						$template_botoes = false;
						$template_conteudo = false;
						
						$result = $conn->query("SELECT tema_id FROM Completed WHERE user_id = $user_id AND estado = 1 AND active = 1");
						if ($result->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							while ($row = $result->fetch_assoc()) {
								$tema_id = $row['tema_id'];
								$infotemas = mysqli_query($conn, "SELECT concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $tema_id");
								error_log(serialize($infotemas));
								while ($row = $infotemas->fetch_assoc()) {
									$concurso = $row['concurso'];
									$sigla_materia = $row['sigla_materia'];
									$nivel = $row['nivel'];
									$nivel1 = $row['nivel1'];
									$nivel2 = $row['nivel2'];
									$nivel3 = $row['nivel3'];
									$nivel4 = $row['nivel4'];
									$nivel5 = $row['nivel5'];
									if ($nivel == 1) {
										$titulo = $nivel1;
									} elseif ($nivel == 2) {
										$titulo = $nivel2;
									} elseif ($nivel == 3) {
										$titulo = $nivel3;
									} elseif ($nivel == 4) {
										$titulo = $nivel4;
									} else {
										$titulo = $nivel5;
									}
									$template_conteudo .= "<a href='verbete.php?concurso=$concurso&tema=$tema_id' target='_blank'><li class='list-group-item list-group-item-action'>$titulo</li></a>";
									break;
								}
							}
							$template_conteudo .= "</ul>";
						} else {
							$template_conteudo .= '<p>Você ainda não marcou nenhum tópico como estudado.</p>';
						}
						include 'templates/page_element.php';
					
					?>
        </div>
        <div id='coluna_direita' class='col-lg-5 col-sm-12 anotacoes_collapse collapse show'>
					
					<?php
						$template_id = 'sticky_anotacoes';
						$template_titulo = 'Anotações privadas';
						$template_botoes = "<span class='anotacoes_editor_collapse collapse show' id='travar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='travar para edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='anotacoes_editor_collapse collapse' id='destravar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='permitir edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>";
						
						$template_quill_unique_name = 'anotacoes';
						$template_quill_initial_state = 'edicao';
						$template_quill_conteudo = $anotacoes_content;
						
						$template_conteudo = include 'templates/quill_form.php';
						include 'templates/page_element.php';
					
					?>

        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>

<?php
	
	$template_modal_div_id = 'modal_editar_dados';
	$template_modal_titulo = 'Alterar dados';
	$template_modal_body_conteudo = "
        <p>Você é identificado por seu apelido em todas as circunstâncias da página em que sua
            participação ou contribuição sejam tornadas públicas.</p>
        <div class='md-form md-2'><input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required></input>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_apelido' required>Apelido</label>
        </div>
        <p>Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.</p>
        <div class='md-form md-2'>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>

            <label data-error='preenchimento incorreto' data-successd='preenchimento correto'
                   for='novo_nome'>Nome</label>
        </div>
        <div class='md-form md-2'>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required></input>

            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' required>Sobrenome</label>
        </div>
    ";
	include 'templates/modal.php';

?>

</body>
<?php
	include 'templates/footer.html';
	$template_sem_verbete = true;
	include 'templates/html_bottom.php';
	include 'templates/sticky_anotacoes.html';
	include 'templates/lock_unlock_quill.php';
?>
</html>
