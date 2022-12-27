<?php
	include 'engine.php';
	$pagina_tipo = 'travelogue';
	$pagina_id = $_SESSION['user_escritorio'];
	$pagina_title = 'Travelogue';
	$pagina_favicon = 'final_travelogue.ico';

//    if (!isset($_SESSION['travelogue_filter'])) {
//		$query = prepare_query("SELECT travelogue_filters FROM nexus_options WHERE user_id = {$_SESSION['user_id']}");
//		$travelogue_filters = $conn->query($query);
//		if ($travelogue_filters->num_rows > 0) {
//			while ($travelogue_filter = $travelogue_filters->fetch_assoc()) {
//				$user_travelogue_filters = $travelogue_filter['travelogue_filters'];
//			}
//		} else {
//			$user_travelogue_filters = false;
//		}
//	}

	if (!isset($_SESSION['travelogue_codes'])) {
		$_SESSION['travelogue_codes'] = build_travelogue_codes();
	}

	if (isset($_POST['travel_new_type'])) {
		if (!isset($_POST['travel_new_release_date'])) {
			$_POST['travel_new_release_date'] = false;
		}
		if (!isset($_POST['travel_new_title'])) {
			$_POST['travel_new_title'] = false;
		}
		$_POST['travel_new_title'] = mysqli_real_escape_string($conn, $_POST['travel_new_title']);
		if (!isset($_POST['travel_new_creator'])) {
			$_POST['travel_new_creator'] = false;
		}
		if (!isset($_POST['travel_new_genre'])) {
			$_POST['travel_new_genre'] = false;
		}
		$_POST[$_POST['travel_new_genre']] = mysqli_real_escape_string($conn, $_POST['travel_new_genre']);
		if (!isset($_POST['travel_new_datexp'])) {
			$_POST['travel_new_datexp'] = false;
		}
		if (!isset($_POST['travel_new_rating'])) {
			$_POST['travel_new_rating'] = false;
		}
		if (!isset($_POST['travel_new_comments'])) {
			$_POST['travel_new_comments'] = false;
		}
		$_POST['travel_new_comments'] = mysqli_real_escape_string($conn, $_POST['travel_new_comments']);
		if (!isset($_POST['travel_new_information'])) {
			$_POST['travel_new_information'] = false;
		}
		$_POST['travel_new_information'] = mysqli_real_escape_string($conn, $_POST['travel_new_information']);
		if (!isset($_POST['travel_new_database'])) {
			$_POST['travel_new_database'] = false;
		}
		$travel_new_codes = array();
		if (isset($_POST['travel_new_code_favorite'])) {
			$travel_new_codes['favorite'] = true;
		}
		if (isset($_POST['travel_new_code_lyrics'])) {
			$travel_new_codes['lyrics'] = true;
		}
		if (isset($_POST['travel_new_code_hifi'])) {
			$travel_new_codes['hifi'] = true;
		}
		if (isset($_POST['travel_new_code_relaxing'])) {
			$travel_new_codes['relaxing'] = true;
		}
		if (isset($_POST['travel_new_code_heavy'])) {
			$travel_new_codes['heavy'] = true;
		}
		if (isset($_POST['travel_new_code_vibe'])) {
			$travel_new_codes['vibe'] = true;
		}
		if (isset($_POST['travel_new_code_complex'])) {
			$travel_new_codes['complex'] = true;
		}
		if (isset($_POST['travel_new_code_instrumental'])) {
			$travel_new_codes['instrumental'] = true;
		}
		if (isset($_POST['travel_new_code_live'])) {
			$travel_new_codes['live'] = true;
		}
		if (isset($_POST['travel_new_code_lists'])) {
			$travel_new_codes['lists'] = true;
		}
		if (isset($_POST['travel_new_code_bookmark'])) {
			$travel_new_codes['bookmark'] = true;
		}
		if (isset($_POST['travel_new_code_thumbsup'])) {
			$travel_new_codes['thumbsup'] = true;
		}
		if (isset($_POST['travel_new_code_thumbsdown'])) {
			$travel_new_codes['thumbsdown'] = true;
		}
		if (isset($_POST['travel_new_code_thumbtack'])) {
			$travel_new_codes['thumbtack'] = true;
		}
		if (isset($_POST['travel_new_code_pointer'])) {
			$travel_new_codes['pointer'] = true;
		}
		$travel_new_codes = serialize($travel_new_codes);
		$query = prepare_query("INSERT INTO travelogue (user_id, type, codes, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl) VALUES ({$_SESSION['user_id']}, '{$_POST['travel_new_type']}', '$travel_new_codes', '{$_POST['travel_new_release_date']}', '{$_POST['travel_new_title']}', '{$_POST['travel_new_creator']}', '{$_POST['travel_new_genre']}', '{$_POST['travel_new_datexp']}', '{$_POST['travel_new_rating']}', '{$_POST['travel_new_comments']}', '{$_POST['travel_new_information']}', '{$_POST['travel_new_database']}')");
		$conn->query($query);
	}

	if (!isset($_SESSION['travelogue_separate_types'])) {
		$_SESSION['travelogue_separate_types'] = false;
	}
	if (isset($_POST['trigger_modal_filter'])) {
		if (isset($_POST['travelogue_sorting'])) {
			$_SESSION['travelogue_sorting'] = $_POST['travelogue_sorting'];
		}
		if (isset($_POST['travelogue_separate_types'])) {
			$_SESSION['travelogue_separate_types'] = true;
		} else {
			$_SESSION['travelogue_separate_types'] = false;
		}

		if (!isset($_POST['travelogue_filter_music'])) {
			$_POST['travelogue_filter_music'] = false;
		}
		if (!isset($_POST['travelogue_filter_concert'])) {
			$_POST['travelogue_filter_concert'] = false;
		}
		if (!isset($_POST['travelogue_filter_movie'])) {
			$_POST['travelogue_filter_movie'] = false;
		}
		if (!isset($_POST['travelogue_filter_tvshow'])) {
			$_POST['travelogue_filter_tvshow'] = false;
		}
		if (!isset($_POST['travelogue_filter_episode'])) {
			$_POST['travelogue_filter_episode'] = false;
		}
		if (!isset($_POST['travelogue_filter_book'])) {
			$_POST['travelogue_filter_book'] = false;
		}
		if (!isset($_POST['travelogue_filter_classical'])) {
			$_POST['travelogue_filter_classical'] = false;
		}
		if (!isset($_POST['travelogue_filter_comic'])) {
			$_POST['travelogue_filter_comic'] = false;
		}
		if (!isset($_POST['travelogue_filter_standup'])) {
			$_POST['travelogue_filter_standup'] = false;
		}
		if (!isset($_POST['travelogue_filter_painting'])) {
			$_POST['travelogue_filter_painting'] = false;
		}
		if (!isset($_POST['travelogue_filter_photo'])) {
			$_POST['travelogue_filter_photo'] = false;
		}
		if (!isset($_POST['travelogue_filter_vidya'])) {
			$_POST['travelogue_filter_vidya'] = false;
		}
		if (!isset($_POST['travelogue_filter_architecture'])) {
			$_POST['travelogue_filter_architecture'] = false;
		}
		if (!isset($_POST['travelogue_filter_sports'])) {
			$_POST['travelogue_filter_sports'] = false;
		}
		if (!isset($_POST['travelogue_filter_live'])) {
			$_POST['travelogue_filter_live'] = false;
		}
		if (!isset($_POST['travelogue_filter_other'])) {
			$_POST['travelogue_filter_other'] = false;
		}
        if (!isset($_POST['travelogue_filter_code_favorite'])) {
            $_POST['travelogue_filter_code_favorite'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_lyrics'])) {
            $_POST['travelogue_filter_code_lyrics'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_hifi'])) {
            $_POST['travelogue_filter_code_hifi'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_relaxing'])) {
            $_POST['travelogue_filter_code_relaxing'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_heavy'])) {
            $_POST['travelogue_filter_code_heavy'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_vibe'])) {
            $_POST['travelogue_filter_code_vibe'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_complex'])) {
            $_POST['travelogue_filter_code_complex'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_instrumental'])) {
            $_POST['travelogue_filter_code_instrumental'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_live'])) {
            $_POST['travelogue_filter_code_live'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_lists'])) {
            $_POST['travelogue_filter_code_lists'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_bookmark'])) {
            $_POST['travelogue_filter_code_bookmark'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_thumbsup'])) {
            $_POST['travelogue_filter_code_thumbsup'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_thumbsdown'])) {
            $_POST['travelogue_filter_code_thumbsdown'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_thumbtack'])) {
            $_POST['travelogue_filter_code_thumbtack'] = false;
        }
        if (!isset($_POST['travelogue_filter_code_pointer'])) {
            $_POST['travelogue_filter_code_pointer'] = false;
        }

        if (!isset($_POST['travelogue_show_no_code'])) {
            $_POST['travelogue_show_no_code'] = false;
        }

        if (!isset($_POST['travelogue_filter_genres'])) {
            $_POST['travelogue_filter_genres'] = array();
        }

		$_SESSION['travelogue_filter_options'] = array('music' => $_POST['travelogue_filter_music'], 'concert' => $_POST['travelogue_filter_concert'], 'movie' => $_POST['travelogue_filter_movie'], 'tvshow' => $_POST['travelogue_filter_tvshow'], 'episode' => $_POST['travelogue_filter_episode'], 'book' => $_POST['travelogue_filter_book'], 'classical' => $_POST['travelogue_filter_classical'], 'comic' => $_POST['travelogue_filter_comic'], 'standup' => $_POST['travelogue_filter_standup'], 'painting' => $_POST['travelogue_filter_painting'], 'photo' => $_POST['travelogue_filter_photo'], 'vidya' => $_POST['travelogue_filter_vidya'], 'architecture' => $_POST['travelogue_filter_architecture'], 'sports' => $_POST['travelogue_filter_sports'], 'live' => $_POST['travelogue_filter_live'], 'other' => $_POST['travelogue_filter_other'], 'favorite' => $_POST['travelogue_filter_code_favorite'], 'lyrics' => $_POST['travelogue_filter_code_lyrics'], 'hifi' => $_POST['travelogue_filter_code_hifi'], 'relaxing' => $_POST['travelogue_filter_code_relaxing'], 'heavy' => $_POST['travelogue_filter_code_heavy'], 'vibe' => $_POST['travelogue_filter_code_vibe'], 'complex' => $_POST['travelogue_filter_code_complex'], 'instrumental' => $_POST['travelogue_filter_code_instrumental'], 'live' => $_POST['travelogue_filter_code_live'], 'lists' => $_POST['travelogue_filter_code_lists'], 'bookmark' => $_POST['travelogue_filter_code_bookmark'], 'thumbsup' => $_POST['travelogue_filter_code_thumbsup'], 'thumbsdown' => $_POST['travelogue_filter_code_thumbsdown'], 'thumbtack' => $_POST['travelogue_filter_code_thumbtack'], 'pointer' => $_POST['travelogue_filter_code_pointer'], 'show_no_code' => $_POST['travelogue_show_no_code'], 'genres' => $_POST['travelogue_filter_genres']);
	}

    if (isset($_POST['trigger_reset_filter'])) {
        unset($_SESSION['travelogue_filter_options']);
    }

	if ($_POST) {
		header("Location: " . $_SERVER['REQUEST_URI']);
		exit();
	}

	include 'templates/html_head.php';
?>
<body class="bg-dark">
<style>
    html {
        font-size: 1rem !important;
    }
</style>
<div class="container-fluid">
    <div class="row sticky-top bg-dark">
		<?php
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => 'col-auto', 'href' => false, 'icon' => 'fas fa-plus', 'id' => 'trigger_add_item', 'modal' => '#modal_add_item'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => 'col-auto', 'href' => false, 'icon' => 'fas fa-filter-list', 'id' => 'trigger_filter', 'modal' => '#modal_filter'));
		?>
    </div>
    <div class="row">
        <div class="col-12">
			<?php
				echo "
                <div class='row mb-3 px-1 sticky-top'>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Codes</span>
                    </div>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Timeline</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Title</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Creator</span>
                    </div>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Genre</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Comments</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400 user-select-none'>Relevant information</span>
                    </div>
                </div>";
				if (!isset($_SESSION['travelogue_sorting'])) {
					$_SESSION['travelogue_sorting'] = false;
				}
				$filter_module = false;
				if (!isset($_SESSION['travelogue_filter'])) {
					$_SESSION['travelogue_filter'] = array('title' => false, 'creator' => false, 'comments' => false, 'otherrelevant' => false, 'type' => false);
					$filter_module = false;
				} else {
					$filter_module = false;
				}
				$order_module = false;
				switch ($_SESSION['travelogue_sorting']) {
					case 'chronological':
						$order_module = "ORDER BY releasedate";
						break;
					case 'biographical':
						$order_module = "ORDER BY datexp DESC, releasedate DESC";
						break;
					case 'alphabetical_creator':
						$order_module = "ORDER BY creator, releasedate";
						break;
					case 'alphabetical_title':
						$order_module = "ORDER BY title, releasedate";
						break;
					case 'alphabetical_genre':
						$order_module = "ORDER BY genre, releasedate";
						break;
					case 'alphabetical_type':
						$order_module = "ORDER BY type, releasedate";
						break;
                    case 'rating':
                        $order_module = "ORDER BY yourrating DESC";
                        break;
					case false:
					default:
					case 'dateadded':
						$order_module = "ORDER BY id";
						break;
				}
				$query = prepare_query("SELECT id, type, codes, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl FROM travelogue WHERE state = 1 $filter_module AND user_id = {$_SESSION['user_id']} $order_module");
				$records = $conn->query($query);
				if ($_SESSION['travelogue_separate_types'] == true) {
					$result = array();
				} else {
					$result = false;
				}
//                $count = 0;
				if ($records->num_rows > 0) {
					while ($record = $records->fetch_assoc()) {
//                        $count++;
//                        if ($count == 20) {
//                            break;
//                        }
						$record['codes'] = unserialize($record['codes']);
						if (isset($_SESSION['travelogue_filter_options'])) {
                            if ($_SESSION['travelogue_filter_options']['genres'] != false) {
                                if (!in_array($record['genre'], $_SESSION['travelogue_filter_options']['genres'])) {
                                    continue;
                                }
                            }
							if ($_SESSION['travelogue_filter_options'][$record['type']] == false) {
								continue;
							}
                            $show_item = false;
                            if ($record['codes'] == false) {
								if ($_SESSION['travelogue_filter_options']['show_no_code'] == false) {
									continue;
								}
                            } else {
								foreach ($record['codes'] as $key => $data) {
									if ($_SESSION['travelogue_filter_options'][$key] == true) {
										$show_item = true;
									}
								}
								if ($show_item == false) {
									continue;
								}
                            }
						}

						$put_together = travelogue_put_together(array('id' => $record['id'], 'type' => $record['type'], 'codes' => $record['codes'], 'releasedate' => $record['releasedate'], 'title' => $record['title'], 'creator' => $record['creator'], 'genre' => $record['genre'], 'datexp' => $record['datexp'], 'yourrating' => $record['yourrating'], 'comments' => $record['comments'], 'otherrelevant' => $record['otherrelevant'], 'dburl' => $record['dburl']), $_SESSION['travelogue_codes']);


                        $highlight_module = 'text-white';
                        $bookmark_module = false;
                        $highlight_icon = false;
						if ($put_together['thumbtack'] == true) {
							$highlight_module = ' text-warning bold';
							$highlight_icon .= "<i class='fa-solid fa-thumbtack fa-lg fa-fw me-1 text-warning align-self-center'></i>";
						}
                        if ($put_together['pointer'] == true) {
                            $highlight_module .= ' bg-primary';
                            $highlight_icon .= "<i class='fa-solid fa-arrow-pointer fa-lg fa-fw me-1 text-white align-self-center'></i>";
                        }
                        if ($put_together['bookmark'] == true) {
                            $bookmark_module .= ' bg-danger p-1 my-1 rounded';
                            $highlight_icon .= "<i class='fa-solid fa-bookmark fa-lg fa-fw me-1 text-danger align-self-center'></i>";
                        }
                        $lists_icon = false;
                        if ($put_together['lists'] == true) {
                            $lists_icon = "<i class='fa-solid fa-award fa-lg fa-fw me-1 text-warning'></i>";
                        }
                        $thumbsup_module = false;
                        if ($put_together['thumbsup'] == true) {
                            $thumbsup_module = "<i class='fa-solid fa-thumbs-up fa-lg ga-gw me-1 text-primary'></i>";
                        }
                        $thumbsdown_module = false;
                        if ($put_together['thumbsdown'] == true) {
                            $thumbsdown_module = "<i class='fa-solid fa-thumbs-down fa-lg ga-gw me-1 text-danger'></i>";
                        }

						$instance = "
                        <div class='row px-1 $bookmark_module' id='travelogue_row_{$record['id']}' value='{$record['id']}'>
                            <div class='col-1 travelogue_col text-white'>{$put_together['codes']}</div>
                            <div class='col-1 travelogue_col text-white user-select-none'>{$put_together['releasedate']}{$put_together['datexp']}</div>
                            <div class='col travelogue_col d-flex justify-content-center $highlight_module'>$highlight_icon {$put_together['title']}</div>
                            <div class='col travelogue_col d-flex justify-content-center $highlight_module'>{$put_together['creator']}</div>
                            <div class='col-1 travelogue_col d-flex justify-content-center text-white user-select-none'>{$put_together['genre']}</div>
                            <div class='col travelogue_col text-white'>{$put_together['yourrating']} $thumbsup_module $thumbsdown_module {$put_together['comments']}</div>
                            <div class='col travelogue_col text-white'>$lists_icon {$put_together['dburl']} {$put_together['otherrelevant']}</div>
                        </div>
                        ";
						if ($_SESSION['travelogue_separate_types'] == true) {
							if (!isset($result[$put_together['type']])) {
								$result[$put_together['type']] = $instance;
							} else {
								$result[$put_together['type']] .= $instance;
							}
						} else {
							$result .= $instance;
						}
					}
				}
				if ($_SESSION['travelogue_separate_types'] == true) {
					$parts = false;
					foreach ($result as $key => $instance) {
						$parts .= $result[$key];
					}
					$result = $parts;
				}
				echo $result;
			?>
        </div>
    </div>
</div>
</body>
<?php
	$template_modal_div_id = 'modal_add_item';
	$template_modal_titulo = 'Add item';
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo .= "
    <form method='post'>
        <div class='mb-3'>
            <label for='travel_new_type' class='form-label'>Type:</label>
            <select id='travel_new_type' name='travel_new_type' type='text' class='form-control'>
                <option value='music'>Music album</option>
                <option value='concert'>Music concert</option>
                <option value='movie'>Movie</option>
                <option value='tvshow'>TV Show</option>
                <option value='episode'>TV Show episode</option>
                <option value='book'>Book</option>
                <option value='classical'>Classical music</option>
                <option value='comic'>Comic Book</option>
                <option value='standup'>Stand-up Show</option>
                <option value='painting'>Painting</option>
                <option value='photo'>Photograph</option>
                <option value='vidya'>Video Game</option>
                <option value='architecture'>Architecture</option>
                <option value='sports'>Sports event</option>
                <option value='live'>Live event</option>
                <option value='other'>Other</option>
            </select>
        </div>
        <div class='mb-3'>
            <label for='travel_new_creator' class='form-label'>Creator:</label>
            <input id='travel_new_creator' name='travel_new_creator' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_title' class='form-label'>Title:</label>
            <input id='travel_new_title' name='travel_new_title' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_release_date' class='form-label'>Release date:</label>
            <input id='travel_new_release_date' name='travel_new_release_date' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_genre' class='form-label'>Genre:</label>
            <input id='travel_new_genre' name='travel_new_genre' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_datexp' class='form-label'>Date experienced:</label>
            <input id='travel_new_datexp' name='travel_new_datexp' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_rating' class='form-label'>Your rating (1 to 5):</label>
            <input type='range' class='form-range' id='travel_new_rating' name='travel_new_rating' min='1' max='5' disabled>
            <button id='trigger_enable_rating' class='btn btn-outline-secondary btn-sm' type='button'>Enable</button>
        </div>
        <div class='mb-3'>
            <label for='travel_new_comments' class='form-label'>Your comments:</label>
            <textarea id='travel_new_comments' name='travel_new_comments' type='textarea' class='form-control' rows='3'></textarea>
        </div>
        <div class='mb-3'>
            <label for='travel_new_information' class='form-label'>Relevant information:</label>
            <textarea id='travel_new_information' name='travel_new_information' type='text' class='form-control' rows='3'></textarea>
        </div>
        <div class='mb-3'>
            <label for='travel_new_database' class='form-label'>Database link (Wikipedia, IMDb, AllMusic etc.):</label>
            <input id='travel_new_database' name='travel_new_database' type='url' class='form-control'>
        </div>";
	$template_modal_body_conteudo .= "<div class='mb-3'>
					<h3>Codes</h3>
				";
	foreach ($_SESSION['travelogue_codes'] as $key => $info) {
		$color = nexus_colors(array('mode' => 'convert', 'color' => $_SESSION['travelogue_codes'][$key][0]['color']));
		$template_modal_body_conteudo .= "
					<div class='form-check'>
						<input name='travel_new_code_$key' id='travel_new_code_$key' class='form-check-input' type='checkbox' value='$key'>
						<label for='travel_new_code_$key' class='form-check-label'><i class='{$_SESSION['travelogue_codes'][$key][0]['icon']} me-2 {$color['link-color']} bg-dark p-1 rounded fa-fw'></i>$key</label>
					</div>
				";
	}
	$template_modal_body_conteudo .= "</div>";
	$template_modal_body_conteudo .= "
        <button type='submit' class='btn btn-primary'>Submit</button>
    </form>
    ";
    $modal_scrollable = true;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_update_entry';
	$template_modal_titulo = 'Update item';
	$template_modal_body_conteudo = 'Loading...';
    $modal_scrollable = true;
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_filter';
	$template_modal_titulo = 'Filter';
	$template_modal_body_conteudo = 'Loading...';
	$modal_scrollable = true;
	include 'templates/modal.php';
?>
<script>
    $(document).on('click', '#trigger_enable_rating', function () {
        $(this).addClass('d-none');
        $("#travel_new_rating").removeAttr("disabled");
    })
    $(document).on('click', '#update_trigger_enable_rating', function () {
        $(this).addClass('d-none');
        $("#update_travel_new_rating").removeAttr("disabled");
    })
    $(document).on('click', '.edit_this_log', function () {
        log_id = $(this).attr('value');
        $.post('engine.php', {
            'edit_this_log': log_id
        }, function (data) {
            if (data != 0) {
                $('#body_modal_update_entry').html(data);
            } else {
                alert('Something went wrong');
            }
        })
    })
    $(document).on('click', '#trigger_filter', function () {
        $.post('engine.php', {
            'load_filter_modal': true
        }, function (data) {
            if (data != 0) {
                $('#body_modal_filter').html(data);
            } else {
                alert('Something went wrong');
            }
        })
    })
    $(document).on('click', '#delete_this_entry', function () {
        del_confirm = confirm('Do you really want to delete this?');
        if (del_confirm == true) {
            del_id = $(this).attr('value');
            $.post('engine.php', {
                'delete_this_entry': del_id
            }, function (data) {
                if (data != 0) {
                    $('#travelogue_row_' + del_id).addClass('d-none');
                    alert('Entry deleted');
                } else {
                    alert('Something went wrong');
                }
            })
        }
    })

    $(document).on('click', '#unselect_codes', function() {
        $( ".code_filter_option" ).prop( "checked", false );
    })

    $(document).on('click', '#unselect_types', function() {
        $( ".type_filter_option" ).prop( "checked", false );
    })
    $(document).on('click', '#select_codes', function() {
        $( ".code_filter_option" ).prop( "checked", true );
    })

    $(document).on('click', '#select_types', function() {
        $( ".type_filter_option" ).prop( "checked", true );
    })

    $(document).on('click', '#enable_genre_filter', function() {
        $(this).addClass('d-none');
        $("#travelogue_filter_genres").removeAttr("disabled");
    })


</script>
<?php
	include 'templates/html_bottom.php';
?>
</html>
