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

class bannermodule {
	function name() { return exponent_lang_loadKey('modules/bannermodule/class.php','module_name'); }
	function author() { return "James Hunt"; }
	function description() { return exponent_lang_loadKey('modules/bannermodule/class.php','module_description'); }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {	
		$i18n = exponent_lang_loadFile('modules/bannermodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'configure'=>$i18n['perm_configure'],
			'manage'=>$i18n['perm_manage'],
			'manage_af'=>$i18n['perm_manage_af'],
		);
	}
	
	function deleteIn($loc) {
		global $db;
		$banners = $db->selectObjects('banner_ad',"location_data='".serialize($loc)."'");
		foreach ($banners as $b) {
			$db->delete('banner_click','ad_id='.$b->id);
			$file = $db->selectObject('file','id='.$b->file_id);
			file::delete($file);
		}
		if (file_exists(BASE.'files/bannermodule/'.$loc->src)) {
			rmdir(BASE.'files/bannermodule/'.$loc->src);
		}
		$db->delete('banner_ad',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/bannermodule/'.$nloc->src;
		if (!file_exists(BASE.$directory) && exponent_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
			return;
		}
		
		global $db;
		foreach ($db->selectObjects('banner_ad',"location_data='".serialize($oloc)."'") as $banner) {
			$file = $db->selectObject('file','id='.$banner->file_id);
			
			copy($file->directory.'/'.$file->filename,$directory.'/'.$file->filename);
			$file->directory = $directory;
			unset($file->id);
			$file->id = $db->insertObject($file,'file');
			
			$banner->location_data = serialize($nloc);
			$banner->file_id = $file->id;
			unset($banner->id);
			$db->insertObject($banner,'banner_ad');
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	function show($view,$loc, $title = '') {
		global $db;
		
		$template = new template('bannermodule',$view,$loc);
		$template->assign('title',$title);
		
		$viewconfig = array('type'=>'default','number'=>1);
		if (is_readable($template->viewdir."/$view.config")) $viewconfig = include($template->viewdir."/$view.config");
		if ($viewconfig['type'] == 'affiliates') {
			$af = $db->selectObjects('banner_affiliate');
			for ($i = 0; $i < count($af); $i++) {
				$af[$i]->bannerCount = $db->countObjects('banner_ad','affiliate_id='.$af[$i]->id);
				$af[$i]->contact_info = str_replace("\n","<br />",$af[$i]->contact_info);
			}
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			usort($af,'exponent_sorting_byNameAscending');
			
			$template->assign('affiliates',$af);
		} else {
			if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		
			$directory = 'files/bannermodule/' . $loc->src;
			if (!file_exists(BASE.$directory)) {
				$err = exponent_files_makeDirectory($directory);
				if ($err != SYS_FILES_SUCCESS) {
					$template->assign('noupload',1);
					$template->assign('uploadError',$err);
				}
			}
			
			$all = $db->selectObjects('banner_ad',"location_data='".serialize($loc)."'");
			
			if ($viewconfig['type'] == 'allbanners') {
				$bfiles = $db->selectObjectsIndexedArray('file',"directory='".$directory."'");
				
				$template->assign('affiliates',bannermodule::listAffiliates());
				$template->assign('files',$bfiles);
				$template->assign('banners',$all);
			} else {
				$num = $viewconfig['number'];
				shuffle($all);
				$banners = array_slice($all,0,$num);
				
				for ($i = 0; $i < count($banners); $i++) {
					$banners[$i]->file = $db->selectObject('file','id='.$banners[$i]->file_id);
				}
				$template->assign('banners',$banners);
			}
		}
		$template->register_permissions(
			array('administrate','manage','manage_af'),
			$loc);
		$template->output();
	}
	
	function listAffiliates() {
		global $db;
		$affiliates = array();
		foreach ($db->selectObjects('banner_affiliate') as $af) {
			$affiliates[$af->id] = $af->name;
		}
		uasort($affiliates,'strnatcmp');
		return $affiliates;
	}
}

?>