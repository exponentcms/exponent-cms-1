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
# $Id: workshop.php,v 1.2 2005/04/26 03:05:14 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$template = new template('imageworkshopmodule','_workshop',$loc);
	
	if (!defined('SYS_FILES')) require_once(BASE.'subsystems/files.php');
	
	$directory = 'files/imageworkshopmodule/'.$loc->src;
	if (!file_exists(BASE.$directory)) {
		$err = exponent_files_makeDirectory($directory);
		if ($err != SYS_FILES_SUCCESS) {
			$template->assign('noupload',1);
			$template->assign('uploadError',$err);
		}
	}
	
	$images = $db->selectObjects('imageworkshop_image',"location_data='".serialize($loc)."'");
	
	if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	uasort($images,'exponent_sorting_byRankAscending');
	$template->assign('images',$images);
	
	$current = $db->selectObject('imageworkshop_image',"location_data='".serialize($loc)."' AND id=".$_GET['id']);
	if ($current) {
		$current->_file = $db->selectObject('file','id='.$current->file_id);
		
		if (!defined('SYS_IMAGE')) require_once(BASE.'subsystems/image.php');
		$info = exponent_image_sizeinfo(BASE.$current->_file->directory.'/'.$current->_file->filename);
		$current->_realname = substr($current->_file->filename,14);
		$current->_width = $info[0];
		$current->_height = $info[1];
#		$current->_imagetype = image_type_to_mime_type($info[2]);
		$current->_bitdepth = $info['bits'];
		$current->_filesize_bytes = filesize(BASE.$current->_file->directory.'/'.$current->_file->filename);
		$current->_filesize = exponent_files_bytesToHumanReadable($current->_filesize_bytes);
		
		$template->assign('current',$current);
		
		$tmp = $db->selectObject('imageworkshop_imagetmp','original_id='.$current->id);
		if ($tmp == null) {
			$tmp = $current;
			$template->assign('nochange',1);
		} else {
			$tmp->_file = $db->selectObject('file','id='.$tmp->file_id);
			
			$info = exponent_image_sizeinfo(BASE.$tmp->_file->directory.'/'.$tmp->_file->filename);
			$tmp->_realname = substr($current->_file->filename,14);
#			$tmp->_imagetype = image_type_to_mime_type($info[2]);
			$tmp->_width = $info[0];
			$tmp->_height = $info[1];
			$tmp->_bitdepth = $info['bits'];
			$tmp->_filesize_bytes = filesize(BASE.$tmp->_file->directory.'/'.$tmp->_file->filename);
			$tmp->_filesize = exponent_files_bytesToHumanReadable($tmp->_filesize_bytes);
			
			$template->assign('nochange',0);
			$template->assign('sizediff',round($tmp->_filesize_bytes / $current->_filesize_bytes * 100));
		}
		$template->assign('working',$tmp);
	}
	$template->output();
// END PERM CHECK

?> 
