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

class htmltemplatemodule {
	function name() { return "HTML Template Editor"; }
	function description() { return "Allows you to create, upload and edit HTML templates, which can be hooked into text controls."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return false; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','htmltemplatemodule');
		return array(
			'administrate'=>TR_IMAGEMANAGERMODULE_PERM_ADMIN,
			'create'=>TR_IMAGEMANAGERMODULE_PERM_POST,
			'edit'=>TR_IMAGEMANAGERMODULE_PERM_EDIT,
			'delete'=>TR_IMAGEMANAGERMODULE_PERM_DELETE
		);
	}
	
	function show($view,$loc = null,$title = '') {
		if (
			pathos_permissions_check('administrate',$loc) ||
			pathos_permissions_check('create',$loc) ||
			pathos_permissions_check('edit',$loc) ||
			pathos_permissions_check('delete',$loc)
		) {
			$template = new template('htmltemplatemodule',$view);
			
			if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
			$directory = 'files/htmltemplatemodule/' . $loc->src;
			if (!file_exists(BASE.$directory)) {
				switch(pathos_files_makeDirectory($directory)) {
					case SYS_FILES_FOUNDFILE:
						$template->assign('noupload',1);
						$template->assign('uploadError','Found a file in the directory path.');
						break;
					case SYS_FILES_NOTWRITABLE:
						$template->assign('noupload',1);
						$template->assign('uploadError','Unable to create directory to store files in.');
						break;
				}
			}
			
			global $db;
			$templates = $db->selectObjects('htmltemplate');
			for ($i = 0; $i < count($templates); $i++) {
				$assocs = $db->selectObjects('htmltemplateassociation','template_id='.$templates[$i]->id);
				if (count($assocs) == 1 && $assocs[0]->global) $templates[$i]->global_assoc = 1;
				else {
					$templates[$i]->global_assoc = 0;
					$templates[$i]->associations = $assocs;
				}
			}
			
			$template->assign('moduletitle',$title);
			$template->assign('templates',$templates);
			$template->register_permissions(
				array('administrate','create','edit','delete'),
				pathos_core_makeLocation('htmltemplatemodule'));
			
			$template->assign('linkbase','?module=htmltemplatemodule&action=');
			
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
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
}

?>