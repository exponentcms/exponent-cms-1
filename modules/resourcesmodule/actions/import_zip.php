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

include_once(BASE.'subsystems/files.php');

//check file to be sure it's a valid zip file
$checkMime = mime_content_type($_FILES['file']['tmp_name']);
echo $checkMime;
if ($checkMime == "application/x-zip"){
	$post = $_POST;
        $post['_formError'] = "File is not a valid zip file.";
        exponent_sessions_set('last_POST',$post);
        unset($_SESSION['resource_cache']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);	
}
//extract archive into tmp directory
//set variables
$directory = 'files/resourcesmodule/'.$loc->src;		
$resDir = BASE . $directory;
$xDir = $resDir . '/import';
@mkdir($xDir);
if (!file_exists($xDir)){
	$post = $_POST;
        $post['_formError'] = "Can not create extraction directory.";
        exponent_sessions_set('last_POST',$post);
        unset($_SESSION['resource_cache']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$extLine = 'unzip ' . $_FILES['file']['tmp_name'] . ' -d '  . $xDir;  
$res = `$extLine`;

//loop over files in directory (in alpha order)and import into this resource module

if (($resource == null && exponent_permissions_check('post',$loc)) ||
	($resource != null && exponent_permissions_check('edit',$loc)) ||
	($iloc != null && exponent_permissions_check('edit',$iloc))
){ 
	$dh = opendir($xDir);
	$filesArray = array();
	while (($f = readdir($dh)) !== false){
		if ($f != '.' && $f != '..' && @filetype($f) !== "dir"){
			array_push($filesArray,$f);
		}
	}		
	sort($filesArray);
	foreach ($filesArray as $key => $f){
		$resource = null;
		$iloc = null;
		//iif ($f != '.' && $f != '..' && @filetype($f) !== "dir"){
			$fileParts = pathinfo($xDir.'/'.$f);
		        $newFilename = time().'_'.exponent_files_fixName($f); 
			rename($xDir.'/'.$f, $resDir.'/'.$newFilename);
			unlink($xDir.'/'.$f);
			$resource->name = $fileParts['filename'];
			$resource->description = '';
			$resource->location_data = serialize($loc);
			$rank = $db->max('resourceitem','rank','location_data',"location_data='".$resource->location_data."'");
			$resource->rank = intval($_POST['rank']);
			if ($rank == 0){
				//check if another reosurce is there to make sure we don't clober the rank
				$resources = $db->selectObjects('resourceitem',"location_data='".$resource->location_data ."'");
				if (count($resources)>0)$rank = 1;
				else $rank=0;
			}else{
				$rank++;
			}
			$resource->rank = $rank;
	
			if (!isset($resource->file_id)) {
				$file->filename = ($newFilename);
				$file->mimetype = mime_content_type($resDir.'/'.$newFilename);
				$file->directory = $directory;
				$file->filesize = filesize($resDir.'/'.$newFilename);
				$file->posted = time();
				$file->last_accessed = time();
				$size = @getimagesize($resDir.'/'.$newFilename);
				if ($size !== false){
					$file->is_image = 1;
					$file->image_width = $size[0];
					$file->image_height = $size[1];
				}else{	
					$file->is_image = 0;
					$file->image_width = 0;
                                        $file->image_height = 0;
				}
				if (is_object($file)) {
					$resource->file_id = $db->insertObject($file,'file');
					$resource->poster = $user->id;
					$resource->posted = time();
					$id = $db->insertObject($resource,'resourceitem');
					$resource->id = $id;
					// Assign new perms on loc
					$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$id);
					exponent_permissions_grant($user,'edit',$iloc);
					exponent_permissions_grant($user,'delete',$iloc);
					exponent_permissions_grant($user,'administrate',$iloc);
					exponent_permissions_triggerSingleRefresh($user);
					
					//if (!defined('SYS_WORKFLOW')) include_once(BASE.'subsystems/workflow.php');
					//exponent_workflow_post($resource,'resourceitem',$loc);
				} else {
				//	// If file::update() returns a non-object, it should be a string.  That string is the error message.
				//	$post = $_POST;
				//	$post['_formError'] = $file;
				//	exponent_sessions_set('last_POST',$post);
				//	unset($_SESSION['resource_cache']);
					//	header('Location: ' . $_SERVER['HTTP_REFERER']);
					echo "Error: $file <br/>";
				}
				unset($file);
			} //ielse {
			//	$resource->editor = $user->id;
			//	$resource->edited = time();
			//	$db->updateObject($resource,'resourceitem');
			//	exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
			//	exponent_flow_redirect();
			//}/
			//exit();
		//}
	}
	exponent_sessions_clearAllUsersSessionCache('resourcesmodule');
	rmdir($xDir);
	header('Location: ' . $_POST['referrer']);
} else {
	echo SITE_403_HTML;
}
?>
