<?php
	if(!isset($pick_database)) {
		$pick_database = "Ubwiki";
//		error_log("there was no pick database set");
	}
	$servername = "localhost";
	$username = "grupoubique";
	$password = "ubique patriae memor";
	$dbname = $pick_database;
	$conn = new mysqli($servername, $username, $password, $dbname);
	mysqli_set_charset($conn, "utf8");
	$pick_database = "Ubwiki";
	?>
