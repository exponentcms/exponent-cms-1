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

class htmltemplatemodule {
	function name() { return exponent_lang_loadKey('modules/htmltemplatemodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/htmltemplatemodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/htmltemplatemodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'create'=>$i18n['perm_create'],
			'edit'=>$i18n['perm_edit'],
			'delete'=>$i18n['perm_delete']
		);
	}
	
	function show($view,$loc = null,$title = '') {
		if (
			exponent_permissions_check('administrate',$loc) ||
			exponent_permissions_check('create',$loc) ||
			exponent_permissions_check('edit',$loc) ||
			exponent_permissions_check('delete',$loc)
		) {
			$template = new template('htmltemplatemodule',$view,$loc);
			
			$template->assign('noupload',0);
			$template->assign('uploadError','');
				
			if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
			$directory = 'files/htmltemplatemodule/' . $loc->src;
			if (!file_exists(BASE.$directory)) {
				$err = exponent_files_makeDirectory($directory);
				if ($err != SYS_FILES_SUCCESS) {
					$template->assign('noupload',1);
					$template->assign('uploadError',$err);
				}
			}
			
			global $db;
			$templates = $db->selectObjects('htmltemplate');
			for ($i = 0; $i < count($templates); $i++) {
				$assocs = $db->selectObjects('htmltemplateassociation','template_id='.$templates[$i]->id);
				if (count($assocs) == 1 && $assocs[0]->global == 1) {
					$templates[$i]->global_assoc = 1;
				} else {
					$templates[$i]->global_assoc = 0;
					$templates[$i]->associations = $assocs;
				}
			}
			
			$template->assign('moduletitle',$title);
			$template->assign('templates',$templates);
			$template->register_permissions(
				array('administrate','create','edit','delete'),
				exponent_core_makeLocation('htmltemplatemodule'));
			
			$template->output();
		}
	}
	
	function deleteIn($loc) {
		global $db;
	
		$db->delete('htmltemplate');
		$db->delete('htmltemplateassociation');
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
}

?>