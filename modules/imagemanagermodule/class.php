<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class imagemanagermodule {
	function name() { return pathos_lang_loadKey('modules/imagemanagermodule/class.php','module_name'); }
	function author() { return 'James Hunt'; }
	function description()  { return pathos_lang_loadKey('modules/imagemanagermodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		$i18n = pathos_lang_loadFile('modules/imagemanagermodule/class.php');
		
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete']
			);
		} else {
			return array(
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete']
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