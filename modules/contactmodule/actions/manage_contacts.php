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

if (pathos_permissions_check("configure",$loc)) {
	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$contacts = array();
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	
	foreach ($db->selectObjects("contact_contact","location_data='".serialize($loc)."'") as $c) {
		if ($c->user_id != 0) {
			$u = pathos_users_getUserById($c->user_id);
			$c->email = $u->email;
			$c->name = $u->firstname . " " . $u->lastname;
			if ($c->name == "") $c->name = $u->username;
		} else {
			$c->name = "<i>&lt;none&gt;</i>";
		}
		$contacts[] = $c;
	}
	
	$template = new template("contactmodule","_contactmanager",$loc);
	$template->assign("contacts",$contacts);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>