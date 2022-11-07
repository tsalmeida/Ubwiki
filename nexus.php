<?php

	//TODO: Trazer o Nexus a um nível básico que permita o uso

	$pagina_tipo = 'nexus';
	include 'engine.php';

	if (!isset($_SESSION['user_nexus_pagina_id'])) {
		$pagina_id = return_pagina_id($user_id, 'nexus');
		$_SESSION['user_nexus_pagina_id'] = $pagina_id;
	} else {
		$pagina_id = $_SESSION['user_nexus_pagina_id'];
	}

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

	if (isset($_POST['nexus_new_folder_title'])) {
		$nexus_new_folder_title = $_POST['nexus_new_folder_title'];
		$nexus_new_folder_icon = $_POST['nexus_new_folder_icon'];
		$nexus_new_folder_color = $_POST['nexus_new_folder_color'];
		$user_nexus_id = $_SESSION['user_nexus_pagina_id'];
		$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $user_nexus_id, '$nexus_new_folder_title', '$nexus_new_folder_icon', '$nexus_new_folder_color')");
		$conn->query($query);
	}

	if (isset($_POST['nexus_del_folder_id'])) {
		$nexus_del_folder_id = $_POST['nexus_del_folder_id'];
		$query = prepare_query("DELETE FROM nexus_folders WHERE id = $nexus_del_folder_id AND user_id = $user_id AND pagina_id = $pagina_id");
		$conn->query($query);
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	$print_folders_large = false;
	$print_folders_small = false;
	$query = prepare_query("SELECT id, title, icon, color FROM nexus_folders WHERE user_id = $user_id AND pagina_id = $pagina_id");
	$nexus_folders_info = $conn->query($query);
	if ($nexus_folders_info->num_rows > 0) {
		while ($nexus_folder_info = $nexus_folders_info->fetch_assoc()) {
			$nexus_folder_id = $nexus_folder_info['id'];
			$nexus_folder_title = $nexus_folder_info['title'];
			$nexus_folder_icon = $nexus_folder_info['icon'];
			$nexus_folder_color = $nexus_folder_info['color'];
			$fa_icone = $nexus_folder_icon;
			$fa_color = $nexus_folder_color;
			$artefato_class = 'all_folder_icons';
			if ($fa_color == "link-dark") {
			    $fa_color_small = "link-light";
            } else {
			    $fa_color_small = $fa_color;
            }

			$artefato_id = "folder_large_{$nexus_folder_id}";
			$artefato_subtitulo = $nexus_folder_title;
			$fa_size = 'fa-4x';

			$print_folders_small .= "<a class='navbar-brand' href='#'><i class='$fa_icone $fa_color_small hidden'></i></a>";
			$print_folders_large .= include 'templates/artefato_item.php';

		}

	}

	$html_head_template_quill = true;
	$html_head_template_conteudo = false;
	$html_head_template_conteudo .= "
        <script type='text/javascript'>
          var user_id=$user_id;
          var user_email='$user_email';
        </script>
    ";

	$wallpaper_file = 'papyrus.webp';
	$wallpaper_repeat = 'repeat';

	$html_head_template_conteudo .= "
        <style>
            body {
                background-image: url('../wallpapers/$wallpaper_file');
                background-repeat: $wallpaper_repeat;
            }
        </style>
	";

	include 'templates/html_head.php';
	include 'templates/navbar.php';

?>
    <body id='nexus_background' class="bg-light">
    <div class="container mt-2">
        <h1 id="page_title" class="fontstack-mono text-center"><?php echo $pagina_titulo; ?></h1>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="mb-3 input-group input-group-lg">
                <input id="cmdbar" type="text" class="form-control text-center fontstack-mono" placeholder="<?php echo $user_apelido; ?> commands…">
            </div>
        </div>
    </div>
    <div id="settings_container" class="container">
        <div class="row d-flex justify-content-start bg-white border rounded p-1">
				<?php

					$template_conteudo = false;

					$artefato_id = 'show_folder_icons';
					$artefato_subtitulo = 'Folders';
					$artefato_class = 'mode_icons';
					$fa_icone = 'fa-folder-bookmark';
					$fa_color = 'link-warning';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'recent_links';
					$artefato_subtitulo = 'Recent links';
					$artefato_class = 'link_icons';
                    $fa_icone = 'fa-clock-rotate-left';
                    $fa_color = 'link-primary';
                    $fa_size = 'fa-4x';
                    $template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'show_setup_icons';
					$artefato_subtitulo = 'Settings';
					$artefato_class = 'mode_icons';
					$fa_icone = 'fa-sliders';
					$fa_color = 'link-secondary';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$template_conteudo .= "<hr id='hr_modes' class='mt-3'>";

					$artefato_id = 'manage_folders';
					$artefato_subtitulo = "Manage folders";
					$artefato_modal = '#modal_manage_folders';
					$artefato_class = "hidden nexus_settings_icon";
					$fa_icone = 'fa-folder-gear';
					$fa_color = 'link-warning';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'manage_links';
					$artefato_subtitulo = "Manage links";
					$artefato_modal = '#modal_manage_links';
					$artefato_class = "hidden nexus_settings_icon";
					$fa_icone = 'fa-bookmark';
					$fa_color = 'text-danger';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'manage_themes';
					$artefato_subtitulo = "Themes";
					$artefato_modal = '#modal_manage_themes';
					$artefato_class = "hidden nexus_settings_icon";
					$fa_icone = 'fa-swatchbook';
					$fa_color = 'link-purple';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'manage_timeline';
					$artefato_subtitulo = "Activity log";
					$artefato_modal = '#modal_manage_timeline';
					$artefato_class = "hidden nexus_settings_icon";
					$fa_icone = 'fa-list-timeline';
					$fa_color = 'link-info';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

					$artefato_id = 'manage_commands';
					$artefato_subtitulo = "Commands";
					$artefato_modal = "#modal_commands";
					$artefato_class = 'hidden nexus_settings_icon';
					$fa_icone = 'fa-rectangle-terminal';
					$fa_color = 'link-teal';
					$fa_size = 'fa-4x';
					$template_conteudo .= include 'templates/artefato_item.php';

                    $template_conteudo .= "<hr id='hr_settings' class='mt-3'>";

                    $template_conteudo .= $print_folders_large;

					$template_conteudo .= "<hr id='hr_folders' class='mt-3'>";

                    echo $template_conteudo;

				?>
        </div>
    </div>
	<?php
		$template_modal_div_id = 'modal_manage_folders';
		$template_modal_titulo = 'Manage folders';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "This will have a list of bookmark folders, with the option for each to be on quick access. It will also explain that links without folders will be in a link dump, not appearing as links";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_manage_links';
		$template_modal_titulo = 'Manage links';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo = "This will be the device to add links both to folders and as folderless, to a link dump.";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_manage_themes';
		$template_modal_titulo = 'Manage themes';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo = "This will give the user his choice of themes. Dark and light for starters.";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_manage_timeline';
		$template_modal_titulo = 'Manage timeline';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo = "This will be the log stuff, which was pretty good.";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_commands';
		$template_modal_titulo = 'The Nexus Command Bar';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo = "A full explanation of how the command bar works, a list of commands and the such.";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

	?>
    </body>
    <script type="text/javascript">
        $(document).on('keyup', '#cmdbar', function (e) {
            bar = $('#cmdbar').val();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                if (bar == '') {
                    alert('pressed enter on an empty command bar');
                }
            } else if (code == 'Escape') {
                $('#cmdbar').val('');
            }
        });
        $(document).on('click', '#trigger_suggest_title', function() {
            scan_new_link = $('#nexus_new_link_url').val();
            $.post('engine.php', {
                'scan_new_link': scan_new_link,
            }, function (data) {
                if (data != 0) {
                    $('#nexus_new_link_title').val('');
                    $('#nexus_new_link_title').val(data);
                }
            });
        })
        $("input:text:visible:first").focus();
        $(document).on('click', '#trigger_show_setup_icons', function () {
            $('#settings_container').find('.nexus_settings_icon').removeClass('hidden');
            $('#settings_container').find('.all_folder_icons').addClass('hidden');
        })

        $(document).on('click', '#trigger_show_folder_icons', function () {
            $('#settings_container').find('.nexus_settings_icon').addClass('hidden');
            $('#settings_container').find('.all_folder_icons').removeClass('hidden');
        })

        $(document).on('click', '#trigger_manage_folders', function () {
            $.post('engine.php', {
                'list_nexus_folders': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_folders').empty();
                    $('#body_modal_manage_folders').append(data);
                }
            });
        });

        $(document).on('click', '#trigger_manage_links', function () {
            $.post('engine.php', {
                'list_nexus_links': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_links').empty();
                    $('#body_modal_manage_links').append(data);
                }
            });
        });
    </script>
<?php

	include 'templates/html_bottom.php';
