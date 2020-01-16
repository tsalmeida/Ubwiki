<?php
	
	include 'engine.php';
	
	if ($user_tipo != 'admin') {
		header('Location:pagina.php?pagina_id=4');
	}
	
	$pagina_tipo = 'loja';
	
	if (isset($_POST['novo_produto_titulo'])) {
		$novo_produto_titulo = $_POST['novo_produto_titulo'];
		$conn->query("INSERT INTO Paginas (tipo, subtipo, compartilhamento, user_id) VALUES ('pagina', 'produto', 'privado', $user_id)");
		$novo_produto_pagina_id = $conn->insert_id;
		$conn->query("INSERT INTO Paginas_elementos (pagina_id, pagina_tipo, tipo, extra, user_id) VALUES ($novo_produto_pagina_id, 'pagina', 'titulo', '$novo_produto_titulo', $user_id)");
	}
	
	include 'templates/html_head.php';

?>

    <body class="grey lighten-5">
		<?php
			include 'templates/navbar.php';
		?>

    <div class="container-fluid">
        <div class="row justify-content-between px-2">
            <div class="py-2 text-left col">
							<?php
								if (($user_tipo == 'admin') || ($user_tipo == 'professor')) {
									echo "<span id='add_produto' class='mx-1' title='Adicionar produto' data-toggle='modal' data-target='#modal_add_produto'><a href='javascript:void(0);' class='text-info'><i class='fad fa-plus-circle fa-2x fa-2x'></i></a></span>";
								}
							?>
            </div>
        </div>

    </div>

    <div class="container-fluid">
			<?php
				$template_titulo = 'Loja virtual';
				$template_subtitulo = false;
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
									$template_titulo = 'Seus produtos não-publicados';
									$template_conteudo_class = 'justify-content-start';
									$template_conteudo_no_col = true;
									$template_conteudo = false;
									$produto_secao_texto = 'Os produtos abaixo foram adicionados por você, mas ainda não foram publicados.';
									$template_conteudo .= include 'templates/produto_secao.php';
									
									while ($produto_nao_publicados_do_usuario = $produtos_nao_publicados_do_usuario->fetch_assoc()) {
										$produto_nao_publicado_do_usuario_pagina_id = $produto_nao_publicados_do_usuario['id'];
										$produto_nao_publicado_do_usuario_pagina_info = return_produto_info($produto_nao_publicado_do_usuario_pagina_id);
										$produto_nao_publicado_do_usuario_titulo = $produto_nao_publicado_do_usuario_pagina_info[0];
										$produto_nao_publicado_do_usuario_imagem = return_produto_imagem($produto_nao_publicado_do_usuario_pagina_id);
										$produto_nao_publicado_do_usuario_imagem = return_imagem_arquivo($produto_nao_publicado_do_usuario_imagem);
										$produto_nao_publicado_do_usuario_imagem = "../imagens/verbetes/thumbnails/$produto_nao_publicado_do_usuario_imagem";
										$produto_nao_publicado_do_usuario_apresentacao = $produto_nao_publicado_do_usuario_pagina_info[1];
										
										$produto_pagina_id = $produto_nao_publicado_do_usuario_pagina_id;
										$produto_titulo = $produto_nao_publicado_do_usuario_titulo;
										$produto_apresentacao = $produto_nao_publicado_do_usuario_apresentacao;
										$produto_imagem = $produto_nao_publicado_do_usuario_imagem;
										$template_conteudo .= include 'templates/produto.php';
									}
									
									include 'templates/page_element.php';
									
								}
								$template_id = 'espaco_zeitgeist';
								$template_titulo = 'Espaço Zeitgeist';
								$template_conteudo_class = 'justify-content-start';
								$template_conteudo_no_col = true;
								$template_conteudo = false;
								
								$template_conteudo .= "
									<div class='container-fluid'>
										<div class='row justify-content-start'>
											<div class='col'>
											<p class='p-limit'>Os cursos do Espaço Zeitgeist são ministrados pelo professor Rômulo e voltados a estudantes do CACD. Incluem conteúdo de História do Brasil, História Mundial e História da Política Exterior Brasileira.</p>
											</div>
										</div>
									</div>
								";
								
								$produto_titulo = 'Zeitgeist Base';
								$produto_pagina_id = 123;
								$produto_apresentacao = 'O zg base constitui a espinha dorsal no que concerne à preparação em história para o Concurso de Admissão à Carreira Diplomática (CACD). Aqui você tem acesso aos nossos três cursos regulares, os quais, com amplíssima carga horária, proporcionarão uma primeira e já profunda varredura dos temas constantes do edital. Através dos nossos cursos de História do Brasil, História Mundial e História da Política Externa Brasileira, o candidato poderá sistematizar todo o conteúdo recorrentemente cobrado no exame. Trata-se de um primeiro e muito significativo passo rumo à sua aprovação.';
								$produto_imagem = 'https://ava.ensinabox.com/uploadsTrilha/804ec17b4c70af93f7fc735402e5918b.png';
								$template_conteudo .= include 'templates/produto.php';
								
								$produto_titulo = 'Zeitgeist+';
								$produto_pagina_id = 123;
								$produto_apresentacao = 'O ZG+ foi criado com o objetivo de funcionar como uma espécie de "Netflix" para os aspirantes à carreira diplomática. Trata-se de uma assinatura mensal que permite o acesso do candidato a vários cursos de aprofundamento e, também, a um curso de exercícios de História Mundial e de História do Brasil voltado para a primeira fase do CACD, o Teste de Pré-Seleção (TPS). O ZG+ está em constante transformação. Todos os meses oferecemos cursos novos e novas folhinhas de exercícios. Trata-se, portanto, de uma plataforma que constantemente agrega novo repertório aos seus assinantes, proporcionando importantes diferenciais em um concurso cada vez mais competitivo. Você pode entrar e sair do ZG+ quando quiser. Caso não queira mais acessar a plataforma, basta interromper a assinatura.';
								$produto_imagem = 'https://ava.ensinabox.com/uploadsTrilha/26734d23e7a26e2152f2d7d5e175c4fb.png';
								$template_conteudo .= include 'templates/produto.php';
								
								$produto_titulo = 'Segunda Etapa';
								$produto_pagina_id = 123;
								$produto_apresentacao = 'O curso preparatório para a segunda fase do Concurso de Admissão à Carreira Diplomática (CACD) é oferecido àqueles alunos que já estão suficientemente familiarizados com o conteúdo de História do Brasil constante no edital. Neste sentido, é altamente recomendável, para fazer parte do grupo, que o candidato tenha concluído qualquer curso teórico de HB para o CACD oferecido no mercado. No sentido de transcender uma tradição “etapista” dos estudos para o Rio Branco, a nossa intenção é propor uma preparação contínua para a segunda fase do concurso, tornando o formato da prova familiar ao aluno e, através dos temas propostos e das aulas expositivas, adensando o seu repertório, permitindo, assim, maior desenvoltura quando da realização da prova.';
								$produto_imagem = 'https://ava.ensinabox.com/uploadsTrilha/26c4ddcab81e6fad49b89bec0bb22450.png';
								$template_conteudo .= include 'templates/produto.php';
								
								include 'templates/page_element.php';
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
			$template_modal_titulo = 'Adicionar produto';
			$template_modal_body_conteudo = false;
			$template_modal_body_conteudo .= "
				<div class='md-form'>
					<input type='text' class='form-control' name='novo_produto_titulo' id='novo_produto_titulo' required>
					<label for='novo_produto_titulo'>Título do novo produto</label>
				</div>
			";
			include 'templates/modal.php';
		?>

    </body>
<?php
	include 'templates/footer.html';
	$mdb_select = true;
	include 'templates/html_bottom.php';
?>