<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

//GREP:UPLOADCHECK

if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
	pathos_lang_loadDictionary('modules','filemanager');
	switch($_FILES['file']['error']) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo TR_FILEMANAGER_FILETOOLARGE.'<br />';
			break;
		case UPLOAD_ERR_PARTIAL:
			echo TR_FILEMANAGER_PARTIALFILE.'<br />';
			break;
		case UPLOAD_ERR_NO_FILE:
			echo TR_FILEMANAGER_NOFILEUPLOADED.'<br />';
			break;
	}
} else {
	$basename = basename($_FILES['file']['name']);
	
	include_once(BASE.'external/Tar.php');
	$tar = new Archive_Tar($_FILES['file']['tmp_name'],'gz');
	
	$dest_dir = BASE.'tmp/'.uniqid('');
	@mkdir($dest_dir);
	if (!file_exists($dest_dir)) {
		echo 'Unable to create temporary directory to extract files archive.';
	} else {
		$return = $tar->extract($dest_dir);
		if (!$return) {
			echo '<br />Error extracting TAR archive<br />';
		} else if (!file_exists($dest_dir.'/files') || !is_dir($dest_dir.'/files')) {
			echo '<br />Invalid archive format<br />';
		} else {
			// Show the form for specifying which mod types to 'extract'
			
			$mods = array(); // Stores the mod classname, the files list, and the module's real name
			
			if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');
			
			$dh = opendir($dest_dir.'/files');
			while (($file = readdir($dh)) !== false) {
				if ($file{0} != '.' && is_dir($dest_dir.'/files/'.$file)) {
					$mods[$file] = array(
						'',
						array_keys(pathos_files_listFlat($dest_dir.'/files/'.$file,1,null,array(),$dest_dir.'/files/'.$file.'/'))
					);
					if (class_exists($file)) {
						$mods[$file][0] = call_user_func(array($file,'name')); // $file is the class name of the module
					}
				}
			}
			
			$template = new template('importer','_files_selectModList');
			$template->assign('dest_dir',$dest_dir);
			$template->assign('file_data',$mods);
			$template->output();
		}
	}
}
	
?>