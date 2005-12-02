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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	$ticket = $db->selectObject('sessionticket',"ticket='".preg_replace('/[^A-Za-z0-9]/','',$_GET['ticket'])."'");
	if ($ticket) {
		if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
		$u = pathos_users_getUserById($ticket->uid);
		if ($u->is_acting_admin == 0 || ($user->is_admin == 1 && $u->is_admin == 0)) {
			// We can only kick the user if they are A) not an acting admin, or B) The current user is a super user and the kicked user is not.
			$db->delete('sessionticket',"ticket='".$ticket->ticket."'");
		}
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>