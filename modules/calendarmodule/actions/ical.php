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
		
		if ($config->rss_limit > 0) {
			$rsslimit = $db->limit($config->rss_limit,0);
		} else {
			$rsslimit = "";
		}
		
		if (isset($_GET['time'])) {
			$time = $_GET['time'];
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfMonthTimestamp($time)." AND date <= ".exponent_datetime_endOfMonthTimestamp($time));
		} else {
//			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfDayTimestamp(time()) . " AND date <= " . exponent_datetime_endOfMonthTimestamp(time()));
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfDayTimestamp(time()).$rsslimit);
		}
//		$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
		$Filename = "Events" . $_GET['src'] . ".ics";
	}	

//	$tz = date('O',time());
//	$tz = substr($tz,0,3).":".substr($tz,3,2);
//	$tz = "America/New_York";
	$tz = DISPLAY_DEFAULT_TIMEZONE;
	$msg = "BEGIN:VCALENDAR\015\012";
	$msg .= "VERSION:2.0\015\012";
	$msg .= "CALSCALE:GREGORIAN\015\012";
	$msg .= "METHOD: PUBLISH\015\012";  
	$msg .= "PRODID:<-//ExponentCMS//EN>\015\012";
	$msg .= "X-PUBLISHED-TTL:PT".$config->rss_cachetime."M\015\012";
	$msg .= "X-WR-CALNAME:$Filename\015\012";
//	$msg .= "TZ: $tz\015\012";
	$msg .= "X-WR-TIMEZONE:$tz\015\012";
	$msg .= "BEGIN:VTIMEZONE\015\012";
	$msg .= "TZID:$tz\015\012";
	$msg .= "X-LIC-LOCATION:$tz\015\012";
	$msg .= "BEGIN:DAYLIGHT\015\012";
	$msg .= "TZOFFSETFROM:-0500\015\012";
	$msg .= "TZOFFSETTO:-0400\015\012";
	$msg .= "TZNAME:EDT\015\012";
	$msg .= "DTSTART:19700308T020000\015\012";
	$msg .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU\015\012";
	$msg .= "END:DAYLIGHT\015\012";
	$msg .= "BEGIN:STANDARD\015\012";
	$msg .= "TZOFFSETFROM:-0400\015\012";
	$msg .= "TZOFFSETTO:-0500\015\012";
	$msg .= "TZNAME:EST\015\012";
	$msg .= "DTSTART:19701101T020000\015\012";
	$msg .= "RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU\015\012";
	$msg .= "END:STANDARD\015\012";
	$msg .= "END:VTIMEZONE\015\012";
	
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

//		$dtend = "";
		if ($items[$i]->is_allday) {
			$dtstart = "DTSTART;VALUE=DATE:" . date("Ymd", $items[$i]->eventstart) . "\015\012";			
			$dtend = "DTEND;VALUE=DATE:" . date("Ymd", strtotime("midnight +1 day",$items[$i]->eventstart)) . "\015\012";			
//			$dtstart = "DTSTART;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd", $items[$i]->eventstart) . "T000000\015\012";
//			$dtend = "DTEND;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd", $items[$i]->eventstart) . "T235959\015\012";
//			$dtend = "DTEND;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd", strtotime("midnight +1 day",$items[$i]->eventstart)) . "T000000\015\012";
		} else {
			$dtstart = "DTSTART;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd\THi00", $items[$i]->eventstart) . "\015\012";
			if($items[$i]->eventend) {
				$dtend = "DTEND;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd\THi00", $items[$i]->eventend) . "\015\012";
			} else {
				$dtend = "DTEND;TZID=$tz;VALUE=DATE-TIME:" . date("Ymd\THi00", $items[$i]->eventstart) . "\015\012";
			}
		}
		// remove all formatting from body text
		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\r",$items[$i]->body)));
		$body = str_replace(array("\r","\n"), "=0D=0A=", $body);
		$title = $items[$i]->title;

		$msg .= "BEGIN:VEVENT\015\012";
		$msg .= $dtstart . $dtend;
		$msg .= "UID:" . $items[$i]->eventdate->id . "\015\012";
		$msg .= "DTSTAMP:" . date("Ymd\THis", time()) . "Z\015\012";
		if($title) { $msg .= "SUMMARY:$title\015\012";}
		if($body) { $msg .= "DESCRIPTION;ENCODING=QUOTED-PRINTABLE:$body\015\012";}
	//	if($link_url) { $msg .= "URL: $link_url\015\012";}
	//	if ($topic_id) { $msg .= "CATEGORIES: APPOINTMENT;$topic[$topic_id]\015\012";}
		$msg .= "END:VEVENT\015\012";      
	}
	$msg .= "END:VCALENDAR";			

	// Kick it out as a file download
	ob_end_clean();

//	$mime_type = (EXPONENT_USER_BROWSER == 'IE' || EXPONENT_USER_BROWSER == 'OPERA') ? 'application/octet-stream;' : "text/x-vCalendar";
//	$mime_type = "text/x-vCalendar";
	$mime_type = "text/Calendar";
	header('Content-Type: ' . $mime_type);
	header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header("Content-length: ".strlen($msg));
	header('Content-Transfer-Encoding: binary');
	header('Content-Encoding:');
//	header("Content-Disposition: inline; filename=$Filename");
	header('Content-Disposition: attachment; filename="' . $Filename . '"');
	// IE need specific headers
//	if (EXPONENT_USER_BROWSER == 'IE') {
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: public');
		header('Vary: User-Agent');
//	} else {
		header('Pragma: no-cache');
//	}
	echo $msg;
	exit();
} else {
	echo SITE_404_HTML;
}

?>
