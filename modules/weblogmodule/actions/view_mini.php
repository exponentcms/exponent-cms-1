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
$locsql .= " AND (publish = 0 or publish <= " . time() . " or poster=" . $user_id .
	') AND (unpublish = 0 or unpublish > ' . time() . " or poster=" . $user_id . ') '; 
if ($user->is_admin || $user->is_acting_admin) {
	$where = $locsql;
} else {	
	$where = '(is_draft = 0 OR poster = '.$user_id.") AND ".$locsql;
}
if (!exponent_permissions_check('view_private',$loc)) $where .= ' AND is_private = 0';

$time = (isset($_GET['month']) ? $_GET['month'] : time());
//$time = exponent_sessions_get('month');

$month_days = exponent_datetime_monthlyDaysTimestamp($time);
$endofmonth = date('t', $time);
$posted = false;
for ($i = 0; $i < count($month_days); $i++) {
	foreach ($month_days[$i] as $mday=>$timestamp) {
		if ( ($mday > 0) && ($mday <= $endofmonth) ) {
			// Got a valid one.	 Go with it.
			$month_days[$i][$mday]['number'] = ($month_days[$i][$mday]['ts'] !=-1) ? $db->countObjects('weblog_post',$where.' AND posted >= '.$timestamp['ts'] .' AND posted < '.strtotime('+1 day',$timestamp['ts'])) : -1;
			if ($month_days[$i][$mday]['number']) $posted = true;
		}
	}
}

$info = getdate($time);
$timefirst = mktime(12,0,0,$info['mon'],1,$info['year']);
$prevmonth = mktime(0, 0, 0, date("m",$timefirst)-1, date("d",$timefirst)+10,   date("Y",$timefirst));
$nextmonth = mktime(0, 0, 0, date("m",$timefirst)+1, date("d",$timefirst)+10,   date("Y",$timefirst));
$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");

$template = new template('weblogmodule','Mini-Calendar',$loc);
//If rss is enabled tell the view to show the RSS button
if (!isset($config->enable_rss)) {$config->enable_rss = 0;}
$template->assign('enable_rss', $config->enable_rss);
$template->assign("now",$timefirst);
$template->assign("posted",$posted);

$monitoring = false;
if ($user && ($user->id!=0)) {
	$weblog_monitor = null;
	$weblog_monitor = $db->selectObject("weblog_monitor","weblog_id=".$config->id." AND user_id=".$user->id);
	if ($weblog_monitor != null) $monitoring = true;
}
$template->assign("monitoring", $monitoring);
$template->assign('logged_in', exponent_users_isLoggedIn());

$template->assign("prevmonth",$prevmonth);
$template->assign("thismonth",$timefirst);
$template->assign("nextmonth",$nextmonth);
$template->assign('days',$month_days);
$template->register_permissions(
	array('administrate'/*,'configure'*/,'post','edit','delete','comment','edit_comments','delete_comments','view_private'),
	$loc);
$template->assign('moduletitle',$title);
$template->output();

?>