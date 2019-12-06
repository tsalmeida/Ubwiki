<?php
	
	include 'engine.php';
	
	if (isset($_GET['texto_id'])) {
		$texto_id = $_GET['texto_id'];
	} else {
		header('Location:index.php');
	}
	$texto_anotacao = false;
	if ($texto_id == 0) {
		$texto_tipo = 'anotacao_privada';
		$texto_titulo = false;
		$texto_page_id = false;
		$texto_criacao = false;
		$texto_verbete_content = false;
		$texto_user_id = $user_id;
	} else {
		$textos = $conn->query("SELECT tipo, titulo, page_id, criacao, verbete_content, user_id FROM Textos WHERE id = $texto_id");
		if ($textos->num_rows > 0) {
			while ($texto = $textos->fetch_assoc()) {
				$texto_tipo = $texto['tipo'];
				$texto_titulo = $texto['titulo'];
				$texto_page_id = $texto['page_id'];
				$texto_criacao = $texto['criacao'];
				$texto_verbete_content = $texto['verbete_content'];
				$texto_user_id = $texto['user_id'];
				$check = false;
				if (strpos($texto_tipo, 'anotac') !== false) {
					$texto_anotacao = true;
					if ($texto_user_id != $user_id) {
						header('Location:index.php');
					}
				}
			}
		}
	}
	$html_head_template_quill = true;
	include 'templates/html_head.php';

?>

<body class="grey lighten-5">
<?php
	include 'templates/navbar.php';
?>

<div class="container bg-white">

    <div class="row">
        <div id="coluna_unica" class="col">
            <div id='quill_pagina_edicao' class="row justify-content-center">
	            <?php
		            if ($texto_anotacao == true) {
		            	$mudar_anotacao_titulo = true;
		            	echo "<div class='w-100 md-form'>
                                  <input type='text' class='form-control' name='novo_texto_titulo' id='novo_texto_titulo' maxlength='60' required>
                                  <label for='nova_prova_titulo'>Título</label>
                              </div>";
		            }
		            echo "<h1 id='texto_titulo'>$texto_titulo</h1>";
	            ?>
	            
							<?php
								$template_id = $texto_tipo;
								$template_quill_initial_state = 'edicao';
								$template_quill_page_id = $texto_page_id;
								$template_quill_pagina_de_edicao = true;
								$quill_instance = include 'templates/template_quill.php';
								echo $quill_instance;
							?>
            </div>
        </div>
    </div>

</div>

</body>

<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>
