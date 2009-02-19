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
# $Id: delete_gallery.php,v 1.4 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = null;
if (isset($_GET['id'])) {
	$gallery = $db->selectObject("imagegallery_gallery","id=".$_GET['id']);
}

if ($gallery) {
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;

	if (exponent_permissions_check("delete",$loc)) {
		$gallery->images = array();
		$gallery->images = $db->selectObjects('imagegallery_image', 'gallery_id='.$gallery->id);
		for ($y = 0; $y < count($gallery->images); $y++) {
			$image = $gallery->images[$y];
			$file = $db->selectObject('file','id='.$image->file_id);
			file::delete($file);
			$file->filename = $image->thumbnail;
			file::delete($file);
			$file->filename = $image->enlarged;
			file::delete($file);
			$db->delete('file','id='.$image->file_id);
			$db->delete("imagegallery_image","id=".$image->id);
		}
		rmdir(BASE.'files/imagegallerymodule/'.$loc->src.'/gallery'.$gallery->id);
		$db->delete("imagegallery_gallery","id=".$gallery->id);
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else echo SITE_404_HTML;

?>