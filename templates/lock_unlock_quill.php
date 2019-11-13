<?php
    if (!isset($template_sem_verbete)) {
        $template_sem_verbete = false;
    }
	echo "<script type='text/javascript'>";
	if ($template_sem_verbete == false) {
		echo "$('.ql-toolbar:first') . hide();";
	}
	echo "$('#travar_verbete').click(function () {
        verbete_editor.disable();
        $('#destravar_verbete').show();
        $('#travar_verbete').hide();
        $('.ql-toolbar:first').hide();
        $('.ql-editor:first').removeClass('ql-editor-active');
    });
    $('#destravar_verbete').click(function () {
        verbete_editor.enable();
        $('#travar_verbete').show();
        $('#destravar_verbete').hide();
        $('.ql-toolbar:first').show();
        $('.ql-editor:first').addClass('ql-editor-active');
    });
    $('#travar_anotacao').click(function () {
        anotacoes_editor.disable();
        $('#travar_anotacao').hide();
        $('#destravar_anotacao').show();
        $('.ql-toolbar:last').hide();
        $('.ql-editor:last').removeClass('ql-editor-active');
    });
    $('.ql-editor:last').addClass('ql-editor-active');
    $('#destravar_anotacao').click(function () {
        anotacoes_editor.enable();
        $('#destravar_anotacao').hide();
        $('#travar_anotacao').show();
        $('.ql-toolbar:last').show();
        $('.ql-editor:last').addClass('ql-editor-active');
    });
</script>";
	unset($template_sem_verbete);