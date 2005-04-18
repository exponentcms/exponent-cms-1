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

if (!defined('PATHOS')) exit('');

/**
 * Text Control
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
require_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * Text Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class textcontrol extends formcontrol {
	var $size = 0;
	var $maxlength = "";
	var $caption = "";
	
	function name() { return "Text Box"; }
	function isSimpleControl() { return true; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>512);
	}
	
	function textcontrol($default = "", $size = 0, $disabled = false, $maxlength = 0, $filter = "") {
		$this->default = $default;
		$this->size = $size;
		$this->disabled = $disabled;
		$this->maxlength = $maxlength;
		$this->filter = $filter;
		$this->required = false;
	}

	function controlToHTML($name) {
		$html = "<input type=\"text\" name=\"$name\" value=\"" . str_replace('"',"&quot;",$this->default) . "\" ";
		$html .= ($this->size?"size=\"".$this->size."\" ":"");
		$html .= ($this->disabled?"disabled ":"");
		$html .= ($this->maxlength?"maxlength=\"".$this->maxlength."\" ":"");
		$html .= ($this->tabindex>=0?"tabindex=\"".$this->tabindex."\" ":"");
		$html .= ($this->accesskey != ""?"accesskey=\"".$this->accesskey."\" ":"");
		if ($this->filter != "") {
			$html .= "onKeyPress=\"return ".$this->filter."_filter.on_key_press(this, event);\" ";
			$html .= "onBlur=\"".$this->filter."_filter.onBlur(this);\" ";
			$html .= "onFocus=\"".$this->filter."_filter.onFocus(this);\" ";
			$html .= "onPaste=\"return ".$this->filter."_filter.onPaste(this, event);\" ";
		}
		if (@$this->required) {
			$html .= 'required="'.rawurlencode($this->default).'" caption="'.rawurlencode($this->caption).'" ';
		}
		$html .= "/>";
		return $html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			$object->size = 0;
			$object->maxlength = 0;
			$object->required = false;
		} 
		pathos_lang_loadDictionary('standard','formcontrols');
		pathos_lang_loadDictionary('standard','core');
		
		$form->register("identifier",TR_FORMCONTROLS_IDENTIFIER,new textcontrol($object->identifier));
		$form->register("caption",TR_FORMCONTROLS_CAPTION, new textcontrol($object->caption));
		$form->register("default",TR_FORMCONTROLS_DEFAULT, new textcontrol($object->default));
		$form->register("size",TR_FORMCONTROLS_SIZE, new textcontrol((($object->size==0)?"":$object->size),4,false,3,"integer"));
		$form->register("maxlength",TR_FORMCONTROLS_MAXLENGTH, new textcontrol((($object->maxlength==0)?"":$object->maxlength),4,false,3,"integer"));
		$form->register("required",TR_FORMCONTROLS_REQUIRED, new checkboxcontrol(@$object->required,false)); 
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new textcontrol();
		if ($values['identifier'] == "") {
			pathos_lang_loadDictionary('standard','formcontrols');
			$post = $_POST;
			$post['_formError'] = TR_FORMCONTROLS_IDENTIFIER_REQUIRED;
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->size = intval($values['size']);
		$object->maxlength = intval($values['maxlength']);
		$object->required = isset($values['required']);
		return $object;
	}
	
}

?>
