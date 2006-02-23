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

$item = null;
if (isset($_POST['id'])) {
	$item = $db->selectObject('imagemanageritem','id='.intval($_POST['id']));
	if ($item) {
		$loc = unserialize($item->location_data);
	}
}

if (	($item == null && pathos_permissions_check('post',$loc)) ||
	($item != null && pathos_permissions_check('edit',$loc))
) {

    // check for real images.
    $filenew = $_FILES['file']['tmp_name'];
    $fileup = getimagesize ( $filenew );

    if (
        $fileup[2] > 0 &&
        $fileup[1] > 0) {

        $item = imagemanageritem::update($_POST,$item);
    	$item->location_data = serialize($loc);
	
    	if (!isset($item->id)) {
	    	if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
	
    		$directory = 'files/imagemanagermodule/'.$loc->src;
	    	$fname = null;
		
		    if (pathos_files_uploadDestinationFileExists($directory,'file')) {
    			// Auto-uniqify Logic here
	    		$fileinfo = pathinfo($_FILES['file']['name']);
		    	$fileinfo['extension'] = '.'.$fileinfo['extension'];
			    do {
    				$fname = basename($fileinfo['basename'],$fileinfo['extension']).uniqid('').$fileinfo['extension'];
	    		} while (file_exists(BASE.$directory.'/'.$fname));
		    }
		
    		$file = file::update('file',$directory,null,$fname);
	    	if (is_object($file)) {
		    	$item->file_id = $db->insertObject($file,'file');
    			// Make thumbnail?
    			$db->insertObject($item,'imagemanageritem');
			
    			pathos_flow_redirect();
    		} else {
	    		// If file::update() returns a non-object, it should be a string.  That string is the error message.
		    	$post = $_POST;
			    $post['_formError'] = $file;
    			pathos_sessions_set('last_POST',$post);
    			header('Location: ' . $_SERVER['HTTP_REFERER']);
    		}

        }

	} else {
		$db->updateObject($item,'imagemanageritem');
		pathos_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>
