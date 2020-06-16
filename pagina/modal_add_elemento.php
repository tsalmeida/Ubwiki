<?php

	$template_modal_div_id = 'modal_add_elementos';
	$template_modal_titulo = $pagina_translated['Adicionar elementos'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		  	<span id='esconder_adicionar_elemento' data-toggle='modal' data-target='#modal_add_elementos' class='row justify-content-center'>";

	if ($pagina_tipo != 'texto') {

		$artefato_tipo = 'adicionar_livro';
		$template_subtipo = 'generico';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = $pagina_translated['Material de leitura'];
		$artefato_modal = '#modal_selecionar_subtipo';
		$subtipo_artefato_link_classes = 'selecionar_categoria';
		include 'templates/subtipo_icone.php';

		$artefato_tipo = 'adicionar_video';
		$template_subtipo = 'generico';
		$template_subtipo_tipo = 'video';
		$template_subtipo_titulo = $pagina_translated['Vídeo'];
		$artefato_modal = '#modal_selecionar_subtipo';
		$subtipo_artefato_link_classes = 'selecionar_categoria';
		include 'templates/subtipo_icone.php';

		$artefato_tipo = 'adicionar_album_musica';
		$template_subtipo = 'generico';
		$template_subtipo_tipo = 'album_musica';
		$template_subtipo_titulo = $pagina_translated['Áudio'];
		$artefato_modal = '#modal_selecionar_subtipo';
		$subtipo_artefato_link_classes = 'selecionar_categoria';
		include 'templates/subtipo_icone.php';

		if ($pagina_tipo != 'escritorio') {
			$artefato_tipo = 'adicionar_imagem';
			$template_subtipo = 'generico';
			$template_subtipo_tipo = 'imagem';
			$template_subtipo_titulo = $pagina_translated['Imagem'];
			$artefato_modal = '#modal_selecionar_subtipo';
			$subtipo_artefato_link_classes = 'selecionar_categoria';
			include 'templates/subtipo_icone.php';
		}

		$template_subtipo = 'etiqueta';
		$template_subtipo_tipo = 'pagina';
		$template_subtipo_titulo = $pagina_translated['free page'];
		$artefato_modal = '#modal_gerenciar_etiquetas';
		include 'templates/subtipo_icone.php';

		if ($pagina_tipo == 'topico') {
			$template_subtipo = false;
			$template_subtipo_tipo = 'questao';
			$template_subtipo_titulo = $pagina_translated['Questão'];
			$artefato_modal = '#modal_adicionar_simulado';
			include 'templates/subtipo_icone.php';
		}

		$paginas_sem_vinculo_wikipedia = array('curso', 'materia', 'escritorio', 'grupo', 'questao', 'biblioteca');
		$subtipos_sem_vinculo_wikipedia = array('modelo', 'plano', 'escritorio', 'Plano de estudos', 'produto');
		$carregar_modal_vincular_wikipedia = false;
		if (!in_array($pagina_tipo, $paginas_sem_vinculo_wikipedia)) {
			if (!in_array($pagina_subtipo, $subtipos_sem_vinculo_wikipedia)) {
				if ($pagina_subtipo != '')
					$carregar_modal_vincular_wikipedia = true;
				$template_subtipo = 'wikipedia';
				$fa_type = 'fab';
				$template_subtipo_tipo = 'referencia';
				$template_subtipo_titulo = $pagina_translated['Vincular a verbete da Wikipédia'];
				$artefato_modal = '#modal_adicionar_wikipedia';
				include 'templates/subtipo_icone.php';
			}
		}
		if (isset($carregar_secoes)) {
			if (($carregar_secoes == true) && ($privilegio_edicao == true)) {
				$artefato_tipo = 'adicionar_secao';
				if ($pagina_tipo == 'elemento') {
					if ($elemento_subtipo == 'podcast') {
						$artefato_titulo = $pagina_translated['Episódio'];
					} elseif ($elemento_subtipo == 'livro') {
						$artefato_titulo = $pagina_translated['Capítulo'];
					} else {
						$artefato_titulo = $pagina_translated['Seção'];
					}
				} else {
					$artefato_titulo = $pagina_translated['Seção'];
				}
				$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
				$artefato_modal = '#modal_partes_form';
				$artefato_info = return_icone_subtipo('secao', false);
				$fa_icone = $artefato_info[0];
				$fa_color = $artefato_info[1];
				$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			}
		}
	} else {
		$artefato_tipo = 'escrever_resposta';
		$artefato_titulo = $pagina_translated['Escrever resposta'];
		$artefato_modal = '#modal_add_reply';
		$artefato_col_limit = 'col-lg-3 col-md-4 col-sm-6';
		$artefato_info = return_icone_subtipo('resposta', false);
		$fa_icone = $artefato_info[0];
		if ($respostas->num_rows > 0) {
			$fa_color = $artefato_info[1];
		} else {
			$fa_color = 'text-muted';
		}

		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	}
	$template_modal_body_conteudo .= "</span>";
	if ($pagina_tipo == 'escritorio') {
		$template_modal_body_conteudo .= "
	        <h3 class='mt-3'>{$pagina_translated['your collection']}</h3>
	        <p>{$pagina_translated['your collection explanation 1']}</p>
	        <p>{$pagina_translated['your collection explanation 2']}</p>
		";
	}
	include 'templates/modal.php';

	if ($pagina_tipo != 'texto') {

		$template_modal_div_id = 'modal_buscar_elemento';
		$template_modal_titulo = $pagina_translated['Buscar e adicionar referência'];
		$template_modal_show_buttons = false;
		$modal_scrollable = true;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= include 'templates/adicionar_referencia_form.php';
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_selecionar_subtipo';
		$template_modal_titulo = $pagina_translated['Selecionar subtipo do novo elemento'];
		$modal_scrollable = true;
		$template_modal_show_buttons = false;
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<span data-toggle='modal' data-target='#modal_selecionar_subtipo'><div class='row d-flex justify-content-center'>";
		$subtipo_artefato_modal = '#modal_buscar_elemento';
		include 'pagina/elemento_subtipos.php';
		$template_modal_body_conteudo .= "</div></span>";
		include 'templates/modal.php';

		if ($carregar_modal_vincular_wikipedia == true) {
			$template_modal_div_id = 'modal_adicionar_wikipedia';
			$template_modal_titulo = $pagina_translated['Vincular a verbete da Wikipédia'];
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
				<p>{$pagina_translated['wikipedia article explanation']}</p>
				<div class='md-form'>
					<input type='url' class='form-control' name='wikipedia_url' id='wikipedia_url' required>
					<label for='wikipedia_url'>{$pagina_translated['Endereço do artigo na Wikipédia']}</label>
				</div>
				<div class='md-form'>
					<input type='text' class='form-control' name='wikipedia_titulo' id='wikipedia_titulo' required>
					<label for='wikipedia_titulo'>{$pagina_translated['Título do artigo na Wikipedia']}</label>
				</div>
			";
			include 'templates/modal.php';
		}
	}