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
<style>
    .ql-editor {
        font-size: 1.5em;
        font-family: 'Roboto Mono', SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;;
    }
</style>
<div class="container-fluid bg-light min-vh-90">
    <div class="row d-flex justify-content-around min-vh-90">
        <div class="col-8 p-limit pagina_coluna bg-white border rounded mt-1 quill_pagina_de_edicao min-vh-50">
            <!-- Include stylesheet -->
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

            <!-- Create the editor container -->
            <div id="editor" class="ql_editor_height font-serif border rounded">

            </div>

            <!-- Include the Quill library -->
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

            <!-- Initialize Quill editor -->
            <script>
                let quill = new Quill('#editor');
            </script>
        </div>
        <div class="col-3 pagina_coluna bg-white border rounded mt-1 p-2">
            <h1>Find repeated words</h1>
            <div class="mb-3">
                <button type="button" id="trigger_analyse_repeats" class="btn btn-primary">Analyze</button>
            </div>
            <ul id="repeated_words_list" class="list-group py-2">

            </ul>
        </div>
    </div>
</div>
<?php
	include 'pagina/modal_languages.php';
?>
</body>
<script type="text/javascript">
    $(document).on('click', '#trigger_analyse_repeats', function () {
        var text = quill.getText();
        text = encodeURIComponent(text);
        // text = btoa(text);
        $.post('engine.php', {
            'find_repeats_text': text
        }, function (data) {
            if (data != 0) {
                $('#repeated_words_list').empty();
                $('#repeated_words_list').append(data);
            }
        });
    })
</script>
<?php
	include 'templates/html_bottom.php';
?>
</html>
