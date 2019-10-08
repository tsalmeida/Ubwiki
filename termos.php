  <?php
  include 'engine.php';
  top_page();
  ?>
  <body>
    <div class="container-fluid px-1 py-1 mb-3 text-center">
      <div class="jumbotron col-sm-12 mb-0">
        <h1 class="display-4">Ubwiki</h1>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <?php
          $result = extract_gdoc("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
          echo $result;
          ?>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </div>
  </body>
  <?php
    bottom_page();
  ?>
