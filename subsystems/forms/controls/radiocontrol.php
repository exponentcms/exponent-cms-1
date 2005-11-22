<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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
		if ($this->default) $html .= ' checked="checked"';
		$html .= ' />';
		return $html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->groupname = "";
			$object->caption = "";
			$object->default = false;
			$object->flip = false;
		} 
		$i18n = pathos_lang_loadFile('subsystems/forms/controls/radiocontrol.php');
		
		$form->register("groupname",$i18n['groupname'],new textcontrol($object->groupname));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("default",$i18n['default'], new checkboxcontrol($object->default,false));
		$form->register("flip",$i18n['flip'], new checkboxcontrol($object->flip,false));
		
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new radiocontrol();
		if ($values['groupname'] == "") {
			$i18n = pathos_lang_loadFile('subsystems/forms/controls/radiocontrol.php');
			$post = $_POST;
			$post['_formError'] = $i18n['groupname_req'];
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
