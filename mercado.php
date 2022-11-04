<?php
	
	include 'engine.php';
	
	if ($user_tipo != 'admin') {
		header('Location:pagina.php?pagina_id=3');
	}
	
	$pagina_tipo = 'mercado';
	
	if (isset($_GET['pagina_id'])) {
		$mercado_pagina_id = $_GET['pagina_id'];
		$mercado_pagina_titulo = return_pagina_titulo($mercado_pagina_id);
	} else {
		$mercado_pagina_id = 1;
		$mercado_pagina_titulo = 'Mercado geral';
	}
	
	if (isset($_POST['novo_produto_titulo'])) {
		$novo_produto_titulo = $_POST['novo_produto_titulo'];
		$novo_produto_tipo = $_POST['novo_produto_tipo'];
		$conn->query("INSERT INTO Paginas (tipo, subtipo, compartilhamento, user_id) VALUES ('pagina', 'produto', 'privado', $user_id)");
		$novo_produto_pagina_id = $conn->insert_id;
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_produto_pagina_id, 'pagina', 'titulo', '$novo_produto_titulo', $user_id)");
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($mercado_pagina_id, '$mercado_pagina_tipo', 'produto', '$novo_produto_tipo', $user_id)");
	}
	
	include 'templates/html_head.php';

?>

    <body class="bg-light">
		<?php
			include 'templates/navbar.php';
		?>

    <div class="container-fluid">
        <div class="row justify-content-between px-2">
            <div class="py-2 text-left col">
							<?php
								if (($user_tipo == 'admin') || ($user_tipo == 'professor')) {
									echo "<span id='add_produto' class='me-1' title='{$pagina_translated['Adicionar produto']}' data-bs-toggle='modal' data-bs-target='#modal_add_produto'><a href='javascript:void(0);' class='link-teal'><i class='fad fa-plus-circle fa-2x fa-2x'></i></a></span>";
								}
							?>
            </div>
        </div>

    </div>

    <div class="container-fluid">
			<?php
				$template_titulo = $pagina_translated['market'];
				$template_subtitulo = $mercado_pagina_titulo;
				$template_titulo_context = true;
				include 'templates/titulo.php';
			?>
        <div class="row d-flex justify-content-center">
            <div id="coluna_unica" class="col mx-2">
							<?php
								
								if ($user_tipo != 'estudante') {
									$produtos_nao_publicados_do_usuario = $conn->query("SELECT id FROM Paginas WHERE user_id = $user_id AND compartilhamento = 'privado' AND subtipo = 'produto'");
								}
								
								if ($produtos_nao_publicados_do_usuario->num_rows > 0) {
									
									$template_id = 'seus_produtos';
									$template_titulo = $pagina_translated['Seus produtos não-publicados'];
									$template_conteudo_class = 'justify-content-start';
									$template_conteudo_no_col = true;
									$template_conteudo = false;
									$produto_secao_texto = $pagina_translated['Os produtos abaixo foram adicionados por você, mas ainda não foram publicados.'];
									$template_conteudo .= include 'templates/produto_secao.php';
									
									while ($produto_nao_publicados_do_usuario = $produtos_nao_publicados_do_usuario->fetch_assoc()) {
										$produto_nao_publicado_do_usuario_pagina_id = $produto_nao_publicados_do_usuario['id'];
										$produto_nao_publicado_do_usuario_pagina_info = return_produto_info($produto_nao_publicado_do_usuario_pagina_id);
										$produto_nao_publicado_do_usuario_titulo = $produto_nao_publicado_do_usuario_pagina_info[0];
										$produto_nao_publicado_do_usuario_preco = $produto_nao_publicado_do_usuario_pagina_info[2];
										$produto_nao_publicado_do_usuario_imagem = return_produto_imagem($produto_nao_publicado_do_usuario_pagina_id);
										$produto_nao_publicado_do_usuario_imagem = return_imagem_arquivo($produto_nao_publicado_do_usuario_imagem);
										$produto_nao_publicado_do_usuario_imagem = "../imagens/verbetes/thumbnails/$produto_nao_publicado_do_usuario_imagem";
										$produto_nao_publicado_do_usuario_apresentacao = $produto_nao_publicado_do_usuario_pagina_info[1];
										
										$produto_pagina_id = $produto_nao_publicado_do_usuario_pagina_id;
										$produto_titulo = $produto_nao_publicado_do_usuario_titulo;
										$produto_apresentacao = $produto_nao_publicado_do_usuario_apresentacao;
										$produto_imagem = $produto_nao_publicado_do_usuario_imagem;
										$produto_preco = $produto_nao_publicado_do_usuario_preco;
										$template_conteudo .= include 'templates/produto.php';
									}
									
									include 'templates/page_element.php';
									
								}
							?>
            </div>
        </div>
    </div>
		
		<?php
			
			/*<p>Formulário deve incluir:</p>
			<ol>
				<li>Título (será o título da página do produto)</li>
				<li>Upload de imagem, pode ser alterada na página do produto.</li>
				<li>Curso, pode ser acrescentado à página do concurso depois.</li>
				<li>Matérias determinadas pelas etiquetas na página do produto.</li>
				<li>Tipo (multiple select), pode ser alterado na página do produto:</li>
				<ol>
					<li>Coaching</li>
					<li>Material de leitura</li>
					<li>Vídeos</li>
					<li>Acesso a página da Ubwiki</li>
				</ol>
				<li>Preço, pode ser alterado na página do produto.</li>
				<li>Apresentação será o verbete da página do produto.</li>
			</ol>
			<p>Ao ser criado, o produto deve ficar invisível para o público, mas visível para o criador.</p>*/
			
			$template_modal_div_id = 'modal_add_produto';
			$template_modal_titulo = $pagina_translated['Adicionar produto'];
			$modal_scrollable = true;
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
				<div class='mb-3'>
					<label for='novo_produto_titulo' class='form-label'>{$pagina_translated['Título do novo produto']}</label>
					<input type='text' class='form-control' name='novo_produto_titulo' id='novo_produto_titulo' required>
				</div>
				<p>{$pagina_translated['Pressione as opções abaixo de acordo com as características do produto:']}</p>
				<div class='row d-flex justify-content-center border p-1 rounded'>
			";
			
			$produto_col_limit = 'col-lg-4 col-md-6 col-sm-12';
			
			$artefato_tipo = 'produto_gratuito';
			$artefato_titulo = $pagina_translated['Gratuito'];
			$artefato_col_limit = $produto_col_limit;
			$fa_icone = 'fa-usd-circle';
			$fa_color = 'link-success';
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			
			$artefato_tipo = 'produto_pago';
			$artefato_titulo = $pagina_translated['Pago'];
			$artefato_col_limit = $produto_col_limit;
			$fa_icone = 'fa-usd-circle';
			$fa_color = 'link-teal';
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			
			$template_modal_body_conteudo .= "
				</div>
				<div class='row d-flex justify-content-center border p-1 my-1 rounded'>
			";
			
			$artefato_tipo = 'curso_distancia';
			$artefato_titulo = $pagina_translated['Curso à distância'];
			$artefato_col_limit = $produto_col_limit;
			$fa_icone = 'fa-step-forward';
			$fa_color = 'link-danger';
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			
			$artefato_tipo = 'curso_presencial';
			$artefato_titulo = $pagina_translated['Curso presencial'];
			$artefato_col_limit = $produto_col_limit;
			$fa_icone = 'fa-chalkboard-teacher';
			$fa_color = 'link-warning';
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			
			$artefato_tipo = 'mentoria';
			$artefato_titulo = $pagina_translated['Mentoria'];
			$artefato_col_limit = $produto_col_limit;
			$fa_icone = 'fa-whistle';
			$fa_color = 'link-purple';
			$template_modal_body_conteudo .= include 'templates/artefato_item.php';
			
			$template_modal_body_conteudo .= "</div>";
			
			if (($user_tipo == 'admin') || ($user_tipo == 'professor')) {
				$template_modal_body_conteudo .= "
                    <p class='mt-2'>{$pagina_translated['Porque você está registrado como professor, você poderá oferecer serviços dentro da Ubwiki:']}</p>
                    <div class='row d-flex justify-content-center border rounded my-1 p-1'>";
				
				$artefato_tipo = 'mentoria_ubwiki';
				$artefato_titulo = $pagina_translated['Mentoria pela Ubwiki'];
				$artefato_col_limit = $produto_col_limit;
				$fa_icone = 'fa-handshake';
				$fa_color = 'link-success';
				$template_modal_body_conteudo .= include 'templates/artefato_item.php';
				
				$artefato_tipo = 'curso_ubwiki';
				$artefato_titulo = $pagina_translated['Acesso a curso Ubwiki'];
				$artefato_col_limit = $produto_col_limit;
				$fa_icone = 'fa-sitemap';
				$fa_color = 'text-primary';
				$template_modal_body_conteudo .= include 'templates/artefato_item.php';
				
				$artefato_tipo = 'grupo_ubwiki';
				$artefato_titulo = $pagina_translated['Acesso a grupo de estudos Ubwiki'];
				$artefato_col_limit = $produto_col_limit;
				$fa_icone = 'fa-users';
				$fa_color = 'link-warning';
				$template_modal_body_conteudo .= include 'templates/artefato_item.php';
				
				$template_modal_body_conteudo .= "</div>";
			}
			
			include 'templates/modal.php';
		?>

    </body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>