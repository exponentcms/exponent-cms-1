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

class inbox_contactlist {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->description = "";
			$object->_members = array();
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("name","Group Name",new textcontrol($object->name));
		$form->register("description","Description",new texteditorcontrol($object->description));
		
		if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
		global $user;
		if (pathos_permissions_check("contact_all",pathos_core_makeLocation("inboxmodule"))) {
			foreach (pathos_users_getAllUsers() as $u) {
				$users[$u->id] = $u;
			}
		} else {
			foreach (pathos_users_getGroupsForUser($user,1,0) as $g) {
				foreach (pathos_users_getUsersInGroup($g) as $u) {
					$users[$u->id] = $u;
				}
			}
		}
		
		foreach (array_keys($users) as $i) {
			$users[$i] = $users[$i]->firstname . " " . $users[$i]->lastname . " (" . $users[$i]->username. ")";
		}
		
		global $db;
		// Process other uses who the current user has blocked, and remove them from the list
		// Process other users who have blocked the current user, and remove them from the list.
		foreach ($db->selectObjects("inbox_contactbanned","owner=".$user->id . " OR user_id=" . $user->id) as $blocked) {
			if ($blocked->user_id == $user->id) {
				// Blocked by someone else.  Remove the owner (user who blocked us)
				unset($users[$blocked->owner]);
			} else if ($blocked->owner == $user->id) {
				// We blocked the user, remove the blocked user_id
				unset($users[$blocked->user_id]);
			}
		}
		
		
		$members = array();
		
		for ($i = 0; $i < count($object->_members); $i++) {
			$tmp = $object->_members[$i];
			$members[$tmp] = $users[$tmp];
			unset($users[$tmp]);
		}
		
		$form->register("members","Members",new listbuildercontrol($members,$users));
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		$object->name = $values['name'];
		$object->description = $values['description'];
		$object->_members = listbuildercontrol::parseData($values,"members");
		pathos_forms_cleanup();
		return $object;
	}
}

?>