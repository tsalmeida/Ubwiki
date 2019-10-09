<?php
include 'engine.php';
top_page();
?>
  <body>
      <div class="container-fluid px-1 py-1 mb-3">
        <div class="jumbotron col-sm-12 mb-0">
          <h1 class="display-3">Ubwiki</h1>
          <p class="lead">Sistema inteligente de estudos para o CACD.</p>
          <hr class="my-4">
          <p>Melhor utilizado em conjunção com o acervo de conteúdo e ferramentas da Ubique, especialmente o Ambiente de Estudos.</p>
        </div>
      </div>
    <div class="container-fluid text-center bg-white">
      <div class="row text-center row-eq-height">
        <?php
            cartao_materia("hb", "História do Brasil");
            cartao_materia("eco","Economia");
            cartao_materia("geo","Geografia");
            cartao_materia("hm","História Mundial");
            cartao_materia("dint","Direito Internacional");
            cartao_materia("di","Direito Interno");
            cartao_materia("pi","Política Internacional");
            cartao_materia("litpt","Língua Portuguesa");
            cartao_materia("liten","Língua Inglesa");
            cartao_materia("litfr","Língua Francesa");
         ?>
      </div>
    </div>
    <div class="container my-5 bg-light">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="list-group col-sm-8">
          <a href="#!" class="list-group-item list-group-item-action">Descobrimento</a>
          <a href="#!" class="list-group-item list-group-item-action">Independência</a>
          <a href="#!" class="list-group-item list-group-item-action">Getúlio Vargas</a>
          <a href="#!" class="list-group-item list-group-item-action">DitaduraMilitar</a>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div>
    <footer class="container-fluid text-center heavy-rain-gradient text-dark py-2">
      <p class="mb-0">A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Clique <a href="termos.php" target="_blank">aqui</a> para rever os termos e condições de uso da página.</p>
    </footer>
  </body>
<?php
  bottom_page();
?>
