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

class calendarmodule {
	function name() { return "Calendar"; }
	function author() { return "OIC Group Exponent Team / Greg Otte"; }
	function description() { return "Allows posting of content to a calendar."; }
	
	function hasContent() { return true; }
	function hasSources() { return true; }
	function hasViews()   { return true; }
	
	function supportsWorkflow() { return true; }
	
	function permissions($internal = '') {
		pathos_lang_loadDictionary('modules','calendarmodule');
		if ($internal == '') {
			return array(
				'administrate'=>TR_CALENDARMODULE_PERM_ADMIN,
				'configure'=>TR_CALENDARMODULE_PERM_CONFIG,
				'post'=>TR_CALENDARMODULE_PERM_POST,
				'edit'=>TR_CALENDARMODULE_PERM_EDIT,
				'delete'=>TR_CALENDARMODULE_PERM_DELETE,
				'approve'=>TR_CALENDARMODULE_PERM_APPROVE,
				'manage_approval'=>TR_CALENDARMODULE_PERM_MANAGEAP,
				'manage_categories'=>'Manage Categories'
			);
		} else {
			return array(
				'administrate'=>TR_CALENDARMODULE_PERM_ADMIN,
				'edit'=>TR_CALENDARMODULE_PERM_EDIT,
				'delete'=>TR_CALENDARMODULE_PERM_DELETE
			);
		}
	}
	
	function getLocationHierarchy($loc) {
		if ($loc->int == '') return array($loc);
		else return array($loc,pathos_core_makelocation($loc->mod,$loc->src));
	}
	
	function show($view,$loc = null, $title = '') {
		global $user;
		global $db;
		
		$template = new template('calendarmodule',$view,$loc);
		$template->assign('moduletitle',$title);
		
		$canviewapproval = false;
		$inapproval = false;
		
		global $user;
		if ($user) $canviewapproval = (pathos_permissions_check("approve",$loc) || pathos_permissions_check("manage_approval",$loc));
		if ($db->countObjects("calendar","location_data='".serialize($loc)."' AND approved!=1")) {
			foreach ($db->selectObjects("calendar","location_data='".serialize($loc)."' AND approved!=1") as $c) {
				if ($c->poster == $user->id) $canviewapproval = true;
			}
			$inapproval = true;
		}
		
		$time = (isset($_GET['time']) ? $_GET['time'] : time());
		$template->assign("time",$time);
		
		$viewconfig = $template->viewparams;
		if ($viewconfig === null) {
			$viewconfig = array("type"=>"default");
		}
		
		if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
		if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
		
		if (!function_exists("pathos_sorting_byEventStartAscending")) {
			function pathos_sorting_byEventStartAscending($a,$b) {
				return ($a->eventstart < $b->eventstart ? -1 : 1);
			}
		}
		
		if ($viewconfig['type'] == "minical") {
			$monthly = array();
			$info = getdate(time());
			$info = getdate(time());
			// Grab non-day numbers only (before end of month)
			$week = 0;
			$currentweek = 0;
			$currentday = $info['mday'];
			
			$infofirst = getdate(mktime(12,0,0,$info['mon'],1,$info['year']));
			
			if ($infofirst['wday'] == 0) $monthly[$week] = array(); // initialize for non days
			for ($i = 0 - $infofirst['wday']; $i < 0; $i++) {
				$monthly[$week][$i] = array("number"=>-1,"ts"=>-1);
			}
			$weekday = $infofirst['wday']; // day number in grid.  if 7+, switch weeks
			// Grab day counts
			$endofmonth = pathos_datetime_endOfMonthDay(time());
			for ($i = 1; $i <= $endofmonth; $i++) {
				$start = mktime(0,0,0,$info['mon'],$i,$info['year']);
				if ($i == $info['mday']) $currentweek = $week;
				#$monthly[$week][$i] = array("ts"=>$start,"number"=>$db->countObjects("calendar","location_data='".serialize($loc)."' AND approved!=0 AND (eventstart >= $start AND eventend <= " . ($start+86399) . ")"));
				// NO WORKFLOW CONSIDERATIONS
				$monthly[$week][$i] = array("ts"=>$start,"number"=>$db->countObjects("eventdate","location_data='".serialize($loc)."' AND date = $start"));
				if ($weekday >= 6) {
					$week++;
					$monthly[$week] = array(); // allocate an array for the next week
					$weekday = 0;
				} else $weekday++;
			}
			// Grab non-day numbers only (after end of month)
			for ($i = 1; $weekday && $i <= (7-$weekday); $i++) $monthly[$week][$i+$endofmonth] = -1;
			
			$template->assign("monthly",$monthly);
			$template->assign("currentweek",$currentweek);
			$template->assign("currentday",$currentday);
			$template->assign("now",time());
		} else if ($viewconfig['type'] == "byday") {
			$startperiod = 0;
			$totaldays = 0;
			if ($viewconfig['range'] == "week") {
				$startperiod = pathos_datetime_startOfWeekTimestamp($time);
				$totaldays = 7;
			} else {
				$startperiod = pathos_datetime_startOfMonthTimestamp($time);
				$totaldays = pathos_datetime_endOfMonthDay($time);
			}
			
			$template->assign("prev_timestamp",$startperiod - 3600);
			$template->assign("next_timestamp",$startperiod+$totaldays*86400 + 3600);
			
			$days = array();
			for ($i = 0; $i < $totaldays; $i++) {
				$start = $startperiod + ($i*86400);
				#$days[$start] = $db->selectObjects("calendar","location_data='".serialize($loc)."' AND (eventstart >= $start AND eventend <= " . ($start+86399) . ") AND approved!=0");
				$edates = $db->selectObjects("eventdate","location_data='".serialize($loc)."' AND date = $start");
				$days[$start] = calendarmodule::_getEventsForDates($edates);
				for ($j = 0; $j < count($days[$start]); $j++) {
					$thisloc = pathos_core_makeLocation($loc->mod,$loc->src,$days[$start][$j]->id);
					$days[$start][$j]->permissions = array(
						"administrate"=>(pathos_permissions_check("administrate",$thisloc) || pathos_permissions_check("administrate",$loc)),
						"edit"=>(pathos_permissions_check("edit",$thisloc) || pathos_permissions_check("edit",$loc)),
						"delete"=>(pathos_permissions_check("delete",$thisloc) || pathos_permissions_check("delete",$loc))
					);
				}
				usort($days[$start],"pathos_sorting_byEventStartAscending");
			}
			$template->assign("days",$days);
		} else if ($viewconfig['type'] == "monthly") {
			$monthly = array();
			$counts = array();
			
			$info = getdate($time);
			$nowinfo = getdate(time());
			if ($info['mon'] != $nowinfo['mon']) $nowinfo['mday'] = -10;
			// Grab non-day numbers only (before end of month)
			$week = 0;
			$currentweek = -1;
			
			$timefirst = mktime(12,0,0,$info['mon'],1,$info['year']);
			$infofirst = getdate($timefirst);
			
			if ($infofirst['wday'] == 0) {
				$monthly[$week] = array(); // initialize for non days
				$counts[$week] = array();
			}
			for ($i = 1 - $infofirst['wday']; $i < 1; $i++) {
				$monthly[$week][$i] = array();
				$counts[$week][$i] = -1;
			}
			$weekday = $infofirst['wday']; // day number in grid.  if 7+, switch weeks
			// Grab day counts
			$endofmonth = pathos_datetime_endOfMonthDay($time);
			
			for ($i = 1; $i <= $endofmonth; $i++) {
				$start = mktime(0,0,0,$info['mon'],$i,$info['year']);
				if ($i == $nowinfo['mday']) $currentweek = $week;
				#$monthly[$week][$i] = $db->selectObjects("calendar","location_data='".serialize($loc)."' AND (eventstart >= $start AND eventend <= " . ($start+86399) . ") AND approved!=0");
				$dates = $db->selectObjects("eventdate","location_data='".serialize($loc)."' AND date = $start");
				$monthly[$week][$i] = calendarmodule::_getEventsForDates($dates);
				
				$counts[$week][$i] = count($monthly[$week][$i]);
				if ($weekday >= 6) {
					$week++;
					$monthly[$week] = array(); // allocate an array for the next week
					$counts[$week] = array();
					$weekday = 0;
				} else $weekday++;
			}
			// Grab non-day numbers only (after end of month)
			for ($i = 1; $weekday && $i < (8-$weekday); $i++) {
				$monthly[$week][$i+$endofmonth] = array();
				$counts[$week][$i+$endofmonth] = -1;
			}
			
			$template->assign("currentweek",$currentweek);
			$template->assign("monthly",$monthly);
			$template->assign("counts",$counts);
			$template->assign("nextmonth",$timefirst+(86400*45));
			$template->assign("prevmonth",$timefirst-(86400*15));
			$template->assign("now",$timefirst);
		} else if ($viewconfig['type'] == "administration") {
			// Check perms and return if cant view
			if ($viewconfig['type'] == "administration" && !$user) return;
			
			$continue = (	pathos_permissions_check("administrate",$loc) ||
					pathos_permissions_check("post",$loc) ||
					pathos_permissions_check("edit",$loc) ||
					pathos_permissions_check("delete",$loc) ||
					pathos_permissions_check("approve",$loc) ||
					pathos_permissions_check("manage_approval",$loc)
				) ? 1 : 0;
			$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "'");
			$items = calendarmodule::_getEventsForDates($dates);
			if (!$continue) {
				foreach ($items as $i) {
					$iloc = pathos_core_makeLocation($loc->mod,$loc->src,$i->id);
					if (	pathos_permissions_check("edit",$iloc) ||
						pathos_permissions_check("delete",$iloc) ||
						pathos_permissions_check("administrate",$iloc)
					) {
						$continue = true;
					}
				}
			}
			if (!$continue) return;
			
			for ($i = 0; $i < count($items); $i++) {
				$thisloc = pathos_core_makeLocation($loc->mod,$loc->src,$items[$i]->id);
				if ($user && $items[$i]->poster == $user->id) $canviewapproval = 1;
				$items[$i]->permissions = array(
					"administrate"=>(pathos_permissions_check("administrate",$thisloc) || pathos_permissions_check("administrate",$loc)),
					"edit"=>(pathos_permissions_check("edit",$thisloc) || pathos_permissions_check("edit",$loc)),
					"delete"=>(pathos_permissions_check("delete",$thisloc) || pathos_permissions_check("delete",$loc))
				);
			}
			usort($items,"pathos_sorting_byEventStartAscending");
			
			$template->assign("items",$items);
		} else if ($viewconfig['type'] == "default") {
			if (!isset($viewconfig['range'])) $viewconfig['range'] = "all";
			
			$limit = '';
			if (isset($template->viewconfig) && isset($template->viewconfig['num_events']) && $template->viewconfig['num_events'] != 0) {
				$limit = $db->limit($template->viewconfig['num_events'],0);
			}
			$items = null;
			$dates = null;
			$day = pathos_datetime_startOfDayTimestamp(time());
			$sort_asc = true; // For the getEventsForDates call
			switch ($viewconfig['range']) {
				case "all":
					#$items = $db->selectObjects("calendar","location_data='" . serialize($loc) . "' AND approved!=0");
					$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "'");
					break;
				case "upcoming":
					#$items = $db->selectObjects("calendar","location_data='" . serialize($loc) . "' AND approved!=0 AND eventstart >= ".time());
					$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "' AND date > $day ORDER BY date ASC ".$limit);
					break;
				case "past":
					#$items = $db->selectObjects("calendar","location_data='" . serialize($loc) . "' AND approved!=0 AND eventstart < ".time());
					$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "' AND date < $day ORDER BY date DESC ".$limit);
					$sort_asc = false;
					break;
				case "today":
					#$items = $db->selectObjects("calendar","location_data='" . serialize($loc) . "' AND approved!=0 AND eventstart >= ".pathos_datetime_startOfDayTimestamp(time()) . " AND eventend <= " . (pathos_datetime_startOfDayTimestamp(time()) + 86400));
					$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "' AND date = $day");
					break;
				case "next":
					#$items = array($db->selectObject("calendar","location_data='" . serialize($loc) . "' AND approved!=0 AND eventstart >= ".time()));
					$dates = array($db->selectObject("eventdate","location_data='" . serialize($loc) . "' AND date >= $day"));
					break;
				case "month":
					#$items = $db->selectObjects("calendar","location_data='" . serialize($loc) . "' AND approved!=0 AND eventstart >= ".pathos_datetime_startOfMonthTimestamp(time()) . " AND eventend <= " . pathos_datetime_endOfMonthTimestamp(time()));
					$dates = $db->selectObjects("eventdate","location_data='" . serialize($loc) . "' AND date >= ".pathos_datetime_startOfMonthTimestamp(time()) . " AND date <= " . pathos_datetime_endOfMonthTimestamp(time()));
					break;
			}
			$items = calendarmodule::_getEventsForDates($dates,$sort_asc);
			
			for ($i = 0; $i < count($items); $i++) {
				$thisloc = pathos_core_makeLocation($loc->mod,$loc->src,$items[$i]->id);
				if ($user && $items[$i]->poster == $user->id) $canviewapproval = 1;
				$items[$i]->permissions = array(
					'administrate'=>(pathos_permissions_check('administrate',$thisloc) || pathos_permissions_check('administrate',$loc)),
					'edit'=>(pathos_permissions_check('edit',$thisloc) || pathos_permissions_check('edit',$loc)),
					'delete'=>(pathos_permissions_check('delete',$thisloc) || pathos_permissions_check('delete',$loc))
				);
			}
			
			$template->assign('items',$items);
		}
		
		$template->assign('in_approval',$inapproval);
		$template->assign('canview_approval_link',$canviewapproval);
		$template->register_permissions(
			array('administrate','configure','post','edit','delete','manage_approval','manage_categories'),
			$loc
		);
		
		$cats = $db->selectObjectsIndexedArray("category","location_data='".serialize($loc)."'");
		$cats[0] = null;
		$cats[0]->name = "<i>No category</i>";
		$cats[0]->color = "#000000";
		$template->assign("categories",$cats);
		
		$config = $db->selectObject("calendarmodule_config","location_data='".serialize($loc)."'");
		
		if (!$config) {
			$config->enable_categories = 0;
		}
		$template->assign("modconfig",$config);
		
		$template->output();
	}
	
	function deleteIn($loc) {
		global $db;
		$items = $db->selectObjects("calendar","location_data='".serialize($loc)."'");
		foreach ($items as $i) {
			$db->delete("calendar_wf_revision","wf_original=".$i->id);
			$db->delete("calendar_wf_info","real_id=".$i->id);
		}
		$db->delete("calendar","location_data='".serialize($loc)."'");
	}
	
	function spiderContent($item = null) {
		global $db;
		
		if (!defined("SYS_SEARCH")) include_once(BASE."subsystems/search.php");
		
		$search = null;
		$search->category = 'Events';
		$search->view_link = ''; // FIXME : need a view action
		$search->ref_module = 'calendarmodule';
		$search->ref_type = 'calendar';
		
		if ($item) {
			$db->delete("search","ref_module='calendarmodule' AND ref_type='calendar' AND original_id=" . $item->id);
			$search->original_id = $item->id;
			$search->body = " " . pathos_search_removeHTML($item->body) . " ";
			$search->title = " " . $item->title . " ";
			$search->location_data = $item->location_data;
			$db->insertObject($search,"search");
		} else {
			$db->delete("search","ref_module='calendarmodule' AND ref_type='calendar'");
			foreach ($db->selectObjects("calendar") as $item) {
				$search->original_id = $item->id;
				$search->body = " " . pathos_search_removeHTML($item->body) . " ";
				$search->title = " " . $item->title . " ";
				$search->location_data = $item->location_data;
				$db->insertObject($search,"search");
			}
		}
		
		return true;
	}
	
	// The following functions are internal helper functions
	
	function _getEventsForDates($edates,$sort_asc = true) {
		if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
		if ($sort_asc && !function_exists('pathos_sorting_byEventStartAscending')) {
			function pathos_sorting_byEventStartAscending($a,$b) {
				return ($a->eventstart < $b->eventstart ? 1 : -1);
			}
		}
		if (!$sort_asc && !function_exists('pathos_sorting_byEventStartDescending')) {
			function pathos_sorting_byEventStartDescending($a,$b) {
				return ($a->eventstart < $b->eventstart ? 1 : -1);
			}
		}
		
		global $db;
		$events = array();
		foreach ($edates as $edate) {
			$o = $db->selectObject("calendar","id=".$edate->event_id);
			$o->eventdate = $edate;
			$o->eventstart += $edate->date;
			$o->eventend += $edate->date;
			$events[] = $o;
		}
		if ($sort_asc == true) {
			usort($events,'pathos_sorting_byEventStartAscending');
		} else {
			usort($events,'pathos_sorting_byEventStartDescending');
		}
		return $events;
	}
}

?>