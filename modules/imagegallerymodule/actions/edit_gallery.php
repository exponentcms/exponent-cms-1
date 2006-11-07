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
# $Id: edit_gallery.php,v 1.4 2005/02/24 20:14:14 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

$gallery = null;
if (isset($_GET['id'])) {
	$gallery = $db->selectObject("imagegallery_gallery","id=".$_GET['id']);
	$loc = unserialize($gallery->location_data);
	$loc->int = $gallery->id;
}

if (exponent_permissions_check("edit",$loc)) {
	$form = imagegallery_gallery::form($gallery);
	$form->location($loc);
	$form->meta("action","save_gallery");
	
	$template = new template("imagegallerymodule","_form_editgallery",$loc);
	$template->assign("is_edit",(isset($gallery->id) ? 1 : 0));
	$template->assign("form_html",$form->toHTML());
	$template->output();
}

?>