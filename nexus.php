<?php

    //TODO: Trazer o Nexus a um nível básico que permita o uso.

	$pagina_tipo = 'nexus';
	include 'engine.php';
	$pagina_id = return_pagina_id($user_id, 'nexus');
	if ($user_id != 1) {
		header('Location:ubwiki.php');
		exit();
	}
	if ($user_email == false) {
		header('Location:ubwiki.php');
		exit();
	}
	$pagina_info = return_pagina_info($pagina_id, true, true, true);
	if ($pagina_info != false) {
		$pagina_criacao = $pagina_info[0];
		$pagina_item_id = (int)$pagina_info[1];
		$pagina_tipo = $pagina_info[2];
		$pagina_estado = (int)$pagina_info[3];
		$pagina_compartilhamento = $pagina_info[4];
		$pagina_user_id = (int)$pagina_info[5];
		$pagina_titulo = $pagina_info[6];
		$pagina_etiqueta_id = (int)$pagina_info[7];
		$pagina_subtipo = $pagina_info[8];
		$pagina_publicacao = $pagina_info[9];
		$pagina_colaboracao = $pagina_info[10];
	} else {
		header('Location:ubwiki.php');
		exit();
	}
	include 'templates/html_head.php';
?>
    <body class="grey lighten-5">
    <div class="container mt-5">
        <h1 id="page_title" class="fontstack-mono text-center"><?php echo $pagina_titulo; ?></h1>
        <h2 id="titulo" class="fontstack-mono rounded p-2 pl-3 my-1 text-white lighten-1"></h2>
        <h2 id="param1" class="fontstack-mono text-muted rounded p-2 pl-3 my-1"></h2>
        <h2 id="param2" class="fontstack-mono text-muted rounded p-2 pl-3 my-1"></h2>
        <h2 id="subtitulo" class="fontstack-mono text-muted rounded p-2 pl-3 my-1"></h2>
    </div>
    <div class="container">
        <div class="row d-flex justify-content-around mt-3">
            <div class="md-form input-group input-group-lg">
                <input type="hidden" id="cmd" value="go">
                <input type="hidden" id="title">
                <input id="cmdbar" type="text" class="form-control text-center fontstack-mono" placeholder="<?php echo $user_apelido; ?> commands…">
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        var update_param1 = function (data) {
            if (data == false) {
                update_cmd('x');
            } else {
                $('#param1').text(data)
            }
            switch (cmd) {
                case 'add link':
                    update_sub('where? -> url [enter]');
                    break;
                case 'set title':
                case 'del link':
                    update_sub('confirm? -> [enter]');
                    break;
            }
        }
        var update_param2 = function (data) {
            if (data == false) {
                update_cmd('x');
            } else {
                $('#param2').text(data)
            }
            switch (cmd) {
                case 'add link title':
                    update_sub('confirm? -> [Enter]')
                    break;
            }
        }
        var update_title = function (data) {
            if (data == false) {
                data = $('#cmd').val();
            }
            $('#titulo').text(data);
            $('#subtitulo').val('');
            $('#cmdbar').val('');
            $('#cmdbar').focus();
            $('#titulo').removeClass('green');
            $('#titulo').removeClass('cyan');
            $('#titulo').removeClass('orange');
            $('#titulo').removeClass('teal');
            switch (data) {
                case 'go':
                case 'add link title url':
                case 'del link title':
                case 'set title confirm':
                    $('#titulo').addClass('green'); // means one stroke away from the command going through
                    break;
                case 'x':
                    $('#titulo').addClass('cyan'); // means command selection
                    break;
                case 'add':
                case 'del':
                case 'set':
                case 'help':
                    $('#titulo').addClass('orange'); // means parameter input needed
                    break;
                case 'set title':
                case 'add link':
                case 'add link title':
                case 'del link':
                    $('#titulo').addClass('red'); // means string input needed
                    break;
                default:
                    alert('need to set a color for this command: ' + data)
                    $('#titulo').addClass('teal'); // needs to be replaced with one of the above
            }
        }
        var zero_param = function () {
            update_param1('—');
            update_param2('—');
        }
        var update_cmd = function (data) {
            $('#cmd').val(data);
            update_title(data);
            switch (data) {
                case 'x':
                    zero_param();
                    update_sub('command? -> go add del set help');
                    break;
                case 'del':
                case 'add':
                    zero_param();
                    update_sub('what? -> link, note');
                    break;
                case 'add link':
                case 'del link':
                    zero_param();
                    update_sub('which? -> title [enter]');
                    break;
                case 'go':
                    zero_param();
                    update_sub('where? -> title [enter]');
                    break;
                case 'set':
                    zero_param();
                    update_sub('what? -> title');
                    break;
                case 'set title':
                    zero_param();
                    update_sub('which? -> new title [enter]');
                    break;
                case 'del link title':
                    update_sub('confirm= -> [enter]')
            }
        }
        var update_sub = function (data) {
            $('#subtitulo').text(data);
        }

        $(document).on('keyup', '#cmdbar', function (e) {
            bar = $('#cmdbar').val();
            cmd = $('#cmd').val();
            pm1 = $('#param1').text();
            pm2 = $('#param2').text();
            long = bar.length;
            var code = e.key;
            if (code == 'Enter') {
                if (bar == '') {
                    if (cmd == 'x') {
                        update_cmd('go');
                    } else {
                        update_cmd('x');
                    }
                }
                switch (cmd) {
                    case 'add link':
                    case 'del link':
                        update_cmd(cmd + ' ' + 'title');
                        update_param1(bar);
                        break;
                    case 'add link title':
                        update_param2(bar);
                        update_cmd(cmd + ' ' + 'url')
                        break;
                    case 'set title':
                        update_param1(bar);
                        update_cmd(cmd + ' ' + 'confirm')
                        $('#cmdbar').val('');
                        break;
                    case 'go':
                        pm1 = bar;
                        if (bar == '') {
                            break;
                        }
                    case 'set title confirm':
                    case 'add link title url':
                    case 'del link title':
                        alert('command sent to engine');
                        $.post('engine.php', {
                            'nxst_cmd': cmd,
                            'nxst_pm1': pm1,
                            'nxst_pm2': pm2
                        }, function (data) {
                            if (data != 0) {
                                var results = JSON.parse(data);
                                results_type = results[0];
                                results_pm1 = results[1];
                                results_pm2 = results[2];
                                results_pm3 = results[3];
                                switch (results_type) {
                                    case 'alert':
                                        alert(results_pm1);
                                        $('#cmdbar').val('');
                                        break;
                                    case 'open_link':
                                        update_cmd('go');
                                        window.open(results_pm1, '_blank');
                                        break;
                                    case 'change_page_title':
                                        update_cmd('x');
                                        $('#page_title').text(results_pm1);
                                        break;
                                }
                            } else {
                                update_cmd('go');
                            }
                        })
                        break;
                }
            } else if (code == 'Backspace') {
                $('#cmdbar').val('');
            } else if (code == 'Escape') {
                update_cmd('x');
            }
            if (bar == ' ') {
                update_cmd('x');
            }

            switch (cmd) {
                case 'x':
                    switch (bar) {
                        case 'del':
                        case 'add':
                        case 'go':
                        case 'help':
                        case 'set':
                            update_cmd(bar);
                        default:
                            if (long == 5) {
                                update_cmd(cmd);
                            }
                    }
                    break;
                case 'del':
                case 'add':
                    switch (bar) {
                        case 'link':
                            update_cmd(cmd + ' ' + bar);
                    }
                    break;
                case 'set':
                    switch (bar) {
                        case 'title':
                            update_cmd(cmd + ' ' + bar);
                    }
            }
        });
        update_cmd('go');
    </script>
<?php
	include 'templates/html_bottom.php';