<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id$
##################################################

if (!defined("PATHOS")) exit("");

$item = null;
if (isset($_GET['id'])) {
	$item = $db->selectObject("imagemanageritem","id=".$_GET['id']);
	$loc = unserialize($item->location_data);
}

if (	($item == null && pathos_permissions_check("post",$loc)) ||
	($item != null && pathos_permissions_check("edit",$loc))
) {
	$form = imagemanageritem::form($item);
	$form->location($loc);
	$form->meta("action","save");
	
	$template = new template("imagemanagermodule","_form_edit",$loc);
	
	$directory = BASE."files/imagemanagermodule/".$loc->src;
	if (!isset($item->id) && !is_writable($directory)) {
		$template->assign("dir_not_writable",1);
		$form->controls['submit']->disabled = 1;
	} else {
		$template->assign("dir_not_writable",0);
	}
	
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit",isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>