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
# $Id: edit_image.php,v 1.4 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$image = null;
if (isset($_GET['id'])) {
	$image = $db->selectObject("imagegallery_image","id=".$_GET['id']);
}
if (!$image) {
	$image->gallery_id = $_GET['gid'];
}

$gallery = $db->selectObject("imagegallery_gallery","id=".$image->gallery_id);

if ($gallery) {
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;
	
	if (exponent_permissions_check("manage",$loc)) {
		$form = imagegallery_image::form($image);
		$form->location($loc);
		$form->meta("action","save_image");
		
		$template = new template("imagegallerymodule","_form_editimage",$loc);
		$template->assign("is_edit",(isset($image->id) ? 1 : 0));
		$template->assign("form_html",$form->toHTML());
		$template->output();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>