<?php
include 'engine.php';
top_page();
?>
  <body>

    <div class="px-1 py-1">
        <div class="jumbotron col-sm-12 mb-0">
          <h1 class="display-4">Ubwiki</h1>
        </div>
    </div>
    <!-- <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true" allowfullscreen></iframe>
    </div> -->
    <?php
      echo file_get_contents("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
     ?>
  </body>
  <?php
    bottom_page();
  ?>
</html>
