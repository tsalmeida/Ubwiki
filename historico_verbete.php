<?php
	
	include 'engine.php';
	
	if (isset($_GET['texto_id'])) {
		$texto_id = $_GET['texto_id'];
	}
	
	$visualizar_historico_id = false;
	if (isset($_POST['visualizar_historico'])) {
		$visualizar_historico_id = $_POST['visualizar_historico'];
	}
	
	$texto_info = return_texto_info($texto_id);
	
	$texto_titulo = $texto_info[2];
	$texto_tipo = $texto_info[1];
	$texto_page_id = $texto_info[3];
	$texto_user_id = $texto_info[8];
	
	if ((strpos($texto_tipo, 'anotac') !== false) || ($texto_tipo == 'verbete_user')) {
		if ($texto_user_id != $user_id) {
			header('Location:index.php');
		}
		$texto_restrito = true;
	} else {
		$texto_restrito = false;
	}
	
	// HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HTML HEAD HTML HEAD HTML HEAD
	
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	
	include 'templates/html_head.php';

?>
<body class="carrara">
<?php
	include 'templates/navbar.php';
?>

<div id="page_height" class="container-fluid">
	<?php
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		$template_titulo = "Histórico: $texto_titulo";
		include 'templates/titulo.php';
	?>
    <div class="row justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'selecionar_historico';
						$template_titulo = 'Histórico de mudanças';
						$template_botoes = false;
						$template_conteudo = false;
						
						$template_conteudo .= "<p>Para visualizar as mais recentes versões salvas deste texto, clique em um dos botões abaixo.</p>";
						if ($texto_restrito == false) {
							$result = $conn->query("SELECT criacao, id, user_id FROM Textos_arquivo WHERE page_id = $texto_page_id AND tipo = '$texto_tipo' ORDER BY id DESC");
						} else {
							$result = $conn->query("SELECT criacao, id, user_id FROM Textos_arquivo WHERE page_id = $texto_page_id AND tipo = '$texto_tipo' AND user_id = $user_id ORDER BY id DESC");
						}
						if ($result->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							$count = 0;
							while ($row = $result->fetch_assoc()) {
								$count++;
								if ($count == 41) {
									break;
								}
								$historico_verbete_criacao = $row['criacao'];
								$historico_verbete_id = $row['id'];
								$historico_verbete_user_id = $row['user_id'];
								$historico_verbete_user_apelido = return_apelido_user_id($historico_verbete_user_id);
								$template_conteudo .= "
									<form method='post'>
                                       <li class='list-group-item'>
	                                      <button type='submit' value='$historico_verbete_id' name='visualizar_historico' class='btn btn-primary btn-sm'>Visualizar</button>
                                          Em <em><small class='text-muted'>$historico_verbete_criacao</small></em>, por
								";
								if ($historico_verbete_user_apelido == false) {
									$template_conteudo .= "anônimo.";
								} else {
									$template_conteudo .= "
									  <a href='pagina.php?user_id=$historico_verbete_user_id' target='_blank' title='Visite o escritório deste usuário'>$historico_verbete_user_apelido</a>
                                  	";
								}
								$template_conteudo .= "
                                      </li>
                                    </form>
                                ";
							}
							$template_conteudo .= "</ul>";
						}
						include 'templates/page_element.php';
					?>
        </div>
        <div id='coluna_direita' class='<?php echo $coluna_classes; ?>'>
					<?php
						if ($visualizar_historico_id != false) {
							$result = $conn->query("SELECT verbete_html FROM Textos_arquivo WHERE id = $visualizar_historico_id");
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									$verbete_html = $row['verbete_html'];
									echo $verbete_html;
									break;
								}
							} else {
								echo "<p>Não foi possível encontrar o verbete indicado.</p>";
							}
						}
					?>
        </div>
    </div>
</div>


</body>


<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

</html>