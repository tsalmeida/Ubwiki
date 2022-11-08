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

	$nexus_info = return_nexus_info_user_id($user_id);
	if ($nexus_info != false) {
		$nexus_id = $nexus_info[0];
		$nexus_timestamp = $nexus_info[1];
		$nexus_title = $nexus_info[3];
		$nexus_theme = $nexus_info[4];
	}

	if ($nexus_title == false) {
		$nexus_title = "Nexus";
	}

	if (isset($_POST['nexus_new_folder_title'])) {
		$nexus_new_folder_title = $_POST['nexus_new_folder_title'];
		$nexus_new_folder_icon = $_POST['nexus_new_folder_icon'];
		$nexus_new_folder_color = $_POST['nexus_new_folder_color'];
		if (isset($_POST['nexus_new_folder_type'])) {
			$nexus_new_folder_type = 'main';
		} else {
		    $nexus_new_folder_type = 'archival';
        }
		$user_nexus_id = $_SESSION['user_nexus_pagina_id'];
		$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, type, title, icon, color) VALUES ($user_id, $user_nexus_id, '$nexus_new_folder_type', '$nexus_new_folder_title', '$nexus_new_folder_icon', '$nexus_new_folder_color')");
		$conn->query($query);
	}

	if (isset($_POST['nexus_del_folder_id'])) {
		$nexus_del_folder_id = $_POST['nexus_del_folder_id'];
		$query = prepare_query("DELETE FROM nexus_folders WHERE id = $nexus_del_folder_id AND user_id = $user_id AND pagina_id = $pagina_id");
		$conn->query($query);
	}

	if (isset($_POST['nexus_new_link_submit'])) {
		if ($_POST['nexus_new_link_icon'] == 'random') {
			$_POST['nexus_new_link_icon'] = nexus_random_icon();
		}
		if ($_POST['nexus_new_link_color'] == 'random') {
			$_POST['nexus_new_link_color'] = nexus_random_color();
		}
		$query = prepare_query("INSERT INTO nexus_links (user_id, pagina_id, pasta_id, url, title, icon, color) VALUES ($user_id, $pagina_id, {$_POST['nexus_new_link_location']}, '{$_POST['nexus_new_link_url']}', '{$_POST['nexus_new_link_title']}', '{$_POST['nexus_new_link_icon']}', '{$_POST['nexus_new_link_color']}')");
		$conn->query($query);
	}

	if (isset($_POST['nexus_theme_select'])) {
		$query = prepare_query("UPDATE nexus SET theme = '{$_POST['nexus_theme_select']}' WHERE user_id = $user_id");
		$conn->query($query);
		$nexus_theme = $_POST['nexus_theme_select'];
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	$print_folders_large = false;
	$print_folders_small = false;
	$query = prepare_query("SELECT id, title, icon, color, type FROM nexus_folders WHERE user_id = $user_id AND pagina_id = $pagina_id");
	$nexus_folders_info = $conn->query($query);
	if ($nexus_folders_info->num_rows > 0) {
		while ($nexus_folder_info = $nexus_folders_info->fetch_assoc()) {
			$nexus_folder_id = $nexus_folder_info['id'];
			$nexus_folder_title = $nexus_folder_info['title'];
			$nexus_folder_icon = $nexus_folder_info['icon'];
			$nexus_folder_color = $nexus_folder_info['color'];
			$nexus_folder_type = $nexus_folder_info['type'];
			$fa_icone = $nexus_folder_icon;
			$fa_color = $nexus_folder_color;
			switch($nexus_folder_type) {
                case 'archival':
                    $artefato_class = 'archival_folder_icons hidden';
                    break;
                case 'hidden':
                    $artefato_class = 'hidden_folder_icons hidden';
                    break;
                default:
                    $artefato_class = 'main_folder_icons hidden';
            }
			$artefato_link_classes = 'bg-white rounded';
			$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
			$artefato_col_limit = 'col-auto';
			if ($fa_color == "link-dark") {
				$fa_color_small = "link-light";
			} else {
				$fa_color_small = $fa_color;
			}

			$artefato_id = "folder_large_{$nexus_folder_id}";
			$artefato_subtitulo = $nexus_folder_title;
			$fa_size = 'fa-4x';

			$print_folders_small .= "<a class='navbar-brand' href='javascript:void(0);'><i class='$fa_icone $fa_color_small'></i></a>";
			$print_folders_large .= include 'templates/artefato_item.php';

		}

	}

	$print_links = false;
	$query = prepare_query("SELECT id, pasta_id, url, title, icon, color FROM nexus_links WHERE pagina_id = $pagina_id AND state = 1 ORDER BY timestamp DESC");
	$nexus_links_info = $conn->query($query);
	if ($nexus_links_info->num_rows > 0) {
		while ($nexus_link_info = $nexus_links_info->fetch_assoc()) {
			$nexus_link_id = $nexus_link_info['id'];
			$nexus_link_pasta_id = $nexus_link_info['pasta_id'];
			$nexus_link_url = $nexus_link_info['url'];
			$nexus_link_title = $nexus_link_info['title'];
			$fa_icone = $nexus_link_info['icon'];
			$fa_color = $nexus_link_info['color'];
			$fa_background = return_background($fa_color);
			$print_links .= "<a id='link_{$nexus_link_id}' href='$nexus_link_url' target='_blank' class='all_link_icons rounded $fa_background py-2 px-3 me-1 mb-1 col-auto'><span class='$fa_color'><i class='$fa_icone me-2'></i></span> <span class='link-dark'>$nexus_link_title</span></a>";
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

	switch ($nexus_theme) {
		case 'light':
			$wallpapers = array('papyrus.webp', 'y-so-serious-white.png', 'what-the-hex.webp', 'cheap_diagonal_fabric.png', 'circles-and-roundabouts.webp', 'crossline-dots.webp', 'diagonal_striped_brick.webp', 'funky-lines.webp', 'gplaypattern.png', 'interlaced.png', 'natural_paper.webp', 'subtle_white_feathers.webp', 'topography.webp', 'webb.png', 'whirlpool.webp', 'white-waves.webp', 'worn_dots.webp', 'vertical-waves.webp');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'color-burn';
			$title_overlay_hover = 'difference';
			break;
		case 'dark':
			$wallpapers = array('tactile_noise.webp', 'black_lozenge.webp', 'dark_leather.webp', 'escheresque_ste.webp', 'papyrus-dark.webp', 'prism.png', 'tasky_pattern.webp', 'y-so-serious.png');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-light';
			$title_overlay = 'color-dodge';
			$title_overlay_hover = 'difference';
			break;
		case 'whimsical':
			$wallpapers = array('pink-flowers.webp', 'morocco.png', 'pool_table.webp', 'retina_wood.png', 'wheat.webp');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'overlay';
			$title_overlay_hover = 'difference';
			break;
		case 'landscape':
			$wallpapers = array('ciprian-boiciuc-354944.jpg', 'dan-aragon-387264.jpg', 'jenu-prasad-347241.jpg', 'uros-jovicic-450294.jpg');
			$wallpaper_repeat = 'no-repeat';
			$wallpaper_size = 'cover';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'overlay';
			$title_overlay_hover = 'difference';
			break;
		default:
			$wallpapers = array('papyrus.webp');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'overlay';
			$title_overlay_hover = 'difference';
			break;
	}

	$random_wallpaper = array_rand($wallpapers);
	$wallpaper_file = $wallpapers[$random_wallpaper];

	$html_head_template_conteudo .= "
        <style>
            html {
                height: 100%;
            }
            body {
                background-image: url('../wallpapers/$wallpaper_file');
                background-repeat: $wallpaper_repeat;
                background-size: $wallpaper_size;
                background-position: $wallpaper_position;
            }
            .nexus-title {
              font-size: 7vw;
              mix-blend-mode: $title_overlay;
              user-select: none;
              cursor: pointer;
              word-break: keep-all;
              line-height: normal;
            }
            
            .nexus-title:hover {
              mix-blend-mode: $title_overlay_hover;
            }
        </style>
	";

	include 'templates/html_head.php';
	include 'templates/navbar.php';

?>
    <body id='nexus_background' class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div id="page_title" class="fontstack-mono text-center nexus-title col-auto <?php echo $title_color; ?>"><?php echo $nexus_title; ?></div>
        </div>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="mb-3 input-group input-group-lg">
                <input id="cmdbar" type="text" class="form-control text-center fontstack-mono" placeholder="<?php echo $user_apelido; ?> commands…">
            </div>
        </div>
    </div>
    <div id="nexus_container" class="container">
        <div id="mode_board" class="row d-flex justify-content-start bg-transparent rounded p-1 mt-1">
            <!--            <div class="col-12">-->
			<?php

				$template_conteudo = false;

				$artefato_id = 'show_main_folder_icons';
				$artefato_subtitulo = 'Main folders';
				$artefato_class = 'mode_icons';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-folder-bookmark';
				$fa_color = 'link-warning';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'show_archival_folder_icons';
				$artefato_subtitulo = 'Archival folders';
				$artefato_class = 'mode_icons';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-cabinet-filing';
				$fa_color = 'link-purple';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'show_link_dump';
				$artefato_subtitulo = 'Link dump';
				$artefato_class = 'mode_icons';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-box-archive';
				$fa_color = 'link-teal';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'recent_links';
				$artefato_subtitulo = 'Recent links';
				$artefato_class = 'link_icons';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-clock-rotate-left';
				$fa_color = 'link-primary';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'show_setup_icons';
				$artefato_subtitulo = 'Settings';
				$artefato_class = 'mode_icons';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-sliders';
				$fa_color = 'link-secondary';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$template_conteudo .= "</div>";
				$template_conteudo .= "<div id='settings_board' class=\"row d-flex justify-content-start bg-transparent rounded p-1 mt-1 hidden\">";
				//				$template_conteudo .= "<hr id='hr_modes' class='mt-3'>";

				$artefato_id = 'manage_folders';
				$artefato_subtitulo = "Manage folders";
				$artefato_modal = '#modal_manage_folders';
				$artefato_class = "nexus_settings_icon";
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-folder-gear';
				$fa_color = 'link-warning';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_links';
				$artefato_subtitulo = "Manage links";
				$artefato_modal = '#modal_manage_links';
				$artefato_class = "nexus_settings_icon";
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-bookmark';
				$fa_color = 'text-danger';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_themes';
				$artefato_subtitulo = "Themes";
				$artefato_modal = '#modal_manage_themes';
				$artefato_class = "nexus_settings_icon";
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-swatchbook';
				$fa_color = 'link-purple';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_options';
				$artefato_subtitulo = "Options";
				$artefato_modal = '#modal_options';
				$artefato_class = "nexus_settings_icon";
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-gears';
				$fa_color = 'link-dark';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_timeline';
				$artefato_subtitulo = "Activity log";
				$artefato_modal = '#modal_manage_timeline';
				$artefato_class = "nexus_settings_icon";
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-list-timeline';
				$fa_color = 'link-info';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$artefato_id = 'manage_commands';
				$artefato_subtitulo = "Commands";
				$artefato_modal = "#modal_commands";
				$artefato_class = 'nexus_settings_icon';
				$artefato_link_classes = 'bg-white rounded';
				$artefato_col_limit = 'col-auto';
				$artefato_classes_detail = 'py-1 artefato rounded d-flex justify-content-center mt-1';
				$fa_icone = 'fa-rectangle-terminal';
				$fa_color = 'link-teal';
				$fa_size = 'fa-4x';
				$template_conteudo .= include 'templates/artefato_item.php';

				$template_conteudo .= "</div>";
				$template_conteudo .= "<div id='folders_board' class=\"row d-flex justify-content-start bg-transparent rounded p-1 mt-1\">";
				//				$template_conteudo .= "<hr id='hr_settings' class='mt-3'>";

				$template_conteudo .= $print_folders_large;

				$template_conteudo .= "</div>";
				$template_conteudo .= "<div id='links_board' class=\"row d-flex justify-content-start bg-transparent rounded p-3 mt-1 hidden\">";

				//				$template_conteudo .= "<hr id='hr_folders' class='mt-3'>";
				//                $template_conteudo .= "<div class='col'>";

				$template_conteudo .= $print_links;

				$template_conteudo .= "</div>";

				echo $template_conteudo;

			?>
            <!--            </div>-->
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
        $(document).on('click', '#trigger_suggest_title', function () {
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
            $('#nexus_container').find('#settings_board').removeClass('hidden');
            $('#nexus_container').find('#links_board').addClass('hidden');
            $('#folders_board').find('.main_folder_icons').addClass('hidden');
            $('#folders_board').find('.archival_folder_icons').addClass('hidden');
        })

        $(document).on('click', '#trigger_show_main_folder_icons', function () {
            $('#nexus_container').find('#settings_board').addClass('hidden');
            $('#nexus_container').find('#links_board').addClass('hidden');
            $('#folders_board').find('.main_folder_icons').removeClass('hidden');
            $('#folders_board').find('.archival_folder_icons').addClass('hidden');
        })

        $(document).on('click', '#trigger_show_archival_folder_icons', function () {
            $('#nexus_container').find('#settings_board').addClass('hidden');
            $('#nexus_container').find('#links_board').addClass('hidden');
            $('#folders_board').find('.main_folder_icons').addClass('hidden');
            $('#folders_board').find('.archival_folder_icons').removeClass('hidden');
        })

        $(document).on('click', '#trigger_recent_links', function () {
            $('#nexus_container').find('#settings_board').addClass('hidden');
            $('#nexus_container').find('#links_board').removeClass('hidden');
            $('#folders_board').find('.main_folder_icons').addClass('hidden');
            $('#folders_board').find('.archival_folder_icons').addClass('hidden');
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

        $(document).on('click', '#trigger_manage_themes', function () {
            $.post('engine.php', {
                'populate_themes_modal': '<?php echo $nexus_theme; ?>'
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_themes').empty();
                    $('#body_modal_manage_themes').append(data);
                }
            });
        });

    </script>
<?php

	include 'templates/html_bottom.php';
