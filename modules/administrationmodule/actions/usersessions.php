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

// Part of the User Management category

if (!defined('PATHOS')) exit('');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	pathos_flow_set(SYS_FLOW_PROTECTED, SYS_FLOW_ACTION);

	$db->delete('sessionticket','last_active < ' . (time() - SESSION_TIMEOUT));
	
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	if (!defined('SYS_DATETIME')) include_once(BASE.'subsystems/datetime.php');
	
	$sessions = $db->selectObjects('sessionticket');
	for ($i = 0; $i < count($sessions); $i++) {
		$sessions[$i]->user = pathos_users_getUserById($sessions[$i]->uid);
		$sessions[$i]->duration = pathos_datetime_duration($sessions[$i]->last_active,$sessions[$i]->start_time);
	}
	
	$template = new template('administrationmodule','_sessionmanager',$loc);
	$template->assign('sessions',$sessions);
	$template->assign('user',$user);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>