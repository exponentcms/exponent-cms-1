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

/**
 * View All Events for a Single Week
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Modules
 * @subpackage Calendar
 */
 
if (!defined("PATHOS")) exit("");

// PERM CHECK?
	pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

	$template = new template("calendarmodule","_viewweek",$loc,false);

	$time = (isset($_GET['time']) ? $_GET['time'] : time());

	if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
	$startweek = pathos_datetime_startOfWeekTimestamp($time);
	$days = array();
	$counts = array();
	for ($i = 0; $i < 7; $i++) {
		$start = $startweek + ($i*86400);
		$days[$start] = array();
		#$days[$start] = $db->selectObjects("calendar","location_data='".serialize($loc)."' AND (eventstart >= $start AND eventend <= " . ($start+86399) . ") AND approved!=0");
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
// END PERM CHECK?

?>
