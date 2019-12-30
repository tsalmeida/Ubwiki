<?php
	if ($texto_user_id == $user_id) {
		
		$grupos_do_usuario = $conn->query("SELECT grupo_id FROM Membros WHERE membro_user_id = $user_id");
		
		if (isset($_POST['compartilhar_grupo_id'])) {
			$compartilhar_grupo_id = $_POST['compartilhar_grupo_id'];
			$conn->query("INSERT INTO Compartilhamento (user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ($user_id, $pagina_texto_id, 'texto', 'grupo', $compartilhar_grupo_id)");
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
