<?php
	$template_modal_div_id = 'modal_add_elementos';
	$template_modal_titulo = 'Adicionar elemento';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		  	<span id='esconder_adicionar_elemento' data-toggle='modal' data-target='#modal_add_elementos' class='row justify-content-center'>";
	
	$add_elemento_artefato_col = 'col-lg-3 col-sm-5';
	
	$artefato_tipo = 'buscar_elemento';
	$artefato_titulo = 'Referência';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = $add_elemento_artefato_col;
	$fa_icone = 'fa-search-plus';
	$fa_color = 'text-success';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'gerenciar_etiquetas';
	$artefato_titulo = 'Página livre';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = $add_elemento_artefato_col;
	$fa_icone = 'fa-tag';
	$fa_color = 'text-warning';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'adicionar_youtube';
	$artefato_titulo = 'Vídeo do YouTube';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = $add_elemento_artefato_col;
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	if ($pagina_tipo != 'escritorio') {
		$artefato_tipo = 'adicionar_imagem';
		$artefato_titulo = 'Imagem';
		$artefato_link = false;
		$artefato_criacao = false;
		$artefato_col_limit = $add_elemento_artefato_col;
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	}
	if ($user_tipo == 'admin') {
		if ($pagina_tipo == 'topico') {
			$artefato_tipo = 'adicionar_dados_provas';
			$artefato_titulo = 'Questão de prova';
			$artefato_link = false;
			$artefato_criacao = false;
			$artefato_col_limit = $add_elemento_artefato_col;
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
		}
	}
	
	$artefato_tipo = 'adicionar_wikipedia';
	$artefato_titulo = 'Vincular a artigo da Wikipédia';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = $add_elemento_artefato_col;
	$fa_icone = 'fa-wikipedia-w';
	$fa_color = 'text-dark';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
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
	$modal_scrollable = true;
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= include 'templates/adicionar_referencia_form.php';
	include 'templates/modal.php';
	if ($user_tipo == 'admin') {
		$template_modal_div_id = 'modal_adicionar_wikipedia';
		$template_modal_titulo = 'Vincular a artigo da Wikipédia';
		$template_modal_show_buttons = false;
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
	}
	include 'templates/modal.php';