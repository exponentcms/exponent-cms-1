<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

//exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);
// id & date_id set if single event, else
//   src & time (opt?) set for longer list/month, etc...
if (isset($_GET['date_id']) || isset($_GET['src'])) {
	if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");

	if (isset($_GET['date_id'])) {
		$dates = array($db->selectObject("eventdate","id=".intval($_GET['date_id'])));
		$Filename = "Event" . $_GET['date_id'] . ".ics";
	} else {
		$loc = exponent_core_makeLocation('calendarmodule',$_GET['src'],'');
		$locsql = "(location_data='".serialize($loc)."'";
		$config = $db->selectObject("calendarmodule_config","location_data='".serialize($loc)."'");
		if (!empty($config->aggregate)) {
			$locations = unserialize($config->aggregate);
			foreach ($locations as $source) {
				$tmploc = null;
				$tmploc->mod = 'calendarmodule';
				$tmploc->src = $source;
				$tmploc->int = '';
				$locsql .= " OR location_data='".serialize($tmploc)."'";
			}
		}
		$locsql .= ')';
		if (isset($_GET['time'])) {
			$time = $_GET['time'];
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfMonthTimestamp($time) . " AND date <= " . exponent_datetime_endOfMonthTimestamp($time));
		} else {
//			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfDayTimestamp(time()) . " AND date <= " . exponent_datetime_endOfMonthTimestamp(time()));
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfDayTimestamp(time()));
		}
		$Filename = "Events" . $_GET['src'] . ".ics";
	}	

	ob_end_clean();

	$tz = date('O',time());
//	$tz = 0;
	$msg = "BEGIN:VCALENDAR\015\012";
	$msg .= "METHOD:PUBLISH\015\012";  
	$msg .= "PRODID:-//ExponentCMS//EN\015\012";
	$msg .= "VERSION:" . "0.98.1" . "\015\012";
	$msg .= "TZ:$tz\015\012";
	
	$items = calendarmodule::_getEventsForDates($dates);

	for ($i = 0; $i < count($items); $i++) {

		//FJD - Goofy-ass daylight savings time hack.  Should be improved at some point.
		//need to do some comparisons on the timestamp and value returned from strftime and adjust accordingly up or down 
		//to correct output.  This will still cause one display bug: if your times are within an hour of the change in one
		//direction, it will display incorrectly.  
		//US does the switch at 2AM, European union at 1AM.
		
		//get interger for hours from eventstart and end divided by 3600, then
		//get interger for hour of time returned from strtime, which should take DST from locale into consideration, 
		//(so our data should be portable).  If they are off, then create the adjustment +/- and correct
		//eventstart and eventend
		// $timeHourStart =  intval($items[$i]->eventstart / 3600);
		// $strHourStart = intval(strftime("%H", $eventdate->date + $items[$i]->eventstart));
		// $timeHourEnd =  intval($items[$i]->eventend / 3600);
		// $strHourEnd = intval(strftime("%H", $eventdate->date + $items[$i]->eventend));
		
		// $adjustStart = (($timeHourStart - $strHourStart) * 3600); //could be + or - or 0 (most of the time);
		// $adjustEnd = (($timeHourEnd - $strHourEnd) * 3600); //could be + or - or 0 (most of the time);
		
		// $items[$i]->eventstart += ($eventdate->date + $adjustStart); 
		// $items[$i]->eventend += ($eventdate->date + $adjustEnd); 
	//	$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
			
		$dtstart = date("Ymd\THi00", $items[$i]->eventstart);
		$dtend = date("Ymd\THi00", $items[$i]->eventend);
		// remove all formatting from body text
		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\r",$items[$i]->body)));
		$body = str_replace("\r", "=0D=0A=", $body);
		$title = $items[$i]->title;

		$msg .= "BEGIN:VEVENT\015\012";
		$msg .= "DTSTART:$dtstart\015\012";
		if($items[$i]->eventend) { $msg .= "DTEND:$dtend\015\012";}
		if($title) { $msg .= "SUMMARY:$title\015\012";}
		if($body) { $msg .= "DESCRIPTION;ENCODING=QUOTED-PRINTABLE: $body\015\012";}
	//	if($link_url) { $msg .= "URL:$link_url\015\012";}
	//	if ($topic_id) { $msg .= "CATEGORIES:APPOINTMENT;$topic[$topic_id]\015\012";}
		$msg .= "END:VEVENT\015\012";      
	}
	$msg .= "END:VCALENDAR\015\012";			

	// Kick it out as a file download
	header("Content-Type: text/x-vCalendar");
	header("Content-length: ".strlen($msg));
	header("Content-Disposition: inline; filename=$Filename");
	echo $msg;
	exit();
} else {
	echo SITE_404_HTML;
}

?>
