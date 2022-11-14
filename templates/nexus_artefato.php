<?php

	if (!isset($nexus_artefato_id)) {
		$nexus_artefato_id = false; //
	}
	if (!isset($nexus_artefato_modal)) {
		$nexus_artefato_modal_module = false;
	} else {
		$nexus_artefato_modal_module = "data-bs-toggle='modal' data-bs-target='{$nexus_artefato_modal}'";
	}

	if (!isset($nexus_artefato_link)) {
		$nexus_artefato_link_module = "href='javascript:void(0);'";
	} else {
		$nexus_artefato_link_module = "href='{$nexus_artefato_link}'";
	}
	if (!isset($nexus_fa_color)) {
		$nexus_fa_color = 'link-indigo';
	}
	if (!isset($nexus_fa_icone)) {
		$nexus_fa_icone = 'fa-solid fa-circle-notch fa-spin';
	}
	if (!isset($nexus_fa_size)) {
		$nexus_fa_size = 'fa-4x';
	}
	if (!isset($nexus_artefato_titulo)) {
		$nexus_artefato_titulo = false;
	}
	if (!isset($nexus_artefato_class)) {
		$nexus_artefato_class = false;
	}
	if (!isset($nexus_artefato_span_class)) {
		$nexus_artefato_span_class = false;
	}

	$nexus_artefato_result = "
	<div class='col-2 p-2 {$nexus_artefato_class}'>
		<span id='trigger_{$nexus_artefato_id}' class='row p-2 rounded d-flex justify-content-center pointer' $nexus_artefato_modal_module $nexus_artefato_link_module>
			<span class='{$nexus_artefato_span_class} p-3 rounded'>
			<div class='row d-flex mb-2'>
				<span class='col-12 {$nexus_fa_color} d-flex justify-content-center'>
					<i class='{$nexus_fa_icone} {$nexus_fa_size} fa-fw'></i>
				</span>
			</div>
			<div class='row d-flex'>
				<small class='col-12 link-light d-flex justify-content-center'>
					{$nexus_artefato_titulo}
				</small>
			</div>
			</span>
		</span>
	</div>
	";

	unset($nexus_artefato_modal_module);
	unset($nexus_artefato_link_module);
	unset($nexus_artefato_id);
	unset($nexus_artefato_modal);
	unset($nexus_artefato_link);
	unset($nexus_fa_color);
	unset($nexus_fa_icone);
	unset($nexus_artefato_titulo);
	unset($nexus_artefato_class);
	unset($nexus_artefato_span_class);

	return $nexus_artefato_result;