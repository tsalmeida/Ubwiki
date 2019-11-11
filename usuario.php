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

	if (isset($_POST['quill_nova_mensagem_html'])) {
		$nova_mensagem = $_POST['quill_nova_mensagem_html'];
		$nova_mensagem = strip_tags($nova_mensagem, '<p><li><ul><ol><h2><h3><blockquote><em><sup><s>');
		$result = $conn->query("SELECT user_id, anotacao FROM Anotacoes WHERE tipo = 'userpage'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$anotacao_user_id = $row['user_id'];
				$anotacao_previa = $row['anotacao'];
				$conn->query("UPDATE Anotacoes SET anotacao = '$nova_mensagem' WHERE user_id = $user_id AND tipo = 'userpage'");
				$conn->query("INSERT INTO Anotacoes_arquivo (user_id, tipo, anotacao) VALUES ($user_id, 'userpage', '$anotacao_previa')");
			}
		} else {
			$conn->query("INSERT INTO Anotacoes (user_id, tipo, anotacao) VALUES ($user_id, 'userpage', '$nova_mensagem')");
		}
		$user_mensagens = $nova_mensagem;
	} else {
		$result = $conn->query("SELECT anotacao FROM Anotacoes WHERE tipo = 'userpage' AND user_id = $user_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$user_mensagens = $row['anotacao'];
			}
		} else {
			$user_mensagens = false;
		}
	}

	if (isset($_POST['novo_nome'])) {
		$user_nome = $_POST['novo_nome'];
		$user_sobrenome = $_POST['novo_sobrenome'];
		$user_apelido = $_POST['novo_apelido'];
		$result = $conn->query("UPDATE Usuarios SET nome = '$user_nome', sobrenome = '$user_sobrenome', apelido = '$user_apelido' WHERE id = $user_id");
	}
	
	$html_head_template_quill_theme = true;
	include 'templates/html_head.php';
	
?>
<body>
<?php
	carregar_navbar('dark');
	standard_jumbotron("Sua página", false);
	if ($user_tipo == 'admin') {
		sub_jumbotron("Administrador", 'admin.php');
	}
?>
<div class="container-fluid my-5">
    <div class="row d-flex justify-content-around">
        <div class="col-lg-5 col-sm-12">
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

						$template_conteudo .= "<ul class='list-group'>";
						$result = $conn->query("SELECT tema_id FROM Bookmarks WHERE user_id = $user_id");
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$tema_id = $row['tema_id'];
								$info_temas = $conn->query("SELECT concurso, sigla_materia, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE id = $tema_id");
								if ($info_temas->num_rows > 0) {
									while ($row = $info_temas->fetch_assoc()) {
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
										$template_conteudo .= "<a href='verbete.php?concurso=$concurso&tema=$tema_id'><li class='list-group-item list-group-item-action'>$titulo</li></a>";
									}
								}
							}
						}
						$template_conteudo .= "</ul>";
						include 'templates/page_element.php';

						$template_id = 'lista_comentarios';
						$template_titulo = 'Participações no fórum';
						$template_botoes = false;
						$template_conteudo = false;
						$result = $conn->query("SELECT DISTINCT tema_id FROM Forum WHERE user_id = $user_id");
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
						                if ($nivel == 1) { $nivel_relevante = $nivel1; }
						                elseif ($nivel == 2) { $nivel_relevante = $nivel2; }
						                elseif ($nivel == 3) { $nivel_relevante = $nivel3; }
						                elseif ($nivel == 4) { $nivel_relevante = $nivel4; }
						                else { $nivel_relevante = $nivel5; }
						                $template_conteudo .= "<a href='verbete.php?concurso=$concurso&tema=$tema_id'><li class='list-group-item list-group-item-action'>$nivel_relevante</li></a>";
                                    }
                                }
                            }
                            $template_conteudo .= "</ul>";
                        }
						else {
						    $template_conteudo .= "<p>Não há registro de participação sua em fórum de verbete.</p>";
                        }
						include 'templates/page_element.php';

					?>
        </div>
        <div class='col-lg-5 col-sm-12'>
					<?php
						$template_id = 'anotacoes_usuario';
						$template_titulo = 'Anotações';
						$template_botoes = false;
						$template_conteudo = false;

						$template_quill_container_id = 'anotacoes_div';
						$template_quill_form_id = 'quill_user_form';
						$template_quill_conteudo_html = 'quill_nova_mensagem_html';
						$template_quill_conteudo_text = 'quill_nova_mensagem_texto';
						$template_quill_conteudo_content = 'quill_nova_mensagem_content';
						$template_quill_container_id = 'quill_container_user';
						$template_quill_editor_id = 'quill_editor_user';
						$template_quill_editor_classes = 'quill_editor_height';
						$template_quill_conteudo_opcional = $user_mensagens;
						$template_quill_botoes_collapse_stuff = false;

						$template_conteudo .= include 'templates/quill_form.php';

						include 'templates/page_element.php';

					?>
        </div>
    </div>
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
	include 'templates/footer.php';
	include 'templates/html_bottom.php'
?>
</html>
