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

$memb = $db->selectObject('groupmembership','member_id='.$user->id.' AND group_id='.$_GET['id'].' AND is_admin=1');

if (pathos_permissions_check('user_management',pathos_core_makeLocation('administrationmodule')) || $memb) {
	$group = $db->selectObject('group','id='.$_POST['id']);
	if ($group) {
		$db->delete('groupmembership','group_id='.$group->id);
		$memb = null;
		$memb->group_id = $group->id;
		if ($_POST['membdata'] != "") {
			foreach (explode(',',$_POST['membdata']) as $str) {
				$str = explode(':',$str);
				$memb->member_id = $str[0];
				$memb->is_admin = $str[1];
				$db->insertObject($memb,'groupmembership');
			}
		}
		pathos_permissions_triggerRefresh();
		pathos_flow_redirect();
	} else echo SITE_404_HTML;
} else {
	echo SITE_403_HTML;
}

?>