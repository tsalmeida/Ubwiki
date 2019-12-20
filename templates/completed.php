<?php

	echo "
        <script type='text/javascript'>
            $('#add_completed').click(function () {
                $.post('engine.php', {
                    'completed_change': true,
                    'completed_pagina_id': $pagina_id,
                });
                $('#add_completed').hide();
                $('#remove_completed').show();
            });
            $('#remove_completed').click(function () {
                $.post('engine.php', {
                    'completed_change': false,
                    'completed_pagina_id': $pagina_id,
                });
                $('#add_completed').show();
                $('#remove_completed').hide();
            });
        </script>
    ";
	