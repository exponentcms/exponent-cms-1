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
//GREP:HARDCODEDTEXT
class file {
	function update($name,$dest,$object,$destname = null) {
	
		pathos_lang_loadDictionary('modules','file');
		
		$object->mimetype = $_FILES[$name]['type'];
		
		if ($destname == null) $object->filename = $_FILES[$name]['name'];
		else $object->filename = $destname;
		
		if (file_exists(BASE.$dest."/".$object->filename)) {
			echo TR_FILE_FILE . $object->filename . TR_FILE_ALREADYEXISTS;
			return null;
		}
		if (!defined("SYS_FILES")) include_once(BASE."subsystems/files.php");
		pathos_files_moveUploadedFile($_FILES[$name]['tmp_name'],BASE.$dest."/".$object->filename);
		if (!file_exists(BASE.$dest."/".$object->filename)) {
			echo TR_FILE_UNABLETOUPLOAD . $object->filename . ".";
			return null;
		}
		
		
		$object->directory = $dest;
		$object->accesscount = 0;
		$object->filesize = $_FILES[$name]['size'];
		$object->posted = time();
		global $user;
		if ($user) $object->poster = $user->id;
		$object->last_accessed = time();
		
		return $object;
	}
	
	function delete($file) {
		if ($file == null) return true;
		
		if (is_readable(BASE.$file->directory) && !file_exists(BASE.$file->directory."/".$file->filename)) return true;
		
		if (is_writeable(BASE.$file->directory)) {
			unlink($file->directory."/".$file->filename);
			if (!file_exists(BASE.$file->directory."/".$file->filename)) return true;
		}
		return false;
	}
}

?>