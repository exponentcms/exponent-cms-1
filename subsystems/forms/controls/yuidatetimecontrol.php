<?php

##################################################
#
# Copyright (c) 2004-2008 OIC Group, Inc.
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

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
require_once(BASE."subsystems/forms/controls/formcontrol.php");

class yuidatetimecontrol extends formcontrol {
	var $showdate = true;
	var $showtime = true;
	
	function name() { return "YUI Date / Time Field"; }
	function isSimpleControl() { return false; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_TIMESTAMP);
	}
	
	function yuidatetimecontrol($default = 0, $edit_text = "", $showdate = true, $showtime = true, $display_only=false, $checked=true) {
		if (!defined("SYS_DATETIME")) include_once(BASE."subsystems/datetime.php");
		if ($default == 0) $default = time();
		$this->default = $default;
		$this->edit_text = $edit_text;
		$this->showdate = $showdate;
		$this->showtime = $showtime;
		$this->display_only = $display_only;
		$this->checked = $checked;
	}

	function toHTML($label,$name) {
		if (!$this->showdate && !$this->showtime) return "";
		$html = "<div id=\"".$name."Control\" class=\"control";
		$html .= (!empty($this->required)) ? ' required">' : '">';
		if(empty($this->flip)){
			$html .= "<span class=\"label\">".$label."</span>";
			$html .= $this->controlToHTML($name);
		} else {
			$html .= $this->controlToHTML($name);
			$html .= "<span class=\"label\">".$label."</span>";
		}
		$html .= "</div>";			
		return $html;
	}
	
	function controlToHTML($name) {
		$datectl = new yuicalendarcontrol($this->default,'',false);
		$timectl = new datetimecontrol($this->default,false);
		$datetime = date('l, F d, o g:i a', $this->default);
		$html  = '<span id="dtdisplay-'.$name.'">'.$datetime.'</span>';
		if (!$this->display_only) {
			$html .= '<input id="'.$name.'" type="checkbox" name="'.$name.'" onchange="divtoggle(\'datetime-'.$name.'\')"';
			$html .= $this->checked ? ' checked>'.$this->edit_text : '>'.$this->edit_text;
			$html .= '<div ';
			$html .= $this->checked ? 'style="display:none"': '';
			$html .= ' id="datetime-'.$name.'">';
			$html .= ($this->showdate) ? $datectl->controlToHTML($name."date") : "";
			$html .= '<div class="yuitime">';
			$html .= ($this->showtime) ? $timectl->controlToHTML($name."time") : "";
			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}
	
	function onRegister(&$form) {
		//$form->addScript('datetime_disable',PATH_RELATIVE.'subsystems/forms/controls/datetimecontrol.js');
	}
	
	function parseData($original_name,$formvalues) {
		if (!isset($formvalues[$original_name])) {
			$date = yuicalendarcontrol::parseData($original_name.'date',$formvalues);
			$time = datetimecontrol::parseData($original_name.'time',$formvalues);
			return $date + $time;
		} else return 0;
		return $time;
	}
	
	function templateFormat($db_data, $ctl) {
		/*
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
		*/
	}
	
	function form($object) {
		/*
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->showdate = true;
			$object->showtime = true;
		} 
		
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/datetimecontrol.php');
		
		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("showdate",$i18n['showdate'], new checkboxcontrol($object->showdate,false));
		$form->register("showtime",$i18n['showtime'], new checkboxcontrol($object->showtime,false));
		
		$form->register("submit","",new buttongroupcontrol($i18n['save'],"",$i18n['cancel']));
		return $form;
		*/
	}
	
	function update($values, $object) {
		/*
		if ($object == null) { 
			$object = new datetimecontrol();
			$object->default = 0; //This will force the control to always show the current time as default
		}
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/datetimecontrol.php');
			
			$post = $_POST;
			$post['_formError'] = $i18n['id_req'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->showdate = isset($values['showdate']);
		$object->showtime = isset($values['showtime']);
		return $object;
		*/
	}
}

?>
