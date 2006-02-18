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

if (isset($_GET['id']) && pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule'))) {
	if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
	
	$u = pathos_users_getUserById(intval($_GET['id']));
	
	$template = new template('administrationmodule','_umgr_edit_which',$loc);
	$template->assign('user',$u);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>