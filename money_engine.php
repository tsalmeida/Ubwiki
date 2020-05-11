<?php
	
	function check_review_state($pagina_id)
	{
		if ($pagina_id == false) {
			return false;
		}
		include 'templates/criar_conn.php';
		$reviews = $conn->query("SELECT id FROM Orders WHERE pagina_id = $pagina_id AND tipo = 'review' AND estado = 1");
		if ($reviews->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	if (isset($_POST['order_review_pagina_id'])) {
		if (isset($_POST['new_review_comments'])) {
			$new_review_comments = $_POST['new_review_comments'];
			$new_review_comments = mysqli_real_escape_string($conn, $new_review_comments);
		} else {
			$new_review_comments = "NULL";
		}
		$order_review_pagina_id = $_POST['order_review_pagina_id'];
		$check = $conn->query("INSERT INTO Orders (tipo, user_id, pagina_id, comments) VALUES ('review', $user_id, $order_review_pagina_id, '$new_review_comments')");
		if ($check == true) {
			$check = $conn->query("INSERT INTO Transactions () VALUES ()");
			$conn->query("INSERT INTO Compartilhamento (tipo, user_id, item_id, item_tipo, compartilhamento, recipiente_id) VALUES ('revision', $user_id, $order_review_pagina_id, 'texto', 'grupo', 13)");
		}
	}
	
	if (isset($_SESSION['credito'])) {
		$novo_credito = $_SESSION['credito'];
		$credit_state = check_credit($novo_credito, 'verify');
		if ($credit_state == false) {
			return false;
			unset($_SESSION['credito']);
		} else {
			$credit_result = add_credit($user_id, $novo_credito);
			if ($credit_result == true) {
				unset($_SESSION['credito']);
			}
		}
	}
	
	function check_credit($novo_credito, $mode)
	{
		if ($novo_credito == false) {
			return false;
		}
		if ($mode == false) {
			$mode = 'verify';
		}
		include 'templates/criar_conn.php';
		$check_creditos = $conn->query("SELECT id, value FROM Creditos WHERE codigo = '$novo_credito' AND state = 1");
		if ($check_creditos->num_rows > 0) {
			switch ($mode) {
				case 'value':
					while ($check_credito = $check_creditos->fetch_assoc()) {
						$credito_value = $check_credito['value'];
						return $credito_value;
					}
					break;
				case 'disable':
					while ($check_credito = $check_creditos->fetch_assoc()) {
						$credito_id = $check_credito['id'];
						$conn->query("UPDATE Creditos SET state = 0 WHERE id = $credito_id");
						return true;
						break;
					}
					break;
				case 'verify':
					return true;
				default:
					return true;
			}
		} else {
			return false;
		}
	}
	
	function add_credit($user_id, $credit_code)
	{
		if (($user_id == false) || ($credit_code == false)) {
			return false;
		}
		$credit_value = check_credit($credit_code, 'value');
		if ($credit_value != false) {
			$user_wallet = return_wallet_value($user_id);
			$end_state = ($credit_value + $user_wallet);
			$conn->query("INSERT INTO Transactions (user_id, direction, value, prevstate, endstate) values ($user_id, 'positive', $credit_value, $user_wallet, $end_state)");
		}
		
		return true;
	}

?>