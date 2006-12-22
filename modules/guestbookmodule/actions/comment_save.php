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
$comment = null;
if (isset($_POST['parent_id'])) {
	$post = $db->selectObject('guestbook_post','id='.intval($_POST['parent_id']));
} else if (isset($_POST['id'])) {
	$comment = $db->selectObject('guestbook_comment','id='.intval($_POST['id']));
	$post = $db->selectObject('guestbook_post','id='.$comment->parent_id);
}
if ($post && $post->is_draft == 0) {
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);

	if ((!$comment && exponent_permissions_check('comment',$loc)) ||
		(!$comment && exponent_permissions_check('comment',$iloc)) ||
		($comment && exponent_permissions_check('edit_comments',$loc)) ||
		($comment && exponent_permissions_check('edit_comments',$iloc))
	) {
		$comment = null;
		if (isset($_POST['id'])) {
			$comment = $db->selectObject('guestbook_comment','id='.intval($_POST['id']));
		}
		
		$comment = guestbook_comment::update($_POST,$comment);

		if (isset($comment->id)) {
			$comment->editor = $user->id;
			$comment->edited = time();
			$db->updateObject($comment,'guestbook_comment');
		} else {
			$comment->posted = time();
			$comment->poster = $user->id;
			$comment->parent_id = intval($_POST['parent_id']);
			$db->insertObject($comment,'guestbook_comment');
		}
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}
?>