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
 * Basic Form Control
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 * @version 0.95
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Basic Form Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class formcontrol {
	var $accesskey = "";
	var $default = "";
	var $disabled = false;
	var $tabindex = -1;

	function name() { return "formcontrol"; }
	function isSimpleControl() { return false; }
	function getFieldDefinition() { return array(); }

	function toHTML($label,$name) {
		$html = "<tr><td valign=\"top\">$label</td><td style='padding-left: 5px;' valign=\"top\">";
		$html .= $this->controlToHTML($name);
		$html .= "</td></tr>";
		return $html;
	}
	
	function controlToHTML($name) {
		return "";
	}
	
	function parseData($original_name,$formvalues) {
		return (isset($formvalues[$original_name])?$formvalues[$original_name]:"");
	}
	
	function onUnRegister(&$form) { // Do we need the explicit ref op??
		return true;
	}
	
	function onRegister(&$form) { // Do we need the explicit ref op??
		return true;
	}
	
	function templateFormat($db_data, $ctl = null) {
		return isset($db_data)?$db_data:"";
	}
}

?>
