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
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		
		$users = array();
		global $db, $user;
		foreach ($db->selectObjects("inbox_contact","owner=".$user->id) as $c) {
			$users[$c->user_id] = $c->display_name;
		}
		uasort($users,"strnatcmp");
		
		$groups = array();
		foreach ($db->selectObjects("inbox_contactlist","owner=".$user->id) as $g) {
			$groups[$g->id] = $g->name;
		}
		uasort($groups,"strnatcmp");
		
		$recipient_caption = "Recipient";
		$btn = new buttongroupcontrol("Save","","Cancel");
		
		$object->group_recipient = array();
		
		if ($object == null || !isset($object->recipient)) {
			$object->subject = "";
			$object->body = "";
			$object->recipient = array();
			
			if (!count($users) && !count($groups)) $btn->disabled = true;
		} else {
			if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
			$u = pathos_users_getUserById($object->recipient);
			$form->register(uniqid(""),"",new htmlcontrol("Reply to '".$u->firstname . " " . $u->lastname . " (" . $u->username . ")'"));
			$form->meta("replyto",$object->recipient);
			$object->recipient = array();
			
			unset($users[$u->id]);
			
			$recipient_caption = "Send copy to";
		}
		
		//$form->register("recipient","Recipient",new textcontrol($object->recipient));
		if (count($users)) {
			$form->register("recipients",$recipient_caption,new listbuildercontrol($object->recipient,$users));
			$form->register("group_recipients",$recipient_caption,new listbuildercontrol($object->group_recipient,$groups));
		}
		else $form->register(uniqid(""),"",new htmlcontrol("<i>You have no contacts in your personal contact list.</i>"));
		
		$form->register("subject","Subject",new textcontrol($object->subject));
		$form->register("body","Message", new htmleditorcontrol($object->body));
		$form->register("submit","",$btn);
		
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