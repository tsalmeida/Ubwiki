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
	if ($edicoes->num_rows == 0) {
		$template_modal_body_conteudo .= "<p>Não há edições registradas deste concurso.</p>";
	} else {
		$template_modal_body_conteudo .= "
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
      	<button type='submit' class='$button_classes'>Adicionar seção</button>
			</div>
		</form>
		<ul class='list-group list-group-flush'>
		<a id='carregar_formulario_adicionar_edicao' href='javascript:void(0);'><li class='list-group-item list-group-item-action list-group-item-info d-flex justify-content-center'>Adicionar edição</li></a>
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
        	<a href='javascript:void(0);' id='carregar_formulario_adicionar_etapa'><li class='list-group-item list-group-item-active list-group-item-info'>Adicionar etapa desta edição do concurso</li></a>
				</ul>
        <div id='etapas_popular'>
        
				</div>
	";
	include 'templates/modal.php';
	
	echo "
		<script type='text/javascript'>
			$(document).ready(function() {
				$('#esconder_formulario_adicionar_edicao').hide();
				$('#esconder_formulario_adicionar_etapa').hide();
			});
			$('#carregar_formulario_adicionar_edicao').click(function() {
				$('#esconder_formulario_adicionar_edicao').show();
				$(this).hide();
			});
			$('#carregar_formulario_adicionar_etapa').click(function() {
				$('#esconder_formulario_adicionar_etapa').show();
				$(this).hide();
			});
			$('.selecionar_edicao').click(function() {
			   edicao_value = $(this).attr('value');
			   $('#nova_etapa_edicao_id').val(edicao_value);
			   $.post('engine.php', {
			      'carregar_edicao': edicao_value
			   }, function(data) {
			       if (data != 0) {
			           $('#etapas_popular').empty();
			           $('#etapas_popular').append(data);
			       } else {
                 $('#etapas_popular').append('<p>Não há etapas registradas desta edição do concurso.</p>');
			       }
			   });
			});
		</script>
	";