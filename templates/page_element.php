<?php
	return "
<div id='$template_id' class='$template_collapse_stuff show mb-5 border-top border-light pt-4'>
    <div class='row'>
        <div class='col-12 d-flex justify-content-between'>
            <h1>$template_titulo</h1>
            <span class='h5'>
                $template_botoes
            </span>
        </div>
    </div>
    
    
    <div class='row py-3'>
        <div class='col-12'>
            $template_conteudo
        </div>
    </div>
</div>
";
?>