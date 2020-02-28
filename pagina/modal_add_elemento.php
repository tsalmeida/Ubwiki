<?php
	
	$template_modal_div_id = 'modal_add_elementos';
	$template_modal_titulo = 'Adicionar elemento';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		  	<span id='esconder_adicionar_elemento' data-toggle='modal' data-target='#modal_add_elementos' class='row justify-content-center'>";
	
	$add_elemento_artefato_col = 'col-lg-3 col-sm-5';
	
	$template_subtipo = 'generico';
	$template_subtipo_tipo = 'referencia';
	$template_subtipo_titulo = 'Material de leitura';
	$artefato_modal = '#modal_selecionar_subtipo';
	include 'templates/subtipo_icone.php';
	
	$template_subtipo = 'generico';
	$template_subtipo_tipo = 'video';
	$template_subtipo_titulo = 'Vídeo';
	$artefato_modal = '#modal_selecionar_subtipo';
	include 'templates/subtipo_icone.php';
	
	$template_subtipo = 'generico';
	$template_subtipo_tipo = 'album_musica';
	$template_subtipo_titulo = 'Áudio';
	$artefato_modal = '#modal_selecionar_subtipo';
	include 'templates/subtipo_icone.php';
	
	$template_subtipo = 'etiqueta';
	$template_subtipo_tipo = 'pagina';
	$template_subtipo_titulo = 'Página livre';
	$artefato_modal = '#modal_gerenciar_etiquetas';
	include 'templates/subtipo_icone.php';
	
	if ($pagina_tipo != 'escritorio') {
		$template_subtipo = 'generico';
		$template_subtipo_tipo = 'imagem';
		$template_subtipo_titulo = 'Imagem';
		$artefato_modal = '#modal_selecionar_subtipo';
		include 'templates/subtipo_icone.php';
	}
	
	if ($pagina_tipo == 'topico') {
		$template_subtipo = false;
		$template_subtipo_tipo = 'questao';
		$template_subtipo_titulo = 'Questão de prova';
		$artefato_modal = '#modal_adicionar_simulado';
		include 'templates/subtipo_icone.php';
	}

	$carregar_modal_vincular_wikipedia = false;
	if (($pagina_tipo != 'curso') && ($pagina_tipo != 'materia') && ($pagina_tipo != 'escritorio') && ($pagina_tipo != 'grupo') && ($pagina_tipo != 'questao') && ($pagina_tipo != 'biblioteca')) {
		$carregar_modal_vincular_wikipedia = true;
		$template_subtipo = 'wikipedia';
		$template_subtipo_tipo = 'referencia';
		$template_subtipo_titulo = 'Vincular a verbete da Wikipédia';
		$artefato_modal = '#modal_adicionar_wikipedia';
		include 'templates/subtipo_icone.php';

	}
	if (isset($carregar_secoes)) {
		if (($carregar_secoes == true) && ($privilegio_edicao == true)) {
			$artefato_tipo = 'adicionar_secao';
			if ($pagina_tipo == 'elemento') {
				if ($elemento_subtipo == 'podcast') {
					$artefato_titulo = 'Episódio';
				} elseif ($elemento_subtipo == 'livro') {
					$artefato_titulo = 'Capítulo';
				} else {
					$artefato_titulo = 'Seção';
				}
			} else {
				$artefato_titulo = 'Seção';
			}
			$artefato_link = false;
			$artefato_criado = false;
			$artefato_col_limit = $add_elemento_artefato_col;
			$artefato_modal = '#modal_partes_form';
			$artefato_info = return_icone_subtipo('secao', false);
			$fa_icone = $artefato_info[0];
			$fa_color = $artefato_info[1];
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		}
	}
	$template_modal_body_conteudo .= "</span>";
	if ($pagina_tipo == 'escritorio') {
		$template_modal_body_conteudo .= "
	        <h3 class='mt-3'>Acervo virtual</h3>
	        <p>Acrescente a seu acervo virtual livros que você tem, quer ter, pretende emprestar de um amigo, assim como artigos que quer ler, revistas, até mesmo álbuns de música ou filmes.</p>
	        <p>Uma vez que seu item tenha sido adicionado, será possível marcar capítulos e escrever fichamentos específicos, assim como resenhas e resumos. Cada anotação será inicialmente privada, podendo ser tornada pública se você assim desejar.</p>
		";
	}
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_buscar_elemento';
	$template_modal_titulo = 'Buscar e adicionar referência';
	$template_modal_show_buttons = false;
	$modal_scrollable = true;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= include 'templates/adicionar_referencia_form.php';
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_selecionar_subtipo';
	$template_modal_titulo = 'Selecionar subtipo do novo elemento';
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
		$template_modal_titulo = 'Vincular a artigo da Wikipédia';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "
		<p>Adicione abaixo apenas verbetes da Wikipédia sobre o exato tópico desta página. Verbetes apenas relacionados devem ser acrescentados à seção \"Leia Mais\".</p>
		<div class='md-form'>
			<input type='url' class='form-control' name='wikipedia_url' id='wikipedia_url' required>
			<label for='wikipedia_url'>Endereço do artigo na Wikipédia</label>
		</div>
		<div class='md-form'>
			<input type='text' class='form-control' name='wikipedia_titulo' id='wikipedia_titulo' required>
			<label for='wikipedia_titulo'>Título do artigo na Wikipedia</label>
		</div>
	";
		include 'templates/modal.php';
	}