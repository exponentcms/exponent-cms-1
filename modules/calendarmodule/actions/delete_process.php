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
	
	if (!count($eventdates)) $db->delete('calendar','id='.$item->id);
	
	pathos_flow_redirect();
} else echo SITE_404_HTML;

?>