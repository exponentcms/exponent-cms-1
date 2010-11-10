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

$post = null;
$iloc = null;
$newpost = 0;

if (isset($_POST['id'])) {
	$post = $db->selectObject('weblog_post','id='.intval($_POST['id']));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
}
else
{
    $newpost = 1;
}

if (($post != null && exponent_permissions_check('edit',$loc)) ||
	($post == null && exponent_permissions_check('post',$loc)) ||
	($post != null && exponent_permissions_check('edit',$iloc))
) {
	// Need to be able to update the posted date if switching from draft to non-draft.
	$was_draft = 0;
	if ($post) $was_draft = $post->is_draft;

	$post = weblog_post::update($_POST,$post);
	$post->location_data = serialize($loc);
	//Get and add the tags selected by the user
	$post->tags = serialize(listbuildercontrol::parseData($_POST,'tags'));

	if (isset($post->id)) {
		if ($was_draft && $post->is_draft == 0) {
			// No longer a draft.
			$post->posted = time();
		} else {
			$post->editor = $user->id;
			$post->edited = time();
		}
		$db->updateObject($post,'weblog_post');
	} else {

        if ($newpost < 1) {
            if ($db->countObjects('weblog_post',"internal_name='".$post->internal_name."'")) {
	    		$_POST['_formError'] = 'That Internal Name is already in use.  Please choose another.';
		    	unset($_POST['internal_name']);
			    exponent_sessions_set('last_POST',$_POST);
    			header('Location: '.$_SERVER['HTTP_REFERER']);
	    		exit('');
		    }
		}
		$post->poster = $user->id;
		$post->posted = time();
				
		$id = $db->insertObject($post,'weblog_post');
		$post->id = $id;
		
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);

		// New, so asign full perms.
		exponent_permissions_grant($user,'edit',$iloc);
		exponent_permissions_grant($user,'delete',$iloc);
		exponent_permissions_grant($user,'comment',$iloc);
		exponent_permissions_grant($user,'edit_comments',$iloc);
		exponent_permissions_grant($user,'delete_comments',$iloc);
		exponent_permissions_grant($user,'view_private',$iloc);
		exponent_permissions_triggerSingleRefresh($user);
	}
		
	if ($post->is_draft == 0 && ($was_draft || $newpost)) {
		$blogname = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");	
		$toneloc = exponent_core_makeLocation($loc->mod,$loc->src);
		//$config = $db->selectObject("bbmodule_config","location_data='".serialize($loc)."'");
		$config = $db->selectObject("weblogmodule_config","location_data='".serialize($toneloc)."'");
		//eDebug($config);
//      exit();

		if (!isset($config->id)) {
			$config->email_title_post = "Weblog : New Post Added";
			$config->email_from_post = "Weblog Manager";
			$config->email_address_post = "weblog@".HOSTNAME;
			$config->email_reply_post = "weblog@".HOSTNAME;
			$config->email_showpost_post = 0;
			$config->email_signature = "--\nThanks, Webmaster";
		}

		if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
		//$title = $config->email_title_thread;
		$title = "[".$config->email_title_post." - $blogname] ".$post->title;
		$from_addr = $config->email_address_post;
		$headers = array(
			"From"=>$from = $config->email_from_post,
			"Reply-to"=>$reply = $config->email_reply_post
			);

		//  set up the html message
		$template = new template("weblogmodule","_email_newpost_html",$loc);
		$template->assign("showpost",$config->email_showpost_post);
		$template->assign('viewlink',URL_FULL.'index.php?module=weblogmodule&action=view&id='.$id.'&src='.$loc->src);
		$template->assign("signature",$config->email_signature);
		$template->assign("post",$post);
		$template->assign("poster",exponent_users_getUserById($post->poster));
		$template->assign("blogname",$blogname);
		$htmlmsg = $template->render();

		// now the same thing for the text message	
		$template = new template("weblogmodule","_email_newpost",$loc);
		$template->assign("showpost",$config->email_showpost_post);
		$template->assign('viewlink',URL_FULL.'index.php?module=weblogmodule&action=view&id='.$id.'&src='.$loc->src);
		$template->assign("signature",$config->email_signature);
		$post->body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\n",$post->body)));
		$template->assign("post",$post);
		$template->assign("poster",exponent_users_getUserById($post->poster));
		$template->assign("blogname",$blogname);
		$msg = $template->render();

		
		// Saved.  do notifs
		$notifs = $db->selectObjects("weblog_monitor","weblog_id=".$config->id);
		/*
		$emails = array();
		if (!defined("SYS_USERS")) require_once(BASE."subsystems/users.php");
		foreach ($notifs as $n) {
			if ($n->user_id != $user->id) {
				$u = exponent_users_getUserById($n->user_id);
				if ($u->email != "") $emails[] = $u->email;
			}
		}

		if (!defined("SYS_SMTP")) require(BASE."subsystems/smtp.php");
		exponent_smtp_mail($emails,$from_addr,$title,$msg,$headers);
		*/
		$emails = array();
		foreach ($notifs as $n) {
//			if ($n->user_id != $user->id) {
				$u = exponent_users_getUserById($n->user_id);
				if ($u->email != "" && !in_array($u->email,$emails)) $emails[] = $u->email;
//			}
		}
	
		require_once(BASE."subsystems/mail.php");
		$mail = new exponentMail();
		$mail->subject($title);
		$mail->addText($msg);
		$mail->addHTML($htmlmsg);
		$mail->addFrom($config->email_address_post,$config->email_from_post);
//		$mail->addTo($emails);
//		$mail->send();
		foreach($emails as $recip) {	// to keep other recepients hidden
			try {
				$mail->addTo($recip);
				$mail->send();
			} catch (Error $e) {
			}
			$mail->flushRecipients();
		}
	}

	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
