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

if (!defined('PATHOS')) exit('');

$post = null;
$comment = null;
if (isset($_POST['parent_id'])) {
	$post = $db->selectObject('weblog_post','id='.$_POST['parent_id']);
} else if (isset($_POST['id'])) {
	$comment = $db->selectObject('weblog_comment','id='.$_POST['id']);
	$post = $db->selectObject('weblog_post','id='.$comment->parent_id);
}
if ($post) {
	$loc = unserialize($post->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);

	if ((!$comment && pathos_permissions_check('comment',$loc)) ||
		(!$comment && pathos_permissions_check('comment',$iloc)) ||
		($comment && pathos_permissions_check('edit_comments',$loc)) ||
		($comment && pathos_permissions_check('edit_comments',$iloc))
	) {
		$comment = null;
		if (isset($_POST['id'])) {
			$comment = $db->selectObject('weblog_comment','id='.$_POST['id']);
		}
		
		$comment = weblog_comment::update($_POST,$comment);

		if (isset($comment->id)) {
			$comment->editor = $user->id;
			$comment->edited = time();
			$db->updateObject($comment,'weblog_comment');
		} else {
			$comment->posted = time();
			$comment->poster = $user->id;
			$comment->parent_id = $_POST['parent_id'];
			$db->insertObject($comment,'weblog_comment');
		}
		
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>