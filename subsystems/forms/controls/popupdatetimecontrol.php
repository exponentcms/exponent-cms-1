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

/**
 * Popup Date/Time Picker Control
 *
 * @author James Hunt
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
 * Popup Date/Time Picker Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class popupdatetimecontrol extends formcontrol {
	var $disable_text = "";
	var $showtime = true;

	function name() { return "Popup Date/Time Selector"; }
	function isSimpleControl() { return true; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_TIMESTAMP);
	}

	function popupdatetimecontrol($default = null, $disable_text = "",$showtime = true) {
		$this->disable_text = $disable_text;
		$this->default = $default;
		$this->showtime = $showtime;
		
		if ($this->default == null) {
			if ($this->disable_text == "") $this->default = time();
			else $this->disabled = true;
		}
	}

	function onRegister(&$form) {
		$form->addScript("jscal-calendar",      PATH_RELATIVE."external/jscalendar/calendar.js");
		$form->addScript("jscal-calendar-lang", PATH_RELATIVE."external/jscalendar/lang/calendar-en.js");
		$form->addScript("jscal-calendar-setup",PATH_RELATIVE."external/jscalendar/calendar-setup.js");
		$form->addScript("popupdatetimecontrol",PATH_RELATIVE."js/PopupDateTimeControl.js");
	}
	
	function controlToHTML($name) {
		$html = "";
		
		$imgsrc = PATH_RELATIVE."external/jscalendar/img.gif";
		if (is_readable(THEME_BASE."icons/calendar_trigger.gif")) {
			$imgsrc = THEME_RELATIVE."icons/calendar_trigger.gif";
		}
		
		if (is_readable(THEME_BASE."popupdatetimecontrol.css")) {
			$html .= '<style type="text/css"> @import url('.THEME_RELATIVE.'popupdatetimecontrol.css);</style>';
		} else {
			$html .= '<style type="text/css"> @import url('.PATH_RELATIVE.'external/jscalendar/default.css);</style>';
		}
		
		$default = "";
		if ($this->default != null) $default = strftime("%m/%d/%Y %H:%M",$this->default);
		
		$html .= '<input type="hidden" name="'.$name.'_hidden" id="'.$name.'_hidden" value="'.($default).'" />';
		$html .= "\n";
		$html .= '<span class="';
		if ($this->disabled) $html .= 'datefield_disabled';
		else $html .= 'datefield';
		$html .= '" id="'.$name.'_span">';
		# for testing
		#$this->default = time();
		if ($this->default == null) {
			$html .= '&lt;No Date Selected&gt;';
		} else {
			if ($this->showtime) $html .= strftime("%A, %B %d, %Y %l:%M %P",$this->default);
			else $html .= strftime("%A, %B %d, %Y",$this->default);
		}
		$html .= '</span>';
		$html .= "\n";
		$html .= '<img align="texttop" src="'.$imgsrc.'" id="'.$name.'_trigger" ';
		if ($this->disabled) {
			$html .= 'style="visibility: hidden;" ';
		} else {
			$html .= 'style="cursor: pointer;" ';
		}
		$html .= 'title="Date selector" onclick="return true;" onmouseover="this.style.background=\'red\';" onmouseout="this.style.background=\'\'" />';
		$html .= "\n";
		if ($this->disable_text != "") {// popupdatetimecontrol_enable(this.form,\''.$name.'\');
			$html .= '<input align="texttop" style="margin-top: -2px;" type="checkbox" name="'.$name.'_disabled" onChange="popupdatetimecontrol_enable(this.form,\''.$name.'\');" onClick="popupdatetimecontrol_enable(this.form,\''.$name.'\');" ';
			if ($this->disabled) $html .= ' checked';
			$html .= '/>'.$this->disable_text;
		} else {
		#	$html .= '<input type="hidden" name="'.$name.'_enabled" value="1" />';
		}
		$html .= '<script type="text/javascript">';
		$html .= "\n";
		//$html .= "var d = new Date();\nd.setTime(". ($this->default*1000) .");\nalert(d);";
		$html .= "\n";
		//$html .= 'alert(new Date().setTime('.$this->default . '));';
		//$html .= 'var d = new Date();  alert(d.getTime()); alert(d.getMilliseconds()); alert("'.time().'");';
		//$html .= "\n";
		$html .= '    Calendar.setup({';
		$html .= "\n";
		$html .= '	         inputField     :    "'.$name.'_hidden",';
		$html .= "\n";
		$html .= '                  ifFormat       :    "%m/%d/%Y %H:%M",';
		$html .= "\n";
		$html .= '                  displayArea    :    "'.$name.'_span",';
		if ($this->showtime) {
			$html .= "\n";
			$html .= '                  daFormat       :    "%A, %B %d, %Y %l:%M %P",';
			$html .= "\n";
			$html .= '                  showsTime      :    true,';
			$html .= "\n";
			$html .= '                  singleClick    :    false,';
		} else {
			$html .= "\n";
			$html .= '                  daFormat       :    "%A, %B %d, %Y",';
			$html .= "\n";
			$html .= '                  singleClick    :    true,';
		}
		$html .= "\n";
		$html .= '                  timeFormat     :    "12",';
		$html .= "\n";
		$html .= '                  button         :    "'.$name.'_trigger",';
		$html .= "\n";
		$html .= '                  align          :    "Tl",';
		if ($this->default != null) {
		//	$html .= '                  date           :    Date.parse("'.strftime("%D %T",$this->default).'"),';
			$html .= '                  date           :    new Date().setTime('.($this->default*1000).'),';
		}
		$html .= "\n";
		$html .= '                  step           :    1';
		$html .= "\n";
		$html .= '    });';
		$html .= "\n";
		$html .= '</script>';
		$html .= "\n";
		return $html;
	}
	
	function parseData($original_name,$formvalues) {
		if (!isset($formvalues[$original_name.'_disabled'])) {
			return strtotime($formvalues[$original_name.'_hidden']);
			//return $formvalues[$original_name.'_hidden'];
		} else return 0;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->showtime = true;
		} 
		
		$form->register("identifier","Identifier",new textcontrol($object->identifier));
		$form->register("caption","Caption", new textcontrol($object->caption));
		$form->register("showtime","Show Time", new checkboxcontrol($object->showtime,false));
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new popupdatetimecontrol();
		if ($values['identifier'] == "") {
			$post = $_POST;
			$post['_formError'] = "Identifier is required.";
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->showtime = isset($values['showtime']);
		return $object;
	}
	
}

?>