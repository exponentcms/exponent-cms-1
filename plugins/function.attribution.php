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

function smarty_function_attribution($params,&$smarty) {
	if (isset($params['user_id'])) {
		if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
		$u = exponent_users_getUserById($params['user_id']);
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
				$str = $u->lastname . ", " . $u->firstname;
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
