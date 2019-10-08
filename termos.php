<?php
include 'engine.php';
top_page();
?>
  <body>
    <div class="container-fluid px-1 py-1 text-center">
        <div class="jumbotron col-sm-12 mb-0">
          <h1 class="display-4">Ubwiki</h1>
        </div>
    </div>
    <iframe>
    <?php
      // $gdoc = file_get_contents("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");

      $ch = curl_init("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");

      $doc = false;

      curl_setopt($ch, CURLOPT_FILE, $doc);
      curl_setopt($ch, CURLOPT_HEADER, 0);

      curl_exec($ch);
      curl_close($ch);

      echo $doc;

     ?>
    </iframe>
  </body>
  <?php
    bottom_page();
  ?>
</html>
