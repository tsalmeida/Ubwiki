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

	if (!isset($pagina_favicon)) {
		$pagina_favicon = 'ubique_solid.ico';
	}

	if (!isset($pagina_title)) {
		$pagina_title = 'Ubwiki';
	}

    $quill_module = false;
	if (($html_head_template_quill == true) || ($html_head_template_quill_sim == true)) {
		$quill_module = "<script src='quill.js'></script>";
	}

    $texto_justificado_module = false;
	if ($opcao_texto_justificado_value) {
		$texto_justificado_module = "
		      	<style>
		      		.ql-editor {
		      			text-align: justify;
		      			white-space: normal;
		      		}
				</style>
		    ";
	}

    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <meta charset='utf-8'>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <meta http-equiv='x-ua-compatible' content='ie=edge'>
        <!-- 
        JQuery -->
        <script src=\"https://code.jquery.com/jquery-3.6.1.min.js\" integrity=\"sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=\" crossorigin=\"anonymous\"></script>
        <!-- Font Awesome -->
        <script src='https://kit.fontawesome.com/b8e073920a.js' crossorigin='anonymous'></script>
        <link href='css/style.css?20230109' rel='stylesheet'>
        <link type='image/vnd.microsoft.icon' rel='icon' href='$pagina_favicon'>
        <title>$pagina_title</title>
        $quill_module
        $texto_justificado_module
        ";

    echo "
    <script src='https://cdn.jsdelivr.net/npm/web3@1.5.2/dist/web3.min.js'></script>
    <script type='text/javascript'>

        if (typeof web3 !== 'undefined') {
          web3 = new Web3(web3.currentProvider);
        } else {
          // handle the case where the user doesn't have Metamask installed
        }

        document.getElementById('loginBtn').addEventListener('click', function() {
          web3.eth.requestAccounts().then(function(accounts) {
            // handle the case where the user is logged in with Metamask
          }).catch(function(error) {
            // handle the case where the user denies the Metamask login request
          });
        });

        

        </script>
    ";

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

?>
    </head>
<?php
