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

exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
$news = $db->selectObject("newsitem","id=" . intval($_GET['id']));
if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
	
	$news->permissions = array(
		"edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$iloc)) ? 1 : 0),
		"delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$iloc)) ? 1 : 0),
		"administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$iloc)) ? 1 : 0)
	);
	
	
	$news->real_posted = ($news->publish != 0 ? $news->publish : $news->posted);
	
	$view = (isset($_GET['view']) ? $_GET['view'] : "_viewSingle");
	$template = new template("newsmodule",$view,$loc);
	
	$template->assign("newsitem",$news);
	$template->assign("loc",$loc);
	
	$template->output();
} else {
	echo SITE_404_HTML;
}

?>