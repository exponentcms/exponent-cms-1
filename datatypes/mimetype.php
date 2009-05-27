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

//TODO: bring back icon selector, this time based on the filebrowser engine
class mimetype {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/mimetype.php');
		
		$form = new form();
		if (!isset($object->mimetype)) {
			$object->mimetype = '';
			$object->name = '';
			$object->icon = '';
		} else {
			$form->meta('oldmime',$object->mimetype);
		}
		
		$form->register('mimetype',$i18n['mimetype'], new textcontrol($object->mimetype));
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		
		$icodir = MIMEICON_RELATIVE;
		$htmlimg = ($object->icon == '' ? '' : '<img src="'.MIMEICON_RELATIVE.$object->icon.'"/>');

		if($object->icon != '')
			$form->register(null,null,new htmlcontrol($htmlimg));

		$form->register('icon',$i18n['icon'], new uploadcontrol());

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->mimetype = $values['mimetype'];
		$object->name = $values['name'];
		// temp fix
		$object->icon = "binary.png";

		$i18n = exponent_lang_loadFile('datatypes/file.php');

		if (!defined('SYS_FILES')) include_once(BASE.'subsystems/files.php');

		$dest = MIMEICON_RELATIVE;
		$filename = $_FILES['icon']['name'];
		// General error message.  This will be made more explicit later on.
		$err = sprintf($i18n['cant_upload'],$filename) .'<br />';
		
		switch($_FILES['icon']['error']) {
			case UPLOAD_ERR_OK:
				// Everything looks good.  Continue with the update.
				break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				// This is a tricky one to catch.  If the file is too large for POST, then the script won't even run.
				// But if its between post_max_size and upload_file_max_size, we will get here.
				return $err.$i18n['file_too_large'];
			case UPLOAD_ERR_PARTIAL:
				return $err.$i18n['partial_file'];
			case UPLOAD_ERR_NO_FILE:
				return $err.$i18n['no_file_uploaded'];
			default:
				return $err.$i18n['unknown'];
				break;
		}
		
		// Fix the filename, so that we don't have funky characters screwing with out attempt to create the destination file.
		$filename = exponent_files_fixName($filename);
	
			
		if (file_exists(BASE.$dest.'/'.$filename) && $force == false) {
			return $err.$i18n['file_exists'];
		}
	
		//Check to see if the directory exists.  If not, create the directory structure.
		if (!file_exists(BASE.$dest)) {
			exponent_files_makeDirectory($dest);
		}	

		// Move the temporary uploaded file into the destination directory, and change the name.
		exponent_files_moveUploadedFile($_FILES['icon']['tmp_name'],BASE.$dest.'/'.$filename);
		
		if (!file_exists(BASE.$dest.'/'.$filename)) {
			return $err.$i18n['cant_move'];
		}
		
		$object->icon = $filename;
		
		return $object;
	}
}

?>