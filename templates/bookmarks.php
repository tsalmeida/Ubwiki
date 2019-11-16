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
            $('#add_bookmark').click(function () {
                $.post('engine.php', {
                    'bookmark_change': true,
                    'bookmark_page_id': $page_id,
                    'bookmark_user_id': $user_id,
                    'bookmark_contexto': '$bookmark_contexto'
                });
                $('#add_bookmark').hide();
                $('#remove_bookmark').show();
            });
            $('#remove_bookmark').click(function () {
                $.post('engine.php', {
                    'bookmark_change': false,
                    'bookmark_page_id': $page_id,
                    'bookmark_user_id': $user_id,
                    'bookmark_contexto': '$bookmark_contexto'
                });
                $('#add_bookmark').show();
                $('#remove_bookmark').hide();
            });
        </script>
    ";
	
	unset($page_id);