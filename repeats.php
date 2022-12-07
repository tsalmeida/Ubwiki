<?php
	include 'engine.php';
	$pagina_tipo = 'repeats';
    $pagina_id = $_SESSION['user_escritorio'];
	include 'templates/html_head.php';
?>
    <body class="bg-light">
    <?php
        $pagina_padrao = true;
        include 'templates/navbar.php';
        ?>
        <div class="container-fluid bg-light">
            <div class="row d-flex justify-content-around">
                <div class="col-8 p-limit pagina_coluna bg-white border rounded mt-1 quill_pagina_de_edicao min-vh-50">
                    <!-- Include stylesheet -->
                    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

                    <!-- Create the editor container -->
                    <div id="editor" clacc="ql_editor_height">
                        <p>Hello World!</p>
                        <p>Some initial <strong>bold</strong> text</p>
                        <p><br></p>
                    </div>

                    <!-- Include the Quill library -->
                    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

                    <!-- Initialize Quill editor -->
                    <script>
                        var quill = new Quill('#editor', {
                            theme: 'snow'
                        });
                    </script>
                </div>
                <div class="col-3 pagina_coluna bg-white border rounded mt-1">
                    <ul class="list-group py-2">
                        <li class="list-group-item active">Lista de palavras repetidas</li>
                        <li class="list-group-item">Palavras repetidas</li>
                        <li class="list-group-item">Palavras repetidas</li>
                        <li class="list-group-item">Palavras repetidas</li>
                        <li class="list-group-item">Palavras repetidas</li>
                        <li class="list-group-item">Palavras repetidas</li>
                    </ul>
                </div>
            </div>
        </div>
    <?php
        include 'pagina/modal_languages.php';
    ?>
    </body>
<?php
	include 'templates/html_bottom.php';
