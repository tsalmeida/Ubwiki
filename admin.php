<?php
	
	include 'engine.php';
	
	if (isset($_POST['trigger_atualizacao'])) {
		$conn->query("ALTER TABLE `Paginas` ADD `estado` TINYINT NULL DEFAULT '0' AFTER `tipo`;");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("INSERT INTO Paginas (item_id, tipo, estado, user_id) VALUES (1, 'sistema', 1, 1)");
		$conn->query("ALTER TABLE `Bookmarks` CHANGE `topico_id` `pagina_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Completed` CHANGE `topico_id` `pagina_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Forum` CHANGE `page_id` `pagina_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Paginas_elementos` CHANGE `page_id` `pagina_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Bookmarks` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `topico_id`;");
		$conn->query("ALTER TABLE `Completed` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `topico_id`;");
		$conn->query("ALTER TABLE `Forum` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `page_id`;");
		$conn->query("ALTER TABLE `Topicos` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `etiqueta_id`;");
		$conn->query("ALTER TABLE `Elementos` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `etiqueta_id`;");
		$conn->query("ALTER TABLE `Textos` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `page_id`;");
		$conn->query("ALTER TABLE `Textos_arquivo` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `page_id`;");
		$conn->query("ALTER TABLE `Forum` CHANGE `page_tipo` `pagina_tipo` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Textos` ADD `pagina_tipo` VARCHAR(255) NULL DEFAULT NULL AFTER `pagina_id`;");
		$conn->query("ALTER TABLE `Textos_arquivo` ADD `pagina_tipo` VARCHAR(255) NULL DEFAULT NULL AFTER `pagina_id`;");
		$topicos = $conn->query("SELECT id, estado_pagina FROM Topicos WHERE pagina_id IS NULL ORDER BY id");
		while ($topico = $topicos->fetch_assoc()) {
			$topico_id = $topico['id'];
			$topico_estado_pagina = $topico['estado_pagina'];
			$conn->query("INSERT INTO Paginas (item_id, tipo, estado) VALUES ($topico_id, 'topico', $topico_estado_pagina)");
			$nova_pagina_id = $conn->insert_id;
			$conn->query("UPDATE Topicos SET pagina_id = $nova_pagina_id WHERE id = $topico_id");
			$conn->query("UPDATE Textos SET pagina_id = $nova_pagina_id, pagina_tipo = 'topico' WHERE page_id = $topico_id AND (tipo = 'verbete' OR tipo = 'anotacoes')");
			$conn->query("UPDATE Textos_arquivo SET pagina_id = $nova_pagina_id, pagina_tipo = 'topico' WHERE page_id = $topico_id AND (tipo = 'verbete' OR tipo = 'anotacoes')");
			$elementos = $conn->query("SELECT elemento_id, tipo, user_id FROM Verbetes_elementos WHERE page_id = $topico_id AND tipo_pagina = 'verbete'");
			$conn->query("UPDATE Forum SET pagina_id = $nova_pagina_id WHERE pagina_id = $topico_id AND pagina_tipo = 'topico'");
			$conn->query("UPDATE Bookmarks SET pagina_id = $nova_pagina_id WHERE pagina_id = $topico_id");
			$conn->query("UPDATE Completed SET pagina_id = $nova_pagina_id WHERE pagina_id = $topico_id");
			if ($elementos->num_rows > 0) {
				while ($elemento = $elementos->fetch_assoc()) {
					$elemento_id = $elemento['elemento_id'];
					$elemento_tipo = $elemento['tipo'];
					$elemento_user_id = $elemento['user_id'];
					$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, elemento_id, tipo, user_id) VALUES ($nova_pagina_id, 'topico', $elemento_id, '$elemento_tipo', $elemento_user_id)");
				}
			}
		}
		$elementos = $conn->query("SELECT id FROM Elementos WHERE pagina_id IS NULL ORDER BY id");
		while ($elemento = $elementos->fetch_assoc()) {
			$elemento_id = $elemento['id'];
			$conn->query("INSERT INTO Paginas (item_id, tipo) VALUES ($elemento_id, 'elemento')");
			$nova_pagina_id = $conn->insert_id;
			$conn->query("UPDATE Elementos SET pagina_id = $nova_pagina_id WHERE id = $elemento_id");
			$conn->query("UPDATE Textos SET pagina_id = $nova_pagina_id, pagina_tipo = 'elemento', tipo = 'verbete' WHERE page_id = $elemento_id AND tipo = 'verbete_elemento'");
			$conn->query("UPDATE Textos_arquivo SET pagina_id = $nova_pagina_id, pagina_tipo = 'elemento', tipo = 'verbete' WHERE page_id = $elemento_id AND tipo = 'verbete_elemento'");
			$conn->query("UPDATE Textos SET pagina_id = $nova_pagina_id, pagina_tipo = 'elemento', tipo = 'anotacoes' WHERE page_id = $elemento_id AND tipo = 'anotacoes_elemento'");
			$conn->query("UPDATE Textos_arquivo SET pagina_id = $nova_pagina_id, pagina_tipo = 'elemento', tipo = 'anotacoes' WHERE page_id = $elemento_id AND tipo = 'anotacoes_elemento'");
			$conn->query("UPDATE Bookmarks SET pagina_id = $nova_pagina_id WHERE elemento_id = $elemento_id");
		}
		$elementos = $conn->query("SELECT id, iframe FROM Elementos WHERE iframe IS NOT NULL");
		if ($elementos->num_rows > 0) {
			while ($elemento = $elementos->fetch_assoc()) {
				$elemento_id = $elemento['id'];
				$elemento_iframe = $elemento['iframe'];
				$novo_elemento_iframe = base64_decode($elemento_iframe);
				$novo_elemento_iframe = mysqli_real_escape_string($conn, $novo_elemento_iframe);
				$conn->query("UPDATE Elementos SET iframe = '$novo_elemento_iframe' WHERE id = $elemento_id");
			}
		}
		$conn->query("ALTER TABLE `Concursos` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `id`;");
		$conn->query("RENAME TABLE `Ubwiki`.`Concursos` TO `Ubwiki`.`Cursos`;");
		$conn->query("ALTER TABLE `Etiquetados` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Materias` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Searchbar` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_edicoes` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_etapas` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_gerados` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_provas` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_questoes` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_respostas` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `sim_textos_apoio` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Textos` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Textos_arquivo` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Topicos` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Verbetes_elementos` CHANGE `concurso_id` `curso_id` INT(11) NULL DEFAULT NULL;");
		$conn->query("ALTER TABLE `Materias` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `etiqueta_id`;");
		$conn->query("ALTER TABLE `Visualizacoes` ADD `extra2` VARCHAR(255) NULL AFTER `extra`;");
		$conn->query("ALTER TABLE `Paginas` ADD `compartilhamento` VARCHAR(255) NULL DEFAULT NULL AFTER `estado`;");
		$conn->query("ALTER TABLE `Paginas_elementos` ADD `extra` VARCHAR(255) NULL DEFAULT NULL AFTER `tipo`;");
		$anotacoes_privadas = $conn->query("SELECT id FROM Textos WHERE tipo = 'anotacao_privada'");
		if ($anotacoes_privadas->num_rows > 0) {
			while ($anotacao_privada = $anotacoes_privadas->fetch_assoc()) {
				$anotacao_privada_id = $anotacao_privada['id'];
				$conn->query("UPDATE Textos SET tipo = 'anotacoes', compartilhamento = 'privado' WHERE id = $anotacao_privada_id");
			}
		}
		$conn->query("ALTER TABLE `Secoes` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `elemento_id`;");
		$conn->query("ALTER TABLE `Secoes` ADD `secao_pagina_id` INT(11) NULL DEFAULT NULL AFTER `pagina_id`;");
		$conn->query("ALTER TABLE `Paginas` ADD `etiqueta_id` INT(11) NULL DEFAULT NULL AFTER `compartilhamento`;");
		$conn->query("UPDATE Usuarios SET special = 19815848 WHERE id = 2");
		$conn->query("UPDATE Usuarios SET special = 15030 WHERE id = 3");
		$conn->query("UPDATE Usuarios SET special = 17091979 WHERE id = 4");
		$conn->query("UPDATE Usuarios SET special = 2951720 WHERE id = 118");
		$conn->query("UPDATE Usuarios SET special = 'joaodaniel' WHERE id = 117");
		$conn->query("UPDATE Usuarios SET special = 'maladiplomatica' WHERE id = 116");
		$conn->query("DELETE FROM Etiquetas WHERE tipo = 'secao'");
		$elementos_secionados = $conn->query("SELECT DISTINCT id, elemento_id, user_id, titulo, texto_id FROM Secoes ORDER BY Ordem");
		if ($elementos_secionados->num_rows > 0) {
			while ($elemento_secionado = $elementos_secionados->fetch_assoc()) {
				$elemento_secionado_id = $elemento_secionado['id'];
				$elemento_secionado_elemento_id = $elemento_secionado['elemento_id'];
				$elemento_secionado_user_id = $elemento_secionado['user_id'];
				$elemento_secionado_titulo = $elemento_secionado['titulo'];
				$elemento_secionado_texto_id = $elemento_secionado['texto_id'];
				$elemento_secionado_pagina_id = return_pagina_id($elemento_secionado_elemento_id, 'elemento');
				$conn->query("UPDATE Secoes SET pagina_id = $elemento_secionado_pagina_id WHERE elemento_id = $elemento_secionado_elemento_id");
				$elemento_original_titulo = return_pagina_titulo($elemento_secionado_pagina_id);
				$etiqueta_titulo = "$elemento_original_titulo // $elemento_secionado_titulo";
				$etiqueta_titulo = mysqli_real_escape_string($conn, $etiqueta_titulo);
				$conn->query("INSERT INTO Etiquetas (tipo, titulo, user_id) VALUES ('secao', '$etiqueta_titulo',$elemento_secionado_user_id)");
				$nova_etiqueta_id = $conn->insert_id;
				$conn->query("INSERT INTO Paginas (item_id, tipo, compartilhamento, etiqueta_id, user_id) VALUES ($elemento_secionado_pagina_id, 'secao', 'igual à página original', $nova_etiqueta_id, $elemento_secionado_user_id)");
				$secao_nova_pagina_id = $conn->insert_id;
				$conn->query("UPDATE Secoes SET secao_pagina_id = $secao_nova_pagina_id WHERE id = $elemento_secionado_id");
				$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($secao_nova_pagina_id, 'secao', 'titulo', '$elemento_secionado_titulo', $elemento_secionado_user_id)");
				$conn->query("UPDATE Textos SET pagina_id = $secao_nova_pagina_id, tipo = 'verbete', pagina_tipo = 'secao' WHERE id = $elemento_secionado_texto_id");
			}
		}
		$conn->query("ALTER TABLE `Grupos` ADD `pagina_id` INT(11) NULL DEFAULT NULL AFTER `estado`;");
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
	    $topicos = $conn->query("SELECT id, estado_pagina, pagina_id FROM Topicos");
	    if ($topicos->num_rows > 0) {
	        while ($topico = $topicos->fetch_assoc()) {
	            $topico_id = $topico['id'];
	            $topico_estado_pagina = $topico['estado_pagina'];
	            $topico_pagina_id = $topico['pagina_id'];
	            $conn->query("UPDATE Paginas SET estado = $topico_estado_pagina WHERE id = $topico_id");
            }
        }
	}
	
	if (isset($_POST['reconstruir_busca'])) {
		$reconstruir_curso_id = $_POST['reconstruir_curso'];
		$ordem = 0;
		$conn->query("DELETE FROM Searchbar WHERE curso_id = $reconstruir_curso_id");
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE curso_id = $reconstruir_curso_id AND estado = 1 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$reconstruir_materia_id = $row['id'];
				$reconstruir_materia_titulo = $row["titulo"];
				$ordem++;
				$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_materia_id , '$reconstruir_materia_titulo', 'materia')");
			}
		}
		$result = $conn->query("SELECT id, titulo FROM Materias WHERE curso_id = $reconstruir_curso_id AND estado = 1 ORDER BY ordem");
		while ($row = $result->fetch_assoc()) {
			$reconstruir_materia_id = $row['id'];
			$result2 = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = $reconstruir_materia_id ORDER BY ordem");
			if ($result2->num_rows > 0) {
				while ($row2 = $result2->fetch_assoc()) {
					$reconstruir_topico_id = $row2["id"];
					$reconstruir_topico_nivel = $row2['nivel'];
					$reconstruir_topico_nivel1 = $row2["nivel1"];
					$reconstruir_topico_nivel2 = $row2["nivel2"];
					$reconstruir_topico_nivel3 = $row2["nivel3"];
					$reconstruir_topico_nivel4 = $row2["nivel4"];
					$reconstruir_topico_nivel5 = $row2["nivel5"];
					$ordem++;
					if ($reconstruir_topico_nivel == 1) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel1', 'topico')");
					} elseif ($reconstruir_topico_nivel == 2) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel2', 'topico')");
					} elseif ($reconstruir_topico_nivel == 3) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel3', 'topico')");
					} elseif ($reconstruir_topico_nivel == 4) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel4', 'topico')");
					} elseif ($reconstruir_topico_nivel == 5) {
						$conn->query("INSERT INTO Searchbar (ordem, curso_id, page_id, chave, tipo) VALUES ($ordem, $reconstruir_curso_id, $reconstruir_topico_id, '$reconstruir_topico_nivel5', 'topico')");
					}
				}
			}
		}
	}
	
	if (isset($_POST['editar_topicos_curso'])) {
		$curso_editar = $_POST['editar_topicos_curso'];
		header("Location:edicao_topicos.php?curso_id=$curso_editar");
	}
	
	if (isset($_POST['novo_curso_titulo'])) {
		$novo_curso_titulo = $_POST['novo_curso_titulo'];
		$novo_curso_sigla = $_POST['novo_curso_sigla'];
		$result = $conn->query("SELECT titulo, sigla FROM cursos WHERE sigla = '$novo_curso_sigla' AND titulo = '$novo_curso_titulo'");
		if ($result->num_rows == 0) {
			$conn->query("INSERT INTO cursos (titulo, sigla) VALUES ('$novo_curso_titulo', '$novo_curso_sigla')");
		} else {
			return false;
		}
	}
	$lista_cursos = array();
	$cursos = $conn->query("SELECT id, titulo, estado FROM cursos");
	if ($cursos->num_rows > 0) {
		while ($curso = $cursos->fetch_assoc()) {
			$curso_id = $curso['id'];
			$curso_titulo = $curso['titulo'];
			$curso_estado = $curso['estado'];
			$um_curso = array($curso_id, $curso_titulo, $curso_estado);
			array_push($lista_cursos, $um_curso);
		}
	}
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';

?>
<body>
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
						$template_id = 'editar_topicos';
						$template_titulo = 'Editar tópicos';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
                <form method='post' formaction='edicao_topicos.php'>
                    <p>Com esta ferramenta, o administrador pode alterar a tabela de tópicos de um curso. O objetivo é maximizar a utilidade do edital original para as atividades do estudante.</p>
                     <label for='editar_topicos_curso'>Curso</label>
                        <select class='form-control' name='editar_topicos_curso'>
                        ";
						foreach ($lista_cursos as $um_curso) {
							if ($um_curso[2] == 0) {
								$estado_curso = '(desativado)';
							} else {
								$estado_curso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_curso[0]'>$um_curso[1] / $estado_curso</option>";
						}
						$template_conteudo .= "</select>";
						$template_conteudo .= "
							<div class='row justify-content-center'>
								<button class='$button_classes'>Acessar ferramenta</button></form>
							</div>
					    ";
						include 'templates/page_element.php';
						
						$template_id = 'acrescentar_curso';
						$template_titulo = 'Acrescentar curso';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "<form method='post' formaction='edicao_topicos.php'>";
						$template_conteudo .= "
                            <p>Cada curso tem um título completo e uma sigla. Este é o primeiro passo no processo de inclusão de novos cursos.</p>
                            <input type='text' id='novo_curso_titulo' name='novo_curso_titulo' class='form-control validate' required>
                            <label data-error='inválido' data-successd='válido' for='novo_curso_titulo'>Título do curso</label>
                            <input type='text' id='novo_curso_sigla' name='novo_curso_sigla' class='form-control validate' required>
                            <label data-error='inválido' data-successd='válido' for='novo_curso_sigla'>Sigla do curso</label>
                            <div class='row justify-content-center'>
                            	<button class='$button_classes' type='submit'>Acrescentar curso</button>
                            </div>
                            </form>
                          ";
						include 'templates/page_element.php';
						
						$template_id = 'barra_busca';
						$template_titulo = 'Barra de busca';
						$template_botoes = false;
						$template_conteudo = false;
						$template_conteudo .= "
                            <form method='post'>
                			<p>Reconstruir tabela de opções da barra de busca.</p>
                    		<label for='editar_topicos_curso'>Curso</label>
                    		<select class='form-control' name='reconstruir_curso' id='reconstruir_curso'>
                        ";
						
						foreach ($lista_cursos as $um_curso) {
							if ($um_curso[2] == 0) {
								$estado_curso = '(desativado)';
							} else {
								$estado_curso = '(ativado)';
							}
							$template_conteudo .= "<option value='$um_curso[0]'>$um_curso[1] / $estado_curso</option>";
						}
						
						$template_conteudo .= "
                              </select>
                              <div class='row justify-content-center'>
                              	<button class='$button_classes' type='submit' name='reconstruir_busca'>Reconstruir
                              </div>
                              </button>
                          </form>
                        ";
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
						        <p>Função geral de atualização.</p>
						        <div class='row justify-content-center'>
						        	<button class='$button_classes' type='submit' name='funcoes_gerais3'>Função geral de atualização</button>
						        </div>
						    </form>
						";
						include 'templates/page_element.php';
						
						$template_id = 'atualizacao';
						$template_titulo = 'Atualização';
						$template_botoes = false;
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
					
					?>

        </div>
        <div id='coluna_direita' class='<?php echo $coluna_classes; ?>'>
					<?php
						
						$template_id = 'anotacoes_admin';
						$template_titulo = 'Anotações';
						$template_quill_page_id = 0;
						$template_conteudo = include 'templates/template_quill.php';
						include 'templates/page_element.php';
					
					?>
        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
</body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
	$anotacoes_id = 'anotacoes_admin';
	include 'templates/esconder_anotacoes.php';
?>
</html>
