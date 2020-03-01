<?php
	
	if (isset($_GET['lg'])) {
		$user_language = $_GET['lg'];
		$user_language_titulo = convert_language($user_language);
	} else {
		$user_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$user_language_titulo = convert_language($user_language);
	}
	if ($user_language_titulo == false) {
		$user_language = 'pt';
		$user_language_titulo = convert_language($user_language);
	}
	
	if ($user_tipo != 'admin') {
		$user_language = 'pt';
		$user_language_titulo = convert_language($user_language);
	}
	
	switch ($user_language) {
		case 'en':
			$common_strings = array(
				"cancel" => "Cancel",
				"save" => "Save",
				"environments" => "Environments",
				"office" => "Your office",
				"courses" => "Courses",
				"freepages" => "Free pages",
				"library" => "Library",
				"forum" => "Forum",
				"market" => "Market",
				"study groups" => "Study groups",
				"your collection" => "Your collection",
				"your office lounge" => "Your office lounge",
				"your cart" => "Your shopping cart",
				"administrators page" => "Administrators' page",
				"logout" => "Log-out",
				"notifications" => "Notifications",
				"bookmarks" => "Bookmarks",
			);
			return $common_strings;
		case 'pt':
			$common_strings = array(
				"cancel" => "Cancelar",
				"save" => "Salvar",
				"environments" => "Ambientes",
				"office" => "Seu escritório",
				"courses" => "Cursos",
				"freepages" => "Páginas livres",
				"library" => "Biblioteca",
				"forum" => "Fórum",
				"market" => "Mercado",
				"study groups" => "Grupos de estudos",
				"your collection" => "Seu acervo",
				"your office lounge" => "Sua sala de visitas",
				"your cart" => "Seu carrinho",
				"administrators page" => "Página dos administradores",
				"logout" => "Desconectar-se",
				"notifications" => "Notificações",
				"bookmarks" => "Lista de leitura",
			);
			return $common_strings;
		case 'es':
			$common_strings = array(
				"cancel" => "Cancelar",
				"save" => "Salvar",
				"environments" => "Ambientes",
				"office" => "Tu oficina",
				"courses" => "Cursos",
				"freepages" => "Páginas libres",
				"library" => "Biblioteca",
				"forum" => "Foro",
				"market" => "Mercado",
				"study groups" => "Grupos de estudio",
				"your collection" => "Tu collection",
				"your office lounge" => "Tu salón de oficina",
				"your cart" => "Tu carrito de la compra",
				"administrators page" => "Página de administradores",
				"logout" => "Cerrar sesión",
				"notifications" => "Notificaciones",
				"bookmarks" => "Lista de lectura",
			);
			return $common_strings;
	}
	
	function translate_pagina($pagina_tipo, $user_language)
	{
		if ($pagina_tipo == 'login') {
			switch ($user_language) {
				case 'en':
					return array(
						"slogan" => "What will you learn today?",
						"access" => "Access",
						"access_message" => "To access or create an account, type your e-mail below.",
						"continue" => "Continue",
						"about_ubwiki" => "About Ubwiki",
						"your_email" => "Your e-mail address",
						"your_password" => "Your password",
						"correct_password_but" => "Your password is correct. However, before you can use this password, you need to follow the link that was sent to your e-mail.",
						"languages" => "Languages",
						"send_by_email" => "Send new password by e-mail",
						"your_new_password" => "Your new password",
						"forgot_your_password" => "Did you forget your password? Type-in a new password below. It will be activated once you follow the link that will be sent to your e-mail address. Don't forget to check your spam folder.",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
					);
					break;
				case 'pt':
					return array(
						"slogan" => "O que você vai aprender hoje?",
						"access" => "Accessar",
						"access_message" => "Para acessar ou criar uma conta, insira seu email abaixo.",
						"continue" => "Continuar",
						"about_ubwiki" => "Sobre a Ubwiki",
						"your_email" => "Seu email",
						"your_password" => "Sua senha",
						"correct_password_but" => "Senha correta. No entanto, antes que essa conta possa ser acessada com a nova senha, será necessário seguir o link enviado ao seu email.",
						"languages" => "Idiomas",
						"send_by_email" => "Enviar nova senha por email",
						"your_new_password" => "Sua nova senha",
						"forgot_your_password" => "Esqueceu sua senha? Crie abaixo sua nova senha, ela será ativada quando você seguir um link que será enviado ao seu email. Não se esqueça de procurá-la na pasta de mensagens bloqueadas.",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
					);
					break;
				case 'es':
					return array(
						"slogan" => "¿Qué vas a aprender hoy?",
						"access" => "Acceder",
						"access_message" => "Para acceder o crear una cuenta, escriba su correo electrónico a continuación.",
						"continue" => "Seguir",
						"about_ubwiki" => "Sobre Ubwiki",
						"your_email" => "Tu correo electrónico",
						"your_password" => "Tu contraseña",
						"correct_password_but" => "Tu contraseña es correcta. Sin embargo, antes de poder usar esta contraseña, debe seguir el enlace que se envió a su correo electrónico.",
						"languages" => "Idiomas",
						"send_by_email" => "Enviar nueva contraseña por correo electrónico",
						"your_new_password" => "Tu nueva contraseña",
						"forgot_your_password" => "¿Olvidaste tu contraseña? Escriba una nueva contraseña a continuación. Se activará una vez que siga el enlace que se enviará a su dirección de correo electrónico. No olvides revisar tu carpeta de spam.",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
						"" => "",
					);
					break;
			}
		} elseif ($pagina_tipo == 'ubwiki') {
			switch ($user_language) {
				case 'en':
					return array(
						"about_office" => "About your office",
						"about_courses" => "About the courses",
						"about_free_pages" => "About free pages",
						"about_library" => "About the library",
						"about_forum" => "About the forum",
						"about_market" => "About the market",
						"visit_office" => "Visit your office",
						"visit_courses" => "Visit the courses",
						"visit_free_pages" => "Visit the free pages hub",
						"visit_library" => "Visit the library",
						"visit_forum" => "Visit the general forum",
						"visit_market" => "Visit the market",
						"" => "",
						"" => "",
					);
				case 'pt':
					return array(
						"about_office" => "Sobre seu escritório",
						"about_courses" => "Sobre os cursos",
						"about_free_pages" => "Sobre as páginas livres",
						"about_library" => "Sobre a biblioteca",
						"about_forum" => "Sobre o fórum",
						"about_market" => "Sobre o mercado",
						"visit_office" => "Visite seu escritório",
						"visit_courses" => "Veja os cursos",
						"visit_free_pages" => "Veja as páginas livres",
						"visit_library" => "Visite a biblioteca",
						"visit_forum" => "Visite o fórum geral",
						"visit_market" => "Visite o mercado",
						"" => "",
						"" => "",
					);
				case 'es':
					return array(
						"about_office" => "Sobre tu oficina",
						"about_courses" => "Sobre los cursos",
						"about_free_pages" => "Sobre las páginas libres",
						"about_library" => "Sobre la biblioteca",
						"about_forum" => "Sobre el foro",
						"about_market" => "Sobre el mercado",
						"visit_office" => "Visite tu oficina",
						"visit_courses" => "Veja los cursos",
						"visit_free_pages" => "Veja las páginas libres",
						"visit_library" => "Visite la biblioteca",
						"visit_forum" => "Visite el foro general",
						"visit_market" => "Visite el mercado",
						"" => "",
						"" => "",
					);
			}
		} elseif ($pagina_tipo == 'escritorio') {
			switch ($user_language) {
				case 'en':
					return array(
						"your_office"=>"Your office",
						"user_office"=>"Office",
						"recent_visits"=>"Recently studied",
						"your_pages"=>"Your pages",
						"new_private_page"=>"New private page",
						"texts and study notes"=>"Your text documents and study notes",
						"press add collection"=>"Click to add an item to your collection",
						"create new study group"=>"Create a new study group",
						"private images"=>"Private images",
						"public images"=>"Public images",
						"joining study group explanation"=>"Upon joining a study group, you will be able to share private items with other group members. To join a study group, it is first necessary to chose a nickname. Only the founder of a study group is able to invite new members.",
						"lounge room explanation 1"=>"Other users can visit your office's lounge upon clicking on your username. Your nickname is the only public information that identifies your activities on Ubwiki.",
						"lounge room explanation 2"=>"Only items explicitly made public by you will be included in your office's lounge. At this moment, it is only possible to write a presentation text.",
						"edit lounge"=>"Edit your office's lounge",
						"most effective"=>"Ubwiki is most effective as a learning platform for communities gathered around common interests. Select a course below to join one such community.",
						"user settings"=>"User settings",
						"your study groups" => "Your study groups",
						"your courses" => "Your courses",
						"available courses" => "Available courses",
					);
				case 'pt':
					return array(
						"your_office"=>"Seu escritório",
						"user_office"=>"Escritório de",
						"recent_visits"=>"Estudos recentes",
						"your_pages"=>"Suas páginas",
						"new_private_page"=>"Nova página privada",
						"texts and study notes"=>"Seus documentos de texto e notas de estudo",
						"press add collection"=>"Pressione para adicionar um item ao seu acervo",
						"create new study group"=>"Criar novo grupo de estudo",
						"private images"=>"Imagens privadas",
						"public images"=>"Imagens públicas",
						"joining study group explanation"=>"Ao aderir a um grupo de estudos, você poderá compartilhar exclusivamente com outros membros. Para participar desta ferramenta, é necessário determinar um apelido. Somente o criador de um grupo de estudos pode acrescentar novos membros.",
						"lounge room explanation 1"=>"A sala de visitas de seu escritório é visível a outros usuários, que a visitarão ao clicar em seu apelido. Seu apelido é a única informação que o identifica em suas atividades públicas na Ubwiki.",
						"lounge room explanation 2"=>"Apenas itens explicitamente tornados públicos por você serão incluídos em sua sala de visitas. No momento, somente é possível escrever um texto de apresentação.",
						"edit lounge"=>"Editar sua sala de visitas",
						"most effective"=>"A Ubwiki é mais efetiva como um ambiente de estudos para comunidades unidas em torno de interesses comuns. Selecione um curso abaixo para participar de uma comunidade.",
						"user settings"=>"Suas configurações",
						"your study groups" => "Seus grupos de estudos",
						"your courses" => "Seus cursos",
						"available courses" => "Cursos disponíveis",
					);
				case 'es':
					return array(
						"your_office"=>"Tu oficina",
						"user_office"=>"Oficina de",
						"recent_visits"=>"Estudiado recientemente",
						"your_pages"=>"Tus paginas",
						"new_private_page"=>"Nueva página privada",
						"texts and study notes"=>"Tus documentos de texto y notas de estudio",
						"press add collection"=>"Presione para agregar un elemento a su colección",
						"create new study group"=>"Crea un nuevo grupo de estudio",
						"private images"=>"Imágenes privadas",
						"public images"=>"Imágenes publicas",
						"joining study group explanation"=>"Al unirse a un grupo de estudio, podrá compartir elementos privados con otros miembros del grupo. Para unirse a un grupo de estudio, primero es necesario elegir un apodo. Solo el fundador de un grupo de estudio puede invitar a nuevos miembros.",
						"lounge room explanation 1"=>"Otros usuarios pueden visitar el salón de su oficina haciendo clic en su nombre de usuario. Su apodo es la única información pública que identifica sus actividades en Ubwiki.",
						"lounge room explanation 2"=>"Solo los artículos que usted haga públicos explícitamente se incluirán en el salón de su oficina. En este momento, solo es posible escribir un texto de presentación.",
						"edit lounge"=>"Edita el salón de tu oficina",
						"most effective"=>"Ubwiki es más eficaz como plataforma de aprendizaje para comunidades reunidas en torno a intereses comunes. Seleccione un curso a continuación para unirse a una de esas comunidades.",
						"user settings"=>"Ajustes de usuario",
						"your study groups" => "Sus grupos de estudio",
						"your courses" => "Tus cursos",
						"available courses" => "Cursos disponibles",
					);
			}
		} elseif ($pagina_tipo == 'pagina') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} elseif ($pagina_tipo == 'curso') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} elseif ($pagina_tipo == 'materia') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} elseif ($pagina_tipo == 'forum') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} elseif ($pagina_tipo == 'biblioteca') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} elseif ($pagina_tipo == 'areas_interesse') {
			switch ($user_language) {
				case 'en':
					return array(
					
					);
				case 'pt':
					return array(
					
					);
				case 'es':
					return array(
					
					);
			}
		} else {
			return false;
		}
	}
	
	function return_texto_pagina_login($user_language)
	{
		if ($user_language == 'pt') {
			return 548;
		} elseif ($user_language == 'en') {
			return 2635;
		} elseif ($user_language == 'es') {
			return 2636;
		}
	}
	
	function return_texto_ambientes($ambiente, $user_language)
	{
		switch ($ambiente) {
			case 'ambientes':
				switch ($user_language) {
					case 'en':
						return 2631;
					case 'pt':
						return 1218;
					case 'es':
						return 2633;
				}
			case 'escritorio':
				switch ($user_language) {
					case 'en':
						return 2607;
					case 'pt':
						return 1220;
					case 'es':
						return 2621;
				}
			case 'cursos':
				switch ($user_language) {
					case 'en':
						return 2609;
					case 'pt':
						return 1222;
					case 'es':
						return 2623;
				}
			case 'paginaslivres':
				switch ($user_language) {
					case 'en':
						return 2611;
					case 'pt':
						return 1224;
					case 'es':
						return 2625;
				}
			case 'biblioteca':
				switch ($user_language) {
					case 'en':
						return 2613;
					case 'pt':
						return 1226;
					case 'es':
						return 2629;
				}
			case 'forum':
				switch ($user_language) {
					case 'en':
						return 2605;
					case 'pt':
						return 1228;
					case 'es':
						return 2627;
				}
			case 'mercado':
				switch ($user_language) {
					case 'en':
						return 2615;
					case 'pt':
						return 1230;
					case 'es':
						return 2619;
				}
			
		}
	}
