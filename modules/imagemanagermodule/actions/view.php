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

if (!defined("EXPONENT")) exit("");

$item = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject("imagemanageritem","id=".intval($_GET['id']));
}

if ($item) {
	$loc = unserialize($item->location_data);
	
	$template = new template("imagemanagermodule","_view",$loc);
	
	$file = $db->selectObject("file","id=".$item->file_id);
    
    if ($file->is_image == 1 && 
        $file->image_width > 0 && 
        $file->image_height > 0) {
            
        	$template->assign("item",$item);
	        $template->assign("file",$file);
	        $template->output();
    } else {
       echo SITE_404_HTML; 
    }
} else {
	echo SITE_404_HTML;
}

?>
