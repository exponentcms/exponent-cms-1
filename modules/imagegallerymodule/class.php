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
# $Id: class.php,v 1.7 2005/05/09 05:53:47 filetreefrog Exp $
##################################################

class imagegallerymodule {
	function name() { return 'Image Gallery'; }
	function description() { return 'Allows a user to post images to galleries.'; }
	function author() { return 'OIC Group, Inc.'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function getLocationHierarchy($loc) {
		return array(exponent_core_makeLocation($loc->mod,$loc->src),$loc);
	}
	
	function permissions($internal = '') {
		if ($internal == '') {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
				'create'=>'Create Galleries',
				'edit'=>'Edit Galleries',
				'delete'=>'Delete Galleries',
				'manage'=>'Manage Gallery Images'
			);
		} else {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
				'edit'=>'Edit Gallery',
				'delete'=>'Delete Gallery',
				'manage'=>'Manage Gallery Images'
			);
		}
	}
	
	function show($view,$loc = null, $title = '') {
		global $db;
		
		$config = null;
		$config = $db->selectObject('imagegallerymodule_config', "location_data='".serialize($loc)."'");
		if (!is_object($config)) {
			$config->multiple_galleries = 0;
			$config->random_single_gallery = 0;
		}
	
		//if ($config->multiple_galleries == 0) {
			//$template = new template("imagegallerymodule",'_view_all_galleries',$loc);
		//} else {
			$template = new template("imagegallerymodule",$view,$loc);
		//}
	
		if (!defined('SYS_FILES')) require(BASE.'subsystems/files.php');
		$directory = 'files/imagegallerymodule/'.$loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				exponent_lang_loadDictionary('modules','filemanager');
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		
		$galleries = $db->selectObjects('imagegallery_gallery',"location_data='".serialize($loc)."'",'galleryorder DESC');
		$iloc = exponent_core_makeLocation($loc->mod,$loc->src);
		for ($i = 0; $i < count($galleries); $i++) {
			$iloc->int = $galleries[$i]->id;
			$galleries[$i]->permissions = array(
				'edit'=>exponent_permissions_check('edit',$iloc),
				'delete'=>exponent_permissions_check('delete',$iloc)
			);
		
			if ($config->multiple_galleries == 0) {	
				$galleries[$i]->images = array();
				$galleries[$i]->images = $db->selectObjects('imagegallery_image', 'gallery_id='.$galleries[$i]->id,'rank');
				for ($y = 0; $y < count($galleries[$i]->images); $y++) {
					$galleries[$i]->images[$y]->file = $db->selectObject("file","id=".$galleries[$i]->images[$y]->file_id);
					//eDebug($galleries[$i]->images[$y]->file);
					if(is_object($galleries[$i]->images[$y]->file)){
						if (file_exists(BASE.$galleries[$i]->images[$y]->file->directory."/".$galleries[$i]->images[$y]->enlarged)) {
							$popsize = getimagesize(BASE.$galleries[$i]->images[$y]->file->directory."/".$galleries[$i]->images[$y]->enlarged);
							//eDebug($galleries[$i]->images[$y]);
							$galleries[$i]->images[$y]->popwidth = $popsize[0];
							$galleries[$i]->images[$y]->popheight = $popsize[1];
						}
					}
				}
			}
		}
		if ( $config->random_single_gallery ) {
			$random_gallery[] = $galleries[rand(0,count($galleries)-1)];
			$template->assign('galleries', $random_gallery);
			$template->assign('allgalleries',$galleries);
		} else {
			$template->assign('galleries',$galleries);
		}
		
		//eDebug($galleries); exit();
		
		$template->assign('moduletitle',$title);
		//$template->assign('show_desc',$config->show_pic_desc);
		$template->register_permissions(
			array('administrate','create','edit','delete','manage'),
			$loc);
		$template->output();
	}

	function createThumbnailFile($file = null, $height = 0) {
		if (!defined('SYS_IMAGE')) require(BASE.'subsystems/image.php');
		if ($file != null && $height != 0) {
			$size = getimagesize($file->directory."/".$file->filename);
			//if($size[1]<=$height) $height = $size[1];
				if($size[1]<=$size[0]){
					$thumb = exponent_image_scaleToHeight($file->directory."/".$file->filename,intval($height));				
				}else{
					$thumb = exponent_image_scaleToWidth($file->directory."/".$file->filename,intval($height));				
				}
			$pos = strrpos($file->filename, ".");
			if ($pos != false) {
				$filename = substr($file->filename, 0, $pos);
				$extension = substr($file->filename, $pos);
				$thumbname = $filename."_thumb".$extension;
			}else{
				$thumbname = $file->filename."_thumb";
			}
		}
		$info = exponent_image_sizeinfo($file->directory."/".$file->filename);
		switch ($info['mime']) {
			case 'image/jpeg':
			case 'image/jpg':
				imagejpeg($thumb, $file->directory."/".$thumbname, 100);
			case 'image/png':
				imagepng($thumb, $file->directory."/".$thumbname);
			case 'image/gif':
				imagegif($thumb, $file->directory."/".$thumbname);
		} 
		
		return $thumbname;
	}
	function createEnlargedFile($file = null, $height = 0) {
		if (!defined('SYS_IMAGE')) require(BASE.'subsystems/image.php');
		if ($file != null && $height != 0) {
			$size = getimagesize($file->directory."/".$file->filename);
			if($size[1]<=$height) $height = $size[1];
				if($size[1]<=$size[0]){
					$thumb = exponent_image_scaleToWidth($file->directory."/".$file->filename,intval($height));				
				}else{
					$thumb = exponent_image_scaleToHeight($file->directory."/".$file->filename,intval($height));				
				}
			$pos = strrpos($file->filename, ".");
			if ($pos != false) {
				$filename = substr($file->filename, 0, $pos);
				$extension = substr($file->filename, $pos);
				$popname = $filename."_popup".$extension;
			}else{
				$popname = $file->filename."_popup";
			}
		}
		$info = exponent_image_sizeinfo($file->directory."/".$file->filename);
		switch ($info['mime']) {
			case 'image/jpeg':
			case 'image/jpg':
				imagejpeg($thumb, $file->directory."/".$popname, 100);
			case 'image/png':
				imagepng($thumb, $file->directory."/".$popname);
			case 'image/gif':
				imagegif($thumb, $file->directory."/".$popname);
		} 
		return $popname;
	}
	
	function deleteIn($loc) {
		global $db;
		foreach ($db->selectObjects('imagegallery_gallery',"location_data='".serialize($loc)."'") as $gallery) {
			$db->delete('imagegallery_image','gallery_id='.$gallery->id);
		}
		$db->delete('imagegallery_gallery',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$basedirectory = 'files/imagegallerymodule/'.$nloc->src;
		
		foreach ($db->selectObjects('imagegallery_gallery',"location_data='".serialize($oloc)."'") as $gallery) {
			$old_id = $gallery->id;
			unset($gallery->id);
			$gallery->location_data = serialize($nloc);
			$gallery->id = $db->insertObject($gallery,'imagegallery_gallery');
			
			$directory = $basedirectory . '/gallery'.$gallery->id;
			if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
			if (!file_exists(BASE.$directory) && exponent_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
				return;
			}
			
			foreach ($db->selectObjects('imagegallery_image','gallery_id='.$old_id) as $image) {
				$file = $db->selectObject('file','id='.$image->file_id);
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$directory.'/'.$file->filename);
				if (file_exists(BASE.$directory.'/'.$file->filename)) {
					$file->directory = $directory;
					unset($file->id);
					$image->file_id = $db->insertObject($file,'file');
					
					unset($image->id);
					$image->gallery_id = $gallery->id;
					$db->insertObject($image,'imagegallery_image');
				}
			}
		}
	}
	
	function spiderContent($item = null) {
		// FIXME:For now, no searching in the gallery mod
		return false;
	}
}

?>
