<?php
	include 'engine.php';
	session_unset();
	session_destroy();
	header('Location:pagina.php?pagina_id=3');
