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

class mediaplayermodule {
	function name() { return 'Media Player Module'; }
	function author() { return 'Ron Miller'; }
	function description() { return 'Display Internet-Friendly media content using FlowPlayer'; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/swfmodule/class.php');
		
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure'],
		);
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,exponent_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$location = null, $title = '') {
		global $user;
		global $db;
	
		$template = new template('mediaplayermodule',$view,$location);
		$template->assign('moduletitle',$title);
			
		if (defined('PREVIEW_READONLY') && !defined('SELECTOR')) {
			return;
		} 
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/mediaplayermodule/' . $location->src;
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
	
		$data = $db->selectObject('mediaitem',"location_data='".serialize($location)."'");
		
		if($data == null) {
			$data->_noflash = 1;
			$data->_align = 'center';
			$data->loop_media = 0;
			$data->auto_rewind = 1;
			$data->autoplay = 0;
			$data->hide_controls = 0;
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
					
			$file = $db->selectObject('file','id='.$data->media_id);
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
		$template->assign('loc', $location);
		$template->output();
		
	}
	
	function spiderContent($item = null) {
		// No searchable content
		return false;
	}
	
	function deleteIn($loc) {
		global $db;
		$data = $db->selectObject('mediaitem',"location_data='".serialize($loc)."'");
		if ($data) {
			$file = $db->selectObject('file','id='.$data->alt_image_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
			$file = $db->selectObject('file','id='.$data->media_id);
			file::delete($file);
			$db->delete('file','id='.$file->id);
			$db->delete('mediaitem','id='.$data->id);
		}	
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
		$data = $db->selectObject('mediaitem',"location_data='".serialize($oloc)."'");
		if ($data) {
			$file = $db->selectObject('file','id='.$data->alt_image_id);
			if ($file) {
				$newname = time().'_'.$file->filename;
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$file->directory.'/'.$newname);
				$file->filename = $newname;
				unset($file->id);
				$data->alt_image_id = $db->insertObject($file,'file');
			}
			
			$file = $db->selectObjects('file','id='.$data->media_id);
			if ($file) {
				$newname = time().'_'.$file->filename;
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$file->directory.'/'.$newname);
				$file->filename = $newname;
				unset($file->id);
				$data->media_id = $db->insertObject($file,'file');
			}
			
			unset($data->id);
			$data->location_data = serialize($nloc);
			$db->insertObject($data,'mediaitem');
		}
	}
}

?>