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
 * HTML Control
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
 * HTML Control
 *
 * @package Subsystems
 * @subpackage Forms
 */class htmlcontrol extends formcontrol {
	var $html;
	
	function name() { return "Static Text"; }
	function isSimpleControl() { return true; }
	
	function htmlcontrol($html = "") {
		$this->html = $html;
	}

	function toHTML($label,$name) {
		return "<tr><td colspan=\"2\">" . $this->html . "</td></tr>";
	}
	
	function controlToHTML($name) {
		return $this->html;
	}
	
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
	
		$form = new form();
		if (!isset($object->html)) {
			$object->html = "";
		} 
		
		pathos_lang_loadDictionary('standard','core');
		
		$form->register("html",'',new htmleditorcontrol($object->html));
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new htmlcontrol();
		$object->html = preg_replace("/<br ?\/>$/","",trim($values['html']));
		$object->caption = '';
		$object->identifier = uniqid("");
		$object->is_static = 1;
		return $object;
	}
	
}

?>
