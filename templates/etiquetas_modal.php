<?php
	if (!isset($template_modal_div_id)) {
		$template_modal_div_id = 'modal_gerenciar_etiquetas';
	}
	if (!isset($template_modal_titulo)) {
		$template_modal_titulo = $pagina_translated['Gerenciar etiquetas'];
	}
	if (!isset($etiquetas_carregar_remover)) {
		$etiquetas_carregar_remover = true;
	}
	$modal_scrollable = true;
	$template_modal_body_conteudo = false;
	$etiquetas_remover_results = false;
	$hidden_class = false;
	
	if ($etiquetas_carregar_remover == true) {
		if ($etiquetados->num_rows == 0) {
			$hidden_class = 'hidden';
		}
		
		$etiquetas_remover_results .= "<h4 class='$hidden_class'>{$pagina_translated['Etiquetas ativas']}</h4>";
		$etiquetas_remover_results .= "<p class='$hidden_class'>{$pagina_translated['Pressione para remover:']}</p>";
		$etiquetas_remover_results .= "<div class='row $hidden_class' id='etiquetas_ativas'>";
		if ($etiquetados->num_rows > 0) {
			while ($etiquetado = $etiquetados->fetch_assoc()) {
				$etiqueta_ativa_id = $etiquetado['extra'];
				$etiqueta_ativa_info = return_etiqueta_info($etiqueta_ativa_id);
				$etiqueta_ativa_tipo = $etiqueta_ativa_info[1];
				$etiqueta_ativa_titulo = $etiqueta_ativa_info[2];
				$etiqueta_ativa_cor_icone = return_etiqueta_cor_icone($etiqueta_ativa_tipo);
				$etiqueta_ativa_cor = $etiqueta_ativa_cor_icone[0];
				$etiqueta_ativa_icone = $etiqueta_ativa_cor_icone[1];
				$etiquetas_remover_results .= "<a href='javascript:void(0);' class='$tag_ativa_classes $etiqueta_ativa_cor' value='$etiqueta_ativa_id'><i class='far $etiqueta_ativa_icone fa-fw'></i> $etiqueta_ativa_titulo</a>";
			}
		}
		$etiquetas_remover_results .= "</div>";
	}
	
	$template_modal_body_conteudo .= $etiquetas_remover_results;
	$template_modal_body_conteudo .= "
			<h4 class='mt-3'>{$pagina_translated['Adicionar etiquetas']}</h4>
		    <div class='mb-3'>
			    <label for='buscar_etiquetas' class='form-label'>{$pagina_translated['Buscar etiqueta']}</label>
			    <input type='text' class='form-control' name='buscar_etiquetas' id='buscar_etiquetas' required>
			    </div>
			<button type='button' class='btn btn-outline-primary mb-2' id='trigger_buscar_etiquetas'>{$pagina_translated['Buscar']}</button>
		    
		    <div class='row border p-2' id='etiquetas_disponiveis'>
		    	
				</div>
		";

	include 'templates/modal.php';
?>