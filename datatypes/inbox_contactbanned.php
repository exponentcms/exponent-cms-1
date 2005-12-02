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

class inbox_contactbanned {
	function form($object) {
		$i18n = pathos_lang_loadFile('database/inbox_contactbanned.php');
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
		
		$users = array();
		foreach (pathos_users_getAllUsers() as $u) {
			if ($u->is_acting_admin == 0 && $u->id != $user->id) {
				$users[$u->id] = $u->firstname . ' ' . $u->lastname . ' (' . $u->username . ')';
			}
		}
		
		foreach ($db->selectObjects('inbox_contactbanned','owner='.$user->id) as $b) {
			unset($users[$b->user_id]);
		}
		
		$form->register('uid',$i18n['uid'],new dropdowncontrol(0,$users));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		
		return $object;
	}
}

?>