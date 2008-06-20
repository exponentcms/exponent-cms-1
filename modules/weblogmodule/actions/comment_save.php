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



require_once(BASE.'subsystems/mail.php');

$i18n = exponent_lang_loadFile('modules/weblogmodule/actions/comment_save.php');

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");

//check to see if the user is logged in.  If not make sure they entered an email addy & captcha
if (!empty($config->require_login) && !exponent_users_isLoggedIn()) validator::failAndReturnToForm('You must be logged in to submit comments');
$validate = array();
if (!empty($config->use_captcha)) $validate['captcha'] = 'captcha_string';
if (!exponent_users_isLoggedIn()) $validate['valid_email'] = 'email';
if (count($validate) > 0) validator::validate($validate, $_POST);

$post = null;
$comment = null;
if (isset($_POST['parent_id'])) {
	$post = $db->selectObject('weblog_post','id='.intval($_POST['parent_id']));
} else if (isset($_POST['id'])) {
	$comment = $db->selectObject('weblog_comment','id='.intval($_POST['id']));
	$post = $db->selectObject('weblog_post','id='.$comment->parent_id);
}

if ($post && $post->is_draft == 0) {
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);

	$comment = null;
	if (isset($_POST['id'])) {
		$comment = $db->selectObject('weblog_comment','id='.intval($_POST['id']));
	}
		
	$comment = weblog_comment::update($_POST,$comment);

	if (isset($comment->id)) {
		$comment->editor = $user->id;
		$comment->edited = time();
		$db->updateObject($comment,'weblog_comment');
	} else {
		$comment->posted = time();
      
      		if (isset($user) && $user->id != 0) {
			$comment->poster = $user->id;
        		$comment->name = $user->username;
	      	} elseif (isset($_POST['name'])) {
        		$comment->name = $_POST['name'];
      		} else {
        		$comment->name = 'Anonymous';
      		}

		$comment->parent_id = intval($_POST['parent_id']);
		$db->insertObject($comment,'weblog_comment');
	}
		
	// Send email to addresses corresponding to users listed in comments_notify
	// 1.23.08 rkq

	$users = unserialize($config->comments_notify);
	$userlist = array();
	$j=0;
	$emailaddresses = array();
	foreach($users as $i)
	{
		if($user->email!="")
		{
			$emailaddresses[$j] = $user->email;
			++$j;
		}
	}
	
	$textmessage = $comment->name."(".$comment->email.") has replied to your blog post, '".$post->title."'. He or she wrote: '".$comment->body."'";
	$htmlmessage = "<a href=\"mailto:".$comment->email."\">".$comment->name."</a> has replied to your blog post, '".$post->title."'. He or she wrote: '".$comment->body."'";
	$params = array("text_message"=>$textmessage,
			"html_message"=>$htmlmessage,
			"subject"=>"Blog Post Comment",
			"to"=>"email",
			"from"=>SMTP_FROMADDRESS,
			);
	$mail = new exponentMail($params);	
	$i=0;		
	while($i<$j)
	{
		$mail->to = $emailaddresses[$i];
		$params["to"] = $emailaddresses[$i];
		try {	
		    $mail->quickSend($params);
		} catch (Exception $e) {
	    }
		$i++;
	}
	exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}




?>
