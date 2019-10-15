  <?php
  include 'engine.php';
  top_page();

// else {
//   $materia = "geral";
//   header("materias.php?materia=$materia&concurso=$concurso");
// }

if (isset($_GET['tema'])) {
  $tema = $_GET['tema'];
}

if (isset($_GET['concurso'])) {
  $concurso = $_GET['concurso'];
}

  ?>
  <body>
    <?php
    standard_jumbotron();

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
    $result = $conn->query("SELECT id, verbete, verbetes, bibliografia, videos, links, discussao FROM Verbetes WHERE id_tema = $id_tema");
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

    echo "<h1 class='mb-5'>$tema</h1>";
    ?>
    <div class='container my-5'>
      <div class='row'>
        <div class='col-sm-2'>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-12'><h2>Índice</h2></div>
            </div>
            <div class='row mt-2'>
              <div class='col-lg-12'>
                <ul class='list-group'>
                  <a class='list-group-item list-group-item-action' href='#verbete'>Verbete consolidado</a>
                  <a class='list-group-item list-group-item-action' href='#imagens'>Imagens de apoio</a>
                  <a class='list-group-item list-group-item-action' href='#verbetes'>Verbetes relacionados</a>
                  <a class='list-group-item list-group-item-action' href='#bibliografia'>Bibliografia pertinente</a>
                  <a class='list-group-item list-group-item-action' href='#videos'>Vídeos e aulas relacionados</a>
                  <a class='list-group-item list-group-item-action' href='#links'>Links externos</a>
                  <a class='list-group-item list-group-item-action' href='#anotacoes'>Minhas anotações</a>
                  <a class='list-group-item list-group-item-action' href='#questoes'>Questões de provas passadas</a>
                  <a class='list-group-item list-group-item-action' href='#discussao'>Discussão</a>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class='col-sm-8'>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='verbete'>Verbete consolidado</h2></div>
              <div class='col-lg-1 h2 float-right'><a href='editar_verbete.php?concurso=$concurso&tema=$id_tema'><i class='fal fa-edit'></i></a></div>
            </div>
            <div class='row'>";
<?php
              if ($verbete == false) {
                echo "<div class='col-lg-12'><p>Ainda não há verbete consolidado para este tema.</p></div>";
              }
              else {
                $verbete = base64_decode($verbete);
                echo "<div class='col-lg-12'><p>$verbete</p></div>";
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='imagens'>Imagens de apoio</h2></div>
              <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
            </div>
            <div class='row'>
<?php
              if ($imagens == false) {
                echo "<div class='col-lg-12'><p>Ainda não foram acrescentadas imagens de apoio sobre este tema.</p></div>";
              }
              else {
                echo $imagens;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-12'><h2 id='verbetes'>Verbetes relacionados</h2></div>
            </div>
            <div class='row'>
<?php
              if ($verbetes == false) {
                echo "<div class='col-lg-12'><p>Ainda não foram identificados verbetes relacionados para este tema.</p></div>";
              }
              else {
                echo $verbetes;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='bibliografia'>Bibliografia pertinente</h2></div>
              <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
            </div>
            <div class='row'>
<?php
              if ($bibliografia == false) {
                echo "<div class='col-lg-12'><p>Ainda não foram identificados recursos bibliográficos sobre este tema.</p></div>";
              }
              else {
                echo $bibliografia;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='videos'>Vídeos e aulas relacionados</h2></div>
              <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
            </div>
            <div class='row'>
<?php
              if ($videos == false) {
                echo "<div class='col-lg-12'><p>Ainda não foram acrescentados links para vídeos e aulas sobre este tema.</p></div>";
              }
              else {
                echo $videos;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='links'>Links externos</h2></div>
              <div class='col-lg-1 h2 float-right'><a><i class='fal fa-plus-square'></i></a></div>
            </div>
            <div class='row'>
<?php
              if ($links == false) {
                echo "<div class='col-lg-12'><p>Ainda não foram acrescentados links externos sobre este tema.</p></div>";
              }
              else {
                echo $links;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-11'><h2 id='anotacoes'>Minhas anotações</h2></div>
              <div class='col-lg-1 h2 float-right'><a><i class='fal fa-edit'></i></a></div>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-12'><h2 id='questoes'>Questões de provas passadas</h2></div>
            </div>
            <div class='row'>
<?php
              $questoes = false;
              if ($questoes == false) {
                echo "<div class='col-lg-12'><p>Não há registro de questão em provas passadas sobre este tema.</p></div>";
              }
              else {
                echo $questoes;
              }
?>
            </div>
          </div>
          <div class='container-fluid mb-5 py-2 bg-lighter rounded'>
            <div class='row'>
              <div class='col-lg-12'><h2 id='discussao'>Debate</h2></div>
            </div>
            <div class='row'>
<?php
              if ($discussao == false) {
                echo "<div class='col-lg-12'><p>Não há debate sobre este tema. Deixe aqui sua opinião!</p></div>";
              }
              else {
                echo $discussao;
              }
?>
            </div>
          </div>
        </div>
        <div class='col-sm-2'></div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page();
?>
