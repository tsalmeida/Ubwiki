<?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    $user = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }
  if (isset($_GET['id'])) {
    $id_elemento = $_GET['id'];
  }

  include 'engine.php';
  top_page(false);

  $result = $conn->query("SELECT criacao, tipo, titulo, autor, capitulo, ano, link, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $id_elemento");
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $criacao_elemento = $row['criacao'];
      $tipo_elemento = $row['tipo'];
      $titulo_elemento = $row['titulo'];
      $autor_elemento = $row['autor'];
      $capitulo_elemento = $row['capitulo'];
      $ano_elemento = $row['ano'];
      $link_elemento = $row['link'];
      $arquivo_elemento = $row['arquivo'];
      $resolucao_elemento = $row['resolucao'];
      $orientacao_elemento = $row['orientacao'];
      $comentario_elemento = $row['comentario'];
      $trecho_elemento = $row['trecho'];
      $user_id_elemento = $row['user_id'];
      $result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id_elemento");
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $user_apelido_elemento = $row['apelido'];
        }
      }
      else {
        $user_apelido_elemeto = "(usuário não-identificado)";
      }
      break;
    }
}

?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Página de Elemento", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-around">
        <div class="col-lg-5 col-sm-12">
          <?php
            if ($tipo_elemento == 'imagem') {
              echo "<h3>Imagem</h3>
                <div class='row'>
                  <div class='col-12'>
                    <img class='imagem_pagina' src='imagens/verbetes/$arquivo_elemento'></img>
                  </div>
                </div>
              ";
            }
          ?>
          <h3>Dados do elemento:</h3>
          <?php
                echo "
                  <ul class='list-group'>
                    <li class='list-group-item list-group-item-action'><strong>Criado em:</strong> $criacao_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Tipo:</strong> $tipo_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Título:</strong> $titulo_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Autor:</strong> $autor_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Capítulo:</strong> $capitulo_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Ano:</strong> $ano_elemento</li>
                    <li class='list-group-item list-group-item-action'><a href='$link_elemento' target='_blank'>Link</a></li>
                    <li class='list-group-item list-group-item-action'><a href='imagens/verbetes/$arquivo_elemento' target='_blank'>Arquivo</a></li>
                    <li class='list-group-item list-group-item-action'><a href='imagens/verbetes/thumbnails/$arquivo_elemento' target='_blank'>Thumbnail</a></li>
                    <li class='list-group-item list-group-item-action'><strong>Resolução:</strong> $resolucao_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Orientação:</strong> $orientacao_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Comentário:</strong> $comentario_elemento</li>
                    <li class='list-group-item list-group-item-action'><strong>Trecho:</strong> $trecho_elemento</li>
                    <li class='list-group-item list-group-item-action'>Acrescentado pelo usuário <strong>$user_apelido_elemento</strong></li>
                  </ul>
                ";
          ?>
        </div>
        <div class='col-lg-5 col-sm-12'>
          <h3>Anotações aqui</h3>
          <p>Quill text editor</p>
        </div>
      </div>
    </div>
  </body>
<?php
  bottom_page();
?>
