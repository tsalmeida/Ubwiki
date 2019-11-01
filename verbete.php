<?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  $result = $conn->query("SELECT id FROM Usuarios WHERE email = '$user_email'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $user_id = $row['id'];
    }
  }

  if (isset($_GET['tema'])) {
    $tema_id = $_GET['tema'];
  }

  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
  }

  $variaveis_php_session = "
    <script type='text/javascript'>
      var user_id=$user_id;
      var tema_id=$tema_id;
      var concurso='$concurso';
      var user_email='$user_email';
    </script>
  ";

  top_page($variaveis_php_session, "quill_v", "lightbox");

  $result = $conn->query("SELECT sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND id = $tema_id");
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
    }
  }

  $result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $materia = $row["materia"];
    }
  }

  $result = $conn->query("SELECT verbete_html, verbete_text, verbete_content FROM Verbetes WHERE id_tema = $tema_id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $verbete_html = $row['verbete_html'];
      $verbete_text = $row['verbete_text'];
      $verbete_content = $row['verbete_content'];
    }
  }
  else {
    $verbete_html = false;
    $verbete_text = false;
    $verbete_content = '%7B%22ops%22:%5B%7B%22insert%22:%22Este%20verbete%20ainda%20n%C3%A3o%20come%C3%A7ou%20a%20ser%20escrito.%5Cn%22%7D%5D%7D';
  }

  if (isset($_POST['quill_novo_verbete_html'])) {
    $novo_verbete_html = $_POST['quill_novo_verbete_html'];
    $novo_verbete_text = $_POST['quill_novo_verbete_text'];
    $novo_verbete_content = $_POST['quill_novo_verbete_content'];
    $novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
    $result = $conn->query("SELECT id FROM Verbetes WHERE id_tema = $tema_id");
    if ($result->num_rows > 0) {
      $result = $conn->query("UPDATE Verbetes SET verbete_html = '$novo_verbete_html', verbete_text = '$novo_verbete_text', verbete_content = '$novo_verbete_content', user_id = '$user_id' WHERE id_tema = $tema_id");
      $result = $conn->query("INSERT INTO Verbetes_arquivo (id_tema, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$tema_id', '$verbete_html', '$verbete_text', '$verbete_content', '$user_id')");
    }
    else {
      $result = $conn->query("INSERT INTO Verbetes (id_tema, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$tema_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
    }
    $verbete_content = $novo_verbete_content;
  }

  $verbete_content = urldecode($verbete_content);
  // $verbete_content = utf8_encode($verbete_content);
  // error_log("utf8 encode: $verbete_content");

  $result = $conn->query("SELECT anotacao FROM Anotacoes WHERE user_id = $user_id AND tema_id = $tema_id");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $anotacao_html = $row['anotacao'];
      break;
    }
  }
  else {
    $anotacao_html = false;
  }

  if (isset($_POST['quill_nova_anotacao_html'])) {
    $nova_anotacao_html = $_POST['quill_nova_anotacao_html'];
    $nova_anotacao_html = strip_tags($nova_anotacao_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
    if ($anotacao_html != false) {
      $update = $conn->query("UPDATE Anotacoes SET anotacao = '$nova_anotacao_html' WHERE user_id = $user_id AND tema_id = $tema_id");
      $insert = $conn->query("INSERT INTO Anotacoes_arquivo (user_id, tema_id, anotacao) VALUES ($user_id, $tema_id, '$nova_anotacao_html')");
    }
    else {
      $insert = $conn->query("INSERT INTO Anotacoes (user_id, tema_id, anotacao) VALUES ($user_id, $tema_id, '$nova_anotacao_html')");
    }
    $anotacao_html = $nova_anotacao_html;
  }

  if (isset($_POST['nova_imagem_link'])) {
    $nova_imagem_link = $_POST['nova_imagem_link'];
    $nova_imagem_titulo = $_POST['nova_imagem_titulo'];
    $nova_imagem_comentario = $_POST['nova_imagem_comentario'];
    adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $nova_imagem_comentario, $tema_id, $user_id);
  }

  if (isset($_POST['nova_referencia_titulo'])) {
    $nova_referencia_titulo = $_POST['nova_referencia_titulo'];
    $nova_referencia_autor = $_POST['nova_referencia_autor'];
    $nova_referencia_capitulo = $_POST['nova_referencia_capitulo'];
    $nova_referencia_ano = $_POST['nova_referencia_ano'];
    $nova_referencia_link = $_POST['nova_referencia_link'];
    $result = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
    if ($result->num_rows == 0) {
      $result = $conn->query("INSERT INTO Elementos (tipo, titulo, autor, capitulo, link, ano, user_id) VALUES ('referencia', '$nova_referencia_titulo', '$nova_referencia_autor', '$nova_referencia_capitulo', '$nova_referencia_link', '$nova_referencia_ano', '$user_id')");
      $result2 = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
      if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
          $nova_referencia_id = $row['id'];
          $insert = $conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $nova_referencia_id, 'referencia', $user_id)");
          break;
        }
      }
    }
    else {
      while ($row = $result->fetch_assoc()) {
        $nova_referencia_id = $row['id'];
        $insert = $conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $nova_referencia_id, 'referencia', $user_id)");
        break;
      }
    }
  }

  if (isset($_POST['novo_video_titulo'])) {
    $novo_video_titulo = $_POST['novo_video_titulo'];
    $novo_video_autor = $_POST['novo_video_autor'];
    $novo_video_link = $_POST['novo_video_link'];
    $result = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
    if ($result->num_rows == 0) {
      $result = $conn->query("INSERT INTO Elementos (tipo, titulo, autor, link, user_id) VALUES ('video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$user_id')");
      $result2 = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
      if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
          $novo_video_id = $row['id'];
          $insert = $conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $novo_video_id, 'video', $user_id)");
          break;
        }
      }
    }
    else {
      while ($row = $result->fetch_assoc()) {
        $novo_video_id = $row['id'];
        $insert = $conn->query("INSERT INTO Verbetes_elementos (id_tema, id_elemento, tipo, user_id) VALUES ($tema_id, $novo_video_id, 'video', $user_id)");
        break;
      }
    }
  }

  $tema_bookmark = false;
  $bookmark = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND tema_id = $tema_id");
  if ($bookmark->num_rows > 0) {
    while($row = $bookmark->fetch_assoc()) {
      $tema_bookmark = $row['bookmark'];
      break;
    }
  }
?>
<body>
<?php
  carregar_navbar('dark');
  $breadcrumbs = "
    <div class='d-block'><a href='index.php'>$concurso</a></div>
    <div class='d-block spacing1'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='materia.php?concurso=$concurso&sigla=$sigla_materia'>$materia</a></div>
  ";
  if ($nivel != 1) {
    if ($nivel1 != false) {
      $result = $conn->query("SELECT id FROM Temas WHERE nivel1 = '$nivel1' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $id_nivel1 = $row['id'];
          break;
        }
      }
      $breadcrumbs .= "<div class='d-block spacing2'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel1'>$nivel1</a></div>";
    }
  }
  else {
    $breadcrumbs .= "<div class='d-block spacing2'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel1</div>";
    $tema_titulo = $nivel1;
  }
  if ($nivel != 2) {
    if ($nivel2 != false) {
      $result = $conn->query("SELECT id FROM Temas WHERE nivel2 = '$nivel2' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $id_nivel2 = $row['id'];
          break;
        }
      }
      $breadcrumbs .= "<div class='d-block spacing3'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel2'>$nivel2</a></div>";
    }
  }
  else {
    $breadcrumbs .= "<div class='d-block spacing3'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel2</div>";
    $tema_titulo = $nivel2;
  }
  if ($nivel != 3) {
    if ($nivel3 != false) {
      $result = $conn->query("SELECT id FROM Temas WHERE nivel3 = '$nivel3' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $id_nivel3 = $row['id'];
          break;
        }
      }
      $breadcrumbs .= "<div class='d-block spacing4'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel3'>$nivel3</a></div>";
    }
  }
  else {
    $breadcrumbs .= "<div class='d-block spacing4'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel3</div>";
    $tema_titulo = $nivel3;
  }
  if ($nivel != 4) {
    if ($nivel4 != false) {
      $result = $conn->query("SELECT id FROM Temas WHERE nivel4 = '$nivel4' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $id_nivel4 = $row['id'];
          break;
        }
      }
      $breadcrumbs .= "<div class='d-block spacing5'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel4'>$nivel4</a></div>";
    }
  }
  else {
    $breadcrumbs .= "<div class='d-block spacing5'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel4</div>";
    $tema_titulo = $nivel4;
  }
  if ($nivel5 != false) {
    $breadcrumbs .= "<div class='d-block spacing6'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel5</div>";
    $tema_titulo = $nivel5;
  }

?>
  <div class='container-fluid grey lighten-3'>
    <div class='row'>
      <div class='col-lg-9 col-sm-12'>
        <div id='collapse_breadcrumbs' class='flex-column collapse'>
          <?php echo $breadcrumbs; ?>
        </div>
      </div>
      <div class='col-lg-3 col-sm-12'>
        <div class='text-right py-2'>
            <span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='collapse' href='#collapse_breadcrumbs' ><a href='javascript:void(0);'><i class='fal fa-chart-network fa-fw'></i></a></span>
            <span id='simulados' class='mx-1' title='Simulados'><a href='javascript:void(0);'><i class='fal fa-check-double fa-fw'></i></a></span>
            <span id='forum' title='Fórum'><a href='javascript:void(0);'><i class='fal fa-comments-alt fa-fw'></i></a></span>
<?php
            if ($tema_bookmark == false) {
              echo "
                <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
                <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$tema_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
              ";
            }
            else {
              echo "
                <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
                <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$tema_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
              ";
            }
?>
        </div>
      </div>
    </div>
  </div>

  <div class='container-fluid grey lighten-5' data-toggle='buttons'>
    <div class='row'>
      <div class='col-12 d-flex justify-content-center'>
        <button class='btn grey lighten-3 btn-rounded btn-sm verbete_collapse collapse mostrar_coluna_esquerda' data-toggle='collapse' data-target='.verbete_collapse' role='button'>Verbete</button>
        <button class='btn grey lighten-3 btn-rounded btn-sm imagens_collapse collapse ml-1 mostrar_coluna_esquerda' data-toggle='collapse' data-target='.imagens_collapse' role='button'>Imagens</button>
        <button class='btn grey lighten-3 btn-rounded btn-sm videos_collapse ml-1 collapse mostrar_coluna_esquerda' data-toggle='collapse' data-target='.videos_collapse' role='button'>Vídeos</button>
        <button class='btn grey lighten-3 btn-rounded btn-sm bibliografia_collapse collapse ml-1 mostrar_coluna_esquerda' data-toggle='collapse' data-target='.bibliografia_collapse' role='button'>Leia mais</button>
        <button id='mostrar_anotacoes' class='btn grey lighten-3 btn-rounded btn-sm collapse anotacoes_collapse collapse ml-1' data-toggle='collapse' data-target='.anotacoes_collapse' role='button'>Anotações</button>
      </div>
    </div>
  </div>

  <div id='page_height' class='container-fluid'>
    <div class='row my-5 d-flex justify-content-center'>
      <div class='col-lg-10 col-sm-12 text-center'>
        <?php
          $playfair = 'playfair400';
          $tema_length = strlen($tema_titulo);
          if ($tema_length < 20) { $display_size_large = 'display-1'; $display_size_mobile = 'display-2'; }
          elseif ($tema_length < 40) { $display_size_large = 'display-2'; $display_size_mobile = 'display-3'; }
          elseif ($tema_length < 60) { $display_size_large = 'display-3'; $display_size_mobile = 'display-4'; }
          elseif ($tema_length < 95) { $display_size_large = 'display-4'; $display_size_mobile = 'h1'; }
          else { $display_size_large = 'h1'; $display_size_mobile = 'h1'; $playfair = 'oswald'; }
          echo "
            <span class='$display_size_large d-none d-lg-inline $playfair'>$tema_titulo</span>
            <span class='$display_size_mobile d-inline d-lg-none $playfair'>$tema_titulo</span>
          ";
        ?>
      </div>
    </div>
    <div class='row justify-content-around'>
      <div id='coluna_esquerda' class='col-lg-5 col-sm-12'>
        <div id='verbete' class='verbete_collapse collapse show mb-5 border-top border-light pt-4'>
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h1>Verbete</h1>
              <span class='h5'>
                <span class='verbete_editor_collapse collapse' id='travar_verbete' data-toggle='collapse' data-target='.verbete_editor_collapse' title='travar verbete para edição'><a href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='verbete_editor_collapse collapse show' id='destravar_verbete' data-toggle='collapse' data-target='.verbete_editor_collapse' title='destravar verbete para edição'><a href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>
                <span id='esconder_verbete' data-toggle='collapse' data-target='.verbete_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-up fa-fw'></i></a></span>
            </div>
          </div>
          <div class='row py-3'>
            <div class='col-12'>
              <form id='quill_verbete_form' method='post'>
                <input name='quill_novo_verbete_html' type='hidden'>
                <input name='quill_novo_verbete_text' type='hidden'>
                <input name='quill_novo_verbete_content' type='hidden'>
                <div class='row'>
                  <div class='container col-12'>
                    <div id='quill_container_verbete'>
                      <div id='quill_editor_verbete' class='quill_editor_height quill_editor_height_leitura'>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='row justify-content-center verbete_editor_collapse collapse mt-3'>
                  <button type='button' class='btn btn-light btn-sm'><i class="fal fa-times-circle fa-fw"></i> Cancelar</button>
                  <button type='submit' class='btn btn-primary btn-sm'><i class='fal fa-check fa-fw'></i> Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div id='videos' class='videos_collapse collapse show mb-5 border-top border-light pt-4'>
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h1>Vídeos e aulas</h1>
              <span class='h5'><a data-toggle='modal' data-target='#modal_videos_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
              <span id='esconder_videos' data-toggle='collapse' data-target='.videos_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-up fa-fw'></i></a></span></span>
            </div>
          </div>
          <div class='row py-3'>
            <div class='col-12'>
<?php
              $result = $conn->query("SELECT id_elemento FROM Verbetes_elementos WHERE id_tema = $tema_id AND tipo = 'video'");
              if ($result->num_rows > 0) {
                echo "<ul class='list-group'>";
                  while($row = $result->fetch_assoc()) {
                    $id_elemento = $row['id_elemento'];
                    $result2 = $conn->query("SELECT titulo, autor, link FROM Elementos WHERE id = $id_elemento");
                    if ($result2->num_rows >  0) {
                      while ($row = $result2->fetch_assoc()) {
                        $video_titulo = $row['titulo'];
                        $video_autor = $row['autor'];
                        $video_link = $row['link'];
                        echo "<li class='list-group-item list-group-item-action'><a href='elemento.php?id=$id_elemento' target='_blank'>$video_titulo : $video_autor</a></li>";
                      }
                    }
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

        <div id='bibliografia' class='bibliografia_collapse collapse show mb-5 border-top border-light pt-4'>
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h1>Leia mais</h1>
              <span class='h5'><a data-toggle='modal' data-target='#modal_referencia_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
              <span id='esconder_bibliografia' data-toggle='collapse' data-target='.bibliografia_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-up fa-fw'></i></a></span></span>
            </div>
          </div>
          <div class='row py-3'>
            <div class='col-12'>
<?php
                $result = $conn->query("SELECT id_elemento FROM Verbetes_elementos WHERE id_tema = $tema_id AND tipo = 'referencia'");
                if ($result->num_rows > 0) {
                  echo "<ul class='list-group'>";
                    while($row = $result->fetch_assoc()) {
                      $id_elemento = $row['id_elemento'];
                      $result2 = $conn->query("SELECT titulo, autor, capitulo, ano, link FROM Elementos WHERE id = $id_elemento");
                      if ($result2->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                          $referencia_titulo = $row['titulo'];
                          $referencia_autor = $row['autor'];
                          $referencia_capitulo = $row['capitulo'];
                          $referencia_ano = $row['ano'];
                          $referencia_link = $row['link'];
                          echo "<li class='list-group-item'><a href='elemento.php?id=$id_elemento' target='_blank'>$referencia_titulo : $referencia_autor : $referencia_capitulo : $referencia_ano</a></li>";
                        }
                      }
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

        <div id='imagens' class='imagens_collapse collapse show mb-5 border-top border-light pt-4'>
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h1>Imagens</h1>
              <span class='h5'><a data-toggle='modal' data-target='#modal_imagens_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>
              <span id='esconder_imagens' data-toggle='collapse' data-target='.imagens_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-up fa-fw'></i></a></span></span>
            </div>
          </div>
          <div class='row py-3'>
            <div class='col-12'>
<?php
            $result = $conn->query("SELECT id_elemento FROM Verbetes_elementos WHERE id_tema = $tema_id AND tipo = 'imagem'");
            $count = 0;
            if ($result->num_rows > 0) {
              echo "
              <div id='carousel-with-lb' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                <div class='carousel-inner mdb-lightbox' role='listbox'>
                  <div id='mdb-lightbox-ui'></div>
              ";
              $active = 'active';
              while($row = $result->fetch_assoc()) {
                $id_elemento = $row['id_elemento'];
                $result2 = $conn->query("SELECT titulo, link, comentario, arquivo, resolucao, orientacao FROM Elementos WHERE id = $id_elemento");
                if ($result2->num_rows > 0) {
                  while($row = $result2->fetch_assoc()) {
                    $count++;
                    $imagem_titulo = $row['titulo'];
                    $imagem_link = $row['link'];
                    $imagem_comentario = $row['comentario'];
                    $imagem_arquivo = $row['arquivo'];
                    $imagem_resolucao = $row['resolucao'];
                    $imagem_orientacao = $row['orientacao'];
                    echo "
                    <div class=' carousel-item $active text-center'>
                      <figure class='col-12'>
                        <a href='imagens/verbetes/$imagem_arquivo'
                          data-size='$imagem_resolucao'>
                          <img src='imagens/verbetes/thumbnails/$imagem_arquivo'
                            class='img-fluid' style='height:300px'>
                        </a>
                        <figcaption><h5 class='mt-3'>$imagem_titulo</h5>
                        $imagem_comentario<p><a href='elemento.php?id=$id_elemento' target='_blank'>Página da imagem</a></p></figcaption>
                      </figure>
                    </div>
                    ";
                    $active = false;
                    break;
                  }
                }
              }
              if ($count != 1) {
                echo "
                </div>
                  <div class='controls-top'>
                    <a class='btn btn-sm grey lighten-3 z-depth-0' href='#carousel-with-lb' data-slide='prev'><i
                        class='fas fa-chevron-left'></i></a>
                    <a class='btn btn-sm grey lighten-3 z-depth-0' href='#carousel-with-lb' data-slide='next'><i
                        class='fas fa-chevron-right'></i></a>
                  </div>
                </div>
                ";
              }
              else {
                echo "</div></div>";
              }
            }
            else {
              echo "<p>Não foram acrescentadas, até o momento, imagens a este verbete.</p>";
            }
?>
            </div>
          </div>
        </div>
      </div>


      <div id='coluna_direita' class='col-lg-5 col-sm-12 anotacoes_collapse collapse show'>
        <div id='sticky_anotacoes' class='mb-5 border-top border-bottom border-light pt-4'>
          <div class='row'>
            <div class='col-12 d-flex justify-content-between'>
              <h1>Anotações</h1>
              <span class='h5'>
                <span class='anotacao_editor_collapse collapse show' id='travar_anotacao' data-toggle='collapse' data-target='.anotacao_editor_collapse' title='travar para edição'><a href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='anotacao_editor_collapse collapse' id='destravar_anotacao' data-toggle='collapse' data-target='.anotacao_editor_collapse' title='destravar para edição'><a href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>
                <span id='minimizar_anotacoes' data-toggle='collapse' data-target='.anotacoes_collapse'><a href='javascript:void(0);'><i class='fal fa-chevron-up fa-fw'></i></a></span>
              </span>
            </div>
          </div>
          <div class='row py-3'>
            <div class='col-12'>
              <form id='quill_anotacao_form' method='post'>
                <input name='quill_nova_anotacao_html' type='hidden'>
                <div class='row'>
                  <div class='container col-12'>
<?php
                      echo "
                        <div id='quill_container_anotacao'>
                          <div id='quill_editor_anotacao' class='quill_editor_height'>
                            $anotacao_html
                          </div>
                        </div>
                      ";
?>
                  </div>
                </div>
                <div class='row justify-content-center mt-3 anotacao_editor_collapse collapse show'>
                  <button type='button' class='btn btn-light btn-sm'><i class="fal fa-times-circle fa-fw"></i> Cancelar</button>
                  <button type='submit' class='btn btn-primary btn-sm'><i class='fal fa-check fa-fw'></i> Salvar</button>
                </div>
              </form>
            </div>
          </div>
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
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_imagem_titulo'>Título da imagem</label>
              </div>
              <div class='md-form'>
                <textarea type='text' id='nova_imagem_comentario' name='nova_imagem_comentario' class='md-textarea form-control' rows='4' required></textarea>
                <label data-error='preenchimento incorreto' data-success='preenchimento correto' for='nova_imagem_comentario'>Breve comentário sobre a imagem, destacando sua relevância didática.</label>
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
              <div class='md-form mb-2'>
                <input type='text' id='nova_referencia_ano' name='nova_referencia_ano' class='form-control validate'>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_referencia_ano'>Ano (opcional)</label>
              </div>
              <div class='md-form mb-2'>
                <input type='text' id='nova_referencia_link' name='nova_referencia_link' class='form-control validate'>
                <label data-error='preenchimento incorreto' data-successd='preenchimento correto' for='nova_referencia_link'>Link (opcional)</label>
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
  </div>
</body>
<?php
  load_footer();
  bottom_page($user_id, $tema_id, "quill_v", 'carousel', 'lightbox-imagens', 'collapse_stuff', 'bookmark_stuff');
  $conn->close();
?>

  <script type='text/javascript'>
    verbete_editor.setContents(<?php echo $verbete_content; ?>);
  </script>

</html>
