
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
