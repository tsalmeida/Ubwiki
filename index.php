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
    <div class="container text-center bg-white my-5">
      <ul class="list-group">
        <li class="list-group-item active">
          <div class="md-v-line"></div><i class="fas fa-laptop mr-4 pr-3"></i>Descobrimento do Brasil
        </li>
        <li class="list-group-item">
          <div class="md-v-line"></div><i class="fas fa-bomb mr-5"></i>Capitanias Hereditárias
        </li>
        <li class="list-group-item">
          <div class="md-v-line"></div><i class="fas fa-code mr-5"></i>Independência do Brasil
        </li>
        <li class="list-group-item">
          <div class="md-v-line"></div><i class="far fa-gem mr-5"></i>Getúlio Vargas
        </li>
        <li class="list-group-item">
          <div class="md-v-line"></div><i class="fas fa-cogs mr-5"></i>Governo Militar
        </li>
      </ul>
    </div>
    <footer class="container-fluid text-center heavy-rain-gradient text-dark py-2">
      <p class="mb-0">A Ubwiki é uma ferramenta de uso público e gratuito. Todos os direitos são reservados ao Grupo Ubique. Clique <a href="termos.php" target="_blank">aqui</a> para rever os termos e condições de uso da página.</p>
    </footer>
  </body>
<?php
  bottom_page();
?>
