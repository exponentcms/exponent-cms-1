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

class calendar {
	function form($object) {
		global $user;
	
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = "";
			$object->body = "";
			$object->eventdate = null;
			$object->eventdate->date = time();
			$object->eventstart = time();
			$object->eventend = time()+3600;
			$object->is_allday = 0;
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("title","Title",new textcontrol($object->title));
		$form->register("body","Body",new htmleditorcontrol($object->body));
		
		$form->register(uniqid(""),"", new htmlcontrol("<hr size='1' />"));
		
		if (!isset($object->id) || $object->is_recurring == false) {
			$form->register("eventdate","Event Date",new popupdatetimecontrol($object->eventdate->date,"",false));
		}
		$form->register("is_allday","All Day Event",new checkboxcontrol($object->is_allday,true));
		$form->register("eventstart","Start Time",new datetimecontrol($object->eventstart,false));
		$form->register("eventend","End Time",new datetimecontrol($object->eventend,false));
		
		if (!isset($object->id)) {
			$customctl = file_get_contents(BASE."modules/calendarmodule/form.part");
			$datectl = new popupdatetimecontrol($object->eventstart+365*86400,"",false);
			$customctl = str_replace("%%UNTILDATEPICKER%%",$datectl->controlToHTML("untildate"),$customctl);
			$form->register("recur","Recurrence",new customcontrol($customctl));
		} else if ($object->is_recurring) {
			// Edit applies to one or more...
			$template = new template("calendarmodule","_recur_dates");
			global $db;
			$eventdates = $db->selectObjects("eventdate","event_id=".$object->id);
			if (!defined("SYS_SORTING")) include_once(BASE."subsystems/sorting.php");
			if (!function_exists("pathos_sorting_byDateAscending")) {
				function pathos_sorting_byDateAscending($a,$b) {
					return ($a->date > $b->date ? 1 : -1);
				}
			}
			usort($eventdates,"pathos_sorting_byDateAscending");
			if (isset($object->eventdate)) $template->assign("checked_date",$object->eventdate);
			$template->assign("dates",$eventdates);
			$form->register(null,"",new htmlcontrol("<hr size='1'/>This event is a recurring event, and occurs on the dates below.  Select which dates you wish to apply these edits to."));
			$form->register(null,"",new htmlcontrol("<table cellspacing='0' cellpadding='2' width='100%'>".$template->render()."</table>"));
		}
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$object->title = $values['title'];
		
		$object->body = preg_replace("/<br ?\/>$/","",trim($values['body']));
		
		if (isset($values['is_allday'])) $object->is_allday = 1;
		#$object->eventstart = popupdatetimecontrol::parseData("eventstart",$values);
		#$object->eventend = popupdatetimecontrol::parseData("eventend",$values);
		
		$object->eventstart = datetimecontrol::parseData("eventstart",$values);
		$object->eventend = datetimecontrol::parseData("eventend",$values);
		
		if (!isset($object->id)) {
			global $user;
			$object->poster = $user->id;
			$object->posted = time();
		}
		
		pathos_forms_cleanup();
		return $object;
	}
}

?>