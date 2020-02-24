<?php
	
	if (isset($_POST['nova_edicao_ano'])) {
		$nova_edicao_ano = $_POST['nova_edicao_ano'];
		$nova_edicao_titulo = $_POST['nova_edicao_titulo'];
		$conn->query("INSERT INTO sim_edicoes (curso_id, ano, titulo, user_id) VALUES ($pagina_curso_id, $nova_edicao_ano, '$nova_edicao_titulo', $user_id)");
	}
	
	$template_modal_div_id = 'modal_adicionar_simulado';
	$template_modal_titulo = "Edições do concurso";
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$modal_scrollable = true;
	$edicoes = $conn->query("SELECT id, ano, titulo FROM sim_edicoes WHERE curso_id = $pagina_curso_id ORDER BY ano");
	$template_modal_body_conteudo .= "
		<ul class='list-group list-group-flush mb-3'>
			<a id='carregar_formulario_adicionar_edicao' href='javascript:void(0);'><li class='list-group-item list-group-item-action list-group-item-info d-flex justify-content-center'>Adicionar edição</li></a>
		</ul>
		<form method='post' id='esconder_formulario_adicionar_edicao' class='border rounded p-3 m-1 mb-3 grey lighten-5'>
			<p>Adicionar edição do concurso:</p>
      <div class='md-form'>
        <input type='number' class='form-control' name='nova_edicao_ano' id='nova_edicao_ano' required>
        <label for='nova_edicao_ano'>Ano da nova edição</label>
      </div>
      <div class='md-form'>
        <input type='text' class='form-control' name='nova_edicao_titulo' id='nova_edicao_titulo' required>
        <label for='nova_edicao_titulo'>Título da nova edição</label>
      </div>
      <div class='row d-flex justify-content-center'>
      	<button type='submit' class='$button_classes'>Adicionar edição</button>
			</div>
		</form>
	";
	if ($edicoes->num_rows == 0) {
		$template_modal_body_conteudo .= "<p>Não há edições registradas deste concurso.</p>";
	} else {
		$template_modal_body_conteudo .= "
		<p>Edições registradas:</p>
		<ul class='list-group list-group-flush'>
		<span data-toggle='modal' data-target='#modal_adicionar_simulado'>
		<span data-toggle='modal' data-target='#modal_vazio_edicoes'>
		";
		while ($edicao = $edicoes->fetch_assoc()) {
			$edicao_id = $edicao['id'];
			$edicao_ano = $edicao['ano'];
			$edicao_titulo = $edicao['titulo'];
			$template_modal_body_conteudo .= "<a href='javascript:void(0);' class='selecionar_edicao mt-1' value='$edicao_id'><li class='list-group-item list-group-item-action border-top'>$edicao_ano: $edicao_titulo</li></a>";
		}
		$template_modal_body_conteudo .= "</span></span></ul>";
	}
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_vazio_edicoes';
	$template_modal_titulo = 'Etapas de edição do concurso';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
				<form method='post' class='p-3 mb-3 grey lighten-5 border' id='esconder_formulario_adicionar_etapa'>
					<p>Adicionar etapa de edição do concurso:</p>
					<input type='hidden' id='nova_etapa_edicao_id' name='nova_etapa_edicao_id' value=''>
					<input type='hidden' id='nova_etapa_curso_id' name='nova_etapa_curso_id' value='$pagina_curso_id'>
					<div class='md-form'>
	          <input type='text' class='form-control' name='nova_etapa_titulo' id='nova_etapa_titulo' required>
	          <label for='nova_etapa_titulo'>Título da nova etapa</label>
	        </div>
          <div class='row justify-content-center'>
            <button class='$button_classes btn-info'>Adicionar etapa</button>
					</div>
        </form>
        <ul class='list-group list-group-flush'>
        	<a href='javascript:void(0);' id='carregar_formulario_adicionar_etapa'><li class='list-group-item list-group-item-action list-group-item-info'>Adicionar etapa desta edição do concurso</li></a>
				</ul>
				<p>Etapas registradas:</p>
        <div id='etapas_popular'>
				</div>
	";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_vazio_provas';
	$template_modal_titulo = 'Provas de etapa do concurso';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<form method='post' class='p-3 mb-3 grey lighten-5 border' id='esconder_formulario_adicionar_prova'>
			<p>Adicionar prova de etapa do concurso:</p>
			<input type='hidden' id='nova_prova_etapa_id' name='nova_prova_etapa_id' value=''>
			<input type='hidden' id='nova_prova_curso_id' name='nova_prova_curso_id' value='$pagina_curso_id'>
			<div class='md-form'>
				<input type='text' class='form-control' name='nova_prova_titulo' id='nova_prova_titulo' required>
				<label for='nova_prova_titutlo'>Título da prova</label>
			</div>
			<select class='$select_classes' name='nova_prova_tipo' required>
        <option value='' disabled selected>Tipo da prova:</option>
        <option value='1'>Objetiva</option>
        <option value='2'>Dissertativa</option>
        <option value='3'>Oral</option>
        <option value='4'>Física</option>
      </select>
      <div class='row d-flex justify-content-center'>
      	<button class='$button_classes'>Adicionar prova a esta edição do concurso</button>
			</div>
		</form>
		<ul class='list-group list-group-flush'>
			<a href='javascript:void(0);' id='carregar_formulario_adicionar_prova'><li class='list-group-item list-group-item-action list-group-item-info'>Adicionar prova desta etapa do concurso.</li></a>
		</ul>
		<p>Provas registradas:</p>
		<div id='provas_popular'>
		</div>
	";
	include 'templates/modal.php';
	
	$artefatos_nova_questao = false;
	
	$artefato_tipo = 'nova_questao_oficial';
	$artefato_titulo = 'Nova questão oficial do concurso';
	$fa_icone = 'fa-file-certificate';
	$fa_color = 'text-success';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'nova_questao_nao_oficial';
	$artefato_titulo = 'Nova questão não-oficial do concurso';
	$fa_icone = 'fa-user-edit';
	$fa_color = 'text-warning';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'questao_de_texto_de_apoio';
	$artefato_titulo = 'Questão depende de texto de apoio';
	$fa_icone = 'fa-file-alt';
	$fa_color = 'text-primary';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';

	$artefato_tipo = 'questao_nao_texto_de_apoio';
	$artefato_titulo = 'Questão não depende de texto de apoio';
	$fa_icone = 'fa-file-times';
	$fa_color = 'text-danger';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'questao_multipla_escolha';
	$artefato_titulo = 'Questão de múltipla escolha';
	$fa_icone = 'fa-ballot-check';
	$fa_color = 'text-warning';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'questao_verdadeiro_falso';
	$artefato_titulo = 'Questão certo ou errado';
	$fa_icone = 'fa-check-double';
	$fa_color = 'text-success';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$artefato_tipo = 'questao_dissertativa';
	$artefato_titulo = 'Questão dissertativa';
	$fa_icone = 'fa-file-edit';
	$fa_color = 'text-secondary';
	$artefato_class = 'artefato_opcao_nova_questao';
	$artefatos_nova_questao .= include 'templates/artefato_item.php';
	
	$template_modal_div_id = 'modal_vazio_questoes';
	$template_modal_titulo = 'Questões desta prova do concurso';
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$modal_scrollable = true;
	$template_modal_body_conteudo .= "
		<form method='post' class='p-3 mb-3 grey lighten-5 border' id='esconder_formulario_adicionar_questao'>
			<p>Adicionar questão desta prova do concurso:</p>
			<input type='hidden' id='nova_questao_curso_id' name='nova_questao_curso_id' value='$pagina_curso_id'>
			<input type='hidden' id='nova_questao_pagina_id' name='nova_questao_pagina_id' value='$pagina_id'>
			<input type='hidden' id='nova_questao_etapa_id' name='nova_questao_etapa_id' value=''>
			<input type='hidden' id='nova_questao_prova_id' name='nova_questao_prova_id' value=''>
			<input type='hidden' id='nova_questao_edicao_id' name='nova_questao_edicao_id' value=''>
			<input type='hidden' id='nova_questao_origem' name='nova_questao_origem' value=''>
			<input type='hidden' id='nova_questao_texto_apoio' name='nova_questao_texto_apoio' value=''>
			<input type='hidden' id='nova_questao_tipo' name='nova_questao_tipo' value=''>
			<p id='p_nova_questao_oficial' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão oficial do concurso.</p>
			<p id='p_nova_questao_nao_oficial' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão não-oficial do concurso.</p>
			<p id='p_nova_questao_texto_apoio' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão depende de texto de apoio.</p>
			<p id='p_nova_questao_nao_texto_apoio' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão não depende de texto de apoio.</p>
			<p id='p_nova_questao_dissertativa' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão dissertativa.</p>
			<p id='p_nova_questao_certo_errado' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão certo ou errado.</p>
			<p id='p_nova_questao_multipla_escolha' class='p_nova_questao_esconder text-success'><i class='fad fa-check-square fa-fw'></i> Questão de múltipla escolha.</p>
			<div class='row d-flex justify-content-start'>
				$artefatos_nova_questao
			</div>
			<div class='bg-white rounded p-3 border'>
				<div class='md-form'>
					<input type='number' class='form-control' name='nova_questao_numero' id='nova_questao_numero' required>
					<label for='nova_questao_numero'>Número da nova questão</label>
				</div>
			</div>
			<p class='mt-2'>Será criada uma página para a nova questão, vinculada à página deste verbete. Demais detalhes deverão ser adicionados na página na questão.</p>
			<div class='row d-flex justify-content-center'>
				<button class='$button_classes btn-info' id='trigger_nova_questao' name='trigger_nova_questao'>Criar questão</button>
			</div>
		</form>
		<ul class='list-group list-group-flush'>
			<a href='javascript:void(0)' id='carregar_formulario_adicionar_questao'><li class='list-group-item list-group-item-action list-group-item-info'>Adicionar questão desta prova do concurso.</li></a>
		</ul>
		<p>Questões desta prova registradas (pressione para adicionar à página deste tópico):</p>
		<div id='questoes_popular'>
		</div>
	";
	include 'templates/modal.php';
	
	echo "
		<script type='text/javascript'>
			$(document).ready(function() {
				$('#esconder_formulario_adicionar_edicao').hide();
				$('#esconder_formulario_adicionar_etapa').hide();
				$('#esconder_formulario_adicionar_prova').hide();
			});
			$('#carregar_formulario_adicionar_edicao').click(function() {
				$('#esconder_formulario_adicionar_edicao').show();
				$(this).hide();
			});
			$('#carregar_formulario_adicionar_etapa').click(function() {
				$('#esconder_formulario_adicionar_etapa').show();
				$(this).hide();
			});
			$('#carregar_formulario_adicionar_prova').click(function() {
				$('#esconder_formulario_adicionar_prova').show();
				$(this).hide();
			});
			$('#carregar_formulario_adicionar_questao').click(function() {
			    $('#esconder_formulario_adicionar_questao').show();
			    $(this).hide();
			});
			$(document).on('click', '.selecionar_edicao', function() {
			   edicao_value = $(this).attr('value');
			   $('#nova_etapa_edicao_id').val(edicao_value);
			   $('#nova_questao_edicao_id').val(edicao_value);
			   $.post('engine.php', {
			      'carregar_edicao': edicao_value
			   }, function(data) {
			       if (data != 0) {
			           $('#etapas_popular').empty();
			           $('#etapas_popular').append(data);
			       } else {
			           $('#etapas_popular').empty();
                 $('#etapas_popular').append('<p>Não há etapas registradas desta edição do concurso.</p>');
			       }
			   });
			});
			$(document).on('click', '.carregar_etapa', function() {
			    etapa_value = $(this).attr('value');
			    $('#nova_prova_etapa_id').val(etapa_value);
			    $('#nova_questao_etapa_id').val(etapa_value);
			    $.post('engine.php', {
			       'carregar_etapa': etapa_value
			    }, function(data) {
			        if (data != 0) {
			            $('#provas_popular').empty();
			            $('#provas_popular').append(data);
			        } else {
			            $('#provas_popular').empty();
			            $('#provas_popular').append('<p>Não há provas registradas desta etapa do concurso.</p>');
			        }
			    });
			});
			$(document).on('click', '.carregar_prova', function() {
			   $('.p_nova_questao_esconder').hide();
			   $('#esconder_formulario_adicionar_questao').hide();
			   $('.esconder_artefato_adicionar_questao').show();
			   $('#carregar_formulario_adicionar_questao').show();
			   $('.artefato_opcao_nova_questao').show();
			   prova_value = $(this).attr('value');
			   $('#nova_questao_prova_id').val(prova_value);
			   $.post('engine.php', {
			      'carregar_prova': prova_value
			   }, function(data) {
			       if (data != 0) {
			           $('#questoes_popular').empty();
			           $('#questoes_popular').append(data);
			       } else {
			           $('#questoes_popular').empty();
			           $('#questoes_popular').append('<p class=\'text-muted\'><em>Não há questões desta prova registradas.<em></p>');
			       }
			   });
			});$(document).on('click', '.adicionar_questao', function() {
			    adicionar_questao_id = $(this).attr('value');
			    $.post('engine.php', {
			        'adicionar_questao_id': adicionar_questao_id,
			        'adicionar_questao_pagina_id': {$pagina_id}
			    }, function(data) {
			        if (data != 0) {
			            window.location.reload(true);
			        } else {
			            alert('Ocorreu algum problema, a questão não foi adicionada.');
			        }
			    });
			});
			
			$(document).on('click', '#trigger_nova_questao_oficial', function() {
			    $('#nova_questao_origem').val(1);
			    $('#artefato_nova_questao_nao_oficial').hide();
			    $('#artefato_nova_questao_oficial').hide();
			    $('#p_nova_questao_oficial').show();
			});
			$(document).on('click', '#trigger_nova_questao_nao_oficial', function() {
          $('#nova_questao_origem').val(0);
          $('#artefato_nova_questao_nao_oficial').hide();
          $('#artefato_nova_questao_oficial').hide();
          $('#p_nova_questao_nao_oficial').show();
			});
			$(document).on('click', '#trigger_questao_de_texto_de_apoio', function() {
			    $('#nova_questao_texto_apoio').val(1);
			    $('#artefato_questao_de_texto_de_apoio').hide();
			    $('#artefato_questao_nao_texto_de_apoio').hide();
			    $('#p_nova_questao_texto_apoio').show();
			});
			$(document).on('click', '#trigger_questao_nao_texto_de_apoio', function() {
			    $('#nova_questao_texto_apoio').val(0);
			    $('#artefato_questao_de_texto_de_apoio').hide();
			    $('#artefato_questao_nao_texto_de_apoio').hide();
			    $('#p_nova_questao_nao_texto_apoio').show();
			});
			$(document).on('click', '#trigger_questao_verdadeiro_falso', function() {
			    $('#nova_questao_tipo').val(1);
			    $('#artefato_questao_dissertativa').hide();
			    $('#artefato_questao_multipla_escolha').hide();
			    $('#artefato_questao_verdadeiro_falso').hide();
			    $('#p_nova_questao_certo_errado').show();
			});
			$(document).on('click', '#trigger_questao_multipla_escolha', function() {
			    $('#nova_questao_tipo').val(2);
			    $('#artefato_questao_dissertativa').hide();
			    $('#artefato_questao_multipla_escolha').hide();
			    $('#artefato_questao_verdadeiro_falso').hide();
			    $('#p_nova_questao_multipla_escolha').show();
			});
			$(document).on('click', '#trigger_questao_dissertativa', function() {
			    $('#nova_questao_tipo').val(3);
			    $('#artefato_questao_dissertativa').hide();
			    $('#artefato_questao_multipla_escolha').hide();
			    $('#artefato_questao_verdadeiro_falso').hide();
			    $('#p_nova_questao_dissertativa').show();
			});
		</script>
	";
	
	