<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
	function name() { return "Flash Animation Module"; }
	function author() { return "OIC Group Exponent Team / Greg Otte"; }
	function description() { return "Manages a Flash Animation."; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','swfmodule');
		return array(
			'administrate'=>TR_SWFMODULE_PERM_ADMIN,
			'configure'=>TR_SWFMODULE_PERM_CONFIG
		);
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') return array($loc);
		else return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
	}
	
	function show($view,$location = null, $title = '') {
		global $user;
		global $db;
	
		$template = new template('swfmodule',$view,$location);
		$template->assign('moduletitle',$title);
			
		if (defined('PREVIEW_READONLY') && !defined('SELECTOR')) {
			return;
		} 
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
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
	
	function copyContent($oloc,$nloc) {
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
}

?>