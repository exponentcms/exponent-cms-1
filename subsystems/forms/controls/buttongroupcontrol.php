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
 * Button Group Control
 *
 * A group of buttons
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
 * Button Group Control Class
 *
 * A group of buttons
 *
 * @package Subsystems
 * @subpackage Forms
 */
class buttongroupcontrol extends formcontrol {
	var $submit = "Submit";
	var $reset = "";
	var $cancel = "";
	
	var $validateJS = "";
	
	function name() { return "Button Group"; }
	
	function parseData($name, $values, $for_db = false) {
		return;
	}
	
	function buttongroupcontrol($submit = "Submit", $reset = "", $cancel = "") {
		$this->submit = $submit;
		$this->reset = $reset;
		$this->cancel = $cancel;
	}

	function toHTML($label,$name) {
		if ($this->submit . $this->reset . $this->cancel == "") return "";
		return parent::toHTML($label,$name);
	}
	
	function controlToHTML($name) {
		if ($this->submit . $this->reset . $this->cancel == "") return "";
		$html = "";
		if ($this->submit != "") {
			$html .= '<input type="submit" value="' . $this->submit . '"';
			if ($this->disabled) $html .= " disabled";
			$html .= ' onClick="if (checkRequired()) ';
			if ($this->validateJS != "") {
				$html .= '{ if (' . $this->validateJS . ') { return true; } else { return false; } }';
			} else {
				$html .= '{ return true; }';
			}
			$html .= ' else { return false; }"';
			$html .= ' />';
			
		}
		if ($this->reset != "") $html .= '<input type="reset" value="' . $this->reset . '"' . ($this->disabled?" disabled":"") . ' />';
		if ($this->cancel != "") {
			$html .= '<input type="button" value="' . $this->cancel . '"';
			$html .= ' onClick="document.location.href=\''.pathos_flow_get().'\'"';
			$html .= ' />';
		}
		return $html;
	}
	
}

?>
