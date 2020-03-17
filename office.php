<?php
	
	$pagina_tipo = 'escritorio';
	include 'engine.php';
	$pagina_id = return_pagina_id($user_id, $pagina_tipo);
	
	if (!isset($user_email)) {
		header('Locatino:ubwiki.php');
	}
	
	if (isset($_POST['novo_nome'])) {
		$novo_user_nome = $_POST['novo_nome'];
		$novo_user_sobrenome = $_POST['novo_sobrenome'];
		$novo_user_apelido = $_POST['novo_apelido'];
		$apelidos = $conn->query("SELECT id FROM Usuarios WHERE apelido = '$novo_user_apelido' AND id <> $user_id");
		if ($apelidos->num_rows == 0) {
			$conn->query("UPDATE Usuarios SET nome = '$novo_user_nome', sobrenome = '$novo_user_sobrenome', apelido = '$novo_user_apelido' WHERE id = $user_id");
			$user_nome = $novo_user_nome;
			$user_sobrenome = $novo_user_sobrenome;
			$user_apelido = $novo_user_apelido;
			if (isset($_POST['opcao_texto_justificado'])) {
				$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 1)");
				$opcao_texto_justificado_value = true;
			} else {
				$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao) VALUES ($user_id, 'texto_justificado', 0)");
				$opcao_texto_justificado_value = false;
			}
		}
	}
	
	if (isset($_POST['selecionar_avatar'])) {
		$novo_avatar = $_POST['selecionar_avatar'];
		$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar', '$novo_avatar')");
	}
	
	if (isset($_POST['selecionar_cor'])) {
		$nova_cor = $_POST['selecionar_cor'];
		$conn->query("INSERT INTO Opcoes (user_id, opcao_tipo, opcao_string) VALUES ($user_id, 'avatar_cor', '$nova_cor')");
	}
	
	include 'pagina/shared_issets.php';
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";
	
	include 'templates/html_head.php';
	include 'templates/navbar.php';

?>
<body class="grey lighten-5">
<div class="container">
	<?php
		if ($user_apelido != false) {
			$template_titulo = $user_apelido;
			$template_titulo_above = $pagina_translated['user_office'];
		} else {
			$template_titulo = $pagina_translated['user_office'];
		}
		$template_titulo_context = true;
		include 'templates/titulo.php'
	
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center mx-1">
        <div id="coluna_unica" class="col">
					<?php
						$template_id = 'escritorio_primeira_janela';
						$template_titulo = false;
						$template_conteudo = false;
						$template_conteudo_class = 'justify-content-start';
						$template_conteudo_no_col = true;
						
						if ($curso_id != false) {
							$artefato_id = 'curso_ativo';
							$artefato_titulo = $curso_titulo;
							$artefato_badge = 'fa-external-link';
							$artefato_icone_background = 'rgba-black-strong';
							$artefato_link = "pagina.php?curso_id=$curso_id";
							$artefato_criacao = $pagina_translated['Curso ativo'];
							$fa_type = 'fas';
							$fa_icone = 'fa-pen-alt';
							$fa_color = 'text-light';
							$template_conteudo .= include 'templates/artefato_item.php';
						}
						
						$usuario_avatar = return_avatar($user_id);
						$fa_icone = $usuario_avatar[0];
						$fa_color = $usuario_avatar[1];
						$artefato_modal = '#modal_opcoes';
						$artefato_badge = 'fa-cog fa-swap-opacity';
						$artefato_subtitulo = 'Suas configurações';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'estudos_recentes';
						$artefato_subtitulo = 'Estudos recentes';
						$artefato_modal = '#modal_estudos_recentes';
						$fa_icone = 'fa-history fa-swap-opacity';
						$fa_color = 'text-info';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'cursos';
						$artefato_subtitulo = 'Seus cursos';
						$fa_icone = 'fa-book-reader';
						$fa_color = 'text-success';
						$artefato_link = 'cursos.php';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'typewriter';
						$artefato_subtitulo = $pagina_translated['Suas páginas e documentos de texto'];
						$fa_icone = 'fa-typewriter';
						$fa_color = 'text-primary';
						$artefato_modal = '#modal_paginas_textos';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'suas_paginas_livres';
						$artefato_subtitulo = 'Páginas livres de seu interesse';
						$fa_icone = 'fa-tags';
						$fa_color = 'text-warning';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'biblioteca_particular';
						$artefato_subtitulo = 'Sua biblioteca particular';
						$fa_icone = 'fa-books';
						$fa_color = 'text-success';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seus_grupos_estudo';
						$artefato_subtitulo = 'Seus grupos de estudo';
						$fa_icone = 'fa-users';
						$fa_color = 'text-default';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'suas_notificacoes';
						$artefato_subtitulo = 'Suas notificações';
						$fa_icone = 'fa-bell fa-swap-opacity';
						$fa_color = 'text-info';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seu_forum';
						$artefato_subtitulo = 'Suas participações em fórum';
						$fa_icone = 'fa-comments-alt';
						$fa_color = 'text-secondary';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seus_bookmarks';
						$artefato_subtitulo = 'Sua lista de leitura';
						$fa_icone = 'fa-bookmark';
						$fa_color = 'text-danger';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'seu_artigos';
						$artefato_subtitulo = 'Artigos que você ajudou a escrever';
						$fa_icone = 'fa-spa';
						$fa_color = 'text-warning';
						$template_conteudo .= include 'templates/artefato_item.php';
						
						$artefato_id = 'sala_visitas';
						$artefato_subtitulo = 'Sua sala de visitas';
						$artefato_badge = 'fa-external-link';
						$fa_icone = 'fa-mug-tea';
						$fa_color = 'text-secondary';
						$template_conteudo .= include "templates/artefato_item.php";
						
						
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
</div>
<?php
	
	if ($user_id == false) {
		$carregar_modal_login = true;
		include 'pagina/modal_login.php';
	}
	include 'pagina/modal_languages.php';
	
	$template_modal_div_id = 'modal_opcoes';
	$template_modal_titulo = $pagina_translated['user settings'];
	if ($opcao_texto_justificado_value == true) {
		$texto_justificado_checked = 'checked';
	} else {
		$texto_justificado_checked = false;
	}
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<h3>Avatar</h3>
		<div class='row justify-content-center'>
			<a href='pagina.php?user_id=$user_id' class='{$usuario_avatar[1]}'><i class='fad {$usuario_avatar[0]} fa-3x fa-fw'></i></a>
		</div>
		<p>Alterar:</p>
		<select name='selecionar_avatar' class='$select_classes'>
			<option disabled selected value=''>{$pagina_translated['Selecione seu avatar']}</option>
			<option value='fa-user'>{$pagina_translated['Padrão']}</option>
			<option value='fa-user-tie'>{$pagina_translated['De terno']}</option>
			<option value='fa-user-secret'>{$pagina_translated['Espião']}</option>
			<option value='fa-user-robot'>{$pagina_translated['Robô']}</option>
			<option value='fa-user-ninja'>{$pagina_translated['Ninja']}</option>
			<option value='fa-user-md'>{$pagina_translated['Médico']}</option>
			<option value='fa-user-injured'>{$pagina_translated['Machucado']}</option>
			<option value='fa-user-hard-hat'>{$pagina_translated['Capacete de segurança']}</option>
			<option value='fa-user-graduate'>{$pagina_translated['Formatura']}</option>
			<option value='fa-user-crown'>{$pagina_translated['Rei']}</option>
			<option value='fa-user-cowboy'>{$pagina_translated['Cowboy']}</option>
			<option value='fa-user-astronaut'>{$pagina_translated['Astronauta']}</option>
			<option value='fa-user-alien'>{$pagina_translated['Alienígena']}</option>
			<option value='fa-cat'>{$pagina_translated['Gato']}</option>
			<option value='fa-cat-space'>{$pagina_translated['Gato astronauta']}</option>
			<option value='fa-dog'>{$pagina_translated['Cachorro']}</option>
			<option value='fa-ghost'>{$pagina_translated['Fantasma']}</option>
		</select>
		<select name='selecionar_cor' class='$select_classes'>
			<option disabled selected value=''>{$pagina_translated['Cor do seu avatar']}</option>
			<option value='text-primary'>{$pagina_translated['Azul']}</option>
			<option value='text-danger'>{$pagina_translated['Vermelho']}</option>
			<option value='text-success'>{$pagina_translated['Verde']}</option>
			<option value='text-warning'>{$pagina_translated['Amarelo']}</option>
			<option value='text-secondary'>{$pagina_translated['Roxo']}</option>
			<option value='text-info'>{$pagina_translated['Azul-claro']}</option>
			<option value='text-default'>{$pagina_translated['Verde-azulado']}</option>
			<option value='text-dark'>{$pagina_translated['Preto']}</option>
		</select>
		<h3 class='mt-3'>{$pagina_translated['Perfil']}</h3>
        <p>{$pagina_translated['Você é identificado exclusivamente por seu apelido em todas as suas atividades públicas.']}</p>
        <div class='md-form md-2'><input type='text' name='novo_apelido' id='novo_apelido' class='form-control validate' value='$user_apelido' pattern='([A-z0-9À-ž\s]){2,14}' required>
            <label data-error='inválido' data-successd='válido' for='novo_apelido' required>{$pagina_translated['Apelido']}</label>
        </div>
        <p>{$pagina_translated['Seu nome e seu sobrenome não serão divulgados em nenhuma seção pública da página.']}</p>
        <div class='md-form md-2'>
               <input type='text' name='novo_nome' id='novo_nome' class='form-control validate' value='$user_nome' pattern='([A-z0-9À-ž\s]){2,}' required></input>

            <label data-error='inválido' data-successd='válido'
                   for='novo_nome'>{$pagina_translated['Nome']}</label>
        </div>
        <div class='md-form md-2'>
            <input type='text' name='novo_sobrenome' id='novo_sobrenome' class='form-control validate' value='$user_sobrenome' required>

            <label data-error='inválido' data-successd='válido' for='novo_sobrenome' pattern='([A-z0-9À-ž\s]){2,}' required>{$pagina_translated['Sobrenome']}</label>
        </div>
        <h3>Opções</h3>
        <div class='md-form md-2'>
        	<input type='checkbox' class='form-check-input' id='opcao_texto_justificado' name='opcao_texto_justificado' $texto_justificado_checked>
        	<label class='form-check-label' for='opcao_texto_justificado'>{$pagina_translated['Mostrar verbetes com texto justificado']}</label>
		</div>
    ";
	$template_modal_body_conteudo .= "
        <h3>{$pagina_translated['Dados de cadastro']}</h3>
        <ul class='list-group'>
            <li class='list-group-item'><strong>{$pagina_translated['Conta criada em']}:</strong> $user_criacao</li>
            <li class='list-group-item'><strong>{$pagina_translated['Email']}:</strong> $user_email</li>
        </ul>
	";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_estudos_recentes';
	$template_modal_titulo = $pagina_translated['recent_visits'];
	$template_modal_body_conteudo = false;
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_paginas_textos';
	$template_modal_titulo = $pagina_translated['Suas páginas e documentos de texto'];
	$template_modal_show_buttons = false;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "<div class='row d-flex justify-content-around border rounded m-1'>";
	
	$artefato_id = 'nova_pagina';
	$artefato_titulo = $pagina_translated['new_private_page'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?pagina_id=new';
	$fa_icone = 'fa-columns';
	$fa_color = 'text-info';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$artefato_id = 'novo_documento_texto';
	$artefato_titulo = $pagina_translated['Nova anotação privada'];
	$artefato_col_limit = 'col-lg-4';
	$artefato_badge = 'fa-plus';
	$artefato_link = 'pagina.php?texto_id=new';
	$fa_icone = 'fa-file-alt fa-swap-opacity';
	$fa_color = 'text-primary';
	$template_modal_body_conteudo .= include 'templates/artefato_item.php';
	
	$template_modal_body_conteudo .= "</div>";
	
	$template_modal_body_conteudo .= "<h3 id='user_pages_hide' class='hidden'>{$pagina_translated['your_pages']}</h3>";
	
	$template_modal_body_conteudo .= "<ul id='user_pages' class='list-group list-group-flush'></ul>";
	
	$template_modal_body_conteudo .= "<h3 id='user_texts_hide' class='hidden'>{$pagina_translated['texts and study notes']}</h3>";
	
	$template_modal_body_conteudo .= "<ul id='user_texts' class='list-group list-group-flush'></ul>";
	
	include 'templates/modal.php';
	
?>
</body>

	<script type="text/javascript">
      $(document).on('click', '#artefato_estudos_recentes', function() {
          $.post('engine.php', {
              'list_estudos_recentes': true
          }, function(data) {
              if (data != 0) {
                  $('#body_modal_estudos_recentes').empty();
                  $('#body_modal_estudos_recentes').append(data);
              }
          });
      });
      $(document).on('click', '#artefato_typewriter', function() {
          $.post('engine.php', {
              'list_user_pages': true
          }, function(data) {
              if (data != 0) {
                  $('#user_pages_hide').removeClass('hidden');
                  $('#user_pages').empty();
                  $('#user_pages').append(data);
              } else {
                  $('#user_pages_hide').addClass('hidden');
              }
          });
          $.post('engine.php', {
              'list_user_texts': true
          }, function(data) {
              if (data != 0) {
                  $('#user_texts_hide').removeClass('hidden');
                  $('#user_texts').empty();
                  $('#user_texts').append(data);
              } else {
                  $('#user_texts_hide').addClass('hidden');
              }
          });
      });
	</script>

<?php
	
	include 'templates/footer.html';
	$sistema_etiquetas_elementos = true;
	$sistema_etiquetas_topicos = true;
	$mdb_select = true;
	$esconder_introducao = true;
	include 'templates/html_bottom.php';

?>
</html>