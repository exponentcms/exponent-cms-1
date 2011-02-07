<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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

$resource = null;
$iloc = null;
if (isset($_POST['categories'])) {
	$cat = $_POST['categories'];
} else {
	$cat = 0;
}
if (isset($_POST['id'])) {
	$resource = $db->selectObject('resourceitem','id='.intval($_POST['id']));
	if ($resource) {
		$loc = unserialize($resource->location_data);
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	}
} else {
	$resource->rank = $db->max('resourceitem', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$cat);
	if ($resource->rank == null) {
		$resource->rank = 0;
	} else {
		$resource->rank += 1;
	}
}

include(BASE.'external/mimetype.php');
// we're using a file already on the server, fool system into thinkin it's just been uploaded
if ($_POST['fileexists']) {
	$filename = $_SERVER['DOCUMENT_ROOT'].$_POST['fileexists'];
	$mimetype = new mimetype();
	$mimetype = $mimetype->getType($filename);
	$_FILES['file']['name'] = end(explode("/", $_POST['fileexists']));
	$_FILES['file']['type'] = $mimetype;
	$_FILES['file']['tmp_name'] = $filename;
	$_FILES['file']['error'] = 0;	
	$_FILES['file']['size'] = filesize($filename);
}

if (($resource == null && exponent_permissions_check('post',$loc)) ||
	($resource != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
) {
	$oldcatid = $resource->category_id;
	$resource = resourceitem::update($_POST,$resource);
	$resource->location_data = serialize($loc);
	$resource->category_id = $cats;
	// if (!isset($resource->id)) {
		// $resource->rank = intval($_POST['rank']);
		// $db->increment('resourceitem','rank',1,"location_data='".serialize($loc)."' AND rank >= ".$resource->rank);
	// }
	if (($oldcatid != $resource->category_id) && isset($resource->id)) {
		$db->decrement('resourceitem', 'rank', 1, "location_data='".serialize($loc)."' AND rank > ".$resource->rank." AND category_id=".$oldcatid);
		$resource->rank = $db->max('resourceitem', 'rank', 'location_data', "location_data='".serialize($loc)."' AND category_id=".$resource->category_id);
		if ($resource->rank == null) {
			$resource->rank = 0;
		} else { 
			$resource->rank += 1;
		}
	}		
	
	if (!isset($resource->file_id)) {
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
			if ($_FILES[$name]['type'] != "application/octet-stream") {
				$file->mimetype = $_FILES[$name]['type'];
			} else {
				$mimetype = new mimetype();
				$file->mimetype = $mimetype->getType($_FILES[$name]['name']);
			}
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
			$resource->file_id = $db->insertObject($file,'file');
			$id = $db->insertObject($resource,'resourceitem');
			// Assign new perms on loc
			$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$id);
			exponent_permissions_grant($user,'edit',$iloc);
			exponent_permissions_grant($user,'delete',$iloc);
			exponent_permissions_grant($user,'administrate',$iloc);
			exponent_permissions_triggerSingleRefresh($user);
			
			$resource->id = $id;
			$resource->poster = $user->id;
//			$resource->posted = time();
			if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
			exponent_workflow_post($resource,'resourceitem',$loc);
			exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
		} else {
			// If file::update() returns a non-object, it should be a string.  That string is the error message.
			$post = $_POST;
			$post['_formError'] = $file;
			exponent_sessions_set('last_POST',$post);
			unset($_SESSION['resource_cache']);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else {
		$resource->editor = $user->id;
		$resource->edited = time();
		$db->updateObject($resource,'resourceitem');
		if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
		exponent_workflow_post($resource,'resourceitem',$loc);
		exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
//		exponent_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>
