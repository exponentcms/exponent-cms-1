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

if (pathos_permissions_check("administrate",$loc)) {
	if (pathos_modules_getViewFile($loc->mod,"_grouppermissions",false) == "") {
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
	$have_users = 0;
	foreach (pathos_users_getAllGroups() as $g) {
		$have_users = 1;
		foreach ($perms as $perm=>$name) {
			$var = "perms_$perm";
			$g->$var = (pathos_permissions_checkGroup($g,$perm,$loc) ? 1 : 0);
		}
		$users[] = $g;
	}
	$template->assign("have_users",$have_users); // users = groups
	$template->assign("users",$users); // users = groups
	$template->assign("perms",$perms);
	/////////////////////////////
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>