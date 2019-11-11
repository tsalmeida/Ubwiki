<?php
	include 'engine.php';
	
	if (isset($_GET['tema'])) {
		$tema_id = $_GET['tema'];
	}
	
	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}
	
	$variaveis_php_session = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var tema_id=$tema_id;
          var concurso='$concurso';
          var user_email='$user_email';
        </script>
    ";
	
	top_page($variaveis_php_session, "quill_h");
?>
    <body>
<?php
	carregar_navbar('dark');
	