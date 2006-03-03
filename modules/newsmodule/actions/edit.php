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

$news = null;
$iloc = null;
if (isset($_GET['id'])) {
	$news = $db->selectObject("newsitem","id=" . intval($_GET['id']));
}

if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
}

if (($news != null && exponent_permissions_check("edit_item",$loc)) || 
	($news == null && exponent_permissions_check("add_item",$loc)) ||
	($iloc != null   && exponent_permissions_check("edit_item",$iloc)) 
) {
	$form = newsitem::form($news);
	$form->location($loc);
	$form->meta("action","save");
	
	$template = new template("newsmodule","_form_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit", (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>