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
      ob_start("ob_gzhandler");
      
      // This code was lifted from phpMyAdmin, but this is Open Source, right?
      // 'application/octet-stream' is the registered IANA type but
      //        MSIE and Opera seems to prefer 'application/octetstream'
      $mime_type = (EXPONENT_USER_BROWSER == 'IE' || EXPONENT_USER_BROWSER == 'OPERA') ? 'application/octetstream' : $file->mimetype;

      header('Content-Type: ' . $mime_type);
      header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
      // IE need specific headers
      if (EXPONENT_USER_BROWSER == 'IE') {
        header('Content-Disposition: inline; filename="' . $file->filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
      } else {
        header('Content-Disposition: attachment; filename="' . $file->filename . '"');
        header('Pragma: no-cache');
      }

      $handle = fopen($filenametest, "r");
      $contents = null;
      $contents = fread($handle, filesize($filenametest));
      fclose($handle);
      if (isset($contents) && $contents != '') {
        echo $contents;
      }
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
