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

if (!defined('EXPONENT')) exit('');

// NOTE (by Maia) - THIS DOES NOT WORK PROPERLY... it needs a redirection or an exponent_sessions_get/set

global $user;

$user_id = ($user ? $user->id : -1);

if (!defined('SYS_DATETIME')) require_once(BASE.'subsystems/datetime.php');

$config = $db->selectObject('weblogmodule_config',"location_data='".serialize($loc)."'");
if ($config == null) $config->allow_comments = 1;

// If this module has been configured to aggregate then setup the where clause to pull
// posts from the proper blogs.
$locsql = "(location_data='".serialize($loc)."'";
if (!empty($config->aggregate)) {
        $locations = unserialize($config->aggregate);
        foreach ($locations as $source) {
                $tmploc = null;
                $tmploc->mod = 'weblogmodule';
                $tmploc->src = $source;
                $tmploc->int = '';
                $locsql .= " OR location_data='".serialize($tmploc)."'";
        }
}
$locsql .= ')';

$where = '(is_draft = 0 OR poster = '.$user_id.") AND ".$locsql;
if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';

$time = (isset($_GET['month']) ? $_GET['month'] : time());
$time = exponent_sessions_get('month');

$month_days = exponent_datetime_monthlyDaysTimestamp($time);
$endofmonth = date('t', $time);
for ($i = 0; $i < count($month_days); $i++) {
	foreach ($month_days[$i] as $mday=>$timestamp) {
		if ( ($mday > 0) && ($mday <= $endofmonth) ) {
			// Got a valid one.	 Go with it.
			$month_days[$i][$mday]['number'] = ($month_days[$i][$mday]['ts'] !=-1) ? $db->countObjects('weblog_post',$where.' AND posted >= '.$timestamp['ts'] .' AND posted < '.strtotime('+1 day',$timestamp['ts'])) : -1;
		}
	}
}

$info = getdate($time);
$timefirst = mktime(12,0,0,$info['mon'],1,$info['year']);
$prevmonth = mktime(0, 0, 0, date("m",$timefirst)-1, date("d",$timefirst)+10,   date("Y",$timefirst));
$nextmonth = mktime(0, 0, 0, date("m",$timefirst)+1, date("d",$timefirst)+10,   date("Y",$timefirst));
$template = new template('weblogmodule','Mini-Calendar',$loc);
$template->assign("now",$timefirst);
$template->assign("prevmonth",$prevmonth);
$template->assign("thismonth",$timefirst);
$template->assign("nextmonth",$nextmonth);
$template->assign('days',$month_days);


?>