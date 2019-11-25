<?php
	
	if (!isset($verbete_vazio)) {
		$verbete_vazio = false;
	}
	if (!isset($mdb_select)) {
		$mdb_select = false;
	}
	if (!isset($gabarito)) {
		$gabarito = false;
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
	
	if ($mdb_select == true) {
		echo "
			<script type='text/javascript'>
				$(document).ready(function() {
					$('.mdb-select').materialSelect()
				});
			</script>
		";
	}

	if ($gabarito == true) {
		echo "
		<script type='text/javascript'>
			$('#mostrar_gabarito').click(function () {
				$('.list-group-item').removeClass('list-group-item-light');
				$('#mostrar_gabarito').hide();
	    });
		</script>
		";
	}
	