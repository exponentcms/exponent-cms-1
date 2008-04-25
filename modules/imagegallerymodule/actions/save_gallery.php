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
if (!empty($_POST['id'])) {
	$gallery = $db->selectObject('imagegallery_gallery','id='.$_POST['id']);
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;
} else {
	$gallery->location_data = serialize($loc);
}

if (exponent_permissions_check('edit',$loc)) {
	if (!empty($gallery->id)) {
		if($_POST['box_size']!=$gallery->box_size || $_POST['pop_size']!=$gallery->pop_size){
			$resizeimages = 1;
		}else{
			$resizeimages = 0;
		}
	}
	$gallery = imagegallery_gallery::update($_POST,$gallery);

	if (!empty($gallery->id)) {
		$db->updateObject($gallery,'imagegallery_gallery');
	} else {
		$gallery->galleryorder = $db->countObjects('imagegallery_gallery','location_data='.serialize($loc)) + 1;
		$id = $db->insertObject($gallery,'imagegallery_gallery');
	
		$resizeimages = 0;
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

	
	if (!exponent_javascript_inAjaxAction()) {
		exponent_flow_redirect();
	} else {
		if($resizeimages == 1){
			$gallery->images = $db->selectObjects('imagegallery_image','gallery_id='.$gallery->id);
			//$gal->images = $db->selectColumn('imagegallery_image','id','gallery_id='.$gallery->id);
			foreach ($gallery->images as $key=>$img){
				$gal->images[$key]->gid = $gallery->id;
				$gal->images[$key]->file_id = $img->file_id;
				$gal->images[$key]->thumb = $gallery->box_size;
				$gal->images[$key]->pop = $gallery->pop_size;
			}
			echo json_encode($gal);			
		}else{
			echo "no-resize";
		}	
		exit;
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>
