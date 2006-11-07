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
# $Id: class.php,v 1.2 2005/04/26 03:03:42 filetreefrog Exp $
##################################################

class imageworkshopmodule {
	function name() { return 'Image Workshop'; }
	function description() { return 'Allows users to scale and rotate images on the website.'; }
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
		
		$template = new template('imageworkshopmodule',$view,$loc);
		$template->assign('moduletitle',$title);
		
		$directory = 'files/imageworkshopmodule/'.$loc->src;
		if (!file_exists(BASE.$directory)) {
			if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
			$err = exponent_files_makeDirectory($directory);
			if ($err != SYS_FILES_SUCCESS) {
				$template->assign('noupload',1);
				$template->assign('uploadError',$err);
			}
		}
		
		$images = $db->selectObjects('imageworkshop_image',"location_data='".serialize($loc)."'");
		
		if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
		usort($images,'exponent_sorting_byRankAscending');
		
		$template->assign('images',$images);
		$template->output();
	}
	
	function deleteIn($loc) {
		// Do nothing, no content
	}
	
	function copyContent($from_loc,$to_loc) {
		// Do nothing, no content
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
	//
	function createWorkingCopy($original,$file) {
		global $db;
	
		$working = null;
		$working->original_id = $original->id;
		$working->location_data = $original->location_data;
		
		$working_file = null;
		$working_file->directory = 'tmp';
		$working_file->filename = uniqid('imageworkshop').'_'.$file->filename;
		copy(BASE.$file->directory.'/'.$file->filename,BASE.$working_file->directory.'/'.$working_file->filename);
		
		$working->file_id = $db->insertObject($working_file,'file');
		
		$working->id = $db->insertObject($working,'imageworkshop_imagetmp');
		
		$working->_file = $working_file;
		return $working;
	}
}

?>