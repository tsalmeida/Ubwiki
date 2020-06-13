<?php
	
	include 'engine.php';
	
	$pagina_tipo = 'admin';
	
	if ($user_tipo != 'admin') {
		header("Location:ubwiki.php");
	}
	
	if (isset($_POST['trigger_atualizacao'])) {
	    adicionar_chave_traducao('Busca em títulos de páginas', 1);
	}
	
	if (isset($_POST['novos_creditos'])) {
		$count = 0;
		while ($count < 300) {
			$count++;
			$novo_credito = generateRandomString(8, 'capsintegers');
			registrar_credito($novo_credito, 50);
		}
	}
	
	if (isset($_POST['trigger_atualizar_textos_size'])) {
	    $query = prepare_query("SELECT id, verbete_content FROM Textos");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$query = prepare_query("UPDATE Textos SET size = $verbete_value WHERE id = $texto_id");
				$conn->query($query);
			}
		}
		$query = prepare_query("SELECT id, verbete_content FROM Textos_arquivo");
		$textos = $conn->query($query);
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_id = $texto['id'];
				$texto_verbete_content = $texto['verbete_content'];
				$verbete_value = strlen($texto_verbete_content);
				$query = prepare_query("UPDATE Textos_arquivo SET size = $verbete_value WHERE id = $texto_id");
				$conn->query($query);
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
	    $query = prepare_query("SELECT id FROM Cursos");
		$cursos = $conn->query($query);
		if ($cursos->num_rows > 0) {
			while ($curso = $cursos->fetch_assoc()) {
				$find_curso_id = $curso['id'];
				reconstruir_busca($find_curso_id);
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
        <div id='coluna_esquerda' class="<?php echo $coluna_media_classes; ?>">
					<?php
						
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
						
						$template_id = 'atualizacao';
						$template_titulo = 'Atualização';
						$template_conteudo = false;
						$template_conteudo .= "
						    <form method='post'>
						        <div class='row justify-content-center'>";
						
						$artefato_tipo = 'atualizacao';
						$artefato_titulo = 'Atualização';
						$artefato_col_limit = 'col-lg-4';
						$artefato_button = 'trigger_atualizacao';
						$fa_icone = 'fab fa-github';
						$fa_color = 'text-secondary';
						
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$template_conteudo .= "
				                </div>
						    </form>
						";
						include 'templates/page_element.php';
						
						$template_id = 'credito_gratis';
						$template_titulo = 'Links para créditos gratuitos';
						$template_conteudo = false;
						$template_conteudo .= "<p>Próximos cinco créditos gratuitos (total de 300 originalmente disponíveis, cada um valendo 50 créditos).</p>";

						$query = prepare_query("SELECT codigo FROM Creditos WHERE id < 301 AND estado = 1 ORDER BY id");
						$creditos = $conn->query($query);
						if ($creditos->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							$count = 0;
							while ($credito = $creditos->fetch_assoc()) {
								$count++;
								if ($count > 15) {
									break;
								}
								$credito_codigo = $credito['codigo'];
								$template_conteudo .= "<li class='list-group-item list-group-item-info fontstack-mono'><small>https://www.ubwiki.com.br/ubwiki/?credito={$credito_codigo}</small></li>";
							}
							$template_conteudo .= '</ul>';
						}
						
						include 'templates/page_element.php';
						
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
						        <p>Atualizar tamanhos dos textos.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='trigger_atualizar_textos_size'>Tamanhos dos textos</button>
						        </div>
						    </form>
						";
						include 'templates/page_element.php';
					
					?>
        </div>
    </div>
</div>
<?php
	include 'pagina/modal_languages.php';
?>
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
