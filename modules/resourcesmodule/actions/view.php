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

//exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
include_once(BASE.'external/mp3file.php');

$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($resource != null) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);

	$resource->permissions = array(
		'administrate'=>exponent_permissions_check('administrate',$iloc),
		'edit'=>exponent_permissions_check('edit',$iloc),
		'delete'=>exponent_permissions_check('delete',$iloc),
		'manage_approval'=>exponent_permissions_check('manage_approval',$iloc),	
	);
	
	if ($resource->flock_owner != 0) {
		if (!defined('SYS_USERS')) include_once(BASE.'subsystems/users.php');
		$resource->lock_owner = exponent_users_getUserById($resource->flock_owner);
		$resource->locked = 1;
	} else {
		$resource->locked = 0;
	}
	
	//unset ($_SESSION['downloadfilename']);
	//unset($_SESSION['downloadfile']);
	
	$file = $db->selectObject('file','id='.$resource->file_id);
	if ($file != null || $user->is_admin || $user->is_acting_admin) {
		$resource->mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");

//        $filenametest = $file->directory."/".$file->filename;
        $filenametest = BASE.$file->directory."/".$file->filename;
		$resource->fileexists = file_exists($filenametest);
//		$filesize = resourcesmodule::formatBytes(filesize(BASE.$file->directory.'/'.$file->filename));
		$resource->filesize = resourcesmodule::formatBytes(filesize($filenametest));
		if ($file->mimetype == "audio/mpeg") {
//			$mp3 = new mp3file(BASE.$file->directory.'/'.$file->filename);
			$mp3 = new mp3file($filenametest);
			$id3 = $mp3->get_metadata();
			if (($id3['Encoding']=='VBR') || ($id3['Encoding']=='CBR')) {
				$resource->duration = $id3['Length mm:ss'];
			} 
		} else {
			$resource->duration = 'Unknown';
		}		
		$resource->filename = URL_FULL.$file->directory.'/'.$file->filename;
        if ($resource->fileexists || $user->is_admin || $user->is_acting_admin) {
			//FJD: this should not be done here. Should be a new view for One Click Download        	      		
            /*header("Content-Disposition: attachment; filename=" . $file->filename);
            $host  	= $_SERVER['HTTP_HOST'];
			$uri  	= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');		
			header("Location: http://$host$uri/$filenametest");
            */
            $template = new template('resourcesmodule','_view',$loc);            
			$template->assign('resource',$resource); 

			// Get all of the categories for this Resources module:
			$config = $db->selectObject('resourcesmodule_config',"location_data='".serialize($loc)."'");
			if ($config == null) {
				$config->enable_categories = 0;
			}
			$cats = array();
			$cats = $db->selectObjectsIndexedArray('category', "location_data='".serialize($loc)."'");
			if ($config->enable_categories) {
				if (count($cats) != 0) {
					$template->assign('hasCategories', 1);				
				} else {
					$template->assign('hasCategories', 0);
				}
			} else {
				$template->assign('hasCategories', 0);
			}
			$c->name = '';
			$c->id = 0;
			uasort($cats, "exponent_sorting_byRankAscending");
			$cats[0] = $c;
			$template->assign('categories', $cats);	
			
            $template->assign('user',$user);
//            $template->assign('file',$file);
//            $template->assign('filename',$filename);
//            $template->assign('mimetype',$mimetype);    
//			$template->assign('filesize',$filesize);			
//			$template->assign('fileexists',$fileexists);			
//			$template->assign('duration',$duration);			
            $template->register_permissions(             
	            array('administrate','edit','delete','manage_approval','can_download'),$loc);
            $template->output();
            
        } else {
            echo SITE_404_HTML;
        }
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
