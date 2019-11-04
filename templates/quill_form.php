<?php
return "
    <form id='$template_quill_form_id' method='post'>
        <input name='$template_quill_conteudo_html' type='hidden'>
        <input name='$template_quill_conteudo_text' type='hidden'>
        <input name='$template_quill_conteudo_content' type='hidden'>
        <div class='row'>
            <div class='container col-12'>
                <div id='$template_quill_container_id'>
                    <div id='$template_quill_editor_id' class='$template_quill_editor_classes'>
                         $template_quill_conteudo_opcional
                    </div>
                </div>
            </div>
        </div>
        <div class='row justify-content-center $template_quill_botoes_collapse_stuff mt-3'>
            <button type='button' class='btn btn-light btn-sm'><i
                    class='fal fa-times-circle fa-fw'
                    ></i> Cancelar
            </button>
            <button type='submit' class='btn btn-primary btn-sm'><i class='fal fa-check fa-fw'></i>
                Salvar
            </button>
        </div>
    </form>
";
?>