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

class imagemanagermodule {
	function name() { return "Image Manager"; }
	function author() { return "James Hunt"; }
	function description()  { return "A tie-in module that allows images to be uploaded and then used from the HTML Editor Control."; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal) {
		pathos_lang_loadDictionary('modules','imagemanagermodule');
		if ($internal == '') {
			return array(
				'administrate'=>TR_IMAGEMANAGERMODULE_PERM_ADMIN,
				'post'=>TR_IMAGEMANAGERMODULE_PERM_POST,
				'edit'=>TR_IMAGEMANAGERMODULE_PERM_EDIT,
				'delete'=>TR_IMAGEMANAGERMODULE_PERM_DELETE
			);
		} else {
			return array(
				#'post'=>'Upload',
				'edit'=>TR_IMAGEMANAGERMODULE_PERM_EDITONE,
				'delete'=>TR_IMAGEMANAGERMODULE_PERM_DELETEONE
			);
		}
	}
	
	function show($view,$loc,$title = '') {
		
		$template = new template('imagemanagermodule',$view,$loc);
		
		$uilevel = 99; // MAX
		if (pathos_sessions_isset("uilevel")) $uilevel = pathos_sessions_get("uilevel");
		$template->assign('show',((defined('SELECTOR') || $uilevel > UILEVEL_PREVIEW) ? 1 : 0));
		
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
		$directory = 'files/imagemanagermodule/'.$loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = pathos_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		global $db;
		$items = $db->selectObjects("imagemanageritem","location_data='".serialize($loc)."'");
		$files = $db->selectObjectsIndexedArray("file","directory='$directory'");
		$template->assign('items',$items);
		$template->assign('files',$files);
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','post','edit','delete'),
			$loc);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$directory = 'files/imagemanagermodule/'.$loc->src;
		foreach ($db->selectObjectsIndexedArray("file","directory='$directory'") as $file) {
			file::delete($file);
		}
		rmdir(BASE.$directory);
		$db->delete('imagemanageritem',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$directory = 'files/imagemanagermodule/'.$nloc->src;
		if (!file_exists(BASE.$directory) && pathos_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
			return;
		}
		foreach ($db->selectObjects("imagemanageritem","location_data='".serialize($oloc)."'") as $i) {
			$file = $db->selectObject('file','id='.$i->file_id);
			copy($file->directory.'/'.$file->filename,$directory.'/'.$file->filename);
			$file->directory = $directory;
			unset($file->id);
			$file->id = $db->insertObject($file,'file');
			
			$i->location_data = serialize($nloc);
			unset($i->id);
			$i->file_id = $file->id;
			$db->insertObject($i,'imagemanageritem');
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
}

?>