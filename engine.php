<?php

$servername = "localhost";
$username = "grupoubique";
$password = "ubique patriae memor";
$dbname = "Ubique";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");

function carregar_navbar() {
  $args = func_get_args();
  $forum = false;
  $count = 0;
  while (isset($args[$count])) {
    $arg = $args[$count];
    if ($arg == 'dark') { $mode = 'dark'; }
    elseif ($arg == 'light') { $mode = 'light'; }
    $count++;
  }
  if ($mode == 'dark') { $mode = 'dark'; $color = 'elegant-color'; }
  elseif ($mode == 'light') { $color = 'bg-white'; }
  echo "<nav class='navbar navbar-expand-lg $color' id='inicio'>";
  if ($mode == 'dark') {
    echo "<a class='navbar-brand playfair text-white' href='index.php'>Ubwiki</a>";
  }
  else {
    echo "<a class='navbar-brand playfair text-dark' href='index.php'>Ubwiki</a>";
  }
  echo "<ul class='nav navbar-nav ml-auto nav-flex-icons'>";
  echo "<li class='nav-item dropdown'>";
  if ($mode == 'dark') {
    echo "<a class='navlink dropdown-toggle waves-effect waves-light text-white' id='user_dropdown' data-toggle='dropdown' href='#'>";
  }
  else {
    echo "<a class='navlink dropdown-toggle waves-effect waves-light text-dark' id='user_dropdown' data-toggle='dropdown' href='#'>";
  }
  echo "
        <i class='fal fa-user-tie fa-2x'></i>
        </a>
        <div class='dropdown-menu dropdown-menu-right z-depth-0'>
          <a class='dropdown-item navlink' href='userpage.php'>Sua página</a>
          <a class='dropdown-item navlink' href='logout.php'>Logout</a>
      </li>
    </ul>
  </nav>";
}

function breadcrumbs() {
  $content = func_get_args();
  if (isset($content[0])) { $breadcrumbs = $content[0]; }
  if (isset($content[1])) { $tema_id = $content[1]; }
  if (isset($content[2])) { $tema_bookmark = $content[2]; }
  echo "
    <div class='container-fluid grey lighten-3'>
      <div class='row'>
        <div class='col-lg-9 col-sm-12'>
          <div class='text-left'>
            <nav>
              <ol class='breadcrumb d-inline-flex transparent mb-0'>
                $breadcrumbs
              </ol>
            </nav>
          </div>
        </div>
        <div class='col-lg-3 col-sm-12'>
          <div class='text-right'>
            <ol class='breadcrumb d-inline-flex transparent mb-0'>
              <li id='verbetes_relacionados' class='breadcrumb-item' title='Verbetes relacionados'><a href='#'><i class='fal fa-chart-network fa-fw'></i></a></li>
              <li id='simulados' class='breadcrumb-item' title='Simulados'><a href='#'><i class='fal fa-check-double fa-fw'></i></a></li>
              <li id='forum' class='breadcrumb-item' title='Fórum'><a href='#'><i class='fal fa-comments-alt fa-fw'></i></a></li>";
              if ($tema_bookmark == false) {
                echo "
                  <li id='add_bookmark' class='breadcrumb-item' title='Marcar para leitura' value='$tema_id'><a href='#'><i class='fal fa-bookmark fa-fw'></i></a></li>
                  <li id='remove_bookmark' class='breadcrumb-item collapse' title='Remover da lista de leitura' value='$tema_id'><a href='#'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></li>
                ";
              }
              else {
                echo "
                  <li id='add_bookmark' class='breadcrumb-item collapse' title='Marcar para leitura' value='$tema_id'><a href='#'><i class='fal fa-bookmark fa-fw'></i></a></li>
                  <li id='remove_bookmark' class='breadcrumb-item' title='Remover da lista de leitura' value='$tema_id'><a href='#'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></li>
                ";
              }
            echo "
            </ol>
          </div>
        </div>
      </div>
    </div>
  ";
}

function top_page() {
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
    <link type="image/vnd.microsoft.icon" rel="icon" href="imagens/favicon.ico"/>
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
        }
        elseif ($args[$array] == "onepage") {
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

function bottom_page() {

  $args = func_get_args();

  echo '
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript" charset="UTF-8" src="engine.js"></script>
  ';

  if ($args != false) {
    $array = 0;
    while (isset($args[$array])) {
      if ($args[$array] == "quill_admin") {
        echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist = ['italic','script','link','blockquote','list','header','strike'];
            var admin_editor = new Quill('#quill_editor_admin', {
              theme: 'snow',
              formats: formatWhitelist,
              modules: {
                toolbar: toolbarOptions
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
            var toolbarOptions = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist = ['italic','script','link','blockquote','list','header','strike'];
            var user_editor = new Quill('#quill_editor_user', {
              theme: 'snow',
              formats: formatWhitelist,
              modules: {
                toolbar: toolbarOptions
              }
            });
            var form_user = document.querySelector('#quill_user_form');
            form_user.onsubmit = function() {
              var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
              quill_nova_mensagem_html.value = user_editor.root.innerHTML;
            }
          </script>
        ";
      }
      elseif ($args[$array] == "quill_v") {
        echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script type='text/javascript'>
            var toolbarOptions = [
              ['italic'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist = ['italic','script','link','blockquote','list','header'];
            var verbete_editor = new Quill('#quill_editor_verbete', {
              readOnly: true
            });
            var anotacao_editor = new Quill('#quill_editor_anotacao', {
              theme: 'snow',
              formats: formatWhitelist,
              modules: {
                toolbar: toolbarOptions
              }
            });
            var form_verbete = document.querySelector('#quill_verbete_form');
            form_verbete.onsubmit = function() {
              var quill_novo_verbete_html = document.querySelector('input[name=quill_novo_verbete_html]');
              quill_novo_verbete_html.value = verbete_editor.root.innerHTML;
            }
            var form_anotacao = document.querySelector('#quill_anotacao_form');
            form_anotacao.onsubmit = function() {
              var quill_nova_anotacao_html = document.querySelector('input[name=quill_nova_anotacao_html]');
              quill_nova_anotacao_html.value = anotacao_editor.root.innerHTML;
            }
          </script>
        ";
      }
      elseif ($args[$array] == "quill_elemento") {
        echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions = [
              ['italic'],
              ['strike'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, 3, false] }],
              ['clean']
            ];
            var formatWhitelist = ['italic','script','link','blockquote','list','header','strike'];
            var elemento_editor = new Quill('#quill_editor_elemento', {
              theme: 'snow',
              formats: formatWhitelist,
              modules: {
                toolbar: toolbarOptions
              }
            });
            var form_elemento = document.querySelector('#quill_elemento_form');
            form_elemento.onsubmit = function() {
              var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
              quill_nova_mensagem_html.value = elemento_editor.root.innerHTML;
            }
          </script>
        ";
      }
      elseif ($args[$array] == "edicao_topicos") {
        echo "
          <script type='text/javascript'>
            $(document).ready(function() {
              $('.novosub').hide();
            });
            $('#novosub1').on('input',function(e){
          	    $('#novosub2').show();
          	});
            $('#novosub2').on('input',function(e){
          	    $('#novosub3').show();
          	});
            $('#novosub3').on('input',function(e){
          	    $('#novosub4').show();
          	});
            $('#novosub4').on('input',function(e){
          	    $('#novosub5').show();
          	});
            $('#novosub5').on('input',function(e){
          	    $('#novosub6').show();
          	});
            $('#novosub6').on('input',function(e){
          	    $('#novosub7').show();
          	});
            $('#novosub7').on('input',function(e){
          	    $('#novosub8').show();
          	});
            $('#novosub8').on('input',function(e){
          	    $('#novosub9').show();
          	});
            $('#novosub9').on('input',function(e){
          	    $('#novosub10').show();
          	});
            $('#novosub10').on('input',function(e){
          	    $('#novosub11').show();
          	});
            $('#novosub11').on('input',function(e){
          	    $('#novosub12').show();
          	});
            $('#novosub12').on('input',function(e){
          	    $('#novosub13').show();
          	});
            $('#novosub13').on('input',function(e){
          	    $('#novosub14').show();
          	});
            $('#novosub14').on('input',function(e){
          	    $('#novosub15').show();
          	});
            $('#novosub15').on('input',function(e){
          	    $('#novosub16').show();
          	});
            $('#novosub16').on('input',function(e){
          	    $('#novosub17').show();
          	});
            $('#novosub17').on('input',function(e){
          	    $('#novosub18').show();
          	});
            $('#novosub18').on('input',function(e){
          	    $('#novosub19').show();
          	});
            $('#novosub19').on('input',function(e){
          	    $('#novosub20').show();
          	});
          </script>
        ";
      }
      elseif ($args[$array] == 'lightbox-imagens') {
        echo "
          <script type='text/javascript'>
            $(function () {
              $('#mdb-lightbox-ui').load('mdb-addons/mdb-lightbox-ui.html');
            });
          </script>
        ";
      }
      elseif ($args[$array] == 'carousel') {
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
      }
      elseif ($args[$array] == 'collapse_stuff') {
        echo "
        <script type='text/javascript'>
          $('#esconder_verbete').click(function(){
            if ( $('#videos').css('display') == 'none' && $('#imagens').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
              $('#coluna_esquerda').css('display', 'none');
              $('#coluna_direita').addClass('col-lg-6');
              $('#coluna_direita').removeClass('col-lg-5');
            }
            if ( $('#coluna_direita').css('display') == 'none' ) {
              $('#coluna_esquerda').addClass('col-lg-6');
              $('#coluna_esquerda').removeClass('col-lg-5');
            }
          });
          $('#esconder_imagens').click(function(){
            if ( $('#verbete').css('display') == 'none' && $('#videos').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
              $('#coluna_esquerda').css('display', 'none');
              $('#coluna_direita').addClass('col-lg-6');
              $('#coluna_direita').removeClass('col-lg-5');
            }
            if ( $('#coluna_direita').css('display') == 'none' ) {
              $('#coluna_esquerda').addClass('col-lg-6');
              $('#coluna_esquerda').removeClass('col-lg-5');
            }
          });
          $('#esconder_videos').click(function(){
            if ( $('#verbete').css('display') == 'none' && $('#imagens').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
              $('#coluna_esquerda').css('display', 'none');
              $('#coluna_direita').addClass('col-lg-6');
              $('#coluna_direita').removeClass('col-lg-5');
            }
            if ( $('#coluna_direita').css('display') == 'none' ) {
              $('#coluna_esquerda').addClass('col-lg-6');
              $('#coluna_esquerda').removeClass('col-lg-5');
            }
          });
          $('#esconder_bibliografia').click(function(){
            if ( $('#verbete').css('display') == 'none' && $('#videos').css('display') == 'none' && $('#imagens').css('display') == 'none' ) {
              $('#coluna_esquerda').css('display', 'none');
              $('#coluna_direita').addClass('col-lg-6');
              $('#coluna_direita').removeClass('col-lg-5');
            }
            if ( $('#coluna_direita').css('display') == 'none' ) {
              $('#coluna_esquerda').addClass('col-lg-6');
              $('#coluna_esquerda').removeClass('col-lg-5');
            }
          });
          $('.mostrar_coluna_esquerda').click(function(){
            $('#coluna_esquerda').css('display', 'inline');
            $('#coluna_direita').addClass('col-lg-5');
            $('#coluna_direita').removeClass('col-lg-6');
          });
          $('#mostrar_anotacoes').click(function(){
            $('#coluna_esquerda').addClass('col-lg-5');
            $('#coluna_esquerda').removeClass('col-lg-6');
            $('#coluna_direita').addClass('col-lg-5');
            $('#coluna_direita').removeClass('col-lg-6');
          });
          $('#minimizar_anotacoes').click(function(){
            $('#coluna_esquerda').addClass('col-lg-6');
            $('#coluna_esquerda').removeClass('col-lg-5');
            $('#coluna_direita').addClass('col-lg-6');
            $('#coluna_direita').removeClass('col-lg-5');
          });
        </script>";
      }
      elseif ($args[$array] == 'bookmark_stuff') {
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
      }
      elseif ($args[$array] == 'sticky_anotacoes') {
        echo "
          <script type='text/javascript'>
            $(document).ready(function() {
              $('#sticky_anotacoes').sticky({
                topSpacing: 50,
                zIndex: 2,
                stopper: '#footer',
              });
            });
          </script>
        ";
      }
      $array++;
    }
  }
  echo "</html>";
}

function load_footer() {
  echo "
    <footer id='footer' class='footer-copyright grey lighten-4 text-center font-small py-2'>
      <span class='text-dark'>A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Siga <a href='termos.php' target='_blank'>este</a> link para rever os termos e condições de uso da página.</span>
    </footer>
  ";
}

function extract_gdoc($url) {
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

function standard_jumbotron($titulo, $link) {
echo "
    <div class='container-fluid p-0 m-0 text-center'>
      <div class='jumbotron col-12 mb-0 elegant-color text-white'>
";
        if ($link == false) {
          echo "<span class='display-1 logo-jumbotron wireone'>$titulo</span>";
        }
        else {
          echo "<a href='$link'><span class='display-3 logo-jumbotron'>$titulo</span></a>";
        }
echo "
      </div>
    </div>
";
}

function sub_jumbotron($titulo, $link) {
echo "
  <div class='container-fluid py-5 col-lg-12 grey lighten-3 text-center mb-3'>
";
    if ($link == false) {
      echo "<span class='display-3 wireone'>$titulo</span>";
    }
    else {
      echo "<span class='display-3 wireone'><a href='$link'>$titulo</a></span>";
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
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT id FROM Bookmarks WHERE user_id = $bookmark_user_id AND tema_id = $bookmark_tema_id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $bookmark_id = $row['id'];
      $update = $conn->query("UPDATE Bookmarks SET bookmark = $bookmark_change WHERE id = $bookmark_id");
      break;
    }
  }
  else {
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
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT sigla, tipo FROM Searchbar WHERE concurso = '$concurso' AND chave = '$command' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sigla = $row["sigla"];
      $tipo = $row["tipo"];
      if ($tipo == "materia") {
        echo "foundfoundfoundfmateria.php?sigla=$sigla&concurso=$concurso";
        return;
      }
      elseif ($tipo == "tema") {
        echo "foundfoundfoundfverbete.php?concurso=$concurso&tema=$sigla";
        return;
      }
    }
  }
  $index = 500;
  $winner = 0;
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND CHAR_LENGTH(chave) < 150 ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $chave = $row["chave"];
      $chavelow = mb_strtolower($chave);
      $commandlow = mb_strtolower($command);
      $check = levenshtein($chavelow, $commandlow, 1, 1, 1);
			if (strpos($chavelow, $commandlow) !== false) {
        echo "notfoundnotfound$chave";
				return;
			}
      elseif ($check < $index) {
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

function quill_reformatar($texto) {
  $texto = str_replace("<blockquote>", "<blockquote class='blockquote'>", $texto);
  return $texto;
}

function generateRandomString() {
	$length = func_get_args();
	if (!isset($length[0])) { $length[0] = 8; }
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length[0]);
}

function make_thumb() {
    $filename = func_get_args();
    $filename = $filename[0];
    $check = substr($filename, -4);
    $check = strtolower($check);
    /* read the source image */
    $original = "imagens/verbetes/$filename";
    if (($check == ".jpg") || ($check == "jpeg")) {
        $source_image = imagecreatefromjpeg($original);
    }
    elseif ($check == ".png") {
        $source_image = imagecreatefrompng($original);
    }
    elseif ($check == ".gif") {
        $source_image = imagecreatefromgif($original);
    }
    else {
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
    $prefix = "imagens/verbetes/thumbnails/";
    $destination = "$prefix$filename";
    if (($check == ".jpg") || ($check == "jpeg")) {
        imagejpeg($virtual_image, "$destination");
    }
    elseif ($check == ".png") {
        imagepng($virtual_image, "$destination");
    }
    elseif ($check == ".gif") {
        imagegif($virtual_image, "$destination");
    }
    $x = 'x';
    if ($width > $height) { $orientacao = 'paisagem'; }
    else { $orientacao = 'retrato'; }
    $resolucao_original = "$width$x$height";
    $dados_da_imagem  = array($resolucao_original,$orientacao);
    return $dados_da_imagem;
}

?>
