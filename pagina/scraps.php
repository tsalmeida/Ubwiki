<?php

	$print_folders_large = false;
	$navbar_custom_leftside = false;
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
			$nexus_fa_icone = nexus_icons('convert', $nexus_fa_icone);
			$nexus_fa_icone = "fa-solid $nexus_fa_icone";
			$nexus_folder_type = $nexus_folder_info['type'];
			$close_folders_container = false;
			switch ($nexus_folder_type) {
				case 'archival':
					$nexus_archive_folder_counter++;
					$nexus_folder_order_identifier = $nexus_archive_folder_counter;
					$nexus_artefato_class = 'archival_folder_icons d-none';
					$nexus_fa_size = 'fa-2x';
					break;
				case 'main':
					$nexus_main_folder_counter++;
					$nexus_folder_order_identifier = $nexus_main_folder_counter;
					$nexus_artefato_class = 'main_folder_icons d-none';
					$close_folders_container = "$('#folders_container').addClass('d-none');";
					$navbar_custom_leftside .= nexus_put_together(array('type' => 'navbar', 'color' => $nexus_folder_info['color'], 'href' => false, 'icon' => $nexus_fa_icone, 'id' => "trigger_folder_small_{$nexus_folder_order_identifier}"));
			}
			$nexus_folder_pairings[$nexus_folder_id] = $nexus_folder_order_identifier;

			$print_folders_large .= nexus_put_together(array('type' => 'medium', 'id' => "folder_large_{$nexus_folder_order_identifier}", 'title' => $nexus_folder_title, 'class' => "$nexus_artefato_class d-none", 'icon' => $nexus_fa_icone, 'color' => $nexus_folder_info['color']));

			$html_bottom_folders .= "
                function show_links_folder_{$nexus_folder_order_identifier}() {
                    $.post('engine.php', {
                        'populate_links': {$nexus_folder_id},
                    }, function (data) {
                        if (data != 0) {
                            $('#links_container').empty();
                            $('#links_container').append(data);
                        }
                    })
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
