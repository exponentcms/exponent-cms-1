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

$item = null;
$iloc = null;
if (isset($_POST['id'])) {
	$item = $db->selectObject("calendar","id=".$_POST['id']);
	$loc = unserialize($item->location_data);
	$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$item->id);
}

if (($item == null && pathos_permissions_check("post",$loc)) ||
	($item != null && pathos_permissions_check("edit",$loc)) ||
	($iloc != null && pathos_permissions_check("edit",$iloc))
) {
	$item = calendar::update($_POST,$item);
	$item->location_data = serialize($loc);
	
	if (isset($_POST['category'])) $item->category_id = $_POST['category'];
	else $item->category_id = 0;
	
	if (!defined("SYS_WORKFLOW")) include_once(BASE."subsystems/workflow.php");
	
	if (isset($item->id)) {
		if ($item->is_recurring) {
			// For recurring events, check some stuff.
			// Were all dates selected?
			$eventdates = $db->selectObjectsIndexedArray("eventdate","event_id=".$item->id);
			if (count($_POST['dates']) == count($eventdates)) {
				// yes.  just update the original
				$db->updateObject($item,"calendar");
			} else {
				// No, create new and relink affected dates
				$olditem = $db->selectObject("calendar","id=".$item->id);
				unset($item->id);
				
				$item->id = $db->insertObject($item,"calendar");
				
				foreach (array_keys($_POST['dates']) as $date_id) {
					if (isset($eventdates[$date_id])) {
						$eventdates[$date_id]->event_id = $item->id;
						$db->updateObject($eventdates[$date_id],"eventdate");
					}
				}
			}
			pathos_flow_redirect();
		} else {
			$item->approved = 1;
			$db->updateObject($item,"calendar");
			
			pathos_flow_redirect();
		}
	} else {
		if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		$start_recur = pathos_datetime_startOfDayTimestamp(popupdatetimecontrol::parseData("eventdate",$_POST));
		$stop_recur  = pathos_datetime_startOfDayTimestamp(popupdatetimecontrol::parseData("untildate",$_POST));
		pathos_forms_cleanup();
		if ($_POST['recur'] != "recur_none") {
			// Do recurrence
			$freq = $_POST['recur_freq_'.$_POST['recur']];
			
			echo $_POST['recur'] . "<br />";
			
			switch ($_POST['recur']) {
				case "recur_daily":
					$dates = pathos_datetime_recurringDailyDates($start_recur,$stop_recur,$freq);
					break;
				case "recur_weekly":
					$dates = pathos_datetime_recurringWeeklyDates($start_recur,$stop_recur,$freq,(isset($_POST['day']) ? array_keys($_POST['day']) : array($dateinfo['wday'])));
					break;
				case "recur_monthly":
					$dates = pathos_datetime_recurringMonthlyDates($start_recur,$stop_recur,$freq,$_POST['month_type']);
					break;
				case "recur_yearly":
					$dates = pathos_datetime_recurringYearlyDates($start_recur,$stop_recur,$freq);
					break;
				default:
					$dates = array();
					echo "Bad type: " . $_POST['recur'] . "<br />";
					return;
					break;
			}
			
			$item->is_recurring = 1; // Set the recurrence flag.
		} else {
			$dates = array($start_recur);
		}
		
		$item->approved = 1; // Bypass workflow.
		
		$edate = null;
		$edate->event_id = $db->insertObject($item,"calendar");
		$edate->location_data = $item->location_data;
		foreach ($dates as $d) {
			$edate->date = $d;
			$db->insertObject($edate,"eventdate");
		}
		pathos_flow_redirect();
		
		pathos_forms_cleanup();
	}
} else {
	echo SITE_403_HTML;
}

?>