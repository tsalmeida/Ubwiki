<?php

	//TODO: Função para confirmar mudança de senha por email.

	include 'engine.php';

	$pagina_tipo = 'admin';
    $pagina_id = 2;

	if ($user_tipo != 'admin') {
		header("Location:ubwiki.php");
	}

//    $conn->query("ALTER TABLE `travelogue` CHANGE `type` `type` VARCHAR(20) NOT NULL DEFAULT 'other';");
//    $conn->query("CREATE TABLE `Ubwiki`.`travelogue_codes` ( `id` INT NOT NULL , `title` VARCHAR(20) NULL DEFAULT NULL , `icon` VARCHAR(30) NULL DEFAULT NULL , `color` VARCHAR(20) NULL DEFAULT NULL , `description` VARCHAR(280) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
//    $conn->query("INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('favorite', 'fas fa-heart', 'red', 'A personal favorite');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('lyrics', 'fas fa-feather', 'purple', 'Good lyrics');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('hifi', 'fas fa-waveform', 'teal', 'Great audio quality');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('relaxing', 'fas fa-blanket', 'purple', 'Relaxing');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('heavy', 'fas fa-weight-hanging', 'blue', 'Heavy sound');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('vibe', 'fas fa-pepper-hot', 'red', 'Great vibe');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('complex', 'fas fa-brain-circuit', 'orange', 'Complex');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('instrumental', 'fas fa-trumpet', 'yellow', 'Instrumental');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('live', 'fas fa-guitars', 'pink', 'Recorded live');
    //INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('lists', 'fas fa-award', 'yellow', 'Critically acclaimed');
//	INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('bookmark', 'fas fa-bookmark', 'red', 'Bookmark');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('thumbsup', 'fas fa-thumbs-up', 'blue', 'Thumbs up');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('thumbsdown', 'fas fa-thumbs-down', 'red', 'Thumbs down');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('thumbtack', 'fas fa-thumbtack', 'yellow', 'Thumbtack');
//INSERT INTO travelogue_codes (title, icon, color, description) VALUES ('pointer', 'fas fa-arrow-pointer', 'white', 'Pointer');
    //");

	if (isset($_POST['trigger_atualizacao'])) {
        $conn->query("TRUNCATE `ubwiki`.`travelogue`");
        $query = prepare_query("SELECT * FROM old_travelogue");
        $old_logs = $conn->query($query);
        if ($old_logs->num_rows > 0) {
//			$count = 0;
            while ($old_log = $old_logs->fetch_assoc()) {
//                $count++;
//                if ($count >= 10) {
//                    break;
//                }
                $old_log['type'] = strtolower($old_log['type']);
                $releasedate = 'NULL';
                $datexp = 'NULL';
                if ($old_log['releasemonth'] != '') {
                    if (strlen($old_log['releasemonth']) == 1) {
                        $old_log['releasemonth'] = "0{$old_log['releasemonth']}";
                    }
                    $releasedate = "{$old_log['releaseyear']}{$old_log['releasemonth']}";
                } else {
                    $releasedate = $old_log['releaseyear'];
                }
                if ($old_log['otherlistens'] != '') {
                    $datexp = "{$old_log['firstlisten']}, {$old_log['otherlistens']}";
                } else {
                    $datexp = $old_log['firstlisten'];
                }
                $datexp = str_replace('/', '', $datexp);
                $codes = array();
                if ($old_log['favorite'] == '1') {
                    $codes['favorite'] = true;
                } else {
                    $codes['favorite'] = false;
                }
                if ($old_log['good_lyrics'] == '1') {
                    $codes['lyrics'] = true;
                } else {
                    $codes['lyrics'] = false;
                }
                if ($old_log['hifi'] == '1') {
                    $codes['hifi'] = true;
                } else {
                    $codes['hifi'] = false;
                }
                if ($old_log['relaxing'] == '1') {
                    $codes['relaxing'] = true;
                } else {
                    $codes['relaxing'] = false;
                }
                if ($old_log['heavy'] == '1') {
                    $codes['heavy'] = true;
                } else {
                    $codes['heavy'] = false;
                }
                if ($old_log['great_feel'] == '1') {
                    $codes['vibe'] = true;
                } else {
                    $codes['vibe'] = false;
                }
                if ($old_log['complex'] == '1') {
                    $codes['complex'] = true;
                } else {
                    $codes['complex'] = false;
                }
                if ($old_log['instrumental'] == '1') {
                    $codes['instrumental'] = true;
                } else {
                    $codes['instrumental'] = false;
                }
                if ($old_log['live'] == '1') {
                    $codes['live'] = true;
                } else {
                    $codes['live'] = false;
                }
                if ($old_log['lists'] == '1') {
                    $codes['lists'] = true;
                } else {
                    $codes['lists'] = false;
                }
                $codes = serialize($codes);
				$old_log['title'] = mysqli_real_escape_string($conn, $old_log['title']);
                $old_log['creator'] = mysqli_real_escape_string($conn, $old_log['creator']);
                $old_log['comments'] = mysqli_real_escape_string($conn, $old_log['comments']);
                $old_log['otherrelevant'] = mysqli_real_escape_string($conn, $old_log['otherrelevant']);
                $query_transfer = prepare_query("INSERT INTO travelogue (user_id, type, codes, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant) VALUES (1, '{$old_log['type']}', '$codes', '$releasedate', '{$old_log['title']}', '{$old_log['creator']}', '{$old_log['genre']}', '$datexp', '{$old_log['yourrating']}', '{$old_log['comments']}', '{$old_log['otherrelevant']}')", 'log');
                $conn->query($query_transfer);
            }
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
<body class="bg-light">
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
				$fa_color = 'link-primary';
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
				$fa_color = 'link-danger';
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
				$fa_icone = 'fad fa-code-pull-request';
				$fa_color = 'link-purple';

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
						$template_conteudo .= "<li class='list-group-item list-group-item-info font-monospace'><small>https://www.ubwiki.com.br/ubwiki/?credito={$credito_codigo}</small></li>";
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
						        	<button class='btn btn-danger' type='submit' name='funcoes_gerais'>Apagar todos os dados sobre simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Simulados/usuários.</p>
						        <div class='row justify-content-center'>
						        	<button class='btn btn-primary' type='submit' name='funcoes_gerais2'>Apagar dados de regitro em simulados</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Reconstruir busca.</p>
						        <div class='row justify-content-center'>
						        	<button class='btn btn-primary' type='submit' name='funcoes_gerais3'>Reconstruir busca</button>
						        </div>
						    </form>
						    <form method='post'>
						        <p>Atualizar tamanhos dos textos.</p>
						        <div class='row justify-content-center'>
						        	<button class='btn btn-primary' type='submit' name='trigger_atualizar_textos_size'>Tamanhos dos textos</button>
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


	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_admin';
	include 'templates/esconder_anotacoes.php';
?>
</html>

