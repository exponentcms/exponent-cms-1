<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");

$time = (isset($_GET['month']) ? $_GET['month'] : time());
$start_month = pathos_datetime_startOfMonthTimestamp($time);
$end_month = pathos_datetime_endOfMonthTimestamp($time);

$where = "location_data='".serialize($loc)."' AND posted >= $start_month AND posted <= $end_month";
if (!pathos_permissions_check("view_private",$loc)) $where .= " AND is_private = 0";

$config = $db->selectObject("weblogmodule_config","location_data='".serialize($loc)."'");
if ($config == null) $config->allow_comments = true;

$posts = $db->selectObjects("weblog_post",$where);
if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
for ($i = 0; $i < count($posts); $i++) {
	$ploc = pathos_core_makeLocation($loc->mod,$loc->src,$posts[$i]->id);
	
	$posts[$i]->permissions = array(
		"administrate"=>pathos_permissions_check("administrate",$ploc),
		"edit"=>pathos_permissions_check("edit",$ploc),
		"delete"=>pathos_permissions_check("delete",$ploc),
		"comment"=>pathos_permissions_check("comment",$ploc),
		"edit_comments"=>pathos_permissions_check("edit_comments",$ploc),
		"delete_comments"=>pathos_permissions_check("delete_comments",$ploc),
		"view_private"=>pathos_permissions_check("view_private",$ploc),
	);
	$comments = $db->selectObjects("weblog_comment","parent_id=".$posts[$i]->id);
	usort($comments,"pathos_sorting_byPostedDescending");
	$posts[$i]->comments = $comments;
}
if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
usort($posts,"pathos_sorting_byPostedDescending");
			
$template = new template("weblogmodule","_view_month",$loc);
$template->assign("posts",$posts);
$template->register_permissions(
	array("administrate"/*,"configure"*/,"post","edit","delete","comment","edit_comments","delete_comments","view_private"),
	$loc);
$template->assign("config",$config);
$template->output();

?>