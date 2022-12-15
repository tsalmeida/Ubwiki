<?php
	include 'engine.php';
	$pagina_tipo = 'travelogue';
	$pagina_id = $_SESSION['user_escritorio'];
	$pagina_title = 'Travelogue';
	$pagina_favicon = 'faticon.ico';
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
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'red', 'class' => 'col-auto', 'href' => false, 'icon' => 'fas fa-minus', 'id' => 'trigger_del_item', 'modal' => '#modal_del_item'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'yellow', 'class' => 'col-auto ms-5', 'href' => false, 'icon' => 'fas fa-plus', 'id' => 'trigger_add_class', 'modal' => '#modal_add_class'));
			echo nexus_put_together(array('type' => 'navbar', 'color' => 'purple', 'class' => 'col-auto ms-5', 'href' => false, 'icon' => 'fas fa-filter-list', 'id' => 'trigger_filter', 'modal' => '#modal_filter'));
		?>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row mb-3 px-1 sticky-top">
                <div class="col-1 bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Codes</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col-1 bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Release date</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Title</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Creator</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col-1 bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Genre</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col-1 bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Date experienced</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col-1 bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Your rating</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Comments</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
                <div class="col bg-darker rounded border border-1 border-dark text-center py-2 d-flex justify-content-between"><span class="text-white font-half-condensed-400">Relevant information</span><a href="javascript:void(0);"><i class="fas fa-filter-list link-success"></i></a></div>
            </div>
			<?php
				echo travelogue_put_together(array('code' => '110100011', 'year' => '1956', 'title' => 'Cole Porter Songbook', 'artist' => 'Ella Fitzgerald', 'genre' => 'Jazz Standards', 'experienced' => true, 'grade' => '5/5', 'comment' => 'Absolutely wonderful', 'info' => false));
				echo travelogue_put_together(array('code' => '010000111', 'year' => '1951', 'title' => 'The Genius of Modern Music, Vol. 1', 'artist' => 'Thelonious Monk', 'genre' => 'Jazz', 'experienced' => '2020/11/29', 'grade' => '5/5', 'comment' => false, 'info' => 'Recorded in 1947 and 1948.'));
				echo travelogue_put_together(array('code' => '111101000', 'year' => '1997', 'title' => 'Time Out of Mind', 'artist' => 'Bob Dylan', 'genre' => 'Singer-Songwriter', 'experienced' => '2005', 'grade' => '5/5', 'comment' => 'Great memories of listening to this.', 'info' => false));
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
            <label for='travel_new_release_date' class='form-label'>Release date:</label>
            <input id='travel_new_release_date' name='travel_new_release_date' type='text'>
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
<script></script>
<?php
	include 'templates/html_bottom.php';
?>
</html>
