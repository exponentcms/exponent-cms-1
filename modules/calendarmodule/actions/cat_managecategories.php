<?php

if (!defined("PATHOS")) exit("");

// PERM CHECK
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	$categories = $db->selectObjects("category","location_data='".serialize($loc)."'");
	$template = new template("calendarmodule","_cat_manageCategories",$loc);
	$template->assign("categories",$categories);
	$template->output();
// END PERM CHECK

?>