<?php
	$pagina_tipo = 'traducoes';
	include 'engine.php';
	include 'templates/html_head.php';
	$traduzir = false;
	if (isset($_GET['traduzir'])) {
		$traduzir = $_GET['traduzir'];
	}

	switch ($user_language) {
        case 'en':
            $_SESSION['chaves_en'] = $pagina_translated;
            break;
        case 'fr':
            $_SESSION['chaves_fr'] = $pagina_translated;
            break;
        case 'es':
            $_SESSION['chaves_es'] = $pagina_translated;
            break;
        case 'pt':
            $_SESSION['chaves_pt'] = $pagina_translated;
            break;
    }

	if (!isset($_SESSION['chaves_en'])) {
	    $chaves_en = translate_pagina('en');
	    $_SESSION['chaves_en'] = $chaves_en;
    } else {
	    $chaves_en = $_SESSION['chaves_en'];
    }
	if (!isset($_SESSION['chaves_pt'])) {
	    $chaves_pt = translate_pagina('pt');
	    $_SESSION['chaves_pt'] = $chaves_pt;
    } else {
	    $chaves_pt = $_SESSION['chaves_pt'];
    }
	if (!isset($_SESSION['chaves_fr'])) {
	    $chaves_fr = translate_pagina('fr');
	    $_SESSION['chaves_fr'] = $chaves_fr;
    } else {
	    $chaves_fr = $_SESSION['chaves_fr'];
    }
	if (!isset($_SESSION['chaves_es'])) {
	    $chaves_es = translate_pagina('es');
	    $_SESSION['chaves_es'] = $chaves_es;
    } else {
	    $chaves_es = $_SESSION['chaves_es'];
    }

	if (isset($_POST['nova_chave_titulo'])) {
		$nova_chave_titulo = $_POST['nova_chave_titulo'];
		adicionar_chave_traducao($nova_chave_titulo, $user_id);
	}
	
	if (isset($_POST['traduzir_chave_id'])) {
		$traduzir_chave_id = $_POST['traduzir_chave_id'];
		$traduzir_chave_string = $_POST['traduzir_chave_string'];
		adicionar_traducao($traduzir_chave_id, $traduzir, $traduzir_chave_string, $user_id);
	}

	$hide = 0;
	$hide_opposite = 1;
	if (isset($_GET['hide'])) {
		$hide = $_GET['hide'];
		if ($hide == true) {
			$hide_opposite = 0;
		} else {
			$hide_opposite = 1;
		}
	}

?>
<body class="bg-light">
<?php
	include 'templates/navbar.php';
?>
<div class="container-fluid">
    <div class="row d-flex justify-content-end p-2">
        <a class="text-primary ms-2" data-bs-toggle="modal" data-bs-target="#modal_chaves"><i
                    class="fad fa-key fa-2x fa-fw"></i></a>
        <a class="link-success ms-2" data-bs-toggle="modal" data-bs-target="#modal_selecionar_lingua"><i
                    class="fad fa-globe fa-2x fa-fw"></i></a>
        <a class="link-danger ms-2" href="<?php echo "traducoes.php?traduzir=$traduzir&hide=$hide_opposite"; ?>"><i
                    class="fad fa-check fa-2x fa-fw"></i></a>
    </div>
</div>
<div class="container">
	<?php
		$template_titulo = 'Traduções';
		$template_titulo_context = true;
		$template_titulo_no_nav = true;
		include 'templates/titulo.php';
	?>
</div>
<div class="container">
    <div class="row d-flex justify-content-center">
        <div id="coluna_unica" class="col">
					<?php
						
						if ($traduzir != false) {
							$template_id = 'traduzir_chaves';
							$template_titulo = "Traduzir chaves: $traduzir";
							$template_conteudo = false;
							$query = prepare_query("SELECT chave_id, traducao FROM Chaves_traduzidas WHERE lingua = '$traduzir'");
							$chaves = $conn->query($query);
							$chaves_traduzidas = array();
							if ($chaves->num_rows > 0) {
								while ($chave = $chaves->fetch_assoc()) {
									$chave_id = $chave['chave_id'];
									$chave_traducao = $chave['traducao'];
									$chaves_traduzidas[$chave_id] = $chave_traducao;
								}
							}
							$chaves_traduzidas_keys = array_keys($chaves_traduzidas);
							$query = prepare_query("SELECT id, chave FROM Translation_chaves ORDER BY id DESC");
							$chaves = $conn->query($query);
							if ($chaves->num_rows > 0) {
								$template_conteudo .= "<ul class='list-group list-group-flush'>";
								while ($chave = $chaves->fetch_assoc()) {
									$chave_id = $chave['id'];
									$chave_chave = $chave['chave'];
									if (in_array($chave_id, $chaves_traduzidas_keys)) {
										if ($hide == 0) {
											$list_color = 'border-success border';
											$list_content = $chaves_traduzidas[$chave_id];
										} else {
											continue;
										}
									} else {
										$list_color = 'list-group-item-light border';
										@$list_content = $chaves_pt[$chave_chave];
										if ($list_content == false) {
											$list_content = $chave['chave'];
										}
									}
									$template_conteudo .= "<a class='traduzir_chave_id mt-1' value='$chave_id'><li class='list-group-item list-group-item-action $list_color'>$list_content</li></a>";
								}
								$template_conteudo .= "</ul>";
							}
							include 'templates/page_element.php';
						}
					?>
        </div>
    </div>
</div>
<?php
	$template_modal_div_id = 'modal_chaves';
	$template_modal_titulo = 'Inserir chave';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<div class='mb-3'>
			<label for='nova_chave_titulo' class='form-label'>Título da nova chave</label>
			<input type='text' class='form-control' name='nova_chave_titulo' id='nova_chave_titulo'>
		</div>
	";
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_selecionar_lingua';
	$template_modal_titulo = 'Selecionar língua';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
							<p>Selecione uma língua para traduzir:</p>
							<ul class='list-group list-group-flush'>
								<a href='javascript:void(0);' value='pt' class='mt-1 selecionar_lingua'><li class='list-group-item list-group-item-action'>Português</li></a>
								<a href='javascript:void(0);' value='en' class='mt-1 selecionar_lingua'><li class='list-group-item list-group-item-action'>English</li></a>
								<a href='javascript:void(0);' value='es' class='mt-1 selecionar_lingua'><li class='list-group-item list-group-item-action'>Español</li></a>
								<a href='javascript:void(0);' value='fr' class='mt-1 selecionar_lingua'><li class='list-group-item list-group-item-action'>Français</li></a>
							</ul>
	";
	$template_modal_show_buttons = false;
	include 'templates/modal.php';
	
	$template_modal_div_id = 'modal_traduzir_chave';
	$template_modal_titulo = $pagina_translated['Traduzir chave'];
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
		<input type='hidden' name='traduzir_chave_id' id='traduzir_chave_id'>
		<ul class='list-group list-group-flush'>
		    <li class='list-group-item' id='chave_codigo'></li>
			<li class='list-group-item' id='chave_pt'></li>
			<li class='list-group-item' id='chave_en'></li>
			<li class='list-group-item' id='chave_es'></li>
			<li class='list-group-item' id='chave_fr'></li>
		</ul>
		<div class='mb-3'>
            <label for='traduzir_chave_string' class='form-label'>{$pagina_translated['Tradução para a língua']} \"$traduzir\"</label>
			<textarea class='md-textarea form-control' name='traduzir_chave_string' id='traduzir_chave_string' row='3'></textarea>
		</div>
	";
	include 'templates/modal.php';
	
	include 'pagina/modal_languages.php';


?>
</body>
<script type="text/javascript">
    $(document).on('click', '.selecionar_lingua', function () {
        var selecionar_lingua = $(this).attr('value');
        window.location.replace("traducoes.php?hide=<?php echo $hide; ?>&traduzir=" + selecionar_lingua)
    });
    $(document).on('click', '.traduzir_chave_id', function () {
        var traduzir_chave_id = $(this).attr('value');
        $('#traduzir_chave_id').val(traduzir_chave_id);
        $('#modal_traduzir_chave').modal('toggle');
        $.post('engine.php', {
            'return_chave_codigo': traduzir_chave_id,
        }, function (data) {
            if (data != 0) {
                $('#chave_codigo').empty();
                $(data).appendTo('#chave_codigo');
            } else {
                $('<span><strong>Código:</strong> <em class=\'text-muted\'>não encontrado</em></span>').appendTo('#chave_pt');
            }
        });
        $.post('engine.php', {
            'popular_traducao_chave_pt': traduzir_chave_id,
        }, function (data) {
            if (data != 0) {
                $('#chave_pt').empty();
                $(data).appendTo('#chave_pt');
            } else {
                $('<span><strong>Português:</strong> <em class=\'text-muted\'>não há tradução registrada</em></span>').appendTo('#chave_pt');
            }
        });
        $.post('engine.php', {
            'popular_traducao_chave_en': traduzir_chave_id,
        }, function (data) {
            if (data != 0) {
                $('#chave_en').empty();
                $(data).appendTo('#chave_en');
            } else {
                $('<span><strong>English:</strong> <em class=\'text-muted\'>não há tradução registrada</em></span>').appendTo('#chave_en');
            }
        });
        $.post('engine.php', {
            'popular_traducao_chave_es': traduzir_chave_id,
        }, function (data) {
            if (data != 0) {
                $('#chave_es').empty();
                $(data).appendTo('#chave_es');
            } else {
                $('<span><strong>Espanhol:</strong> <em class=\'text-muted\'>não há tradução registrada</em></span>').appendTo('#chave_es');
            }
        });
        $.post('engine.php', {
            'popular_traducao_chave_fr': traduzir_chave_id,
        }, function (data) {
            if (data != 0) {
                $('#chave_fr').empty();
                $(data).appendTo('#chave_fr');
            } else {
                $('<span><strong>Français:</strong> <em class=\'text-muted\'>não há tradução registrada</em></span>').appendTo('#chave_fr');
            }
        });
    });
</script>
<?php
	include 'templates/footer.html';
	include 'templates/html_bottom.php';
?>

