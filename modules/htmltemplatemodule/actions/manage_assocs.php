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

/**
 * Template <-> Module Associations Manager Interface
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage HTMLTemplate
 */

if (!defined("PATHOS")) exit("");

// PERM CHECK
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined("SYS_MODULES")) include_once(BASE."subsystems/modules.php");
	//$mods = array_flip(pathos_modules_list());
	foreach (pathos_modules_list() as $mod) {
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