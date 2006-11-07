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
# $Id: view_image.php,v 1.4 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$image = null;
$gallery = null;
if (isset($_GET['id'])) {
	$image = $db->selectObject("imagegallery_image","id=".$_GET['id']);
	if ($image) {
		$gallery = $db->selectObject("imagegallery_gallery","id=".$image->gallery_id);
	}
}
	
if ($image && $gallery) {
	exponent_flow_set(SYS_FLOW_ACTION,SYS_FLOW_PUBLIC);

	$image->file = $db->selectObject("file","id=".$image->file_id);
	
	$loc = unserialize($gallery->location_data);
	
	$template = new template("imagegallerymodule","_view_image",$loc);
	$template->assign("image",$image);
	$template->assign("gallery",$gallery);
	$template->register_permissions(
		"manage",
		array($loc,exponent_core_makeLocation($loc->mod,$loc->src,$gallery->id))
	);
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>