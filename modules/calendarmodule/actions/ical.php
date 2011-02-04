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

	if (isset($_GET['date_id'])) {  // get specific event only
		$dates = array($db->selectObject("eventdate","id=".intval($_GET['date_id'])));
		$Filename = "Event-" . $_GET['date_id'];
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

		if (!function_exists("exponent_datetime_startOfDayTimestamp")) {
			if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");               
		}		
		$day = exponent_datetime_startOfDayTimestamp(time());
		if ($config->rss_limit > 0) {
			$rsslimit = " AND date <= " . ($day + ($config->rss_limit * 86400));
		} else {
			$rsslimit = "";
		}
		
		if (isset($_GET['time'])) {
			$time = $_GET['time'];  // get current month's events
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfMonthTimestamp($time)." AND date <= ".exponent_datetime_endOfMonthTimestamp($time));
		} else {
			$time = date('U',strtotime("midnight -1 month",time()));  // previous month also
			$dates = $db->selectObjects("eventdate",$locsql." AND date >= ".exponent_datetime_startOfDayTimestamp($time).$rsslimit);
		}
		$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");
		$Filename = preg_replace('/\s+/','',$title);  // without whitespace
	}	

	$search = array ('/"/',
					 '/,/',
					 '/\n/',
					 '/\r/',
					 '/:/',
					 '/;/',
					 '/\\//');                    // evaluate as php

	$replace = array ('\"',
					 '\\,',
					 '\\n',
					 '',
					 '\:',
					 '\\;',
					 '\\\\');

//	$tz = "America/New_York";
	$tz = DISPLAY_DEFAULT_TIMEZONE;
	$msg = "BEGIN:VCALENDAR\n";
	$msg .= "VERSION:2.0\n";
	$msg .= "CALSCALE:GREGORIAN\n";
	$msg .= "METHOD: PUBLISH\n";  
	$msg .= "PRODID:<-//ExponentCMS//EN>\n";
	$msg .= "X-PUBLISHED-TTL:PT".$config->rss_cachetime."M\n";
	$msg .= "X-WR-CALNAME:$Filename\n";
	
	$items = calendarmodule::_getEventsForDates($dates);

	for ($i = 0; $i < count($items); $i++) {

		$eventstart = new DateTime(date('r',$items[$i]->eventstart),new DateTimeZone($tz));
		$eventstart->setTimezone(new DateTimeZone('GMT')); 
		$eventend = new DateTime(date('r',$items[$i]->eventend),new DateTimeZone($tz));
		$eventend->setTimezone(new DateTimeZone('GMT')); 
		if ($items[$i]->is_allday) {
			$dtstart = "DTSTART;VALUE=DATE:" . date("Ymd", $items[$i]->eventstart) . "\n";			
			$dtend = "DTEND;VALUE=DATE:" . date("Ymd", strtotime("midnight +1 day",$items[$i]->eventstart)) . "\n";			
		} else {
			$dtstart = "DTSTART;VALUE=DATE-TIME:" . $eventstart->format("Ymd\THi00") . "Z\n";
			if($items[$i]->eventend) {
				$dtend = "DTEND;VALUE=DATE-TIME:" . $eventend->format("Ymd\THi00") . "Z\n";
			} else {
				$dtend = "DTEND;VALUE=DATE-TIME:" . $eventstart->format("Ymd\THi00") . "Z\n";
			}
		}
		// remove all formatting from body text
//		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>","\r","\n"),"\r\n",$items[$i]->body)));
//		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\r",$items[$i]->body)));
//		$body = str_replace(array("\r","\n"), "=0D=0A=", $body);
		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>","</p>"), "\n",$items[$i]->body)));
		$body = str_replace(array("\r"), "", $body);
		$body = str_replace(array("&#160;"), " ", $body);
		$body = str_replace(array("\n"), "=0D=0A", $body);

		// $body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\r",$items[$i]->body)));
		// $body = preg_replace($search, $replace, $body);
		// $body = wordwrap($body);
		// $body = str_replace("\n","\n  ",$body);

//		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\r",$items[$i]->body)));
//		$body = chop(strip_tags(str_replace(array("<br />","<br>","br/>"),"\n",$items[$i]->body)));
//		$body = str_replace(array("\r","\n"), "=0D=0A=", $body);
//		$body = str_replace(array("\r"), "=0D=0A=", $body);
//		$body = str_replace(array("\r","\n"), "\r\n", $body);

		$title = $items[$i]->title;

		$msg .= "BEGIN:VEVENT\n";
		$msg .= $dtstart . $dtend;
		$msg .= "UID:" . $items[$i]->eventdate->id . "\n";
		$msg .= "DTSTAMP:" . date("Ymd\THis", time()) . "Z\n";
		if($title) { $msg .= "SUMMARY:$title\n";}
		if($body) { $msg .= "DESCRIPTION;ENCODING=QUOTED-PRINTABLE:$body\n";}
//		if($body) { $msg .= "DESCRIPTION:$body\n";}
	//	if($link_url) { $msg .= "URL: $link_url\n";}
	//	if ($topic_id) { $msg .= "CATEGORIES: APPOINTMENT;$topic[$topic_id]\n";}
		$msg .= "END:VEVENT\n";      
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
//	header("Content-Disposition: inline; filename=".$Filename.".ics");
	header('Content-Disposition: attachment; filename="' . $Filename . '.ics"');
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
