<?php
  include 'engine.php';
  top_page("quill");

  if (isset($_GET['tema'])) {
    $id_tema = $_GET['tema'];
  }

  if (isset($_GET['concurso'])) {
    $concurso = $_GET['concurso'];
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
    }
  }
  $verbete_consolidado = base64_decode($verbete_consolidado);
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
                <div class='col-12 container'>
                  <textarea id='editor' name='verbete_texto' class='rounded textarea_verbete px-4 py-4 my-2'>$verbete_consolidado</textarea>
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
