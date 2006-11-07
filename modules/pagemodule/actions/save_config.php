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
# $Id: save_config.php,v 1.3 2005/02/19 16:53:36 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check("configure",$loc)) {
	$config = $db->selectObject("pagemodule_config","location_data='".serialize($loc)."'");
	$newconfig = pagemodule_config::update($_POST,$config);
	$newconfig->location_data = serialize($loc);
	
	/* --- CHECK FOR EXISTENCE / READABILITY OF FILE
	if ($newconfig->filepath != "") {
		// Specified a file path.  Try to find it.
	}
	*/
	
	if ($config->file_id != 0 && $newconfig->filepath != "") {
		// Specified a file path, when we were using an uploaded file.
		$file = $db->selectObject("file","id=".$config->file_id);
		if (BASE.$file->directory."/".$file->filename == $newconfig->filepath) {
			$newconfig->filepath = "";
		} else {
			// Not the same.  Delete file object
			file::delete($file);
			$newconfig->file_id = 0;
		}
	}
	
	if (!isset($newconfig->id)) $db->insertObject($newconfig,"pagemodule_config");
	else $db->updateObject($newconfig,"pagemodule_config");
	
	exponent_flow_redirect();
}

?>