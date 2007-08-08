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

//check to see if the user is logged in.  If not make sure they entered an email addy
//eDebug($user);eDebug($_POST);exit();
if (!isset($user) && (!isset($_POST['email']) || $_POST['email'] == '') ){
  echo '<br /><span class="error">Users who are not logged in must supply an email address.  <br /><br />Please go back and enter an email address.'; exit();
}

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
		
	exponent_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>
