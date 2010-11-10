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

if ($_POST['fileexists']) {
	$filename = $_SERVER['DOCUMENT_ROOT'].$_POST['fileexists'];
	include(BASE.'external/mimetype.php');
	$mimetype = new mimetype();
	$mimetype = $mimetype->getType($filename);
	$_FILES['file']['name'] = end(explode("/", $_POST['fileexists']));
	$_FILES['file']['type'] = $mimetype;
	$_FILES['file']['tmp_name'] = $filename;
	$_FILES['file']['error'] = 0;	
	$_FILES['file']['size'] = filesize($filename);
}

$resource = $db->selectObject('resourceitem','id='.intval($_POST['id']));
if ($resource) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	if (exponent_permissions_check('edit',$loc) || exponent_permissions_check('edit',$iloc)) {
		$directory = 'files/resourcesmodule/'.$loc->src;
		
if ($_POST['fileexists']) {

	$name = 'file';
	$dest = $directory;
//	$object = null;
	$destname = time().'_'.$_FILES['file']['name'];
	$force=false;
	
		$i18n = exponent_lang_loadFile('datatypes/file.php');
		
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
		
		// Get the filename, if it was passed in the update() call.  Otherwise, fallback
		if ($destname == null) {
			$file->filename = $_FILES[$name]['name'];
		} else {
			$file->filename = $destname;
		}
		// General error message.  This will be made more explicit later on.
		$err = sprintf($i18n['cant_upload'],$file->filename) .'<br />';
		
		switch($_FILES[$name]['error']) {
			case UPLOAD_ERR_OK:
				// Everything looks good.  Continue with the update.
				break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				// This is a tricky one to catch.  If the file is too large for POST, then the script won't even run.
				// But if its between post_max_size and upload_file_max_size, we will get here.
				$file =  $err.$i18n['file_too_large'];
			case UPLOAD_ERR_PARTIAL:
				$file =  $err.$i18n['partial_file'];
			case UPLOAD_ERR_NO_FILE:
				$file =  $err.$i18n['no_file_uploaded'];
			default:
				$file =  $err.$i18n['unknown'];
				break;
		}
		
		// Fix the filename, so that we don't have funky characters screwing with out attempt to create the destination file.
		$file->filename = exponent_files_fixName($file->filename);
			
		if (file_exists(BASE.$dest.'/'.$file->filename) && $force == false) {
			$file =  $err.$i18n['file_exists'];
		}
	
		//Check to see if the directory exists.  If not, create the directory structure.
		if (!file_exists(BASE.$dest)) {
			exponent_files_makeDirectory($dest);
		}	

		// Move the temporary uploaded file into the destination directory, and change the name.
//		exponent_files_moveUploadedFile($_FILES[$name]['tmp_name'],BASE.$dest.'/'.$file->filename);
//		move_uploaded_file($_FILES[$name]['tmp_name'],BASE.$dest.'/'.$file->filename);
		copy($_FILES[$name]['tmp_name'],BASE.$dest.'/'.$file->filename);
//		$contentx =@file_get_contents($_FILES[$name]['tmp_name']); 
//		   $openedfile = fopen(BASE.$dest.'/'.$file->filename, "w"); 
//		   fwrite($openedfile, $contentx); 
//		   fclose($openedfile); 
		
//		if (file_exists(BASE.$dest.'/'.$file->filename)) {
//			$__oldumask = umask(0);
//			chmod(BASE.$dest.'/'.$file->filenamet,FILE_DEFAULT_MODE);
//			umask($__oldumask);
//		}		
		if (!file_exists(BASE.$dest.'/'.$file->filename)) {
			$file = $err.$i18n['cant_move'];
		}
		
		// At this point, we are good to go.
		
		$file->mimetype = $_FILES[$name]['type'];
		$file->directory = $dest;
		//$file->accesscount = 0;
		$file->filesize = $_FILES[$name]['size'];
		$file->posted = time();
		global $user;
		if ($user) {
			$file->poster = $user->id;
		}
		$file->last_accessed = time();
		
		$file->is_image = 0;
		// Get image width and height:
		$size = @getimagesize(BASE.$file->directory.'/'.$file->filename);
		if ($size !== false) {
			$file->is_image = 1;
			$file->image_width = $size[0];
			$file->image_height = $size[1];
		}
	
} else {
		$file = file::update('file',$directory,null,time().'_'.$_FILES['file']['name']);
}		
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
			exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
		} else {
			// If file::update() returns a non-object, it should be a string.  That string is the error message.
			$post = $_POST;
			$post['_formError'] = $file;
			exponent_sessions_set('last_POST',$post);
			exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}	
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
