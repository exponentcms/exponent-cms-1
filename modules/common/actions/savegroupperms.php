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

    $groups = explode(';',$_POST['permdata']);
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	foreach ($groups as $group_str) {
		$perms = explode(':',$group_str);
		$g = pathos_users_getGroupById($perms[0]);
		pathos_permissions_revokeAllGroup($g,$loc);
		for ($i = 1; $i < count($perms); $i++) {
			pathos_permissions_grantGroup($g,$perms[$i],$loc);
		}
	}
	pathos_permissions_triggerRefresh();
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
