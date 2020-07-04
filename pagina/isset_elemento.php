<?php

	if (isset($_POST['trigger_subcategoria'])) {
		$trigger_subcategoria = $_POST['trigger_subcategoria'];
		$query = prepare_query("UPDATE Elementos SET subtipo = '$trigger_subcategoria' WHERE id = $elemento_id");
		$conn->query($query);
	}

	if (isset($_POST['submit_elemento_dados'])) {
		$elemento_mudanca_estado = 0;
		if (isset($_POST['elemento_mudanca_estado'])) {
			$elemento_mudanca_estado = 1;
		}
		$elemento_novo_titulo = "NULL";
		$elemento_novo_autor = "NULL";
		$elemento_novo_capitulo = "NULL";
		$elemento_novo_ano = "NULL";
		if (isset($_POST['elemento_novo_titulo'])) {
			$elemento_novo_titulo = $_POST['elemento_novo_titulo'];
			$elemento_novo_titulo = mysqli_real_escape_string($conn, $elemento_novo_titulo);
			if ($elemento_novo_titulo == '') {
				$elemento_novo_titulo = "NULL";
			} else {
				$elemento_novo_titulo = "'$elemento_novo_titulo'";
			}
		}
		if (isset($_POST['elemento_novo_autor'])) {
			$elemento_novo_autor = $_POST['elemento_novo_autor'];
			$elemento_novo_autor = mysqli_real_escape_string($conn, $elemento_novo_autor);
			if ($elemento_novo_autor == '') {
				$elemento_novo_autor = "NULL";
			} else {
				$elemento_novo_autor = "'$elemento_novo_autor'";
			}
		}
		if (isset($_POST['elemento_novo_capitulo'])) {
			$elemento_novo_capitulo = $_POST['elemento_novo_capitulo'];
			$elemento_novo_capitulo = mysqli_real_escape_string($conn, $elemento_novo_capitulo);
			if ($elemento_novo_capitulo == '') {
				$elemento_novo_capitulo = "NULL";
			} else {
				$elemento_novo_capitulo = "'$elemento_novo_capitulo'";
			}
		}
		if (isset($_POST['elemento_novo_ano'])) {
			$elemento_novo_ano = $_POST['elemento_novo_ano'];
			if ($elemento_novo_ano == '') {
				$elemento_novo_ano = "NULL";
			}
		}

		$query = prepare_query("UPDATE Elementos SET estado = $elemento_mudanca_estado, titulo = $elemento_novo_titulo, autor = $elemento_novo_autor, capitulo = $elemento_novo_capitulo, ano = $elemento_novo_ano WHERE id = $elemento_id");
		$update = $conn->query($query);
		update_etiqueta_elemento($elemento_id, $user_id);
		$query = prepare_query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $elemento_id, 'elemento_dados')");
		$conn->query($query);
		$nao_contar = true;

	}
?>