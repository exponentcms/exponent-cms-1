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
 * Dropdown Control
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
 * Dropdown Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class dropdowncontrol extends formcontrol {
	var $items = array();
	//var $multiple = false;
	var $size = 1;
	var $jsHooks = array();
	
	function name() { return "Drop Down List"; }
	function isSimpleControl() { return true; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>255);
	}
	
	function dropdowncontrol($default = "",$items = array()) {
		$this->default = $default;
		$this->items = $items;
	}
	
	function controlToHTML($name) {
		$html = '<select id="' . $name . '" name="' . $name . '" size="' . $this->size . '"';
		//if ($this->multiple) $html .= ' multiple';
		if ($this->disabled) $html .= ' disabled';
		if ($this->tabindex >= 0) $html .= ' tabindex="' . $this->tabindex . '"';
		foreach ($this->jsHooks as $hook=>$action) {
			$html .= " $hook=\"$action\"";
		}
		$html .= '>';
		foreach ($this->items as $value=>$caption) {
			$html .= '<option value="' . $value . '"';
			if ($value == $this->default) $html .= " selected";
			$html .= '>' . $caption . '</option>';
		}
		$html .= '</select>';
		return $html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			//$object->multiple = false;
			$object->size = 1;
			$object->items = array();
		} 
		
		$form->register("identifier","Identifier",new textcontrol($object->identifier));
		$form->register("caption","Caption", new textcontrol($object->caption));
		$form->register("items","Items", new listbuildercontrol($object->items,null));
		$form->register("default","Default", new textcontrol($object->default));
		//$form->register("multiple","Allow Multiple", new checkboxcontrol($object->multiple,false));
		$form->register("size","Size", new textcontrol($object->size,3,false,2,"integer"));
		$form->register(uniqid(""),"", new htmlcontrol("<br>*Size of 1 is a drop down control. Size greater then 1 is a list box.<br>"));
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($values['identifier'] == "") {
			$post = $_POST;
			$post['_formError'] = "Identifier is required.";
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		if ($object == null) $object = new dropdowncontrol();
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->items = listbuildercontrol::parseData($values,'items',true);
		//$object->multiple = isset($values['multiple']);
		$object->size = (intval($values['size']) <= 0)?1:intval($values['size']);
		pathos_forms_cleanup();
		return $object;
	}
}

?>
