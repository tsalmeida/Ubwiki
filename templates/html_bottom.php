<?php
	
	if (!isset($html_bottom_template_quill)) {
		$html_bottom_template_quill = false;
	}

?>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
    <script type="text/javascript" charset="UTF-8" src="engine.js"></script>
<?php
	if ($html_bottom_template_quill == true) {
		echo "<script src='https://cdn.quilljs.com/1.3.6/quill.js'></script>";
	}
	
	