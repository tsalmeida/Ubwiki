<?php
	if (!isset($template_navbar_mode)) {
		$template_navbar_mode = 'dark';
	}
	if (!isset($pagina_padrao)) {
		$pagina_padrao = false;
	}
	if (!isset($navbar_custom_leftside)) {
		$navbar_custom_leftside = false;
	}
	if (!isset($navbar_custom_rightside)) {
		$navbar_custom_rightside = false;
	}
	if (!isset($navbar_custom_dropdown_start)) {
		$navbar_custom_dropdown_start = false;
	}
	if (!isset($navbar_custom_dropdown_end)) {
		$navbar_custom_dropdown_end = false;
	}
		if ($template_navbar_mode == 'dark') {
		$template_navbar_color = 'bg-dark';
		$template_navbar_text = 'text-white';
	} elseif ($template_navbar_mode == 'light') {
		$template_navbar_color = 'bg-white';
		$template_navbar_text = 'text-dark';
	} elseif ($template_navbar_mode == 'transparent') {
		$template_navbar_color = 'transparent';
		$template_navbar_text = 'text-white';
	}
	echo "
		<nav class='navbar navbar-expand-lg $template_navbar_color px-3' id='navbar'>
			<div class='container-fluid'>
			
	";

	if (($pagina_tipo != "curso") && ($pagina_tipo != "nexus")) {
		if (isset($raiz_ativa)) {
			echo "<a class='navbar-brand $template_navbar_text me-auto' href='pagina.php?pagina_id=$raiz_ativa' title='$raiz_titulo'>$raiz_sigla</a>";
		} else {
			echo "<a class='navbar-brand $template_navbar_text me-auto' href='ubwiki.php' title='{$pagina_translated['Retornar ao nexus.']}'>Ubwiki</a>";
		}
	}

	echo $navbar_custom_leftside;

//	if ($pagina_padrao == true) {
//		echo "
//				<a href='javascript:void(0);' class='text-black-50' id='hide_bars'><i class='fad fa-eye-slash fa-fw fa-sm'></i></a>
//			";
//	}
	if ($user_id != false) {
		echo "
			<div class='collapse navbar-collapse flex-row-reverse' id='navbarNavDropdown'>
			<ul class='navbar-nav'>";
		echo "<li class='nav-item dropdown'>";
		echo $navbar_custom_rightside;
		echo "
				<a class='navlink dropdown-toggle bg-dark $template_navbar_text ms-auto' id='user_dropdown' href='javascript:void(0);' role='button' data-bs-toggle='dropdown' aria-expander='false'>
		        	<i class='fas fa-2x {$_SESSION['user_avatar_icone']} fa-lg fa-fw'></i>
				</a>
							
		        <ul class='dropdown-menu dropdown-menu-end' aria-labelledby=\"navbarDropdownMenuLink\">";
		echo $navbar_custom_dropdown_start;
		if ($pagina_tipo != 'escritorio') {
			echo "<li><a class='dropdown-item' href='escritorio.php'><i class='fad fa-lamp-desk fa-fw'></i> {$pagina_translated['office']}</a></li>";
		}
		if ($pagina_tipo != "nexus") {
			if ($user_id == 1) {
				echo "<li><a class='dropdown-item' href='nexus.php'><i class='fad fa-house-turret fa-fw'></i> Nexus</a></li>";
			}
			if ($pagina_tipo != 'ubwiki') {
				echo "<li><a class='dropdown-item' href='ubwiki.php'><i class='fad fa-portal-enter fa-fw'></i> {$pagina_translated['environments']}</a></li>";
			}
			//echo "<li><a class='dropdown-item' href='carrinho.php'><i class='fad fa-shopping-cart fa-fw'></i> {$pagina_translated['your cart']}</a></li>";
			if (isset($raiz_sigla)) {
				if (!in_array($pagina_tipo, array('curso', 'escritorio'))) {
					echo "<li><a class='dropdown-item' href='pagina.php?pagina_id=$raiz_ativa' title='$raiz_titulo'><i class='fad fa-history fa-fw'></i> $raiz_sigla</a></li>";
				}
			}
			echo "<li><a class='dropdown-item' href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#modal_languages'><i class='fad fa-language fa-fw'></i> $user_language_titulo</a></li>";
			if ($user_id != false) {
				echo "<li><a class='dropdown-item' href='logout.php?pagina_id=$pagina_id'><i class='fad fa-portal-exit fa-fw'></i> {$pagina_translated['logout']}</a></li>";
			}
		}
		echo $navbar_custom_dropdown_end;
		echo " 
		    </ul>
  	";
	} else {
		$carregar_modal_login = true;
		echo "
			<a class='ms-auto rounded text-white' data-bs-toggle='modal' data-bs-target='#modal_login' href='javascript:void(0);'>
				<i class='fas fa-2x fa-user-circle fa-fw'></i>
			</a>
		";
	}
	echo "</div></div></nav>";
	unset($template_navbar_mode);
	unset($template_navbar_color);
	unset($template_navbar_text);
	unset($navbar_custom_dropdown_end);
	unset($navbar_custom_dropdown_start);
	unset($navbar_custom_leftside);
	unset($navbar_custom_rightside);