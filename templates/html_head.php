<?php

	if (!isset($html_head_template_quill)) {
		$html_head_template_quill = false;
	}
	if (!isset($html_head_template_one_page)) {
		$html_head_template_one_page = false;
	}
	if (!isset($html_head_template_conteudo)) {
		$html_head_template_conteudo = false;
	}
	if (!isset($html_head_template_quill_sim)) {
		$html_head_template_quill_sim = false;
	}

	echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <meta http-equiv='x-ua-compatible' content='ie=edge'>";

	echo "
        <!-- Bootstrap 5 -->
        <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC\" crossorigin=\"anonymous\">
        <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM\" crossorigin=\"anonymous\"></script>
        <!-- Font Awesome -->
        <script src='https://kit.fontawesome.com/b8e073920a.js' crossorigin='anonymous'></script>
        <!-- Your custom styles (optional) -->
        <link href='css/style.css?20221109' rel='stylesheet'>
        <link type='image/vnd.microsoft.icon' rel='icon' href='../imagens/favicon.ico'/>
        <!-- JQuery -->
        <script src=\"https://code.jquery.com/jquery-3.6.1.min.js\" integrity=\"sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=\" crossorigin=\"anonymous\"></script>";
    if ($pagina_tipo == 'nexus') {
        $pagina_title = "Nexus";
    } else {
        $pagina_title = 'Ubwiki';
    }
    echo "<title>$pagina_title</title>";

	if (($html_head_template_quill == true) || ($html_head_template_quill_sim == true)) {
		echo "<link href='css/quill.snow.css' rel='stylesheet'>";
		echo "<script src='quill.js'></script>";
	}

	if ($html_head_template_quill == true) {
		echo "
            <script type='text/javascript'>
	  	        var formatWhitelist_verbete = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'image', 'indent', 'bold'];
	  	        var formatWhitelist_modelo = ['italic', 'list', 'bold'];
	  	        var formatWhitelist_modelo_directions = formatWhitelist_modelo;
	  	        var formatWhitelist_anotacoes = ['italic', 'script', 'link', 'blockquote', 'list', 'checkbox', 'header', 'image', 'bold', 'background', 'color', 'strike', 'underline', 'align', 'link', 'video', 'image', 'indent'];
                var toolbarOptions_verbete = [
                    [{'header': [2, 3, false]}],
                    ['bold'],
                    ['italic'],
                    [{'script': 'super'}],
                    ['blockquote'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'indent': '-1'}, { 'indent': '+1' }],
                    ['image'],
                ];
                var toolbarOptions_modelo = [
                    ['bold'],
                    ['italic'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                ];
                var toolbarOptions_modelo_directions = toolbarOptions_modelo;
                var toolbarOptions_anotacoes = [
                    [{'header': [2, 3, false]}],
                    ['bold'],
                    ['italic'],
                    ['underline'],
                    ['strike'],
                    [{'color': [false, 'red', 'orange', 'yellow', 'green', 'blue', 'purple']}],
                    [{'background': [false, 'red', 'orange', 'yellow', 'green', 'blue', 'purple']}],
                    [{'script': 'super'}],
                    ['blockquote'],
                    [{'list': 'ordered'}, {'list': 'bullet'}, {'list': 'check'}],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{'align': []}],
                    ['link'],
                    ['video'],
                ];
                var BackgroundClass = Quill.import('attributors/class/background');
                var ColorClass = Quill.import('attributors/class/color');
				Quill.register(BackgroundClass, true);
				Quill.register(ColorClass, true);
            </script>";
	}
	if ($html_head_template_conteudo != false) {
		echo "$html_head_template_conteudo";
	}
	if ($html_head_template_quill_sim == true) {
		echo "
		        <script type='text/javascript'>
		        var formatWhitelist_simulados = ['italic', 'bold', 'underline', 'script', 'list', 'indent', 'image'];
                var toolbarOptions_simulados = [
                    ['bold'],
                    ['italic'],
                    ['underline'],
                    [{'script': 'super'}],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['image'],
                ];
		        </script>
		    ";
	}
	if ($opcao_texto_justificado_value == true) {
		echo "
		      	<style>
		      		.ql-editor {
		      			text-align: justify;
		      			white-space: normal;
		      		}
				</style>
		    ";
	}
?>
    </head>
<?php
