<?php
  session_save_path('/home/tsilvaalmeida/public_html/ubwiki/sessions/');
  session_start();
  if (isset($_SESSION['email'])) {
    $user = $_SESSION['email'];
  }
  else {
    header('Location:login.php');
  }

  include 'engine.php';
  top_page();

?>
  <body>
    <?php
    carregar_navbar('dark');
    standard_jumbotron("Ubwiki", false);
    ?>
    <div class="container-fluid my-5">
      <div class="row justify-content-center">
        <div class="col-4 col-sm-12">
          <?php
          $result = extract_gdoc("https://docs.google.com/document/d/e/2PACX-1vTAJII7h1Fm2ndrB-KjqH2w4CvwfyKKcr5myjh_IfqCIe7-Ai9JZWj6wlNt5shG_wbNv0_KVELPGU6W/pub?embedded=true");
          echo $result;
          ?>
        </div>
      </div>
    </div>
  </body>
<?php
  bottom_page();
?>
