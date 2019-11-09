<?php
	
	if (!isset($loginpage)) {
		$loginpage = false;
	}
	
	$sessionpath = getcwd();
	$sessionpath .= '/../sessions';
	session_save_path($sessionpath);
	session_start();
	
	$servername = "localhost";
	$username = "grupoubique";
	$password = "ubique patriae memor";
	$dbname = "Ubique";
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	
	$user_email = false;
	$newuser = false;
	$special = false;
	
	if (isset($_GET['special'])) {
		$special = $_GET['special'];
	}
	if ($special == 14836) {
		$_SESSION['email'] = 'tsilvaalmeida@gmail.com';
		$special = true;
	} elseif ($special == 19815848) {
		$_SESSION['email'] = 'cavalcanti@me.com';
		$special = true;
	} elseif ($special == 17091979) {
		$_SESSION['email'] = 'marciliofcf@gmail.com';
		$special = true;
	}
	if (!isset($_SESSION['email'])) {
		if ((isset($_POST['email'])) && (isset($_POST['bora']))) {
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['bora'] = $_POST['bora'];
			$user_email = $_SESSION['email'];
			$bora = $_SESSION['bora'];
			$result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
			if ($result->num_rows == 0) {
				$newuser = true;
				$insert = $conn->query("INSERT INTO Usuarios (tipo, email) VALUES ('estudante', '$user_email')");
			}
		} else {
			if ($loginpage == false) {
				header('Location:login.php');
			}
		}
	} else {
		$user_email = $_SESSION['email'];
	}
	if ($user_email != false) {
		$result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
//        header("Location:index.php");
			}
		}
	} else {
		if ($special == true) {
//      header('Location:index.php');
		}
	}
	
	$result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$user_id = $row['id'];
		}
	}
	
	function carregar_navbar()
	{
		$args = func_get_args();
		$forum = false;
		$count = 0;
		while (isset($args[$count])) {
			$arg = $args[$count];
			if ($arg == 'dark') {
				$mode = 'dark';
			} elseif ($arg == 'light') {
				$mode = 'light';
			}
			$count++;
		}
		if ($mode == 'dark') {
			$mode = 'dark';
			$color = 'elegant-color';
		} elseif ($mode == 'light') {
			$color = 'bg-white';
		}
		echo "<nav class='navbar navbar-expand-lg $color' id='inicio'>";
		if ($mode == 'dark') {
			echo "<a class='navbar-brand playfair900 text-white' href='index.php'>Ubwiki</a>";
		} else {
			echo "<a class='navbar-brand playfair900 text-dark' href='index.php'>Ubwiki</a>";
		}
		echo "<ul class='nav navbar-nav ml-auto nav-flex-icons'>";
		echo "<li class='nav-item dropdown'>";
		if ($mode == 'dark') {
			echo "<a class='navlink dropdown-toggle waves-effect waves-light text-white' id='user_dropdown' data-toggle='dropdown' href='javascript:void(0);'>";
		} else {
			echo "<a class='navlink dropdown-toggle waves-effect waves-light text-dark' id='user_dropdown' data-toggle='dropdown' href='javascript:void(0);'>";
		}
		echo "
        <i class='fas fa-user-tie fa-lg fa-fw'></i>
        </a>
        <div class='dropdown-menu dropdown-menu-right z-depth-0'>
          <a class='dropdown-item navlink' href='usuario.php'>Sua página</a>
          <a class='dropdown-item navlink' href='logout.php'>Logout</a>
      </li>
    </ul>
  </nav>";
	}
	
	function top_page()
	{
		$args = func_get_args();
		echo '
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b8e073920a.js" crossorigin="anonymous"></script>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Bootstrap Horizon -->
    <link href="css/bootstrap-horizon.css" rel="stylesheet">
    <link type="image/vnd.microsoft.icon" rel="icon" href="../imagens/favicon.ico"/>
	  <!-- JQuery -->
	  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <title>Ubwiki</title>';
		
		if ($args != false) {
			$array = 0;
			while (isset($args[$array])) {
				if ($array == 0) {
					if ($args[0] != false) {
						echo $args[0];
					}
				}
				if (($args[$array] == "quill_v") || ($args[$array] == "quill_admin") || ($args[$array] == 'quill_user') || ($args[$array] == 'quill_elemento')) {
					echo "
            <link href='css/quill.snow.css' rel='stylesheet'>
          ";
				} elseif ($args[$array] == "onepage") {
					echo "
            <style>
              html, body, .onepage {
                height: 100vh;
                overflow-y: auto;
              }
            </style>
          ";
				}
				$array++;
			}
		}
		echo '
  </head>
  ';
	}
	
	function bottom_page()
	{
		
		$args = func_get_args();
		
		echo '
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript" charset="UTF-8" src="engine.js"></script>
  ';
		//TODO: imageHandler deve salvar no artigo o link interno do servidor, e não o link original. Não é difícil de fazer, basta capturar a resposta em echo do engine.php e substituir o link final onde diz 'value'
		if ($args != false) {
			$array = 0;
			while (isset($args[$array])) {
				if ($args[$array] == "quill_admin") {
					echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions_admin = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist_admin = ['italic','script','link','blockquote','list','header','strike'];
            var admin_editor = new Quill('#quill_editor_admin', {
              theme: 'snow',
              formats: formatWhitelist_admin,
              modules: {
                toolbar: toolbarOptions_admin
              }
            });
            var form_admin = document.querySelector('#quill_admin_form');
            form_admin.onsubmit = function() {
              var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
              quill_nova_mensagem_html.value = admin_editor.root.innerHTML;
            }
          </script>
        ";
				}
				if ($args[$array] == "quill_user") {
					echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions_user = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist_user = ['italic','script','link','blockquote','list','header','strike'];
            var user_editor = new Quill('#quill_editor_user', {
              theme: 'snow',
              formats: formatWhitelist_user,
              modules: {
                toolbar: toolbarOptions_user
              }
            });
            var form_user = document.querySelector('#quill_user_form');
            form_user.onsubmit = function() {
              var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
              quill_nova_mensagem_html.value = user_editor.root.innerHTML;
            }
          </script>
        ";
				} elseif ($args[$array] == "quill_v") {
					echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script type='text/javascript'>
            var toolbarOptions_verbete = [
              ['italic'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean'],
              ['image']
            ];
            var toolbarOptions_anotacao = [
              ['italic'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist_verbete = ['italic','script','link','blockquote','list','header','image'];
            var formatWhitelist_anotacao = ['italic','script','link','blockquote','list','header','image'];
            var Delta_verbete = Quill.import('delta');
            var verbete_editor = new Quill('#quill_editor_verbete', {
              theme: 'snow',
              formats: formatWhitelist_verbete,
              modules: {
                toolbar: {
                  container: toolbarOptions_verbete,
                  handlers: {
                    image: imageHandler
                  }
                }
              }
            });
            verbete_editor.disable();
            $('.ql-toolbar:first').hide();

            function imageHandler() {
                var range = this.quill.getSelection();
                var value = prompt('Qual o endereço da imagem?');
                value = encodeURI(value);
                if(value){
                    $.post('engine.php', {
                      'nova_imagem': value,
                      'user_id': $args[0],
                      'tema_id': $args[1]
                    },
                    function(data) {
                        alert(data);
                    });
                		this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
                }
            }

            var anotacao_editor = new Quill('#quill_editor_anotacao', {
              theme: 'snow',
              formats: formatWhitelist_anotacao,
              modules: {
                toolbar: toolbarOptions_anotacao
              }
            });
            $('#travar_verbete').click(function(){
              verbete_editor.disable();
              $('#destravar_verbete').show();
              $('#travar_verbete').hide();
              $('.ql-toolbar:first').hide();
              $('.ql-editor:first').removeClass('ql-editor-active');
            });
            $('#destravar_verbete').click(function(){
              verbete_editor.enable();
              $('#travar_verbete').show();
              $('#destravar_verbete').hide();
              $('.ql-toolbar:first').show();
              $('.ql-editor:first').addClass('ql-editor-active');
            });
            $('#travar_anotacao').click(function(){
              anotacao_editor.disable();
              $('#travar_anotacao').hide();
              $('#destravar_anotacao').show();
              $('.ql-toolbar:last').hide();
              $('.ql-editor:last').removeClass('ql-editor-active');
            });
            $('.ql-editor:last').addClass('ql-editor-active');
            $('#destravar_anotacao').click(function(){
              anotacao_editor.enable();
              $('#destravar_anotacao').hide();
              $('#travar_anotacao').show();
              $('.ql-toolbar:last').show();
              $('.ql-editor:last').addClass('ql-editor-active');
            });
            var form_verbete = document.querySelector('#quill_verbete_form');
            form_verbete.onsubmit = function() {
              var quill_novo_verbete_html = document.querySelector('input[name=quill_novo_verbete_html]');
              quill_novo_verbete_html.value = verbete_editor.root.innerHTML;

              var quill_novo_verbete_text = document.querySelector('input[name=quill_novo_verbete_text]');
              quill_novo_verbete_text.value = verbete_editor.getText();

              var quill_novo_verbete_content = document.querySelector('input[name=quill_novo_verbete_content]');
              var quill_verbete_content = verbete_editor.getContents();
              quill_verbete_content = JSON.stringify(quill_verbete_content);
              quill_verbete_content = encodeURI(quill_verbete_content);
              quill_novo_verbete_content.value = quill_verbete_content;
            };
            var form_anotacao = document.querySelector('#quill_anotacao_form');
            form_anotacao.onsubmit = function() {
              var quill_nova_anotacao_html = document.querySelector('input[name=quill_nova_anotacao_html]');
              quill_nova_anotacao_html.value = anotacao_editor.root.innerHTML;
            }
          </script>
        ";
				} elseif ($args[$array] == "quill_elemento") {
					echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions_elemento = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist_elemento = ['italic','script','link','blockquote','list','header','strike'];
            var elemento_editor = new Quill('#quill_editor_elemento', {
              theme: 'snow',
              formats: formatWhitelist_elemento,
              modules: {
                toolbar: toolbarOptions_elemento
              }
            });
            var form_elemento = document.querySelector('#quill_elemento_form');
            form_elemento.onsubmit = function() {
              var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
              quill_nova_mensagem_html.value = elemento_editor.root.innerHTML;
            }
          </script>
        ";
				} elseif ($args[$array] == "edicao_topicos") {
					echo "
          <script type='text/javascript'>
            $(document).ready(function() {
              $('.novosub').hide();
            });
            $('#novosub1').on('input',function(){
          	    $('#novosub2').show();
          	});
            $('#novosub2').on('input',function(){
          	    $('#novosub3').show();
          	});
            $('#novosub3').on('input',function(){
          	    $('#novosub4').show();
          	});
            $('#novosub4').on('input',function(){
          	    $('#novosub5').show();
          	});
            $('#novosub5').on('input',function(){
          	    $('#novosub6').show();
          	});
            $('#novosub6').on('input',function(){
          	    $('#novosub7').show();
          	});
            $('#novosub7').on('input',function(){
          	    $('#novosub8').show();
          	});
            $('#novosub8').on('input',function(){
          	    $('#novosub9').show();
          	});
            $('#novosub9').on('input',function(){
          	    $('#novosub10').show();
          	});
            $('#novosub10').on('input',function(){
          	    $('#novosub11').show();
          	});
            $('#novosub11').on('input',function(){
          	    $('#novosub12').show();
          	});
            $('#novosub12').on('input',function(){
          	    $('#novosub13').show();
          	});
            $('#novosub13').on('input',function(){
          	    $('#novosub14').show();
          	});
            $('#novosub14').on('input',function(){
          	    $('#novosub15').show();
          	});
            $('#novosub15').on('input',function(){
          	    $('#novosub16').show();
          	});
            $('#novosub16').on('input',function(){
          	    $('#novosub17').show();
          	});
            $('#novosub17').on('input',function(){
          	    $('#novosub18').show();
          	});
            $('#novosub18').on('input',function(){
          	    $('#novosub19').show();
          	});
            $('#novosub19').on('input',function(){
          	    $('#novosub20').show();
          	});
          </script>
        ";
				} elseif ($args[$array] == 'lightbox-imagens') {
					echo "
          <script type='text/javascript'>
            $(function () {
              $('#mdb-lightbox-ui').load('mdb-addons/mdb-lightbox-ui.html');
            });
          </script>
        ";
				} elseif ($args[$array] == 'carousel') {
					echo "
          <script type='text/javascript'>
            $('.carousel.carousel-multi-item.v-2 .carousel-item').each(function(){
              var next = $(this).next();
              if (!next.length) {
                next = $(this).siblings(':first');
              }
              next.children(':first-child').clone().appendTo($(this));

              for (var i=0;i<4;i++) {
                next=next.next();
                if (!next.length) {
                  next=$(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));
              }
            });
          </script>
        ";
				} elseif ($args[$array] == 'bookmark_stuff') {
					echo "
        <script type='text/javascript'>
          $('#add_bookmark').click(function() {
        		$.post('engine.php', {
        			'bookmark_change': true,
        			'bookmark_tema_id': tema_id,
        			'bookmark_user_id': user_id
        		});
            $('#add_bookmark').hide();
            $('#remove_bookmark').show();
        	});
        	$('#remove_bookmark').click(function() {
        		$.post('engine.php', {
        			'bookmark_change': false,
        			'bookmark_tema_id': tema_id,
        			'bookmark_user_id': user_id
        		});
            $('#add_bookmark').show();
            $('#remove_bookmark').hide();
        	});
        </script>
        ";
				} elseif ($args[$array] == 'homepage') {
					echo "
        <script type='text/javascript'>
          $(document).ready(function() {
            $('#searchBar').focus();
            $(document.body).on('click', '.cardmateria' ,function(){
              if ($(this).attr('href')) {
                var link = $(this).attr('href');
                window.open(link, '_self');
                event.preventDefault();
              }
            });
            $('#searchBarGo').click(function() {
              var command = $('#searchBar').val();
              var command = btoa(command);
              var concurso = $('#searchBarGo').val();
              var concurso = btoa(concurso);
              $.post('engine.php', {'sbcommand': command, 'sbconcurso': concurso}, function(data) {
                $('#searchBar').val('');
                if (data != 0) {
                  var pw = data.substring(0, 16);
                  var pw2 = data.substring(16);
                  if (pw == 'notfoundnotfound') {
                    $('#searchBar').val(pw2);
                  }
                  else if (pw == 'foundfoundfoundf') {
                    window.open(pw2, '_self');
                  }
                }
              });
              return false;
            });
          });
        </script>
        ";
				} elseif ($args[$array] == 'sticky_anotacoes') {
					echo "
						<script type='text/javascript'>
							$('#mostrar_coluna_direita').hide();
							$('#esconder_sticky_anotacoes').click(function() {
								$('#coluna_direita').hide();
								$('#mostrar_coluna_direita').show();
							});
							$('#mostrar_coluna_direita').click(function() {
						    $('.sticky_anotacoes_collapse').show();
						    $('#mostrar_sticky_anotacoes').hide();
						    $('#mostrar_coluna_direita').hide();
						    $('#coluna_direita').show();
							})
						</script>
					";
				}
				$array++;
			}
		}
	}
	
	function load_footer()
	{
		include 'templates/footer.php';
	}
	
	function extract_gdoc($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		$position = strpos($output, "<body");
		$body = substr($output, $position);
		$body = str_replace("</html>", "", $body);
		curl_close($ch);
		return $body;
	}
	
	function standard_jumbotron($titulo, $link)
	{
		echo "
    <div class='container-fluid p-0 m-0 text-center'>
      <div class='jumbotron col-12 mb-0 elegant-color text-white'>
		";
		if ($link == false) {
			echo "<h1 class='h1-responsive playfair400 logo-jumbotron d-sm-inline d-md-none'>$titulo</h1>";
			echo "<span class='display-2 playfair400 logo-jumbotron d-none d-md-inline'>$titulo</span>";
		} else {
			echo "<a href='$link'><h1 class='h1-responsive playfair400 logo-jumbotron d-sm-inline d-md-none'>$titulo</h1></a>";
			echo "<a href='$link'><span class='display-2 playfair400 logo-jumbotron d-none d-md-inline'>$titulo</span></a>";
		}
		echo "
      </div>
    </div>
";
	}
	
	function sub_jumbotron($titulo, $link)
	{
		echo "
  <div class='container-fluid py-5 col-lg-12 grey lighten-3 text-center mb-3'>
";
		if ($link == false) {
			echo "<h2 class='h2-responsive' d-sm-inline d-md-none>$titulo</h2>";
			echo "<span class='display-4 d-none d-md-inline'>$titulo</span>";
		} else {
			echo "<h2 class='h2-responsive d-sm-inline d-md-none'><a href='$link'>$titulo</a></h2>";
			echo "<span class='display-4 d-none d-md-inline'><a href='$link'>$titulo</a></span>";
		}
		echo "
  </div>
";
	}
	
	if (isset($_POST['bookmark_change'])) {
		$bookmark_change = $_POST['bookmark_change'];
		$bookmark_tema_id = $_POST['bookmark_tema_id'];
		$bookmark_user_id = $_POST['bookmark_user_id'];
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubique";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT id FROM Bookmarks WHERE user_id = $bookmark_user_id AND tema_id = $bookmark_tema_id");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$bookmark_id = $row['id'];
				$update = $conn->query("UPDATE Bookmarks SET bookmark = $bookmark_change WHERE id = $bookmark_id");
				break;
			}
		} else {
			$insert = $conn->query("INSERT INTO Bookmarks (user_id, tema_id, bookmark) VALUES ($bookmark_user_id, $bookmark_tema_id, $bookmark_change)");
		}
	}
	
	if (isset($_POST['sbcommand'])) {
		$concurso = base64_decode($_POST['sbconcurso']);
		$command = base64_decode($_POST['sbcommand']);
		$command = utf8_encode($command);
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubique";
		$found = false;
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT sigla, tipo FROM Searchbar WHERE concurso = '$concurso' AND chave = '$command' ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$sigla = $row["sigla"];
				$tipo = $row["tipo"];
				if ($tipo == "materia") {
					echo "foundfoundfoundfmateria.php?sigla=$sigla&concurso=$concurso";
					return;
				} elseif ($tipo == "tema") {
					echo "foundfoundfoundfverbete.php?concurso=$concurso&tema=$sigla";
					return;
				}
			}
		}
		$index = 500;
		$winner = 0;
		$result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$chave = $row["chave"];
				$chavelow = mb_strtolower($chave);
				$commandlow = mb_strtolower($command);
				$check = levenshtein($chavelow, $commandlow, 1, 1, 1);
				if (strpos($chavelow, $commandlow) !== false) {
					echo "notfoundnotfound$chave";
					return;
				} elseif ($check < $index) {
					$index = $check;
					$winner = $chave;
				}
			}
			$length = strlen($command);
			if ($index < $length) {
				echo "notfoundnotfound$winner";
				return;
			}
		}
		echo "Nada foi encontrado";
		return;
	}
	
	function quill_reformatar($texto)
	{
		$texto = str_replace("<blockquote>", "<blockquote class='blockquote'>", $texto);
		return $texto;
	}
	
	function generateRandomString()
	{
		$length = func_get_args();
		if (!isset($length[0])) {
			$length[0] = 8;
		}
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length[0]);
	}
	
	function make_thumb()
	{
		$filename = func_get_args();
		$filename = $filename[0];
		$check = substr($filename, -4);
		$check = strtolower($check);
		/* read the source image */
		$original = "../imagens/verbetes/$filename";
		if (($check == ".jpg") || ($check == "jpeg")) {
			$source_image = imagecreatefromjpeg($original);
		} elseif ($check == ".png") {
			$source_image = imagecreatefrompng($original);
		} elseif ($check == ".gif") {
			$source_image = imagecreatefromgif($original);
		} else {
			return false;
		}
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_height = 300;
		$desired_width = floor($desired_height * ($width / $height));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		$prefix = "../imagens/verbetes/thumbnails/";
		$destination = "$prefix$filename";
		if (($check == ".jpg") || ($check == "jpeg")) {
			imagejpeg($virtual_image, "$destination");
		} elseif ($check == ".png") {
			imagepng($virtual_image, "$destination");
		} elseif ($check == ".gif") {
			imagegif($virtual_image, "$destination");
		}
		$x = 'x';
		if ($width > $height) {
			$orientacao = 'paisagem';
		} else {
			$orientacao = 'retrato';
		}
		$resolucao_original = "$width$x$height";
		$dados_da_imagem = array($resolucao_original, $orientacao);
		return $dados_da_imagem;
	}
	
	if (isset($_POST['nova_imagem'])) {
		$nova_imagem_link = $_POST['nova_imagem'];
		$user_id = $_POST['user_id'];
		$tema_id = $_POST['tema_id'];
		$nossa_copia = adicionar_imagem($nova_imagem_link, 'Não há título registrado', 'Não há comentário registrado', $tema_id, $user_id);
		echo $nossa_copia;
	}
	
	function adicionar_thumbnail_youtube($youtube_thumbnail_original)
	{
		$randomfilename = generateRandomString(12);
		$randomfilename .= '.jpg';
		$nova_imagem_arquivo = $randomfilename;
		$nova_imagem_diretorio = "../imagens/youthumb/$nova_imagem_arquivo";
		file_put_contents($nova_imagem_diretorio, fopen($youtube_thumbnail_original, 'r'));
		return $nova_imagem_arquivo;
	}
	
	function adicionar_imagem()
	{
		$args = func_get_args();
		$nova_imagem_link = $args[0];
		$nova_imagem_titulo = $args[1];
		$nova_imagem_comentario = $args[2];
		$tema_id = $args[3];
		$user_id = $args[4];
		$servername = "localhost";
		$username = "grupoubique";
		$password = "ubique patriae memor";
		$dbname = "Ubique";
		$conn = new mysqli($servername, $username, $password, $dbname);
		mysqli_set_charset($conn, "utf8");
		$result = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
		if ($result->num_rows == 0) {
			$randomfilename = generateRandomString(12);
			$ultimo_ponto = strripos($nova_imagem_link, ".");
			$extensao = substr($nova_imagem_link, $ultimo_ponto);
			$nova_imagem_arquivo = "$randomfilename$extensao";
			$nova_imagem_diretorio = "../imagens/verbetes/$randomfilename$extensao";
			file_put_contents($nova_imagem_diretorio, fopen($nova_imagem_link, 'r'));
			$dados_da_imagem = make_thumb($nova_imagem_arquivo);
			if ($dados_da_imagem == false) {
				return false;
			}
			$nova_imagem_resolucao_original = $dados_da_imagem[0];
			$nova_imagem_orientacao = $dados_da_imagem[1];
			$conn->query("INSERT INTO Elementos (tipo, titulo, link, arquivo, resolucao, orientacao, comentario, user_id) VALUES ('imagem', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', '$nova_imagem_comentario', $user_id)");
			$result2 = $conn->query("SELECT id FROM Elementos WHERE link = '$nova_imagem_link'");
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					$nova_imagem_id = $row['id'];
					$conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $nova_imagem_id, 'imagem', $user_id)");
					break;
				}
			}
		} else {
			while ($row = $result->fetch_assoc()) {
				$nova_imagem_id = $row['id'];
				$conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $nova_imagem_id, 'imagem', $user_id)");
				break;
			}
		}
		return "https://www.ubwiki.com.br/imagens/verbetes/$nova_imagem_arquivo";
	}
	
	function get_youtube($url)
	{
		
		$youtube = "http://www.youtube.com/oembed?url=" . $url . "&format=json";
		
		$curl = curl_init($youtube);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($curl);
		curl_close($curl);
		return json_decode($return, true);
		
	}


?>
