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

class privatemessage {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/privatemessage.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		
		$users = array();
		$groups = array();
		global $db, $user;
		
		if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
		if (pathos_permissions_check('contact_all',pathos_core_makeLocation('inboxmodule'))) {
			foreach (pathos_users_getAllUsers() as $u) {
				$users[$u->id] = $u->firstname . ' ' . $u->lastname . ' (' . $u->username . ')';
			}
		} else {
			foreach (pathos_users_getGroupsForUser($user,1,0) as $g) {
				foreach (pathos_users_getUsersInGroup($g) as $u) {
					$users[$u->id] = $u->firstname . ' ' . $u->lastname . ' (' . $u->username . ')';
				}
			}
		}
		
		// Process other uses who the current user has blocked, and remove them from the list
		// Process other users who have blocked the current user, and remove them from the list.
		foreach ($db->selectObjects('inbox_contactbanned','owner='.$user->id . ' OR user_id=' . $user->id) as $blocked) {
			if ($blocked->user_id == $user->id) {
				// Blocked by someone else.  Remove the owner (user who blocked us)
				unset($users[$blocked->owner]);
			} else if ($blocked->owner == $user->id) {
				// We blocked the user, remove the blocked user_id
				unset($users[$blocked->user_id]);
			}
		}
		uasort($users,'strnatcmp');
		
		$groups = array();
		foreach ($db->selectObjects('inbox_contactlist','owner='.$user->id) as $g) {
			$groups['list_'.$g->id] = $g->name . ' ' . $i18n['personal_list'];
		}
		if (pathos_permissions_check('contact_all',pathos_core_makeLocation('inboxmodule'))) {
			foreach (pathos_users_getAllGroups(1,0) as $g) {
				$groups['group_'.$g->id] = $g->name . ' ' . $i18n['system_group'];
			}
		} else {
			foreach (pathos_users_getGroupsForUser($user,1,0) as $g) {
				$groups['group_'.$g->id] = $g->name . ' ' . $i18n['system_group'];
			}
		}
		uasort($groups,'strnatcmp');
		
		$recipient_caption = $i18n['recipient'];
		$group_recipient_caption = $i18n['group_recipient'];
		
		$btn = new buttongroupcontrol($i18n['save'],'',$i18n['cancel']);
		
		$object->group_recipient = array();
		
		if ($object == null || !isset($object->recipient)) {
			$object->subject = '';
			$object->body = '';
			$object->recipient = array();
			
			if (!count($users) && !count($groups)) $btn->disabled = true;
		} else {
			if (!defined('SYS_USERS')) require_once(BASE.'subsystems/users.php');
			$u = pathos_users_getUserById($object->recipient);
			$form->register(null,'',new htmlcontrol(sprintf($i18n['replyto'],$u->firstname . ' ' . $u->lastname . ' (' . $u->username . ')')));
			$form->meta('replyto',$object->recipient);
			$object->recipient = array();
			
			unset($users[$u->id]);
			
			$recipient_caption = $i18n['copyto'];
			$group_recipient_caption = $i18n['group_copyto'];
		}
		
		if (count($users)) {
			$form->register('recipients',$recipient_caption,new listbuildercontrol($object->recipient,$users));
		}
		if (count($groups)) {
			$form->register('group_recipients',$group_recipient_caption,new listbuildercontrol($object->group_recipient,$groups));
		}
		
		if (!count($groups) && !count($users)) {
			$form->register(null,'',new htmlcontrol('<div class="error">'.$i18n['nocontacts'].'</div>'));
		}
		
		$form->register('subject',$i18n['subject'],new textcontrol($object->subject));
		$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
		$form->register('submit','',$btn);
		
		return $form;
	}
	
	function update($values,$object) {
		$object->subject = $values['subject'];
		$object->body = $values['body'];
		return $object;
	}
}

?>