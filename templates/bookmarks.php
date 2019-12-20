<?php
	
	echo "
        <script type='text/javascript'>
            $('#add_bookmark').click(function () {
                $.post('engine.php', {
                    'bookmark_change': true,
                    'bookmark_pagina_id': $pagina_id,
                });
                $('#add_bookmark').hide();
                $('#remove_bookmark').show();
            });
            $('#remove_bookmark').click(function () {
                $.post('engine.php', {
                    'bookmark_change': false,
                    'bookmark_pagina_id': $pagina_id,
                });
                $('#add_bookmark').show();
                $('#remove_bookmark').hide();
            });
        </script>
    ";