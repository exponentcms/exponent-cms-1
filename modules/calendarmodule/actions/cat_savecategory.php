<?php

if (!defined("PATHOS")) exit("");

$cat = null;
if (isset($_POST['id'])) $cat = $db->selectObject("category","id=".$_POST['id']);
if ($cat) {
	$loc = unserialize($cat->location_data);
}
// PERM CHECK
	$cat = category::update($_POST,$cat);
	$cat->location_data = serialize($loc);
	if (isset($cat->id)) $db->updateObject($cat,"category");
	else $db->insertObject($cat,"category");
	pathos_flow_redirect();
// END PERM CHECK

?>