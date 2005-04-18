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
//GREP:HARDCODEDTEXT
if (!defined("PATHOS")) exit("");

$resource = null;
$iloc = null;
if (isset($_POST['id'])) {
	$resource = $db->selectObject("resourceitem","id=".$_POST['id']);
	$loc = unserialize($resource->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$resource->id);
}

if (($resource == null && pathos_permissions_check("post",$loc)) ||
	($resource != null && pathos_permissions_check("edit",$loc)) ||
	($iloc != null && pathos_permissions_check("edit",$iloc))
) {
	$resource = resourceitem::update($_POST,$resource);
	$resource->location_data = serialize($loc);
	
	if (!isset($resource->id)) {
		$resource->rank = $_POST['rank'];
		$db->increment('resourceitem','rank',1,"location_data='".serialize($loc)."' AND rank >= ".$resource->rank);
	}
	
	if (!isset($resource->file_id)) {
		$directory = "files/resourcesmodule/".$loc->src;
		
		if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
			$file = file::update("file",$directory,null,time()."_".$_FILES['file']['name']);
			if ($file != null) {
				$resource->file_id = $db->insertObject($file,"file");
				$id = $db->insertObject($resource,"resourceitem");
				// Assign new perms on loc
				$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$id);
				pathos_permissions_grant($user,"edit",$iloc);
				pathos_permissions_grant($user,"delete",$iloc);
				pathos_permissions_grant($user,"administrate",$iloc);
				pathos_permissions_triggerSingleRefresh($user);
				
				if (!defined("SYS_WORKFLOW")) require_once(BASE."subsystems/workflow.php");
				$resource->id = $id;
				$resource->poster = $user->id;
				$resource->posted = time();
				pathos_workflow_post($resource,"resourceitem",$loc);
			}
		} else {
			pathos_lang_loadDictionary('modules','filemanager');
			switch($_FILES["file"]["error"]) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					echo TR_FILEMANAGER_FILETOOLARGE;
					break;
				case UPLOAD_ERR_PARTIAL:
					echo TR_FILEMANAGER_PARTIALFILE;
					break;
				case UPLOAD_ERR_NO_FILE:
					echo TR_FILEMANAGER_NOFILEUPLOADED;
					break;
			}
		}
	} else {
		$resource->editor = $user->id;
		$resource->edited = time();
		$db->updateObject($resource,"resourceitem");
		pathos_flow_redirect();
	}
} else {
	echo SITE_403_HTML;
}

?>