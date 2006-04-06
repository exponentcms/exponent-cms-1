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

// PERM CHECK
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined("SYS_MODULES")) require_once(BASE."subsystems/modules.php");
	//$mods = array_flip(exponent_modules_list());
	foreach (exponent_modules_list() as $mod) {
		if (call_user_func(array($mod,"hasContent")) == true) {
			$mods[$mod] = null;
			$mods[$mod]->name = call_user_func(array($mod,"name"));
			$mods[$mod]->associated = $db->selectObjects("htmltemplateassociation","module='$mod'");
		}
	}
	$template = new template("htmltemplatemodule","_viewassocs",$loc);
	
	$template->assign("modules",$mods);
	
	$template->assign("templates",$db->selectObjectsIndexedArray("htmltemplate"));
	$template->assign("template_count",$db->countObjects("htmltemplate"));
	$template->assign("globals",$db->selectObjectsIndexedArray("htmltemplateassociations","is_global = 1"));
	
	$template->output();
// END PERM CHECK

?>