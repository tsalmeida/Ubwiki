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
    <body class="bg-light">
    <div class="container mt-5">
        <a href="javascript:void(0);"><h1 id="page_title" class="fontstack-mono text-center"><?php echo $pagina_titulo; ?></h1></a>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="mb-3 input-group input-group-lg">
                <input id="cmdbar" type="text" class="form-control text-center fontstack-mono" placeholder="<?php echo $user_apelido; ?> commands…">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-center mx-1">
            <div id="coluna_unica" class="col">
			<?php

                $template_conteudo = false;

				$template_id = 'nexus_board';
				$template_titulo = false;
				$template_conteudo = false;
				$template_conteudo_class = 'justify-content-start';
				$template_botoes = "<a id='show_setup_icons' class='text-muted' title='Settings'><i class='fad fa-sliders-simple fa-fw fa-lg'></i></a>";
				$template_botoes .= "<a id='hide_setup_icons' class='text-muted hidden' title='Back home'><i class='fad fa-house-person-return fa-fw fa-lg'></i></a>";
				$template_conteudo_no_col = true;

				$artefato_id = 'manage_folders';
				$artefato_subtitulo = "Folders";
				$artefato_modal = '#manage_folders';
				$artefato_class = "hidden nexus_settings_icon";
				$fa_icone = 'fa-folder-gear';
				$fa_color = 'link-warning';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_links';
				$artefato_subtitulo = "Links";
				$artefato_modal = '#modal_manage_links';
				$artefato_class = "hidden nexus_settings_icon";
				$fa_icone = 'fa-link';
				$fa_color = 'text-primary';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_bookmarks';
				$artefato_subtitulo = "Bookmarks";
				$artefato_modal = '#modal_manage_bookmarks';
				$artefato_class = "hidden nexus_settings_icon";
				$fa_icone = 'fa-folder-bookmark';
				$fa_color = 'link-danger';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_themes';
				$artefato_subtitulo = "Themes";
				$artefato_modal = '#modal_manage_themes';
				$artefato_class = "hidden nexus_settings_icon";
				$fa_icone = 'fa-swatchbook';
				$fa_color = 'link-purple';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'timeline';
				$artefato_subtitulo = "Activity log";
				$artefato_modal = '#modal_timeline';
				$artefato_class = "hidden nexus_settings_icon";
				$fa_icone = 'fa-list-timeline';
				$fa_color = 'link-success';
				$template_conteudo .= include 'templates/artefato_item.php';

				include 'templates/page_element.php';

            ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row d-flex justify-content-center mx-1">
            <div id="coluna_unica" class="col">
                <?php

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
        $(document).on('click', '#show_setup_icons', function () {
            $('#show_setup_icons').addClass('hidden')
            $('#hide_setup_icons').removeClass('hidden')
            $('#nexus_board').find('.nexus_settings_icon').removeClass('hidden');
        })
        $(document).on('click', '#hide_setup_icons', function () {
            $('#hide_setup_icons').addClass('hidden')
            $('#show_setup_icons').removeClass('hidden')
            $('#nexus_board').find('.nexus_settings_icon').addClass('hidden');
        })
    </script>
<?php
	include 'templates/html_bottom.php';