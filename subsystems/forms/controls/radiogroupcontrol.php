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
 * Radio Control
 *
 * An HTML radio button
 *
 * @author Greg Otte
 * @copyright 2004 OIC Group, Inc.
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
	}
	
	function controlToHTML($name) {
		$html = "<table border='0' cellpadding='0' cellspacing='0'><tr>";
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
			if ($this->default == $value) $html .= ' checked';
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
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
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
		
		$form->register("identifier","Identifier",new textcontrol($object->identifier));
		$form->register("caption","Caption / Value", new textcontrol($object->caption));
		$form->register("items","Items", new listbuildercontrol($object->items,null));
		$form->register("default","Default", new textcontrol($object->default));
		$form->register("flip","Captions on Right", new checkboxcontrol($object->flip,false));
		$form->register("cols","Number of Columns", new textcontrol($object->cols,4,false,2,"integer"));
		$form->register(uniqid(""),"", new htmlcontrol(" *Setting Number of Columns to zero will put all items on one row.<br><br>"));
		$form->register("spacing","Column Spacing", new textcontrol($object->spacing,5,false,4,"integer"));
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new radiogroupcontrol();
		if ($values['identifier'] == "") {
			$post = $_POST;
			$post['_formError'] = "Identifier required.";
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->items = listbuildercontrol::parseData($values['items'],true);
		$object->flip = isset($values['flip']);
		$object->cols = intval($values['cols']);
		$object->spacing = intval($values['spacing']);
		pathos_forms_cleanup();
		return $object;
	}
}

?>
