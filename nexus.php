<?php

	//TODO: Clicar em "link dump" no modal de links deveria fechar o modal.
	//TODO: Forma de alterar icones, cores e títulos de todos os elementos.
	//TODO: Forma de alterar a ordem dos elementos.
	//TODO: Forma de criar themes e selecioná-los.
	//TODO: Ferramentas básicas: bloco de nots, to-do list etc.
	//TODO: Fake RNG: na verdade, todas as combinações de ícone e cor devem se exaurir, quando em aleatório, antes que uma se repita.
	//TODO: Não retornar à Ubwiki, mas pedir login. Para que funcione como uma Startpage de fato.
	//TODO: Alguma integração com uma extensão do Chrome? Quem sabe?
	//TODO: Algum elemento crypto ou inteligência artificial?
	//TODO: Usar o curl, há possibilidades interessantes, como pesquisar e ver os resultados em baixo. Por exemplo, ver apenas os trending topics do Twitter na própria página, com um comando. Wikipédia, etc.
	//TODO: Um Travelogue, extensão do Plano de Estudos da Ubwiki. Talvez como próximo projeto grande.
	//TODO: E se fosse possível jogar o Chess Battles dentro da página, uma jogada por vez?
	//TODO: A página poderia recordar a jogar Wordle, por exemplo.
	//TODO: Links com maior e menor proeminência, alguns apenas com links, outros sem cor, outros em letras menores.
    //TODO: Detect dead links and make them a different color on the scren.

	$pagina_tipo = 'nexus';
	include 'engine.php';

	if ((!isset($_SESSION['user_nexus_pagina_id'])) || ($_SESSION['user_nexus_pagina_id'] == false)) {
		$pagina_id = return_pagina_id($user_id, 'nexus');
		$_SESSION['user_nexus_pagina_id'] = $pagina_id;
	} else {
		$pagina_id = $_SESSION['user_nexus_pagina_id'];
	}

	$_SESSION['nexus_options'] = nexus_options(array('mode' => 'read', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
	if (count($_SESSION['nexus_options']) == 0) {
		nexus_options(array('mode'=>'create', 'pagina_id'=>$_SESSION['user_nexus_pagina_id']));
		$_SESSION['nexus_options'] = nexus_options(array('mode' => 'read', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
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
	    if (!isset($_POST['nexus_new_folder_icon'])) {
	        $_POST['nexus_new_folder_icon'] = false;
        }
	    if (!isset($_POST['nexus_new_folder_color'])) {
	        $_POST['nexus_new_folder_color'] = false;
        }
	    if (!isset($_POST['nexus_new_folder_type'])) {
	        $_POST['nexus_new_folder_type'] = 'archival';
        } else {
	        $_POST['nexus_new_folder_type'] = 'main';
        }
	    if (!isset($_POST['nexus_new_folder_title'])) {
	        $_POST['nexus_new_folder_title'] = false;
        }
	    nexus_new_folder(array('user_id'=>$_SESSION['user_id'], 'pagina_id'=>$_SESSION['user_nexus_pagina_id'], 'title'=>$_POST['nexus_new_folder_title'], 'icon'=>$_POST['nexus_new_folder_icon'], 'color'=>$_POST['nexus_new_folder_color'], 'type'=>$_POST['nexus_new_folder_type']));
		unset($_SESSION['nexus_links']);
	}

	if (isset($_POST['nexus_del_folder_id'])) {
		$nexus_del_folder_id = $_POST['nexus_del_folder_id'];
		$query = prepare_query("DELETE FROM nexus_folders WHERE id = $nexus_del_folder_id AND user_id = $user_id AND pagina_id = $pagina_id");
		$conn->query($query);
		unset($_SESSION['nexus_links']);
	}

	if (isset($_POST['nexus_new_link_submit'])) {
		$params = array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'location' => $_POST['nexus_new_link_location'], 'url' => $_POST['nexus_new_link_url'], 'title' => $_POST['nexus_new_link_title'], 'icon' => $_POST['nexus_new_link_icon'], 'color' => $_POST['nexus_new_link_color']);
		nexus_add_link($params);
		unset($_SESSION['nexus_links']);
	}

	if (isset($_POST['nexus_theme_select'])) {
		$query = prepare_query("UPDATE nexus SET theme = '{$_POST['nexus_theme_select']}' WHERE user_id = $user_id");
		$conn->query($query);
		$nexus_theme = $_POST['nexus_theme_select'];
	}

	if (isset($_POST['new_bulk_folder_1'])) {
		if ($_POST['new_bulk_folder_1'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_1']));
		}
		if ($_POST['new_bulk_folder_2'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_2']));
		}
		if ($_POST['new_bulk_folder_3'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_3']));
		}
		if ($_POST['new_bulk_folder_4'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_4']));
		}
		if ($_POST['new_bulk_folder_5'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_5']));
		}
		if ($_POST['new_bulk_folder_6'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_6']));
		}
		if ($_POST['new_bulk_folder_7'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_7']));
		}
		unset($_SESSION['nexus_links']);
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
		unset($_SESSION['nexus_links']);
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

//	unset($_SESSION['nexus_links']);

	if (!isset($_SESSION['nexus_links'])) {
		$nexus_all_links = rebuild_cmd_links($_SESSION['user_nexus_pagina_id']);
		$_SESSION['nexus_links'] = $nexus_all_links['nexus_links'];
		$_SESSION['nexus_folders'] = $nexus_all_links['nexus_folders'];
		$_SESSION['nexus_order'] = $nexus_all_links['nexus_order'];
		$_SESSION['nexus_alphabet'] = $nexus_all_links['nexus_alphabet'];
		$_SESSION['nexus_codes'] = $nexus_all_links['nexus_codes'];
	}

	$hidden_inputs = false;

	$nexus_folder_order_identifier = 0;
	$navbar_custom_leftside = false;
	$print_folders_large = false;
	$html_bottom_folders = false;
	$close_folders_container = false;
	foreach ($_SESSION['nexus_folders'] as $folder_id => $content) {
			if ($_SESSION['nexus_folders'][$folder_id]['info']['type'] == 'main') {
			$nexus_folder_order_identifier++;
			$close_folders_container = "$('#folders_container').addClass('d-none');";
			$navbar_custom_leftside .= nexus_put_together(array('type' => 'navbar', 'id' => "trigger_folder_small_$folder_id", 'color' => $_SESSION['nexus_folders'][$folder_id]['info']['color'], 'icon' => $_SESSION['nexus_folders'][$folder_id]['info']['icon'], 'title' => $_SESSION['nexus_folders'][$folder_id]['info']['title']));
			if ($nexus_folder_order_identifier < 10) {
				$html_bottom_folders .= "
                document.addEventListener ('keydown', function (zEvent) {
                    if (zEvent.altKey  &&  zEvent.key === '{$nexus_folder_order_identifier}') {
                        show_links_folder_{$folder_id}();
                    }
                });
			    ";
			}
		} else {
			$close_folders_container = false;
		}
		$print_folders_large .= nexus_put_together(array('type' => 'folder', 'id' => "folder_large_$folder_id", 'class' => "{$_SESSION['nexus_folders'][$folder_id]['info']['type']}_folder_icons d-none", 'color' => $_SESSION['nexus_folders'][$folder_id]['info']['color'], 'icon' => $_SESSION['nexus_folders'][$folder_id]['info']['icon'], 'title' => $_SESSION['nexus_folders'][$folder_id]['info']['title']));
		$html_bottom_folders .= "
                function show_links_folder_{$folder_id}() {
                    load_state = $('#nexus_body').find('#load_state_{$folder_id}').val()
                        if (load_state == 'false') {
                        $.post('engine.php', {
                            'populate_links': {$folder_id},
                        }, function (data) {
                            if (data != 0) {
                                $('#links_row').append(data);
                            }
                        })
                    }
                    $('#page_title_text').empty();
                    $('#page_title_text').append('{$_SESSION['nexus_folders'][$folder_id]['info']['title']}');
                    $('#cmd_container').addClass('d-none');
                    $('#links_container').removeClass('d-none');
                    $('#settings_container').addClass('d-none');
                    $('.all_link_icons').addClass('d-none');
                    $('.link_of_folder_{$folder_id}').removeClass('d-none');
                    $('#nexus_body').find('#load_state_{$folder_id}').val('true');
                    $close_folders_container
                }
                $(document).on('click', '#trigger_folder_large_{$folder_id}', function () {
                    show_links_folder_{$folder_id}();
                })
                $(document).on('click', '#trigger_folder_small_{$folder_id}', function () {
                    show_links_folder_{$folder_id}();
                })";
		$hidden_inputs .= "
		    <input id='load_state_{$folder_id}' type='hidden' value='false'>
		";

	}

	// links = (user_id, pagina_id, type, param_int_1:folder_id, param_int_2:link_id, param1:link_url, param2:link_title, param3:link_icon, param4:link_color)

	$html_head_template_quill = false;
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
            #screenFiller {
                background-image: url('../wallpapers/$wallpaper_file');
                background-repeat: $wallpaper_repeat;
                background-size: $wallpaper_size;
                background-position: $wallpaper_position;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                height: 100vh;
                overflow-y: auto;
            }
            #page_title_text {
              mix-blend-mode: $title_overlay;
              user-select: none;
              word-break: keep-all;
              line-height: normal;
              cursor: pointer;
            }
            #page_title_text:hover {
              mix-blend-mode: $title_overlay_hover;
            }
        </style>
	";

	include 'templates/html_head.php';

?>
    <div id="nexus_body">
		<?php
			echo $hidden_inputs;
		?>
        <div id="screenFiller">
            <input id="load_state_linkdump" type="hidden" value="false">
            <div class="container-fluid">
                <div class="row sticky-top">
                    <div class="col-12 d-flex bg-dark p-1">
						<?php
							echo $navbar_custom_leftside;
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'yellow', 'class' => 'ms-auto', 'href' => false, 'icon' => 'fas fa-folder-bookmark', 'id' => 'trigger_show_main_folder_icons'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => false, 'href' => false, 'icon' => 'fas fa-cabinet-filing fa-swap-opacity', 'id' => 'trigger_show_archival_folder_icons'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => false, 'href' => false, 'icon' => 'fas fa-box-archive fa-swap-opacity', 'id' => 'trigger_show_link_dump'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'cyan', 'class' => false, 'href' => false, 'icon' => 'fas fa-clock-rotate-left', 'id' => 'trigger_show_recent_links'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'orange', 'class' => false, 'href' => false, 'icon' => 'fas fa-cog', 'id' => 'trigger_show_setup_icons'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'blue', 'class'=>false, 'href'=>'pagina.php?plano_id=bp', 'icon' => 'fas fa-books', 'id' => 'go_to_studyplan'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'green', 'class' => false, 'href' => 'escritorio.php', 'icon' => 'fas fa-lamp-desk', 'id' => 'back_to_office'));
						?>
                    </div>
                </div>
                <div class="row d-flex">
                    <div class="col-12">
                        <div id="page_title" class="my-5 text-center col-auto">
                            <span id="page_title_text" class="nexus-title display-1 <?php echo $title_color; ?>">
                                <?php
                                    echo $nexus_title;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
			<?php
				//        $cmd_text = nexus_random_color();
				//        $cmd_text = nexus_convert_color($cmd_text);
				//        $cmd_text = $cmd_text['text-color'];
				$cmd_text = 'text-white';
			?>
            <div id="cmd_container" class="container">
                <div class="row d-flex justify-content-around mt-3">
                    <div class="col">
                        <div class="mb-3 input-group">
                            <input id="cmdbar" name="cmdbar" list="command-list" type="text" class="form-control font-monospace mx-1 cmd-bar rounded <?php echo $cmd_text; ?> bg-dark border-0" rows="1" autocomplete="off" spellcheck="false" placeholder="<?php echo $user_apelido; ?> commands…">
                            <datalist id="command-list">
								<?php
									if ($_SESSION['nexus_options']['cmd_link_id'] == false) {
										foreach ($_SESSION['nexus_alphabet'] as $key => $value) {
											echo "<option value='$key'>";
										}
									} else {
										foreach ($_SESSION['nexus_codes'] as $key => $value) {
											echo "<option value='$key'>{$_SESSION['nexus_codes'][$key]['title']}</option>";
										}
									}
								?>
                            </datalist>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                echo "<div id='settings_container' class='container d-none mt-1'><div id='settings_row' class='row'>";

//                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_testing', 'title' => 'Manage testing', 'modal' => '#modal_manage_testing', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-circle-notch', 'color' => 'orange'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_folders', 'title' => 'Add or remove folders', 'modal' => '#modal_manage_folders', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-folder-gear', 'color' => 'yellow'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_links', 'title' => 'Add or remove links', 'modal' => '#modal_manage_links', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-bookmark', 'color' => 'red'));
				echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_icons_titles', 'title'=>'Manage icons and titles', 'modal'=>'#modal_manage_icons_titles', 'class'=>'nexus_settings_icon', 'icon'=>'fad fa-icons', 'color'=>'pink'));
				echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_move_links', 'title'=>'Move links between folders', 'modal'=>'#modal_manage_move_links', 'class'=>'nexus_settings_icon', 'icon'=>'fad fa-arrow-right-arrow-left', 'color'=>'teal'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_themes', 'title' => 'Manage themes', 'modal' => '#modal_manage_themes', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-swatchbook', 'color' => 'purple'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_options', 'title' => 'Options', 'modal' => '#modal_options', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-toggle-large-on', 'color' => 'green'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_timeline', 'title' => 'Activity log', 'modal' => '#modal_manage_timeline', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-list-timeline', 'color' => 'cyan'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'manage_commands', 'title' => 'Commands', 'modal' => '#modal_commands', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-rectangle-terminal', 'color' => 'orange'));
                echo nexus_put_together(array('type' => 'folder', 'id' => 'logout', 'title' => 'Logout', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-person-through-window', 'color' => 'red'));
                //					if ($user_id == 1) {
//						echo nexus_put_together(array('type' => 'folder', 'id' => 'transfer_nexustation', 'title' => 'Transfer Nexustation', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-timeline-arrow', 'color' => 'indigo'));
//						echo nexus_put_together(array('type' => 'folder', 'id' => 'shred_nexus', 'title' => 'Delete all Nexus data', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-shredder', 'color' => 'red'));
//					}

                echo "</div></div>";
				echo "<div id='folders_container' class='container d-none'><div id='folders_row' class='row'>";

				echo $print_folders_large;

				echo "</div></div>";
				echo "<div id='links_container' class='container d-none'><div id='links_row' class='row'>";
				echo "</div></div>";

			?>
        </div>
		<?php
//			$template_modal_div_id = 'modal_manage_testing';
//			$template_modal_titulo = 'Testing array reorder';
//			$template_modal_body_conteudo = false;
//			$template_modal_body_conteudo .= "<ul>";
//			$newarray = array();
//			foreach ($_SESSION['nexus_links'] as $key => $info)
//            {
//                $title = $_SESSION['nexus_links'][$key]['title'];
//                $template_modal_body_conteudo .= "<li>$key: $title</li>";
//                $new_array[$title] = $key;
//            }
//			$template_modal_body_conteudo .= "</ul>";
//			ksort($new_array);
//			$template_modal_body_conteudo .= serialize($new_array);
//			$template_modal_show_buttons = false;
//			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_folders';
			$template_modal_titulo = 'Add or remove folders';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_links';
			$template_modal_titulo = 'Add or remove links';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_icons_titles';
			$template_modal_titulo = 'Manage icons and titles';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_move_links';
			$template_modal_titulo = 'Move links between folders';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_themes';
			$template_modal_titulo = 'Manage themes';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_timeline';
			$template_modal_titulo = 'Manage timeline';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading..";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_options';
			$template_modal_titulo = 'Options';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "Loading...";
			$template_modal_show_buttons = false;
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_commands';
			$template_modal_titulo = 'The Nexus Command Bar';
			$modal_scrollable = true;
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo = "
		    <ul class='list-group mb-3'>
		        <li class='list-group-item active'><h5>Commands:</h5></li>
		        <li class='list-group-item'>\"/r/\" will send you to a subreddit. For example, just type \"/r/prequelmemes\".</li>
		        <li class='list-group-item'>\"/linkdump url\" to add a link to the Link Dump with random icon and color.</li>
		        <li class='list-group-item'>\"/log message\" will add a message to your log.</li>
		        <li class='list-group-item'>\Type a url starting with \"http\" or \"www\" to go directly to that address.</li>
		        <li class='list-group-item'>\"/del link title\" to delete a link.</li>
		        <li class='list-group-item'>\"/dl address\" to download an image.</li>
		        <li class='list-group-item'>\"/go search terms\" will perform a Google seach.</li>
		        <li class='list-group-item'>\"/gi search terms\" will perform a Google image seach.</li>
		        <li class='list-group-item'>\"/yt search terms\" will perform a Youtube seach.</li>
		        <li class='list-group-item'>\"/rd search terms\" will perform a Reddit seach.</li>
		        <li class='list-group-item'>\"/tw search terms\" will perform a Twitter seach.</li>
            </ul>
		    <ul class='list-group'>
		        <li class='list-group-item active'><h5>Hotkeys:</h5></li>
		        <li class='list-group-item'>Alt+C will return to the original screen, with focus on the command bar. Clicking the title text does the same.</li>
		        <li class='list-group-item'>Alt+1 to 9 will show the links from each of the first nine main folders, in order.</li>
		        <li class='list-group-item'>Alt+A will show the archived links.</li>
		        <li class='list-group-item'>Alt+S will show the settings.</li>
		        <li class='list-group-item'>Alt+R will show recent links.</li>
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
    </div>
    </body>
    <script type="text/javascript">
        function original_state() {
            $('#page_title_text').empty();
            $('#page_title_text').append('<?php echo $nexus_title; ?>');
            $('#cmdbar').val('');
            $('#folders_container').addClass('d-none');
            $('#settings_container').addClass('d-none');
            $('#links_container').addClass('d-none');
            $('#cmd_container').removeClass('d-none');
            $("input:text:visible:first").focus();
        }

        $(document).on('click', '#page_title_text', function () {
            original_state();
        })

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'c') {
                event.preventDefault();
                original_state();
            }
        });

        function isUrlValid(url) {
            return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        }

        $(document).on('keyup', '#cmdbar', function (e) {
            bar = $('#cmdbar').val();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                $.post('engine.php', {
                    'analyse_cmd_input': bar,
                }, function (data) {
                    if (data != 0) {
                        check = isUrlValid(data);
                        if (check == true) {
                            window.open(data, '_blank');
                            $("#cmdbar").val('');
                        } else {
                            $("#cmdbar").val(data);
                        }
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

            load_state = $('#nexus_body').find('#load_state_linkdump').val()
            if (load_state == 'false') {
                $.post('engine.php', {
                    'populate_links': 'linkdump',
                }, function (data) {
                    if (data != 0) {
                        $('#links_row').append(data);
                    }
                })
            }

            $('#page_title_text').empty();
            $('#page_title_text').append('Link Dump');
            $('#cmd_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
            $(document).find('#folders_container').addClass('d-none');
            $(document).find('#links_container').removeClass('d-none');
            $('#links_container').find('.all_link_icons').addClass('d-none');
            $('#links_container').find('.link_of_folder_linkdump').removeClass('d-none');
            $('#nexus_body').find('#load_state_linkdump').val('true');
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
            $('#page_title_text').empty();
            $('#page_title_text').append('Settings');
            $('#cmd_container').addClass('d-none');
            $('#links_container').addClass('d-none');
            $('.setup_icon').removeClass('d-none');
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
            $('#page_title_text').empty();
            $('#page_title_text').append('Main folders');
            $('#cmd_container').addClass('d-none');
            $(document).find('#links_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
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
            $('#page_title_text').empty();
            $('#page_title_text').append('Archives');
            $('#cmd_container').addClass('d-none');
            $(document).find('#links_container').addClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
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
            $('#page_title_text').empty();
            $('#page_title_text').append('Recent links');
            $('#cmd_container').addClass('d-none');
            $(document).find('#links_container').removeClass('d-none');
            $(document).find('#settings_container').addClass('d-none');
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

        $(document).on('click', '#trigger_manage_icons_titles', function () {
            $.post('engine.php', {
                'populate_icons_titles': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_icons_titles').empty();
                    $('#body_modal_manage_icons_titles').append(data);
                }
            });
        });

        $(document).on('click', '#trigger_manage_move_links', function () {
            $.post('engine.php', {
                'populate_move_links': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_move_links').empty();
                    $('#body_modal_manage_move_links').append(data);
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

        $(document).on('click', '#trigger_manage_options', function () {
            $.post('engine.php', {
                'populate_options_modal': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_options').empty();
                    $('#body_modal_options').append(data);
                }
            });
        });

        $(document).on('click', '#trigger_transfer_nexustation', function () {
            $.post('engine.php', {
                'transfer_nexustation': true
            }, function (data) {
                if (data != 0) {
                    if (data == 'done') {
                        window.location.reload(true);
                    }
                }
            })
        });

        $(document).on('click', '#trigger_shred_nexus', function () {
            $.post('engine.php', {
                'shred_nexus': true
            }, function (data) {
                if (data != 0) {
                    if (data == 'done') {
                        window.location.reload(true);
                    }
                }
            })
        });

        $(document).on('click', '#trigger_manage_timeline', function () {
            $.post('engine.php', {
                'send_log': true
            }, function (data) {
                if (data != 0) {
                    $('#body_modal_manage_timeline').empty();
                    $('#body_modal_manage_timeline').append(data);
                }
            })
        })

        $(document).on('click', '#trigger_logout', function () {
            $.post('engine.php', {
                'nexus_logout': true
            }, function (data) {
                if (data != 0) {
                    window.location.reload(true);
                } else {
                    alert('Could not log you out, somehow.');
                }
            })
        })

        $('.all_link_icons').bind('click', function(event){
            if(event.altKey) {
                event.preventDefault();
                alert('alt click happened');
            }
        });

        $(document).on('change', '#manage_icon_title_folders', function() {
            if(this.checked) {
                $('.manage_folder_hide').removeClass('d-none');
                $('.manage_link_hide').addClass('d-none');
            }
        });
        $(document).on('change', '#manage_icon_title_links', function() {
            if(this.checked) {
                $('.manage_folder_hide').addClass('d-none');
                $('.manage_link_hide').removeClass('d-none');
            }
        });
        $(document).on('change', '.change_trigger_show_details', function() {
            details_loaded = $('#details_loaded').val();
            if (details_loaded == 'false') {
                $('#details_loaded').val(true);
                $(document).find('.manage_details_hide').removeClass('d-none');
            }
        });

        <?php
		echo $html_bottom_folders;
		?>

    </script>
<?php

	include 'templates/html_bottom.php';
