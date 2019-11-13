<?php
	
	if (!isset($verbete_vazio)) {
		$verbete_vazio = false;
	}
	
	echo "
    <!-- Bootstrap tooltips -->
    <script type='text/javascript' src='js/popper.min.js'></script>
    <!-- Bootstrap core JavaScript -->
    <script type='text/javascript' src='js/bootstrap.min.js'></script>
    <!-- MDB core JavaScript -->
    <script type='text/javascript' src='js/mdb.min.js'></script>
    ";
	
	if ($verbete_vazio == true) {
		echo "
		<script type='text/javascript'>
			$('#destravar_verbete').click(function () {
				$('#verbete_vazio').hide();
	    });
		</script>
	";
	}
	
	unset($verbete_vazio);