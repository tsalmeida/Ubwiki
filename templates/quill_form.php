<?php
	
	// para criar uma nova instance do quill, as seguintes variáveis devem ser set:
	// $template_quill_unique_name (se nenhum, nome será 'general')
	// $template_quill_initial_state ('edicao' ou 'leitura')
	// $template_quill_page_id (em alguns casos, determinada automaticamente, exceto em admin ou userpage)
	// $template_quill_empty_content (em anotações, não é necessário)
	// $quill_visualizacoes_tipo (para a tabela de visualizacoes, registra que o usuário mudou o texto desse elemento)
	// $template_quill_public (boolean, determina se o query buscará o id do usuario ou não, default é true)
	
	if (!isset($template_quill_empty_content)) {
		$template_quill_empty_content = false;
	}
	if (!isset($template_quill_unique_name)) {
		$template_quill_unique_name = 'general';
	}
	$template_quill_toolbar_and_whitelist = 'general';
	if ($template_quill_unique_name == 'anotacoes') {
		$template_quill_toolbar_and_whitelist = 'anotacoes';
	}
	$template_quill_whitelist = "formatWhitelist_{$template_quill_toolbar_and_whitelist}";
	$template_quill_toolbar = "toolbarOptions_{$template_quill_toolbar_and_whitelist}";
	
	if (!isset($template_quill_initial_state)) {
		$template_quill_initial_state = 'edicao';
	}
	
	if ($template_quill_initial_state == 'edicao') {
		$template_quill_editor_classes = 'quill_editor_height quill_editor_height_leitura';
		$template_quill_collapse = 'collapse show';
	} else {
		$template_quill_editor_classes = 'quill_editor_height';
		$template_quill_collapse = 'collapse';
	}
	if (!isset($template_quill_page_id)) {
		if (isset($topico_id)) {
			$template_quill_page_id = $topico_id;
		} elseif (isset($elemento_id)) {
			$template_quill_page_id = $elemento_id;
		} else {
			$template_quill_page_id = false;
			// nesse caso, o $template_quill_tipo deve ser determinado. Será 'admin' ou 'userpage'.
		}
	}
	if (!isset($template_quill_tipo)) {
		$template_quill_tipo = 'verbete';
	}
	if (!isset($quill_visualizacoes_tipo)) {
		$quill_visualizacoes_tipo = 'verbete_mudanca';
	}
	$quill_novo_verbete_html = "quill_novo_{$template_quill_unique_name}_html";
	$quill_novo_verbete_text = "quill_novo_{$template_quill_unique_name}_text";
	$quill_novo_verbete_content = "quill_novo_{$template_quill_unique_name}_content";
	$quill_trigger_button = "quill_trigger_{$template_quill_unique_name}";
	
	$verbete_exists = false; // essa variável muda se é encontrado conteúdo prévio.
	
	if (!isset($template_quill_public)) {
		$template_quill_public = true;
	}
	
	if ($template_quill_public == true) {
		$result = $conn->query("SELECT verbete_content, id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_quill_tipo'");
	} else {
		$result = $conn->query("SELECT verbete_content, id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_quill_tipo' AND user_id = $user_id");
	}
	$quill_verbete_content = false; // o conteúdo final, é determinado ao final ou permanece vazio.
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$quill_verbete_content = $row['verbete_content'];
			$quill_texto_id = $row['id'];
			$verbete_exists = true;
		}
		if ($quill_verbete_content != false) {
			$quill_verbete_content = urldecode($quill_verbete_content);
		}
	}
	
	if (isset($_POST[$quill_trigger_button])) {
		$novo_verbete_html = $_POST[$quill_novo_verbete_html];
		$novo_verbete_text = $_POST[$quill_novo_verbete_text];
		$novo_verbete_content = $_POST[$quill_novo_verbete_content];
		$novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup><img><u><b>');
		if ($template_quill_public == true) {
			$result = $conn->query("SELECT id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_quill_tipo'");
		}
		else {
			$result = $conn->query("SELECT id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_quill_tipo' AND user_id = $user_id");
		}
		if ($verbete_exists == true) {
			$conn->query("UPDATE Textos SET verbete_html = '$novo_verbete_html', verbete_text = '$novo_verbete_text', verbete_content = '$novo_verbete_content' WHERE id = $quill_texto_id");
			$conn->query("INSERT INTO Textos_arquivo (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('verbete', $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
		} else {
			$conn->query("INSERT INTO Textos (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$template_quill_tipo', $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
			$conn->query("INSERT INTO Textos_arquivo (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$template_quill_tipo' , $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $template_quill_page_id, '$quill_visualizacoes_tipo')");
		$quill_verbete_content = urlencode($novo_verbete_content);
		$nao_contar = true;
	}
	
	$quill_result = false;
	
	$quill_result .= "
    <form id='quill_{$template_quill_unique_name}_form' method='post'>
        <input name='$quill_novo_verbete_html' type='hidden'>
        <input name='$quill_novo_verbete_text' type='hidden'>
        <input name='$quill_novo_verbete_content' type='hidden'>
        <div class='row'>
            <div class='container col-12'>
                <div id='quill_container_{$template_quill_unique_name}'>
                    <div id='quill_editor_{$template_quill_unique_name}' class='$template_quill_editor_classes'>
                    </div>
                </div>
            </div>
        </div>
        <div class='row justify-content-center {$template_quill_unique_name}_editor_collapse $template_quill_collapse mt-3'>
        		<button type='button' class='btn btn-light btn-md'>
        			<i class='fal fa-times-circle fa-fw'></i> Cancelar
            </button>
            <button type='submit' class='btn btn-primary btn-md' name='$quill_trigger_button'>
            	<i class='fal fa-check fa-fw'></i> Salvar
            </button>
        </div>
    </form>
    <script>
    var {$template_quill_unique_name}_editor = new Quill('#quill_editor_{$template_quill_unique_name}', {
        theme: 'snow',
        formats: $template_quill_whitelist,
        modules: {
            toolbar: {
                container: $template_quill_toolbar,
                handlers: {
                    image: imageHandler
                }
            }
        }
    });


    var form_{$template_quill_unique_name} = document.querySelector('#quill_{$template_quill_unique_name}_form');
    form_{$template_quill_unique_name}.onsubmit = function () {
        var quill_novo_{$template_quill_unique_name}_html = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_html]');
        quill_novo_{$template_quill_unique_name}_html.value = {$template_quill_unique_name}_editor.root.innerHTML;

        var quill_novo_{$template_quill_unique_name}_text = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_text]');
        quill_novo_{$template_quill_unique_name}_text.value = {$template_quill_unique_name}_editor.getText();

        var quill_novo_{$template_quill_unique_name}_content = document.querySelector('input[name=quill_novo_{$template_quill_unique_name}_content]');
        var quill_{$template_quill_unique_name}_content = {$template_quill_unique_name}_editor.getContents();
        quill_{$template_quill_unique_name}_content = JSON.stringify(quill_{$template_quill_unique_name}_content);
        quill_{$template_quill_unique_name}_content = encodeURI(quill_{$template_quill_unique_name}_content);
        quill_novo_{$template_quill_unique_name}_content.value = quill_{$template_quill_unique_name}_content;
    };
    
	  {$template_quill_unique_name}_editor.setContents($quill_verbete_content);
	</script>
	";
	
	if ($template_quill_initial_state != 'edicao') {
		$quill_result .= "
			<script type='text/javascript'>
				{$template_quill_unique_name}_editor.disable();
			</script>";
	}
	
	unset($template_quill_unique_name);
	unset($template_quill_initial_state);
	unset($template_quill_editor_classes);
	unset($template_quill_toolbar_and_whitelist);
	unset($template_quill_page_id);
	unset($template_quill_tipo);
	unset($quill_visualizacoes_tipo);
	unset($quill_verbete_content);
	unset($template_quill_public);
	
	return $quill_result;