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
      <div class="row">
        <?php
            ler_cartoes("CACD");
        ?>
      </div>
    </div>

    <!-- <div class="container my-5 bg-light py-5 col-sm-12">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 bg-white py-3">
          <form method="GET" action="https://creator.zoho.com/api/json/ubique/view/ETIQUETAS_RPT">
            <div class="form-group">
              <label>authtoken</label>
              <input class="form-control mb-4" name ="authtoken" value="10abe2d1aae94281cff517bb019aea2b" type="text"></input>
            </div>
            <div class="form-group">
              <label>zc_ownername</label>
              <input class="form-control mb-4" name ="zc_ownername" value="marciliofilho" type="text"></input>
            </div>
            <div class="form-group">
              <label>criteria</label>
              <input class="form-control mb-4" name="criteria" value='(materia.materia == "Geografia")' type="text"></input>
            </div>
            <div class="form-group">
              <label>scope</label>
              <input class="form-control mb-4" name ="scope" id="scope" value="creatorapi" type="text"></input>
            </div>
            <button type="submit">Enviar</button>
          </form>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div> -->

    <!-- <div class="container my-5 bg-light py-5 col-sm-12">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 bg-white py-3"> -->
          <?php
            // $result = extract_zoho("https://creator.zoho.com/api/xml/ubique/view/ETIQUETAS_RPT", "10abe2d1aae94281cff517bb019aea2b", "marciliofilho", "Geografia", "creatorapi");
            // echo "<p>$result</p>";
           ?>
        <!-- </div>
        <div class="col-sm-2"></div>
      </div>
    </div> -->

    <div class="container my-5 bg-light py-5 col-sm-12" id="#hb">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="list-group col-sm-8">
          <?php
              ler_edital("Cultura Geral");
           ?>
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
