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

if (!defined("PATHOS")) exit("");

if ($user && $user->is_acting_admin) {
	$group = $db->selectObject("group","id=".$_GET['id']);
	if ($group) {
		if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
		$users = pathos_users_getAllUsers(0);
		
		$members = array();
		foreach ($db->selectObjects("groupmembership","group_id=".$group->id) as $m) {
			$members[] = $m->member_id;
		}
		
		for ($i = 0; $i < count($users); $i++) {
			if (in_array($users[$i]->id,$members)) $users[$i]->is_member = 1;
			else $users[$i]->is_member = 0;
		}
		
		$template = new Template("administrationmodule","_groupmembership",$loc);
		$template->assign("group",$group);
		$template->assign("users",$users);
		$template->assign("canAdd",(count($members) < count($users) ? 1 : 0));
		$template->assign("hasMember",(count($members) > 0 ? 1 : 0));
		$template->output();
	} else echo SITE_404_HTML;
} else {
	echo SITE_403_HTML;
}

?>