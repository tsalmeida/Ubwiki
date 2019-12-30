<?php
	
	include 'engine.php';
	
	if (isset($_GET['texto_id'])) {
		$texto_id = $_GET['texto_id'];
		$pagina_texto_id = $texto_id;
	}
	if (($texto_id == false) || ($texto_id == '')) {
		header("Location:pagina.php?pagina_id=4");
	}
	
	if (isset($_POST['destruir_anotacao'])) {
		$conn->query("DELETE FROM Textos WHERE id = $texto_id");
		header('Location:escritorio.php');
	}
	
	$texto_anotacao = false;
	$texto_editar_titulo = false;
	if ($texto_id == 'new') {
		if ($conn->query("INSERT INTO Textos (tipo, compartilhamento, page_id, pagina_id, user_id, verbete_html, verbete_text, verbete_content) VALUES ('anotacoes', 'privado', 0, 0, $user_id, FALSE, FALSE, FALSE)") === true) {
			$new_texto_id = $conn->insert_id;
			header("Location:edicao_textos.php?texto_id=$new_texto_id");
		}
	} else {
		$textos = $conn->query("SELECT tipo, titulo, page_id, pagina_id, pagina_tipo, estado_texto, compartilhamento, criacao, verbete_content, user_id FROM Textos WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_tipo = $texto['tipo'];
				$texto_titulo = $texto['titulo'];
				$texto_page_id = $texto['page_id'];
				$texto_pagina_id = $texto['pagina_id'];
				$texto_pagina_tipo = $texto['pagina_tipo'];
				$texto_criacao = $texto['criacao'];
				$texto_estado = $texto['estado_texto'];
				$texto_compartilhamento = $texto['compartilhamento'];
				$texto_verbete_content = $texto['verbete_content'];
				$texto_user_id = $texto['user_id'];
				$check = false;
				if ((strpos($texto_tipo, 'anotac') !== false) || ($texto_tipo == 'verbete_user')) {
					$texto_anotacao = true;
					if (($texto_compartilhamento != false) && ($texto_user_id != $user_id)) {
					    $comps = $conn->query("SELECT recipiente_id, compartilhamento FROM Compartilhamento WHERE item_tipo = 'texto' AND item_id = $texto_id");
					    if ($comps->num_rows > 0) {
					        while ($comp = $comps->fetch_assoc()) {
					            $item_comp_compartilhamento = $comp['compartilhamento'];
					            if ($item_comp_compartilhamento == 'grupo') {
					                $item_grupo_id = $comp['recipiente_id'];
					                $check_membro_grupo = check_membro_grupo($user_id, $item_grupo_id);
					                if ($check_membro_grupo == false) {
					                    header('Location:pagina.php?pagina_id=4');
                                    }
                                } elseif ($item_comp_compartilhamento == 'usuario') {
					                $item_usuario_id = $comp['recipiente_id'];
					                if ($item_usuario_id != $user_id) {
					                    header('Location:pagina.php?pagina_id=4');
                                    }
                                }
                            }
                        } else {
					        header('Location:pagina.php?pagina_id=3');
					    }
					}
				}
				if ($texto_page_id === 0) {
					$texto_editar_titulo = true;
				}
			}
		} else {
			header('Location:pagina.php?pagina_id=3');
		}
	}
	$html_head_template_quill = true;
	
	$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra, extra2) VALUES ($user_id, $texto_id, 'texto', '$texto_tipo', 'edicao_textos')");
	
	include 'templates/html_head.php';

?>

<body class="carrara">
<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid bg-white">
    <div class="row">
        <div id="coluna_unica" class="col grey lighten-5">
            <div id='quill_pagina_edicao' class="row justify-content-center grey lighten-5">
							<?php
								if ($texto_editar_titulo == true) {
									echo "<h1 id='texto_titulo' class='w-100 mt-4 grey lighten-5'><input type='text' name='novo_texto_titulo' maxlength='80' value='$texto_titulo' placeholder='Escreva aqui o título' class='border-0 text-center w-100 grey lighten-5'></h1>";
								} else {
									if ($texto_titulo != false) {
										echo "<h1 class='w-100 mt-4 grey lighten-5 text-center'>$texto_titulo</h1>";
									}
								}
								$template_id = $texto_tipo;
								$template_quill_initial_state = 'edicao';
								$template_quill_page_id = $texto_page_id;
								$template_quill_pagina_id = $texto_pagina_id;
								$template_quill_pagina_de_edicao = true;
								$quill_instance = include 'templates/template_quill.php';
								echo $quill_instance;
							?>
            </div>
        </div>
    </div>
	<?php
		
		if ($texto_user_id == $user_id) {
			
			include 'templates/etiquetas_modal.php';
			
			$grupos_do_usuario = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id");
			
			if (isset($_POST['compartilhar_grupo_id'])) {
			    $compartilhar_grupo_id = $_POST['compartilhar_grupo_id'];
			    $conn->query("INSERT INTO Compartilhamento (user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ($user_id, $texto_id, 'texto', 'grupo', $compartilhar_grupo_id)");
            }
			
			$template_modal_div_id = 'modal_compartilhar_anotacao';
			$template_modal_titulo = 'Compartilhamento';
			$template_modal_show_buttons = false;
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
			  <p>Apenas você, como criador original desta anotação, poderá alterar suas opções de compartilhamento. Por favor, analise cuidadosamente as opções abaixo. Versões anteriores do documento estarão sempre disponíveis no histórico (para todos os que tenham acesso à sua versão atual) Todo usuário com acesso à anotação poderá alterar suas etiquetas.</p>
			  <h3>Compartilhar com grupo de estudos</h3>
			  ";
            if ($grupos_do_usuario->num_rows > 0) {
	            $template_modal_body_conteudo .= "
                  <form method='post'>
                    <select name='compartilhar_grupo_id' class='$select_classes'>
                        <option value='' disabled selected>Selecione o grupo de estudos</option>
                ";
                while ($grupo_do_usuario = $grupos_do_usuario->fetch_assoc()) {
                    $grupo_do_usuario_id = $grupo_do_usuario['grupo_id'];
                    $grupo_do_usuario_titulo = return_grupo_titulo_id($grupo_do_usuario_id);
                    $template_modal_body_conteudo .= "<option value='$grupo_do_usuario_id'>$grupo_do_usuario_titulo</option>";
                }
	            $template_modal_body_conteudo .= "
                    </select>
                    <div class='row justify-content-center'>
                        <button class='$button_classes' name='trigger_compartilhar_grupo'>Compartilhar com grupo</button>
                    </div>
                  </form>
                ";
            } else {
                $template_modal_body_conteudo .= "<p class='text-muted'><em>Você não faz parte de nenhum grupo de estudos.</em></p>";
            }
            
            /*$template_modal_body_conteudo .= "
              <form>
              <h3>Compartilhar com outro usuário</h3>
                <select name='compartilhar_usuario' class='$select_classes'>
                    <option value='' disabled selected>Selecione o usuário</option>
                </select>
                <div class='row justify-content-center'>
                    <button class='$button_classes' name='trigger_compartilhar_usuario'>Compartilhar com usuário</button>
                </div>
              </form>
              <h3>Tornar anotação pública.</h3>
              <p>Todo usuário da Ubwiki poderá ler sua anotação, mas não poderá editá-la.</p>
              <h3>Tornar pública e aberta.</h3>
              <p>Todo usuário da Ubwiki poderá ler e editar sua anotação.</p>
          ";*/

			include 'templates/modal.php';
			
			$template_modal_div_id = 'modal_apagar_anotacao';
			$template_modal_titulo = 'Triturar documento';
			$template_modal_show_buttons = false;
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
			  <p>Tem certeza? Não será possível recuperar sua anotação.</p>
	          <form method='post'>
	            <div class='row justify-content-center'>
		            <button class='$button_classes_red' name='destruir_anotacao'>Destruir</button>
	            </div>
	          </form>
            ";
			include 'templates/modal.php';
		}
	
	?>
</div>

</body>

<?php
	$quill_extra_buttons = "
              <span id='salvar_anotacao' class='ml-1' title='Salvar anotação'>
				  <a href='javascript:void(0);'>
					  <i class='fal fa-save fa-fw'></i>
				  </a>
			  </span>
              <span id='anotacao_salva' class='ml-1 text-success' title='Salvar anotação'>
	              <i class='fas fa-save fa-fw'></i>
			  </span>
    ";
	$quill_extra_buttons .= "
              <span class='ml-1' title='Ver histórico'>
                <a href='historico_verbete.php?texto_id=$texto_id'>
                  <i class='fal fa-history fa-fw'></i>
                </a>
              </span>
	";
	if ($texto_user_id == $user_id) {
		$quill_extra_buttons .= "
              <span id='adicionar_etiqueta' class='ml-1' title='Adicionar etiqueta' data-toggle='modal'
                    data-target='#modal_gerenciar_etiquetas'>
				  <a href='javascript:void(0);'>
					  <i class='fal fa-tags fa-fw'></i>
				  </a>
			  </span>
        ";
		if (($texto_anotacao == true) && ($texto_tipo != 'verbete_user')) {
			$quill_extra_buttons .= "
              <span id='compartilhar_anotacao' class='ml-1' title='Editar compartilhamento desta anotação'
                       data-toggle='modal' data-target='#modal_compartilhar_anotacao'>
                  <a href='javascript:void(0);'>
                      <i class='fal fa-users fa-fw'></i>
                  </a>
              </span>
              <span id='apagar_anotacao' class='ml-1' title='Destruir anotação'
                       data-toggle='modal' data-target='#modal_apagar_anotacao'>
                  <a href='javascript:void(0);' class='text-danger'>
                      <i class='fal fa-shredder fa-fw'></i>
                  </a>
              </span>
			";
		}
	}
	$quill_extra_buttons .= "<br>";
	$quill_extra_buttons = mysqli_real_escape_string($conn, $quill_extra_buttons);
	$etiquetas_bottom = true;
	$mdb_select = true;
	$sticky_toolbar = true;
	include 'templates/html_bottom.php';
?>
