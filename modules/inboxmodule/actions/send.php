<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');

if ($user) {
	
	$i18n = exponent_lang_loadFile('modules/inboxmodule/actions/send.php');
	
	if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	exponent_forms_initialize();
	
	// Process recipients first, so that we can save the built list in the last_POST var in case we need to return
	$recipients = listbuildercontrol::parseData($_POST,'recipients');
	
	$banned = array();
	foreach ($db->selectObjects('inbox_contactbanned','owner='.$user->id.' OR user_id='.$user->id) as $b) {
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
		$gr = listbuildercontrol::parseData($_POST,'group_recipients');
		foreach ($gr as $ginfo) {
			$toks = explode('_',$ginfo);
			$gid = $toks[1];
			if ($toks[0] == 'group') {
				foreach (exponent_users_getUsersInGroup(exponent_users_getGroupById($gid)) as $u) {
					if (!in_array($u->id,$banned)) {
						$recipients[] = $u->id;
					}
				}
			} else {
				$list = $db->selectObject('inbox_contactlist','id='.$gid);
				if ($list->owner == $user->id) {
					foreach ($db->selectObjects('inbox_contactlist_member','list_id='.$list->id) as $m) {
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
		$post['_formError'] = $i18n['err_no_subject'];
		$post['recipients'] = $recipients;
		$post['group_recipients'] = $gr;
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	
	if (count($recipients) == 0) {
		$post = $_POST;
		$post['_formError'] = $i18n['err_no_recipients'];
		$post['recipients'] = $recipients;
		$post['group_recipients'] = $gr;
		exponent_sessions_set('last_POST',$post);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
	$message->from_id = $user->id;
	$message->from_name = $user->firstname . ' ' . $user->lastname . ' (' . $user->username . ')';
	$message->date_sent = time();
	$message->unread = 1;
	
	// Set up the reply-to header, in case we need to send an email message
	$reply_to = trim($user->firstname . ' ' . $user->lastname);
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
	$failed->from_name = $i18n['failed_from'];
	$failed->date_sent = time();
	$failed->unread = 1;
	$failed->subject = $i18n['failed_title'];
	$failed->recipient = $user->id;
	
	// Init SMTP subsystem
	if (!defined('SYS_SMTP')) include_once(BASE.'subsystems/smtp.php');
	$emails = array();
	foreach ($recipients as $id) {
		if ($id != '') {
			$u = exponent_users_getUserByID($id);
			if (!$u) {
				$failed->body = sprintf($i18n['failed_404'],$message->body);
				$db->insertObject($failed,'privatemessage');
			} else {
				$ban = $db->selectObject('inbox_contactbanned','user_id='.$user->id.' AND owner='.$id);
				if (!$ban) $ban = $db->selectObject('inbox_contactbanned','user_id='.$id.' AND owner='.$user->id);
				if ($ban) {
					$failed->body = sprintf($i18n['failed_403'],$message->body);
					$db->insertObject($failed,'privatemessage');
				} else {
					$message->recipient = $id;
					$inbox_userconfig = $db->selectObject('inbox_userconfig','id='.$id);
					
					if (!$inbox_userconfig) {
						$inbox_userconfig->forward = 1;
					}
					if ($inbox_userconfig->forward == 1 && $u->email != '') {
						// Forward the message to their email account
						$emails[] = $u->email;
					}
					$db->insertObject($message,'privatemessage');
				}
			}
		}
	}
	
	if (count($emails)) {
		$body = $message->body;	
		$headers = array(
			'MIME-Version'=>'1.0',
			'Content-type'=>'text/html; charset=iso-8859-1',
			'Reply-to'=>$reply_to,
			'From'=>$reply_to,
		);
		if (exponent_smtp_mail($emails,'',$message->subject,'<html><body>'.$message->body.'</body></html>',$headers) == false) {
			echo $i18n['err_smtp'];
		}
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>