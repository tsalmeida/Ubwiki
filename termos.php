  <?php
  include 'engine.php';
  top_page();
  ?>
  <body>
    <?php
    carregar_navbar();
    standard_jumbotron("Ubwiki", false);
    breadcrumbs("
      <div class='mr-auto'>
        <nav>
          <ol class='breadcrumb d-inline-flex pl-0 pt-0 text-dark'>
            <li class='breadcrumb-item'><i class='fal fa-chevron-right'></i></li>
            <li class='breadcrumb-item text-muted2'>Termos de Serviço</li>
          </ol>
        </nav>
      </div>
    ");
    ?>
    <div class="container my-5">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <?php
          $result = extract_gdoc("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
          echo $result;
          ?>
        </div>
        <div class="col-2"></div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
