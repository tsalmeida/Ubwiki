<?php

$servername = "localhost";
$username = "grupoubique";
$password = "ubique patriae memor";
$dbname = "Ubique";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");
$dbname = "Ubwiki_usuarios";
$conn2 = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn2,"utf8");

function carregar_navbar() {
  echo "<nav class='navbar navbar-expand-lg'>
    <a class='navbar-brand playfair' href='index.php'>Ubwiki</a>
    <ul class='nav navbar-nav ml-auto nav-flex-icons'>
      <li class='nav-item dropdown'>
        <a class='navlink dropdown-toggle waves-effect waves-light' id='user_dropdown' data-toggle='dropdown' href='#'>
          <i class='fas fa-user-tie fa-2x'></i>
        </a>
        <div class='dropdown-menu dropdown-menu-right dropdown-default'>
          <a class='dropdown-item navlink' href='userpage.php'>Sua página</a>
          <a class='dropdown-item' href='logout.php'>Logout</a>
      </li>
    </ul>
  </nav>";
}

function breadcrumbs($content) {
  echo "
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-lg-12 px-2'>
          <div class='mr-auto'>
            <nav>
              <ol class='breadcrumb d-inline-flex text-dark bg-white mb-0'>
                $content
              </ol>
            </nav>
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
    <link type="image/vnd.microsoft.icon" rel="icon" href="imagens/favicon.ico"/>
    <title>Ubwiki</title>';

    if ($args != false) {
      $array = 0;
      while (isset($args[$array])) {
        if ($args[$array] == "quill_v") {
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
  echo '</head>';
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
      if ($args[$array] == "quill_v") {
        echo "
          <script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
          <script>
            var toolbarOptions = [
              ['italic'],
              ['blockquote'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              [{ 'script': 'super' }],
              [{ 'header': [2, false] }],
              ['clean']
            ];
            var formatWhitelist = ['italic','script','link','blockquote','list','header'];
            var verbete_editor = new Quill('#quill_editor_verbete', {
              theme: 'snow',
              formats: formatWhitelist,
              modules: {
                toolbar: toolbarOptions
              }
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
      if ($args[$array] == "edicao_temas") {
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
      $array++;
    }
  }

  echo "</html>";

}

function load_footer() {
  echo "
    <footer class='footer-copyright bg-lighter text-dark text-center font-small mt-2'>
      <p class='mb-0'>A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Siga <a href='termos.php' target='_blank'>este</a> link para rever os termos e condições de uso da página.</p>
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
          echo "<span class='display-3 logo-jumbotron'>$titulo</span>";
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
  <div class='container-fluid py-5 col-lg-12 grey lighten-1 text-center mb-3'>
";
    if ($link == false) {
      echo "<h1>$titulo</h1>";
    }
    else {
      echo "<a href='$link'><h1>$titulo</h1></a>";
    }
echo "
  </div>
";
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

?>
