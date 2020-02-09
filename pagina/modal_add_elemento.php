<?php
	$template_modal_div_id = 'modal_add_elementos';
	$template_modal_titulo = 'Adicionar elemento';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		  	<span id='esconder_adicionar_elemento' data-toggle='modal' data-target='#modal_add_elementos' class='row justify-content-center'>";
	
	$artefato_tipo = 'adicionar_youtube';
	$artefato_titulo = 'Adicionar vídeo do YouTube';
	$artefato_link = false;
	$artefato_criacao = false;
	$artefato_col_limit = 'col';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	if ($pagina_tipo != 'escritorio') {
		$artefato_tipo = 'adicionar_imagem';
		$artefato_titulo = 'Adicionar imagem';
		$artefato_link = false;
		$artefato_criacao = false;
		$artefato_col_limit = 'col';
		$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	}
	if ($user_tipo == 'admin') {
		if ($pagina_tipo == 'topico') {
			$artefato_tipo = 'adicionar_dados_provas';
			$artefato_titulo = 'Adicionar questão de prova';
			$artefato_link = false;
			$artefato_criacao = false;
			$artefato_col_limit = 'col';
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
	
	$template_modal_body_conteudo .= "
        <h3>Buscar elemento</h3>
        <p>Antes de criar novo registro de elemento, por favor use o mecanismo de busca para que não haja duplicidade.</p>
";
	
	$adicionar_referencia_busca_texto = "Digite aqui o título da nova referência";
	$adicionar_referencia_form_botao = "Adicionar referência";
	$template_modal_body_conteudo .= include 'templates/adicionar_referencia_form.php';
	
	include 'templates/modal.php';
	