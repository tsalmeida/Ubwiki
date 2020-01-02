<?php
	$elementos = $conn->query("SELECT estado, compartilhamento, criacao, tipo, titulo, autor, capitulo, ano, link, iframe, arquivo, resolucao, orientacao, comentario, trecho, user_id FROM Elementos WHERE id = $elemento_id");
	if ($elementos->num_rows > 0) {
		while ($elemento = $elementos->fetch_assoc()) {
			$elemento_estado = $elemento['estado'];
			$elemento_compartilhamento = $elemento['compartilhamento'];
			$elemento_criacao = $elemento['criacao'];
			$elemento_tipo = $elemento['tipo'];
			$elemento_titulo = $elemento['titulo'];
			$elemento_autor = $elemento['autor'];
			$elemento_capitulo = $elemento['capitulo'];
			$elemento_ano = $elemento['ano'];
			$elemento_link = $elemento['link'];
			$elemento_iframe = $elemento['iframe'];
			$elemento_arquivo = $elemento['arquivo'];
			$elemento_user_id = $elemento['user_id'];
			if ($elemento_compartilhamento != $pagina_compartilhamento) {
				$conn->query("UPDATE Paginas SET compartilhamento = '$elemento_compartilhamento', user_id = $elemento_user_id WHERE id = $pagina_id");
				$pagina_compartilhamento = $elemento_compartilhamento;
				if ($user_id != $elemento_user_id) {
					header('Location:pagina.php?pagina_id=4');
				}
			}
			$elemento_user_apelido = return_apelido_user_id($elemento_user_id);
			if ($elemento_user_apelido == false) {
				$elemento_user_apelido = '(usuário não-identificado)';
			}
		}
	}
?>