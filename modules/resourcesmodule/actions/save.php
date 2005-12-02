<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

$resource = null;
$iloc = null;
if (isset($_POST['id'])) {
	$resource = $db->selectObject('resourceitem','id='.$_POST['id']);
	if ($resource) {
		$loc = unserialize($resource->location_data);
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$resource->id);
	}
}

if (($resource == null && pathos_permissions_check('post',$loc)) ||
	($resource != null && pathos_permissions_check('edit',$loc)) ||
	($iloc != null && pathos_permissions_check('edit',$iloc))
) {
	$resource = resourceitem::update($_POST,$resource);
	$resource->location_data = serialize($loc);
	
	if (!isset($resource->id)) {
		$resource->rank = $_POST['rank'];
		$db->increment('resourceitem','rank',1,"location_data='".serialize($loc)."' AND rank >= ".$resource->rank);
	}
	
	if (!isset($resource->file_id)) {
		$directory = 'files/resourcesmodule/'.$loc->src;
		
		$file = file::update('file',$directory,null,time().'_'.$_FILES['file']['name']);
		if (is_object($file)) {
			$resource->file_id = $db->insertObject($file,'file');
			$id = $db->insertObject($resource,'resourceitem');
			// Assign new perms on loc
			$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$id);
			pathos_permissions_grant($user,'edit',$iloc);
			pathos_permissions_grant($user,'delete',$iloc);
			pathos_permissions_grant($user,'administrate',$iloc);
			pathos_permissions_triggerSingleRefresh($user);
			
			if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
			$resource->id = $id;
			$resource->poster = $user->id;
			$resource->posted = time();
			pathos_workflow_post($resource,'resourceitem',$loc);
		} else {
			// If file::update() returns a non-object, it should be a string.  That string is the error message.
			$post = $_POST;
			$post['_formError'] = $file;
			pathos_sessions_set('last_POST',$post);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else {
		$resource->editor = $user->id;
		$resource->edited = time();
		$db->updateObject($resource,'resourceitem');
		pathos_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>
