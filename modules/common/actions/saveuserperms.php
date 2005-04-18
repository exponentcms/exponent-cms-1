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
	$users = explode(";",$_POST['permdata']);
	if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
	foreach ($users as $user_str) {
		$perms = explode(":",$user_str);
		$u = pathos_users_getUserById($perms[0]);
		pathos_permissions_revokeAll($u,$loc);
		for ($i = 1; $i < count($perms); $i++) {
			pathos_permissions_grant($u,$perms[$i],$loc);
		}
		
		if ($perms[0] == $user->id) {
			pathos_permissions_load($user);
		}
	}
	pathos_permissions_triggerRefresh();
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>