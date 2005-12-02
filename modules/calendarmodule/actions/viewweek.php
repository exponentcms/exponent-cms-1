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

$template = new template("calendarmodule","_viewweek",$loc,false);

$time = (isset($_GET['time']) ? $_GET['time'] : time());

if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
$startweek = pathos_datetime_startOfWeekTimestamp($time);
$days = array();
$counts = array();
$startinfo = getdate($startweek);
for ($i = 0; $i < 7; $i++) {
	$start = mktime(0,0,0,$startinfo['mon'],$startinfo['mday']+$i,$startinfo['year']);
	$days[$start] = array();
	$dates = $db->selectObjects("eventdate","location_data='".serialize($loc)."' AND date = $start");
	for ($j = 0; $j < count($dates); $j++) {
		$o = $db->selectObject("calendar","id=".$dates[$j]->event_id);
		$thisloc = pathos_core_makeLocation($loc->mod,$loc->src,$o->id);
		$o->permissions = array(
			"administrate"=>(pathos_permissions_check("administrate",$thisloc) || pathos_permissions_check("administrate",$loc)),
			"edit"=>(pathos_permissions_check("edit",$thisloc) || pathos_permissions_check("edit",$loc)),
			"delete"=>(pathos_permissions_check("delete",$thisloc) || pathos_permissions_check("delete",$loc))
		);
		$o->eventdate = $dates[$j];
		$o->eventstart += $o->eventdate->date;
		$o->eventend += $o->eventdate->date;
		$days[$start][] = $o;
	}
	$counts[$start] = count($days[$start]);
}

$template->register_permissions(
	array("manage_approval"),
	$loc);

$template->assign("days",$days);
$template->assign("counts",$counts);
$template->assign("startweek",$startweek);
$template->assign("startnextweek",($startweek+(86400*9)));
$template->assign("startprevweek",($startweek-(86400*3)));
$template->output();

?>
