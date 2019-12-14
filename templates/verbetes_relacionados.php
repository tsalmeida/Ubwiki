<?php
	// VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS VERBETES RELACIONADOS
	
	
	$breadcrumbs = "
    <div class='d-block'><a href='index.php'>$concurso_sigla</a></div>
    <div class='d-block spacing0'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='materia.php?materia_id=$materia_id'>$materia_titulo</a></div>
  ";
	
	$result = $conn->query("SELECT id, nivel, nivel1, nivel2, nivel3, nivel4, nivel5 FROM Topicos WHERE materia_id = $materia_id ORDER BY ordem");
	
	if ($nivel == 1) {
		$count = 0;
		$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row = $result->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel1 = $row['id'];
			$titulo_nivel1 = $row['nivel1'];
			$nivel_nivel1 = $row['nivel'];
			if ($nivel_nivel1 == 1) {
				if ($titulo_nivel1 == $nivel1) {
					$breadcrumbs .= "<div class='spacing1'>$fawesome$titulo_nivel1</div>";
					$count2 = 0;
					$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
					$result2 = $conn->query("SELECT id, nivel2 FROM Topicos WHERE nivel1 = '$id_nivel1' AND nivel = 2 ORDER BY ordem");
					while ($row2 = $result2->fetch_assoc()) {
						$id_nivel2 = $row2['id'];
						$titulo_nivel2 = $row2['nivel2'];
						$count2++;
						if ($count2 == 2) {
							$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
						}
						$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='verbete.php?topico_id=$id_nivel2'>$titulo_nivel2</a></div>";
					}
				} else {
					$breadcrumbs .= "<div class='spacing1'>$fawesome<a href='verbete.php?topico_id=$id_nivel1'>$titulo_nivel1</a></div>";
				}
			}
		}
	}
	if ($nivel > 1) {
		$titulo_nivel1 = return_titulo_topico($nivel1);
		$breadcrumbs .= "<div class='spacing1'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?topico_id=$nivel1'>$titulo_nivel1</a></div>";
	}
	if ($nivel == 2) {
		$result2 = $conn->query("SELECT id, nivel2 FROM Topicos WHERE nivel1 = $nivel1 AND nivel = 2 ORDER BY ordem");
		$count = 0;
		$fawesome = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row2 = $result2->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			}
			$id_nivel2 = $row2['id'];
			$titulo_nivel2 = $row2['nivel2'];
			if ($titulo_nivel2 == $nivel2) {
				$breadcrumbs .= "<div class='spacing2'>$fawesome$nivel2</div>";
				$result3 = $conn->query("SELECT id, nivel3 FROM Topicos WHERE materia_id = $materia_id AND nivel = 3 AND nivel2 = $id_nivel2 ORDER BY ordem");
				$count = 0;
				$fawesome3 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row3 = $result3->fetch_assoc()) {
					$id_nivel3 = $row3['id'];
					$titulo_nivel3 = $row3['nivel3'];
					$count++;
					if ($count == 2) {
						$fawesome3 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='verbete.php?topico_id=$id_nivel3'>$titulo_nivel3</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing2'>$fawesome<a href='verbete.php?topico_id=$id_nivel2'>$titulo_nivel2</a></div>";
			}
		}
	}
	if ($nivel > 2) {
		$titulo_nivel2 = return_titulo_topico($nivel2);
		$breadcrumbs .= "<div class='spacing2'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?topico_id=$nivel2'>$titulo_nivel2</a></div>";
	}
	if ($nivel == 3) {
		$result3 = $conn->query("SELECT id, nivel3 FROM Topicos WHERE nivel2 = $nivel2 AND nivel = 3 ORDER BY ordem");
		$count = 0;
		$fawesome3 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row3 = $result3->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome3 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel3 = $row3['id'];
			$titulo_nivel3 = $row3['nivel3'];
			if ($titulo_nivel3 == $nivel3) {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3$nivel3</div>";
				$result4 = $conn->query("SELECT id, nivel4 FROM Topicos WHERE materia_id = $materia_id AND nivel = 4 AND nivel3 = $id_nivel3 ORDER BY ordem");
				$count = 0;
				$fawesome4 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row4 = $result4->fetch_assoc()) {
					$id_nivel4 = $row4['id'];
					$titulo_nivel4 = $row4['nivel4'];
					$count++;
					if ($count == 2) {
						$fawesome4 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='verbete.php?topico_id=$id_nivel4'>$titulo_nivel4</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing3'>$fawesome3<a href='verbete.php?topico_id=$id_nivel3'>$titulo_nivel3</a></div>";
			}
		}
	}
	if ($nivel > 3) {
		$titulo_nivel3 = return_titulo_topico($nivel3);
		$breadcrumbs .= "<div class='spacing3'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?topico_id=$nivel3'>$titulo_nivel3</a></div>";
	}
	if ($nivel == 4) {
		$result4 = $conn->query("SELECT id, nivel4 FROM Topicos WHERE nivel3 = $nivel3 AND nivel = 4 ORDER BY ordem");
		$count = 0;
		$fawesome4 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row4 = $result4->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome4 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel4 = $row4['id'];
			$titulo_nivel4 = $row4['nivel4'];
			if ($titulo_nivel4 == $nivel4) {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4$nivel4</div>";
				$result5 = $conn->query("SELECT id, nivel5 FROM Topicos WHERE materia_id = $materia_id AND nivel = 5 AND nivel4 = $id_nivel4 ORDER BY ordem");
				$count = 0;
				$fawesome5 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
				while ($row5 = $result5->fetch_assoc()) {
					$id_nivel5 = $row5['id'];
					$titulo_nivel5 = $row5['nivel5'];
					$count++;
					if ($count == 2) {
						$fawesome5 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
					}
					$breadcrumbs .= "<div class='spacing5'>$fawesome5<a href='verbete.php?topico_id=$id_nivel5'>$titulo_nivel5</a></div>";
				}
			} else {
				$breadcrumbs .= "<div class='spacing4'>$fawesome4<a href='verbete.php?topico_id=$id_nivel4'>$titulo_nivel4</a></div>";
			}
		}
	}
	if ($nivel > 4) {
		$titulo_nivel4 = return_titulo_topico($nivel4);
		$breadcrumbs .= "<div class='spacing4'><i class='fal fa-level-up fa-rotate-90 fa-fw'></i><a href='verbete.php?topico_id=$nivel4'>$titulo_nivel4</a></div>";
	}
	if ($nivel == 5) {
		$result5 = $conn->query("SELECT id, nivel5 FROM Topicos WHERE nivel4 = $nivel4 AND nivel = 5 ORDER BY ordem");
		$count = 0;
		$fawesome5 = "<i class='fal fa-level-up fa-rotate-90 fa-fw'></i>";
		while ($row5 = $result5->fetch_assoc()) {
			$count++;
			if ($count == 2) {
				$fawesome5 = "<i class='fal fa-long-arrow-right fa-fw'></i>";
			};
			$id_nivel5 = $row5['id'];
			$titulo_nivel5 = $row5['nivel5'];
			if ($titulo_nivel5 == $nivel5) {
				$breadcrumbs .= "<div class='spacing5'>$fawesome5$nivel5</div>";
			} else {
				$breadcrumbs .= "<div class='spacing5'>$fawesome5<a href='verbete.php?topico_id=$id_nivel5'>$titulo_nivel5</a></div>";
			}
		}
	}
	?>
