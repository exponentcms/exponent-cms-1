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

if (!defined("PATHOS")) exit("");

if ($user) {
	
	pathos_lang_loadDictionary('modules','inboxmodule');
	
	if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	// Process recipients first, so that we can save the built list in the last_POST var in case we need to return
	$recipients = listbuildercontrol::parseData($_POST,"recipients");
	
	
	$banned = array();
	foreach ($db->selectObjects("inbox_contactbanned","owner=".$user->id." OR user_id=".$user->id) as $b) {
		if ($b->owner == $user->id) {
			$banned[$b->user_id] = $b->user_id;
		} else {
			$banned[$b->user_id] = $b->owner;
		}
	}
	
	if (isset($_POST['replyto'])) {
		$recipients[] = $_POST['replyto'];
	}
	
	$gr = array();
	if (isset($_POST['group_recipients'])) {
		$gr = listbuildercontrol::parseData($_POST,"group_recipients");
		foreach ($gr as $ginfo) {
			$toks = explode("_",$ginfo);
			$gid = $toks[1];
			if ($toks[0] == "group") {
				foreach (pathos_users_getUsersInGroup(pathos_users_getGroupById($gid)) as $u) {
					if (!in_array($u->id,$banned)) {
						$recipients[] = $u->id;
					}
				}
			} else {
				$list = $db->selectObject("inbox_contactlist","id=".$gid);
				if ($list->owner == $user->id) {
					foreach ($db->selectObjects("inbox_contactlist_member","list_id=".$list->id) as $m) {
						if (!in_array($u->id,$banned)) {
							$recipients[] = $m->user_id;
						}
					}
				}
			}
		}
	}
	
	// remove duplicates
	$recipients = array_flip(array_flip($recipients));
	
	$message = privatemessage::update($_POST,null);
	if (trim($message->subject) == '') {
		$post = $_POST;
		unset($post['subject']);
		$post['_formError'] = "You must enter a subject.";
		$post['recipients'] = $recipients;
		$post['group_recipients'] = $gr;
		pathos_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	
	if (count($recipients) == 0) {
		$post = $_POST;
		$post['_formError'] = "You must specify a user or group to send this message to.";
		$post['recipients'] = $recipients;
		$post['group_recipients'] = $gr;
		pathos_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	$message->from_id = $user->id;
	$message->from_name = $user->firstname . " " . $user->lastname . " (" . $user->username . ")";
	$message->date_sent = time();
	$message->unread = 1;
	
	// Set up the reply-to header, in case we need to send an email message
	$reply_to = trim($user->firstname . " " . $user->lastname);
	if ($reply_to == '') {
		$reply_to = $username;
	}
	
	if (trim($user->email) != '') {
		$reply_to .= ' <'.$user->email.'>';
	} else {
		$reply_to .= ' <noreply@'.HOSTNAME.'>';
	}
	
	$failed = null;
	$failed->from_id = 0;
	$failed->from_name = TR_INBOXMODULE_FAILED_FROM;
	$failed->date_sent = time();
	$failed->unread = 1;
	$failed->subject = TR_INBOXMODULE_FAILED_TITLE;
	$failed->recipient = $user->id;
	
	// Init SMTP subsystem
	if (!defined("SYS_SMTP")) require_once(BASE."subsystems/smtp.php");
	$emails = array();
	foreach ($recipients as $id) {
		if ($id != "") {
			$u = pathos_users_getUserByID($id);
			if (!$u) {
				$failed->body = sprintf(TR_INBOXMODULE_FAILED_404MSG,$message->body);
				$db->insertObject($failed,"privatemessage");
			} else {
				$ban = $db->selectObject("inbox_contactbanned","user_id=".$user->id." AND owner=".$id);
				if (!$ban) $ban = $db->selectObject("inbox_contactbanned","user_id=".$id." AND owner=".$user->id);
				if ($ban) {
					$failed->body = sprintf(INBOXMODULE_FAILED_MSG,$message->body);
					$db->insertObject($failed,"privatemessage");
				} else {
					$message->recipient = $id;
					$inbox_userconfig = $db->selectObject("inbox_userconfig","id=".$id);
					
					if (!$inbox_userconfig) {
						$inbox_userconfig->forward = 1;
					}
					if ($inbox_userconfig->forward == 1 && $u->email != "") {
						// Forward the message to their email account
						$emails[] = $u->email;
					}
					$db->insertObject($message,"privatemessage");
				}
			}
		}
	}
	
	if (count($emails)) {
		$body = $message->body;	
		$headers = array(
			"MIME-Version"=>"1.0",
			"Content-type"=>"text/html; charset=iso-8859-1",
			'Reply-to'=>$reply_to,
			'From'=>$reply_to,
		);
		if (pathos_smtp_mail($emails,"",$message->subject,'<html><body>'.$message->body.'</body></html>',$headers) == false) {
			echo TR_INBOXMODULE_ERR_SMTP;
		}
	}
	pathos_flow_redirect();
}

?>