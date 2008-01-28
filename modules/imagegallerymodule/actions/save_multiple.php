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
# $Id: save_multiple.php,v 1.7 2005/04/08 22:57:52 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = null;
if (isset($_POST['gid'])) {
	$gallery = $db->selectObject("imagegallery_gallery","id=".$_POST['gid']);
	if ($gallery) {
		$loc = unserialize($gallery->location_data);
	}
}

if ($gallery) {

	if (exponent_permissions_check('manage',$loc)) {
		$image = null;
		$image->gallery_id = $_POST['gid'];
		$image->newwindow = 1;
		
		$directory = "files/imagegallerymodule/".$loc->src."/gallery".$gallery->id;
		
		$firstrank = $db->max('imagegallery_image','rank','gallery_id','gallery_id='.$gallery->id);
		if ($firstrank == null) $firstrank = -1; // It will be incremented
		
		$success = true;
		for ($i = 0; $i < $_POST['count']; $i++) {
			$firstrank++;
			$image->rank = $firstrank;
			$image->name = $_POST["name$i"];
			$image->alt = $_POST["alt$i"];
			
			if ($_FILES["file$i"]['tmp_name'] != "") {
				$file = file::update("file$i",$directory,null);

				if ($file == null) {
					$success = false;
					continue;
				}
				
				$image->file_id = $db->insertObject($file,"file");
				
				$thumbname = imagegallerymodule::createThumbnailFile($file, $gallery->box_size); 
				$image->thumbnail = $thumbname; 
				$popname = imagegallerymodule::createEnlargedFile($file, $gallery->pop_size); 
				$image->enlarged = $popname; 
				
				$db->insertObject($image,"imagegallery_image");
			}
		}
		
		if ($success) {
			exponent_flow_redirect();
		}
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
