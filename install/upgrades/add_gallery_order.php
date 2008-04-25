<?php
##################################################
#
# Copyright (c) 2007 OIC Group, Inc.
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

class add_gallery_order extends upgradescript {
	protected $from_version = '0.96.3';
    protected $to_version = '0.97.0';

	function name() {
		return "Ordering Image Galleries";
	}

	function upgrade() {
		global $db;
		$galleries = $db->selectObjects('imagegallery_gallery');
        foreach ($galleries as $gallery) {
            if (empty($gallery->galleryorder)) {
                $gallery->galleryorder = $db->countObjects('imagegallery_gallery',"location_data='".$gallery->location_data."' AND galleryorder !=''") + 1;
                $db->updateObject($gallery,'imagegallery_gallery');
            }
        }
        return "Complete";
	}
}

?>
