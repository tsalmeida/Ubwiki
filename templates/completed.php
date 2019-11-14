<?php
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