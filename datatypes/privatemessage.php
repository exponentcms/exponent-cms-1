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

class privatemessage {
	function form($object) {
		pathos_lang_loadDictionary('modules','inboxmodule');
		pathos_lang_loadDictionary('standard','core');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		
		$users = array();
		$groups = array();
		global $db, $user;
		
		if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
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
			$groups['list_'.$g->id] = $g->name . ' ' . TR_INBOXMODULE_PERSONALLIST;
		}
		if (pathos_permissions_check('contact_all',pathos_core_makeLocation('inboxmodule'))) {
			foreach (pathos_users_getAllGroups(1,0) as $g) {
				$groups['group_'.$g->id] = $g->name . ' ' . TR_INBOXMODULE_SYSGROUP;
			}
		} else {
			foreach (pathos_users_getGroupsForUser($user,1,0) as $g) {
				$groups['group_'.$g->id] = $g->name . ' ' . TR_INBOXMODULE_SYSGROUP;
			}
		}
		uasort($groups,'strnatcmp');
		
		$recipient_caption = TR_INBOXMODULE_RECIPIENT;
		$group_recipient_caption = TR_INBOXMODULE_GROUPRECIPIENT;
		
		$btn = new buttongroupcontrol(TR_INBOXMODULE_SEND,'',TR_CORE_CANCEL);
		
		$object->group_recipient = array();
		
		if ($object == null || !isset($object->recipient)) {
			$object->subject = '';
			$object->body = '';
			$object->recipient = array();
			
			if (!count($users) && !count($groups)) $btn->disabled = true;
		} else {
			if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
			$u = pathos_users_getUserById($object->recipient);
			$form->register(null,'',new htmlcontrol(sprintf(TR_INBOXMODULE_REPLYTO,$u->firstname . ' ' . $u->lastname . ' (' . $u->username . ')')));
			$form->meta('replyto',$object->recipient);
			$object->recipient = array();
			
			unset($users[$u->id]);
			
			$recipient_caption = TR_INBOXMODULE_COPYTO;
			$group_recipient_caption = TR_INBOXMODULE_GROUPCOPYTO;
		}
		
		if (count($users)) {
			$form->register('recipients',$recipient_caption,new listbuildercontrol($object->recipient,$users));
		}
		if (count($groups)) {
			$form->register('group_recipients',$group_recipient_caption,new listbuildercontrol($object->group_recipient,$groups));
		}
		
		if (!count($groups) && !count($users)) {
			$form->register(null,'',new htmlcontrol('<div class="error">'.TR_INBOXMODULE_NOCONTACTSWARNING.'</div>'));
		}
		
		$form->register('subject',TR_INBOXMODULE_SUBJECT,new textcontrol($object->subject));
		$form->register('body',TR_INBOXMODULE_MESSAGE, new htmleditorcontrol($object->body));
		$form->register('submit','',$btn);
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->subject = $values['subject'];
		$object->body = $values['body'];
		return $object;
	}
}

?>