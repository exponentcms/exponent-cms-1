<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

$item = $db->selectObject('calendar','id='.intval($_GET['id']));
if ($item) {

	if ($item->is_recurring == 1) { // need to give user options
		
		$template = new template('calendarmodule','_form_delete');
		
		$eventdate = $db->selectObject('eventdate','id='.intval($_GET['date_id']));
		$template->assign('checked_date',$eventdate);
		
		$eventdates = $db->selectObjects('eventdate','event_id='.$item->id);
		if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
		if (!function_exists('pathos_sorting_byDateAscending')) {
			function pathos_sorting_byDateAscending($a,$b) {
				return ($a->date > $b->date ? 1 : -1);
			}
		}
		usort($eventdates,'pathos_sorting_byDateAscending');
		
		$template->assign('dates',$eventdates);
		
		$template->assign('event',$item);
		
		$template->output();
		
	}  else {
		// Process a regular delete
		include(BASE.'modules/calendarmodule/delete.php');
	}
} else {
	echo SITE_404_HTML;
}

?>