<?php

##################################################
#
# Copyright (c) 2004-2007 OIC Group, Inc.
# Written and Designed by Adam Kessler
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
 * Generic HTML Input Control
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
require_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * Check Box Control class
 *
 * An HTML checkbox
 *
 * @package Subsystems
 * @subpackage Forms
 */
class genericcontrol extends formcontrol {
	var $flip = false;
	var $jsHooks = array();
	
	function name() { return "generic"; }
	function isSimpleControl() { return true; }
	
	function getFieldDefinition() { 
		return array();
	}

	function genericcontrol($type="", $default = false, $class="", $filter="", $checked=false, $required = false, $validate="", $onclick="") {
		$this->type = $type;
		$this->default = $default;
		$this->class = $class;
		$this->checked = $checked;
		$this->jsHooks = array();
		$this->filter = $filter;
		$this->required = $required;
		$this->validate = $validate;
		$this->onclick = $onclick;
		$this->size = '';
	}
	
	function toHTML($label,$name) {
		if (!$this->flip) return parent::toHTML($label,$name);
		else {
			$html = "<tr><td valign=\"top\" align=\"left\" colspan=\"2\">".$this->controlToHTML($name)."&nbsp;&nbsp;&nbsp;".$label."</td>";
			//$html .= "<td style='padding-left: 5px;' valign=\"top\">".$label."</td></tr>";
			//$html .= $this->controlToHTML($name);
			//$html .= "&nbsp;$label</td></tr>";
			return $html;
		}
	}
	
	function controlToHTML($name) {
		$html = '<input type="'.$this->type.'" id="' . $name . '" name="' . $name . '" value="'.$this->default.'"';
		if ($this->size) $html .= ' size="' . $this->size . '"';
		if ($this->checked) $html .= ' checked="checked"';
		if ($this->class != '') $html .= ' class="' . $this->class . '"';
		if ($this->tabindex >= 0) $html .= ' tabindex="' . $this->tabindex . '"';
		if ($this->accesskey != "") $html .= ' accesskey="' . $this->accesskey . '"';
		if ($this->filter != "") {
                        $html .= " onkeypress=\"return ".$this->filter."_filter.on_key_press(this, event);\" ";
                        $html .= "onblur=\"".$this->filter."_filter.onblur(this);\" ";
                        $html .= "onfocus=\"".$this->filter."_filter.onfocus(this);\" ";
                        $html .= "onpaste=\"return ".$this->filter."_filter.onpaste(this, event);\" ";
                }
		if ($this->disabled) $html .= ' disabled';
		foreach ($this->jsHooks as $type=>$val) {
			$html .= ' '.$type.'="'.$val.'"';
		}
		if (@$this->required) {
                        $html .= 'required="'.rawurlencode($this->default).'" caption="'.rawurlencode($this->caption).'" ';
                }
		if ($this->onclick != "") {
			$html .= 'onclick="'.$this->onclick.'" ';
		}
		$html .= ' />';
		return $html;
	}
	
	function parseData($name, $values, $for_db = false) {
		return isset($values[$name])?1:0;
	}
	
	function templateFormat($db_data, $ctl) {
		return ($db_data==1)?"Yes":"No";
	}
	
	function form($object) {
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/checkboxcontrol.php');
	
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = false;
			$object->flip = false;
			$object->required = false;
		} 
		
		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("default",$i18n['default'], new checkboxcontrol($object->default,false));
		$form->register("flip",$i18n['caption_right'], new checkboxcontrol($object->flip,false));
	 	$form->register(null, null, new htmlcontrol('<br />'));
                $form->register("required", $i18n['required'], new checkboxcontrol($object->required,true));
                $form->register(null, null, new htmlcontrol('<br />'));	
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new checkboxcontrol();
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/checkboxcontrol.php');
		
			$post = $_POST;
			$post['_formError'] = $i18n['id_required'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = isset($values['default']);
		$object->flip = isset($values['flip']);
		$object->required = isset($values['required']);
		return $object;
	}
	
}

?>
