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
	
	if (isset($_GET['credito'])) {
		$_SESSION['credito'] = $_GET['credito'];
	}
	
	if (isset($_POST['adicionar_credito_codigo'])) {
		$_SESSION['credito'] = $_POST['adicionar_credito_codigo'];
	}
	
	if (isset($_SESSION['credito'])) {
		$novo_credito = $_SESSION['credito'];
		$credit_state = check_credit($novo_credito, 'verify', $user_id);
		if ($credit_state == false) {
			return false;
			unset($_SESSION['credito']);
		} else {
			$credit_result = add_credit($user_id, $novo_credito);
			if ($credit_result == true) {
				unset($_SESSION['credito']);
				header('Location:pagina.php?pagina_id=6');
			}
		}
	}
	
	function check_credit($novo_credito, $mode, $user_id)
	{
		if ($novo_credito == false) {
			return false;
		}
		if ($mode == false) {
			$mode = 'verify';
		}
		include 'templates/criar_conn.php';
		$query = "SELECT id, value FROM Creditos WHERE codigo = '$novo_credito' AND estado = 1";
		$check_creditos = $conn->query($query);
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
						$conn->query("UPDATE Creditos SET estado = 0, user_id = $user_id, data_uso = NOW() WHERE id = $credito_id");
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
		$credit_value = check_credit($credit_code, 'value', false);
		if ($credit_value != false) {
			include 'templates/criar_conn.php';
			$user_wallet = (int)return_wallet_value($user_id);
			$end_state = (int)($credit_value + $user_wallet);
			$check_query = $conn->query("INSERT INTO Transactions (user_id, direction, value, prevstate, endstate) values ($user_id, 'positive', $credit_value, $user_wallet, $end_state)");
			if ($check_query === true) {
				check_credit($credit_code, 'disable', $user_id);
			}
			return true;
		}
		return false;
	}
	
	function registrar_credito($codigo, $value)
	{
		if (($codigo == false) || ($value == false)) {
			return false;
		} else {
			include 'templates/criar_conn.php';
			$conn->query("INSERT INTO Creditos (codigo, value) VALUES ('$codigo', $value)");
			$check = $conn->insert_id;
			if ($check != false) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function calculate_review_price() {
		$args = func_get_args();
		$wordcount = $args[0];
		$extension = $args[1];
		$grade = $args[2];
		$chat = $args[3];
		$reviewer = $args[4];
		
		$sum = (int)0;
		
		if ($grade == 'with_grade') {
			$sum = ($sum + 15);
		}
		switch ($reviewer) {
			case 'professor_especialista':
			case 'revisor_diplomata':
				$simplified = 100;
				$detailed = 200;
				$rewrite = 300;
				$chat_20 = 100;
				$chat_40 = 200;
				$chat_60 = 300;
				break;
			default:
				$simplified = 60;
				$detailed = 130;
				$rewrite = 200;
				$chat_20 = 50;
				$chat_40 = 100;
				$chat_60 = 150;
		}
		
		switch ($extension) {
			case 'simplified':
			case 'detailed':
				$extension_price = $detailed;
				break;
			case 'rewrite':
				$extension_price = $rewrite;
				break;
			default:
				$extension_price = $simplified;
		}
		
		$sum = ($sum + $extension_price);
		
		if ($extension == 'simplified') {
			$sum = ($sum + $simplified);
		} elseif ($extension == 'detailed') {
			$sum = ($sum + $detailed);
		} else {
			return false;
		}
		if ($chat == 'chat_20') {
			$sum = ($sum + $chat_20);
		} elseif ($chat == 'chat_40') {
			$sum = ($sum + $chat_40);
		} elseif ($chat == 'chat_60') {
			$sum = ($sum + $chat_60);
		}
		
		$price = (int)($wordcount * $sum);
		$price = (int)($price / 600);
		$price = floor($price);
		return $price;
	}
	
	if (isset($_POST['trigger_review_send'])) {
		$extension = $_POST['extension'];
		if (isset($_POST['review_grade'])) {
			$review_grade = 'with_grade';
		} else {
			$review_grade = 'without_grade';
		}
		$reviewer_choice = $_POST['reviewer_choice'];
		$reviewer_chat = $_POST['reviewer_chat'];
		$new_review_comments = $_POST['new_review_comments'];
		$new_review_comments = mysqli_real_escape_string($conn, $new_review_comments);
		
		$order_review_pagina_id = $_POST['order_review_pagina_id'];
		$pagina_correcao_info = return_pagina_info($order_review_pagina_id);
		$pagina_correcao_texto_id = $pagina_correcao_info[1];
		$pagina_correcao_tipo = $pagina_correcao_info[2];
		if ($pagina_correcao_tipo == 'texto') {
			$correcao_template_id = 'anotacoes';
		} else {
			$correcao_template_id = 'verbete';
		}
		
		if ($pagina_correcao_texto_id != false) {
			$pagina_correcao_verbete_text = return_verbete_text($pagina_correcao_texto_id);
			$pagina_correcao_wordcount = str_word_count($pagina_correcao_verbete_text);
			$review_price = calculate_review_price($pagina_correcao_wordcount, $extension, $review_grade,
				$reviewer_chat, $reviewer_choice);
			
			$user_end_state = (int)($user_wallet - $review_price);
			if ($user_end_state > 0) {
				$check = $conn->query("INSERT INTO Transactions (user_id, direction, value, prevstate, endstate) VALUES ($user_id, 'negative', $review_price, $user_wallet, $user_end_state)");
				if ($check == true) {
					$check = $conn->query("INSERT INTO Orders (tipo, user_id, pagina_id, comments) VALUES ('review', $user_id, $order_review_pagina_id, '$new_review_comments')");
				}
			}
		}
	}
	

?>