<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

// PERM CHECK
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	
	$mloc = pathos_core_makeLocation($_GET['orig_module'], $loc->src, $loc->int);
	$categories = $db->selectObjects("category","location_data='".serialize($mloc)."'");
	if (pathos_template_getModuleViewFile($mloc->mod,"_cat_manageCategories",false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template("categories","_cat_manageCategories",$loc);
	} else {
		$template = new template($mloc->mod,"_cat_manageCategories",$loc);
	}	
	usort($categories, "pathos_sorting_byRankAscending");
	$template->assign("origmodule", $_GET['orig_module']);
	$template->assign("categories",$categories);
	$template->output();
// END PERM CHECK

?>