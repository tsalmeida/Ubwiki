<?php

	if (!isset($nexus_artefato_id)) {
		$nexus_artefato_id = false;
	}
	if (!isset($nexus_artefato_modal)) {
		$nexus_artefato_modal = false;
	}
	if (!isset($nexus_artefato_classes_detail)) {
		$nexus_artefato_classes_detail = false;
	}
	if (!isset($nexus_artefato_link_classes)) {
		$nexus_artefato_link_classes = false;
	}
	if (!isset($nexus_fa_color)) {
		$nexus_fa_color = false;
	}
	if (!isset($nexus_fa_icone)) {
		$nexus_fa_icone = false;
	}
	if (!isset($nexus_artefato_titulo)) {
		$nexus_artefato_titulo = false;
	}

	$nexus_artefato_result = "
	<a id='trigger_{$nexus_artefato_id}' class='col-2 {$nexus_artefato_link_classes}' href='javascript:void(0);'>
		<div id='{$nexus_artefato_id}' data-bs-toggle='modal' data-bs-target='{$nexus_artefato_modal}' class='row {$nexus_artefato_class} {$nexus_artefato_classes_detail}'>
		<span class='row justify-content-center p-1 rounded {$nexus_fa_color}'><span class='col-12'><i class='{$nexus_fa_icone} fa-fw'></i></span>
		<span class='row justify-content-center p-1 rounded'><span class='col-12'>{$nexus_artefato_titulo}</span></span>
	</div></a>
	";

	unset($nexus_artefato_modal);
	unset($nexus_artefato_classes);
	unset($nexus_artefato_trigger);
	unset($nexus_artefato_fa_icon);
	unset($nexus_artefato_titulo);

	return $nexus_artefato_result;