<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

class swfmodule {
	function name() { return pathos_lang_loadKey('modules/swfmodule/class.php','module_name'); }
	function author() { return 'Greg Otte'; }
	function description() { return pathos_lang_loadKey('modules/swfmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/swfmodule/class.php');
		
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure'],
		);
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$location = null, $title = '') {
		global $user;
		global $db;
	
		$template = new template('swfmodule',$view,$location);
		$template->assign('moduletitle',$title);
			
		if (defined('PREVIEW_READONLY') && !defined('SELECTOR')) {
			return;
		} 
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/swfmodule';
		if (!file_exists(BASE.$directory)) {
			$err = pathos_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
	
		$data = $db->selectObject('swfitem',"location_data='".serialize($location)."'");
		
		if($data == null) {
			$data->_noflash = 1;
			$data->_align = 'center';
		} else {
			$data->_noflash = 0;
			switch ($data->alignment) {
				case 1:
					$data->_align = 'left';
					break;
				case 2:
					$data->_align = 'right';
					break;
				default:
					$data->_align = 'center';
					break;
			}
					
			$file = $db->selectObject('file','id='.$data->swf_id);
			if ($file && is_readable(BASE.$file->directory.'/'.$file->filename)) {
				$data->_flashurl=$file->directory.'/'.$file->filename;
			} else {
				$data->_flashurl='';
			}
			$file = $db->selectObject('file','id='.$data->alt_image_id);
			if ($file && is_readable(BASE.$file->directory.'/'.$file->filename)) {
				$data->_noflashurl=$file->directory.'/'.$file->filename;
			} else {
				$data->_noflashurl='';
			}	
		}
		$template->assign('data',$data);
		$template->register_permissions(
			array('administrate','configure'), 
			$location
		);
		$template->output();
		
	}
	
	function spiderContent($item = null) {
		// No searchable content
		return false;
	}
	
	function deleteIn($loc) {
		global $db;
		$data = $db->selectObject('swfitem',"location_data='".serialize($loc)."'");
		if ($data) {
			$file = $db->selectObject('file','id='.$data->alt_image_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
			$file = $db->selectObject('file','id='.$data->swf_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
			$db->delete('swfitem','id='.$data->id);
		}	
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$data = $db->selectObject('swfitem',"location_data='".serialize($oloc)."'");
		if ($data) {
			$file = $db->selectObject('file','id='.$data->alt_image_id);
			if ($file) {
				$newname = time().'_'.$file->filename;
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$file->directory.'/'.$newname);
				$file->filename = $newname;
				unset($file->id);
				$data->alt_image_id = $db->insertObject($file,'file');
			}
			
			$file = $db->selectObjects('file','id='.$data->swf_id);
			if ($file) {
				$newname = time().'_'.$file->filename;
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$file->directory.'/'.$newname);
				$file->filename = $newname;
				unset($file->id);
				$data->swf_id = $db->insertObject($file,'file');
			}
			
			unset($data->id);
			$data->location_data = serialize($nloc);
			$db->insertObject($data,'swfitem');
		}
	}
}

?>