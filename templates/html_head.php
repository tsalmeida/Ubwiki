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
        <meta http-equiv='x-ua-compatible' content='ie=edge'>
        <!-- Font Awesome -->
        <script src='https://kit.fontawesome.com/b8e073920a.js' crossorigin='anonymous'></script>
        <!-- Bootstrap core CSS -->
        <link href='css/bootstrap.min.css' rel='stylesheet'>
        <!-- Material Design Bootstrap -->
        <link href='css/mdb.min.css' rel='stylesheet'>
        <!-- Your custom styles (optional) -->
        <link href='css/style.css' rel='stylesheet'>
        <!-- Bootstrap Horizon -->
        <link href='css/bootstrap-horizon.css' rel='stylesheet'>
        <link type='image/vnd.microsoft.icon' rel='icon' href='../imagens/favicon.ico'/>
        <!-- JQuery -->
        <script type='text/javascript' src='js/jquery-3.4.1.min.js'></script>
        <title>Ubwiki</title>
    ";
	
		if (($html_head_template_quill == true) || ($html_head_template_quill_sim == true)) {
			echo "<link href='css/quill.snow.css' rel='stylesheet'>";
			echo "<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>";
		}
		if ($html_head_template_quill == true) {
			echo "
            <script type='text/javascript'>
	  	        var formatWhitelist_verbete = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'image', 'indent', 'bold'];
	  	        var formatWhitelist_anotacoes = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'image', 'bold', 'background', 'color', 'strike', 'underline', 'align', 'link', 'video', 'image', 'indent'];
                var toolbarOptions_verbete = [
                    [{'header': [2, 3, false]}],
                    ['bold'],
                    ['italic'],
                    [{'script': 'super'}],
                    ['blockquote'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'indent': '-1'}, { 'indent': '+1' }],
                    ['image'],
                    ['clean'],
                ];
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
                    ['clean'],
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
                    ['clean'],
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
