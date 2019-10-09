<?php

function top_page() {
  echo '
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="ubiquestyle.css">
    <link type="image/vnd.microsoft.icon" rel="icon" href="../nxst/favicons/simple.scss.ico"/>
    <title>Ubwiki</title>
  </head>
  ';
}

function bottom_page() {
  echo '
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  </html>
  ';
}

function extract_gdoc($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  $position = strpos($output, "<body");
  $body = substr($output, $position);
  $body = str_replace("</html>", "", $body);
  curl_close($ch);
  return $body;
}

function extract_zoho($linkplanilha, $authtoken, $ownername, $materia, $scope) {
  $ch = curl_init();
  $linkplanilha = "$linkplanilha?authtoken=$authtoken&zc_ownername=$ownername&materia=$materia&scope=$scope";
  curl_setopt($ch, CURLOPT_URL, $linkplanilha);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);

  $xml = simplexml_load_string($output, "SimpleXMLElement", LIBXML_NOCDATA);
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  $output = serialize($array);
  return $output;
}

function connect_to_mysql($id, $materia) {

  return $result;
}

function cartao_materia($id) {
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $conn = new mysqli($servername, $username, $password);
  $sql = "SELECT id, sigla, materia FROM Materias";
  $result = $conn->query($sql);
  echo "<p>$result</p>";

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $check = $row['id'];
        if ($id == $check) {
        $sigla = $row["sigla"];
        $materia = $row["materia"];
        echo "
            <div class='col-lg-2 col-md-3 py-2 px-2'>
              <a href='#$id' class=''>
                <div class='card card-cascade narrower'>
                  <div class='view view-cascade overlay'>
                    <img src='imagens/$id.jpg' class='card-img-top'
                      alt='$materia'>
                    <a>
                      <div class='mask rgba-white-slight'></div>
                    </a>
                  </div>
                  <div class='card-body card-body-cascade'>
                    <h5 class='card-title'>$materia</h5>
                  </div>
                </div>
              </a>
            </div>
        ";
      }
    }
  }
  $conn->close();
}



?>
