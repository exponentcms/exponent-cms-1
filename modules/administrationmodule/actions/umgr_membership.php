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
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	$u = pathos_users_getUserById($_GET['id']);
	if ($u) {
		$groups = pathos_users_getAllGroups();
		
		$admin = array();
		$membership = array();
		foreach ($db->selectObjects('groupmembership','member_id='.$u->id) as $m) {
			$membership[] = $m->group_id;
			if ($m->is_admin == 1) {
				$admin[] = $m->group_id;
			}
		}
		
		for ($i = 0; $i < count($groups); $i++) {
			if (in_array($groups[$i]->id,$membership)) {
				$groups[$i]->is_member = 1;
				if (in_array($groups[$i]->id,$admin)) {
					$groups[$i]->is_admin = 1;
				} else {
					$groups[$i]->is_admin = 0;
				}
			} else {
				$groups[$i]->is_member = 0;
			}
		}
		
		$template = new template('administrationmodule','_usermembership',$loc);
		$template->assign('user',$u);
		$template->assign('groups',$groups);
		$template->assign('canAdd',(count($membership) < count($groups) ? 1 : 0));
		$template->assign('hasMember',(count($membership) > 0 ? 1 : 0));
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>