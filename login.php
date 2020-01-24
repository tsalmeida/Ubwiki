<?php

	$pagina_tipo = 'login';
	$pagina_id = false;
	include 'engine.php';
	include 'templates/html_head.php';
	if (isset($_SESSION['thinkific_email'])) {
		$thinkific_email = $_SESSION['thinkific_email'];
		$thinkific_bora = $_SESSION['thinkific_bora'];
	}
?>

<body class="grey lighten-5">
<div class="container">
	<div class="row d-flex justify-content-center mt-5">
		<div class="col">
			<?php
				$template_titulo = 'Ubwiki';
				$template_subtitulo = 'O que você vai aprender hoje?';
				$template_titulo_context = true;
				include 'templates/titulo.php';
			?>
		</div>
		<!--<div class="col">
			<?php
				/*$template_id = 'logo_ubwiki';
				$template_titulo = false;
				$template_conteudo = false;
				$template_botoes_padrao = false;
				$template_background = 'grey lighten-5';
				$template_conteudo_no_col = true;
				$logo_ubwiki = 'https://ubwiki.com.br/imagens/logo.png';
				$template_conteudo .= "
						        <div class='logo_ubwiki rounded m-1' style='background-image: url($logo_ubwiki)'>
                                </div>
						    ";
				include 'templates/page_element.php';*/
			?>
		</div>-->
	</div>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						
						$template_id = 'formulario_login';
						$template_titulo = 'Acessar';
						$template_botoes = false;
						$template_botoes_padrao = false;
						$template_conteudo = false;
						$template_classes = 'justify-content-center';
						$template_conteudo .= "
                            <form method='post' name='form_login' id='form_login'>
                                <p id='thinkific_transfer' class='collapse'>Não é mais necessário passar pela página do Grupo Ubique para acessar a Ubwiki. Crie uma senha abaixo.</p>
                                <p id='thinkific_senha_existe' class='collapse'>Porque você já criou uma senha, não é mais necessário passar pela página do Grupo Ubique para acessar a Ubwiki. Insira sua senha abaixo.</p>
                                <p id='thinkific_senha_incorreta' class='collapse'>Senha incorreta. Trata-se da senha que você criou na Ubwiki, não da sua senha na página do Grupo Ubique.</p>
                                <p id='login_mensagem_basica' class='collapse'>Para acessar ou criar uma conta, insira seu email abaixo.</p>
                                <p id='login_senha_incorreta' class='collapse'>Senha incorreta.</p>
                                <p id='login_novo_usuario' class='collapse'>Não existe conta registrada para este email. Continue para criar uma conta.</p>
                                <p id='login_thinkific_registro' class='collapse'>Para acessar a Ubwiki diretamente e criar uma nova senha, você precisará, uma última vez, passar pela <a href='https://www.grupoubique.com.br/'>página do Grupo Ubique</a>.</p>
                                <div id='secao_login_email' class='md-form mt-3 collapse'>
                                    <input type='email' id='login_email' name='login_email' class='form-control'>
                                    <label for='login_email'>Seu email</label>
                                </div>
                                <div id='secao_login_thinkific_email' class='md-form collapse'>
                                    <input type='email' id='login_thinkific_email' name='login_thinkific_email' class='form-control' disabled>
                                    <label for='login_thinkific_email'></label>
                                </div>
                                <div id='secao_login_senha' class='md-form collapse'>
                                    <input type='password' id='login_senha' name='login_senha' class='form-control'>
                                    <label for='login_senha'>Sua senha</label>
                                </div>
                                <div id='secao_login_confirmacao' class='md-form collapse'>
                                    <input type='password' id='login_senha_confirmacao' name='login_senha_confirmacao' class='form-control'>
                                    <label for='login_senha_confirmacao'>Confirme sua senha</label>
                                </div>
                                <div id='secao_login_enviar' class='md-form d-flex justify-content-center'>
                                    <button id='botao_login' name='botao_login' type='button' class='$button_classes w-50' disabled>Continuar</button>
                                </div>
                            </form>
                        ";
						include 'templates/page_element.php';
						
						$template_id = 'sobre_ubwiki';
						$template_titulo = 'Sobre a Ubwiki';
						$template_botoes = false;
						$template_botoes_padrao = false;
						$template_conteudo = false;
						$template_conteudo .= return_verbete_html(548);
						include 'templates/page_element.php';
					?>
        </div>
    </div>
</div>
</body>
<?php
	if (!isset($thinkific_email)) {
		echo "
			<script type='text/javascript'>
				function isEmail(email) {
                  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                  return regex.test(email);
                }
				$('#secao_login_email').show();
				$('#login_mensagem_basica').show();
				$('#login_email').keyup(function() {
				    email = $('#login_email').val();
				    email_check = isEmail(email);
				    if (email_check == true) {
				        $('#secao_login_senha').show();
				    } else {
				        $('#secao_login_senha').hide();
				    }
				});
				$('#login_senha').keyup(function() {
                    var senha = $('#login_senha').val();
                    var senha_length = senha.length;
                    if (senha_length > 5) {
                        $('#botao_login').prop('disabled', false)
                    } else {
                        $('#botao_login').prop('disabled', true)
                    }
                 });
				$('#login_senha_confirmacao').keyup(function() {
                    var senha = $('#login_senha').val();
   				    var senha2 = $('#login_senha_confirmacao').val();
                    if (senha == senha2) {
                        $('#botao_login').prop('disabled', false)
                    } else {
                        $('#botao_login').prop('disabled', true)
                    }
                 });
				
                  $(document).on('click', '#botao_login', function() {
                      var login = $('#login_email').val();
                      var senha = $('#login_senha').val();
                      var senha2 = $('#login_senha_confirmacao').val();
                      if (senha2 == false) {
                        $.post('engine.php', {
                            'login_email': login,
                            'login_senha': senha,
                            'login_origem': 'desconhecido'
                        }, function(data) {
                            if ((data == 1) || (data == 11)) {
                                window.location.replace('index.php');
                            } else if (data == 0) {
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_senha_incorreta').show();
                            } else if (data == 'novo_usuario') {
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_novo_usuario').show();
                                $('#secao_login_confirmacao').show();
                                $('#login_email').prop('disabled', true);
                                $('#login_senha').prop('disabled', true);
                                $('#botao_login').prop('disabled', true);
                            } else if (data == 'thinkific') {
                                $('#login_thinkific_registro').show();
                                $('#login_mensagem_basica').addClass('text-muted');
                                $('#login_email').prop('disabled', true);
                                $('#login_senha').prop('disabled', true);
                            }
                        });
                      } else {
                          if (senha != senha2) {
                              alert('Senhas não conferem.');
                          } else {
                              $.post('engine.php', {
                                 'login_email': login,
                                 'login_senha': senha,
                                 'login_senha2': senha2,
                              }, function(data) {
                                  if (data == 1) {
                                      window.location.replace('index.php');
                                  } else {
                                      alert('Ocorreu algum problema, sua conta não foi criada.');
                                  }
                              });
                          }
                      }
                  });
			</script>
		";
	} else {
		$usuarios = $conn->query("SELECT id FROM Usuarios WHERE email = '$thinkific_email' AND senha IS NULL");
		if ($usuarios->num_rows == 0) {
			echo "
	            <script type='text/javascript'>
	               $('#thinkific_senha_existe').show();
	               $('#login_thinkific_email').val('$thinkific_email');
	               $('#secao_login_thinkific_email').show();
	               $('#secao_login_senha').show();
	               $('#secao_login_confirmacao').hide();
	               $('#login_senha').keyup(function() {
	                    var senha = $('#login_senha').val();
	                    var senha_length = senha.length;
	                    if (senha_length > 5) {
	                        $('#botao_login').prop('disabled', false)
	                    } else {
	                        $('#botao_login').prop('disabled', true)
	                    }
	               });
	               $(document).on('click', '#botao_login', function() {
	                    var login = '$thinkific_email';
	                    var senha = $('#login_senha').val();
	                    $.post('engine.php', {
	                        'login_email': login,
	                        'login_senha': senha,
	                        'login_origem': 'thinkific'
	                    }, function(data) {
	                        if (data != 0) {
	                            window.location.replace('index.php');
	                        } else {
	                            $('#thinkific_senha_existe').addClass('text-muted');
	                            $('#thinkific_senha_incorreta').show();
	                        }
	                    });
	               });
                </script>
	        ";
		} else {
			echo "
                <script type='text/javascript'>
                    $('#secao_login_thinkific_email').show();
                    $('#thinkific_transfer').show();
                    $('#login_thinkific_email').val('$thinkific_email');
                    $('#secao_login_senha').show();
                    $('#secao_login_confirmacao').show();
                    $('#login_senha_confirmacao').keyup(function() {
                       var senha1 = $('#login_senha').val();
                       var senha2 = $('#login_senha_confirmacao').val();
                       var senha_length = senha2.length;
                       if (senha_length > 5) {
                           if (senha2 != '') {
                               if (senha1 == senha2) {
                                   $('#botao_login').prop('disabled', false)
                               }
                           } else {
                               $('#botao_login').prop('disabled', true)
                           }
                       } else {
                          $('#botao_login').prop('disabled', true)
                       }
                       if (senha1 != senha2) {
                          $('#botao_login').prop('disabled', true)
                       }
                    });
                    $(document).on('click', '#botao_login', function() {
                       var login = '$thinkific_email';
                       var senha1 = $('#login_senha').val();
                        $.post('engine.php', {
                            'thinkific_login': login,
                            'thinkific_senha': senha1,
                        }, function(data) {
                            if (data != 0) {
                                window.location.replace('index.php');
                            } else {
                                alert('Senha incorreta.');
                            }
                        });
                    });
                </script>
            ";
		}
	}
	include 'templates/html_bottom.php';
	include 'templates/footer.html';
?>
</html>

