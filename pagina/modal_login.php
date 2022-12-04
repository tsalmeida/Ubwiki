<?php

	$template_modal_div_id = 'modal_login';
	$template_modal_titulo = $pagina_translated['access'];
	$template_modal_body_conteudo = false;
	$modal_focus = 'login_email';

	$template_modal_body_conteudo .= "
    <form method='post' name='form_login' id='form_login'>
        <p id='thinkific_transfer' class='collapse'>Não é mais necessário passar pela página do Grupo Ubique para acessar a Ubwiki. Crie uma senha abaixo.</p>
        <p id='thinkific_senha_existe' class='collapse'>Porque você já criou uma senha, não é mais necessário passar pela página do Grupo Ubique para acessar a Ubwiki. Insira sua senha abaixo.</p>
        <p id='thinkific_senha_incorreta' class='collapse link-danger'>Senha incorreta. Trata-se da senha que você criou na Ubwiki, não da sua senha na página do Grupo Ubique.</p>
        <p id='login_mensagem_basica' class='collapse'>{$pagina_translated['access_message']} <span data-bs-toggle='modal' data-bs-target='#modal_login'><a href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#modal_nova_senha'>{$pagina_translated['lost password?']}</a></span></p>
        <p id='login_senha_confirmar' class='collapse'>{$pagina_translated['correct_password_but']}</p>
        <p id='login_senha_incorreta' class='collapse link-danger'>{$pagina_translated['Senha incorreta.']}</p>
        <p id='login_novo_usuario' class='collapse'>{$pagina_translated['Não existe conta registrada para este email. Continue para criar uma conta.']}</p>
        <p id='login_thinkific_registro' class='collapse'>Para acessar a Ubwiki diretamente e criar uma nova senha, você precisará, uma última vez, passar pela <a href='https://www.grupoubique.com.br/'>página do Grupo Ubique</a>. Alternativamente, você pode escolher a opção \"Esqueceu sua senha?\" e receber uma nova senha por email.</p>
        <div id='secao_login_email' class='mb-3 mb-3 collapse'>
            <label class='form-label' for='login_email'>{$pagina_translated['your_email']}</label>
            <input type='email' id='login_email' name='login_email' class='form-control' autocomplete='username'>
        </div>
        <div id='secao_login_thinkific_email' class='mb-3 collapse mb-3'>
            <label class='form-label' for='login_thinkific_email'></label>
            <input type='email' id='login_thinkific_email' name='login_thinkific_email' class='form-control' autocomplete='username' disabled>
        </div>
        <div id='secao_login_senha' class='mb-3 collapse mb-3'>
	        <label class='form-label' for='login_senha'>{$pagina_translated['your_password']}</label>
            <input type='password' id='login_senha' name='login_senha' autocomplete='password' class='form-control'>
        </div>
        <div id='secao_login_confirmacao' class='mb-3 collapse mb-3'>
            <label class='form-label' for='login_senha_confirmacao'>{$pagina_translated['Confirme sua senha']}</label>
            <input type='password' id='login_senha_confirmacao' name='login_senha_confirmacao' class='form-control' autocomplete='password' disabled>
        </div>
        <div id='secao_login_enviar' class='mb-3 d-flex justify-content-center'>
            <button id='botao_login' name='botao_login' type='button' class='btn btn-primary' disabled>{$pagina_translated['continue']}</button>
        </div>
    </form>
";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_nova_senha';
	$template_modal_titulo = $pagina_translated['send_by_email'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<p>{$pagina_translated['forgot_your_password']}</p>
		<form>
			<div class='mb-3'>
				<label for='nova_senha_email' class='form-label'>{$pagina_translated['your_email']}</label>
				<input type='text' class='form-control' name='nova_senha_email' id='nova_senha_email' autocomplete='new-password'>
			</div>
			<div class='mb-3'>
				<label for='nova_senha' class='form-label'>{$pagina_translated['your_new_password']}</label>
				<input type='password' class='form-control' name='nova_senha' id='nova_senha' autocomplete='new-password'>
			</div>
		</form>
	";
	include 'templates/modal.php';
	