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

/**
 * Contact Control
 *
 * @author James Hunt, Greg Otte
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
include_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * Contact Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class datetimecontrol extends formcontrol {
	var $showdate = true;
	var $showtime = true;
	
	function name() { return "Date / Time Field"; }
	function isSimpleControl() { return true; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_TIMESTAMP);
	}
	
	function datetimecontrol($default = 0, $showdate = true, $showtime = true) {
		if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
		if (!$default) $default = time();
		$this->default = $default;
		$this->showdate = $showdate;
		$this->showtime = $showtime;
	}

	function toHTML($label,$name) {
		if (!$this->showdate && !$this->showtime) return "";
		return parent::toHTML($label,$name);
	}
	
	function controlToHTML($name) {
		if (!$this->showdate && !$this->showtime) return "";
		$default_date = getdate($this->default);
		$hour = $default_date['hours'];
		if ($hour > 12) $hour -= 12;
		if ($hour == 0) $hour = 12;
		
		$minute = $default_date['minutes']."";
		if ($minute < 10) $minute = "0".$minute;
		$html = "<input type='hidden' id='__".$name."' name='__".$name."' value='".($this->showdate?"1":"0").($this->showtime?"1":"0")."' />";
		if ($this->showdate) {
			if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
			$html .= pathos_datetime_monthsDropdown($name . "_month",$default_date['mon']);
			$html .= '<input type="text" id="' . $name . '_day" name="' . $name . '_day" size="3" maxlength="2" value="' . $default_date['mday'] . '" />';
			$html .= '<input type="text" id="' . $name . '_year" name="' . $name . '_year" size="5" maxlength="4" value="' . $default_date['year'] . '" />';
		}
		if ($this->showtime) {
			$html .= '<input type="text" id="' . $name . '_hour" name="' . $name . '_hour" size="3" maxlength="2" value="' . $hour . '" />';
			$html .= '<input type="text" id="' . $name . '_minute" name="' . $name . '_minute" size="3" maxlength="2" value="' . $minute . '" />';
			$html .= '<select id="' . $name . '_ampm" name="' . $name . '_ampm" size="1">';
			$html .= '<option value="am"' . ($default_date['hours'] <= 12 ? " selected":"") . '>am</option>';
			$html .= '<option value="pm"' . ($default_date['hours'] <= 12 ? "":" selected") . '>pm</option>';
			$html .= '</select>';
		}
		return $html;
	}
	
	function parseData($original_name,$formvalues,$for_db = false) {
		$time = 0;
		if (isset($formvalues[$original_name."_month"])) $time = mktime(8,0,0,$formvalues[$original_name.'_month'],$formvalues[$original_name.'_day'],$formvalues[$original_name.'_year']) - 8*3600;
		
		if (isset($formvalues[$original_name."_hour"])) {
			if ($formvalues[$original_name.'_hour'] == 12 && $formvalues[$original_name.'_ampm'] == 'am') {
				$formvalues[$original_name.'_hour'] = 0;
			}
			$time += ($formvalues[$original_name.'_hour']*3600 + $formvalues[$original_name.'_minute']*60 + (12*3600*($formvalues[$original_name.'_ampm'] == 'am' ? 0 : 1)));
		}
		
		return $time;
	}
	
	function templateFormat($db_data, $ctl) {
		if ($ctl->showdate && $ctl->showtime) {
			return strftime(DISPLAY_DATETIME_FORMAT,$db_data);
		} 
		elseif ($ctl->showdate) {
			return strftime(DISPLAY_DATE_FORMAT, $db_data);
		}
		elseif ($ctl->showtime) {
			return strftime(DISPLAY_TIME_FORMAT, $db_data);
		}
		else {
			return "";
		}
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->showdate = true;
			$object->showtime = true;
		} 
		
		pathos_lang_loadDictionary('standard','formcontrols');
		pathos_lang_loadDictionary('standard','core');
		
		$form->register("identifier",TR_FORMCONTROLS_IDENTIFIER,new textcontrol($object->identifier));
		$form->register("caption",TR_FORMCONTROLS_CAPTION, new textcontrol($object->caption));
		$form->register("showdate",TR_FORMCONTROLS_SHOWDATE, new checkboxcontrol($object->showdate,false));
		$form->register("showtime",TR_FORMCONTROLS_SHOWTIME, new checkboxcontrol($object->showtime,false));
		
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,"",TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new datetimecontrol();
		if ($values['identifier'] == "") {
			pathos_lang_loadDictionary('standard','formcontrols');
			$post = $_POST;
			$post['_formError'] = TR_FORMCONTROLS_IDENTIFIER_REQUIRED;
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->showdate = isset($values['showdate']);
		$object->showtime = isset($values['showtime']);
		return $object;
	}
}

?>
