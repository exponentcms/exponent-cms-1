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

$hasperm = (exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("view_unpublished",$loc));

$time = time();

$nloc = exponent_core_makeLocation($loc->mod,$loc->src);

$expired = array();
foreach ($db->selectObjects("newsitem","location_data='".serialize($loc)."' AND unpublish != 0 AND unpublish < $time") as $news) {
	$nloc->int = $news->id;
	$news->permissions = array(
		"edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$nloc)) ? 1 : 0),
		"delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$nloc)) ? 1 : 0),
		"administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$nloc)) ? 1 : 0)
	);
	$news->difference = $time - $news->unpublish;
	
	if (!$hasperm) $hasperm = ($news->permissions["edit_item"] || $news->permissions["delete_item"]);
	$expired[] = $news;
}

$unpub = array();
foreach ($db->selectObjects("newsitem","location_data='".serialize($loc)."' AND publish != 0 AND publish > $time") as $news) {
	$nloc->int = $news->id;
	$news->permissions = array(
		"edit_item"=>((exponent_permissions_check("edit_item",$loc) || exponent_permissions_check("edit_item",$nloc)) ? 1 : 0),
		"delete_item"=>((exponent_permissions_check("delete_item",$loc) || exponent_permissions_check("delete_item",$nloc)) ? 1 : 0),
		"administrate"=>((exponent_permissions_check("administrate",$loc) || exponent_permissions_check("administrate",$nloc)) ? 1 : 0)
	);
	$news->difference = $news->publish - $time;
	
	if (!$hasperm) $hasperm = ($news->permissions["edit_item"] || $news->permissions["delete_item"]);
	$unpub[] = $news;
}

if ($hasperm) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	$template = new template("newsmodule","_view_unpublished",$loc);
	$template->assign("expired",$expired);
	$template->assign("unpublished",$unpub);
	$template->register_permissions(
		array("edit_item","delete_item"),
		$loc);
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>