<?php
	include 'engine.php';
	$pagina_tipo = 'travelogue';
	$pagina_id = $_SESSION['user_escritorio'];
	$pagina_title = 'Travelogue';
	$pagina_favicon = 'travelogue.ico';

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
		$query = prepare_query("INSERT INTO travelogue (user_id, type, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl) VALUES ({$_SESSION['user_id']}, '{$_POST['travel_new_type']}', '{$_POST['travel_new_release_date']}', '{$_POST['travel_new_title']}', '{$_POST['travel_new_creator']}', '{$_POST['travel_new_genre']}', '{$_POST['travel_new_datexp']}', '{$_POST['travel_new_rating']}', '{$_POST['travel_new_comments']}', '{$_POST['travel_new_information']}', '{$_POST['travel_new_database']}')", 'log');
		$conn->query($query);
	}

	if (!isset($_SESSION['travelogue_separate_types'])) {
		$_SESSION['travelogue_separate_types'] = true;
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

    #wider-container {
        width: 150vw;
        overflow-x: scroll;
    }
</style>
<div class="container-fluid">
    <div class="row sticky-top bg-dark">
		<?php
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'teal', 'class' => 'col-auto', 'href' => false, 'icon' => 'fas fa-plus', 'id' => 'trigger_add_item', 'modal' => '#modal_add_item'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'red', 'class' => 'col-auto', 'href' => false, 'icon' => 'fas fa-minus', 'id' => 'trigger_del_item', 'modal' => '#modal_del_item'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => 'col-auto ms-5', 'href' => false, 'icon' => 'fas fa-filter-list', 'id' => 'trigger_filter', 'modal' => '#modal_filter'));
		?>
    </div>
    <div class="row">
        <div class="col-12">
			<?php
				echo "
                <div class='row mb-3 px-1 sticky-top'>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Codes</span>
                    </div>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Timeline</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Title</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Creator</span>
                    </div>
                    <div class='col-1 travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Genre</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Comments</span>
                    </div>
                    <div class='col travelogue_col text-center'>
                                <span class='text-white font-half-condensed-400'>Relevant information</span>
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
					case false:
					default:
					case 'chronological':
						$order_module = "ORDER BY releasedate";
				}
				$query = prepare_query("SELECT id, type, codes, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl FROM travelogue WHERE state = 1 $filter_module AND user_id = {$_SESSION['user_id']} $order_module", 'log');
				$records = $conn->query($query);
				if ($_SESSION['travelogue_separate_types'] == true) {
					$result = array();
				} else {
					$result = false;
				}
				if ($records->num_rows > 0) {
					while ($record = $records->fetch_assoc()) {
						$put_together = travelogue_put_together(array('id' => $record['id'], 'type' => $record['type'], 'codes' => $record['codes'], 'releasedate' => $record['releasedate'], 'title' => $record['title'], 'creator' => $record['creator'], 'genre' => $record['genre'], 'datexp' => $record['datexp'], 'yourrating' => $record['yourrating'], 'comments' => $record['comments'], 'otherrelevant' => $record['otherrelevant'], 'dburl' => $record['dburl']), $_SESSION['travelogue_codes']);
						$instance = "
                        <div class='row px-1'>
                            <div class='col-1 travelogue_col'><a href='javascript:void(0);' value='{$record['id']}' class='rounded link-dark bg-light me-2 edit_this_log' data-bs-toggle='modal' data-bs-target='#modal_update_entry'><i class='fas fa-pen-to-square fa-fw'></i></a>{$put_together['codes']}</div>
                            <div class='col-1 travelogue_col'>{$put_together['releasedate']}{$put_together['datexp']}</div>
                            <div class='col travelogue_col d-flex justify-content-center'>{$put_together['title']}</div>
                            <div class='col travelogue_col d-flex justify-content-center'>{$put_together['creator']}</div>
                            <div class='col-1 travelogue_col d-flex justify-content-center'>{$put_together['genre']}</div>
                            <div class='col travelogue_col'>{$put_together['comments']}</div>
                            <div class='col travelogue_col'>{$put_together['otherrelevant']}</div>
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
                <option value='vidya'>Video Game</option>
                <option value='architecture'>Architecture</option>
                <option value='sports'>Sports event</option>
                <option value='live'>Live event</option>
                <option value='other'>Other</option>
            </select>
        </div>
        <div class='mb-3'>
            <label for='travel_new_release_date' class='form-label'>Release date:</label>
            <input id='travel_new_release_date' name='travel_new_release_date' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_title' class='form-label'>Title:</label>
            <input id='travel_new_title' name='travel_new_title' type='text' class='form-control'>
        </div>
        <div class='mb-3'>
            <label for='travel_new_creator' class='form-label'>Creator:</label>
            <input id='travel_new_creator' name='travel_new_creator' type='text' class='form-control'>
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
        </div>
        <button type='submit' class='btn btn-primary'>Submit</button>
    </form>
    ";
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_update_entry';
	$template_modal_titulo = 'Update item';
	$template_modal_body_conteudo = 'Loading...';
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_del_item';
	$template_modal_titulo = 'Del item';
	$template_modal_body_conteudo = 'Loading...';
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_filter';
	$template_modal_titulo = 'Filter';
	$template_modal_show_buttons = true;
	$template_modal_body_conteudo = false;
	$template_modal_body_conteudo = "
            <h3>Order</h3>
	        <div class='mb-2'>
	            <label class='form-label' for='travelogue_sorting'>How to order entries:</label>
	            <select class='form-select' id='travelogue_sorting' name='travelogue_sorting'>
	                <option value='chronological'>Chronological by release date</option>
	                <option value='biographical'>Chronological by date experienced</option>
	                <option value='alphabetical_creator'>Alphabetical by creator name</option>
	                <option value='alphabetical_title'>Alphabetical by title</option>
	                <option value='alphabetical_genre'>Alphabetical by genre</option>
	                <option value='alphabetical_type'>Alphabetical by type</option>
                </select>
            </div>
            <div class='form-check'>
                <input name='travelogue_separate_types' id='travelogue_separate_types' class='form-check-input' type='checkbox' checked>
                <label for='travelogue_separate_types' class='form-check-label'>Separate entries by type</label>    
            </div>
            <hr>
            <h3>Filter</h3>
            <p>Show only the following entry types:</p>
            <div class='form-check'>
                <input name='travelogue_filter_music' id='travelogue_filter_music' class='form-check-input' type='checkbox'>
                <label for='travelogue_filter_music' class='form-check-label'>Music</label>
            </div>
            <div class='form-check'>
                <input name='travelogue_filter_movie' id='travelogue_filter_movie' class='form-check-input' type='checkbox'>
                <label for='travelogue_filter_movie' class='form-check-label'>Movie</label>
            </div>
            <div class='form-check'>
                <input name='travelogue_filter_sports' id='travelogue_filter_sports' class='form-check-input' type='checkbox'>
                <label for='travelogue_filter_sports' class='form-check-label'>Sports</label>
            </div>
	";
	include 'templates/modal.php';
?>
<script>
    $(document).on('click', '#trigger_enable_rating', function () {
        $(this).addClass('d-none');
        $("#travel_new_rating").removeAttr("disabled");
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


</script>
<?php
	include 'templates/html_bottom.php';
?>
</html>
