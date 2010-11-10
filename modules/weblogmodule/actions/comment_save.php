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
	if ($config->approve_comments && ($user->id == $post->poster || $user->id == $post->editor)) {
		$comment->approved = 1;  // only auto-approve comments by original poster or editor
	} else {
		$comment->approved = 0;  // dis-approve all other comments, including those approved earlier, but now edited
	}

	if (isset($comment->id)) {
		$comment->editor = $user->id;
		$comment->edited = time();
		$db->updateObject($comment,'weblog_comment');
		$typecomment = "edited their reply";
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
		$typecomment = "replied";
	}
		
	// Send email to addresses corresponding to users listed in comments_notify
	// 1.23.08 rkq

	$userlist = unserialize($config->comments_notify);
	if (!empty($userlist)) {
		try {
			$j=0;
			$emailaddresses = array();
			foreach($userlist as $userid)
			{
				$notify = exponent_users_getUserById($userid);
				if($notify->email!="")  // valid e-mail address?
//				if(($notify->email!="") && ($userid != $user->id))  // valid e-mail address?
				{
					$emailaddresses[$j] = $notify->email;
					++$j;
				}
			}
			$textmessage = $comment->name."(".$comment->email.") has ".$typecomment." to your blog post, '".$post->title."'. They wrote:\r\n\r\n '".$comment->body."'";
			$htmlmessage = "<a href=\"mailto:".$comment->email."\">".$comment->name."</a> has ".$typecomment." to your blog post, '".$post->title."'. They wrote:<br /><br /> <blockquote>'".$comment->body."'</blockquote>";

			if ($config->approve_comments && !$comment->approved) {
				$textmessage .= "\r\n\r\n This comment requires approval before it can be viewed!\r\n".URL_FULL."index.php?module=weblogmodule&action=view&id=".$post->id."&src=".$loc->src."#comments";
				$htmlmessage .= "<br /><br /><b>This comment requires <a href=".URL_FULL."index.php?module=weblogmodule&action=view&id=".$post->id."&src=".$loc->src."#comments>approval</a> before it can be viewed!</b>";
			}

			if ($config->email_showpost_post) {
				$textmessage .= "\r\n--------\r\n".chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\n",$comment->body)));
				$htmlmessage .= "<br /><hr><br />".$comment->body;
			}
			
			$params = array(
				"text_message"=>$textmessage,
				"html_message"=>$htmlmessage,
				"subject"=>"Blog Post Comment - ".$post->title,
				"to"=>"email",
				"from"=>SMTP_FROMADDRESS,
				);
			$mail = new exponentMail($params);	
			$i=0;		
			while($i<$j)
			{
				$mail->to = $emailaddresses[$i];
				$params["to"] = $emailaddresses[$i];	
				$mail->quickSend($params);
				$i++;
			}
		}catch (Exception $e){
			$message = exponent_lang_getText("Your comment was saved, however an error occured while trying to send email: \n") . $e->getMessage() . "\n";
			flash('error', $message); 
		}
	}
	if(!empty($comment) && (empty($comment->approved) || $comment->approved == 0)) {
		flash('message', "Your comment was saved, however is must first be approved");
	}	
	exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>
