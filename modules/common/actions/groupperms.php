<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

if (pathos_permissions_check("administrate",$loc)) {
	if (pathos_template_getModuleViewFile($loc->mod,"_grouppermissions",false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template("common","_grouppermissions",$loc);
	} else {
		$template = new template($loc->mod,"_grouppermissions",$loc);
	}
	$template->assign("user_form",0);
	/////////////////////////////
	if (!defined("SYS_GROUPS")) include_once(BASE."subsystems/users.php");
	$users = array(); // users = groups
	
	$modclass = $loc->mod;
	$mod = new $modclass();
	$perms = $mod->permissions($loc->int);
	// Create the anonymous group
	$g = null;
	$g->id = 0;
	$g->name = "Anonymous Users";
	foreach ($perms as $perm=>$name) {
		$var = "perms_$perm";
		if (pathos_permissions_checkGroup($g,$perm,$loc,true)) $g->$var = 1;
		else if (pathos_permissions_checkGroup($g,$perm,$loc)) $g->$var = 2;
		else $g->$var = 0;
	}
	$users[] = $g;
	
	foreach (pathos_users_getAllGroups() as $g) {
		foreach ($perms as $perm=>$name) {
			$var = "perms_$perm";
			if (pathos_permissions_checkGroup($g,$perm,$loc,true)) $g->$var = 1;
			else if (pathos_permissions_checkGroup($g,$perm,$loc)) $g->$var = 2;
			else $g->$var = 0;
		}
		$users[] = $g;
	}
	$template->assign("have_users",1); // users = groups
	$template->assign("users",$users); // users = groups
	$template->assign("perms",$perms);
	/////////////////////////////
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>