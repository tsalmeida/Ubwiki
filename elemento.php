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

?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Página de Elemento", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-sm-12">
          <h3>Dados do elemento:</h3>
          <?php
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
                echo "
                  <ul class='list-group'>
                    <li class='list-group-item list-group-item-action'>Criado em: $criacao_elemento</li>
                    <li class='list-group-item list-group-item-action'>Tipo: $tipo_elemento</li>
                    <li class='list-group-item list-group-item-action'>Título: $titulo_elemento</li>
                    <li class='list-group-item list-group-item-action'>Autor: $autor_elemento</li>
                    <li class='list-group-item list-group-item-action'>Capítulo: $capitulo_elemento</li>
                    <li class='list-group-item list-group-item-action'>Ano: $ano_elemento</li>
                    <li class='list-group-item list-group-item-action'><a href='$link_elemento' target='_blank'>Link</a></li>
                    <li class='list-group-item list-group-item-action'><a href='imagens/verbetes/$arquivo_elemento' target='_blank'>Arquivo</a></li>
                    <li class='list-group-item list-group-item-action'><a href='imagens/verbetes/thumbnail/$arquivo_elemento' target='_blank'>Thumbnail</a></li>
                    <li class='list-group-item list-group-item-action'>Resolução: $resolucao_elemento</li>
                    <li class='list-group-item list-group-item-action'>Orientação: $orientacao_elemento</li>
                    <li class='list-group-item list-group-item-action'>Comentário: $comentario_elemento</li>
                    <li class='list-group-item list-group-item-action'>Trecho: $trecho_elemento</li>
                    <li class='list-group-item list-group-item-action'>Acrescentado pelo usuário $user_id_elemento</li>
                  </ul>
                ";
              }
            }
          ?>
        </div>
      </div>
    </div>
  </body>
<?php
  bottom_page();
?>
