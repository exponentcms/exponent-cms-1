<?php

// Normalizer Script
// This script normalizes the database and sets some things back in order.

// Normalize Section Rankings
function pathos_backup_normalize_sections($db,$parent = 0) {
	$sections = $db->selectObjects('section','parent='.$parent);
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($sections,'pathos_sorting_byRankAscending');
	$rank = 0;
	foreach ($sections as $s) {
		$s->rank = $rank;
		$db->updateObject($s,'section');
		pathos_backup_normalize_sections($db,$s->id); // Normalize children
		$rank++;
	}
}

pathos_backup_normalize_sections($db);

?>