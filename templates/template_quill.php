<?php

	//TODO: A tabela Textos_arquivo deve ser transferida a um banco de dados separado.
	//TODO: Resolver a questão do scrollcontainer

	$quill_was_loaded = true;
	
	if (!isset($curso_id)) {
		$curso_id = "NULL";
	}
	if (!isset($template_quill_pagina_de_edicao)) {
		$template_quill_pagina_de_edicao = false;
	}
	if (!isset($texto_tipo)) {
		$texto_tipo = $template_id;
	}
	if (!isset($pagina_tipo)) {
		$pagina_tipo = "NULL";
	}
	
	if (!isset($pagina_subtipo)) {
		$pagina_subtipo = "NULL";
	}
	if (!isset($pagina_curso_id)) {
		$pagina_curso_id = "NULL";
	}
	if (!isset($template_quill_botoes)) {
		$template_quill_botoes = true;
	}
	
	if (isset($pagina_tipo)) {
		$template_quill_meta_tipo = $template_id;
		$template_quill_toolbar_and_whitelist = $template_id;
		if (!isset($template_quill_vazio)) {
			$template_quill_vazio = $pagina_translated['Seja o primeiro a escrever sobre este assunto.'];
		}
		$template_quill_public = true;
		if (!isset($template_quill_initial_state)) {
			$template_quill_initial_state = return_quill_initial_state($template_id);
		}
		if ($template_id == 'anotacoes') {
			$template_classes = 'anotacoes_sticky';
			$template_quill_vazio = $pagina_translated['Não há notas de estudo suas sobre este assunto.'];
			$template_quill_public = false;
		}
	}
	
	if (!isset($template_quill_vazio)) {
		$template_quill_vazio = 'Documento vazio';
	}
	
	$template_quill_whitelist = "formatWhitelist_{$template_quill_toolbar_and_whitelist}";
	$template_quill_toolbar = "toolbarOptions_{$template_quill_toolbar_and_whitelist}";
	
	if (($pagina_tipo == 'resposta') && (($user_tipo == 'admin') || ($user_tipo == 'professor'))) {
		$template_quill_whitelist = "formatWhitelist_anotacoes";
		$template_quill_toolbar = "toolbarOptions_anotacoes";
	}
	
	if ($template_quill_initial_state == 'edicao') {
		$template_quill_editor_classes = 'quill_editor_height quill_editor_height_leitura';
	} else {
		$template_quill_editor_classes = 'quill_editor_height p-limit';
	}
	if ($template_quill_pagina_de_edicao == true) {
		$template_quill_editor_classes = 'quill_pagina_de_edicao';
	}
	
	if (isset($pagina_id)) {
		$template_quill_pagina_id = $pagina_id;
	}
	$template_quill_pagina_id = (int)$template_quill_pagina_id;
	
	if (isset($pagina_id)) {
		$quill_visualizacoes_tipo = $pagina_tipo;
	}
	$quill_novo_verbete_html = "quill_novo_{$template_id}_html";
	$quill_novo_verbete_text = "quill_novo_{$template_id}_text";
	$quill_novo_verbete_content = "quill_novo_{$template_id}_content";
	$quill_trigger_button = "quill_trigger_{$template_id}";
	
	$verbete_exists = false; // essa variável muda se é encontrado conteúdo prévio.
	
	if (!isset($template_botoes)) {
		$template_botoes = false;
	}

	if (!isset($pagina_texto_id)) {
		$quill_texto_id = return_texto_id($pagina_tipo, $template_id, $pagina_id, $user_id);
		if (($pagina_tipo == 'topico') && ($template_id == 'verbete')) {
			$topico_texto_id = $quill_texto_id;
		}
	} else {
		$quill_texto_id = $pagina_texto_id;
	}
	
	$compartilhamento_check = false;
	if ($quill_texto_id != false) {
		$compartilhamento_check = return_compartilhamento($pagina_id, $user_id);
	}
	
	$anotacoes_existem = false;
	$quill_verbete_content = false;
	if ($quill_texto_id != false) {
		$quill_query = "SELECT verbete_content FROM Textos WHERE id = $quill_texto_id";
		if (($template_quill_public != true) && ($compartilhamento_check == false)) {
			$quill_query .= " AND user_id = $pagina_user_id";
		}
		$quill_textos = $conn->query($quill_query);
		
		if ($quill_textos->num_rows > 0) {
			while ($quill_texto = $quill_textos->fetch_assoc()) {
				$quill_verbete_content = $quill_texto['verbete_content'];
				$verbete_exists = true;
			}
		} else {
			$template_vazio = true;
		}
	}
	
	if ($quill_verbete_content != false) {
		if ($template_id == 'verbete') {
			if ($pagina_estado == 0) {
				$conn->query("UPDATE Paginas SET estado = 1 WHERE id = $pagina_id");
			}
		} elseif ($template_id == 'anotacoes') {
			$anotacoes_existem = true;
		}
	}
	
	if ($quill_texto_id == false) {
		if (!isset($pagina_curso_id)) {
			$pagina_curso_id = "NULL";
		}
		if (!isset($pagina_item_id)) {
			$pagina_item_id = "NULL";
		}
		$conn->query("INSERT INTO Textos (curso_id, tipo, page_id, pagina_id, pagina_tipo, estado_texto, verbete_html, verbete_text, verbete_content, user_id) VALUES ($pagina_curso_id, '$template_id', $pagina_item_id, $pagina_id, '$pagina_tipo', 1, FALSE, FALSE, FALSE, $user_id)");
		$quill_texto_id = $conn->insert_id;
	}
	
	$quill_result = false;
	
	$template_botoes_salvar = false;

	$quill_edicao = false;
	if (($template_id == 'verbete') && ($privilegio_edicao == true)) {
		$quill_edicao = true;
	}
	if ($template_id != 'verbete') {
		$quill_edicao = true;
	}
	if ((($template_id == 'modelo') || ($template_id == 'modelo_directions')) && ($pagina_compartilhamento == false)) {
		$quill_edicao = false;
	}

	if ($quill_edicao == true) {
		$template_botoes_salvar .= "<a href='javascript:void(0)' id='{$template_id}_trigger_save' title='{$pagina_translated['Salvar mudanças']}' class='mr-2 text-primary'><i class='fad fa-save fa-fw'></i></a><a href='javascript:void(0)' id='{$template_id}_trigger_save_success' title='Edições salvas' class='text-success collapse mr-2'><i class='fad fa-check-square fa-fw'></i></a><a href='javascript:void(0)' id='{$template_id}_trigger_save_failure' title='{$pagina_translated['Suas edições não foram salvas']}' class='text-danger collapse mr-2'><i class='fad fa-times-square fa-fw'></i></a>";
	}
	if (($quill_texto_id != false) && ($template_id != 'modelo')) {
		$template_botoes_salvar .= "<a href='historico.php?texto_id=$quill_texto_id' title='{$pagina_translated['Histórico do documento']}' class='mr-2 text-default'><i class='fad fa-history fa-fw'></i></a>";
		/*$template_botoes .= "
			<a href='pagina.php?texto_id=$quill_texto_id' title='Editar na página de edição'><i class='fad fa-external-link-square fa-fw'></i></a>
		";*/
	}
	
	$template_botoes_salvar = mysqli_real_escape_string($conn, $template_botoes_salvar);
	
	if ($template_quill_meta_tipo == 'anotacoes') {
		$template_botoes .= "
			<a href='pagina.php?texto_id=$quill_texto_id&corr=1' class='text-warning' title='{$pagina_translated['review']}'><i class='fad fa-highlighter fa-fw'></i></a>
			<a href='javascript:void(0);' id='esconder_coluna_esquerda' title='{$pagina_translated['expand']}' class='text-primary'><i class='fad fa-arrow-alt-square-left fa-fw'></i></a>
    		<a href='javascript:void(0);' id='mostrar_coluna_esquerda' title='{$pagina_translated['compress']}' class='text-primary'><i class='fad fa-arrow-alt-square-right fa-fw'></i></a>
			<a href='pagina.php?texto_id=$quill_texto_id' id='compartilhar_anotacao' title='{$pagina_translated['Página deste documento']}' class='text-primary'><i class='fad fa-external-link-square fa-fw'></i></a>
		";
	}

	if ($quill_edicao == true) {
		$template_botoes .= "
			<!--<span id='travar_{$template_id}' title='{$pagina_translated['Travar para edição']}'>
	      <a href='javascript:void(0);' class='text-primary'><i class='fad fa-pen-square fa-fw'></i></a>
	    </span>-->
	    <span id='destravar_{$template_id}' title='{$pagina_translated['Permitir edição']}'>
				<a href='javascript:void(0);' class='text-primary'><i class='fad fa-pen-square fa-fw'></i></a>
	    </span>
		";
	}
	if ($user_id == false) {
		$template_botoes .= "
			<a href='javascript:void(0);' class='text-primary' data-toggle='modal' data-target='#modal_login' title='{$pagina_translated['Permitir edição']}'><i class='fad fa-pen-square fa-fw'></i></a>
		";
	}
	$template_no_spacer = true;
	
	if ($template_quill_botoes == false) {
		$template_botoes = false;
	}
	
	$quill_result .= "
    <form id='quill_{$template_id}_form' method='post' class='w-100'>
        <!--<div class='row'>
            <div class='container'>-->
                <div id='quill_container_{$template_id}' class='bg-white'>
                    <div id='quill_editor_{$template_id}' class='$template_quill_editor_classes'>
                    </div>
                </div>
            <!--</div>
        </div>-->";
	$quill_result .= "
        <div id='botoes_salvar_{$template_id}' class='row justify-content-center mt-3 d-none'>
            <button type='submit' id='$quill_trigger_button' class='$button_classes' name='$quill_trigger_button'>
            	Salvar
            </button>
        </div>
    </form>";
	$quill_user_id = (int)$user_id;
	$scrollingContainer = 'html';
	if (($template_id != 'anotacoes') && ($pagina_tipo != 'texto')) {
		$scrollingContainer = "#anotacoes";
	}

	$quill_result .= "
    <script type='text/javascript'>
    var {$template_id}_editor = new Quill('#quill_editor_{$template_id}', {
        theme: 'snow',
        scrollingContainer: '{$scrollingContainer}',
        placeholder: '{$template_quill_vazio}',
        formats: $template_quill_whitelist,
        modules: {
            toolbar: {
                container: $template_quill_toolbar,
                handlers: {
                    image: function() {
                        var range = this.quill.getSelection();
								        var link = prompt('{$pagina_translated['Qual o endereço da imagem?']}');
						            var titulo = prompt('{$pagina_translated['Título da imagem:']}');
								        var value64 = btoa(link);
								        if (link) {
								            $.post('engine.php', {
								                    'nova_imagem': value64,
								                    'user_id': $quill_user_id,
								                    'page_id': $template_quill_pagina_id,
								                    'nova_imagem_titulo': titulo,
								                    'contexto': '$template_id'
						                },
						                function(data) {
						                });
								            this.quill.insertEmbed(range.index, 'image', link, Quill.sources.USER);
								        }
                    }
                }
            }
        }
    });

    var form_{$template_id} = document.querySelector('#quill_{$template_id}_form');
    form_{$template_id}.onsubmit = function (e) {
        e.preventDefault();

        var quill_{$template_id}_content = {$template_id}_editor.getContents();
        quill_{$template_id}_content = JSON.stringify(quill_{$template_id}_content);
        
        $.post('engine.php', {
            'quill_novo_verbete_html': {$template_id}_editor.root.innerHTML,
            'quill_novo_verbete_text': {$template_id}_editor.getText(),
            'quill_novo_verbete_content': quill_{$template_id}_content,
            'quill_pagina_id': {$pagina_id},
            'quill_texto_tipo': '{$template_id}',
            'quill_texto_id': {$quill_texto_id},
            'quill_texto_page_id': {$pagina_item_id},
            'quill_pagina_tipo': '{$pagina_tipo}',
            'quill_pagina_subtipo': '{$pagina_subtipo}',
            'quill_pagina_estado': {$pagina_estado},
            'quill_curso_id': '{$pagina_curso_id}'
        }, function(data) {
            if (data != false) {
                $('#{$template_id}_trigger_save').hide();
								$('#{$template_id}_trigger_save_success').show();
								setTimeout(function(){
									$('#{$template_id}_trigger_save').show();
									$('#{$template_id}_trigger_save_success').hide();
						    }, 2000);
            } else {
                $('#{$template_id}_trigger_save').hide();
								$('#{$template_id}_trigger_save_failure').show();
								setTimeout(function(){
									$('#{$template_id}_trigger_save').show();
									$('#{$template_id}_trigger_save_failure').hide();
						    }, 5000);
            }
        });
    };
   
	  {$template_id}_editor.setContents($quill_verbete_content);

	  $('#travar_{$template_id}').click(function () {
        {$template_id}_editor.disable();
        $('#travar_{$template_id}').hide();
        $('#destravar_{$template_id}').show();
        $('#quill_container_{$template_id}').children(':first').hide();
        $('#botoes_salvar_{$template_id}').hide();
        $('#{$template_id}_trigger_save').hide();
        $('#quill_editor_{$template_id}').children(':first').removeClass('ql-editor-active');
    });
    $('#destravar_{$template_id}').click(function () {
        {$template_id}_editor.enable();
        $('#travar_{$template_id}').show();
        $('#destravar_{$template_id}').hide();
        $('#quill_container_{$template_id}').children(':first').show();
        $('#botoes_salvar_{$template_id}').show();
        $('#{$template_id}_trigger_save').show();
        $('#quill_editor_{$template_id}').children(':first').addClass('ql-editor-active');
    });
		var template_botoes_salvar = \"$template_botoes_salvar\";
		$('#quill_container_{$template_id} > .ql-toolbar').prepend(template_botoes_salvar);
		$('#{$template_id}_trigger_save').click(function () {
			$('#{$quill_trigger_button}').click();
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
				$('#{$template_id}_trigger_save').hide();
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
				$('#{$template_id}_trigger_save').show();
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
	unset($template_quill_pagina_id);
	unset($quill_visualizacoes_tipo);
	unset($quill_verbete_content);
	unset($template_quill_public);
	unset($verbete_exists);
	unset($template_quill_meta_tipo);
	unset($template_quill_pagina_de_edicao);
	unset($quill_texto_id);
	unset($template_quill_botoes);
	
	return $quill_result;