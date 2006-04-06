<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
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
 * Radio Control
 *
 * An HTML radio button
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
 * Radio Button Control class
 *
 * An HTML Radio Button
 *
 * @package Subsystems
 * @subpackage Forms
 */
class radiogroupcontrol extends formcontrol {
	var $flip = false;
	var $items = array();
	var $spacing = 100;
	var $cols = 1;

	
	function name() { return "Radio Button Group"; }
	
	function isSimpleControl() { return true; }
	
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>512);
	}
	
	function radiogroupcontrol($default = "", $items = array(), $flip=false, $spacing=100, $cols = 1) {
		$this->default = $default;
		$this->items = $items;
		$this->flip = $flip;
		$this->spacing = $spacing;
		$this->cols = $cols;
		$this->required = false;
	}
	
	function controlToHTML($name) {
		$html = "";
		if (@$this->required) {
			$html .= "<script language='JavaScript'>registerRG('".rawurlencode($this->caption)."')</script>";
		}
		$html .= "<table border='0' cellpadding='0' cellspacing='0'><tr>";
		$count = 0;
		foreach ($this->items as $value=>$caption) {
			$count++;
			
			if (($this->cols != 0) && ($count > $this->cols)) {
				$count = 1;
				$html.= '</tr><tr>';
			}
			$html .= '<td style="width:'.$this->spacing .'" align="'. ($this->flip?"left":"right") .'" nowrap>';
			
			if (!$this->flip) $html .= $caption.'&nbsp;';
			
			$html .= '<input type="radio" value="'.$value .'" name="' . $name . '"';
			if ($this->default == $value) $html .= ' checked="checked"';
			$html .= ' />';
			
			if ($this->flip) $html .= '&nbsp;'.$caption;
			$html .= '</td>';
		}
		
		//Add empty cells to fill out table evenly
		if (($this->cols != 0) && ($count < $this->cols)) {
			for ($x = $count; $x < $this->cols ; $x++) {
				$html .= '<td style="width:'.$this->spacing .'">&nbsp;</td>';
			}
		}
		$html .= "</tr></table>";
	
		
		return $html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			$object->flip = false;
			$object->spacing = 100;
			$object->cols = 1;
			$object->items = array();
		} 
		
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/radiogroupcontrol.php');
		
		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("items",$i18n['items'], new listbuildercontrol($object->items,null));
		$form->register("default",$i18n['default'], new textcontrol($object->default));
		$form->register("flip",$i18n['flip'], new checkboxcontrol($object->flip,false));
		$form->register("cols",$i18n['cols'], new textcontrol($object->cols,4,false,2,"integer"));
		$form->register(null,"", new htmlcontrol($i18n['spacing_msg']));
		$form->register("spacing",$i18n['spacing'], new textcontrol($object->spacing,5,false,4,"integer"));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new radiogroupcontrol();
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/radiogroupcontrol.php');
			$post = $_POST;
			$post['_formError'] = $i18n['id_req'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->items = listbuildercontrol::parseData($values,'items',true);
		$object->flip = isset($values['flip']);
		$object->cols = intval($values['cols']);
		$object->spacing = intval($values['spacing']);
		$object->required = isset($values['required']);
		
		return $object;
	}
}

?>
