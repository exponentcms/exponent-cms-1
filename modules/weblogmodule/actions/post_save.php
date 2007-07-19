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
		$post->id = $db->insertObject($post,'weblog_post');

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

	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
