<?php
	
	include 'engine.php';
	include 'templates/html_head.php';
	
	$pub_user_id = false;
	$pub_user_apelido = false;
	if (isset($_GET['pub_user_id'])) {
		$pub_user_id = $_GET['pub_user_id'];
		$usuarios = $conn->query("SELECT apelido FROM Usuarios WHERE id = $pub_user_id");
		if ($usuarios->num_rows > 0) {
			while ($row = $usuarios->fetch_assoc()) {
				$pub_user_apelido = $row['apelido'];
			}
		}
		else {
			$pub_user_id = false;
		}
	}
?>

<body>

<?php
	include 'templates/navbar.php';
?>

<div class="container-fluid">
	<?php
		if ($pub_user_apelido != false) {
			$template_titulo = $pub_user_apelido;
		}
		else {
			$template_titulo = 'Usuário não-encontrado';
		}
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
    <div class="row d-flex justify-content-center">
        <div id="coluna_esquerda" class="<?php echo $coluna_classes; ?>">
					<?php
						$template_id = 'perfil_publico';
						$template_titulo = 'Perfil público';
						$template_conteudo = false;
						if ($pub_user_id == false) {
							$template_conteudo .= "<p>Usuário não encontrado.</p>";
						} else {
							$results = $conn->query("SELECT verbete_html FROM Textos WHERE user_id = $pub_user_id AND tipo = 'verbete_user'");
							if ($results->num_rows > 0) {
								while ($row = $results->fetch_assoc()) {
									$verbete_publico = $row['verbete_html'];
									$template_conteudo .= $verbete_publico;
								}
							}
							else {
								$template_conteudo .= "<p>Este usuário não preencheu o campo de perfil público em sua página.</p>";
							}
						}
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
