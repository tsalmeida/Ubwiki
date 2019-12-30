<?php
	if (!isset($template_modal_div_id)) {
		$template_modal_div_id = 'modal_gerenciar_etiquetas';
	}
	if (!isset($template_modal_titulo)) {
		$template_modal_titulo = 'Gerenciar etiquetas';
	}
	if (!isset($etiquetas_carregar_remover)) {
		$etiquetas_carregar_remover = true;
	}
	$template_modal_body_conteudo = false;
	if ($etiquetas_carregar_remover == true) {
		$template_modal_body_conteudo .= "
				<h4>Etiquetas ativas</h4>
				<p>Pressione para remover:</p>
				<div class='row' id='etiquetas_ativas'>
		";
		$etiquetados = $conn->query("SELECT DISTINCT extra FROM Paginas_elementos WHERE pagina_id = $pagina_id AND tipo = 'topico' AND estado = 1");
		if ($etiquetados->num_rows > 0) {
			while ($etiquetado = $etiquetados->fetch_assoc()) {
				$etiqueta_ativa_id = $etiquetado['extra'];
				$etiqueta_ativa_info = return_etiqueta_info($etiqueta_ativa_id);
				$etiqueta_ativa_tipo = $etiqueta_ativa_info[1];
				$etiqueta_ativa_titulo = $etiqueta_ativa_info[2];
				$etiqueta_ativa_cor_icone = return_etiqueta_cor_icone($etiqueta_ativa_tipo);
				$etiqueta_ativa_cor = $etiqueta_ativa_cor_icone[0];
				$etiqueta_ativa_icone = $etiqueta_ativa_cor_icone[1];
				$template_modal_body_conteudo .= "<a href='javascript:void(0);' class='$tag_ativa_classes $etiqueta_ativa_cor' value='$etiqueta_ativa_id'><i class='far $etiqueta_ativa_icone fa-fw'></i> $etiqueta_ativa_titulo</a>";
			}
		}
		$template_modal_body_conteudo .= "
			</div>
	    ";
	}
	$template_modal_body_conteudo .= "
			<h4 class='mt-3'>Adicionar etiquetas</h4>
		    <div class='md-form'>
			    <input type='text' class='form-control' name='buscar_etiquetas' id='buscar_etiquetas' required>
			    <label for='buscar_etiquetas'>Buscar etiqueta</label>
		    </div>
		    <div class='row' id='etiquetas_disponiveis'>
		    	
				</div>
		";
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
