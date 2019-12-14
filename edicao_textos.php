<?php
	
	include 'engine.php';

	if (isset($_GET['texto_id'])) {
		$texto_id = $_GET['texto_id'];
		$texto_id = (int)$texto_id;
	}
	if (($texto_id == false) || ($texto_id == '')) {
	    header('Location:index.php');
    }
	$texto_anotacao = false;
	if ($texto_id == 0) {
		if ($conn->query("INSERT INTO Textos (tipo, page_id, user_id, estado_texto, verbete_html, verbete_text, verbete_content) VALUES ('anotacao_privada', 0, $user_id, FALSE, FALSE, FALSE)") === true) {
			$new_texto_id = $conn->insert_id;
			header("Location:edicao_textos.php?texto_id=$new_texto_id");
		}
	} else {
		$textos = $conn->query("SELECT tipo, titulo, page_id, estado_texto, criacao, verbete_content, user_id FROM Textos WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_tipo = $texto['tipo'];
				$texto_titulo = $texto['titulo'];
				$texto_page_id = $texto['page_id'];
				$texto_criacao = $texto['criacao'];
				$texto_estado = $texto['estado_texto'];
				$texto_verbete_content = $texto['verbete_content'];
				$texto_user_id = $texto['user_id'];
				$check = false;
				if ((strpos($texto_tipo, 'anotac') != false) || ($texto_tipo == 'verbete_user')) {
					$texto_anotacao = true;
					if ($texto_user_id != $user_id) {
						header('Location:index.php');
					}
				}
			}
		}
	}
	$html_head_template_quill = true;

	$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina, extra) VALUES ($user_id, $texto_id, 'texto', '$texto_tipo')");

	include 'templates/html_head.php';

?>

<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>

<div class="container bg-white">
    <div class="row grey lighten-5 sticky-top">
        <div class="col">
			<span id="salvar_anotacao" class="ml-1" title="Salvar anotação">
				<a href='javascript:void(0);'>
					<i class='fal fa-save fa-fw'></i>
				</a>
			</span>
            <span id="anotacao_salva" class="ml-1 text-success" title="Salvar anotação">
	            <i class='fas fa-save fa-fw'></i>
			</span>
            <span id="adicionar_etiqueta" class="ml-1" title="Adicionar etiqueta" data-toggle="modal"
                  data-target="#modal_gerenciar_etiquetas">
				<a href='javascript:void(0);'>
					<i class='fal fa-tags fa-fw'></i>
				</a>
			</span>
            <span>
                <?php
	                echo "
                      <a href='historico_verbete.php?texto_id=$texto_id'>
                        <i class='fal fa-history fa-fw'></i>
                      </a>
                    ";
                ?>
            </span>
            <!--<span id="compartilhar_anotacao" class="ml-1" title="Editar compartilhamento desta anotação"
                     data-toggle="modal" data-target="#modal_compartilhar_anotacao">
                <a href="javascript:void(0);">
                    <i class="fal fa-share-alt fa-2x fa-fw"></i>
                </a>
            </span>!-->
        </div>
    </div>
    <div class="row">
        <div id="coluna_unica" class="col grey lighten-5">
            <div id='quill_pagina_edicao' class="row justify-content-center grey lighten-5">
							<?php
								if ($texto_anotacao == true) {
									$mudar_anotacao_titulo = true;
									echo "<h1 id='texto_titulo' class='w-100 mt-4 grey lighten-5'><input type='text' name='novo_texto_titulo' maxlength='80' value='$texto_titulo' placeholder='Escreva aqui o título' class='border-0 text-center w-100 grey lighten-5'></h1>";
								}
								$template_id = $texto_tipo;
								$template_quill_initial_state = 'edicao';
								$template_quill_page_id = $texto_page_id;
								$template_quill_pagina_de_edicao = true;
								$template_quill_load_button = false;
								$quill_instance = include 'templates/template_quill.php';
								echo $quill_instance;
							?>
            </div>
        </div>
    </div>
	<?php
		include 'templates/etiquetas_modal.php';
		
		$template_modal_div_id = 'modal_compartilhar_anotacao';
		$template_modal_titulo = 'Compartilhamento';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
            <p>Compartilhar com outros usuários da Ubwiki</p>
            <p>Tornar anotação pública</p>
        ";
		include 'templates/modal.php';
	
	?>
</div>

</body>

<?php
	
	$etiquetas_bottom = true;
	$mdb_select = true;
	$sticky_toolbar = true;
	include 'templates/html_bottom.php';
?>
