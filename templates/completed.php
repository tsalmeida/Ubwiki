<?php
	
	if (isset($topico_id)) {
		$page_id = $topico_id;
		$bookmark_contexto = 'verbete';
	} elseif (isset($elemento_id)) {
		$page_id = $elemento_id;
		$bookmark_contexto = 'elemento';
	}

	echo "
        <script type='text/javascript'>
            $('#add_completed').click(function () {
                $.post('engine.php', {
                    'completed_change': true,
                    'completed_page_id': $page_id,
                    'completed_user_id': $user_id
                });
                $('#add_completed').hide();
                $('#remove_completed').show();
            });
            $('#remove_completed').click(function () {
                $.post('engine.php', {
                    'completed_change': false,
                    'completed_page_id': $page_id,
                    'completed_user_id': $user_id
                });
                $('#add_completed').show();
                $('#remove_completed').hide();
            });
        </script>
    ";
	
	unset($page_id);