<?php
	
	if (!isset($template_quill_empty_content)) {
		$template_quill_empty_content = false;
	}
	if (!isset($template_quill_pagina_de_edicao)) {
		$template_quill_pagina_de_edicao = false;
	}
	if (strpos($template_id, 'anotac') !== false) {
		$template_quill_meta_tipo = 'anotacoes';
		$template_quill_toolbar_and_whitelist = 'anotacoes';
		$template_quill_initial_state = 'edicao';
		$template_classes = 'anotacoes_sticky';
	} else {
		$template_quill_meta_tipo = 'verbete';
		$template_quill_toolbar_and_whitelist = 'general';
		if (!isset($template_quill_initial_state)) {
			$template_quill_initial_state = 'leitura';
		}
	}
	$template_quill_whitelist = "formatWhitelist_{$template_quill_toolbar_and_whitelist}";
	$template_quill_toolbar = "toolbarOptions_{$template_quill_toolbar_and_whitelist}";
	
	if ($template_quill_initial_state == 'edicao') {
		$template_quill_editor_classes = 'quill_editor_height quill_editor_height_leitura';
	} else {
		$template_quill_editor_classes = 'quill_editor_height';
	}
	if ($template_quill_pagina_de_edicao == true) {
		$template_quill_editor_classes = 'quill_pagina_de_edicao';
	}
	if (!isset($template_quill_page_id)) {
		if (isset($topico_id)) {
			$template_quill_page_id = $topico_id;
		} elseif (isset($elemento_id)) {
			$template_quill_page_id = $elemento_id;
		} elseif (isset($questao_id)) {
			$template_quill_page_id = $questao_id;
		} elseif (isset($texto_apoio_id)) {
			$template_quill_page_id = $texto_apoio_id;
		} elseif (isset($materia_id)) {
			$template_quill_page_id = $materia_id;
		} elseif (isset($concurso_id)) {
			$template_quill_page_id = $concurso_id;
		} else {
			$template_quill_page_id = false;
		}
	}
	$template_quill_page_id = (int)$template_quill_page_id;
	
	if (!isset($quill_visualizacoes_tipo)) {
		$quill_visualizacoes_tipo = 'verbete_mudanca';
	}
	$quill_novo_verbete_html = "quill_novo_{$template_id}_html";
	$quill_novo_verbete_text = "quill_novo_{$template_id}_text";
	$quill_novo_verbete_content = "quill_novo_{$template_id}_content";
	$quill_trigger_button = "quill_trigger_{$template_id}";
	
	$verbete_exists = false; // essa variável muda se é encontrado conteúdo prévio.
	
	if (!isset($template_quill_public)) {
		$template_quill_public = true;
		if ($template_quill_meta_tipo == 'anotacoes') {
			$template_quill_public = false;
		}
	}
	if (!isset($template_botoes)) {
		$template_botoes = false;
	}
	
	if ($template_quill_public == true) {
		$result = $conn->query("SELECT verbete_content, id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_id'");
	} else {
		$result = $conn->query("SELECT verbete_content, id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_id' AND user_id = $user_id");
	}
	$quill_verbete_content = false; // o conteúdo final, é determinado ao final ou permanece vazio.
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$quill_verbete_content = $row['verbete_content'];
			$quill_texto_id = $row['id'];
			$verbete_exists = true;
		}
		if ($quill_verbete_content != false) {
		}
	} else {
		if ($template_quill_meta_tipo == 'verbete') {
			$template_vazio = true;
		}
	}
	
	if (isset($_POST[$quill_trigger_button])) {
		$novo_verbete_html = $_POST[$quill_novo_verbete_html];
		$novo_verbete_html = mysqli_real_escape_string($conn, $novo_verbete_html);
		$novo_verbete_text = $_POST[$quill_novo_verbete_text];
		$novo_verbete_text = mysqli_real_escape_string($conn, $novo_verbete_text);
		$novo_verbete_content = $_POST[$quill_novo_verbete_content];
		$quill_verbete_content = $novo_verbete_content;
		$novo_verbete_content = mysqli_real_escape_string($conn, $novo_verbete_content);
		$novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup><img><u><b><a>');
		if ($template_quill_public == true) {
			$result = $conn->query("SELECT id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_id'");
		} else {
			$result = $conn->query("SELECT id FROM Textos WHERE page_id = $template_quill_page_id AND tipo = '$template_id' AND user_id = $user_id");
		}
		if ($verbete_exists == true) {
			$conn->query("UPDATE Textos SET verbete_html = '$novo_verbete_html', verbete_text = '$novo_verbete_text', verbete_content = '$novo_verbete_content' WHERE id = $quill_texto_id");
			$conn->query("INSERT INTO Textos_arquivo (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$template_id', $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
		} else {
			$conn->query("INSERT INTO Textos_arquivo (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$template_id' , $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
			$conn->query("INSERT INTO Textos (tipo, page_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$template_id', $template_quill_page_id, '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', $user_id)");
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $template_quill_page_id, '$quill_visualizacoes_tipo')");
		$nao_contar = true;
	}
	
	$quill_result = false;
	
	if ($quill_verbete_content == false) {
		$quill_result .= $template_quill_empty_content;
	}
	
	if ($template_quill_meta_tipo == 'anotacoes') {
		$template_botoes .= "
		<span id='esconder_coluna_esquerda' title='expandir'>
			<a href='javascript:void(0);'><i class='fal fa-chevron-square-left fa-fw'></i></a>
  	</span>
    <span id='mostrar_coluna_esquerda' title='comprimir'>
    	<a href='javascript:void(0);'><i class='fal fa-chevron-square-right fa-fw'></i></a>
  	</span>
		";
	}
	
	$template_botoes .= "
		<span id='travar_{$template_id}' title='travar para edição'>
    	<a href='javascript:void(0);'><i class='fal fa-pen-square fa-fw'></i></a>
  	</span>
    <span id='destravar_{$template_id}' title='permitir edição'>
			<a href='javascript:void(0);'><span class='text-muted'><i class='fas fa-pen-square fa-fw'></i></span></a>
  	</span>
	";
	
	$quill_result .= "
    <form id='quill_{$template_id}_form' method='post'>
        <input name='$quill_novo_verbete_html' type='hidden'>
        <input name='$quill_novo_verbete_text' type='hidden'>
        <pre><input name='$quill_novo_verbete_content' type='hidden'></pre>
        <div class='row'>
            <div class='container col'>
                <div id='quill_container_{$template_id}' class='bg-white'>
                    <div id='quill_editor_{$template_id}' class='$template_quill_editor_classes'>
                    </div>
                </div>
            </div>
        </div>
        <div id='botoes_salvar_{$template_id}' class='row justify-content-center mt-3'>
        		<!--<button type='button' class='btn btn-light btn-sm'>
        			<i class='fal fa-times-circle fa-fw'></i> Cancelar
            </button>!-->
            <button type='submit' class='$button_classes' name='$quill_trigger_button'>
            	<i class='fal fa-check fa-fw'></i> Salvar
            </button>
        </div>
    </form>";
	$quill_result .= "
    <script>
    var {$template_id}_editor = new Quill('#quill_editor_{$template_id}', {
        theme: 'snow',
        formats: $template_quill_whitelist,
        modules: {
            toolbar: {
                container: $template_quill_toolbar,
                handlers: {
                    image: function() {
                        var range = this.quill.getSelection();
								        var link = prompt('Qual o endereço da imagem?');
							          var titulo = prompt('Título da imagem:');
								        var value64 = btoa(link);
								        if (link) {
								            $.post('engine.php', {
								                    'nova_imagem': value64,
								                    'user_id': $user_id,
								                    'page_id': $template_quill_page_id,
								                    'nova_imagem_titulo': titulo,
								                    'contexto': '$template_id'
								                },
								                function (data) {
								                });
								            this.quill.insertEmbed(range.index, 'image', link, Quill.sources.USER);
								        }
                    }
                }
            }
        }
    });

    var form_{$template_id} = document.querySelector('#quill_{$template_id}_form');
    form_{$template_id}.onsubmit = function () {
        var quill_novo_{$template_id}_html = document.querySelector('input[name=quill_novo_{$template_id}_html]');
        quill_novo_{$template_id}_html.value = {$template_id}_editor.root.innerHTML;

        var quill_novo_{$template_id}_text = document.querySelector('input[name=quill_novo_{$template_id}_text]');
        quill_novo_{$template_id}_text.value = {$template_id}_editor.getText();

        var quill_novo_{$template_id}_content = document.querySelector('input[name=quill_novo_{$template_id}_content]');
        var quill_{$template_id}_content = {$template_id}_editor.getContents();
        quill_{$template_id}_content = JSON.stringify(quill_{$template_id}_content);
        quill_novo_{$template_id}_content.value = quill_{$template_id}_content;
    };
   
	  {$template_id}_editor.setContents($quill_verbete_content);

	  $('#travar_{$template_id}').click(function () {
        {$template_id}_editor.disable();
        $('#travar_{$template_id}').hide();
        $('#destravar_{$template_id}').show();
        $('#quill_container_{$template_id}').children(':first').hide();
        $('#botoes_salvar_{$template_id}').hide();
        $('#quill_editor_{$template_id}').children(':first').removeClass('ql-editor-active');
        $('#verbete_vazio_{$template_id}').show();
    });
    $('#destravar_{$template_id}').click(function () {
        {$template_id}_editor.enable();
        $('#travar_{$template_id}').show();
        $('#destravar_{$template_id}').hide();
        $('#quill_container_{$template_id}').children(':first').show();
        $('#botoes_salvar_{$template_id}').show();
        $('#quill_editor_{$template_id}').children(':first').addClass('ql-editor-active');
        $('#verbete_vazio_{$template_id}').hide();
    });

	</script>
	";
	
	if ($template_quill_initial_state == 'leitura') {
		$quill_result .= "
			<script type='text/javascript'>
				$('#destravar_{$template_id}').show();
				$('#travar_{$template_id}').hide();
				$('#quill_container_{$template_id}').removeClass('ql-editor-active');
        $('#quill_container_{$template_id}').children(':first').hide();
        $('#botoes_salvar_{$template_id}').hide();
				{$template_id}_editor.disable();
			</script>";
	} elseif (($template_quill_initial_state == 'edicao') && ($template_quill_pagina_de_edicao == false)) {
		$quill_result .= "
			<script type='text/javascript'>
				$('#destravar_{$template_id}').hide();
				$('#travar_{$template_id}').show();
				$('#quill_editor_{$template_id}').children(':first').addClass('ql-editor-active');
        $('#quill_container_{$template_id}').children(':first').show();
        $('#botoes_salvar_{$template_id}').show();
				{$template_id}_editor.enable();
			</script>";
	}
	if ($template_quill_pagina_de_edicao == true) {
		$quill_result .= "
			<script type='text/javascript'>
				{$template_id}_editor.enable();
			</script>
		";
	}
	
	unset($template_quill_initial_state);
	unset($template_quill_editor_classes);
	unset($template_quill_toolbar_and_whitelist);
	unset($template_quill_page_id);
	unset($quill_visualizacoes_tipo);
	unset($quill_verbete_content);
	unset($template_quill_public);
	unset($template_quill_empty_content);
	unset($verbete_exists);
	unset($template_quill_meta_tipo);
	unset($template_quill_pagina_de_edicao);
	
	return $quill_result;