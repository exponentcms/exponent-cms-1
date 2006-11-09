<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check('extensions',exponent_core_makeLocation('administrationmodule'))) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$tag_collections = $db->selectObjects("tag_collections");
	$template = new template("tags","_manage_collections");
	
	if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
	usort($tag_collections, "exponent_sorting_byNameAscending");

	$template->assign("tag_collections",$tag_collections);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
