<?php
  include 'engine.php';
  top_page("quill");

  if (isset($_GET['tema'])) {
    $id_tema = $_GET['tema'];
  }

  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
  }

  if (isset($_POST['salvar_verbete_texto'])) {
    $concursoid = $_POST['salvar_verbete_texto'];
    $concursoid = unserialize($concursoid);
    $concurso = $concursoid[0];
    $id_tema = $concursoid[1];
    $novo_verbete = $_POST['verbete_texto'];
    $novo_verbete = strip_tags($novo_verbete);
    $novo_verbete = base64_encode($novo_verbete);
    $found = false;
    mysqli_set_charset($conn,"utf8");
    $result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = '$id_tema'");
    if ($result->num_rows > 0) {
      $result = $conn->query("UPDATE Verbetes (verbete) VALUES ('$novo_verbete')");
    }
    else {
      $result = $conn->query("INSERT INTO Verbetes (id_tema, concurso, verbete) VALUES ('$id_tema', '$concurso', '$novo_verbete')");
    }
  }

  $result = $conn->query("SELECT chave FROM Searchbar WHERE concurso = '$concurso' AND sigla = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $tema = $row['chave'];
    }
  }
  $result = $conn->query("SELECT verbete FROM Verbetes WHERE concurso = '$concurso' AND id_tema = $id_tema");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $verbete_consolidado = $row['verbete'];
      $verbete_consolidado = base64_decode($verbete_consolidado);
    }
  }
  else {
    $verbete_consolidado = false;
  }
  $salvar = array($concurso, $id_tema);
  $salvar = serialize($salvar);

?>
  <body>
<?php
    carregar_navbar();
    standard_jumbotron($tema, "verbete.php?concurso=$concurso&tema=$id_tema");
    sub_jumbotron("Edição de verbete", false);
?>
    <div class="container-fluid mb-5">
      <div class="row text-center justify-content-center">
        <div class="col-lg-8 col-sm-12">
<?php
            echo "
            <form method='post'>
              <div class='row justify-content-center'>
                <div id='quill_container' class='container col-12'>
                  <textarea id='quill_editor' name='verbete_texto' class='rounded textarea_verbete px-4 py-4 my-2'>$verbete_consolidado</textarea>
                </div>
              </div>
              <div class='row justify-content-center'>
                <button name='salvar_verbete_texto' type='submit' class='btn btn-primary' value='$salvar'>Salvar</button>
              </div>
            </form>
            ";
?>
        </div>
      </div>
    </div>
  </body>
<?php
  load_footer();
  bottom_page("quill");
?>
