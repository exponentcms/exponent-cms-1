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

if (!defined('PATHOS')) exit('');

$post = null;
$iloc = null;
if (isset($_POST['id'])) {
	$post = $db->selectObject('weblog_post','id='.$_POST['id']);
	$loc = unserialize($post->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);
}

if (($post != null && pathos_permissions_check('edit',$loc)) || 
	($post == null && pathos_permissions_check('post',$loc)) ||
	($post != null && pathos_permissions_check('edit',$iloc))
) {
	$post = weblog_post::update($_POST,$post);
	$post->location_data = serialize($loc);
	
	if (isset($post->id)) {
		$post->editor = $user->id;
		$post->edited = time();
		$db->updateObject($post,'weblog_post');
	} else {
		$post->poster = $user->id;
		$post->posted = time();
		$post->id = $db->insertObject($post,'weblog_post');
		
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$post->id);
		// New, so asign full perms.
		pathos_permissions_grant($user,'edit',$iloc);
		pathos_permissions_grant($user,'delete',$iloc);
		pathos_permissions_grant($user,'comment',$iloc);
		pathos_permissions_grant($user,'edit_comments',$iloc);
		pathos_permissions_grant($user,'delete_comments',$iloc);
		pathos_permissions_grant($user,'view_private',$iloc);
		pathos_permissions_triggerSingleRefresh($user);
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>