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

class phoneextension {
	function name() { return "Phone Extension"; }
	function author() { return "Jeremy Shinall"; }
	function description() { return "Stores phone numbers for the user."; }

	function modifyForm($form,$user) { // new if !isset($user->id)
	
		pathos_lang_loadDictionary('extras','phoneextension');
	
		if (!isset($user->user_phone) || $user->user_phone == null) {
			$user->user_phone = phoneextension::_blankPhone();
		}
		
		$form->register(null,"",new htmlcontrol('<hr size="1" /><b>'.TR_X_PHONEEXTENSION_HEADER.'</b>'));
		$form->register("home_phone",TR_X_PHONEEXTENSION_HOME_PHONE, new textcontrol($user->user_phone->home_phone));
		$form->register("bus_phone",TR_X_PHONEEXTENSION_BUS_PHONE, new textcontrol($user->user_phone->bus_phone));
		$form->register("other_phone",TR_X_PHONEEXTENSION_OTHER_PHONE, new textcontrol($user->user_phone->other_phone));
		
		// Define pref_contact dropdown sources
		$pref_array = array("", "Home Phone", "Business Phone", "Other Phone", "Email");
		if (!isset($user->user_phone->pref_contact)) {
			$form->register("pref_contact",TR_X_PHONEEXTENSION_PREF_CONTACT, new dropdowncontrol("", $pref_array));
		} else {
			$form->register("pref_contact",TR_X_PHONEEXTENSION_PREF_CONTACT, new dropdowncontrol($user->user_phone->pref_contact, $pref_array));
		}
		
		//Define contact_time dropdown sources
		$time_array = array("", "12am - 3am", "3am - 6am", "6am - 9am", "9am - 12pm", "12pm - 3pm", "3pm - 6pm", "6pm - 9pm", "9pm - 12am");
		if (!isset($user->user_phone->contact_time)) {
			$form->register("contact_time",TR_X_PHONEEXTENSION_CONTACT_TIME, new dropdowncontrol("", $time_array));
		} else {
			$form->register("contact_time",TR_X_PHONEEXTENSION_CONTACT_TIME, new dropdowncontrol($user->user_phone->contact_time, $time_array));
		}
		return $form;
	}
	
	function saveProfile($values,$user,$is_new) {
		global $db;
		$db->delete("user_phone","uid=".$user->id);
		
		$phone = null;
		$phone->uid = $user->id;
		$phone->home_phone = $values['home_phone'];
		$phone->bus_phone = $values['bus_phone'];
		$phone->other_phone = $values['other_phone'];
		$phone->pref_contact = $values['pref_contact'];
		$phone->contact_time = $values['contact_time'];
		
		$db->insertObject($phone,"user_phone");
		
		$user->user_phone = $phone;
		unset($user->user_phone->uid);
		return $user;
	}
	
	function getProfile($user) {
		global $db;
		if (!isset($user->id)) {
			$user->user_phone = phoneextension::_blankPhone();
		} else {
			$user->user_phone = $db->selectObject("user_phone","uid=".$user->id);
		}
		return $user;
	}
	
	function cleanup($user) {
		global $db;
		$db->delete("user_phone","uid=".$user->id);
	}
	
	function clear() {
		global $db;
		$db->delete("user_phone");
	}
	
	function hasData() {
		global $db;
		return ($db->countObjects("user_phone") != 0);
	}
	
	function _blankPhone() {
		$phone = null;
		$phone->home_phone = "";
		$phone->bus_phone = "";
		$phone->other_phone = "";
		$phone->pref_contact = "";
		$phone->contact_time = "";
		return $phone;
	}
}

?>