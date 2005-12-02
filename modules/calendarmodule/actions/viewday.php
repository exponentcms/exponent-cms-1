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
 
if (!defined("PATHOS")) exit("");

pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

$time = (isset($_GET['time']) ? $_GET['time'] : time());
$info = getdate($time);
$start = mktime(0,0,0,$info['mon'],$info['mday'],$info['year']);

$template = new template("calendarmodule","_viewday",$loc,false,$loc);

$dates = $db->selectObjects("eventdate","location_data='".serialize($loc)."' AND date = $start");
$events = array();
foreach ($dates as $d) {
	$o = $db->selectObject("calendar","id=".$d->event_id);
	$o->eventstart += $d->date;
	$o->eventend += $d->date;
	$o->eventdate = $d;
	$thisloc = pathos_core_makeLocation($loc->mod,$loc->src,$o->id);
	$o->permissions = array(
		"administrate"=>(pathos_permissions_check("administrate",$thisloc) || pathos_permissions_check("administrate",$loc)),
		"edit"=>(pathos_permissions_check("edit",$thisloc) || pathos_permissions_check("edit",$loc)),
		"delete"=>(pathos_permissions_check("delete",$thisloc) || pathos_permissions_check("delete",$loc))
	);
	$events[] = $o;
}

$template->register_permissions(
	array("manage_approval"),
	$loc);

$template->assign("events",$events);
$template->assign("now",$time);
$template->assign("nextday",$time+86400);
$template->assign("prevday",$time-86400);

$template->output();

?>