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

class inbox_contactbanned {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
		
		$users = pathos_users_getAllUsers();
		global $db,$user;
		foreach ($db->selectObjects("inbox_contactbanned","owner=".$user->id) as $b) {
			unset($users[$b->user_id]);
		}
		foreach ($db->selectObjects("inbox_contact","owner=".$user->id) as $c) {
			unset($users[$c->user_id]);
		}
		foreach (array_keys($users) as $id) {
			$users[$id] = $users[$id]->firstname . " " . $users[$id]->lastname . " (" . $users[$id]->username . ")";
		}
		
		$form->register("uid","User",new dropdowncontrol(0,$users));
		$form->register("submit","",new buttongroupcontrol("Block User","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		
		return $object;
	}
}

?>