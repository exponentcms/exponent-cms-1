<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('administrate',$loc)) {
	$users = explode(';',$_POST['permdata']);
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	foreach ($users as $user_str) {
		$perms = explode(':',$user_str);
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