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
# $Id: sort_images.php,v 1.4 2005/04/26 02:56:05 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = $db->selectObject("imagegallery_gallery","id=".$_POST['gid']);
if ($gallery) {
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;
	
	if (exponent_permissions_check("manage",$loc)) {
		if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");
		
		if (!function_exists($_POST['sorting']) || substr($_POST['sorting'],0,15) != "exponent_sorting_") {
			echo "No such sorting function";
		} else {
			$images = $db->selectObjects("imagegallery_image","gallery_id=".$_POST['gid']);
			usort($images,$_POST['sorting']);
			$rank = 0;
			foreach ($images as $i) {
				$i->rank = $rank;
				$db->updateObject($i,"imagegallery_image");
				$rank++;
			}
			
			exponent_flow_redirect();
		}
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>