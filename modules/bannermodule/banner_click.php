<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

define('SCRIPT_EXP_RELATIVE','modules/bannermodule/');
define('SCRIPT_FILENAME','banner_click.php');

include_once('../../exponent.php');

// Process click
$banner = $db->selectObject('banner_ad','id='.intval($_GET['id']));
if (!defined('SYS_DATETIME')) include_once(BASE.'subsystems/datetime.php');
$start = exponent_datetime_startOfDayTimestamp(time());
$clicks = $db->selectObject('banner_click','ad_id='.$banner->id.' AND date='.$start);
if ($clicks != null) {
	$clicks->clicks++;
	$db->updateObject($clicks,'banner_click');
} else {
	$clicks->clicks = 1;
	$clicks->ad_id = $banner->id;
	$clicks->date = $start;
	$db->insertObject($clicks,'banner_click');
}

if (substr($banner->url,0,7) != 'http://') $banner->url = 'http://' . $banner->url;
header('Location: ' . $banner->url);

?>
