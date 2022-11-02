<?php
	if (!isset($template_navbar_mode)) {
		$template_navbar_mode = 'dark';
	}
	if (!isset($pagina_padrao)) {
		$pagina_padrao = false;
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
	echo "<nav class='navbar navbar-expand-lg $template_navbar_color' id='navbar'>";

	switch ($pagina_tipo) {
		case "nexus":
			break;
		case "curso":
			break;
		default:
			if (isset($raiz_ativa)) {
				echo "<a class='navbar-brand $template_navbar_text' href='pagina.php?pagina_id=$raiz_ativa' title='$raiz_titulo'>$raiz_sigla</a>";
			} else {
				echo "<a class='navbar-brand $template_navbar_text' href='ubwiki.php' title='{$pagina_translated['Retornar ao nexus.']}'>Ubwiki</a>";
			}
	}
	if ($pagina_padrao == true) {
		echo "
				<a href='javascript:void(0);' class='text-black-50' id='hide_bars'><i class='fad fa-eye-slash fa-fw fa-sm'></i></a>
			";
	}
	if ($user_id != false) {
		echo "<ul class='nav navbar-nav ml-auto nav-flex-icons'>";
		echo "<li class='nav-item dropdown'>";
		echo "<a class='navlink dropdown-toggle waves-effect waves-light rounded $template_navbar_text' id='user_dropdown' data-toggle='dropdown' href='javascript:void(0);'>
		        <i class='fas fa-2x {$_SESSION['user_avatar_icone']} fa-lg fa-fw'></i>
		        </a>
		        <div class='dropdown-menu dropdown-menu-right z-depth-0'>";
		if ($pagina_tipo != 'escritorio') {
			echo "<a class='dropdown-item navlink z-depth-0' href='escritorio.php'><i class='fad fa-lamp-desk fa-fw'></i> {$pagina_translated['office']}</a>";
		}
		if ($pagina_tipo == "nexus") {
			echo "<a class='dropdown-item navlink z-depth-0' href='javascript:void(0);'><i class='fad fa-cog fa-fw'></i> Setup mode</a>";
			echo "<a class='dropdown-item navlink z-depth-0' href='javascript:void(0);'><i class='fad fa-link-horizontal fa-fw'></i> Link dump</a>";
			echo "<a class='dropdown-item navlink z-depth-0' href='javascript:void(0);'><i class='fad fa-folder-bookmark fa-fw'></i> Bookmarks</a>";
			echo "<a class='dropdown-item navlink z-depth-0' href='javascript:void(0);'><i class='fad fa-swatchbook fa-fw'></i> Themes</a>";
		} else {
			if ($user_id == 1) {
				echo "<a class='dropdown-item navlink z-depth-0' href='nexus.php'><i class='fad fa-house-turret fa-fw'></i> Nexus</a>";
			}
			if ($pagina_tipo != 'ubwiki') {
				echo "<a class='dropdown-item navlink z-depth-0' href='ubwiki.php'><i class='fad fa-portal-enter fa-fw'></i> {$pagina_translated['environments']}</a>";
			}
			//echo "<a class='dropdown-item navlink z-depth-0' href='carrinho.php'><i class='fad fa-shopping-cart fa-fw'></i> {$pagina_translated['your cart']}</a>";
			if (isset($raiz_sigla)) {
				if (!in_array($pagina_tipo, array('curso', 'escritorio'))) {
					echo "<a class='dropdown-item navlink z-depth-0' href='pagina.php?pagina_id=$raiz_ativa' title='$raiz_titulo'><i class='fad fa-history fa-fw'></i> $raiz_sigla</a>";
				}
			}
			echo "<a class='dropdown-item navlink z-depth-0' href='javascript:void(0);' data-toggle='modal' data-target='#modal_languages'><i class='fad fa-language fa-fw'></i> $user_language_titulo</a>";
			if ($user_id != false) {
				echo "<a class='dropdown-item navlink z-depth-0' href='logout.php?pagina_id=$pagina_id'><i class='fad fa-portal-exit fa-fw'></i> {$pagina_translated['logout']}</a>";
			}
		}
		echo "
		      </li>
		    </ul>
  	";
	} else {
		$carregar_modal_login = true;
		echo "
			<a class='ml-auto waves-effect waves-light rounded text-white' data-toggle='modal' data-target='#modal_login'>
				<i class='fas fa-2x fa-user-circle fa-fw'></i>
			</a>
		";
	}
	echo "</nav>";
	unset($template_navbar_mode);
	unset($template_navbar_color);
	unset($template_navbar_text);