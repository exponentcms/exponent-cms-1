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

if (!defined("PATHOS")) exit("");

$item = $db->selectObject("imagemanageritem","id=".intval($_GET['id']));
if ($item != null) {
	$loc = unserialize($item->location_data);
	
	if (pathos_permissions_check("delete",$loc)) {
		$file = $db->selectObject("file","id=".$item->file_id);
		
		$db->delete("file","id=".$file->id);
		$db->delete("imagemanageritem","id=".$item->id);
		pathos_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>