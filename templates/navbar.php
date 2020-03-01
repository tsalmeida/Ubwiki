<?php
	if (!isset($template_navbar_mode)) {
		$template_navbar_mode = 'dark';
	}
	if ($template_navbar_mode == 'dark') {
		$template_navbar_color = 'grey darken-4';
		$template_navbar_text = 'text-white';
	} elseif ($template_navbar_mode == 'light') {
		$template_navbar_color = 'bg-white';
		$template_navbar_text = 'text-dark';
	} elseif ($template_navbar_mode == 'transparent') {
		$template_navbar_color = 'transparent';
		$template_navbar_text = 'text-white';
	}
	if (isset($user_id)) {
		$user_avatar_info = return_avatar($user_id);
		$navbar_avatar = $user_avatar_info[0];
	}
	if (!isset($navbar_avatar)) {
		$navbar_avatar = 'fa-user-tie';
	}
	if (isset($curso_id)) {
		echo "<nav class='navbar navbar-expand-lg $template_navbar_color' id='inicio'>";
		if ($pagina_tipo == 'curso') {
			echo "<a class='navbar-brand $template_navbar_text' href='ubwiki.php' title='Retornar ao nexus.'>Ubwiki</a>";
		} else {
			if (isset($curso_id)) {
				echo "<a class='navbar-brand $template_navbar_text' href='pagina.php?curso_id=$curso_id' title='Retornar ao curso ativo.'>Ubwiki: $curso_sigla</a>";
			} else {
				echo "<a class='navbar-brand $template_navbar_text' href='ubwiki.php' title='Retornar ao nexus.'>Ubwiki</a>";
			}
		}
		echo "<ul class='nav navbar-nav ml-auto nav-flex-icons'>";
		echo "<li class='nav-item dropdown'>";
		echo "<a class='navlink dropdown-toggle waves-effect waves-light rounded $template_navbar_text' id='user_dropdown' data-toggle='dropdown' href='javascript:void(0);'>";
		echo "
		        <i class='fas fa-2x $navbar_avatar fa-lg fa-fw'></i>
		        </a>
		        <div class='dropdown-menu dropdown-menu-right z-depth-0'>
		          <a class='dropdown-item navlink z-depth-0' href='escritorio.php'><i class='fad fa-lamp-desk fa-fw'></i> {$common_strings['office']}</a>
		          <a class='dropdown-item navlink z-depth-0' href='ubwiki.php'><i class='fad fa-portal-enter fa-fw'></i> {$common_strings['environments']}</a>";
		if ($carregar_carrinho == true) {
			echo "<a class='dropdown-item navlink z-depth-0' href='carrinho.php'><i class='fad fa-shopping-cart fa-fw'></i> {$common_strings['your cart']}</a>";
		}
		if ($user_tipo == 'admin') {
			echo "<a class='dropdown-item navlink z-depth-0' href='admin.php'><i class='fad fa-cogs fa-fw'></i> {$common_strings['administrators page']}</a>";
		}
		echo "
		          <a class='dropdown-item navlink z-depth-0' href='pagina.php?curso_id=$curso_id'><i class='fad fa-book-reader fa-fw'></i> $curso_sigla</a>
		          <a class='dropdown-item navlink z-depth-0' href='logout.php'><i class='fad fa-portal-exit fa-fw'></i> {$common_strings['logout']}</a>
		      </li>
		    </ul>
		  </nav>
  	";
	}
	unset($template_navbar_mode);
	unset($template_navbar_color);
	unset($template_navbar_text);