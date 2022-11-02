<?php

	//TODO: Trazer o Nexus a um nível básico que permita o uso

	$pagina_tipo = 'nexus';
	include 'engine.php';
	$pagina_id = return_pagina_id($user_id, 'nexus');
	if ($user_id != 1) {
		header('Location:ubwiki.php');
		exit();
	}
	if ($user_email == false) {
		header('Location:ubwiki.php');
		exit();
	}
	$pagina_info = return_pagina_info($pagina_id, true, true, true);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = (int)$pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = (int)$pagina_info[5];
		$pagina_titulo = $pagina_info[6];
		$pagina_etiqueta_id = (int)$pagina_info[7];
		$pagina_subtipo = $pagina_info[8];
		$pagina_publicacao = $pagina_info[9];
		$pagina_colaboracao = $pagina_info[10];
	} else {
		header('Location:ubwiki.php');
		exit();
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}
	$html_head_template_quill = true;
	$html_head_template_conteudo = "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";

	include 'templates/html_head.php';
	include 'templates/navbar.php';


?>
    <body class="grey lighten-5">
    <div class="container mt-5">
        <a href="javascript:void(0);"><h1 id="page_title" class="fontstack-mono text-center"><?php echo $pagina_titulo; ?></h1></a>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="md-form input-group input-group-lg">
                <input id="cmdbar" type="text" class="form-control text-center fontstack-mono" placeholder="<?php echo $user_apelido; ?> commands…">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-center mx-1">
            <div id="coluna_unica" class="col">
			<?php

				$template_conteudo_hidden = false;

				$template_id = 'nexus_main';
				$template_titulo = false;
				$template_conteudo = false;
				$template_conteudo_class = 'justify-content-start';
				$template_botoes = "<a id='show_hidden_icons' class='text-success' title='Add stuff'><i class='fad fa-grid-2-plus fa-fw fa-lg'></i></a>";
				$template_conteudo_no_col = true;

				$artefato_id = 'manage_folders';
				$artefato_subtitulo = "Manage folders";
				$artefato_modal = '#modal_nexus_1';
				$fa_icone = 'fa-folder-plus';
				$fa_color = 'text-warning';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_links';
				$artefato_subtitulo = "Manage links";
				$artefato_modal = '#modal_nexus_2';
				$fa_icone = 'fa-link';
				$fa_color = 'text-primary';
				$template_conteudo .= include 'templates/artefato_item.php';

				include 'templates/page_element.php';

            ?>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        $(document).on('keyup', '#cmdbar', function (e) {
            bar = $('#cmdbar').val();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                if (bar == '') {
                    alert('this happened');
                }
            } else if (code == 'Escape') {
                $('#cmdbar').val('');
            }
        });
        $("input:text:visible:first").focus();
    </script>
<?php
	include 'templates/html_bottom.php';