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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	$ticket = $db->selectObject('sessionticket',"ticket='".$_GET['ticket']."'");
	if ($ticket) {
		if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
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