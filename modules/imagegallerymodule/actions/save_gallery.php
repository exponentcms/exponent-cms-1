<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: save_gallery.php,v 1.7 2005/05/09 05:54:38 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$gallery = null;
if (isset($_POST['id'])) {
	$gallery = $db->selectObject('imagegallery_gallery','id='.$_POST['id']);
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;
} else {
	$gallery->location_data = serialize($loc);
}

if (exponent_permissions_check('edit',$loc)) {
	
	$gallery = imagegallery_gallery::update($_POST,$gallery);
	
	if (isset($gallery->id)) {
		$db->updateObject($gallery,'imagegallery_gallery');
	} else {
		$id = $db->insertObject($gallery,'imagegallery_gallery');
	
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		
		$directory = 'files/imagegallerymodule/' . $loc->src . '/gallery'. $id;#.'/.thumbs';
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				echo '<div class="error">';
				if ($err == SYS_FILES_FOUNDFILE) {
					echo TR_FILEMANAGER_FILEFOUNDINPATH;
				} else if ($err == SYS_FILES_NOTWRITABLE) {
					echo TR_FILEMANAGER_CANTMKDIR;
				} else {
					echo TR_FILEMANAGER_UNKNOWNERROR;
				}
				echo '</div>';
				$db->delete('imagegallery_gallery','id='.$id);
				return;
			}
		}
		// Assign permissions -- need to create an internal loc
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$id);
		foreach (array_keys(imagegallerymodule::permissions($id)) as $perm) {
			exponent_permissions_grant($user,$perm,$iloc);
		}
		exponent_permissions_triggerSingleRefresh($user);
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>