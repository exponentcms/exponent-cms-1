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

if ($user) {
	$message = privatemessage::update($_POST,null);
	$message->from_id = $user->id;
	$message->from_name = $user->firstname . " " . $user->lastname . " (" . $user->username . ")";
	$message->date_sent = time();
	$message->unread = 1;
	
	$failed = null;
	$failed->from_id = 0;
	$failed->from_name = "System Message";
	$failed->date_sent = time();
	$failed->unread = 1;
	$failed->subject = "Failed Delivery";
	$failed->recipient = $user->id;
	
	if (!defined("SYS_USERS")) include_once(BASE."subsystems/users.php");
	
	$recipients = array();
	if (isset($_POST['recipients'])) {
		$recipients = explode(":",$_POST['recipients']);
	}
	if (isset($_POST['replyto'])) {
		$recipients[] = $_POST['replyto'];
	}
	
	if (isset($_POST['group_recipients'])) {
		$gr = explode(":",$_POST['group_recipients']);
		foreach ($gr as $gid) {
			$list = $db->selectObject("inbox_contactlist","id=".$gid);
			if ($list->owner == $user->id) {
				foreach ($db->selectObjects("inbox_contactlist_member","list_id=".$list->id) as $m) {
					$recipients[] = $m->user_id;
				}
			}
		}
	}
	
	foreach ($recipients as $id) {
		if ($id != "") {
			$u = pathos_users_getUserByID($id);
			if (!$u) {
				$failed->body = "The following message was not delivered because the recipient was not found in the system.";
				$failed->body .= "<hr size='1' /><hr size='1' />" . $message->body;
				$db->insertObject($failed,"privatemessage");
			} else {
				$ban = $db->selectObject("inbox_contactbanned","user_id=".$user->id." AND owner=".$id);
				if ($ban) {
					$failed->body = "The following message was not delivered.";
					$failed->body .= "<hr size='1' /><hr size='1' />" . $message->body;
					$db->insertObject($failed,"privatemessage");
				} else {
					$message->recipient = $id;
					$db->insertObject($message,"privatemessage");
					
				}
			}
		}
	}
	pathos_flow_redirect();
}

?>