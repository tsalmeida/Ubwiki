  <?php
  include 'engine.php';
  top_page();
  ?>
  <body>
    <?php
    carregar_navbar("
      <div class='mr-auto'>
        <nav>
          <ol class='breadcrumb d-inline-flex pl-0 pt-0 text-dark'>
            <li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li>
            <li class='breadcrumb-item text-muted2'>Sua página</li>
          </ol>
        </nav>
      </div>
    ");
    standard_jumbotron("Sua página", false);
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <p>Informações do usuário aqui. Verbetes em que colaborou, comentários que escreveu, temas em que já fez anotações, temas prioritários etc.</p>
        </div>
        <div class="col-2"></div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
