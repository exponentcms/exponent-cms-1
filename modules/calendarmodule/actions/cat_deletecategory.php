<?php

if (!defined("PATHOS")) exit("");

$cat = null;
if (isset($_GET['id'])) $cat = $db->selectObject("category","id=".$_GET['id']);
if ($cat) {
	$loc = unserialize($cat->location_data);
	// PERM CHECK
	$db->delete("category","id=".$cat->id);
	pathos_flow_redirect();
	// END PERM CHECK
} else {
	echo SITE_404_HTML;
}

?>