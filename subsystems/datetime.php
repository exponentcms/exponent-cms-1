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
 * DateTime Subsystem
 *
 * The DateTime Subsystem exports functions for
 * managing and modifying dates in PHP.  This is mainly
 * provided as a service, since most of the algorithms in here
 * are simple and ubiquitous.
 *
 * @package		Subsystems
 * @subpackage	DateTime
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flasg for DateTime Subsystem
 *
 * The definition of this constant lets other parts
 * of the system know that the DateTime Subsystem
 * has been included for use.
 */
define("SYS_DATETIME",1);

/**
 * @deprecated ?
 */
function pathos_datetime_monthsDropdown($controlName,$default_month) {
	$months = array(
		1=>"January",
		2=>"February",
		3=>"March",
		4=>"April",
		5=>"May",
		6=>"June",
		7=>"July",
		8=>"August",
		9=>"September",
		10=>"October",
		11=>"November",
		12=>"December"
	);
	
	$html = '<select name="' . $controlName . '" size="1">';
	foreach ($months as $id=>$month) {
		$html .= '<option value="' . $id . '"';
		if ($id == $default_month) $html .= ' selected';
		$html .= '>' . $month . '</option>';
	}
	$html .= '</select>';
	return $html;
}

/**
 * Calculates Duration of Period
 *
 * Looks at a start and end time and figures out
 * how many seconds elapsed since between the earlier
 * timestamp and the later timestamp.  It doesn't matter
 * if the bigger argument is specified first or not.
 *
 * @param timestamp $time_a The first timestamp
 * @param timestamp $time_b The second timestamp
 * @return integer The number of seconds between $time_a and $time_b
 */
function pathos_datetime_duration($time_a,$time_b) {
	$d = abs($time_b-$time_a);
	$duration = array();
	if ($d >= 86400) {
		$duration['days'] = floor($d / 86400);
		$d %= 86400;
	}
	if (isset($duration['days']) || $d >= 3600) {
		if ($d) $duration['hours'] = floor($d / 3600);
		else $duration['hours'] = 0;
		$d %= 3600;
	}
	if (isset($duration['hours']) || $d >= 60) {
		if ($d) $duration['minutes'] = floor($d / 60);
		else $duration['minutes'] = 0;
		$d %= 60;
	}
	$duration['seconds'] = $d;
	return $duration;
}

#
# The following STILL NEED TO TAKE DST INTO ACCOUNT
#
/* Month Functions */
function pathos_datetime_startOfMonthTimestamp($timestamp) {
	$info = getdate($timestamp);
	return mktime(8,0,0,$info['mon'],1,$info['year']) - 8*3600;
}

function pathos_datetime_endOfMonthTimestamp($timestamp) {
	$info = getdate($timestamp);
	$info['mday'] = 28;
	while (checkdate($info['mon'],$info['mday']+1,$info['year'])) $info['mday']++;
	return mktime(8,0,0,$info['mon'],$info['mday'],$info['year']) - 8*3600;
}

/**
 * Return the last day of a month.
 *
 * Looks at a timestamp and returns the date of the last
 * day.  For instance, if the passed timestamp was in January,
 * this function would return 31.  Leap year is taken into account.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @return integer The last date of the month.
 */
function pathos_datetime_endOfMonthDay($timestamp) {
	$info = getdate($timestamp);
	$last = 28;
	while (checkdate($info['mon'],$last+1,$info['year'])) $last++;
	return $last;
}

/**
 * Return the timestamp for 12:00:01 am for any given day
 *
 * Looks at a timestamp and returns another timestamp representing
 * 12:00:01 am of the same day.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @return timestamp The timestamp corresponding to the first second of the day.
 */
function pathos_datetime_startOfDayTimestamp($timestamp) {
	$info = getdate($timestamp);
	return mktime(0,0,0,$info['mon'],$info['mday'],$info['year']);
}

/*
function pathos_datetime_endOfDayTimestamp($timestamp) {
	$info = getdate($timestamp);
	return mktime(23,59,59,$info['mon'],$info['mday'],$info['year']);
}

function pathos_datetime_makeDay($timestamp,$day) {
	$info = getdate($timestamp);
	return mktime(0,0,0,$info['mon'],$day,$info['year']);
}
*/

/* Week Functions */
/**
 * Returns the timestamp for 12:00:01 am Sunday, for any given week
 *
 * Looks at a timestamp and returns another timestamp representing
 * 12:00:01 am of the Sunday of the same week.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @return timestamp The timestamp corresponding to the first second of the week.
 */
function pathos_datetime_startOfWeekTimestamp($timestamp) {
	$info = getdate($timestamp);
	$firstOfWeek = $info['mday'] - $info['wday'];
	//FIXME WILL NOT WORK ON WINDOWS SERVERS
	return mktime(0,0,0,$info['mon'],$firstOfWeek,$info['year']);
}

// Recurring Dates

function pathos_datetime_recurringDailyDates($start,$end,$freq) {
	$dates = array();
	$curdate = $start;
	do {
		$dates[] = $curdate;
		$curdate += (86400 * $freq);
	} while ($curdate <= $end);
	return $dates;
}

function pathos_datetime_recurringWeeklyDates($start,$end,$freq,$days) {
	$dates = array();
	
	$dateinfo = getdate($start);
	for ($counter = 0; $counter < count($days); ) {// realign pointer
		if ($days[$counter] >= $dateinfo['wday']) break; // exit loop, we found it
		$counter++;
	}
	if ($days[$counter] < $dateinfo['wday']) {
		// in case we did MWF and started on a Saturday...
		$counter = 0; // reset to first day of next week
		$start += 86400 * (7-$dateinfo['wday']+$days[$counter]);
	} else if ($days[$counter] > $dateinfo['wday']) {
		// 'Normal' case, in which we started before one of the repeat days.
		$start += 86400 * ($days[$counter] - $dateinfo['wday']);
	}
	$curdate = $start;
	
	do {
		$dates[] = $curdate;
		
		$dateinfo = getdate($curdate);
		$day = $days[$counter];
		
		$counter++;
		if ($counter >= count($days)) {
			// Went off the end of the week.
			$counter = 0; // Reset the pointer to the beginning
			$daydiff = $days[count($days)-1]-$days[0];
			if ($daydiff == 0) $daydiff = 7;
			else $daydiff--;
			$curdate += 7 * 86400 * ($freq-1) + 86400 * $daydiff; // Increment by number of weeks
		} else {
			$curdate += 86400 * ($days[$counter] - $dateinfo['wday']);
		}
		$curdate = pathos_datetime_startOfDayTimestamp($curdate);
	} while ($curdate <= $end);
	
	return $dates;
}

function pathos_datetime_recurringMonthlyDates($start,$end,$freq,$by_day=false) {
	$dates = array();
	$curdate = $start;
	
	$week = 0; // Only used for $by_day;
	$wday = 0; // Only used for $by_day;
	if ($by_day) {
		$dateinfo = getdate($curdate);
		$startmonthinfo = getdate(pathos_datetime_startOfMonthTimestamp($curdate));
		$mday = $dateinfo['mday'] - ($startofmonth['wday'] ? 8 - $startmonthinfo['wday'] : 1);
		$week = floor($mday / 7);
		$wday = $dateinfo['wday'];
	}
	
	do {
		$dates[] = $curdate;
		
		$dateinfo = getdate($curdate);
		
		$curdate = pathos_datetime_startOfMonthTimestamp(mktime(8,0,0,$dateinfo['mon']+$freq,$dateinfo['mday'],$dateinfo['year']));
		if ($by_day) {
			$startmonthinfo = getdate(pathos_datetime_startOfMonthTimestamp($curdate));
			$mwday = $startmonthinfo['wday'] ? 8 - $startmonthinfo['wday'] : 1;
			$mday = $mwday + ( 7 * $week ) + $wday;
			$curdate = pathos_datetime_startOfDayTimestamp(mktime(8,0,0,$dateinfo['mon']+$freq,$mday,$dateinfo['year']));
		}
	} while ($curdate <= $end);
	
	return $dates;
}

function pathos_datetime_recurringYearlyDates($start,$end,$freq) {
	return pathos_datetime_recurringMonthlyDates($start,$end,$freq*12);
}

?>