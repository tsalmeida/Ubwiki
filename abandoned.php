function bottom_page()
{

$args = func_get_args();

if ($args != false) {
$array = 0;
while (isset($args[$array])) {
if ($args[$array] == "quill_admin") {
echo "

<script>
    var formatWhitelist_admin = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'strike'];
    var admin_editor = new Quill('#quill_editor_admin', {
        theme: 'snow',
        formats: formatWhitelist_admin,
        modules: {
            toolbar: toolbarOptions_admin
        }
    });
    var form_admin = document.querySelector('#quill_admin_form');
    form_admin.onsubmit = function () {
        var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
        quill_nova_mensagem_html.value = admin_editor.root.innerHTML;
    }
</script>
";
}
if ($args[$array] == "quill_user") {
echo "
<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
<script>

    var formatWhitelist_user = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'strike'];
    var user_editor = new Quill('#quill_editor_user', {
        theme: 'snow',
        formats: formatWhitelist_user,
        modules: {
            toolbar: toolbarOptions_user
        }
    });
    var form_user = document.querySelector('#quill_user_form');
    form_user.onsubmit = function () {
        var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
        quill_nova_mensagem_html.value = user_editor.root.innerHTML;
    }
</script>
";
} elseif ($args[$array] == "quill_v") {
echo "

<script type='text/javascript'>

    var Delta_verbete = Quill.import('delta');


</script>
";
} elseif ($args[$array] == "quill_elemento") {
echo "
<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>
<script>
    var elemento_editor = new Quill('#quill_editor_elemento', {
        theme: 'snow',
        formats: formatWhitelist_elemento,
        modules: {
            toolbar: toolbarOptions_elemento
        }
    });
    var form_elemento = document.querySelector('#quill_elemento_form');
    form_elemento.onsubmit = function () {
        var quill_nova_mensagem_html = document.querySelector('input[name=quill_nova_mensagem_html]');
        quill_nova_mensagem_html.value = elemento_editor.root.innerHTML;
    }
</script>
";
} elseif ($args[$array] == "edicao_topicos") {
echo "

";
}

<?php

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


} elseif ($args[$array] == 'collapse_stuff') {
echo "
<script type='text/javascript'>
    $('#esconder_verbete').click(function(){
        if ( $('#videos').css('display') == 'none' && $('#imagens').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
            $('#coluna_esquerda').css('display', 'none');
            $('#coluna_direita').addClass('col-lg-6');
            $('#coluna_direita').removeClass('col-lg-5');
        }
        if ( $('#coluna_direita').css('display') == 'none' ) {
            $('#coluna_esquerda').addClass('col-lg-6');
            $('#coluna_esquerda').removeClass('col-lg-5');
        }
    });
    $('#esconder_imagens').click(function(){
        if ( $('#verbete').css('display') == 'none' && $('#videos').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
            $('#coluna_esquerda').css('display', 'none');
            $('#coluna_direita').addClass('col-lg-6');
            $('#coluna_direita').removeClass('col-lg-5');
        }
        if ( $('#coluna_direita').css('display') == 'none' ) {
            $('#coluna_esquerda').addClass('col-lg-6');
            $('#coluna_esquerda').removeClass('col-lg-5');
        }
    });
    $('#esconder_videos').click(function(){
        if ( $('#verbete').css('display') == 'none' && $('#imagens').css('display') == 'none' && $('#bibliografia').css('display') == 'none' ) {
            $('#coluna_esquerda').css('display', 'none');
            $('#coluna_direita').addClass('col-lg-6');
            $('#coluna_direita').removeClass('col-lg-5');
        }
        if ( $('#coluna_direita').css('display') == 'none' ) {
            $('#coluna_esquerda').addClass('col-lg-6');
            $('#coluna_esquerda').removeClass('col-lg-5');
        }
    });
    $('#esconder_bibliografia').click(function(){
        if ( $('#verbete').css('display') == 'none' && $('#videos').css('display') == 'none' && $('#imagens').css('display') == 'none' ) {
            $('#coluna_esquerda').css('display', 'none');
            $('#coluna_direita').addClass('col-lg-6');
            $('#coluna_direita').removeClass('col-lg-5');
        }
        if ( $('#coluna_direita').css('display') == 'none' ) {
            $('#coluna_esquerda').addClass('col-lg-6');
            $('#coluna_esquerda').removeClass('col-lg-5');
        }
    });
    $('.mostrar_coluna_esquerda').click(function(){
        $('#coluna_esquerda').css('display', 'inline');
        $('#coluna_direita').addClass('col-lg-5');
        $('#coluna_direita').removeClass('col-lg-6');
    });
    $('#mostrar_anotacoes').click(function(){
        $('#coluna_esquerda').addClass('col-lg-5');
        $('#coluna_esquerda').removeClass('col-lg-6');
        $('#coluna_direita').addClass('col-lg-5');
        $('#coluna_direita').removeClass('col-lg-6');
    });
    $('#minimizar_anotacoes').click(function(){
        $('#coluna_esquerda').addClass('col-lg-6');
        $('#coluna_esquerda').removeClass('col-lg-5');
        $('#coluna_direita').addClass('col-lg-6');
        $('#coluna_direita').removeClass('col-lg-5');
    });
</script>";