<?php
	$servername = "localhost";
	$username = "grupoubique";
	$password = "ubique patriae memor";
	$dbname = "Ubwiki";
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	mysqli_set_charset($conn, "utf8");
	?>
