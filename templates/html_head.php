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

?>

    <!DOCTYPE html>
    <html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b8e073920a.js" crossorigin="anonymous"></script>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Bootstrap Horizon -->
    <link href="css/bootstrap-horizon.css" rel="stylesheet">
    <link type="image/vnd.microsoft.icon" rel="icon" href="../imagens/favicon.ico"/>
    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <title>Ubwiki</title>
	<?php
		if ($html_head_template_quill == true) {
			echo "<link href='css/quill.snow.css' rel='stylesheet'>";
            echo "<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>";
            echo "<script type='text/javascript'>
	  	        var formatWhitelist_general = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'image'];
			</script>";
            echo "<script type='text/javascript'>
	  	        var formatWhitelist_anotacoes = ['italic', 'script', 'link', 'blockquote', 'list', 'header', 'image'];
			</script>";
            echo "<script type='text/javascript'>
		    var toolbarOptions_general = [
		        ['italic'],
		        ['blockquote'],
		        [{'list': 'ordered'}, {'list': 'bullet'}],
		        [{'script': 'super'}],
		        [{'header': [2, 3, false]}],
		        ['clean'],
		        ['image']
		    ];
			</script>";
            echo "<script type='text/javascript'>
	    var toolbarOptions_anotacoes = [
	        ['italic'],
	        ['strike'],
	        ['blockquote'],
	        [{'list': 'ordered'}, {'list': 'bullet'}],
	        [{'script': 'super'}],
	        [{'header': [2, 3, false]}],
	        ['clean']
	    ];
	</script>";
	}
        if ($html_head_template_one_page == true) {
            echo "
            <style>
              html, body, .onepage {
                height: 100vh;
                overflow-y: auto;
              }
            </style>
          ";
        }
        if ($html_head_template_conteudo != false) {
            echo "$html_head_template_conteudo";
        }
	?>
</head>
<?php