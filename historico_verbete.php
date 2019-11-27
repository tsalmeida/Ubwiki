<?php
	
	include 'engine.php';
	
	if (isset($_GET['topico_id'])) {
		$topico_id = $_GET['topico_id'];
	}
	
	$visualizar_historico_id = false;
	if (isset($_POST['visualizar_historico'])) {
		$visualizar_historico_id = $_POST['visualizar_historico'];
	}
	
	$topico_titulo = return_titulo_topico($topico_id);
	
	// HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HTML HEAD HTML HEAD HTML HEAD
 
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

<div id="page_height" class="container-fluid">
	<?php
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		$template_titulo = "Histórico: $topico_titulo";
		include 'templates/titulo.php';
	?>
    <div class="row justify-content-around">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'selecionar_historico';
						$template_titulo = 'Histórico de mudanças';
						$template_botoes = false;
						$template_conteudo = false;
						
						$template_conteudo .= "<p>Para visualizar as mais recentes versões salvas deste verbete, clique em um dos botões abaixo.</p>";
						$result = $conn->query("SELECT criacao, id, user_id FROM Textos_arquivo WHERE page_id = $topico_id AND tipo = 'verbete' ORDER BY id DESC");
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
                                $historico_verbete_criacao : <a href='perfil.php?pub_user_id=$historico_verbete_user_id' target='_blank'>$historico_verbete_user_apelido</a></li>
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