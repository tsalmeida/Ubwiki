<?php
	if (!isset($template_navbar_mode)) {
		$template_navbar_mode = 'dark';
	}
	if ($template_navbar_mode == 'dark') {
		$template_navbar_color = 'elegant-color';
		$template_navbar_text = 'text-white';
	}
	elseif ($template_navbar_mode == 'light') {
		$template_navbar_color = 'bg-white';
		$template_navbar_text = 'text-dark';
	}
	elseif ($template_navbar_mode == 'transparent') {
		$template_navbar_color = 'transparent';
		$template_navbar_text = 'text-white';
	}
	
	$concursos = $conn->query("SELECT id, titulo FROM Concursos WHERE estado = 1");
	
	echo "<nav class='navbar navbar-expand-lg $template_navbar_color' id='inicio'>";
	echo "<a class='navbar-brand playfair900 $template_navbar_text' href='index.php'>Ubwiki</a>";
	echo "<ul class='nav navbar-nav ml-auto nav-flex-icons'>";
	echo "<li class='nav-item dropdown'>";
	echo "<a class='navlink dropdown-toggle waves-effect waves-light $template_navbar_text' id='user_dropdown' data-toggle='dropdown' href='javascript:void(0);'>";
	echo "
        <i class='fas fa-user-tie fa-lg fa-fw'></i>
        </a>
        <div class='dropdown-menu dropdown-menu-right z-depth-0'>
          <a class='dropdown-item navlink z-depth-0' href='escritorio.php'>Seu escritório</a>
          <a class='dropdown-item navlink z-depth-0' href='cursos.php'>Escolher curso</a>
          <a class='dropdown-item navlink z-depth-0' href='logout.php'>Logout</a>
      </li>
    </ul>
  </nav>";

	unset($template_navbar_mode);
	unset($template_navbar_color);
	unset($template_navbar_text);