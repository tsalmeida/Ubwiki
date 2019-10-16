<?php
  include 'engine.php';
  top_page();

if (isset($_GET['tema'])) {
  $id_tema = $_GET['tema'];
}

if (isset($_GET['concurso'])) {
  $concurso = $_GET['concurso'];
}

?>
<body>
  <?php
  carregar_navbar();
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND sigla = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $tema = $row['chave'];
    }
  }
  $found = false;
  $id_verbete = false;
  $verbete = false;
  $imagens = false;
  $verbetes = false;
  $bibliografia = false;
  $videos = false;
  $links = false;
  $discussao = false;
  $result = $conn->query("SELECT id, verbete, imagens, verbetes, bibliografia, videos, links, discussao FROM Verbetes WHERE id_tema = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $found = true;
      $id_verbete = $row["id"];
      $verbete = $row["verbete"];
      $imagens = $row["imagens"];
      $verbetes = $row["verbetes"];
      $bibliografia = $row["bibliografia"];
      $videos = $row["videos"];
      $links = $row["links"];
      $discussao = $row["discussao"];
    }
  }
  $conn->close();
  standard_jumbotron($tema, false);
?>
  <div class='container-fluid py-3 col-12 bg-lighter text-center'>
    <ul class='list-group list-group-horizontal-lg'>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#verbete'>Verbete consolidado</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#imagens'>Imagens de apoio</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#verbetes'>Verbetes relacionados</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#bibliografia'>Bibliografia pertinente</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#videos'>Vídeos e aulas relacionados</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#links'>Links externos</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#anotacoes'>Minhas anotações</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#questoes'>Questões de provas passadas</a>
      <a class='list-group-item list-group-item-action bg-lighter text-dark' href='#discussao'>Discussão</a>
    </ul>
  </div>
  <div class='container mt-5 justify-content-center' id='verbete'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Verbete consolidado</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a href='editar_verbete.php?concurso=$concurso&tema=$id_tema'><i class='fal fa-edit'></i></a></span>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          if ($verbete == false) {
            echo "<p>Não há, no momento, verbete consolidado para este tema.</p>";
          }
          else {
            $verbete = base64_decode($verbete);
            $separator = "\r\n";
            $line = strtok($verbete, $separator);

            while ($line !== false) {
                echo "<p class='pv'>$line</p>";
                $line = strtok( $separator );
            }
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='imagens'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Imagens de apoio</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a><i class='fal fa-plus-square'></i></span></a>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          if ($imagens == false) {
            echo "<p>Ainda não foram acrescentadas imagens de apoio a este verbete.</p>";
          }
          else {
            echo "$imagens";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='verbetes'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Verbetes relacionados</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'></div>
      <div class='col-6 text-left'>
        <?php
          if ($verbetes == false) {
            echo "<p>Não há verbetes relacionados a este tema.</p>";
          }
          else {
            echo "$verbetes";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='bibliografia'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Bibliografia pertinente</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a><i class='fal fa-plus-square'></i></span></a>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          if ($bibliografia == false) {
            echo "<p>Não foram identificados, até o momento, recursos bibliográficos sobre este tema.</p>";
          }
          else {
            echo "$bibliografia";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='videos'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Vídeos e aulas relacionados</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a><i class='fal fa-plus-square'></i></span></a>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          if ($videos == false) {
            echo "<p>Ainda não foram acrescentados links para vídeos e aulas sobre este tema.</p>";
          }
          else {
            echo "$videos";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='links'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Links externos</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a><i class='fal fa-plus-square'></i></span></a>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          if ($links == false) {
            echo "<p>Ainda não foram acrescentados links externos sobre este tema.</p>";
          }
          else {
            echo "$links";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='anotacoes'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Minhas anotações</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'>
          <?php echo "<span class='h4 text-center justify-content-center align-middle'><a><i class='fal fa-edit'></i></span></a>"; ?>
      </div>
      <div class='col-6 text-left'>
        <?php
          $anotacoes = false;
          if ($anotacoes == false) {
            echo "<p>Você ainda não acrescentou suas próprias anotações sobre este tema.</p>";
          }
          else {
            echo "$anotacoes";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='questoes'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Questões de provas passadas</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'></div>
      <div class='col-6 text-left'>
        <?php
          if ($questoes == false) {
            echo "<p>Não há registro de questões em provas passadas sobre este tema.</p>";
          }
          else {
            echo "$questoes";
          }
        ?>
      </div>
    </div>
  </div>

  <div class='container mt-5 justify-content-center' id='discussao'>
    <div class='row'>

      <div class='col-2 text-center justify-content-center align-middle'>
        <span class='align-middle'>Debate</span>
      </div>
      <div class='col-1 text-center justify-content-center align-middle border-right border-dark'></div>
      <div class='col-6 text-left'>
        <?php
          if ($discussao == false) {
            echo "<p>Não há debate sobre este tema. Deixe aqui sua opinião!</p>";
          }
          else {
            echo "$discussao";
          }
        ?>
      </div>
    </div>
  </div>
</body>
<?php
  load_footer();
  bottom_page();
?>
