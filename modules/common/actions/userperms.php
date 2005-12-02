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
	if (pathos_template_getModuleViewFile($loc->mod,'_userpermissions',false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template('common','_userpermissions',$loc);
	} else {
		$template = new template($loc->mod,'_userpermissions',$loc);
	}
	$template->assign('user_form',1);
	
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	$users = array();
	$modclass = $loc->mod;
	$mod = new $modclass();
	$perms = $mod->permissions($loc->int);
	$have_users = 0;
	foreach (pathos_users_getAllUsers(0) as $u) {
		$have_users = 1;
		foreach ($perms as $perm=>$name) {
			$var = 'perms_'.$perm;
			if (pathos_permissions_checkUser($u,$perm,$loc,true)) {
				$u->$var = 1;
			} else if (pathos_permissions_checkUser($u,$perm,$loc)) {
				$u->$var = 2;
			} else {
				$u->$var = 0;
			}
		}
		$users[] = $u;
	}
	$template->assign('have_users',$have_users);
	$template->assign('users',$users);
	$template->assign('perms',$perms);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>