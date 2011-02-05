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

$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($resource != null) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	/*$resource->permissions = array(
		'administrate'=>exponent_permissions_check('administrate',$iloc),
		'edit'=>exponent_permissions_check('edit',$iloc),
		'delete'=>exponent_permissions_check('delete',$iloc),
		'can_download'=>exponent_permissions_check('delete',$iloc),
	);*/

	$config = $db->selectObject('resourcesmodule_config', "location_data='".serialize($loc)."'");

	// if the admin/user hasn't configured this module yet we'll allow anon-downloads
	if (empty($config)) { 
		if (empty($config)) $config->allow_anon_downloads = true;
		$config->require_agreement = false;
	}

	// check if a confidentiality agreement is needed and present
	if ($config->require_agreement) {
		global $user;
		$user_agreement = $db->selectObject('resource_agreement', "user_id=".$user->id." AND location_data='".$resource->location_data."'");
		if (empty($user_agreement)) redirect_to(array('module'=>'resourcesmodule', 'action'=>'viewagreement', 'src'=>$loc->src, 'id'=>$resource->id));
	}

	if (!isset($config) || $config == null || $config->allow_anon_downloads != true) {
		if ( !exponent_permissions_check('can_download',$loc) && !exponent_permissions_check('can_download',$iloc)) {
			echo '<div class="error">You do not have permissions to download this file.</div>';
			exit();
		}
	}
  
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
	if ($file != null) {
		$mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");

        $filenametest = $file->directory . "/" . $file->filename;
	
		if (file_exists($filenametest)) {			
		//increment the download counter
			$db->increment("resourceitem","num_downloads",1,'id='.$resource->id);
			ob_end_clean();

			// NO buffering from here on out or things break unexpectedly. - RAM

			// This code was lifted from phpMyAdmin, but this is Open Source, right?
			// 'application/octet-stream' is the registered IANA type but
			//        MSIE and Opera seems to prefer 'application/octetstream'
			// It seems that other headers I've added make IE prefer octet-stream again. - RAM

			$mime_type = (EXPONENT_USER_BROWSER == 'IE' || EXPONENT_USER_BROWSER == 'OPERA') ? 'application/octet-stream;' : $file->mimetype;

			header('Content-Type: ' . $mime_type);
			header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header("Content-length: ".filesize($filenametest));
			header('Content-Transfer-Encoding: binary');
			header('Content-Encoding:');
			header('Content-Disposition: attachment; filename="' . $file->filename . '"');
			// IE need specific headers
			if (EXPONENT_USER_BROWSER == 'IE') {
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Vary: User-Agent');
			} else {
			header('Pragma: no-cache');
			}
			//Read the file out directly
			readfile($filenametest);
			exit();
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
