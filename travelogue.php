<?php
	include 'engine.php';
	$pagina_tipo = 'travelogue';
	$pagina_id = $_SESSION['user_escritorio'];
	$pagina_title = 'Travelogue';
	$pagina_favicon = 'faticon.ico';

	if (isset($_POST['travel_new_type'])) {
		if (!isset($_POST['travel_new_release_date'])) {
			$_POST['travel_new_release_date'] = false;
		}
		if (!isset($_POST['travel_new_title'])) {
			$_POST['travel_new_title'] = false;
		}
		if (!isset($_POST['travel_new_creator'])) {
			$_POST['travel_new_creator'] = false;
		}
		if (!isset($_POST['travel_new_genre'])) {
			$_POST['travel_new_genre'] = false;
		}
		if (!isset($_POST['travel_new_datexp'])) {
			$_POST['travel_new_datexp'] = false;
		}
		if (!isset($_POST['travel_new_rating'])) {
			$_POST['travel_new_rating'] = false;
		}
		if (!isset($_POST['travel_new_comments'])) {
			$_POST['travel_new_comments'] = false;
		}
		if (!isset($_POST['travel_new_information'])) {
			$_POST['travel_new_information'] = false;
		}
		if (!isset($_POST['travel_new_database'])) {
			$_POST['travel_new_database'] = false;
		}
		$query = prepare_query("INSERT INTO travelogue (user_id, type, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl) VALUES ({$_SESSION['user_id']}, '{$_POST['travel_new_type']}', '{$_POST['travel_new_release_date']}', '{$_POST['travel_new_title']}', '{$_POST['travel_new_creator']}', '{$_POST['travel_new_genre']}', '{$_POST['travel_new_datexp']}', '{$_POST['travel_new_rating']}', '{$_POST['travel_new_comments']}', '{$_POST['travel_new_information']}', '{$_POST['travel_new_database']}')", 'log');
		$conn->query($query);
	}

	unset($_POST);

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
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'yellow', 'class' => 'col-auto ms-5', 'href' => false, 'icon' => 'fas fa-plus', 'id' => 'trigger_add_class', 'modal' => '#modal_add_class'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => 'col-auto ms-5', 'href' => false, 'icon' => 'fas fa-filter-list', 'id' => 'trigger_filter', 'modal' => '#modal_filter'));
		?>
    </div>
    <div class="row">
        <div class="col-12">
			<?php
				$query = prepare_query("SELECT id, type, codes, releasedate, title, creator, genre, datexp, yourrating, comments, otherrelevant, dburl FROM travelogue WHERE state = 1 AND user_id = {$_SESSION['user_id']}");
				$records = $conn->query($query);
                $row_codes = false;
                $row_releasedate = false;
                $row_title = false;
                $row_creator = false;
                $row_genre = false;
                $row_datexp = false;
                $row_yourrating = false;
                $row_comments = false;
                $row_otherrelevant = false;
                $row_dburl = false;
				if ($records->num_rows > 0) {
					while ($record = $records->fetch_assoc()) {
						$put_together = travelogue_put_together(array('id' => $record['id'], 'codes' => $record['codes'], 'releasedate' => $record['releasedate'], 'title' => $record['title'], 'creator' => $record['creator'], 'genre' => $record['genre'], 'datexp' => $record['datexp'], 'yourrating' => $record['yourrating'], 'comments' => $record['comments'], 'otherrelevant' => $record['otherrelevant'], 'dburl' => $record['dburl']));
						$row_codes .= $put_together['codes'];
						$row_releasedate .= $put_together['releasedate'];
						$row_title .= $put_together['title'];
						$row_creator .= $put_together['creator'];
						$row_genre .= $put_together['genre'];
						$row_datexp .= $put_together['datexp'];
						$row_yourrating .= $put_together['yourrating'];
						$row_comments .= $put_together['comments'];
						$row_otherrelevant .= $put_together['otherrelevant'];
						$row_dburl .= $put_together['dburl'];
					}
				}
				echo "
                <div class='row mb-3 px-1 sticky-top'>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Codes</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_codes
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Release date</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_releasedate
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Title</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_title
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Creator</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_creator
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Genre</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_genre
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Date experienced</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_datexp
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Your rating</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_yourrating
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Comments</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_comments
                    </div>
                    <div class='col-auto travelogue_col'>
                        <div class='row travelogue_item'>
                            <div class='col d-flex justify-content-between'>
                                <span class='text-white font-half-condensed-400'>Relevant information</span>
                                <a href='javascript:void(0);'><i class='fas fa-filter-list link-success'></i></a>
                            </div>
                        </div>
                        $row_otherrelevant
                    </div>
                </div>";
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
                <option value='1'>Music album</option>
                <option value='2'>Movie</option>
                <option value='3'>Painting</option>
                <option value='4'>Architecture</option>
                <option value='5'>Sports event</option>
                <option value='6'>Live event</option>
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
            <input type='range' class='form-range' id='travel_new_rating' min='1' max='5' disabled>
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

	$template_modal_div_id = 'modal_del_item';
	$template_modal_titulo = 'Del item';
	$template_modal_body_conteudo = 'Loading...';
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_add_class';
	$template_modal_titulo = 'Add class';
	$template_modal_body_conteudo = 'Loading...';
	include 'templates/modal.php';

	$template_modal_div_id = 'modal_filter';
	$template_modal_titulo = 'Filter';
	$template_modal_body_conteudo = 'Loading...';
	include 'templates/modal.php';
?>
<script>
    $(document).on('click', '#trigger_enable_rating', function () {
        $(this).addClass('d-none');
        $("#travel_new_rating").removeAttr("disabled");
    })


</script>
<?php
	include 'templates/html_bottom.php';
?>
</html>
