<?php
	
	include 'engine.php';
	
	if (isset($_GET['tema'])) {
		$tema_id = $_GET['tema'];
	}
	
	if (isset($_GET['concurso'])) {
		$concurso = $_GET['concurso'];
	}
	
	$nao_contar = false;
	
	$result = $conn->query("SELECT sigla_materia, nivel, ordem, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND id = $tema_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$sigla_materia = $row['sigla_materia'];
			$nivel = $row['nivel'];
			$ordem = $row['ordem'];
			$nivel1 = $row['nivel1'];
			$nivel2 = $row['nivel2'];
			$nivel3 = $row['nivel3'];
			$nivel4 = $row['nivel4'];
			$nivel5 = $row['nivel5'];
		}
	}
	
	$result = $conn->query("SELECT materia FROM Materias WHERE concurso = '$concurso' AND estado = 1 AND sigla = '$sigla_materia' ORDER BY ordem");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$materia = $row["materia"];
		}
	}
	
	// VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
	
	$result = $conn->query("SELECT verbete_content, user_id FROM Verbetes WHERE tema_id = $tema_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$verbete_content = $row['verbete_content'];
		}
	} else {
		$verbete_content = false;
	}
	
	if (isset($_POST['quill_novo_verbete_html'])) {
		$novo_verbete_html = $_POST['quill_novo_verbete_html'];
		$novo_verbete_text = $_POST['quill_novo_verbete_text'];
		$novo_verbete_content = $_POST['quill_novo_verbete_content'];
		$novo_verbete_html = strip_tags($novo_verbete_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup>');
		$result = $conn->query("SELECT id FROM Verbetes WHERE tema_id = $tema_id");
		if ($result->num_rows > 0) {
			$conn->query("UPDATE Verbetes SET verbete_html = '$novo_verbete_html', verbete_text = '$novo_verbete_text', verbete_content = '$novo_verbete_content', user_id = '$user_id' WHERE tema_id = $tema_id");
			$conn->query("INSERT INTO Verbetes_arquivo (tema_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$tema_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
		} else {
			$conn->query("INSERT INTO Verbetes (tema_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$tema_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
			$conn->query("INSERT INTO Verbetes_arquivo (tema_id, verbete_html, verbete_text, verbete_content, user_id) VALUES ('$tema_id', '$novo_verbete_html', '$novo_verbete_text', '$novo_verbete_content', '$user_id')");
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'verbete')");
		$verbete_content = $novo_verbete_content;
		$nao_contar = true;
	}
	if ($verbete_content != false) {
		$verbete_content = urldecode($verbete_content);
	}
	
	// ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO ANOTACAO
	
	$result = $conn->query("SELECT anotacao_content FROM Anotacoes WHERE tema_id = $tema_id AND user_id = $user_id");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$anotacoes_content = $row['anotacao_content'];
		}
	} else {
		$anotacoes_content = '';
	}
	
	if (isset($_POST['quill_novo_anotacoes_html'])) {
		$novo_anotacoes_html = $_POST['quill_novo_anotacoes_html'];
		$novo_anotacoes_text = $_POST['quill_novo_anotacoes_text'];
		$novo_anotacoes_content = $_POST['quill_novo_anotacoes_content'];
		$novo_anotacoes_html = strip_tags($novo_anotacoes_html, '<p><li><ul><ol><h2><h3><blockquote><em><sup><b>');
		$result = $conn->query("SELECT id FROM Anotacoes WHERE tema_id = $tema_id AND user_id = $user_id");
		if ($result->num_rows > 0) {
			$conn->query("UPDATE Anotacoes SET anotacao_html = '$novo_anotacoes_html', anotacao_text = '$novo_anotacoes_text', anotacao_content = '$novo_anotacoes_content', user_id = '$user_id' WHERE tema_id = $tema_id");
			$conn->query("INSERT INTO Anotacoes_arquivo (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		} else {
			$conn->query("INSERT INTO Anotacoes (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
			$conn->query("INSERT INTO Anotacoes_arquivo (tema_id, anotacao_html, anotacao_text, anotacao_content, user_id) VALUES ('$tema_id', '$novo_anotacoes_html', '$novo_anotacoes_text', '$novo_anotacoes_content', '$user_id')");
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'verbete_anotacao')");
		$anotacoes_content = $novo_anotacoes_content;
		$nao_contar = true;
	}
	
	$anotacoes_content = urldecode($anotacoes_content);
	
	// IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM IMAGEM
	
	if (isset($_POST['nova_imagem_link'])) {
		$nova_imagem_link = $_POST['nova_imagem_link'];
		$nova_imagem_titulo = $_POST['nova_imagem_titulo'];
		adicionar_imagem($nova_imagem_link, $nova_imagem_titulo, $tema_id, $user_id, 'verbete');
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'topico_imagem')");
	}
	
	// REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA REFERENCIA
	
	if (isset($_POST['nova_referencia_titulo'])) {
		$nova_referencia_titulo = $_POST['nova_referencia_titulo'];
		$nova_referencia_autor = $_POST['nova_referencia_autor'];
		$nova_referencia_capitulo = $_POST['nova_referencia_capitulo'];
		$nova_referencia_ano = $_POST['nova_referencia_ano'];
		$nova_referencia_link = $_POST['nova_referencia_link'];
		$result = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
		if ($result->num_rows == 0) {
			$result = $conn->query("INSERT INTO Elementos (tipo, titulo, autor, capitulo, link, ano, user_id) VALUES ('referencia', '$nova_referencia_titulo', '$nova_referencia_autor', '$nova_referencia_capitulo', '$nova_referencia_link', '$nova_referencia_ano', '$user_id')");
			$result2 = $conn->query("SELECT id FROM Elementos WHERE titulo = '$nova_referencia_titulo'");
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					$nova_referencia_id = $row['id'];
					$insert = $conn->query("INSERT INTO Verbetes_elementos (tema_id, elemento_id, tipo, user_id) VALUES ($tema_id, $nova_referencia_id, 'referencia', $user_id)");
					break;
				}
			}
		} else {
			while ($row = $result->fetch_assoc()) {
				$nova_referencia_id = $row['id'];
				$insert = $conn->query("INSERT INTO Verbetes_elementos (tema_id, elemento_id, tipo, user_id) VALUES ($tema_id, $nova_referencia_id, 'referencia', $user_id)");
				break;
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'topico_referencia')");
	}
	
	// VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO VIDEO
	
	if (isset($_POST['novo_video_link'])) {
		$novo_video_link = $_POST['novo_video_link'];
		$novo_video_data = get_youtube($novo_video_link);
		if ($novo_video_data == false) {
			return false;
		}
		$novo_video_titulo = $novo_video_data['title'];
		$novo_video_autor = $novo_video_data['author_name'];
		$novo_video_thumbnail = $novo_video_data['thumbnail_url'];
		$novo_video_iframe = $novo_video_data['html'];
		$novo_video_iframe = base64_encode($novo_video_iframe);
		$result = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$id_video_preexistente = $row['id'];
				$insert = $conn->query("INSERT INTO Verbetes_elementos (tema_id, elemento_id, tipo, user_id) VALUES ($tema_id, $id_video_preexistente, 'youtube', $user_id)");
				break;
			}
		} else {
			$novo_youtube_thumbnail = adicionar_thumbnail_youtube($novo_video_thumbnail);
			$insert = $conn->query("INSERT INTO Elementos (tipo, titulo, autor, link, iframe, arquivo, user_id) VALUES ('video', '$novo_video_titulo', '$novo_video_autor', '$novo_video_link', '$novo_video_iframe', '$novo_youtube_thumbnail', $user_id)");
			$result = $conn->query("SELECT id FROM Elementos WHERE link = '$novo_video_link'");
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$novo_video_id = $row['id'];
					$insert = $conn->query("INSERT INTO Verbetes_elementos (tema_id, elemento_id, tipo, user_id) VALUES ($tema_id, $novo_video_id, 'video', $user_id)");
					break;
				}
			} else {
			}
		}
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'topico_video')");
	}
	
	// FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM FORUM
	
	if (isset($_POST['novo_comentario'])) {
		$novo_comentario = $_POST['novo_comentario'];
		$insert = $conn->query("INSERT INTO Forum (user_id, tema_id, comentario)  VALUES ($user_id, $tema_id, '$novo_comentario')");
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'topico_forum')");
	}
	
	// COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED COMPLETED
	
	$estado_estudo = false;
	$estudos = $conn->query("SELECT estado FROM Completed WHERE user_id = $user_id AND tema_id = $tema_id AND active = 1 ORDER BY id DESC");
	if ($estudos->num_rows > 0) {
		while ($row = $estudos->fetch_assoc()) {
			$estado_estudo = $row['estado'];
			break;
		}
	}
	
	// BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK BOOKMARK
	
	$tema_bookmark = false;
	$bookmark = $conn->query("SELECT bookmark FROM Bookmarks WHERE user_id = $user_id AND tema_id = $tema_id AND active = 1 ORDER BY id DESC");
	if ($bookmark->num_rows > 0) {
		while ($row = $bookmark->fetch_assoc()) {
			$tema_bookmark = $row['bookmark'];
			break;
		}
	}
	
	// HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD HTML HEAD
	
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var tema_id=$tema_id;
          var concurso='$concurso';
          var user_email='$user_email';
        </script>
    ";
	include 'templates/html_head.php';
	include 'templates/imagehandler.php';
	if ($nao_contar == false) {
		$conn->query("INSERT INTO Visualizacoes (user_id, page_id, tipo_pagina) VALUES ($user_id, $tema_id, 'topico')");
	}
?>
<body>
<?php
	include 'templates/navbar.php';
	$breadcrumbs = "
    <div class='d-block'><a href='index.php'>$concurso</a></div>
    <div class='d-block spacing0'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='materia.php?concurso=$concurso&sigla=$sigla_materia'>$materia</a></div>
  ";
	
	// VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS
	
	$result = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
	if ($nivel == 1) {
		$tema_titulo = $nivel1;
	} elseif ($nivel == 2) {
		$tema_titulo = $nivel2;
	} elseif ($nivel == 3) {
		$tema_titulo = $nivel3;
	} elseif ($nivel == 4) {
		$tema_titulo = $nivel4;
	} elseif ($nivel == 5) {
		$tema_titulo = $nivel5;
	}
	if ($nivel == 1) {
		$count = 0;
		$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row = $result->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel1 = $row['id'];
			$titulo_nivel1 = $row['nivel1'];
			$nivel_nivel1 = $row['nivel'];
			if ($nivel_nivel1 == 1) {
				if ($titulo_nivel1 == $nivel1) {
					$breadcrumbs .= "<div class='spacing1'>$fawesome$titulo_nivel1</div>";
					$count2 = 0;
					$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
					$result2 = $conn->query("SELECT id, nivel2 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel1 = '$nivel1' AND nivel = 2 ORDER BY ordem");
					while ($row2 = $result2->fetch_assoc()) {
						$id_nivel2 = $row2['id'];
						$titulo_nivel2 = $row2['nivel2'];
						$count2++;
						if ($count2 == 2) {
							$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
						}
						$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='verbete.php?concurso=$concurso&tema=$id_nivel2'>$titulo_nivel2</a></div>";
					}
				} else {
					$breadcrumbs .= "<div class='spacing1'>$fawesome<a href='verbete.php?concurso=$concurso&tema=$id_nivel1'>$titulo_nivel1</a></div>";
				}
			}
		}
	}
	if ($nivel > 1) {
		$result = $conn->query("SELECT id FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel1 = '$nivel1' AND nivel = 1 ORDER BY ordem");
		while ($row = $result->fetch_assoc()) {
			$id_nivel1 = $row['id'];
			$breadcrumbs .= "<div class='spacing1'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel1'>$nivel1</a></div>";
		}
	}
	if ($nivel == 2) {
		$result2 = $conn->query("SELECT id, nivel2 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel1 = '$nivel1' AND nivel = 2 ORDER BY ordem");
		$count = 0;
		$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row2 = $result2->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel2 = $row2['id'];
			$titulo_nivel2 = $row2['nivel2'];
			if ($titulo_nivel2 == $nivel2) {
				$breadcrumbs .= "<div class='spacing2'>$fawesome$nivel2</div>";
				$result3 = $conn->query("SELECT id, nivel3 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel = 3 AND nivel2 = '$nivel2' ORDER BY ordem");
				$count = 0;
				$fawesome3 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row3 = $result3->fetch_assoc()) {
					$id_nivel3 = $row3['id'];
					$titulo_nivel3 = $row3['nivel3'];
					$count++;
					if ($count == 2) {
						$fawesome3 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='verbete.php?concurso=$concurso&tema=$id_nivel3'>$titulo_nivel3</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='verbete.php?concurso=$concurso&tema=$id_nivel2'>$titulo_nivel2</a></div>";
			}
		}
	}
	if ($nivel > 2) {
		$result2 = $conn->query("SELECT id, nivel2 FROM Temas WHERE nivel1 = '$nivel1' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
		while ($row2 = $result2->fetch_assoc()) {
			$id_nivel2 = $row2['id'];
			$titulo_nivel2 = $row2['nivel2'];
			if ($titulo_nivel2 == $nivel2) {
				$breadcrumbs .= "<div class='spacing2'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel2'>$nivel2</a></div>";
				break;
			}
		}
	}
	if ($nivel == 3) {
		$result3 = $conn->query("SELECT id, nivel3 FROM Temas WHERE nivel2 = '$nivel2' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel = 3 ORDER BY ordem");
		$count = 0;
		$fawesome3 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row3 = $result3->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome3 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel3 = $row3['id'];
			$titulo_nivel3 = $row3['nivel3'];
			if ($titulo_nivel3 == $nivel3) {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3$nivel3</div>";
				$result4 = $conn->query("SELECT id, nivel4 FROM Temas WHERE concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel = 4 AND nivel3 = '$nivel3' ORDER BY ordem");
				$count = 0;
				$fawesome4 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row4 = $result4->fetch_assoc()) {
					$id_nivel4 = $row4['id'];
					$titulo_nivel4 = $row4['nivel4'];
					$count++;
					if ($count == 2) {
						$fawesome4 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='verbete.php?concurso=$concurso&tema=$id_nivel4'>$titulo_nivel4</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='verbete.php?concurso=$concurso&tema=$id_nivel3'>$titulo_nivel3</a></div>";
			}
		}
	}
	if ($nivel > 3) {
		$result3 = $conn->query("SELECT id, nivel3 FROM Temas WHERE nivel2 = '$nivel2' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
		while ($row3 = $result3->fetch_assoc()) {
			$id_nivel3 = $row3['id'];
			$titulo_nivel3 = $row3['nivel3'];
			if ($titulo_nivel3 == $nivel3) {
				$breadcrumbs .= "<div class='spacing3'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel3'>$titulo_nivel3</a></div>";
				break;
			}
		}
	}
	if ($nivel == 4) {
		$result4 = $conn->query("SELECT id, nivel4 FROM Temas WHERE nivel3 = '$nivel3' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia' AND nivel = 4 ORDER BY ordem");
		$count = 0;
		$fawesome4 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row4 = $result4->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome4 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel4 = $row4['id'];
			$titulo_nivel4 = $row4['nivel4'];
			if ($titulo_nivel4 == $nivel4) {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4$titulo_nivel4</div>";
			} else {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='verbete.php?concurso=$concurso&tema=$id_nivel4'>$titulo_nivel4</a></div>";
			}
		}
	}
	if ($nivel > 4) {
		$result4 = $conn->query("SELECT id, nivel4 FROM Temas WHERE nivel3 = '$nivel3' AND concurso = '$concurso' AND sigla_materia = '$sigla_materia' ORDER BY ordem");
		while ($row4 = $result4->fetch_assoc()) {
			$id_nivel4 = $row4['id'];
			$titulo_nivel4 = $row4['nivel4'];
			if ($titulo_nivel4 == $nivel4) {
				$breadcrumbs .= "<div class='spacing4'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?concurso=$concurso&tema=$id_nivel4'>$titulo_nivel4</a></div>";
				break;
			}
		}
	}
	if ($nivel == 5) {
		$breadcrumbs .= "<div class='spacing5'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i>$nivel5</div>";
	}
	
	// PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA PAGINA


?>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-lg-8 col-sm-12'>
            <div id='collapse_breadcrumbs' class='flex-column collapse'>
							<?php echo $breadcrumbs; ?>
            </div>
        </div>
        <div class='col-lg-4 col-sm-12'>
            <div class='text-right py-2'>
                <span id='verbetes_relacionados' class='mx-1' title='Verbetes relacionados' data-toggle='collapse'
                      href='#collapse_breadcrumbs'><a href='javascript:void(0);'><i
                                class='fal fa-chart-network fa-fw'></i></a></span>
                <!--<span id='simulados' class='mx-1' title='Simulados'><a href='javascript:void(0);'><i
                                class='fal fa-check-double fa-fw'></i></a></span>-->
                <span id='forum' title='Fórum' data-toggle='modal' data-target='#modal_forum'><a
                            href='javascript:void(0);'><i
                                class='fal fa-comments-alt fa-fw'></i></a></span>
							<?php
								if ($estado_estudo == false) {
									echo "
      <span id='add_completed' class='ml-1' title='Estudo completo' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
      <span id='remove_completed' class='ml-1 collapse' title='Desmarcar como completo' value='$tema_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
    ";
								} else {
									echo "
      <span id='add_completed' class='ml-1 collapse' title='Estudo completo' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-check-circle fa-fw'></i></a></span>
      <span id='remove_completed' class='ml-1' title='Desmarcar como completo' value='$tema_id'><a href='javascript:void(0);'><span class='text-success'><i class='fas fa-check-circle fa-fw'></i></span></span></a></span>
    ";
								}
								if ($tema_bookmark == false) {
									echo "
              <span id='add_bookmark' class='ml-1' title='Marcar para leitura' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1 collapse' title='Remover da lista de leitura' value='$tema_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								} else {
									echo "
              <span id='add_bookmark' class='ml-1 collapse' title='Marcar para leitura' value='$tema_id'><a href='javascript:void(0);'><i class='fal fa-bookmark fa-fw'></i></a></span>
              <span id='remove_bookmark' class='ml-1' title='Remover da lista de leitura' value='$tema_id'><a href='javascript:void(0);'><span class='text-danger'><i class='fas fa-bookmark fa-fw'></i></span></span></a></span>
            ";
								}
							?>
            </div>
        </div>
    </div>
</div>

<div id='page_height' class='container-fluid'>
	<?php
		$template_titulo_context = true;
		$template_titulo = $tema_titulo;
		include 'templates/titulo.php';
	?>
    <div class='row justify-content-around'>
        <div id='coluna_esquerda' class='col-lg-5 col-sm-12'>
					<?php
						//VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE VERBETE
						
						$template_id = 'verbete';
						$template_titulo = 'Verbete';
						$template_botoes = "
						<a href='historico_verbete.php?concurso=$concurso&tema=$tema_id' target='_blank'><i class='fal fa-history fa-fw'></i></a>
                <span class='verbete_editor_collapse collapse' id='travar_verbete' data-toggle='collapse'
                      data-target='.verbete_editor_collapse' title='travar para edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='verbete_editor_collapse collapse show' id='destravar_verbete' data-toggle='collapse'
                      data-target='.verbete_editor_collapse' title='permitir edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>
                        ";
						$template_conteudo = false;
						
						
						$template_quill_unique_name = 'verbete';
						$template_quill_initial_state = 'leitura';
						$template_quill_conteudo = $verbete_content;
						
						if ($verbete_content == false) {
							$template_conteudo .= "<p id='verbete_vazio'>Seja o primeiro a contribuir para a construção deste verbete.</p>";
						}
						$template_conteudo .= include 'templates/quill_form.php';
						include 'templates/page_element.php';
						
						//VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS VIDEOS
						
						$template_id = 'videos';
						$template_titulo = 'Vídeos e aulas';
						$template_botoes = "
                                  <a data-toggle='modal' data-target='#modal_videos_form' href=''>
                                    <i class='fal fa-plus-square fa-fw'></i>
                                  </a>
                        ";
						$template_conteudo = false;
						
						$result = $conn->query("SELECT elemento_id FROM Verbetes_elementos WHERE tema_id = $tema_id AND tipo = 'video'");
						$count = 0;
						if ($result->num_rows > 0) {
							$template_conteudo .= "
                                <div id='carousel-videos' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                <div class='carousel-inner' role='listbox'>
                            ";
							$active = 'active';
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, autor, arquivo FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row = $result2->fetch_assoc()) {
										$count++;
										$video_titulo = $row['titulo'];
										$video_autor = $row['autor'];
										$video_arquivo = $row['arquivo'];
										$template_conteudo .= "
                                <div class=' carousel-item $active text-center'>
                                  <figure class='col-12'>
                                    <a href='elemento.php?id=$elemento_id' target='_blank'>
                                      <img src='/../imagens/youthumb/$video_arquivo'
                                        class='img-fluid' style='height:300px'>
                                    </a>";
										$template_conteudo .= "<figcaption>
                                           <strong class='h5-responsive mt-2'>$video_titulo</strong>";
										$template_conteudo .= "<p>$video_autor</p>";
										$template_conteudo .= "</figcaption>";
										
										$template_conteudo .= "</figure>
                                </div>
                                ";
										$active = false;
										break;
									}
								}
							}
							if ($count != 1) {
								$template_conteudo .= "
                            </div>
                              <div class='controls-top'>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-videos' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-videos' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                            ";
							}
							$template_conteudo .= "</div></div>";
						} else {
						    $template_load_invisible = true;
							$template_conteudo .= "<p>Não foram acrescentados, até o momento, vídeos ou aulas sobre este tópico.</p>";
						}
						include 'templates/page_element.php';
						
						//LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS LEIA MAIS
						
						$template_id = 'bibliografia';
						$template_titulo = 'Leia mais';
						$template_botoes = "<a data-toggle='modal' data-target='#modal_referencia_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>";
						$template_conteudo = false;
						
						$result = $conn->query("SELECT elemento_id FROM Verbetes_elementos WHERE tema_id = $tema_id AND tipo = 'referencia'");
						if ($result->num_rows > 0) {
							$template_conteudo .= "<ul class='list-group'>";
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, autor, capitulo, ano, link FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row = $result2->fetch_assoc()) {
										$referencia_titulo = $row['titulo'];
										$referencia_autor = $row['autor'];
										$referencia_capitulo = $row['capitulo'];
										$referencia_ano = $row['ano'];
										$referencia_link = $row['link'];
										$template_conteudo .= "<li class='list-group-item'><a href='elemento.php?id=$elemento_id' target='_blank'>$referencia_titulo : $referencia_autor : $referencia_capitulo : $referencia_ano</a></li>";
									}
								}
							}
							$template_conteudo .= "</ul>";
						} else {
						    $template_load_invisible = true;
							$template_conteudo .= "<p>Associe referências bibliográficas sobre este tema.</p>";
						}
						
						include 'templates/page_element.php';
						
						// IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS IMAGENS
						
						$template_id = 'imagens';
						$template_titulo = 'Imagens';
						$template_botoes = "<a data-toggle='modal' data-target='#modal_imagens_form' href=''><i class='fal fa-plus-square fa-fw'></i></a>";
						$template_conteudo = false;
						
						$result = $conn->query("SELECT elemento_id FROM Verbetes_elementos WHERE tema_id = $tema_id AND tipo = 'imagem'");
						$count = 0;
						if ($result->num_rows > 0) {
							$template_conteudo .= "
                                <div id='carousel-imagens' class='carousel slide carousel-multi-item mb-0' data-ride='carousel'>
                                <div class='carousel-inner' role='listbox'>
                            ";
							$active = 'active';
							while ($row = $result->fetch_assoc()) {
								$elemento_id = $row['elemento_id'];
								$result2 = $conn->query("SELECT titulo, arquivo FROM Elementos WHERE id = $elemento_id");
								if ($result2->num_rows > 0) {
									while ($row = $result2->fetch_assoc()) {
										$count++;
										$imagem_titulo = $row['titulo'];
										$imagem_arquivo = $row['arquivo'];
										$template_conteudo .= "
                                <div class=' carousel-item $active text-center'>
                                  <figure class='col-12'>
                                    <a href='elemento.php?id=$elemento_id' target='_blank'>
                                      <img src='/../imagens/verbetes/thumbnails/$imagem_arquivo'
                                        class='img-fluid' style='height:300px'>
                                    </a>";
										$template_conteudo .= "<figcaption>
                                           <strong class='h5-responsive mt-2'>$imagem_titulo</strong>";
										$template_conteudo .= "</figcaption>";
										
										$template_conteudo .= "</figure>
                                </div>
                                ";
										$active = false;
										break;
									}
								}
							}
							if ($count != 1) {
								$template_conteudo .= "
                            </div>
                              <div class='controls-top'>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='prev'><i style='transform: translateY(70%)' class='fas fa-chevron-left'></i></a>
                                <a class='btn btn-floating grey lighten-3 z-depth-0' href='#carousel-imagens' data-slide='next'><i style='transform: translateY(70%)' class='fas fa-chevron-right'></i></a>
                            ";
							}
							$template_conteudo .= "</div></div>";
						} else {
						    $template_load_invisible = true;
							$template_conteudo .= "<p>Ainda não foram associadas imagens a este verbete.</p>";
						}
						include 'templates/page_element.php';
					?>

        </div>

        <!-- COLUNA DIREITA COLUNA DIRETA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA COLUNA DIREITA -->

        <div id='coluna_direita' class='col-lg-5 col-sm-12 anotacoes_collapse collapse show'>
					
					<?php
						$template_id = 'sticky_anotacoes';
						$template_titulo = 'Anotações privadas';
						$template_botoes = "<span class='anotacoes_editor_collapse collapse show' id='travar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='travar para edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-open-alt fa-fw'></i></a></span>
                <span class='anotacoes_editor_collapse collapse' id='destravar_anotacao' data-toggle='collapse'
                      data-target='.anotacoes_editor_collapse' title='permitir edição'><a
                            href='javascript:void(0);'><i class='fal fa-lock-alt fa-fw'></i></a></span>";
						
						$template_quill_unique_name = 'anotacoes';
						$template_quill_initial_state = 'edicao';
						$template_quill_conteudo = $anotacoes_content;
						
						$template_conteudo = include 'templates/quill_form.php';
						include 'templates/page_element.php';
					
					?>

        </div>
    </div>
    <button id='mostrar_coluna_direita' class='btn btn-md elegant-color text-white p-2 m-1' tabindex='-1'><i
                class='fas fa-pen-alt fa-fw'></i></button>
</div>
<?php
	
	//IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL IMAGENS MODAL
	
	$template_modal_div_id = 'modal_imagens_form';
	$template_modal_titulo = 'Adicionar imagem';
	$template_modal_body_conteudo = "
        <div class='md-form mb-2'>
        <input type='url' id='nova_imagem_link' name='nova_imagem_link'
               class='form-control validate' required>
        <label data-error='preenchimento incorreto' data-success='preenchimento válido'
               for='nova_imagem_link'>Link para a imagem</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_imagem_titulo' name='nova_imagem_titulo'
                   class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_imagem_titulo'>Título da imagem</label>
        </div>
    ";
	include 'templates/modal.php';
	
	//LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL LEIA MAIS MODAL
	
	$template_modal_div_id = 'modal_referencia_form';
	$template_modal_titulo = 'Adicionar material de leitura';
	$template_modal_body_conteudo = "
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_titulo' name='nova_referencia_titulo'
                   class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_referencia_titulo'>Título da obra</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_autor' name='nova_referencia_autor'
                   class='form-control validate' required>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_referencia_autor'>Nome do autor</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_capitulo' name='nova_referencia_capitulo'
                   class='form-control validate'>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_referencia_capitulo'>Capítulo (opcional)</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_ano' name='nova_referencia_ano'
                   class='form-control validate'>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_referencia_ano'>Ano (opcional)</label>
        </div>
        <div class='md-form mb-2'>
            <input type='text' id='nova_referencia_link' name='nova_referencia_link'
                   class='form-control validate'>
            <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                   for='nova_referencia_link'>Link (opcional)</label>
        </div>
	";
	
	include 'templates/modal.php';
	
	// VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL VIDEOS MODAL
	
	$template_modal_div_id = 'modal_videos_form';
	$template_modal_titulo = 'Adicionar vídeo ou aula';
	$template_modal_body_conteudo = "
                    <p>No momento, apenas vídeos do Youtube podem ser adicionados. Outras opções estarão disponíveis em breve.</p>
                    <div class='md-form mb-2'>
                        <input type='url' id='novo_video_link' name='novo_video_link' class='form-control validate'
                               required>
                        <label data-error='preenchimento incorreto' data-success='preenchimento válido'
                               for='novo_video_link'>Link para o vídeo</label>
                    </div>
	";
	
	include 'templates/modal.php';
	
	// FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL FORUM MODAL
	
	$template_modal_div_id = 'modal_forum';
	$template_modal_titulo = 'Fórum';
	$template_modal_body_conteudo = false;
	
	$result = $conn->query("SELECT timestamp, comentario, user_id FROM Forum WHERE tema_id = $tema_id");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "<ul class='list-group'>";
		while ($row = $result->fetch_assoc()) {
			$timestamp_comentario = $row['timestamp'];
			$texto_comentario = $row['comentario'];
			$autor_comentario = $row['user_id'];
			$result2 = $conn->query("SELECT apelido FROM usuarios WHERE id = $autor_comentario");
			while ($row2 = $result2->fetch_assoc()) {
				$autor_comentario = $row2['apelido'];
				break;
			}
			$template_modal_body_conteudo .= "<li class='list-group-item'>
                                                <p><strong>$autor_comentario</strong> <span class='text-muted'><small>escreveu em $timestamp_comentario</small></span></p>
                                                $texto_comentario
                                              </li>";
		}
		$template_modal_body_conteudo .= "</ul>";
	} else {
		$template_modal_body_conteudo .= "<p><strong>Não há comentários sobre este tópico.</strong></p>";
	}
	$result = $conn->query("SELECT apelido FROM Usuarios WHERE id = $user_id AND apelido IS NOT NULL");
	if ($result->num_rows > 0) {
		$template_modal_body_conteudo .= "
                <div class='md-form mb-2'>
                    <p>Adicione seu comentário:</p>
                    <input type='text' id='novo_comentario' name='novo_comentario' class='form-control validate' required></input>
                    <label data-error='preenchimento incorreto' data-success='preenchimento válido' for='novo_comentario'></label>
                </div>
            ";
	} else {
		$template_modal_body_conteudo .= "<p class='mt-3'><strong>Para adicionar um comentário, você precisará definir seu apelido em sua <a href='usuario.php' target='_blank'>página de usuário</a>.</strong></p>";
	}
	
	include 'templates/modal.php';

?>


</div>
</body>
<?php
	include 'templates/footer.html';
	if ($verbete_content == false) {
		$verbete_vazio = true;
	}
	include 'templates/html_bottom.php';
	include 'templates/sticky_anotacoes.html';
	include 'templates/bookmarks.php';
	include 'templates/completed.php';
	include 'templates/carousel.html';
	include 'templates/lock_unlock_quill.php';
?>

</html>
