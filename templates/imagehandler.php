<?php
	
	if (isset($tema_id)) {
		$page_id = $tema_id;
		$contexto = 'verbete';
	}
	elseif (isset($elemento_id)) {
		$page_id = $elemento_id;
		$contexto = 'elemento';
	}
	elseif (isset($user_id)) {
		$page_id = false;
		$contexto = 'userpage';
	}
	
	echo "
<script type='text/javascript'>
    function imageHandler() {
        var range = this.quill.getSelection();
        var value = prompt('Qual o endereço da imagem?');
        var value64 = btoa(value);
        if (value) {
            $.post('engine.php', {
                    'nova_imagem': value64,
                    'user_id': $user_id,
                    'page_id': $page_id,
                    'contexto': '$contexto'
                },
                function (data) {
                });
            this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
        }
    }
</script>";