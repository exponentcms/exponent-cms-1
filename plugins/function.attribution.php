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

function smarty_function_attribution($params,&$smarty) {
	if (isset($params['user_id'])) {
		if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
		$u = pathos_users_getUserById($params['user_id']);
	} else if (isset($params['user'])) {
		$u = $params['user'];
	}
	if ($u) {
		$str = "";
		switch (DISPLAY_ATTRIBUTION) {
			case "firstlast":
				$str = $u->firstname . " " . $u->lastname;
				break;
			case "lastfirst":
				$str = $u->lastname . ", " . $u->lastname;
				break;
			case "first":
				$str = $u->firstname;
				break;
			case "username":
			default:
				$str = $u->username;
				break;
		}
		echo $str;
	}
}

?>