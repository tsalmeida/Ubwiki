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

  $result = $conn->query("SELECT id, tipo, criacao, apelido, nome, sobrenome FROM Usuarios WHERE email = '$user_email'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $user_id = $row['id'];
      $user_tipo = $row['tipo'];
      $user_criacao = $row['criacao'];
      $user_apelido = $row['apelido'];
      $user_nome = $row['nome'];
      $user_sobrenome = $row['sobrenome'];
    }
  }

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

  if (isset($_POST['quill_nova_mensagem_html'])) {
    $nova_mensagem = $_POST['quill_nova_mensagem_html'];
    $nova_mensagem = strip_tags($nova_mensagem, '<p><li><ul><ol><h2><blockquote><em><sup><s>');
    $result = $conn->query("SELECT analise, user_id FROM Elementos_analise WHERE id_elemento = $id_elemento");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $analise_user_id = $row['user_id'];
        $analise_previa = $row['analise'];
        $conn->query("UPDATE Elementos_analise SET analise = '$nova_mensagem' WHERE id_elemento = $id_elemento");
        $conn->query("INSERT INTO Elementos_analise_arquivo (user_id, id_elemento, analise§) VALUES ($analise_user_id, $id_elemento, '$analise_previa')");
      }
    }
    else {
      $conn->query("INSERT INTO Elementos_analise (user_id, id_elemento, analise) VALUES ($user_id, $id_elmento, '$nova_mensagem')");
    }
    $elemento_analise = $nova_mensagem;
  }
  else {
    $result = $conn->query("SELECT analise FROM Elementos_analise WHERE id_elemento = $id_elemento");
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $elemento_analise = $row['analise'];
      }
    }
    else {
      $elemento_analise = false;
    }
  }
}

  top_page(false, 'quill_elemento');

?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Página de Elemento", false);
    ?>
    <div class="container-fluid my-5">
      <div class='row justify-content-around'>
        <div class="col-lg-5 col-sm-12">
          <?php
            if ($tipo_elemento == 'imagem') {
              echo "
              <h3>Imagem</h3>
              <img class='imagem_pagina border' src='imagens/verbetes/$arquivo_elemento'></img>
              ";
            }
          ?>
          <h3 class='mt-3'>Dados do elemento:</h3>
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
          <h4>Análise</h4>
          <form id='quill_elemento_form' method='post'>
            <input name='quill_nova_mensagem_html' type='hidden'>
            <div class='row'>
              <div class='container col-12'>
                <?php
                  echo "
                    <div id='quill_container_elemento'>
                      <div id='quill_editor_elemento' class='quill_editor_height'>
                        $elemento_analise
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
  </body>
<?php
  bottom_page('quill_elemento');
?>
