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


		$image = json_decode($_POST['galobject']);
		$gallery = $db->selectObject("imagegallery_gallery","id=".$image->gallery_id);
		
		$file = $db->selectObject("file","id=".$image->file_id);
		
		$thumbname = imagegallerymodule::createThumbnailFile($file, $gallery->box_size); 
		$image->thumbnail = $thumbname; 
		$popname = imagegallerymodule::createEnlargedFile($file, $gallery->pop_size); 
		$image->enlarged = $popname; 
		
		$db->updateObject($image,"imagegallery_image");
		

		exit();
		


?>
