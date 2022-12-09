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

	if (!isset($_SESSION['nexus_options'])) {
		$_SESSION['nexus_options'] = nexus_options(array('mode' => 'read', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
		if (count($_SESSION['nexus_options']) == 0) {
			nexus_options(array('mode' => 'create', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
			$_SESSION['nexus_options'] = nexus_options(array('mode' => 'read', 'pagina_id' => $_SESSION['user_nexus_pagina_id']));
		}
	}

	if (isset($_SESSION['nexus_options']['justify_links'])) {
		if ($_SESSION['nexus_options']['justify_links'] == false) {
			$_SESSION['nexus_options']['justify_links'] = 'justify-content-start';
		}
	} else {
        $_SESSION['nexus_options']['justify_links'] = 'justify-content-start';
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
//		header('Location:ubwiki.php');
//		exit();
		$pagina_criacao = false;
		$pagina_item_id = false;
		$pagina_tipo = 'nexus';
		$pagina_estado = false;
		$pagina_compartilhamento = false;
		$pagina_user_id = false;
		$pagina_titulo = 'Nexus';
		$pagina_etiqueta_id = false;
		$pagina_subtipo = false;
		$pagina_publicacao = false;
		$pagina_colaboracao = false;
	}

	$nexus_theme = false;
	$nexus_title = false;
	$nexus_info = false;

	if ($user_id == false) {
		$carregar_modal_login = true;
	}
	$nexus_info = return_nexus_info_user_id($user_id);

	if ($nexus_info != false) {
		$nexus_id = $nexus_info[0];
		$nexus_timestamp = $nexus_info[1];
		$nexus_title = $nexus_info[3];
		$nexus_theme = $nexus_info[4];
//        $nexus_random_icons = $nexus_info[5];
//        $nexus_random_colors = $nexus_info[6];
	} else {
		$nexus_id = false;
		$nexus_timestamp = false;
		$nexus_title = 'Nexus';
		$nexus_theme = 'random';
	}

	if ($nexus_title == false) {
		$nexus_title = "Nexus";
	}

//	$error = serialize(return_user_colors(array('user_id'=>$_SESSION['user_id'])));
//    error_log($error);

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
		nexus_new_folder(array('user_id' => $_SESSION['user_id'], 'pagina_id' => $_SESSION['user_nexus_pagina_id'], 'title' => $_POST['nexus_new_folder_title'], 'icon' => $_POST['nexus_new_folder_icon'], 'color' => $_POST['nexus_new_folder_color'], 'type' => $_POST['nexus_new_folder_type']));
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
		if (!isset($_POST['new_bulk_folders_type'])) {
			$_POST['new_builk_folders_type'] = 'main';
		}
		if ($_POST['new_bulk_folder_1'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_1'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_2'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_2'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_3'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_3'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_4'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_4'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_5'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_5'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_6'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_6'], 'type' => $_POST['new_bulk_folders_type']));
		}
		if ($_POST['new_bulk_folder_7'] != false) {
			nexus_new_folder(array('user_id' => $user_id, 'pagina_id' => $pagina_id, 'title' => $_POST['new_bulk_folder_7'], 'type' => $_POST['new_bulk_folders_type']));
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

	if (isset($_POST['del_theme_select'])) {
		$query = prepare_query("UPDATE nexus_elements SET state = 0 WHERE param_int_1 = {$_POST['del_theme_select']} AND pagina_id = {$_SESSION['user_nexus_pagina_id']} AND type = 'theme'");
		$conn->query($query);
	}

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

//	unset($_SESSION['nexus_links']);

	$nexus_folders_check = false;

	if ((!isset($_SESSION['nexus_links'])) || (($_SESSION['nexus_links']) == array(false))) {
		$_SESSION['nexus_links'] = array(false);
		$_SESSION['nexus_folders'] = array(false);
		$_SESSION['nexus_order'] = array(false);
		$_SESSION['nexus_alphabet'] = array(false);
		$_SESSION['nexus_codes'] = array(false);
		if ($user_id != false) {
			$nexus_all_links = rebuild_cmd_links($_SESSION['user_nexus_pagina_id']);
			if ($nexus_all_links != false) {
				$_SESSION['nexus_links'] = $nexus_all_links['nexus_links'];
				$_SESSION['nexus_folders'] = $nexus_all_links['nexus_folders'];
				$_SESSION['nexus_order'] = $nexus_all_links['nexus_order'];
				$_SESSION['nexus_alphabet'] = $nexus_all_links['nexus_alphabet'];
				$_SESSION['nexus_codes'] = $nexus_all_links['nexus_codes'];
				$nexus_folders_check = true;
			}
		}
	} else {
		$nexus_folders_check = true;
	}

	$hidden_inputs = false;

	$nexus_folder_order_identifier = 0;
	$navbar_custom_leftside = false;
	$print_folders_large = false;
	$html_bottom_folders = false;
	$close_folders_container = false;
	$folder_containers = false;

	$close_folders_container = false;


	if ($_SESSION['nexus_folders'] != false) {
		foreach ($_SESSION['nexus_folders'] as $folder_id => $content) {
			$close_folders_container = false;
			if (!isset($_SESSION['nexus_folders'][$folder_id]['info']['type'])) {
				continue;
			}
			if ($_SESSION['nexus_folders'][$folder_id]['info']['type'] == 'main') {
				$nexus_folder_order_identifier++;
				$close_folders_container = "$('#folders_container').addClass('d-none');";
				$navbar_custom_leftside .= nexus_put_together(array('type' => 'navbar', 'id' => "trigger_folder_small_$folder_id", 'color' => $_SESSION['nexus_folders'][$folder_id]['info']['color'], 'icon' => $_SESSION['nexus_folders'][$folder_id]['info']['icon'], 'title' => $_SESSION['nexus_folders'][$folder_id]['info']['title'], 'class' => 'col-auto'));
				if ($nexus_folder_order_identifier < 10) {
					$html_bottom_folders .= "
                document.addEventListener ('keydown', function (zEvent) {
                    if (zEvent.altKey  &&  zEvent.key === '{$nexus_folder_order_identifier}') {
                        show_links_folder_{$folder_id}();
                    }
                });
			    ";
				}
			}
			if ($_SESSION['nexus_folders'][$folder_id]['info']['type'] == 'linkdump') {
				$close_folders_container = "$('#folders_container').addClass('d-none');";
			}
			$print_folders_large .= nexus_put_together(array('type' => 'folder_slim', 'id' => "folder_large_$folder_id", 'class' => "{$_SESSION['nexus_folders'][$folder_id]['info']['type']}_folder_icons d-none", 'color' => $_SESSION['nexus_folders'][$folder_id]['info']['color'], 'icon' => $_SESSION['nexus_folders'][$folder_id]['info']['icon'], 'title' => $_SESSION['nexus_folders'][$folder_id]['info']['title']));
			$html_bottom_folders .= "
                function show_links_folder_{$folder_id}() {
                    load_state = $('#nexus_body').find('#load_state_{$folder_id}').val()
                        if (load_state == 'false') {
                        $.post('engine.php', {
                            'populate_links': '{$folder_id}',
                        }, function (data) {
                            if (data != 0) {
                                $('#links_from_folder_{$folder_id}').append(data);
                            }
                        })
                    }
                    $('#page_title_text').empty();
                    $('#page_title_text').append('{$_SESSION['nexus_folders'][$folder_id]['info']['title']}');
                    $('#cmd_container').addClass('d-none');
                    $('#links_container').removeClass('d-none');
                    $('.specific_folder_links').addClass('d-none');
                    $('#links_from_folder_{$folder_id}').removeClass('d-none');
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
		    <input id='load_state_{$folder_id}' class='hidden_load_states' type='hidden' value='false'>
		";
			$folder_containers .= "<div id='links_from_folder_{$folder_id}' class='row specific_folder_links {$_SESSION['nexus_options']['justify_links']}'></div>";
		}
	}


	// links = (user_id, pagina_id, type, param_int_1:folder_id, param_int_2:link_id, param1:link_url, param2:link_title, param3:link_icon, param4:link_color)

	$html_head_template_quill = false;
	$html_head_template_conteudo = false;
	$html_head_template_conteudo .= "
        <script type='text/javascript'>
          var user_id='$user_id';
          var user_email='$user_email';
        </script>
    ";

	switch ($nexus_theme) {
		case 'random':
			$background_color = random_bg_color();
			$wallpapers = array(false);
			$wallpaper_repeat = false;
			$wallpaper_size = false;
			$wallpaper_position = false;
			$title_color = 'text-light';
			$title_overlay = false;
			$title_overlay_hover = false;
			break;
		case 'light':
			$background_color = '#f7f7f7';
			$wallpapers = array('../wallpapers/tile/papyrus.webp', '../wallpapers/tile/y-so-serious-white.png', '../wallpapers/tile/what-the-hex.webp', '../wallpapers/tile/cheap_diagonal_fabric.png', '../wallpapers/tile/circles-and-roundabouts.webp', '../wallpapers/tile/crossline-dots.webp', '../wallpapers/tile/diagonal_striped_brick.webp', '../wallpapers/tile/funky-lines.webp', '../wallpapers/tile/gplaypattern.png', '../wallpapers/tile/interlaced.png', '../wallpapers/tile/natural_paper.webp', '../wallpapers/tile/subtle_white_feathers.webp', '../wallpapers/tile/topography.webp', '../wallpapers/tile/webb.png', '../wallpapers/tile/whirlpool.webp', '../wallpapers/tile/white-waves.webp', '../wallpapers/tile/worn_dots.webp', '../wallpapers/tile/vertical-waves.webp');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'color-burn';
			$title_overlay_hover = 'difference';
			break;
		case 'dark':
			$background_color = '#191919';
			$wallpapers = array('../wallpapers/tile/tactile_noise.webp', '../wallpapers/tile/black_lozenge.webp', '../wallpapers/tile/dark_leather.webp', '../wallpapers/tile/escheresque_ste.webp', '../wallpapers/tile/papyrus-dark.webp', '../wallpapers/tile/prism.png', '../wallpapers/tile/tasky_pattern.webp', '../wallpapers/tile/y-so-serious.png');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-light';
			$title_overlay = 'color-dodge';
			$title_overlay_hover = 'difference';
			break;
		case 'whimsical':
			$background_color = '#8BA810';
			$wallpapers = array('../wallpapers/tile/pink-flowers.webp', '../wallpapers/tile/morocco.png', '../wallpapers/tile/pool_table.webp', '../wallpapers/tile/retina_wood.png', '../wallpapers/tile/wheat.webp');
			$wallpaper_repeat = 'repeat';
			$wallpaper_size = 'auto';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'overlay';
			$title_overlay_hover = 'difference';
			break;
		case 'landscape':
		case false:
			$background_color = '#471918';
			$wallpapers = glob( '../wallpapers/landscape/*.*');
			$wallpaper_repeat = 'no-repeat';
			$wallpaper_size = 'cover';
			$wallpaper_position = 'center center';
			$title_color = 'text-dark';
			$title_overlay = 'overlay';
			$title_overlay_hover = 'difference';
			break;
		default:
			$theme_info = return_theme($nexus_theme);
			$background_color = "#{$theme_info['bghex']}";
			$wallpapers = array($theme_info['url']);
			$wallpaper_repeat = $theme_info['repeat'];
			$wallpaper_size = $theme_info['size'];
			$wallpaper_position = $theme_info['position'];
			$title_color_info = nexus_colors(array('mode' => 'convert', 'color' => $theme_info['homehex']));
			$title_color = $title_color_info['link-color'];
			$title_overlay = $theme_info['homeeffect'];
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
                background-image: url('$wallpaper_file');
                background-repeat: $wallpaper_repeat;
                background-size: $wallpaper_size;
                background-position: $wallpaper_position;
                background-color: $background_color;
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
					<?php
						echo $navbar_custom_leftside;
						$msauto = false;
						if ($nexus_folders_check == true) {
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'yellow', 'class' => 'col-auto ms-auto', 'href' => false, 'icon' => 'fas fa-folder-bookmark', 'id' => 'trigger_show_main_folder_icons'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => 'col-auto ', 'href' => false, 'icon' => 'fas fa-cabinet-filing fa-swap-opacity', 'id' => 'trigger_show_archival_folder_icons'));
							echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => 'col-auto me-3', 'href' => false, 'icon' => 'fas fa-box-archive fa-swap-opacity', 'id' => 'trigger_show_linkdump'));
//								echo nexus_put_together(array('type' => 'navbar', 'color' => 'cyan', 'class' => '', 'href' => false, 'icon' => 'fas fa-clock-rotate-left', 'id' => 'trigger_show_recent_links'));
						} else {
							$msauto = 'ms-auto';
						}
						echo nexus_put_together(array('type' => 'navbar', 'color' => 'orange', 'class' => "col-auto $msauto", 'href' => false, 'icon' => 'fas fa-cog', 'id' => 'trigger_show_setup_icons'));
						echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => 'col-auto ', 'href' => false, 'icon' => 'fad fa-circle-arrow-up-right', 'id' => 'trigger_show_leave_icons', 'modal' => '#modal_leave_options'));
						6 ?>
                </div>
                <div class="row d-flex">
                    <div class="col-12">
                        <div id="page_title" class="my-3 text-center col-auto">
                            <span id="page_title_text" class="nexus-title <?php echo $title_color; ?>">
                                <?php
									echo $nexus_title;
								?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cmd_container" class="container">
                <div class="row d-flex justify-content-center mt-3 input-group">
                    <!--                    <div class="col">-->
                    <!--                        <div class="mb-3 input-group">-->
                    <form>
                        <input type="text" id="username" autocomplete="username" style="width:0;height:0;visibility:hidden;position:absolute;left:0;top:0"/>
                        <input type="password" autocomplete="current-password" style="width:0;height:0;visibility:hidden;position:absolute;left:0;top:0"/>
                    </form>
                    <input id="cmdbar" name="cmdbar" list="command-list" type="text" class="font-monospace cmd-bar rounded text-white bg-darker border-0 mb-5" rows="1" autocomplete="off" spellcheck="false" placeholder="<?php echo $user_apelido; ?> commands…">
                    <datalist id="command-list">
						<?php
							if (!isset($_SESSION['nexus_options']['cmd_link_id'])) {
								$_SESSION['nexus_options']['cmd_link_id'] = false;
							}
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
                    <!--                        </div>-->
                    <!--                    </div>-->
                </div>
                <div id="under_cmdbar" class="row <?php echo $_SESSION['nexus_options']['justify_links']; ?>"></div>
            </div>
			<?php
				echo "<div id='settings_container' class='container d-none mt-1'><div id='settings_row' class='row'>";

				//                echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_testing', 'title' => 'Manage testing', 'modal' => '#modal_manage_testing', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-circle-notch', 'color' => 'orange'));
				if ($nexus_folders_check == true) {
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_folders', 'title' => 'Add folder', 'modal' => '#modal_manage_folders', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-folder-plus', 'color' => 'yellow'));
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_links', 'title' => 'Add link', 'modal' => '#modal_manage_links', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-grid-2-plus', 'color' => 'red'));
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_icons_titles', 'title' => 'Manage icons and folders', 'modal' => '#modal_manage_icons_titles', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-pen-ruler', 'color' => 'pink'));
//					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_move_links', 'title' => 'Move links between folders', 'modal' => '#modal_manage_move_links', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-arrow-right-arrow-left', 'color' => 'teal'));
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_themes', 'title' => 'Manage themes', 'modal' => '#modal_manage_themes', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-swatchbook', 'color' => 'purple'));
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_options', 'title' => 'Options', 'modal' => '#modal_options', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-toggle-large-on', 'color' => 'green'));
					echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_timeline', 'title' => 'Activity log', 'modal' => '#modal_manage_timeline', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-list-timeline', 'color' => 'cyan'));
				}
				echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'manage_commands', 'title' => 'Commands', 'modal' => '#modal_commands', 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-rectangle-terminal', 'color' => 'orange'));
				//				echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'logout', 'title' => 'Logout', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-person-through-window', 'color' => 'red'));
				//					if ($user_id == 1) {
				//						echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'transfer_nexustation', 'title' => 'Transfer Nexustation', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-timeline-arrow', 'color' => 'indigo'));
				//						echo nexus_put_together(array('type' => 'folder_fat', 'id' => 'shred_nexus', 'title' => 'Delete all Nexus data', 'modal' => false, 'class' => 'nexus_settings_icon', 'icon' => 'fad fa-shredder', 'color' => 'red'));
				//					}

				echo "</div></div>";
				echo "<div id='folders_container' class='container d-none'><div id='folders_row' class='row'>";

				echo $print_folders_large;

				echo "</div></div>";
				echo "<div id='links_container' class='container d-none'><div id='links_row' class='row'>";

				echo $folder_containers;

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
			//
			//			include 'templates/modal.php';

			$template_modal_div_id = 'modal_leave_options';
			$template_modal_titulo = 'Go...';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "<div class='row justify-content-center d-flex'>";
			if ($user_id != false) {
				$template_modal_body_conteudo .= nexus_put_together(array('type' => 'large', 'id' => 'return_ubwiki', 'icon' => 'fad fa-books', 'color' => 'green', 'title' => 'Study Plan', 'href' => 'pagina.php?plano_id=bp'));
				$template_modal_body_conteudo .= nexus_put_together(array('type' => 'large', 'id' => 'return_ubwiki', 'icon' => 'fad fa-lamp-desk', 'color' => 'yellow', 'title' => 'Office', 'href' => 'escritorio.php'));
				$template_modal_body_conteudo .= nexus_put_together(array('type' => 'large', 'id' => 'logout', 'icon' => 'fad fa-right-from-bracket', 'color' => 'red', 'title' => 'Logout'));
			} else {
				$template_modal_body_conteudo .= nexus_put_together(array('type' => 'large', 'id' => 'back_to_ubwiki', 'targetblank' => false, 'href' => 'ubwiki.php', 'icon' => 'fad fa-home', 'color' => 'blue', 'title' => 'Ubwiki'));
				$template_modal_body_conteudo .= nexus_put_together(array('type' => 'large', 'id' => 'login', 'icon' => 'fad fa-right-to-bracket', 'color' => 'teal', 'title' => 'Login', 'href' => false, 'modal' => '#modal_login'));
			}

			//			echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => 'ms-auto', 'href' => false, 'icon' => 'fas fa-right-to-bracket', 'id' => 'trigger_login', 'modal' => '#modal_login'));

			$template_modal_body_conteudo .= "</div>";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_folders';
			$template_modal_titulo = 'Add folder';
			$template_modal_body_conteudo = "Loading...";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_links';
			$template_modal_titulo = 'Add link';
			$template_modal_body_conteudo = "Loading...";
			$modal_focus = 'nexus_new_link_url';
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_icons_titles';
			$template_modal_titulo = 'Manage links and folders';
			$template_modal_body_conteudo = "Loading...";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_move_links';
			$template_modal_titulo = 'Move links between folders';
			$template_modal_body_conteudo = "Loading...";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_themes';
			$template_modal_titulo = 'Manage themes';
			$template_modal_body_conteudo = "Loading...";
			$modal_focus = 'nexus_theme_select';
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_manage_timeline';
			$template_modal_titulo = 'Manage timeline';
			$template_modal_body_conteudo = "Loading..";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_options';
			$template_modal_titulo = 'Options';
			$template_modal_body_conteudo = "Loading...";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_commands';
			$template_modal_titulo = 'The Nexus Command Bar';
			$modal_scrollable = true;
			$template_modal_body_conteudo = "
		    <ul class='list-group mb-3'>
		        <li class='list-group-item active'><h5>Commands:</h5></li>
		        <li class='list-group-item'>\"/r/\" will send you to a subreddit. For example, just type \"/r/prequelmemes\".</li>
		        <li class='list-group-item'>\"/ld url\" to add a link to the Link Dump with random icon and color.</li>
		        <li class='list-group-item'>\"/log message\" will add a message to your log.</li>
		        <li class='list-group-item'>Type a url starting with \"http\" or \"www\" to go directly to that address.</li>
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
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_add_folders_bulk';
			$template_modal_titulo = 'Add folders in bulk';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "<p>You will be able to change the details later. For now, all you need is a name for each.</p>";
			include 'templates/modal.php';

			$template_modal_div_id = 'modal_add_links_bulk';
			$template_modal_titulo = 'Add links in bulk to the Link Dump';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "<p>You will be able to change the details later. For now, all you need is a name for each.</p>";
			include 'templates/modal.php';

			if ($user_id == false) {
				include 'pagina/modal_login.php';
			}

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
            $(document).find("#cmdbar").focus();
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
            $("#under_cmdbar").empty();
            bar = $('#cmdbar').val();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                $.post('engine.php', {
                    'analyse_cmd_input': bar,
                }, function (data) {
                    if (data != 0) {
                        data_type = data.substring(0, 7);
                        data_content = data.substring(7);
                        check = isUrlValid(data);
                        if (check == true) {
                            window.open(data, '_blank');
                            $("#cmdbar").val('');
                        } else {
                            if (data_type == '#HTMLS#') {
                                $("#cmdbar").val('');
                                $("#under_cmdbar").html(data_content);
                            } else if (data_type == '#MESSG#') {
                                $("#cmdbar").val(data_content);
                            } else {
                                $("#cmdbar").val('Something went wrong');
                            }
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

        $(document).on('click', '#trigger_show_linkdump', function () {
            show_links_folder_linkdump();
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
                    $("#nexus_new_link_url").focus();
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

        $('.all_link_icons').bind('click', function (event) {
            if (event.altKey) {
                event.preventDefault();
                alert('alt click happened');
            }
        });

        $(document).on('change', '#manage_icon_title_folders', function () {
            if (this.checked) {
                $('.manage_folder_hide').removeClass('d-none');
                $('.manage_link_hide').addClass('d-none');
                $("#move_to_this_folder_id").prop('disabled', 'disabled');
                $("#diff_this_link_type").prop('disabled', 'disabled');
                $('.manage_details_links_only').addClass('d-none');
                filled_in_folder = $('#manage_icon_title_folder_id').val();
                if (filled_in_folder != null) {
                    $('.manage_details_folders_only').removeClass('d-none');
                }
            }
        });
        $(document).on('change', '#manage_icon_title_links', function () {
            if (this.checked) {
                $('.manage_link_hide').removeClass('d-none');
                $('.manage_folder_hide').addClass('d-none');
                $('.manage_details_folders_only').addClass('d-none');
                filled_in_link = $('#manage_icon_title_link_id').val();
                if (filled_in_link != null) {
                    $('.manage_details_links_only').removeClass('d-none');
                    $("#move_to_this_folder_id").removeAttr("disabled");
                    $("#diff_this_link_type").removeAttr("disabled");
                }
            }
        });
        $(document).on('change', '#manage_icon_title_folder_id', function () {
            details_loaded = $('#details_loaded').val();
            $('.manage_details_folders_only').removeClass('d-none');
            if (details_loaded == 'false') {
                $('#details_loaded').val(true);
                $(document).find('.manage_details_hide').removeClass('d-none');
                $("#move_to_this_folder_id").prop('disabled', 'disabled');
                $("#diff_this_link_type").prop('disabled', 'disabled');
                $('.manage_details_links_only').addClass('d-none');
            }
        });
        $(document).on('change', '#manage_icon_title_link_id', function () {
            details_loaded = $('#details_loaded').val();
            $('.manage_details_links_only').removeClass('d-none');
            if (details_loaded == 'false') {
                $('#details_loaded').val(true);
                $(document).find('.manage_details_hide').removeClass('d-none');
                $('.manage_details_folders_only').addClass('d-none');
                $("#move_to_this_folder_id").removeAttr("disabled");
                $("#diff_this_link_type").removeAttr("disabled");
            }
        });
        $(document).on('click', '#trigger_delete_this_folder', function () {
            delete_folder_check = confirm('Do you really want to delete this folder?');
            if (delete_folder_check === true) {
                delete_folder_id = $('#manage_icon_title_folder_id').val();
                $.post('engine.php', {
                    'remove_this_folder': delete_folder_id
                }, function (data) {
                    if (data != 0) {
                        window.location.reload(true);
                    } else {
                        alert('Could not delete the link, for some reason.');
                    }
                })
            }
        })
        $(document).on('click', '#trigger_delete_this_link', function () {
            delete_link_check = confirm('Do you really want to delete this link?');
            if (delete_link_check == true) {
                delete_link_id = $('#manage_icon_title_link_id').val();
                $.post('engine.php', {
                    'remove_this_link': delete_link_id
                }, function (data) {
                    if (data != 0) {
                        window.location.reload(true);
                    } else {
                        alert('Could not delete the link, for some reason.');
                    }
                })
            }
        })

        $(document).on('change', '#radio_pick_theme', function () {
            if (this.checked) {
                $(document).find('.pick_theme_details').removeClass('d-none');
                $(document).find('.add_theme_details').addClass('d-none');
                $(document).find('.del_theme_details').addClass('d-none');
            }
        });

        $(document).on('change', '#radio_add_theme', function () {
            if (this.checked) {
                $(document).find('.add_theme_details').removeClass('d-none');
                $(document).find('.pick_theme_details').addClass('d-none');
                $(document).find('.del_theme_details').addClass('d-none');
            }
        });

        $(document).on('change', '#radio_del_theme', function () {
            if (this.checked) {
                $(document).find('.del_theme_details').removeClass('d-none');
                $(document).find('.pick_theme_details').addClass('d-none');
                $(document).find('.add_theme_details').addClass('d-none');
            }
        });

        $(document).on('click', '#trigger_redo_icons', function () {
            $.post('engine.php', {
                'redo_all_icons': true
            }, function (data) {
                if (data != 0) {
                    window.location.reload(true);
                } else {
                    alert('Could not redo the links, for some reason.');
                }
            })
        })

		<?php
		echo $html_bottom_folders;
		?>

        document.addEventListener('keydown', function (zEvent) {
            if (zEvent.altKey && zEvent.key === 'd') {
                event.preventDefault();
                show_links_folder_linkdump();
            }
        });
        $(document).find("#cmdbar").focus();

    </script>
<?php

	include 'templates/html_bottom.php';
