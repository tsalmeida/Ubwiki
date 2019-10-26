<?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    $user = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  top_page("quill_v", "lightbox");
  $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $usuario_id = $row['id'];
    }
  }
  $tabela_usuario = "usuario_id_";
  $tabela_usuario .= $usuario_id;
  $tabela_usuario_arquivo = $tabela_usuario;
  $tabela_usuario_arquivo .= "_arquivo";

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

$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $materia = $row["materia"];
  }
}

$result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = $id_tema");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $verbete_html = $row['verbete'];
  }
}
else {
  $verbete_html = false;
}

if (isset($_POST['quill_novo_verbete_html'])) {
  $novo_verbete_html = $_POST['quill_novo_verbete_html'];
  $novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><blockquote><em><sup>');
  $result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = $id_tema");
  if ($result->num_rows > 0) {
    $result = $conn->query("UPDATE Verbetes SET verbete = '$novo_verbete_html', usuario = '$user' WHERE concurso = '$concurso' AND id_tema = $id_tema");
    $result = $conn->query("INSERT INTO Verbetes_arquivo (id_tema, concurso, verbete, usuario) VALUES ('$id_tema', '$concurso', '$verbete_html', '$user')");
  }
  else {
    $result = $conn->query("INSERT INTO Verbetes (id_tema, concurso, verbete, usuario) VALUES ('$id_tema', '$concurso', '$novo_verbete_html', '$user')");
  }
  $verbete_html = $novo_verbete_html;
}

$result = $conn2->query("SELECT conteudo_texto FROM $tabela_usuario WHERE tipo = 'anotacoes' AND tipo2 = $id_tema");
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $anotacao_html = $row['conteudo_texto'];
  }
}
else {
  $anotacao_html = false;
}

if (isset($_POST['quill_nova_anotacao_html'])) {
  $nova_anotacao_html = $_POST['quill_nova_anotacao_html'];
  $nova_anotacao_html = strip_tags($nova_anotacao_html, '<p><li><ul><ol><h2><blockquote><em><sup>');
  $result = $conn2->query("SELECT conteudo_texto FROM $tabela_usuario WHERE tipo = 'anotacoes' AND tipo2 = $id_tema ");
  if ($anotacao_html != false) {
    $result = $conn2->query("UPDATE $tabela_usuario SET conteudo_texto = '$nova_anotacao_html', WHERE tipo = 'anotacoes' AND tipo2 = $id_tema");
    $result = $conn2->query("INSERT INTO $tabela_usuario_arquivo (tipo, tipo2, conteudo_texto) VALUES ('anotacoes', $id_tema, '$nova_anotacao_html')");
  }
  else {
    $result = $conn2->query("INSERT INTO $tabela_usuario (tipo, tipo2, conteudo_texto) VALUES ('anotacoes', $id_tema, '$nova_anotacao_html')");
  }
  $anotacao_html = $nova_anotacao_html;
}

if (isset($_POST['nova_imagem_link'])) {
  $nova_imagem_link = $_POST['nova_imagem_link'];
  $result = $conn->query("SELECT id FROM Imagens WHERE concurso = '$concurso' AND id_tema = $id_tema AND link = '$nova_imagem_link'");
  if ($result->num_rows == 0) {
    $nova_imagem_titulo = $_POST['nova_imagem_titulo'];
    $nova_imagem_trecho = $_POST['nova_imagem_trecho'];
    $nova_imagem_comentario = $_POST['nova_imagem_comentario'];
    $randomfilename = generateRandomString(12);
    $ultimo_ponto = strripos($nova_imagem_link, ".");
    $extensao = substr($nova_imagem_link, $ultimo_ponto);
    $nova_imagem_arquivo = "$randomfilename$extensao";
    $nova_imagem_diretorio = "imagens/verbetes/$randomfilename$extensao";
    file_put_contents("$nova_imagem_diretorio", fopen($nova_imagem_link, 'r'));
    $dados_da_imagem = make_thumb($nova_imagem_arquivo);
    $nova_imagem_resolucao_original = $dados_da_imagem[0];
    $nova_imagem_orientacao = $dados_da_imagem[1];
    $result = $conn->query("INSERT INTO Imagens (id_tema, concurso, titulo, link, arquivo, resolucao, orientacao, comentario, trecho, usuario) VALUES ($id_tema, '$concurso', '$nova_imagem_titulo', '$nova_imagem_link', '$nova_imagem_arquivo', '$nova_imagem_resolucao_original', '$nova_imagem_orientacao', '$nova_imagem_comentario', '$nova_imagem_trecho', '$user')");
  }
}

if (isset($_POST['nova_referencia_titulo'])) {
  $nova_referencia_titulo = $_POST['nova_referencia_titulo'];
  $nova_referencia_autor = $_POST['nova_referencia_autor'];
  $nova_referencia_capitulo = $_POST['nova_referencia_capitulo'];
  $result = $conn->query("SELECT id FROM Bibliografia WHERE concurso = '$concurso' AND id_tema = $id_tema AND titulo = '$nova_referencia_titulo'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Bibliografia (id_tema, concurso, titulo, autor, ano, usuario) VALUES ($id_tema, '$concurso', '$nova_referencia_titulo', '$nova_referencia_autor', '$nova_referencia_capitulo', '$user')");
  }
}

if (isset($_POST['novo_video_titulo'])) {
  $novo_video_titulo = $_POST['novo_video_titulo'];
  $novo_video_autor = $_POST['novo_video_autor'];
  $novo_video_link = $_POST['novo_video_link'];
  $result = $conn->query("SELECT id FROM Videos WHERE concurso = '$concurso' AND id_tema = $id_tema AND titulo = '$novo_video_titulo'");
  if ($result->num_rows == 0) {
    $result = $conn->query("INSERT INTO Videos (id_tema, concurso, titulo, autor, link, usuario) VALUES ($id_tema, '$concurso', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$user')");
  }
}

?>
<body>
  <?php
    carregar_navbar('dark');
    $breadcrumbs = "
      <li class='breadcrumb-item'><a href='index.php'>$concurso</a></li>
      <li class='breadcrumb-item'><a href='materia.php?concurso=$concurso&sigla=$sigla_materia'>$materia</a></li>
      <li class='breadcrumb-item'>$nivel1</li>
    ";
    if ($nivel2 != false) { $breadcrumbs .= "<li class='breadcrumb-item'>$nivel2</li>"; }
    if ($nivel3 != false) { $breadcrumbs .= "<li class='breadcrumb-item'>$nivel3</li>"; }
    if ($nivel4 != false) { $breadcrumbs .= "<li class='breadcrumb-item'>$nivel4</li>"; }
    if ($nivel5 != false) { $breadcrumbs .= "<li class='breadcrumb-item'>$nivel5</li>"; }
    breadcrumbs($breadcrumbs);
  ?>
  <div class='container-fluid grey lighten-5' data-toggle='buttons'>
    <div class='row d-flex justify-content-between'>
      <div class='col-10 d-flex justify-content-left'>
        <div class='btn-group btn-group-toggle'>
          <label class='btn btn-primary btn-sm active' data-toggle='collapse' data-target='#verbete' href='#inicio'><input type='checkbox' autocomplete='off' checked>Verbete</label>
          <label class='btn btn-primary btn-sm' data-toggle='collapse' data-target='#imagens' href='#imagens'><input type='checkbox' autocomplete='off'>Imagens</label>
          <label class='btn btn-primary btn-sm active' data-toggle='collapse' data-target='#videos' href='#videos'><input type='checkbox' autocomplete='off' checked>Vídeos</label>
          <label class='btn btn-primary btn-sm' data-toggle='collapse' data-target='#bibliografia' href='#bibliografia'><input type='checkbox' autocomplete='off'>Leitura complementar</label>
        </div>
      </div>
      <div class='col-2 d-flex'>
        <div class='row d-flex justify-content-right'>
          <div class='btn-group btn-group-toggle'>
            <label class='btn btn-primary btn-sm inactive collapse anotacoes_collapsible' data-toggle='collapse' data-target='.anotacoes_collapsible'><input type='checkbox'>Anotações</label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class='container-fluid'>
    <div class='row justify-content-around my-5'>
      <div class='col-lg-5 col-sm-12'>
        <div id='verbete' class='collapse show mb-5'>
          <div class='row text-left'>
            <div class='col-10 text-left'>
              <?php
                echo "<h1>$tema</h1>";
              ?>
            </div>
            <div class='col-2 h3 text-right'>
              <a data-toggle='modal' data-target='#modal_editar_verbete' href=''><i class="fal fa-pen-square fa-fw"></i></a>
            </div>
          </div>
          <div class='row justify-content-left border-bottom border-dark py-3'>
            <div class='col-12 text-left font-weight-normal'>
              <?php
                if ($verbete_html == false) {
                  echo "<p>O verbete deste tópico ainda não começou a ser escrito.</p>";
                }
                else {
                  $verbete_reformatado = quill_reformatar($verbete_html);
                  echo $verbete_reformatado;
                }
              ?>
            </div>
          </div>
        </div>
        <div id='imagens' class='collapse mb-5'>
          <div class='row justify-content-between h3'>
            <div class='col-10 text-left justify-content-center align-middle'>
              <h2 class='align-left'>Imagens</h2>
            </div>
            <div class='col-2 text-right justify-content-center align-middle'>
                <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_imagens_form' href=''><i class='fal fa-plus-square fa-fw'></i></a></span>
            </div>
          </div>
          <div class='row justify-content-center border-bottom border-dark py-5'>
            <div class='col-12 text-left font-weight-normal'>
              <?php
              $result = $conn->query("SELECT titulo, link, arquivo, resolucao, orientacao, comentario FROM Imagens WHERE id_tema = $id_tema AND concurso = '$concurso'");
              if ($result->num_rows > 0) {
                echo "
                <div class='row'>
                  <div class='col-12'>
                    <div id='imagens_carrossel' class='carousel slide carousel-multi-item v-2' data-ride='carousel'>
                      <div class='controls-top'>
                        <a class='btn-floating btn-light btn-sm text-dark z-depth-0' href='#imagens_carrossel' data-slide='prev'><i class='fal fa-chevron-left fa-fw'></i></a>
                        <a class='btn-floating btn-light btn-sm text-dark z-depth-0' href='#imagens_carrossel' data-slide='next'><i class='fal fa-chevron-right fa-fw'></i></a>
                      </div>
                      <div class='carousel-inner v-2 mdb-lightbox' role='listbox'>
                        <div id='lightbox-imagens'></div>
                          <div class='mdb-lightbox'>
                ";
                            $active = 'active';
                            while($row = $result->fetch_assoc()) {
                              $imagem_titulo = $row['titulo'];
                              $imagem_link = $row['link'];
                              $imagem_comentario = $row['comentario'];
                              $imagem_arquivo = $row['arquivo'];
                              $imagem_resolucao = $row['resolucao'];
                              $imagem_orientacao = $row['orientacao'];
                              if ($imagem_orientacao == 'retrato') { $col = 6; }
                              else { $col = 12; }
                              echo "
                                <div class='carousel-item $active'>
                                  <div class='col-$col'>
                                    <div class='card mb-2 z-depth-0 border'>
                                      <figure>
                                        <a href='imagens/verbetes/$imagem_arquivo' data-size='$imagem_resolucao'>
                                          <img class='card-img-top cardlimit img-fluid' src='imagens/verbetes/thumbnails/$imagem_arquivo'></img>
                                        </a>
                                      </figure>
                                      <div class='card-body'>
                                        <p>$imagem_titulo</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              ";
                              $active = false;
                            }
                            echo "
                        </div>
                      </div>
                    </div>
                  </div>
                </div>";
                }
                else {
                  echo "<p>Não foram acrescentadas, até o momento, imagens a este verbete.</p>";
                }
              ?>
            </div>
          </div>
        </div>
        <div id='videos' class='collapse show mb-5'>
          <div class='row justify-content-between h3'>
            <div class='col-10 text-left justify-content-center align-middle'>
              <h2 class='align-left'>Vídeos e aulas</h2>
            </div>
            <div class='col-2 text-right justify-content-center align-middle'>
                <span class='text-center justify-content-center align-middle'><a data-toggle='modal' data-target='#modal_videos_form' href=''><i class='fal fa-plus-square fa-fw'></i></a></span>
            </div>
          </div>
          <div class='row justify-content-center border-bottom border-dark py-5'>
            <div class='col-12 text-left font-weight-normal'>
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
        <div id='bibliografia' class='collapse mb-5'>
          <div class='row justify-content-between h3'>
            <div class='col-10 text-left justify-content-center align-middle'>
              <h2 class='align-left'>Leitura complementar</h2>
            </div>
          </div>
          <div class='row justify-content-center border-bottom border-dark py-5'>
            <div class='col-12 text-left font-weight-normal'>
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
              <div class='row'>
                <div class='col-12 text-center h3'>
                  <a data-toggle='modal' data-target='#modal_referencia_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id='anotacoes' class='col-lg-5 col-sm-12 collapse show anotacoes_collapsible'>
        <div class='row'>
          <div class='col-12'>
            <div class='row justify-content-between h3'>
              <div class='col-10 text-left justify-content-center align-middle'>
                <h2 class='align-left'>Anotações</h2>
              </div>
              <div class='col-2 text-right justify-content-center align-middle'>
                <span class='text-center justify-content-center align-middle' data-toggle='collapse' data-target='.anotacoes_collapsible'><a href='#inicio'><i class='fal fa-times-square fa-fw'></i></a></span>
              </div>
            </div>
            <div class='row justify-content-center border-bottom border-dark py-5'>
              <div class='col-12 text-left font-weight-normal'>
                <form id='quill_anotacao_form' method='post' action='#anotacoes'>
                  <input name='quill_nova_anotacao_html' type='hidden'>
                  <div class='row justify-content-center'>
                    <div class='container col-12 justify-content-center'>
                      <?php
                        echo "
                          <div id='quill_container_anotacao'>
                            <div id='quill_editor_anotacao'>
                              $anotacao_html
                            </div>
                          </div>
                        ";
                      ?>
                    </div>
                  </div>
                  <div class='row d-flex justify-content-center mt-3'>
                    <button type='button' class='btn btn-light'><i class="fal fa-times-circle fa-fw"></i> Cancelar</button>
                    <button type='submit' class='btn btn-primary'><i class='fal fa-check fa-fw'></i> Salvar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class='modal fade' id='modal_imagens_form' role='dialog' tabindex='-1'>
      <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
          <form method='post' action='#imagens'>
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
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_imagem_titulo'>Título da imagem</label>
              </div>
              <div class='md-form mb-2'>
                <input type='text' id='nova_imagem_trecho' name='nova_imagem_trecho' class='form-control validate' required>
                <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='nova_imagem_trecho'>Trecho do verbete a vincular</label>
              </div>
              <div class='md-form'>
                <textarea type='text' id='nova_imagem_comentario' name='nova_imagem_comentario' class='md-textarea form-control' rows='4' required></textarea>
                <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='nova_imagem_comentario'>Breve comentário sobre a imagem, destacando sua relevância para a compreensão do tópico.</label>
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
          <form method='post' action='#bibliografia'>
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
    <div class='modal fade' id='modal_videos_form' role='dialog' tabindex='-1'>
      <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
          <form method='post' action='#videos'>
            <div class='modal-header text-center'>
              <h4 class='modal-title w-100 font-weight-bold'>Adicionar vídeo ou aula</h4>
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
      <div class='modal-dialog modal-lg quill_modal' role='document'>
        <div class='modal-content'>
          <form id='quill_verbete_form' method='post' action='#inicio'>
            <input name='quill_novo_verbete_html' type='hidden'>
            <div class='modal-header text-center'>
              <h4 class='modal-title w-100 font-weight-bold'>Editar verbete</h4>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="fal fa-times-circle"></i>
              </button>
            </div>
            <div class='modal-body'>
              <div class='row justify-content-center'>
                <div class='container col-12 justify-content-center'>
                  <?php
                    echo "
                      <div id='quill_container_verbete' class='quill_container_modal'>
                        <div id='quill_editor_verbete'>
                          $verbete_html
                        </div>
                      </div>
                    ";
                  ?>
                </div>
              </div>
            </div>
            <div class='modal-footer d-flex justify-content-center mt-5'>
              <button type='button' class='btn bg-lighter btn-lg' data-dismiss='modal'><i class="fal fa-times-circle"></i> Cancelar</button>
              <button type='submit' class='but btn-primary btn-lg'><i class='fal fa-check'></i> Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class='row'>
    <div class='col-12'>
      <!--Carousel Wrapper-->
      <div id="carousel-with-lb" class="carousel slide carousel-multi-item" data-ride="carousel">

        <!--Controls-->
        <div class="controls-top">
          <a class="btn-floating btn-secondary" href="#carousel-with-lb" data-slide="prev"><i
              class="fas fa-chevron-left"></i></a>
          <a class="btn-floating btn-secondary" href="#carousel-with-lb" data-slide="next"><i
              class="fas fa-chevron-right"></i></a>
        </div>
        <!--/.Controls-->

        <!--Indicators-->
        <ol class="carousel-indicators">
          <li data-target="#carousel-with-lb" data-slide-to="0" class="active secondary-color"></li>
          <li data-target="#carousel-with-lb" data-slide-to="1" class="secondary-color"></li>
          <li data-target="#carousel-with-lb" data-slide-to="2" class="secondary-color"></li>
        </ol>
        <!--/.Indicators-->

        <!--Slides and lightbox-->

        <div class="carousel-inner mdb-lightbox" role="listbox">
          <div id="mdb-lightbox-ui"></div>
          <!--First slide-->
          <div class=" carousel-item active text-center">

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(2).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(2).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(4).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(4).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(6).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(6).jpg"
                  class="img-fluid">
              </a>
            </figure>

          </div>
          <!--/.First slide-->

          <!--Second slide-->
          <div class="carousel-item text-center">

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(22).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(22).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(25).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(25).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(29).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(29).jpg"
                  class="img-fluid">
              </a>
            </figure>

          </div>
          <!--/.Second slide-->

          <!--Third slide-->
          <div class="carousel-item text-center">

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(44).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(44).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(45).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(45).jpg"
                  class="img-fluid">
              </a>
            </figure>

            <figure class="col-md-4 d-md-inline-block">
              <a href="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(66).jpg"
                data-size="1600x1067">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20(66).jpg"
                  class="img-fluid">
              </a>
            </figure>

          </div>
          <!--/.Third slide-->

        </div>
        <!--/.Slides-->

      </div>
      <!--/.Carousel Wrapper-->
    </div>
  </div>
</body>
<?php
    load_footer();
    bottom_page("quill_v", 'carousel', 'lightbox-imagens2');
    $conn->close();
?>
