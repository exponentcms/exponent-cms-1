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

if (!defined('EXPONENT')) exit('');

$resource = $db->selectObject('resourceitem','id='.intval($_POST['id']));
if ($resource) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	if (exponent_permissions_check('edit',$loc) || exponent_permissions_check('edit',$iloc)) {
		$directory = 'files/resourcesmodule/'.$loc->src;
		$file = file::update('file',$directory,null,time().'_'.$_FILES['file']['name']);
		if (is_object($file)) {
			$id = $db->insertObject($file,'file');
			$resource->file_id = $id;
			
			$resource->editor = $user->id;
			$resource->edited = time();
			
			if (isset($_POST['checkin']) && ($user->is_acting_admin == 1 || $user->id == $resource->flock_owner)) {
				$resource->flock_owner = 0;
			}
			
			if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
			exponent_workflow_post($resource,'resourceitem',$loc);
			unset($_SESSION['resource_cache']);
		} else {
			// If file::update() returns a non-object, it should be a string.  That string is the error message.
			$post = $_POST;
			$post['_formError'] = $file;
			exponent_sessions_set('last_POST',$post);
			unset($_SESSION['resource_cache']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}	
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
