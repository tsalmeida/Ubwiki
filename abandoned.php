
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


var change_verbete = new Delta_verbete();
verbete_editor.on('text-change', function(delta) {
  change_verbete = change_verbete.compose(delta);
});
setInterval(function() {
  if(change_verbete.length() > 0) {
    console.log('Saving changes', change_verbete);
    change_verbete = new Delta_verbete();
  }
}, 5*1000);

window.onbeforeunload = function() {
  if (change_verbete.length() > 0) {
    alert('Suas contribuições ainda não foram salvas. Realmente deseja sair?');
  }
}

if ($nivel == 3) {
  $result = $conn->query("SELECT id, nivel3 FROM Temas WHERE nivel2 = '$nivel2' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia'");
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $sibling_titulo = $row['nivel3'];
      $sibling_id = $row['id'];
      $breadcrumbs .= "<div class='d-block spacing4'><i class='fal fa-long-arrow-right fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$sibling_id'>$sibling_titulo</a></div>";
    }
  }
}
