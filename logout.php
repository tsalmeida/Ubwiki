<?php
	include 'engine.php';
	
	session_unset();
	session_destroy();
	
	include 'templates/html_head.php';
?>
<body>
<?php
	include 'templates/navbar.php';
	standard_jumbotron("Ubwiki", false);
?>
<div class="container-fluid my-5">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-sm-12">
            <h1>Logout realizado</h1>
            <p>Para retornar, por favor siga o link na <a href='https://www.grupoubique.com.br'>página do Grupo
                    Ubique</a>.</p>
        </div>
    </div>
</div>
</body>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>
</html>
