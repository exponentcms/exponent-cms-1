<?php
#############################################################
# GUESTBOOKMODULE
#############################################################
# Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
#
# version 0.5:	Developement-Version
# version 1.0:	1st release for Exponent v0.93.3
# version 1.2:	Captcha added
# version 2.0:	now compatible to Exponent v0.93.5
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################


if (!defined('EXPONENT')) exit('');

$post = null;
$iloc = null;
$newpost = 0;
// check the capcha
$capcha_real = exponent_sessions_get('capcha_string');
if (SITE_USE_CAPTCHA && strtoupper($_POST['captcha_string']) != $capcha_real) {
	$post = $_POST;
	unset($post['captcha_string']);
	$i18n = exponent_lang_loadFile('modules/guestbookmodule/views/Default.php');
	$post['_formError'] = $i18n['bad_captcha'];
	exponent_sessions_set('last_POST',$post);
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	if (isset($_POST['id'])) {
		$post = $db->selectObject('guestbook_post','id='.intval($_POST['id']));
		$loc = unserialize($post->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
	} else {
		$newpost = 1;
	}


	$post = guestbook_post::update($_POST,$post);
	$post->location_data = serialize($loc);

	if (isset($post->id)) {
		#$post->editor = $user->id;
		#$post->edited = time();
		$db->updateObject($post,'guestbook_post');
	} else {
		#$post->poster = $user->id;
		$post->posted = time();
		$post->id = $db->insertObject($post,'guestbook_post');
		
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
	#print_r($post);
		
		// New, so asign full perms.
		exponent_permissions_grant($user,'edit',$iloc);
		exponent_permissions_grant($user,'delete',$iloc);
		exponent_permissions_grant($user,'comment',$iloc);
		exponent_permissions_grant($user,'edit_comments',$iloc);
		exponent_permissions_grant($user,'delete_comments',$iloc);
		#exponent_permissions_triggerSingleRefresh($user);
	}
	exponent_sessions_unset('capcha_string');
	exponent_flow_redirect();
}
?>