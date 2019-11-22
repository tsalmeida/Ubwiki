<?php
	
	if (isset($topico_id)) {
		$page_id = $topico_id;
		$contexto = 'verbete';
	} elseif (isset($elemento_id)) {
		$page_id = $elemento_id;
		$contexto = 'elemento';
	} elseif (isset($user_id)) {
		$page_id = false;
		$contexto = 'userpage';
	}
	
	echo "
<script type='text/javascript'>
    function imageHandler() {
        var range = this.quill.getSelection();
        var link = prompt('Qual o endereço da imagem?');
        var titulo = prompt('Título da imagem:');
        var value64 = btoa(link);
        if (link) {
            $.post('engine.php', {
                    'nova_imagem': value64,
                    'user_id': $user_id,
                    'page_id': '$page_id',
                    'nova_imagem_titulo': titulo,
                    'contexto': '$contexto'
                },
                function (data) {
                });
            this.quill.insertEmbed(range.index, 'image', link, Quill.sources.USER);
        }
    }function imageHandler_privado() {
        var range = this.quill.getSelection();
        var link = prompt('Qual o endereço da imagem?');
        var titulo = prompt('Título da imagem:');
        var value64 = btoa(link);
        if (link) {
            $.post('engine.php', {
                    'nova_imagem_privada': value64,
                    'user_id': $user_id,
                    'page_id': '$page_id',
                    'nova_imagem_titulo': titulo,
                    'contexto': '$contexto'
                },
                function (data) {
                });
            this.quill.insertEmbed(range.index, 'image', link, Quill.sources.USER);
        }
    }
</script>";