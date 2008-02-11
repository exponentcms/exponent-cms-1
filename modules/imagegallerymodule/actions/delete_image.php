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
# $Id: delete_image.php,v 1.5 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$image = null;
if (isset($_GET['id'])) $image = $db->selectObject("imagegallery_image","id=".$_GET['id']);

if ($image) {
	$gallery = $db->selectObject("imagegallery_gallery","id=".$image->gallery_id);
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;

	if (exponent_permissions_check("manage",$loc)) {
		$file = $db->selectObject('file','id='.$image->file_id);
		file::delete($file);
		$file->filename = $image->thumbnail;
		file::delete($file);
		$file->filename = $image->enlarged;
		file::delete($file);
		$db->delete('file','id='.$image->file_id);
		$db->delete("imagegallery_image","id=".$image->id);
		$db->decrement('imagegallery_image','rank',1,'gallery_id='.$image->gallery_id.' AND rank > ' . $image->rank);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else echo SITE_404_HTML;

?>
