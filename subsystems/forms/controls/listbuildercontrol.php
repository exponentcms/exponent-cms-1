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
 * List Builder Control
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
 * List Builder Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class listbuildercontrol extends formcontrol {
	var $source = null;
	var $size = 8;
	var $newList = false;
	
	function name() { return "List Builder"; }

	function listbuildercontrol($default,$source,$size=8) {
		if (is_array($default)) $this->default = $default;
			else $this->default = array($default);
			
			$this->size = $size;
	
		if ($source !== null) {
			if (is_array($source)) $this->source = $source;
			else $this->source = array($source);
			$this->source = array_diff_assoc($this->source,$this->default);
		} else {
			$this->newList = true;
		}
	}

	function controlToHTML($name) {
		$html = '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.implode("|!|",array_keys($this->default)).'" />';
		$html .= '<table cellpadding="9" border="0" width="30"><tr><td width="10">';
		if (!$this->newList) {
			$html .= "<select id='source_$name' size='".$this->size."'>";
			foreach ($this->source as $key=>$value) {
				$html .= "<option value='$key'>$value</option>";
			}
			$html .= "</select>";
		} else {
			$html .= "<input id='source_$name' type='text' />";
		}
		$html .= "</td>";
		$html .= "<td valign='middle' width='10'>";
		$html .= "<button onClick='addSelectedItem(&quot;$name&quot;); return false'>&gt;&gt;</button>";
		$html .= "<br /><hr size='1'/>";
		$html .= "<button onClick='removeSelectedItem(&quot;$name&quot;); return false;'>&lt;&lt;</button>";
		$html .= "</td>";
		$html .= "<td width='10' valign='top'><select id='dest_$name' size='".$this->size."'>";
		foreach ($this->default as $key=>$value) {
			$html .= "<option value='$key'>$value</option>";
		}
		$html .= "</select>";
		$html .= "</td><td width='100%'></td></tr></table>";
		$html .= "<script>newList.$name = ".($this->newList?"true":"false").";</script>";
		return $html;
	}
	
	function onRegister(&$form) {
		$form->addScript("listbuilder",PATH_RELATIVE."subsystems/forms/controls/listbuildercontrol.js");
	}
	
	function parseData($formvalues, $name, $forceindex = false) {
		$values = array();
		if ($formvalues[$name] == "") return array();
		foreach (explode("|!|",$formvalues[$name]) as $value) {
			if ($value != "") {
				if (!$forceindex) {
					$values[] = $value;
				}
				else {
					$values[$value] = $value;
				}
			}
		}
		return $values;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
		}
		
		pathos_lang_loadDictionary('standard','formcontrols');
		pathos_lang_loadDictionary('standard','core');
		
		$form->register("identifier",TR_FORMCONTROLS_IDENTIFIER,new textcontrol($object->identifier));
		$form->register("caption",TR_FORMCONTROLS_CAPTION, new textcontrol($object->caption));
		
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($values['identifier'] == "") {
			pathos_lang_loadDictionary('standard','formcontrols');
			$post = $_POST;
			$post['_formError'] = TR_FORMCONTROLS_IDENTIFIER_REQUIRED;
			pathos_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		return $object;
	}
	
	
}

?>