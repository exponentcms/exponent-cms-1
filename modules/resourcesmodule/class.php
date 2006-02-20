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

class resourcesmodule {
	function name() { return pathos_lang_loadKey('modules/resourcesmodule/class.php','module_name'); }
	function author() { return 'James Hunt'; }
	function description() { return pathos_lang_loadKey('modules/resourcesmodule/class.php','module_description'); }
	
	function hasContent() { return true; }
	function hasViews() { return true; }
	function hasSources() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = pathos_lang_loadFile('modules/resourcesmodule/class.php');
		if ($internal == '') {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'post'=>$i18n['perm_post'],
				'edit'=>$i18n['perm_edit'],
				'delete'=>$i18n['perm_delete'],
				'manage_approval'=>$i18n['perm_manage_approval']
			);
		} else {
			return array(
				'administrate'=>$i18n['perm_administrate'],
				'edit'=>$i18n['perm_editone'],
				'delete'=>$i18n['perm_deleteone'],
				'manage_approval'=>$i18n['perm_manage_approval']
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') {
			return array($loc);
		} else {
			return array($loc,pathos_core_makeLocation($loc->mod,$loc->src));
		}
	}
	
	function show($view,$loc,$title = '') {
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		
		$template = new template('resourcesmodule',$view,$loc);
		
		$directory = 'files/resourcesmodule/' . $loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = pathos_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		global $db;
		
		$resources = $db->selectObjects('resourceitem',"location_data='".serialize($loc)."'");
		$iloc = pathos_core_makeLocation($loc->mod,$loc->src);
		for ($i = 0; $i < count($resources); $i++) {
			$iloc->int = $resources[$i]->id;
			$resources[$i]->permissions = array(
				'administrate'=>pathos_permissions_check('administrate',$iloc),
				'edit'=>pathos_permissions_check('edit',$iloc),
				'delete'=>pathos_permissions_check('delete',$iloc),
			);
		}
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		usort($resources,'pathos_sorting_byRankAscending');
		
		$rfiles = array();
		foreach ($db->selectObjects('file',"directory='$directory'") as $file) {
			$file->mimetype = $db->selectObject('mimetype',"mimetype='".$file->mimetype."'");
			$rfiles[$file->id] = $file;
		}
		
		$template->assign('moduletitle',$title);
		$template->assign('resources',$resources);
		$template->assign('files',$rfiles);
		
		$template->register_permissions(
			array('administrate','configure','post','edit','delete'),
			$loc);
		
		$template->output($view);
		
	}
	
	function deleteIn($loc) {
		global $db;
		foreach($db->selectObjects('resourceitem',"location_data='".serialize($loc)."'") as $res) {
			foreach ($db->selectObjects('resourceitem_wf_revision','wf_original='.$res->id) as $wf_res) {
				$file = $db->selectObject('file','id='.$wf_res->file_id);
				file::delete($file);
				$db->delete('file','id='.$file->id);
			}
			$db->delete('resourceitem_wf_revision','wf_original='.$res->id);
		}
		rmdir(BASE.'files/resourcesmodule/'.$loc->src);
		$db->delete('resourceitem',"location_data='".serialize($loc)."'");
	}
	
	function copyContent($oloc,$nloc) {
		if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
		$directory = 'files/resourcesmodule/'.$nloc->src;
		if (!file_exists(BASE.$directory) && pathos_files_makeDirectory($directory) != SYS_FILES_SUCCESS) {
			return;
		}
		
		global $db;
		foreach ($db->selectObjects('resourceitem',"location_data='".serialize($oloc)."'") as $r) {
			$file = $db->selectObject('file','id='.$r->file_id);
			
			copy($file->directory.'/'.$file->filename,$directory.'/'.$file->filename);
			$file->directory = $directory;
			unset($file->id);
			$file->id = $db->insertObject($file,'file');
			
			$r->location_data = serialize($nloc);
			$r->file_id = $file->id;
			unset($r->id);
			$db->insertObject($r,'resourceitem');
		}
	}
	
	function spiderContent($item = null) {
		$i18n = pathos_lang_loadFile('modules/resourcesmodule/class.php');
		
		global $db;
		
		if (!defined('SYS_SEARCH')) require_once(BASE.'subsystems/search.php');
		
		$search = null;
		$search->category = $i18n['search_category'];
		$search->ref_module = 'resourcesmodule';
		$search->ref_type = 'resourceitem';
		
		if ($item) {
			$db->delete('search',"ref_module='resourcesmodule' AND ref_type='resourceitem' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = ' ' . pathos_search_removeHTML($item->description) . ' ';
			$search->title = ' ' . $item->name . ' ';
			$search->location_data = $item->location_data;
			$search->view_link = 'index.php?module=resourcesmodule&action=view&id='.$item->id;
			$db->insertObject($search,'search');
		} else {
			$db->delete('search',"ref_module='resourcesmodule' AND ref_type='resourceitem'");
			foreach ($db->selectObjects('resourceitem') as $item) {
				$search->original_id = $item->id;
				$search->body = ' ' . pathos_search_removeHTML($item->description) . ' ';
				$search->title = ' ' . $item->name . ' ';
				$search->location_data = $item->location_data;
				$search->view_link = 'index.php?module=resourcesmodule&action=view&id='.$item->id;
				$db->insertObject($search,'search');
			}
		}
		
		return true;
	}
}

?>