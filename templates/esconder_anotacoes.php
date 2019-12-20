<?php
	if (!isset($anotacoes_id)) {
		$anotacoes_id = 'anotacoes';
	}
	echo "
<script type='text/javascript'>
    $('#mostrar_coluna_direita').hide();
    $('#mostrar_coluna_esquerda').hide();
    $('#esconder_{$anotacoes_id}').click(function () {
        $('#coluna_direita').hide();
        $('#mostrar_coluna_direita').show();
        $('#coluna_esquerda').addClass('col-lg-7');
        $('#coluna_esquerda').removeClass('col-lg-6');
    });
    $('#mostrar_coluna_direita').click(function () {
        $('.{$anotacoes_id}_collapse').show();
        $('#mostrar_{$anotacoes_id}').hide();
        $('#mostrar_coluna_direita').hide();
        $('#coluna_direita').show();
        $('#coluna_esquerda').addClass('col-lg-6');
        $('#coluna_esquerda').removeClass('col-lg-7');
    })
    $('#mostrar_coluna_esquerda').click(function () {
        $('.{$anotacoes_id}_collapse').show();
        $('#mostrar_{$anotacoes_id}').hide();
        $('#mostrar_coluna_esquerda').hide();
        $('#esconder_coluna_esquerda').show();
        $('#coluna_esquerda').show();
        $('#coluna_direita').addClass('col-lg-6');
        $('#coluna_direita').removeClass('col-lg-7');
    })
    $('#esconder_coluna_esquerda').click(function () {
        $('.{$anotacoes_id}_collapse').show();
        $('#mostrar_{$anotacoes_id}').hide();
        $('#esconder_coluna_esquerda').hide();
        $('#mostrar_coluna_esquerda').show();
        $('#coluna_esquerda').hide();
        $('#coluna_direita').addClass('col-lg-7');
        $('#coluna_direita').removeClass('col-lg-6');
    })
</script>
";
?>