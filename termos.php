<?php
	
  include 'engine.php';
	
	include 'templates/html_head.php';

?>
  <body>
    <?php
	    include 'templates/navbar.php';
        standard_jumbotron("Ubwiki", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="<?php echo $coluna_classes; ?>">
          <?php
          $result = extract_gdoc("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
          echo $result;
          ?>
        </div>
      </div>
    </div>
  </body>
<?php

	include 'templates/html_bottom.php';
?>
</html>
