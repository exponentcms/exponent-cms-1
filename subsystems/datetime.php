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

/* exdoc
 * The definition of this constant lets other parts
 * of the system know that the DateTime Subsystem
 * has been included for use.
 * @node Subsystems:DateTime
 */
define("SYS_DATETIME",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
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

/* exdoc
 * Looks at a start and end time and figures out
 * how many seconds elapsed since between the earlier
 * timestamp and the later timestamp.  It doesn't matter
 * if the bigger argument is specified first or not. Returns
 * the number of seconds between $time_a and $time_b
 *
 * @param timestamp $time_a The first timestamp
 * @param timestamp $time_b The second timestamp
 * @node Subsystems:DateTime
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

/* exdoc
 * Given a timestamp, this function will calculate another timestamp
 * that represents the beginning of the month that the passed timestamp
 * falls into.  For instance, passing a timestamp representing January 25th 1984
 * would return a timestamp representing January 1st 1984, at 12:00am. 
 *
 * @param timestamp $timestamp The original timestamp to use when calculating.
 * @node Subsystems:DateTime
 */
function pathos_datetime_startOfMonthTimestamp($timestamp) {
	$info = getdate($timestamp);
	// Calculate the timestamp at 8am, and then subtract 8 hours, for Daylight Savings
	// Time.  If we are in those strange edge cases of DST, 12:00am can turn out to be
	// of the previous day.
	return mktime(8,0,0,$info['mon'],1,$info['year']) - 8*3600;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_datetime_endOfMonthTimestamp($timestamp) {
	$info = getdate($timestamp);
	// No month has fewer than 28 days, even in leap year, so start out at 28.
	// At most, we will loop through the while loop 3 times (29th, 30th, 31st)
	$info['mday'] = 28;
	// Keep incrementing the mday value until it is not valid, and use last valid value.
	// This should get us the last day in the month, and take into account leap years
	while (checkdate($info['mon'],$info['mday']+1,$info['year'])) $info['mday']++;
	// Calculate the timestamp at 8am, and then subtract 8 hours, for Daylight Savings
	// Time.  If we are in those strange edge cases of DST, 12:00am can turn out to be
	// of the previous day.
	return mktime(8,0,0,$info['mon'],$info['mday'],$info['year']) - 8*3600;
}

/* exdoc
 * Looks at a timestamp and returns the date of the last
 * day.  For instance, if the passed timestamp was in January,
 * this function would return 31.  Leap year is taken into account.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @node Subsystems:DateTime
 */
function pathos_datetime_endOfMonthDay($timestamp) {
	$info = getdate($timestamp);
	// No month has fewer than 28 days, even in leap year, so start out at 28.
	// At most, we will loop through the while loop 3 times (29th, 30th, 31st)
	$last = 28;
	// Keep incrementing the mday value until it is not valid, and use last valid value.
	// This should get us the last day in the month, and take into account leap years
	while (checkdate($info['mon'],$last+1,$info['year'])) $last++;
	return $last;
}

/* exdoc
 * Looks at a timestamp and returns another timestamp representing
 * 12:00:01 am of the same day.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @node Subsystems:DateTime
 */
function pathos_datetime_startOfDayTimestamp($timestamp) {
	$info = getdate($timestamp);
	// Calculate the timestamp at 8am, and then subtract 8 hours, for Daylight Savings
	// Time.  If we are in those strange edge cases of DST, 12:00am can turn out to be
	// of the previous day.
	return mktime(8,0,0,$info['mon'],$info['mday'],$info['year']) - 8*3600;
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
/* exdoc
 * Looks at a timestamp and returns another timestamp representing
 * 12:00:01 am of the Sunday of the same week.
 *
 * @param timestamp $timestamp The timestamp to check.
 * @node Subsystems:DateTime
 */
function pathos_datetime_startOfWeekTimestamp($timestamp) {
	$info = getdate($timestamp);
	// FIXME: The following line will sometimes calculate negative dates,
	// FIXME: which will not work on Windows platforms.
	$firstOfWeek = $info['mday'] - $info['wday'];
	// Calculate the timestamp at 8am, and then subtract 8 hours, for Daylight Savings
	// Time.  If we are in those strange edge cases of DST, 12:00am can turn out to be
	// of the previous day.
	return mktime(8,0,0,$info['mon'],$firstOfWeek,$info['year']) - 8*3600;
}

// Recurring Dates

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_datetime_recurringDailyDates($start,$end,$freq) {
	$dates = array();
	$curdate = $start;
	do {
		$dates[] = $curdate;
		$curdate += (86400 * $freq);
	} while ($curdate <= $end);
	return $dates;
}

/* exdoc
 * Finds all of the dates that fall within the weekly recurrence criteria
 * (namely, which weekdays) and within the $start to $end timestamp range.
 *
 * For a technical discussion of this function and the mathematics involved,
 * please see the sdk/analysis/subsystems/datetime.txt file.
 *
 * @param timestamp $start The start of the recurrence range
 * @param timestamp $end The end of the recurrence range
 * @param integer $freq Weekly frequency - 1 means every week, 2 means every
 *   other week, etc.
 * @param array $days The weekdays (in integer notation, 0 = Sunday, etc.) that
 *   should be matched.  A MWF recurrence rotation would contain the values
 *  1,3 and 5.
 * @node Subsystems:DateTime
 */
function pathos_datetime_recurringWeeklyDates($start,$end,$freq,$days) {
	// Holding array, for keeping the timestamps of applicable dates.
	// This variable will be returned to the calling scope.
	$dates = array();
	
	// Need to figure out which weekday occurs directly after the specified
	// start date.  This will be our launching point for the recurrence calculations.
	$dateinfo = getdate($start);
	
	// Finding the Start Date
	//
	// Start at the first weekday in the list ($days[$counter] where $counter is 0)
	// and go until we find a weekday greater than the weekday of the $start date.
	//
	// So, if we start on a Tuesday, and want to recur weekly for a MWF rotation,
	// This would check Monday, then Wednesday and stop, using wednesday for the
	// recacluated start date ($curdate)
	for ($counter = 0; $counter < count($days); $counter++) {
		if ($days[$counter] >= $dateinfo['wday']) {
			// exit loop, we found the weekday to use ($days[$counter])
			break;
		}
	}
	if ($days[$counter] < $dateinfo['wday']) {
		// in case we did MWF and started on a Saturday...
		$counter = 0; // reset to first day of next week
		$start += 86400 * (7-$dateinfo['wday']+$days[$counter]);
	} else if ($days[$counter] > $dateinfo['wday']) {
		// 'Normal' case, in which we started before one of the repeat days.
		$start += 86400 * ($days[$counter] - $dateinfo['wday']);
	}
	// Found start date.  Set curdate to the start date, so it gets picked
	// up in the do ... while loop.
	$curdate = $start;
	
	// Find all of the dates that match the recurrence criteria, within the
	// specified recurrence range.  Implemented as a do ... while loop because
	// we always need at least the start date, and we have already determined
	// that with the code above (the $curdate variable)
	do {
		// Append $curdate to the array of dates.  $curdate will be changed
		// at the end of the loop, to be equal to the next date meeting the
		// criteria.  If $curdate ever falls outside the recurrence range, the
		// loop will exit.
		$dates[] = $curdate;
		$curdate += 8*3600; // Add 8 hours, to avoid DST problems
		// Grab the date information (weekday, month, day, year, etc.) for
		// the current date, so we can ratchet up to the next date in the series.
		$dateinfo = getdate($curdate);
		// Get the current weekday.
		$day = $days[$counter];
		// Increment the counter so that the next time through we get the next
		// weekday.  If the counter moves off the end of the list, reset it to 0.
		$counter++;
		if ($counter >= count($days)) {
			// Went off the end of the week.  Reset the pointer to the beginning
			$counter = 0;
			// Difference in number of days between the last day in the rotation
			// and the first day (for recacluating the $curdate value)
			#$daydiff = $days[count($days)-1]-$days[0];
			
			$daydiff = 7 + $days[0] - $days[count($days)-1];
			
			if ($daydiff == 0) {
				// In case we are in a single day rotation, the difference will be 0.
				// It needs to be 7, so that we skip ahead a full week.
				$daydiff = 7;
			}
			// Increment the current date to go off to the next week, first weekday
			// in the rotation.
			$curdate += 7 * 86400 * ($freq-1) + 86400 * $daydiff; // Increment by number of weeks
		} else {
			// If we haven't gone off the end of the week, we just need to add the number
			// of days between the next weekday in the rotation ($days[$counter] - because
			// $counter was incremented above) and the $curdate weekday (store in the
			// $dateinfo array returned from the PHP call to getdate(), aboce).
			$curdate += 86400 * ($days[$counter] - $dateinfo['wday']);
		}
		// Round down to the start of the day (12:00 am) for $curdate, in case something
		// got a little out of whack thanks to DST.
		$curdate = pathos_datetime_startOfDayTimestamp($curdate);
	} while ($curdate <= $end); // If we go off the end of the recurrence range, ext.
	
	// We have no fully calculated the dates we need. Return them to the calling scope.
	return $dates;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_datetime_recurringMonthlyDates($start,$end,$freq,$by_day=false) {
	// Holding array, for keeping all of the matching timestamps
	$dates = array();
	// Date to start on.
	$curdate = $start;
	
	// Get the date info, including the weekday.
	$dateinfo = getdate($curdate);
	
	// Store the month day.  If we are not doing by day monthly recurrence,
	// then this will be used unchanged throughout the do .. while loop.
	$mdate = $dateinfo['mday'];
		
	$week = 0; // Only used for $by_day;
	$wday = 0; // Only used for $by_day;
	if ($by_day) {
		// For by day recurrence, we need to know what week it is, and what weekday.
		// (i.e. the 3rd Thursday of the month)
		
		// Calculate the Week Offset, as the ceilling value of date / DAYS_PER_WEEK
		$week = ceil($mdate / 7);
		// Store the weekday
		$wday = $dateinfo['wday'];
	}

	// Loop until we exceed the until date.
	do {
		// Append the current date to the list of dates.  $curdate will be updated
		// in the rest of the loop, so that it contains the next date.  This next date will
		// be checked in the while condition, and if it is still before the until date,
		// the loop iterates back here again for another go.
		$dates[] = $curdate;
		
		// Grab the date information for $curdate.  This gives us the current month
		// information, for the next jump.
		$dateinfo = getdate($curdate);
		
		// Make the next month's timestamp, by adding frequency to the month. 
		// PHP can pick up on the fact that the 13th month of this year is the 1st
		// month of the next year.
		#$curdate = mktime(8,0,0,$dateinfo['mon']+$freq,1,$dateinfo['year']);
		#$dateinfo = getdate($curdate);
		
		// Manually update the month and monthday.
		$dateinfo['mon'] += $freq;
		$dateinfo['mday'] = 1;
		
		if ($by_day) {
			// For by day recurrence (first tuesday of every month), we need to do a
			// little more fancy footwork to determine the next timestamp, since there
			// is no easy mathematical way to advance a whole month and land on
			// the same week offset and weekday.
			
			// Calculate the next month date.
			if ($dateinfo['wday'] > $wday) {
				// The month starts on a week day that is after the target week day.
				// For more detailed discussion of the following formula, see the
				// analysis docs, sdk/analysis/subsystems/datetime.txt
				
				// TARGET_WDAY is $wday
				// START_WDAY is $startmonthinfo['wday']
				$mdate = $wday - $dateinfo['wday'] + ( 7 * $week ) + 1;
			} else {
				// The month starts on a week day that is before or equal to the
				// target week day.  This formula is identical to the one above,
				// except that we subtract one from the week offset
				// For more detailed discussion of the following formula, see the
				// analysis docs, sdk/analysis/subsystems/datetime.txt
				
				// TARGET_WDAY is $wday
				// START_WDAY is $startmonthinfo['wday']
				$mdate = $wday - $dateinfo['wday'] + ( 7 * ( $week - 1 ) ) + 1;
			}
		}
		
		// Re-assemble the $curdate value, using the correct $mdate.  If not doing by_day
		// recurrence, this value remains essentially unchanged.  Otherwise, it will be
		// set to reflect the new day of the Nth weekday.
		$curdate = pathos_datetime_startOfDayTimestamp(mktime(8,0,0,$dateinfo['mon'],$mdate,$dateinfo['year']));
	} while ($curdate <= $end);
	
	return $dates;
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_datetime_recurringYearlyDates($start,$end,$freq) {
	return pathos_datetime_recurringMonthlyDates($start,$end,$freq*12);
}

?>