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

exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

$resource = $db->selectObject('resourceitem','id='.intval($_GET['id']));
if ($resource != null) {
	$loc = unserialize($resource->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$resource->id);
	
	$resource->permissions = array(
		'administrate'=>exponent_permissions_check('administrate',$iloc),
		'edit'=>exponent_permissions_check('edit',$iloc),
		'delete'=>exponent_permissions_check('delete',$iloc),
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
	if ($file != null) {
		$mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");

        $filenametest = $file->directory . "/" . $file->filename;
	
        if (file_exists($filenametest)) {
        	       		
            	header("Content-Disposition: attachment; filename=" . $file->filename);
            	$host  	= $_SERVER['HTTP_HOST'];
			$uri  	= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');		
			header("Location: http://$host$uri/$filenametest");
            

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
