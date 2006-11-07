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
# $Id: class.php,v 1.10 2005/05/09 06:01:04 filetreefrog Exp $
##################################################

class pagemodule {
	function name() { return 'Page Displayer'; }
	function description() { return 'Display files on the server, and optionally upload files to display.'; }
	function author() { return 'OIC Group, Inc.'; }
	
	function hasSources() { return true; }
	function hasContent() { return true; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		if ($internal == '') {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
			);
		} else {
			return array(
				'administrate'=>'Administrate',
				'configure'=>'Configure',
			);
		}
	}
	
	function show($view,$loc = null, $title = '') {
		global $db;
		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
		$config = $db->selectObject('pagemodule_config',"location_data='".serialize($loc)."'");
		if (!$config) {
			$config->file_id = 0;
			$config->filepath = ''; // Will always be absolute
			$config->handle_php = 0; // 0 for show, 1 for show highlighted, 2 for exec
		}
		
		$template = new template('pagemodule',$view,$loc);
		$template->assign('config',$config);
		
		if ($config && $config->file_id != 0) {
			$file = $db->selectObject('file','id='.$config->file_id);
			$config->filepath = BASE.$file->directory.'/'.$file->filename;
		}
		
		$directory = 'files/pagemodule/'.$loc->src;
		if (!file_exists(BASE.$directory)) {
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				exponent_lang_loadDictionary('modules','filemanager');
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		#if (!is_really_writable(BASE.$directory)) $template->assign("no_directory",1);
		#else $template->assign("no_directory",0);
		
		if ($config->file_id == 0 && !file_exists($config->filepath)) {
			$template->assign('not_configured',1);
		} else {
			$template->assign('not_configured',0);
			
			$output = '';
			if (substr($config->filepath,-4,4) == '.php') {
				if ($config->handle_php == 0) { // Show highlighted normally
					$output = highlight_file($config->filepath,true);
				} else { // Execute PHP code
					ob_start();
					include($config->filepath);
					$output = ob_get_contents();
					ob_end_clean();
				}
			} else $output = file_get_contents($config->filepath);
			$template->assign('output',$output);
		}
		
		$template->assign('moduletitle',$title);
		$template->register_permissions(
			array('administrate','configure'),
			$loc);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$config = $db->selectObject('pagemodule_config',"location_data='".serialize($loc)."'");
		if ($config) {
			if ($config->file_id != 0) {
				$file = $db->selectObject('file','id='.$config->file_id);
				if ($file) {
					file::delete($file);
					rmdir(BASE.$file->directory);
				}
			}
			$db->delete('pagemodule_config',"location_data='".serialize($loc)."'");
		}
	}
	
	function copyContent($oloc,$nloc) {
		global $db;
	
		$dir = "files/pagemodule/".$nloc->src;
		if (!file_exists(BASE.$dir)) {
			if (!defined("SYS_FILES")) require_once(BASE."subsystems/files.php");
			exponent_files_makeDirectory($dir);
		}
		
		if (!file_exists(BASE.$dir)) {
			return;
		}
		
		$config = $db->selectObject('pagemodule_config',"location_data='".serialize($oloc)."'");
		if ($config) {
			$file = $db->selectObject('file','id='.$config->file_id);
			if ($file) {
				copy(BASE.$file->directory.'/'.$file->filename,BASE.$dir.'/'.$file->filename);
				if (file_exists(BASE.$dir.'/'.$file->filename)) {
					$file->directory = $dir;
					unset($file->id);
					$config->file_id = $db->insertObject($file,'file');
					$config->location_data = serialize($nloc);
					$db->insertObject($config,'pagemodule_config');
				}
			} else {
				unset($config->id);
				$config->location_data = serialize($nloc);
				$db->insertObject($config,'pagemodule_config');
			}
		}
	}
	
	function spiderContent($item = null) {
		// FIXME:searching in page module.
		return false;
	}
}

?>