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
class radiocontrol extends formcontrol {
	var $flip = false;
	
	function name() { return "Radio Button"; }
	
	function radiocontrol($default = false, $value = "", $groupname="radiogroup", $flip=false) {
		$this->default = $default;
		$this->groupname = $groupname;
		$this->value = $value;
		$this->flip = $flip;
	}
	
	
	function toHTML($label,$name) {
		if (!$this->flip) return parent::toHTML($label,$name);
		else {
			$html = "<tr><td valign=\"top\">&nbsp;</td><td style='padding-left: 5px;' valign=\"top\">";
			$html .= $this->controlToHTML($name);
			$html .= "&nbsp;$label</td></tr>";
			return $html;
		}
	}
	
	function controlToHTML($name) {
		$html = '<input type="radio" value="'.$this->value .'" name="' . $this->groupname . '"';
		if ($this->default) $html .= ' checked';
		$html .= ' />';
		return $html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->groupname = "";
			$object->caption = "";
			$object->default = false;
			$object->flip = false;
		} 
		pathos_lang_loadDictionary('standard','formcontrols');
		pathos_lang_loadDictionary('standard','core');
		
		$form->register("groupname",TR_FORMCONTROLS_GROUPNAME,new textcontrol($object->groupname));
		$form->register("caption",TR_FORMCONTROLS_CAPTIONVALUE, new textcontrol($object->caption));
		$form->register("default",TR_FORMCONTROLS_DEFAULT, new checkboxcontrol($object->default,false));
		$form->register("flip",TR_FORMCONTROLS_CAPTIONONRIGHT, new checkboxcontrol($object->flip,false));
		
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new radiocontrol();
		if ($values['groupname'] == "") {
			pathos_lang_loadDictionary('standard','formcontrols');
			$post = $_POST;
			$post['_formError'] = TR_FORMCONTROLS_GROUPNAME_REQUIRED;
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = uniqid("");
		$object->groupname = $values['groupname'];
		$object->caption = $values['caption'];
		$object->default = isset($values['default']);
		$object->flip = isset($values['flip']);
		return $object;
	}
}

?>
