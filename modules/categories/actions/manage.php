<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined("PATHOS")) exit("");

$mloc = pathos_core_makeLocation($_GET['orig_module'], $loc->src, $loc->int);

if (pathos_permissions_check('manage_categories',$mloc)) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	$categories = $db->selectObjects("category","location_data='".serialize($mloc)."'");
	if (pathos_template_getModuleViewFile($mloc->mod,"_cat_manageCategories",false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template("categories","_cat_manageCategories",$loc);
	} else {
		$template = new template($mloc->mod,"_cat_manageCategories",$loc);
	}	
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($categories, "pathos_sorting_byRankAscending");
	$template->assign("origmodule", $_GET['orig_module']);
	$template->assign("categories",$categories);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>