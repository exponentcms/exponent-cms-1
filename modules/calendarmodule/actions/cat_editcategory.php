<?php

if (!defined("PATHOS")) exit("");

$cat = null;
if (isset($_GET['id'])) $cat = $db->selectObject("category","id=".$_GET['id']);
if ($cat) {
	$loc = unserialize($cat->location_data);
}
// PERM CHECK
	$form = category::form($cat);
	$form->location($loc);
	$form->meta("action","cat_savecategory");
	echo $form->toHTML();
// END PERM CHECK

?>