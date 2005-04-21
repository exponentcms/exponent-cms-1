<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$item = null;
if (isset($_POST['id'])) {
	$item = $db->selectObject("imagemanageritem","id=".$_POST['id']);
	$loc = unserialize($item->location_data);
}

if (	($item == null && pathos_permissions_check("post",$loc)) ||
	($item != null && pathos_permissions_check("edit",$loc))
) {
	$item = imagemanageritem::update($_POST,$item);
	$item->location_data = serialize($loc);
	
	if (!isset($item->id)) {
		if (!defined("SYS_FILES")) require_once(BASE."subsystems/files.php");
	
		$directory = "files/imagemanagermodule/".$loc->src;
		$fname = null;
		
		if (pathos_files_uploadDestinationFileExists($directory,"file")) {
			// Auto-uniqify Logic here
			$fileinfo = pathinfo($_FILES['file']['name']);
			$fileinfo['extension'] = ".".$fileinfo['extension'];
			do {
				$fname = basename($fileinfo['basename'],$fileinfo['extension']).uniqid("").$fileinfo['extension'];
			} while (file_exists(BASE.$directory."/$fname"));
		}
		
		$file = file::update("file",$directory,null,$fname);
		if ($file != null) {
			$item->file_id = $db->insertObject($file,"file");
			// Make thumbnail?
			$db->insertObject($item,"imagemanageritem");
			
			pathos_flow_redirect();
		}
	} else {
		$db->updateObject($item,"imagemanageritem");
		pathos_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>