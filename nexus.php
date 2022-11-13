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

	$pagina_info = return_pagina_info($pagina_id, false, false, false);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = (int)$pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = (int)$pagina_info[5];
		$pagina_titulo = 'Nexus';
		$pagina_etiqueta_id = (int)$pagina_info[7];
		$pagina_subtipo = $pagina_info[8];
		$pagina_publicacao = false;
		$pagina_colaboracao = false;
	} else {
		header('Location:ubwiki.php');
		exit();
	}

	$nexus_theme = false;
	$nexus_title = false;
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
		if ($nexus_new_folder_icon == false) {
			$nexus_new_folder_icon = nexus_random_folder_icon();
		}
		if ($nexus_new_folder_color == false) {
			$nexus_new_folder_color = nexus_random_color();
		}

		$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, type, title, icon, color) VALUES ($user_id, $user_nexus_id, '$nexus_new_folder_type', '$nexus_new_folder_title', '$nexus_new_folder_icon', '$nexus_new_folder_color')");
		$conn->query($query);
	}

	if (isset($_POST['nexus_del_folder_id'])) {
		$nexus_del_folder_id = $_POST['nexus_del_folder_id'];
		$query = prepare_query("DELETE FROM nexus_folders WHERE id = $nexus_del_folder_id AND user_id = $user_id AND pagina_id = $pagina_id");
		$conn->query($query);
	}

	if (isset($_POST['nexus_new_link_submit'])) {
		$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'location' => $_POST['nexus_new_link_location'], 'url' => $_POST['nexus_new_link_url'], 'title' => $_POST['nexus_new_link_title'], 'icon' => $_POST['nexus_new_link_icon'], 'color' => $_POST['nexus_new_link_color']);
		nexus_add_link($params);
	}

	if (isset($_POST['nexus_theme_select'])) {
		$query = prepare_query("UPDATE nexus SET theme = '{$_POST['nexus_theme_select']}' WHERE user_id = $user_id");
		$conn->query($query);
		$nexus_theme = $_POST['nexus_theme_select'];
	}

	if (isset($_POST['new_bulk_folder_1'])) {
		if ($_POST['new_bulk_folder_1'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_1']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_2'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_2']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_3'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_3']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_4'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_4']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_5'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_5']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_6'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_6']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
		if ($_POST['new_bulk_folder_7'] != false) {
			$new_folder_random_icon = nexus_random_folder_icon();
			$new_folder_random_color = nexus_random_color();
			$query = prepare_query("INSERT INTO nexus_folders (user_id, pagina_id, title, icon, color) VALUES ($user_id, $pagina_id, '{$_POST['new_bulk_folder_7']}', '$new_folder_random_icon', '$new_folder_random_color')");
			$conn->query($query);
		}
	}

	if (isset($_POST['new_bulk_link_1'])) {
	    if (!isset($_POST['new_bulk_links_folder'])) {
	        $_POST['new_bulk_links_folder'] = false;
        }
		if ($_POST['new_bulk_link_1'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_1'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_2'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_2'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_3'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_3'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_4'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_4'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_5'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_5'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_6'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_6'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
		if ($_POST['new_bulk_link_7'] != false) {
			$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'url' => $_POST['new_bulk_link_7'], 'location' => $_POST['new_bulk_links_folder']);
			nexus_add_link($params);
		}
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	$print_folders_large = false;
	$navbar_custom_leftside = false;
	$navbar_custom_rightside = false;
	$navbar_custom_dropdown_start = false;
	$navbar_custom_dropdown_end = false;
	$html_bottom_folders = false;
	$query = prepare_query("SELECT id, title, icon, color, type FROM nexus_folders WHERE user_id = $user_id AND pagina_id = $pagina_id");
	$nexus_folders_info = $conn->query($query);
	$nexus_folder_order_identifier = 0;
	$nexus_main_folder_counter = 0;
	$nexus_archive_folder_counter = 2000;
	$nexus_linkdump_folder_counter = 1000;
	$nexus_folder_pairings = array();
	if ($nexus_folders_info->num_rows > 0) {
		while ($nexus_folder_info = $nexus_folders_info->fetch_assoc()) {
			$nexus_folder_id = $nexus_folder_info['id'];
			$nexus_folder_title = $nexus_folder_info['title'];
			$nexus_fa_icone = $nexus_folder_info['icon'];
			$nexus_fa_icone = nexus_convert_icon($nexus_fa_icone);
			$nexus_fa_icone = "fa-solid $nexus_fa_icone";
			$nexus_fa_color = $nexus_folder_info['color'];
			$nexus_fa_color = nexus_convert_color($nexus_fa_color);
			$nexus_fa_color = $nexus_fa_color[0];
			$nexus_folder_type = $nexus_folder_info['type'];
			$close_folders_container = false;
			switch ($nexus_folder_type) {
				case 'archival':
					$nexus_archive_folder_counter++;
					$nexus_folder_order_identifier = $nexus_archive_folder_counter;
					$nexus_artefato_class = 'archival_folder_icons d-none';
					$nexus_fa_size = 'fa-3x';
					break;
				case 'main':
					$nexus_main_folder_counter++;
					$nexus_folder_order_identifier = $nexus_main_folder_counter;
					$nexus_artefato_class = 'main_folder_icons d-none';
					$close_folders_container = "$('#folders_container').addClass('d-none');";
					$navbar_custom_leftside .= "<a class='nexus-navbar-button $nexus_fa_color' href='javascript:void(0);' id='trigger_folder_small_{$nexus_folder_order_identifier}'><i class='$nexus_fa_icone fa-fw'></i></a>";
			}
			$nexus_folder_pairings[$nexus_folder_id] = $nexus_folder_order_identifier;
			$nexus_artefato_id = "folder_large_{$nexus_folder_order_identifier}";
			$nexus_artefato_titulo = $nexus_folder_title;


			$print_folders_large .= include 'templates/nexus_artefato.php';

			$html_bottom_folders .= "
                function show_links_folder_{$nexus_folder_order_identifier}() {
                    $('#page_title').empty();
                    $('#page_title').append('{$nexus_folder_title}');
                    $('#cmd_container').addClass('d-none');
                    $('#links_container').removeClass('d-none');
                    $('.all_link_icons').addClass('d-none');
                    $('#settings_container').addClass('d-none');
                    $('.link_icon_{$nexus_folder_order_identifier}').removeClass('d-none');
                    $close_folders_container
                }
                $(document).on('click', '#trigger_folder_large_{$nexus_folder_order_identifier}', function () {
                    show_links_folder_{$nexus_folder_order_identifier}();
                })
                $(document).on('click', '#trigger_folder_small_{$nexus_folder_order_identifier}', function () {
                    show_links_folder_{$nexus_folder_order_identifier}();
                })";
			if ($nexus_folder_order_identifier < 10) {
				$html_bottom_folders .= "
                document.addEventListener ('keydown', function (zEvent) {
                    if (zEvent.altKey  &&  zEvent.key === '{$nexus_folder_order_identifier}') {
                        show_links_folder_{$nexus_folder_order_identifier}();
                    }
                });
			    ";
			}
		}
	}

	$_SESSION['cmd_links'] = array();
	$print_links = false;
	// links = (user_id, pagina_id, type, param_int_1:folder_id, param_int_2:link_id, param1:link_url, param2:link_title, param3:link_icon, param4:link_color)
	$query = prepare_query("SELECT param_int_1, param_int_2, param1, param2, param3, param4 FROM nexus_elements WHERE pagina_id = $pagina_id AND state = 1 AND type = 'link'");
	$nexus_links_info = $conn->query($query);
	if ($nexus_links_info->num_rows > 0) {
		while ($nexus_link_info = $nexus_links_info->fetch_assoc()) {
			$nexus_link_pasta_id = $nexus_link_info['param_int_1'];
			$nexus_link_id = $nexus_link_info['param_int_2'];
			$nexus_link_url = $nexus_link_info['param1'];
			$nexus_link_title = $nexus_link_info['param2'];
			$_SESSION['cmd_links'][$nexus_link_title] = $nexus_link_url;
			$fa_icone = $nexus_link_info['param3'];
			$fa_icone = nexus_convert_icon($fa_icone);
			$fa_icone = "fa-solid $fa_icone";
			$fa_color = $nexus_link_info['param4'];
			$fa_colors = nexus_convert_color($fa_color);
			$fa_color = $fa_colors[0];
			$fa_background = $fa_colors[1];
			if ($nexus_link_pasta_id == false) {
				$nexus_pasta_order_identifier = 9999;
			} else {
				$nexus_pasta_order_identifier = $nexus_folder_pairings[$nexus_link_pasta_id];
			}
			$print_links .= "<a id='link_{$nexus_link_id}' href='$nexus_link_url' target='_blank' class='all_link_icons rounded $fa_background py-4 px-4 me-1 mb-1 col-auto link_icon_{$nexus_pasta_order_identifier}'><span class='$fa_color'><i class='$fa_icone me-2'></i></span> <span class='link-light'>$nexus_link_title</span></a>";
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
			$wallpapers = array('ciprian-boiciuc-354944.jpg', 'dan-aragon-387264.jpg', 'jenu-prasad-347241.jpg', 'olivia-henry-296.jpg', 'dewang-gupta-Q7jQXMk-5qU-unsplash.jpg', 'ezra-jeffrey-357875.jpg');
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

?>
    <div class="container-fluid bg-dark">
        <div class="row">
            <div class="col-6 d-flex flex-row bd-highlight">
				<?php
					echo $navbar_custom_leftside;
				?>
            </div>
            <div class="col-6 d-flex flex-row-reverse bd-highlight">
                <a class='nexus-navbar-button nexus-link-green' href='escritorio.php'><i class='fas fa-lamp-desk fa-fw'></i></a>
                <a class='nexus-navbar-button nexus-link-orange' href='javascript:void(0)' id='trigger_show_setup_icons'><i class='fas fa-cog fa-fw'></i></a>
                <a class='nexus-navbar-button nexus-link-cyan' href='javascript:void(0);' id='trigger_show_recent_links'><i class='fas fa-clock-rotate-left fa-fw'></i></a>
                <a class='nexus-navbar-button nexus-link-teal' href='javascript:void(0);' id='trigger_show_link_dump'><i class='fas fa-box-archive fa-swap-opacity fa-fw'></i></a>
                <a class='nexus-navbar-button nexus-link-purple' href='javascript:void(0);' id='trigger_show_archival_folder_icons'><i class='fas fa-cabinet-filing fa-swap-opacity fa-fw'></i></a>
                <a class='nexus-navbar-button nexus-link-yellow' href='javascript:void(0);' id='trigger_show_main_folder_icons'><i class='fas fa-folder-bookmark fa-fw'></i></a>
				<?php
					echo $navbar_custom_rightside;
				?>
            </div>
        </div>
    </div>
    <body id='nexus_background' class="bg-light">
    <div class="container-fluid my-5">
        <div class="row justify-content-center">
            <div id="page_title" class="text-center nexus-title col-auto display-1 <?php echo $title_color; ?>"><?php echo $nexus_title; ?></div>
        </div>
    </div>
    <div id="cmd_container" class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="col">
                <div class="mb-3 input-group">
                    <input id="cmdbar" name="cmdbar" list="command-list" type="text" class="form-control font-monospace mx-1 cmd-bar rounded text-white bg-dark border-0" rows="1" autocomplete="off" spellcheck="false" placeholder="<?php echo $user_apelido; ?> commands…">
                    <datalist id="command-list">
						<?php
							foreach ($_SESSION['cmd_links'] as $key => $value) {
								echo "<option>$key</option>";
							}
						?>
                    </datalist>
                </div>
            </div>
        </div>
    </div>
	<?php
		$template_conteudo = false;
		$template_conteudo .= "<div id='settings_container' class='container d-none'><div id='settings_row' class='row'>";

		$nexus_artefato_id = 'manage_folders';
		$nexus_artefato_titulo = "Manage folders";
		$nexus_artefato_modal = '#modal_manage_folders';
		$nexus_artefato_class = "nexus_settings_icon";
		$nexus_fa_icone = 'fad fa-folder-gear';
		$nexus_fa_color = 'nexus-link-yellow';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$nexus_artefato_id = 'manage_links';
		$nexus_artefato_titulo = "Manage links";
		$nexus_artefato_modal = '#modal_manage_links';
		$nexus_artefato_class = "nexus_settings_icon";
		$nexus_fa_icone = 'fad fa-bookmark';
		$nexus_fa_color = 'nexus-link-red';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$nexus_artefato_id = 'manage_themes';
		$nexus_artefato_titulo = "Themes";
		$nexus_artefato_modal = '#modal_manage_themes';
		$nexus_artefato_class = "nexus_settings_icon";
		$nexus_fa_icone = 'fad fa-swatchbook';
		$nexus_fa_color = 'nexus-link-purple';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$nexus_artefato_id = 'manage_options';
		$nexus_artefato_titulo = "Options";
		$nexus_artefato_modal = '#modal_options';
		$nexus_artefato_class = "nexus_settings_icon";
		$nexus_fa_icone = 'fad fa-toggle-large-on';
		$nexus_fa_color = 'nexus-link-green';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$nexus_artefato_id = 'manage_timeline';
		$nexus_artefato_titulo = "Activity log";
		$nexus_artefato_modal = '#modal_manage_timeline';
		$nexus_artefato_class = "nexus_settings_icon";
		$nexus_fa_icone = 'fad fa-list-timeline';
		$nexus_fa_color = 'nexus-link-cyan';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$nexus_artefato_id = 'manage_commands';
		$nexus_artefato_titulo = "Commands";
		$nexus_artefato_modal = "#modal_commands";
		$nexus_artefato_class = 'nexus_settings_icon';
		$nexus_fa_icone = 'fad fa-rectangle-terminal';
		$nexus_fa_color = 'nexus-link-teal';
		$template_conteudo .= include 'templates/nexus_artefato.php';

		$template_conteudo .= "</div></div>";
		$template_conteudo .= "<div id='folders_container' class=\"container d-none\"><div id='folders_row' class='row'>";

		$template_conteudo .= $print_folders_large;

		$template_conteudo .= "</div></div>";
		$template_conteudo .= "<div id='links_container' class=\"container d-none\"><div id='links_row' class='row'>";

		$template_conteudo .= $print_links;
		$template_conteudo .= "</div></div>";
		echo $template_conteudo;
	?>
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
		$template_modal_body_conteudo = "
		    <ul>
		        <li>Press Esc to return to the original screen, with the command bar. You may also click the large text under the navbar.</li>
		        <li>Alt+1 to 9 will show the links from each of the main folders, in order.</li>
		        <li>Alt+A will show the archived links.</li>
		        <li>Alt+S will show the settings.</li>
		        <li>Alt+R will show recent links.</li>
            </ul>
		";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_add_folders_bulk';
		$template_modal_titulo = 'Add folders in bulk';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>You will be able to change the details later. For now, all you need is a name for each.</p>";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

		$template_modal_div_id = 'modal_add_links_bulk';
		$template_modal_titulo = 'Add links in bulk to the Link Dump';
		$template_modal_body_conteudo = false;
		$template_modal_body_conteudo .= "<p>You will be able to change the details later. For now, all you need is a name for each.</p>";
		$template_modal_show_buttons = false;
		include 'templates/modal.php';

	?>
    </body>
    <script type="text/javascript">
        function original_state() {
            $('#page_title').empty();
            $('#page_title').append('<?php echo $nexus_title; ?>');
            $('#cmdbar').val('');
            $('#folders_container').addClass('d-none');
            $('#settings_container').addClass('d-none');
            $('#links_container').addClass('d-none');
            $('#cmd_container').removeClass('d-none');
            $("input:text:visible:first").focus();
        }

        $(document).on('keyup', function (e) {
            var code = e.key;
            if (code == 'Escape') {
                original_state();
            }
        })

        $(document).on('click', '#page_title', function () {
            original_state();
        })

        $(document).on('keyup', '#cmdbar', function (e) {
            bar = $('#cmdbar').val();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                $.post('engine.php', {
                    'analyse_cmd_input': bar,
                }, function (data) {
                    if (data != 0) {
                        window.open(data, '_blank');
                        $("#cmdbar").val('');
                    }
                });
            }
        })

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

        $(document).on('click', '#show_modal_add_folders_bulk', function () {
            $.post('engine.php', {
                'populate_add_folders_bulk': true,
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_add_folders_bulk').empty();
                    $('#body_modal_add_folders_bulk').append(data);
                }
            })
        })

        $(document).on('click', '#show_modal_add_links_bulk', function () {
            $.post('engine.php', {
                'populate_add_links_bulk': true,
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_add_links_bulk').empty();
                    $('#body_modal_add_links_bulk').append(data);
                }
            })
        })

        function show_link_dump_icons() {
            $('#page_title').empty();
            $('#page_title').append('Link Dump');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
            $(document).find('#folders_container').addClass('d-none');
            $(document).find('#links_container').removeClass('d-none');
            $('#links_container').find('.all_link_icons').addClass('d-none');
            $('#links_container').find('.link_icon_9999').removeClass('d-none');
        }

        $(document).on('click', '#trigger_show_link_dump', function () {
            show_link_dump_icons();
        });

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'd') {
                event.preventDefault();
                show_link_dump_icons();
            }
        });

        $("input:text:visible:first").focus();

        function show_setup_icons() {
            $('#page_title').empty();
            $('#page_title').append('Settings');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').removeClass('d-none');
            $(document).find('#links_container').addClass('d-none');
            $('#folders_container').find('.main_folder_icons').addClass('d-none');
            $('#folders_container').find('.archival_folder_icons').addClass('d-none');
        }

        $(document).on('click', '#trigger_show_setup_icons', function () {
            show_setup_icons();
        })

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 's') {
                event.preventDefault();
                show_setup_icons();
            }
        });

        function show_main_folder_icons() {
            $('#page_title').empty();
            $('#page_title').append('Main folders');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
            $(document).find('#links_container').addClass('d-none');
            $('#folders_container').removeClass('d-none');
            $('#folders_container').find('.main_folder_icons').removeClass('d-none');
            $('#folders_container').find('.archival_folder_icons').addClass('d-none');
        }

        $(document).on('click', '#trigger_show_main_folder_icons', function () {
            show_main_folder_icons();
        })

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'f') {
                event.preventDefault();
                show_main_folder_icons();
            }
        });

        function show_archival_folder_icons() {
            $('#page_title').empty();
            $('#page_title').append('Archives');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
            $(document).find('#links_container').addClass('d-none');
            $('#folders_container').removeClass('d-none');
            $('#folders_container').find('.main_folder_icons').addClass('d-none');
            $('#folders_container').find('.archival_folder_icons').removeClass('d-none');
        }

        $(document).on('click', '#trigger_show_archival_folder_icons', function () {
            show_archival_folder_icons();
        })

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'a') {
                show_archival_folder_icons();
            }
        });

        function show_recent_links() {
            $('#page_title').empty();
            $('#page_title').append('Recent links');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
            $(document).find('#links_container').removeClass('d-none');
            $('#folders_container').find('.main_folder_icons').addClass('d-none');
            $('#folders_container').find('.archival_folder_icons').addClass('d-none');
            $('#links_container').removeClass('d-none');
            $('#links_container').find('.all_link_icons').removeClass('d-none');
        }

        $(document).on('click', '#trigger_show_recent_links', function () {
            show_recent_links();
        });

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'r') {
                show_recent_links();
            }
        });

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

		<?php
		echo $html_bottom_folders;
		?>

    </script>
<?php

	include 'templates/html_bottom.php';
