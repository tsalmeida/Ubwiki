<?php
  include 'engine.php';
  top_page();

if (isset($_GET['tema'])) {
  $id_tema = $_GET['tema'];
}

if (isset($_GET['concurso'])) {
  $concurso = $_GET['concurso'];
}

$result = $conn->query("SELECT sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND id = $id_tema");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $sigla_materia = $row['sigla_materia'];
    $nivel = $row['nivel'];
    $ordem = $row['ordem'];
    $nivel1 = $row['nivel1'];
    $nivel2 = $row['nivel2'];
    $nivel3 = $row['nivel3'];
    $nivel4 = $row['nivel4'];
    $nivel5 = $row['nivel5'];
    if ($nivel == 5) { $tema = $nivel5; $parent = $nivel4; }
    elseif ($nivel == 4) { $tema = $nivel4; $parent = $nivel3; }
    elseif ($nivel == 3) { $tema = $nivel3; $parent = $nivel2; }
    elseif ($nivel == 2) { $tema = $nivel2; $parent = $nivel1; }
    else { $tema = $nivel1; $parent = false; }
  }
}

$result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = $id_tema");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $verbete_consolidado = $row['verbete'];
  }
}
else {
  $verbete_consolidado = false;
}

if (isset($_POST['verbete_texto'])) {
  $novo_verbete = $_POST['verbete_texto'];
  $novo_verbete = strip_tags($novo_verbete);
  $result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = $id_tema");
  if ($result->num_rows > 0) {
    $result = $conn->query("UPDATE Verbetes SET verbete = '$novo_verbete' WHERE concurso = '$concurso' AND id_tema = $id_tema");
    $result = $conn->query("INSERT INTO Verbetes_arquivo (id_tema, concurso, verbete) VALUES ('$id_tema', '$concurso', '$verbete_consolidado')");
  }
  else {
    $result = $conn->query("INSERT INTO Verbetes (id_tema, concurso, verbete) VALUES ('$id_tema', '$concurso', '$novo_verbete')");
  }
  $verbete_consolidado = $novo_verbete;
}

if (isset($_POST['nova_imagem_link'])) {
  $nova_imagem_link = $_POST['nova_imagem_link'];
  $nova_imagem_titulo = $_POST['nova_imagem_titulo'];
  $nova_imagem_trecho = $_POST['nova_imagem_trecho'];
  $nova_imagem_comentario = $_POST['nova_imagem_comentario'];
  $result = $conn->query("SELECT id FROM Imagens WHERE concurso = '$concurso' AND id_tema = $id_tema AND link = '$nova_imagem_link'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Imagens (id_tema, concurso, titulo, link, comentario, trecho) VALUES ($id_tema, '$concurso', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_comentario', '$nova_imagem_trecho')");
  }
}

if (isset($_POST['novo_link_link'])) {
  $novo_link_link = $_POST['novo_link_link'];
  $novo_link_titulo = $_POST['novo_link_titulo'];
  $novo_link_comentario = $_POST['novo_link_comentario'];
  $result = $conn->query("SELECT id FROM Links WHERE concurso = '$concurso' AND id_tema = $id_tema AND link = '$novo_link_link'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Links (id_tema, concurso, titulo, link, comentario) VALUES ($id_tema, '$concurso', '$novo_link_titulo', '$novo_link_link', '$novo_link_comentario')");
  }
}

if (isset($_POST['nova_referencia_titulo'])) {
  $nova_referencia_titulo = $_POST['nova_referencia_titulo'];
  $nova_referencia_autor = $_POST['nova_referencia_autor'];
  $nova_referencia_capitulo = $_POST['nova_referencia_capitulo'];
  $result = $conn->query("SELECT id FROM Bibliografia WHERE concurso = '$concurso' AND id_tema = $id_tema AND titulo = '$nova_referencia_titulo'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Bibliografia (id_tema, concurso, titulo, autor, ano) VALUES ($id_tema, '$concurso', '$nova_referencia_titulo', '$nova_referencia_autor', '$nova_referencia_capitulo')");
  }
}

if (isset($_POST['novo_video_titulo'])) {
  $novo_video_titulo = $_POST['novo_video_titulo'];
  $novo_video_autor = $_POST['novo_video_autor'];
  $novo_video_link = $_POST['novo_video_link'];
  $result = $conn->query("SELECT id FROM Videos WHERE concurso = '$concurso' AND id_tema = $id_tema AND titulo = '$novo_video_titulo'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Videos (id_tema, concurso, titulo, autor, link) VALUES ($id_tema, '$concurso', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link')");
  }
}

function ler_relacionados($id_tema, $concurso) {
  return false;
}

?>
<body>
<?php
  $navbar = "
  <div class='mr-auto'>
    <nav>
      <ol class='breadcrumb d-inline-flex pl-0 pt-0 text-dark'>
        <li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li>
        <li class='breadcrumb-item text-muted2'><a href='index.php>$concurso</a></li>
        <li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li>
        <li class='breadcrumb-item text-muted2'><a href='materia.php?concurso=$concurso&sigla=$sigla_materia'>$sigla_materia</a></li>
        <li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li>
        <li class='breadcrumb-item text-muted2'>$nivel1</li>
        ";
  if ($nivel2 != false) { $navbar .= "<li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li><li class='breadcrumb-item text-muted2'>$nivel2</li>"; }
  if ($nivel3 != false) { $navbar .= "<li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li><li class='breadcrumb-item text-muted2'>$nivel3</li>"; }
  if ($nivel4 != false) { $navbar .= "<li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li><li class='breadcrumb-item text-muted2'>$nivel4</li>"; }
  if ($nivel5 != false) { $navbar .= "<li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li><li class='breadcrumb-item text-muted2'>$nivel5</li>"; }
  $navbar .= "</ol></nav></div>";
  carregar_navbar($navbar);
  standard_jumbotron($tema, false);
?>
  <div class='container-fluid py-3 col-12 bg-lighter text-center'>
    <ul class='list-group list-group-horizontal-lg'>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#verbete'>Verbete consolidado</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#imagens'>Imagens de apoio</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#verbetes'>Verbetes relacionados</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#bibliografia'>Bibliografia pertinente</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#videos'>Vídeos e aulas relacionados</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#links'>Links externos</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#anotacoes'>Minhas anotações</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#questoes'>Questões de provas passadas</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark border-0' href='#discussao'>Discussão</a>
    </ul>
  </div>
  <div class='container my-5' id='verbete'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Verbete consolidado</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
        <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_editar_verbete'><i class='fal fa-plus-square'></i></a></span>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
          if ($verbete_consolidado == false) {
            echo "<p>Não há, no momento, verbete consolidado para este tema.</p>";
          }
          else {
            $separator = "\r\n";
            $line = strtok($verbete_consolidado, $separator);
            while ($line !== false) {
                echo "<p>$line</p>";
                $line = strtok( $separator );
            }
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='imagens'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Imagens de apoio</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
          <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_imagens_form'><i class='fal fa-plus-square'></i></a></span>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
        $result = $conn->query("SELECT titulo, link, comentario, trecho FROM Imagens WHERE id_tema = $id_tema AND concurso = '$concurso'");
        if ($result->num_rows > 0) {
          echo "<ul class='list-group'>";
            while($row = $result->fetch_assoc()) {
              $imagem_titulo = $row['titulo'];
              $imagem_link = $row['link'];
              $imagem_comentario = $row['comentario'];
              $imagem_trecho = $row['trecho'];
              echo "<li class='list-group-item list-group-item-action'><a href='$imagem_link' target='_blank'>$imagem_titulo : $imagem_comentario : $imagem_trecho</a></li>";
            }
          echo "</ul>";
        }
        else {
          echo "<p>Não foram acrescentadas, até o momento, imagens de apoio a este verbete.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='verbetes'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Verbetes relacionados</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
          ler_relacionados($id_tema, $concurso);
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='bibliografia'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Bibliografia pertinente</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
          <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_referencia_form'><i class='fal fa-plus-square'></i></a></span>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
        $result = $conn->query("SELECT titulo, autor, capitulo FROM Bibliografia WHERE id_tema = $id_tema AND concurso = '$concurso'");
        if ($result->num_rows > 0) {
          echo "<ul class='list-group'>";
            while($row = $result->fetch_assoc()) {
              $referencia_titulo = $row['titulo'];
              $referencia_autor = $row['autor'];
              $referencia_capitulo = $row['capitulo'];
              echo "<li class='list-group-item'>$referencia_titulo : $referencia_autor : $referencia_capitulo</li>";
            }
          echo "</ul>";
        }
        else {
          echo "<p>Não foram identificados, até o momento, recursos bibliográficos sobre este tema.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='videos'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Vídeos e aulas</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
          <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_videos_form'><i class='fal fa-plus-square'></i></a></span>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
        $result = $conn->query("SELECT titulo, autor, link FROM Videos WHERE id_tema = $id_tema AND concurso = '$concurso'");
        if ($result->num_rows > 0) {
          echo "<ul class='list-group'>";
            while($row = $result->fetch_assoc()) {
              $video_titulo = $row['titulo'];
              $video_autor = $row['autor'];
              $video_link = $row['link'];
              echo "<li class='list-group-item list-group-item-action'><a href='$video_link' target='_blank'>$video_titulo : $video_autor</a></li>";
            }
          echo "</ul>";
        }
        else {
          echo "<p>Ainda não foram acrescentados vídeos ou aulas sobre este assunto.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='links'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Links externos</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
          <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_links_form'><i class='fal fa-plus-square'></i></a></span>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
        $result = $conn->query("SELECT titulo, comentario, link FROM Links WHERE id_tema = $id_tema AND concurso = '$concurso'");
        if ($result->num_rows > 0) {
          echo "<ul class='list-group'>";
            while($row = $result->fetch_assoc()) {
              $link_titulo = $row['titulo'];
              $link_link = $row['link'];
              $link_comentario = $row['comentario'];
              echo "<li class='list-group-item list-group-item-action'><a href='$link_link' target='_blank'>$link_titulo : $link_comentario</a></li>";
            }
          echo "</ul>";
        }
        else {
          echo "<p>Ainda não foram acrescentados links externos sobre este tema.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='anotacoes'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Suas anotações</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'>
      </div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
          if ($anotacoes == false) {
            echo "<p>Você ainda não fez anotações sobre este tema.</p>";
          }
          else {
            echo $anotacoes;
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='questoes'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Questões de provas passadas</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'></div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
          if ($questoes == false) {
            echo "<p>Não há registro de questões em provas passadas sobre este tema.</p>";
          }
          else {
            echo $questoes;
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container my-5' id='discussao'>
    <div class='row justify-content-between h3 my-5'>
      <div class='col-lg-4 col-sm-8 text-center justify-content-center align-middle'>
        <span class='align-middle'>Debate</span>
      </div>
      <div class='col-lg-1 col-sm-4 text-center justify-content-center align-middle'></div>
    </div>
    <div class='row justify-content-center border-bottom border-dark py-5'>
      <div class='col-lg-6 col-sm-12 text-left font-weight-normal'>
        <?php
          if ($discussao == false) {
            echo "<p>Não há debate sobre este tema. Deixe aqui sua opinião!</p>";
          }
          else {
            echo $discussao;
          }
        ?>
      </div>
    </div>
  </div>

<div class='modal fade' id='modal_imagens_form' role='dialog' tabindex='-1'>
  <div class='modal-dialog modal-lg' role='document'>
    <div class='modal-content'>
      <form method='post'>
        <div class='modal-header text-center'>
          <h4 class='modal-title w-100 font-weight-bold'>Adicionar imagem</h4>
          <button type='button' class='close' data-dismiss='modal'>
            <i class="fal fa-times-circle"></i>
          </button>
        </div>
        <div class='modal-body mx-3'>
          <div class='md-form mb-2'>
            <input type='url' id='nova_imagem_link' name='nova_imagem_link' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_imagem_link'>Link para a imagem</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='nova_imagem_titulo' name='nova_imagem_titulo' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_imagem_titulo'>Titulo da imagem</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='nova_imagem_trecho' name='nova_imagem_trecho' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='nova_imagem_trecho'>Trecho do verbete a vincular</label>
          </div>
          <div class='md-form'>
            <textarea type='text' id='nova_imagem_comentario' name='nova_imagem_comentario' class='md-textarea form-control' rows='4' required></textarea>
            <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='nova_imagem_comentario'>Breve comentário sobre a imagem, destacando sua relevância para a compreensão do tema.</label>
          </div>
        </div>
        <div class='modal-footer d-flex justify-content-center'>
          <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
          <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class='modal fade' id='modal_referencia_form' role='dialog' tabindex='-1'>
  <div class='modal-dialog modal-lg' role='document'>
    <div class='modal-content'>
      <form method='post'>
        <div class='modal-header text-center'>
          <h4 class='modal-title w-100 font-weight-bold'>Adicionar referência bibliográfica</h4>
          <button type='button' class='close' data-dismiss='modal'>
            <i class="fal fa-times-circle"></i>
          </button>
        </div>
        <div class='modal-body mx-3'>
          <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_titulo' name='nova_referencia_titulo' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_referencia_titulo'>Título da obra</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_autor' name='nova_referencia_autor' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_referencia_autor'>Nome do autor</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_capitulo' name='nova_referencia_capitulo' class='form-control validate'>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_referencia_capitulo'>Capítulo (opcional)</label>
          </div>
        </div>
        <div class='modal-footer d-flex justify-content-center'>
          <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
          <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class='modal fade' id='modal_links_form' role='dialog' tabindex='-1'>
  <div class='modal-dialog modal-lg' role='document'>
    <div class='modal-content'>
      <form method='post'>
        <div class='modal-header text-center'>
          <h4 class='modal-title w-100 font-weight-bold'>Adicionar link</h4>
          <button type='button' class='close' data-dismiss='modal'>
            <i class="fal fa-times-circle"></i>
          </button>
        </div>
        <div class='modal-body mx-3'>
          <div class='md-form mb-2'>
            <input type='url' id='novo_link_link' name='novo_link_link' class='form-control validate'>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_link_link' required>Link para a página</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='novo_link_titulo' name='novo_link_titulo' class='form-control validate'>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_link_titulo' required>Título da página</label>
          </div>
          <div class='md-form'>
            <textarea type='text' id='novo_link_comentario' name='novo_link_comentario' class='md-textarea form-control' rows='4' required></textarea>
            <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='novo_link_comentario'>Breve comentário sobre o link, destacando sua relevância para a compreensão do tema.</label>
          </div>
        </div>
        <div class='modal-footer d-flex justify-content-center'>
          <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
          <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class='modal fade' id='modal_videos_form' role='dialog' tabindex='-1'>
  <div class='modal-dialog modal-lg' role='document'>
    <div class='modal-content'>
      <form method='post'>
        <div class='modal-header text-center'>
          <h4 class='modal-title w-100 font-weight-bold'>Adicionar referência bibliográfica</h4>
          <button type='button' class='close' data-dismiss='modal'>
            <i class="fal fa-times-circle"></i>
          </button>
        </div>
        <div class='modal-body mx-3'>
          <div class='md-form mb-2'>
            <input type='text' id='novo_video_titulo' name='novo_video_titulo' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_video_titulo'>Título do vídeo</label>
          </div>
          <div class='md-form mb-2'>
            <input type='text' id='novo_video_autor' name='novo_video_autor' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_video_autor'>Nome do autor</label>
          </div>
          <div class='md-form mb-2'>
            <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='novo_video_link'>Link para o vídeo</label>
          </div>
        </div>
        <div class='modal-footer d-flex justify-content-center'>
          <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
          <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class='modal fade' id='modal_editar_verbete' role='dialog' tabindex='-1'>
  <div class='modal-dialog modal-lg' role='document'>
    <div class='modal-content'>
      <form method='post'>
        <div class='modal-header text-center'>
          <h4 class='modal-title w-100 font-weight-bold'>Editar verbete</h4>
          <button type='button' class='close' data-dismiss='modal'>
            <i class="fal fa-times-circle"></i>
          </button>
        </div>
        <div class='modal-body mx-3'>

          <div class='row justify-content-center'>
            <div class='container col-12 justify-content-center'>
              <?php
                echo "<textarea name='verbete_texto' class='rounded textarea_verbete px-4 py-5'>$verbete_consolidado</textarea>";
              ?>
            </div>
          </div>

        </div>
        <div class='modal-footer d-flex justify-content-center'>
          <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
          <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
<?php
  load_footer();
  bottom_page();
  $conn->close();
?>
