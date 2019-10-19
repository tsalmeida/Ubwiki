if (isset($_POST['metatemas_automaticos'])) {
  $concurso = $_POST['metatemas_automaticos'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("SELECT id, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND metaid IS NULL");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $nivel1 = $row['nivel1'];
      $nivel2 = $row['nivel2'];
      $nivel3 = $row['nivel3'];
      $nivel4 = $row['nivel4'];
      $nivel5 = $row['nivel5'];
      if ($nivel5 != false) { $novo_metaid = $nivel5; }
      elseif ($nivel4 != false) { $novo_metaid = $nivel4; }
      elseif ($nivel3 != false) { $novo_metaid = $nivel3; }
      elseif ($nivel2 != false) { $novo_metaid = $nivel2; }
      else { $novo_metaid = $nivel1; }
      $novo_metaid = strtolower($novo_metaid);
      $novo_metaid = str_replace(" e ", "-", $novo_metaid);
      $novo_metaid = str_replace(" a ", "-", $novo_metaid);
      $novo_metaid = str_replace(" o ", "-", $novo_metaid);
      $novo_metaid = str_replace(" as ", "-", $novo_metaid);
      $novo_metaid = str_replace(" os ", "-", $novo_metaid);
      $novo_metaid = str_replace(" de ", "-", $novo_metaid);
      $novo_metaid = str_replace(" do ", "-", $novo_metaid);
      $novo_metaid = str_replace(" da ", "-", $novo_metaid);
      $novo_metaid = str_replace(" dos ", "-", $novo_metaid);
      $novo_metaid = str_replace(" das ", "-", $novo_metaid);
      $novo_metaid = str_replace(" no ", "-", $novo_metaid);
      $novo_metaid = str_replace(" na ", "-", $novo_metaid);
      $novo_metaid = str_replace(" nos ", "-", $novo_metaid);
      $novo_metaid = str_replace(" nas ", "-", $novo_metaid);
      $novo_metaid = str_replace(" em ", "-", $novo_metaid);
      $novo_metaid = str_replace(" para ", "-", $novo_metaid);
      $novo_metaid = str_replace(" ", "-", $novo_metaid);
      $novo_metaid = str_replace("---", "-", $novo_metaid);
      $novo_metaid = str_replace("--", "-", $novo_metaid);
      $novo_metaid = str_replace(":", "", $novo_metaid);
      $novo_metaid = str_replace(".", "", $novo_metaid);
      $novo_metaid = str_replace(",", "", $novo_metaid);
      $novo_metaid = str_replace("ç", "c", $novo_metaid);
      $novo_metaid = str_replace("ã", "a", $novo_metaid);
      $novo_metaid = str_replace("á", "a", $novo_metaid);
      $novo_metaid = str_replace("â", "a", $novo_metaid);
      $novo_metaid = str_replace("í", "i", $novo_metaid);
      $novo_metaid = str_replace("ê", "e", $novo_metaid);
      $novo_metaid = str_replace("é", "e", $novo_metaid);
      $novo_metaid = str_replace("à", "a", $novo_metaid);
      $novo_metaid = str_replace("ú", "u", $novo_metaid);
      $novo_metaid = str_replace("(", "", $novo_metaid);
      $novo_metaid = str_replace(")", "", $novo_metaid);
      $novo_metaid = str_replace("ó", "o", $novo_metaid);
      $novo_metaid = str_replace("ô", "o", $novo_metaid);
      $novo_metaid = utf8_decode($novo_metaid);
      $novo_metaid = substr($novo_metaid, 0, 30);
      $novo_metaid = utf8_encode($novo_metaid);
      $update = $conn->query("UPDATE Temas SET metaid = '$novo_metaid' WHERE id = '$id'");
    }
  }
}

if (isset($_POST['novo_metatema_id'])) {
  $novo_metatema = $_POST['novo_metatema'];
  $novo_metatema_id = $_POST['novo_metatema_id'];
  $remover_ciclo = $_POST['remover_ciclo'];
  $servername = "localhost";
  $username = "grupoubique";
  $password = "ubique patriae memor";
  $dbname = "Ubique";
  $found = false;
  $conn = new mysqli($servername, $username, $password, $dbname);
  mysqli_set_charset($conn,"utf8");
  $result = $conn->query("UPDATE Temas SET metaid = '$novo_metatema' WHERE id = '$novo_metatema_id'");
  if ($remover_ciclo = true) {
    $result = $conn->query("UPDATE Temas SET ciclo_revisao = 1 WHERE id = '$novo_metatema_id'");
  }
}

<form class='text-center border border-light px-2 my-2' method='post'>
  <p class='h4 my-4'>Acrescentar meta-tema</p>
  <p class='text-left'>Para que o sistema seja imune a mudanças de edital, é necessário que cada tema seja internamente identificado por um meta-tema.</p>
  <fieldset class='form-group text-left'>
    <label for='registrarmeta'>Novo meta-tema para este assunto:</label>
    <input name='novo_metatema' id='registrarmeta' type='text' value='$metaid'></input>
  </fieldset>
  <div class='custom-control custom-checkbox'>
      <input type='checkbox' class='custom-control-input' id='remover_ciclo' name='remover_ciclo' checked>
      <label class='custom-control-label' for='remover_ciclo'>Remover do ciclo de revisão</label>
  </div>
  <button name='novo_metatema_id' type='submit' class='btn btn-primary' value='$id'>Registrar novo meta-tema</button>
</form>

<form class='text-center border border-light px-2 my-2' method='post'>
  <p class='h4 my-4'>Metatemas automáticos</p>
  <p class='text-left'>Ao pressionar o botão abaixo, todos os metatemas não-registrados serão automaticamente criados, baseados no título do tema.</p>
  <button name='metatemas_automaticos' type='submit' class='btn btn-primary' value='$concurso'>criar metatemas automaticos</button>
</form>

<p class='text-left'>Utiliza-se esta ferramenta para alterar a estrutura de temas do edital dos concursos. Alguns temas do edital são muito vagos, exigindo que sejam criadas novas sub-entradas. Outros incluem vários temas ao mesmo tempo, precisando ser divididos em várias entradas. É necessário, ainda, determinar uma metalinguagem permanente, que seja independente do texto do edital, que será reconhecida pelo sistema como correspondente àquele tema.</p>
<p class='text-left'>A matéria listada abaixo está marcada para revisão.</p>

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
