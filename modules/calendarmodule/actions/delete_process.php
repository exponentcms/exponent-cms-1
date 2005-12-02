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
 
if (!defined('PATHOS')) exit('');

$item = $db->selectObject('calendar','id='.$_POST['id']);
if ($item && $item->is_recurring == 1) {
	$eventdates = $db->selectObjectsIndexedArray('eventdate','event_id='.$item->id);
	foreach (array_keys($_POST['dates']) as $d) {
		if (isset($eventdates[$d])) {
			$db->delete('eventdate','id='.$d);
			unset($eventdates[$d]);
		}
	}
	
	if (!count($eventdates)) {
		$db->delete('calendar','id='.$item->id);
	}
	
	pathos_flow_redirect();
} else {
	echo SITE_404_HTML;
}

?>