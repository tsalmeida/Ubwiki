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
    <?php
      // $gdoc = file_get_contents("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");

      <?php
      // create a new cURL resource
      $ch = curl_init();

      // set URL and other appropriate options
      curl_setopt($ch, CURLOPT_URL, "ttps://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
      curl_setopt($ch, CURLOPT_HEADER, false);

      // grab URL and pass it to the browser
      curl_exec($ch);

      // close cURL resource, and free up system resources
      curl_close($ch);
      ?>

     ?>
  </body>
  <?php
    bottom_page();
  ?>
</html>
