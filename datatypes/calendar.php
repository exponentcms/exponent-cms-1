<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
		
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','calendarmodule');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->body = '';
			$object->eventdate = null;
			$object->eventdate->id = 0;
			$object->eventdate->date = time();
			$object->eventstart = time();
			$object->eventend = time()+3600;
			$object->is_allday = 0;
			$object->is_recurring = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('title',TR_CALENDARMODULE_TITLE,new textcontrol($object->title));
		$form->register('body',TR_CALENDARMODULE_BODY,new htmleditorcontrol($object->body));
		
		$form->register(null,'', new htmlcontrol('<hr size="1" />'));
		
		if ($object->is_recurring == 1) {
			$form->register(null,'',new htmlcontrol(TR_CALENDARMODULE_RECURMOVEWARNING,false));
		}
		$form->register('eventdate',TR_CALENDARMODULE_EVENTDATE,new popupdatetimecontrol($object->eventdate->date,'',false));
		
		$cb = new checkboxcontrol($object->is_allday,true);
		$cb->jsHooks = array('onClick'=>'pathos_forms_disable_datetime(\'eventstart\',this.form,this.checked); pathos_forms_disable_datetime(\'eventend\',this.form,this.checked);');
		$form->register('is_allday',TR_CALENDARMODULE_ISALLDAY,$cb);
		$form->register('eventstart',TR_CALENDARMODULE_EVENTSTART,new datetimecontrol($object->eventstart,false));
		$form->register('eventend',TR_CALENDARMODULE_EVENTEND,new datetimecontrol($object->eventend,false));
		
		if (!isset($object->id)) {
			$customctl = file_get_contents(BASE.'modules/calendarmodule/form.part');
			$datectl = new popupdatetimecontrol($object->eventstart+365*86400,'',false);
			$customctl = str_replace('%%UNTILDATEPICKER%%',$datectl->controlToHTML('untildate'),$customctl);
			$form->register('recur',TR_CALENDARMODULE_RECURRENCE,new customcontrol($customctl));
		} else if ($object->is_recurring == 1) {
			// Edit applies to one or more...
			$template = new template('calendarmodule','_recur_dates');
			global $db;
			$eventdates = $db->selectObjects('eventdate','event_id='.$object->id);
			if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
			if (!function_exists('pathos_sorting_byDateAscending')) {
				function pathos_sorting_byDateAscending($a,$b) {
					return ($a->date > $b->date ? 1 : -1);
				}
			}
			usort($eventdates,'pathos_sorting_byDateAscending');
			if (isset($object->eventdate)) $template->assign('checked_date',$object->eventdate);
			$template->assign('dates',$eventdates);
			$form->register(null,'',new htmlcontrol('<hr size="1"/>'.TR_CALENDARMODULE_RECURRENCEWARNING));
			$form->register(null,'',new htmlcontrol('<table cellspacing="0" cellpadding="2" width="100%">'.$template->render().'</table>'));
		
			$form->meta('date_id',$object->eventdate->id); // Will be 0 if we are creating.
		}
		
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$object->title = $values['title'];
		
		$object->body = preg_replace('/<br ?\/>$/','',trim($values['body']));
		
		$object->is_allday = (isset($values['is_allday']) ? 1 : 0);
		
		$object->eventstart = datetimecontrol::parseData('eventstart',$values);
		$object->eventend = datetimecontrol::parseData('eventend',$values);
		
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