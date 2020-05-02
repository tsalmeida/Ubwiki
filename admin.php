<?php
	
	include 'engine.php';
	
	$pagina_tipo = 'admin';
	
	if ($user_tipo != 'admin') {
		header("Location:ubwiki.php");
	}
	
	if (isset($_POST['trigger_atualizacao'])) {
		$usuarios_errados = $conn->query("SELECT id, email, pagina_id FROM Usuarios WHERE origem = 'NxXegQpBs4Td'");
		if ($usuarios_errados->num_rows > 0) {
			$usuarios_contados = array();
			while ($usuario_errado = $usuarios_errados->fetch_assoc()) {
				$usuario_errado_id = $usuario_errado['id'];
				$usuario_errado_email = $usuario_errado['email'];
				$usuario_errado_pagina_id = $usuario_errado['pagina_id'];
				if (in_array($usuario_errado_email, $usuarios_contados)) {
				    $conn->query("DELETE FROM Usuarios WHERE id = $usuario_errado_id");
					//error_log("repetido: $usuario_errado_id // $usuario_errado_email // $usuario_errado_pagina_id");
				    continue;
				} else {
					array_push($usuarios_contados, $usuario_errado_email);
				}
				$usuario_certo_dados = return_user_info($usuario_errado_id);
				if ($usuario_certo_dados != false) {
					$usuario_certo_origem = $usuario_certo_dados[0];
					$usuario_certo_senha = $usuario_certo_dados[1];
					//error_log("corrigir: $usuario_errado_id // $usuario_errado_email");
					$conn->query("UPDATE Usuarios SET origem = '$usuario_certo_origem', senha = '$usuario_certo_senha' WHERE id = $usuario_errado_id");
				}
			}
		}
	}
	
	if (isset($_POST['trigger_atualizar_textos_size'])) {
		$textos = $conn->query("SELECT id, verbete_content FROM Textos");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$conn->query("UPDATE Textos SET size = $verbete_value WHERE id = $texto_id");
			}
		}
		$textos = $conn->query("SELECT id, verbete_content FROM Textos_arquivo");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$conn->query("UPDATE Textos_arquivo SET size = $verbete_value WHERE id = $texto_id");
			}
		}
	}
	
	if (isset($_POST['funcoes_gerais'])) {
		$conn->query("TRUNCATE `Ubwiki`.`sim_detalhes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_edicoes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_edicoes_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_etapas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_etapas_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_gerados`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_provas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_provas_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_questoes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_questoes_arquivo`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_respostas`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_textos_apoio`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_textos_apoio_arquivo`");
	}
	
	if (isset($_POST['funcoes_gerais2'])) {
		$conn->query("TRUNCATE `Ubwiki`.`sim_detalhes`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_gerados`");
		$conn->query("TRUNCATE `Ubwiki`.`sim_respostas`");
	}
	
	if (isset($_POST['funcoes_gerais3'])) {
		$cursos = $conn->query("SELECT id FROM Cursos");
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$find_curso_id = $curso['id'];
				reconstruir_busca($find_curso_id);
			}
		}
	}
	
	if (isset($_POST['funcoes_gerais4'])) {
		$textos_antigos = $conn->query("SELECT id, pagina_id FROM Textos_arquivo WHERE tipo = 'verbete' AND texto_id IS NULL");
		if ($textos_antigos->num_rows > 0) {
			while ($texto_antigo = $textos_antigos->fetch_assoc()) {
				$texto_antigo_id = $texto_antigo['id'];
				$texto_antigo_pagina_id = $texto_antigo['pagina_id'];
				$texto_antigo_texto_id = return_texto_id('topico', 'verbete', $texto_antigo_pagina_id, false);
				if ($texto_antigo_texto_id != false) {
					$conn->query("UPDATE Textos_arquivo SET texto_id = $texto_antigo_texto_id WHERE id = $texto_antigo_id");
				}
			}
		}
	}
	
	include 'templates/html_head.php';

?>
<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
	<?php
		$template_titulo_context = true;
		$template_titulo = 'Página de Administradores';
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row justify-content-around">
        <div id='coluna_esquerda' class="<?php echo $coluna_classes; ?>">
					<?php
						
						$template_id = 'funcoes_gerais';
						$template_titulo = 'Funções gerais';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
						    <form method='post'>
						        <p>Simulados.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes_red' type='submit' name='funcoes_gerais'>Apagar todos os dados sobre simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Simulados/usuários.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais2'>Apagar dados de regitro em simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Reconstruir busca.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais3'>Reconstruir busca</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Função não-operacional que atualiza o Textos_arquivo.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais4'>Atualizar textos_arquivo</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Atualizar tamanhos dos textos.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='trigger_atualizar_textos_size'>Tamanhos dos textos</button>
						        </div>
						    </form>
						";
						include 'templates/page_element.php';
						
						$template_id = 'traducoes';
						$template_titulo = 'Traduções';
						$template_conteudo = false;
						$template_conteudo .= "<div class='row d-flex justify-content-center'>";
						
						$artefato_tipo = 'acesso_traducoes';
						$artefato_titulo = 'Acessar página de traduções';
						$artefato_col_limit = 'col-lg-4';
						$artefato_link = 'traducoes.php';
						$fa_icone = 'fa-language';
						$fa_color = 'text-primary';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$template_conteudo .= "</div>";
						include 'templates/page_element.php';
						
						$template_id = 'atualizacao';
						$template_titulo = 'Atualização';
						$template_conteudo = false;
						$template_conteudo .= "
						    <form method='post'>
						        <p>Atualização desde 20191204</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='trigger_atualizacao' value='20191210'>Atualizar página</button>
				                </div>
						    </form>
						";
						include 'templates/page_element.php';
						
						$template_id = 'emails_usuarios';
						$template_titulo = 'Emails dos usuários';
						$template_conteudo = false;
						$template_conteudo .= "<div class='row d-flex justify-content-center'>";
						
						$artefato_tipo = 'emails_usuarios';
						$artefato_titulo = 'Listar emails dos usuários';
						$artefato_col_limit = 'col-lg-4';
						$artefato_button = 'carregar_emails';
						$fa_icone = 'fa-at';
						$fa_color = 'text-danger';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$template_conteudo .= "</div>";
						$template_conteudo .= "<ul id='lista_usuarios_emails'></ul>";
						
						include 'templates/page_element.php';
					
					?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).on('click', '#trigger_emails_usuarios', function () {
        $(this).hide();
        $.post('engine.php', {
            'listar_usuarios_emails': true,
        }, function (data) {
            if (data != 0) {
                $('#lista_usuarios_emails').append(data);
            }
        });
    });
</script>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_admin';
	include 'templates/esconder_anotacoes.php';
?>
</html>
