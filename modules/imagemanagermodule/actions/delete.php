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

/**
 * Delete an Image
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage ImageManager
 */

if (!defined("PATHOS")) exit("");

$item = $db->selectObject("imagemanageritem","id=".$_GET['id']);
if ($item != null) {
	$loc = unserialize($item->location_data);
	
	if (pathos_permissions_check("delete",$loc)) {
		$file = $db->selectObject("file","id=".$item->file_id);
		
		if (isset($_GET['force']) || ($file != null && file::delete($file))) {
			$db->delete("file","id=".$file->id);
			$db->delete("imagemanageritem","id=".$item->id);
			pathos_flow_redirect();
		} else {
			echo "Could not delete actual file.  Click <a class='mngmntlink imagemanager_mngmntlink' href='".$_SERVER['REQUEST_URI']."&force'>here</a>. to delete this reference.";
		}
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>